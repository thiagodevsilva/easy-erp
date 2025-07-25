<?php
$title = "Easy ERP - Painel Inicial";
ob_start();
?>

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

<form method="get" action="/produtos" class="row mb-4">
    <div class="col-md-6">
        <input type="text" name="busca" class="form-control"
            placeholder="Buscar por nome" value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Filtrar</button>
    </div>
</form>

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
                    <div class="mb-2">
                        <?= htmlspecialchars($v['variacao']) ?> - 
                        <strong><?= $v['quantidade'] ?> em estoque</strong>
                        <?php if ($v['quantidade'] > 0): ?>
                            <form method="post" action="/carrinho/adicionar" class="d-inline ms-2">
                                <input type="hidden" name="produto_id" value="<?= $p['id'] ?>">
                                <input type="hidden" name="variacao" value="<?= $v['variacao'] ?>">
                                <input type="hidden" name="quantidade" value="1">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Comprar
                                </button>
                            </form>
                        <?php else: ?>
                            <span class="badge bg-danger ms-2">Esgotado</span>
                        <?php endif; ?>
                    </div>
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

<?php if ($totalPaginas > 1): ?>
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?= $i === (int)($_GET['pagina'] ?? 1) ? 'active' : '' ?>">
                    <a class="page-link" href="/produtos?pagina=<?= $i ?>&busca=<?= urlencode($_GET['busca'] ?? '') ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>


<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>