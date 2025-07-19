<?php
$title = "Lista de Pedidos";
ob_start();
?>

<h1 class="mb-4">Pedidos</h1>

<?php if (empty($pedidos)): ?>
    <p>Nenhum pedido encontrado.</p>
<?php else: ?>
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
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
