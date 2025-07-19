<?php
$title = "Easy ERP - Painel Inicial";
ob_start();
?>

<h1 class="mb-4 text-center">Bem-vindo ao Easy ERP</h1>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow text-center">
            <div class="card-body">
                <h4 class="card-title">Produtos</h4>
                <p class="card-text">Gerencie seus produtos, variações e estoque.</p>
                <a href="/produtos" class="btn btn-primary">Acessar</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow text-center">
            <div class="card-body">
                <h4 class="card-title">Cupons</h4>
                <p class="card-text">Crie e gerencie cupons de desconto.</p>
                <a href="/cupons" class="btn btn-primary">Acessar</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow text-center">
            <div class="card-body">
                <h4 class="card-title">Carrinho</h4>
                <p class="card-text">Veja os itens adicionados e finalize pedidos.</p>
                <a href="/carrinho" class="btn btn-primary">Acessar</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-4">
    <div class="col-md-12 text-center">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title">Checkout</h4>
                <p class="card-text">Inicie o processo de finalização do pedido.</p>
                <a href="/checkout" class="btn btn-success">Ir para Checkout</a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>