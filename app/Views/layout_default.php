<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url('img/FaviconL5.svg') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
<style>
img{
    width: 25px;
    margin-right: 10px;
}
        .dataTables_filter {
            float: right;
            text-align: right;
        }
        .dataTables_paginate {
            float: right;
            text-align: right;
        }
        .dataTables_info {
            float: left;
            text-align: left;
        }
        .dataTables_wrapper:after {
            content: "";
            display: block;
            clear: both;
        }
        .dataTables_filter{
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .paginate_button{
            margin-left: 7px;
            margin-right: 7px;
        }
</style>
<script>
    $(document).ready(function() {
        $('#table').DataTable({
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "Nenhum registro encontrado",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "Nenhum dado disponível",
                "infoFiltered": "(filtrado de _MAX_ registros no total)",
                "search": "Buscar:",
                "paginate": {
                    "first": "Primeira",
                    "last": "Última",
                    "next": "Próxima",
                    "previous": "Anterior"
                }
            },
            "pageLength": 10,
            "dom": '<"top"fl>rt<"bottom"ip><"clear">'
        });
    });
</script>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">          
            <a class="navbar-brand" href="#"> <img src="<?= base_url('img/FaviconL5.svg') ?>" alt="">CRUD</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="/">Clientes</a></li>
                    <li class="nav-item"><a class="nav-link" href="/produtos">Produtos</a></li>
                    <li class="nav-item"><a class="nav-link" href="/pedidos">Pedidos</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <?= $this->renderSection('content') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>