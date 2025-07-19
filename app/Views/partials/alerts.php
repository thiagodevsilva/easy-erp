<?php if (!empty($_SESSION['mensagem'])): ?>
    <div class="alert alert-info text-center mt-3">
        <?= htmlspecialchars($_SESSION['mensagem']); ?>
    </div>
    <?php unset($_SESSION['mensagem']); ?>
<?php endif; ?>
