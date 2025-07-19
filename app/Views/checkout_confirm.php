<?php
$title = "Easy ERP - Painel Inicial";
ob_start();
?>


<h1>Confirmar Pedido</h1>

<h4>Dados do Cliente</h4>
<ul>
    <li><strong>Nome:</strong> <?= htmlspecialchars($cliente['nome']) ?></li>
    <li><strong>E-mail:</strong> <?= htmlspecialchars($cliente['email']) ?></li>
    <li><strong>Endereço:</strong> <?= htmlspecialchars("{$cliente['rua']}, {$cliente['numero']} - {$cliente['bairro']} - {$cliente['cidade']}/{$cliente['estado']} - CEP {$cliente['cep']}") ?></li>
</ul>

<h4>Resumo do Pedido</h4>
<ul>
    <?php foreach ($items as $item): ?>
        <li><?= htmlspecialchars($item['nome']) ?> (<?= htmlspecialchars($item['variacao']) ?>) - 
            <?= $item['quantidade'] ?> x R$ <?= number_format($item['preco'], 2, ',', '.') ?></li>
    <?php endforeach; ?>
</ul>

<p>Subtotal: R$ <?= number_format($subtotal, 2, ',', '.') ?></p>
<p>Frete: R$ <?= number_format($frete, 2, ',', '.') ?></p>
<?php if ($desconto > 0): ?>
    <p>Desconto: -R$ <?= number_format($desconto, 2, ',', '.') ?></p>
<?php endif; ?>
<h3>Total: R$ <?= number_format($total, 2, ',', '.') ?></h3>

<form method="post" action="/checkout/finalizar">
    <?php foreach ($cliente as $k => $v): ?>
        <input type="hidden" name="<?= $k ?>" value="<?= htmlspecialchars($v) ?>">
    <?php endforeach; ?>
    <button type="submit" class="btn btn-success">Confirmar Pedido</button>
    <a href="/checkout" class="btn btn-secondary">Voltar</a>
</form>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
