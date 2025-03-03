<?= $this->extend('layout_default') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Pedidos</h2>
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
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddPedido" aria-controls="offcanvasAddPedido">
                <i class="fa fa-plus"></i> Adicionar
            </button>
        </div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddPedido" aria-labelledby="offcanvasAddPedidoLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasAddPedidoLabel">Adicionar Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form method="POST" action="<?= base_url('pedidos/novo') ?>">
                    <div class="mb-3">
                        <label for="cliente_id" class="form-label">Cliente</label>
                        <select class="form-select" id="cliente_id" name="cliente_id" required>
                            <option value="">Selecione um cliente</option>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?= $cliente['id'] ?>"><?= $cliente['razao_social'] ?> (<?= $cliente['dados'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="produto_id" class="form-label">Produto</label>
                        <select class="form-select" id="produto_id" name="produto_id[]" multiple required>
                            <?php foreach ($produtos as $produto): ?>
                                <option value="<?= $produto['id'] ?>" data-preco="<?= $produto['preco'] ?>">
                                    <?= $produto['nome'] ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Em Aberto">Em Aberto</option>
                            <option value="Pago">Pago</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Comprar</button>
                </form>

            </div>
        </div>

    <div class="table-responsive">
        <table id="table" class="table table-bordered table-striped mx-auto text-center mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th> 
                <th>Pedidos</th>
                <th>total</th>
                <th>status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $pedido): ?>
                <tr data-produtos='<?= htmlspecialchars(json_encode($pedido['produtos'])) ?>'>
                    <td><?= $pedido['id'] ?></td>
                    <td><?= $pedido['razao_social'] ?></td>
                    <td>
                        <?php if (!empty($pedido['produtos'])): ?>
                            <?php foreach ($pedido['produtos'] as $produto): ?>
                                <?= $produto['nome'] ?> 
                            <?php endforeach; ?>
                        <?php else: ?>
                            Nenhum produto
                        <?php endif; ?>
                    </td>
                    <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                    <td><?= $pedido['status'] ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEditPedido<?= $pedido['id'] ?>">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </button>
                        <form method="GET" action="<?= base_url('pedidos/excluir/' . $pedido['id']) ?>" class="d-inline">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>    
    <?php foreach ($pedidos as $pedido): ?>
    <!-- Offcanvas de Edição -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditPedido<?= $pedido['id'] ?>" aria-labelledby="offcanvasEditPedidoLabel<?= $pedido['id'] ?>">
        <div class="offcanvas-header">
            <h5 id="offcanvasEditPedidoLabel<?= $pedido['id'] ?>">Editar Pedido</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form method="POST" action="<?= base_url('pedidos/editar/' . $pedido['id']) ?>">
                <div class="mb-3">
                    <label for="cliente_id" class="form-label">Cliente</label>
                    <select class="form-select" id="cliente_id" name="cliente_id" required>
                        <option value="">Selecione um cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= $cliente['id'] ?>" <?= ($cliente['id'] == $pedido['cliente_id']) ? 'selected' : '' ?>>
                                <?= $cliente['razao_social'] ?> (<?= $cliente['dados'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="produto_id" class="form-label">Produto</label>
                    <?php  $produtosSelecionados = isset($pedido['produtos']) ? array_column($pedido['produtos'], 'produto_id') : []; ?>
                    <select class="form-select" id="produto_id" name="produto_id[]" multiple required>
                        <?php foreach ($produtos as $produto): ?>
                            <option value="<?= $produto['id'] ?>" data-preco="<?= $produto['preco'] ?>"
                                <?= in_array($produto['id'], $produtosSelecionados) ? 'selected' : '' ?>>
                                <?= $produto['nome'] ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Em Aberto" <?= ($pedido['status'] == 'Em Aberto') ? 'selected' : '' ?>>Em Aberto</option>
                        <option value="Pago" <?= ($pedido['status'] == 'Pago') ? 'selected' : '' ?>>Pago</option>
                        <option value="Cancelado" <?= ($pedido['status'] == 'Cancelado') ? 'selected' : '' ?>>Cancelado</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Salvar Alterações</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?= $this->endSection() ?>