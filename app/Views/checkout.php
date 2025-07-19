<?php
$title = "Finalizar Pedido";
ob_start();
?>

<h1>Finalizar Pedido</h1>

<h4>Resumo do Carrinho</h4>
<p>Subtotal: R$ <?= number_format($subtotal, 2, ',', '.') ?></p>
<p>Frete: R$ <?= number_format($frete, 2, ',', '.') ?></p>

<?php if (!empty($_SESSION['cupom_aplicado'])): ?>
    <p>Desconto: -R$ <?= number_format($desconto, 2, ',', '.') ?></p>
<?php endif; ?>

<h3>Total: R$ <?= number_format($total, 2, ',', '.') ?></h3>

<hr>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$usuario = $_SESSION['usuario'] ?? [];
?>

<form method="post" action="/checkout/confirmar" id="checkout-form" class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control"
            value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control"
            value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">CEP</label>
        <input type="text" name="cep" id="cep" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Rua</label>
        <input type="text" name="rua" id="rua" class="form-control" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Número</label>
        <input type="text" name="numero" class="form-control" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Bairro</label>
        <input type="text" name="bairro" id="bairro" class="form-control" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Cidade</label>
        <input type="text" name="cidade" id="cidade" class="form-control" required>
    </div>
    
    <div class="col-md-4">
        <label class="form-label">Estado</label>
        <input type="text" name="estado" id="estado" class="form-control" required>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-success">Finalizar Pedido</button>
        <a href="/carrinho" class="btn btn-secondary">Voltar</a>
    </div>
</form>

<script>
$('#cep').on('blur', function() {
    const cep = $(this).val().replace(/\D/g, '');
    if (cep.length !== 8) return;

    $.getJSON(`https://viacep.com.br/ws/${cep}/json/`, function(data) {
        if (!('erro' in data)) {
            $('#rua').val(data.logradouro);
            $('#bairro').val(data.bairro);
            $('#cidade').val(data.localidade);
            $('#estado').val(data.uf);
        } else {
            alert('CEP não encontrado!');
        }
    });
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>