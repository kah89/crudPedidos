<?= $this->extend('layout_default') ?>
<?= $this->section('content') ?>

<script>
    $(document).ready(function () {
        const $cpfCnpjAdd = $("#add-cpfCnpj");
        const $cpfCnpjEdit = $("#edit-cpfCnpj");
        const $mensagem = $("#mensagem");
        const $razaoSocialAdd = $("#add-razaoSocial");
        const $razaoSocialEdit = $("#edit-razaoSocial");

        // Aplica máscara de CPF/CNPJ
        $cpfCnpjAdd.on("input", function () { formatarCPF_CNPJ(this); });
        $cpfCnpjEdit.on("input", function () { formatarCPF_CNPJ(this); });

        // Validação ao perder o foco
        $cpfCnpjAdd.on("blur", function () {
            validarCPF_CNPJ(this, $mensagem, $razaoSocialAdd);
        });

        $cpfCnpjEdit.on("blur", function () {
            validarCPF_CNPJ(this, $mensagem, $razaoSocialEdit);
        });

        // Preenche os campos ao editar
        $(".edit-button").on("click", function () {
            $("#edit-id").val($(this).data("id"));
            $("#edit-cpfCnpj").val($(this).data("dados"));
            $("#edit-razaoSocial").val($(this).data("razao-social"));
        });

        // Limpa os campos ao abrir o modal de adição
        $("[data-bs-target='#offcanvasAddCliente']").on("click", function () {
            $("#add-cpfCnpj, #add-razaoSocial").val('');
        });

        // Validação e envio do formulário
        $("#add-form").on("submit", function (event) {
            let cpfCnpj = $cpfCnpjAdd.val().trim();
            let razaoSocial = $razaoSocialAdd.val().trim();

            // Se estiver vazio, impede o envio
            if (cpfCnpj === "" || razaoSocial === "") {
                event.preventDefault();
                $mensagem.html('<div class="alert alert-danger">Preencha todos os campos corretamente.</div>');
            } else {
                $mensagem.html(""); 
            }
        });

        function validarCPF_CNPJ(input, mensagemEl, razaoSocialEl) {
            let valor = input.value.replace(/\D/g, '');

            if (valor.length === 11 && !validarCPF(valor)) {
                mensagemEl.html('<div class="alert alert-danger">CPF inválido.</div>');
                razaoSocialEl.val("");
                return;
            } else if (valor.length === 14 && !validarCNPJ(valor)) {
                mensagemEl.html('<div class="alert alert-danger">CNPJ inválido.</div>');
                razaoSocialEl.val("");
                return;
            } else {
                mensagemEl.html(""); 
            }

            if (valor.length === 14) {
                buscarRazaoSocial(valor, razaoSocialEl);
            }
        }

        function buscarRazaoSocial(cnpj, razaoSocialEl) {
            $.ajax({
                url: `seu-backend.php?cnpj=${cnpj}`, 
                type: "GET",
                dataType: "json",
                success: function (data) {
                    if (data.nome) {
                        razaoSocialEl.val(data.nome);
                    } else {
                        $("#mensagem").html('<div class="alert alert-warning">CNPJ não encontrado.</div>');
                        razaoSocialEl.val("");
                    }
                },
                error: function () {
                    $("#mensagem").html('<div class="alert alert-danger">Erro ao buscar CNPJ.</div>');
                    razaoSocialEl.val("");
                }
            });
        }

        function validarCPF(cpf) {
            cpf = cpf.replace(/\D/g, '');
            if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;
            let soma = 0, resto;

            for (let i = 1; i <= 9; i++) soma += parseInt(cpf.charAt(i - 1)) * (11 - i);
            resto = (soma * 10) % 11;
            if (resto === 10 || resto === 11) resto = 0;
            if (resto !== parseInt(cpf.charAt(9))) return false;

            soma = 0;
            for (let i = 1; i <= 10; i++) soma += parseInt(cpf.charAt(i - 1)) * (12 - i);
            resto = (soma * 10) % 11;
            if (resto === 10 || resto === 11) resto = 0;
            return resto === parseInt(cpf.charAt(10));
        }

        function validarCNPJ(cnpj) {
            cnpj = cnpj.replace(/\D/g, '');
            if (cnpj.length !== 14) return false;

            let tamanho = cnpj.length - 2;
            let numeros = cnpj.substring(0, tamanho);
            let digitos = cnpj.substring(tamanho);
            let soma = 0;
            let pos = tamanho - 7;

            for (let i = tamanho; i >= 1; i--) {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2) pos = 9;
            }

            let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(0)) return false;

            tamanho = tamanho + 1;
            numeros = cnpj.substring(0, tamanho);
            soma = 0;
            pos = tamanho - 7;

            for (let i = tamanho; i >= 1; i--) {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2) pos = 9;
            }

            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            return resultado == digitos.charAt(1);
        }

        function formatarCPF_CNPJ(input) {
            let valor = input.value.replace(/\D/g, "");

            if (valor.length <= 11) {
                valor = valor.replace(/^(\d{3})(\d)/, "$1.$2");
                valor = valor.replace(/^(\d{3})\.(\d{3})(\d)/, "$1.$2.$3");
                valor = valor.replace(/\.(\d{3})(\d)/, ".$1-$2");
            } else {
                valor = valor.replace(/^(\d{2})(\d)/, "$1.$2");
                valor = valor.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
                valor = valor.replace(/\.(\d{3})(\d)/, ".$1/$2");
                valor = valor.replace(/(\d{4})(\d)/, "$1-$2");
            }

            input.value = valor;
        }
    });
