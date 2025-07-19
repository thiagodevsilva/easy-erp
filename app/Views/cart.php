<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <?php include __DIR__ . '/partials/alerts.php'; ?>

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
                <?php foreach ($items as $key => $item): 
                    $linhaSubtotal = $item['preco'] * $item['quantidade'];
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nome']) ?></td>
                        <td><?= htmlspecialchars($item['variacao']) ?></td>
                        <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                        <td>
                            <input type="number" name="quantidade[<?= $key ?>]" value="<?= $item['quantidade'] ?>" min="1"
                                class="form-control w-50 d-inline-block">
                        </td>
                        <td>R$ <?= number_format($linhaSubtotal, 2, ',', '.') ?></td>
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

        <a href="/produtos" class="btn btn-primary">Continuar Comprando</a>

        <hr>

        <h4>Resumo</h4>
        <p>Subtotal: R$ <?= number_format($subtotal, 2, ',', '.') ?></p>
        <p>Frete: R$ <?= number_format($frete, 2, ',', '.') ?></p>

        <?php if (!empty($_SESSION['cupom_aplicado'])): ?>
            <p>
                Desconto (<?= htmlspecialchars($_SESSION['cupom_aplicado']['codigo']) ?>): 
                - R$ <?= number_format($_SESSION['cupom_aplicado']['desconto'], 2, ',', '.') ?>
                <a href="/carrinho/remover-cupom" class="btn btn-sm btn-outline-danger ms-2">Remover</a>
            </p>
        <?php else: ?>
            <form method="post" action="/carrinho/aplicar-cupom" class="d-flex mb-3">
                <input type="text" name="codigo" class="form-control w-25 me-2" placeholder="Código do Cupom">
                <button type="submit" class="btn btn-outline-primary">Aplicar Cupom</button>
            </form>
        <?php endif; ?>

        <hr>

        <h3>Total: R$ <?= number_format($subtotal + $frete - $desconto, 2, ',', '.') ?></h3>

        <hr>

        <a href="/checkout" class="btn btn-success">Finalizar Pedido</a>

        <?php if (!empty($_SESSION['mensagem'])): ?>
            <div class="alert alert-info mt-3"><?= $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></div>
        <?php endif; ?>

    <?php endif; ?>

</body>
</html>
