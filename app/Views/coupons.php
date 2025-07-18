<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cupons - Mini ERP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

<h1>Gerenciar Cupons</h1>

<form method="post" action="/cupons/criar" class="row g-3 mb-4">
    <div class="col-md-3">
        <input type="text" name="codigo" class="form-control" placeholder="Código" required>
    </div>
    <div class="col-md-2">
        <input type="number" step="0.01" name="desconto" class="form-control" placeholder="Desconto (R$)" required>
    </div>
    <div class="col-md-2">
        <input type="number" step="0.01" name="minimo" class="form-control" placeholder="Mínimo (R$)" required>
    </div>
    <div class="col-md-3">
        <input type="date" name="valido_ate" class="form-control" required>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-success w-100">Criar</button>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Código</th>
            <th>Desconto</th>
            <th>Mínimo</th>
            <th>Validade</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cupons as $c): 
            $valido = strtotime($c['valido_ate']) >= time();
        ?>
            <tr>
                <td><?= htmlspecialchars($c['codigo']) ?></td>
                <td>R$ <?= number_format($c['desconto'], 2, ',', '.') ?></td>
                <td>R$ <?= number_format($c['minimo'], 2, ',', '.') ?></td>
                <td><?= date('d/m/Y', strtotime($c['valido_ate'])) ?></td>
                <td><?= $valido ? '<span class="text-success">Válido</span>' : '<span class="text-danger">Expirado</span>' ?></td>
                <td>
                    <a href="/cupons/deletar?id=<?= $c['id'] ?>" class="btn btn-danger btn-sm"
                        onclick="return confirm('Tem certeza que deseja excluir este cupom?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="/" class="btn btn-secondary mt-3">Voltar</a>

</body>
</html>
