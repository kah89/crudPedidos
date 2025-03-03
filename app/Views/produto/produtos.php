<?= $this->extend('layout_default') ?>
<?= $this->section('content') ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".edit-button").forEach(button => {
        button.addEventListener("click", function() {
            let id = this.getAttribute("data-id");
            document.getElementById("edit-form-edit").action = "<?= base_url('produtos/editar/') ?>" + id;
            document.getElementById("edit-id").value = id;
            document.getElementById("edit-nome").value = this.getAttribute("data-nome");
            document.getElementById("edit-descricao").value = this.getAttribute("data-descricao");
            document.getElementById("edit-preco").value = this.getAttribute("data-preco");
        });
    });
});

</script>

<div class="container mt-4">
    <h2>Produtos</h2>
    <div class="d-flex justify-content-end">
        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddProduto" aria-controls="offcanvasAddProduto">
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
    
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddProduto" aria-labelledby="offcanvasAddProdutoLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasAddProdutoLabel">Adicionar Produto</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="edit-form" action="<?= base_url('produtos/novo') ?>" method="post">
                <input type="hidden" id="edit-id" name="id">

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                </div>
                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <input type="text" class="form-control" id="descricao" name="descricao" required>
                </div>
                <div class="mb-3">
                    <label for="preco" class="form-label">Preço</label>
                    <input type="number" class="form-control" id="preco" name="preco" required>
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
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($produtos)) : ?>
            <?php foreach ($produtos as $produto) : ?>
            <tr>
                <td><?= esc($produto['id']) ?></td>
                <td><?= esc($produto['nome']) ?></td>
                <td><?= esc($produto['descricao']) ?></td>
                <td><?= esc($produto['preco']) ?></td>
                <td>
                    <button class="btn btn-warning btn-sm edit-button" data-id="<?= esc($produto['id']) ?>" data-nome="<?= esc($produto['nome']) ?>" data-descricao="<?= esc($produto['descricao']) ?>" data-preco="<?= esc($produto['preco']) ?>" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEditProduto">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>

                    <a href="<?= site_url('produtos/excluir/' . esc($produto['id'])) ?>" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
             <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="4" class="text-center">Nenhum produto encontrado.</td>
            </tr>
        <?php endif; ?>
        </tbody>
        </table>
    </div>    
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditProduto" aria-labelledby="offcanvasEditProdutoLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasEditProdutoLabel">Editar Produto</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <!-- <form action="<?= base_url('produtos/editar/') . $produto['id'] ?>" method="post">; -->
            <form id="edit-form-edit" action="<?= base_url('produtos/editar/') ?>" method="post">
                <input type="hidden" id="edit-id" name="id">
                <div class="mb-3">
                    <label for="edit-nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="edit-nome" name="nome" required>
                </div>
                <div class="mb-3">
                    <label for="edit-descricao" class="form-label">Descrição</label>
                    <input type="text" class="form-control" id="edit-descricao" name="descricao" required>
                </div>
                <div class="mb-3">
                    <label for="edit-preco" class="form-label">Preço</label>
                    <input type="number" class="form-control" id="edit-preco" name="preco" required>
                </div>
                <button type="submit" class="btn btn-success">Salvar</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>