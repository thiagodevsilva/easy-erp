<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
<h1>Carrinho</h1>

<?php if (empty($items)): ?>
    <p>Seu carrinho está vazio.</p>
    <a href="/produtos" class="btn btn-primary">Continuar Comprando</a>
<?php else: ?>
    <form method="post" action="/carrinho/atualizar" class="mb-4">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Variação</th>
                    <th>Preço Unitário</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                foreach ($items as $key => $item): 
                    $subtotal = $item['preco'] * $item['quantidade'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nome']) ?></td>
                        <td><?= htmlspecialchars($item['variacao']) ?></td>
                        <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                        <td>
                            <input type="number" name="quantidade" value="<?= $item['quantidade'] ?>" min="1"
                                class="form-control w-50 d-inline-block">
                            <input type="hidden" name="key" value="<?= $key ?>">
                        </td>
                        <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                        <td>
                            <a href="/carrinho/remover?key=<?= $key ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Remover item do carrinho?')">Remover</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-secondary">Atualizar Quantidades</button>
    </form>

    <h4>Total: R$ <?= number_format($total, 2, ',', '.') ?></h4>
    <a href="/produtos" class="btn btn-primary">Continuar Comprando</a>
    <a href="/checkout" class="btn btn-success">Finalizar Pedido</a>
<?php endif; ?>

</body>
</html>
