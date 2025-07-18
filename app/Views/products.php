<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Produtos - Mini ERP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

<h1>Produtos</h1>

<form method="post" action="/produtos/criar" class="row g-3 mb-4">
    <div class="col-md-4">
        <input type="text" name="nome" class="form-control" placeholder="Nome" required>
    </div>
    <div class="col-md-2">
        <input type="number" step="0.01" name="preco" class="form-control" placeholder="Preço" required>
    </div>
    <div class="col-md-2">
        <input type="number" name="quantidade" class="form-control" placeholder="Estoque" required>
    </div>
    <div class="col-md-2">
        <input type="text" name="variacao" class="form-control" placeholder="Variação (opcional)">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-success w-100">Adicionar</button>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Preço</th>
            <th>Estoque</th>
            <th>Variação</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produtos as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['nome']) ?></td>
                <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                <td><?= $p['quantidade'] ?></td>
                <td><?= $p['variacao'] ?: '-' ?></td>
                <td>
                    <a href="/produtos/deletar?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