</script>


<div class="container mt-4">
    <h2>Clientes</h2>
    <div class="d-flex justify-content-end">
        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddCliente">
            <i class="fa fa-plus"></i> Adicionar
        </button>
    </div>
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
   
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddCliente" aria-labelledby="offcanvasAddClienteLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasAddClienteLabel">Adicionar Cliente</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="add-form" action="<?= base_url('clientes/novo') ?>" method="post">
                <input type="hidden" id="add-id" name="id">
                <div class="mb-3">
                    <label for="add-cpfCnpj" class="form-label">CPF/CNPJ</label>
                    <input type="text" class="form-control" id="add-cpfCnpj" name="dados" maxlength="18" onkeyup="formatarCPF_CNPJ(this)" required>
                    <div id="mensagem"></div>
                </div>
                <div class="mb-3">
                    <label for="add-razaoSocial" class="form-label">Nome/Razão Social</label>
                    <input type="text" class="form-control" id="add-razaoSocial" name="razao_social" required>
                </div>
                <button type="submit" class="btn btn-success">Salvar</button>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table id="table" class="table table-bordered table-striped mx-auto text-center mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>CPF/CNPJ</th>
                    <th>Nome/Razão Social</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($clientes)) : ?>
                    <?php foreach ($clientes as $cliente) : ?>
                        <tr>
                            <td><?= esc($cliente['id']) ?></td>
                            <td><?= esc($cliente['dados']) ?></td>
                            <td><?= esc($cliente['razao_social']) ?></td>
                            <td>
                               <button class="btn btn-warning btn-sm edit-button" 
                                    data-id="<?= esc($cliente['id']) ?>" 
                                    data-dados="<?= esc($cliente['dados']) ?>" 
                                    data-razao-social="<?= esc($cliente['razao_social']) ?>"  
                                    data-bs-toggle="offcanvas" 
                                    data-bs-target="#offcanvasEditCliente">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </button>
                                <a href="<?= site_url('clientes/excluir/' . esc($cliente['id'])) ?>" class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="text-center">Nenhum cliente encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditCliente" aria-labelledby="offcanvasEditClienteLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasEditClienteLabel">Editar Cliente</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="edit-form" action="<?= base_url('clientes/editar') ?>" method="post">
                <input type="hidden" id="edit-id" name="id">
                <div class="mb-3">
                    <label for="edit-cpfCnpj" class="form-label">CPF/CNPJ</label>
                    <input type="text" class="form-control" id="edit-cpfCnpj" name="dados" maxlength="18" onkeyup="formatarCPF_CNPJ(this)" required>
                </div>
                <div class="mb-3">
                    <label for="edit-razaoSocial" class="form-label">Nome/Razão Social</label>
                    <input type="text" class="form-control" id="edit-razaoSocial" name="razao_social" required>
                </div>
                <button type="submit" class="btn btn-success">Salvar</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>