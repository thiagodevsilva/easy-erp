<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Pedido</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="container mt-4">
    <?php include __DIR__ . '/partials/alerts.php'; ?>

    <h1>Finalizar Pedido</h1>

    <h4>Resumo do Carrinho</h4>
    <p>Subtotal: R$ <?= number_format($subtotal, 2, ',', '.') ?></p>
    <p>Frete: R$ <?= number_format($frete, 2, ',', '.') ?></p>
    <?php if (!empty($_SESSION['cupom_aplicado'])): ?>
        <p>Desconto: -R$ <?= number_format($desconto, 2, ',', '.') ?></p>
    <?php endif; ?>
    <h3>Total: R$ <?= number_format($total, 2, ',', '.') ?></h3>

    <hr>

    <form method="post" action="/checkout/confirmar" id="checkout-form" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control" required>
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
    // Busca endereço via ViaCEP
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
</body>
</html>
