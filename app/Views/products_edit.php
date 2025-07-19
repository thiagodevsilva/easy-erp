<?php
$title = "Easy ERP - Painel Inicial";
ob_start();
?>

<h1>Editar Produto</h1>

<form method="post" action="/produtos/atualizar">
    <input type="hidden" name="id" value="<?= $produto['id'] ?>">

    <div class="mb-3">
        <label>Nome</label>
        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($produto['nome']) ?>">
    </div>

    <div class="mb-3">
        <label>Preço</label>
        <input type="number" step="0.01" name="preco" class="form-control" value="<?= $produto['preco'] ?>">
    </div>

    <h5>Variações</h5>
    <?php foreach ($produto['variacoes'] as $v): ?>
        <div class="row mb-2">
            <input type="hidden" name="variacao_id[]" value="<?= $v['estoque_id'] ?>">
            <div class="col-md-5">
                <input type="text" name="variacao[]" class="form-control" value="<?= htmlspecialchars($v['variacao']) ?>">
            </div>
            <div class="col-md-3">
                <input type="number" name="quantidade[]" class="form-control" value="<?= $v['quantidade'] ?>">
            </div>
            <div class="col-md-2">
            <a href="/produtos/remover-variacao?vid=<?= $v['estoque_id'] ?>" 
                class="btn btn-danger"
                onclick="return confirm('Deseja remover esta variação?')">X</a>
            </div>
        </div>
    <?php endforeach; ?>

    <h6 class="mt-4">Adicionar Novas Variações</h6>
    <div id="novasVariacoes">
        <div class="row mb-2">
            <div class="col-md-5">
                <input type="text" name="nova_variacao[]" class="form-control" placeholder="Nova variação">
            </div>
            <div class="col-md-3">
                <input type="number" name="nova_quantidade[]" class="form-control" placeholder="Quantidade">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-secondary add-nova">+</button>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Salvar Alterações</button>
    <a href="/produtos" class="btn btn-secondary mt-3">Voltar</a>
</form>

<script>
document.querySelector('#novasVariacoes').addEventListener('click', function(e) {
    if (e.target.classList.contains('add-nova')) {
        const row = e.target.closest('.row');
        const clone = row.cloneNode(true);
        clone.querySelectorAll('input').forEach(i => i.value = '');
        row.after(clone);
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
