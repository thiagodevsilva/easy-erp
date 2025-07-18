<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Produtos - Mini ERP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

<h1>Produtos</h1>

<form method="post" action="/produtos/criar" class="mb-4">
    <div class="row mb-2">
        <div class="col-md-5">
            <input type="text" name="nome" class="form-control" placeholder="Nome do Produto" required>
        </div>
        <div class="col-md-3">
            <input type="number" step="0.01" name="preco" class="form-control" placeholder="Preço" required>
        </div>
    </div>

    <h5>Variações</h5>
    <div id="variacoes">
        <div class="row mb-2">
            <div class="col-md-5">
                <input type="text" name="variacao[]" class="form-control" placeholder="Ex.: P, M, G">
            </div>
            <div class="col-md-3">
                <input type="number" name="quantidade[]" class="form-control" placeholder="Quantidade" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-secondary add-variacao">+</button>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-success mt-2">Salvar Produto</button>
</form>

<script>
document.querySelector('#variacoes').addEventListener('click', function(e) {
    if (e.target.classList.contains('add-variacao')) {
        const row = e.target.closest('.row');
        const clone = row.cloneNode(true);
        clone.querySelectorAll('input').forEach(i => i.value = '');
        row.after(clone);
    }
});
</script>

<hr>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Produto</th>
            <th>Preço</th>
            <th>Variações / Estoque</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produtos as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nome']) ?></td>
                <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                <td>
                    <?php foreach ($p['variacoes'] as $v): ?>
                        <?= htmlspecialchars($v['variacao']) ?> (<?= $v['quantidade'] ?>)<br>
                    <?php endforeach; ?>
                </td>
                <td>
                <a href="/produtos/editar?id=<?= $p['id'] ?>" class="btn btn-primary btn-sm">Editar</a>
                <a href="/produtos/deletar?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
