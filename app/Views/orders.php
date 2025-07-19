<?php
$title = "Lista de Pedidos";
ob_start();
?>

<h1 class="mb-4">Pedidos</h1>

<?php if (empty($pedidos)): ?>
    <p>Nenhum pedido encontrado.</p>
<?php else: ?>

    <form method="get" action="/pedidos" class="row mb-4 g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">Todos</option>
                <option value="pendente" <?= ($_GET['status'] ?? '') === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                <option value="pago" <?= ($_GET['status'] ?? '') === 'pago' ? 'selected' : '' ?>>Pago</option>
                <option value="cancelado" <?= ($_GET['status'] ?? '') === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Data Inicial</label>
            <input type="date" name="data_inicio" class="form-control"
                value="<?= htmlspecialchars($_GET['data_inicio'] ?? '') ?>">
        </div>

        <div class="col-md-3">
            <label class="form-label">Data Final</label>
            <input type="date" name="data_fim" class="form-control"
                value="<?= htmlspecialchars($_GET['data_fim'] ?? '') ?>">
        </div>

        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
    </form>


    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Email</th>
                    <th>Total</th>
                    <th>Frete</th>
                    <th>Status</th>
                    <th>Criado em</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <?php
                        // Define cor baseada no status
                        $classe = '';
                        switch ($pedido['status']) {
                            case 'pendente':
                                $classe = 'table-warning'; // Amarelo
                                break;
                            case 'pago':
                                $classe = 'table-success'; // Verde
                                break;
                            case 'cancelado':
                                $classe = 'table-secondary'; // Preto com texto branco
                                break;
                        }
                    ?>
                    <tr class="<?= $classe ?>">
                        <td><?= $pedido['id'] ?></td>
                        <td><?= htmlspecialchars($pedido['nome_cliente']) ?></td>
                        <td><?= htmlspecialchars($pedido['email_cliente']) ?></td>
                        <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                        <td>R$ <?= number_format($pedido['frete'], 2, ',', '.') ?></td>
                        <td><?= ucfirst($pedido['status']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($pedido['criado_em'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPaginas > 1): ?>
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item <?= $i === (int)($_GET['pagina'] ?? 1) ? 'active' : '' ?>">
                        <a class="page-link"
                        href="/pedidos?pagina=<?= $i ?>&status=<?= urlencode($_GET['status'] ?? '') ?>&data_inicio=<?= urlencode($_GET['data_inicio'] ?? '') ?>&data_fim=<?= urlencode($_GET['data_fim'] ?? '') ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
    
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
