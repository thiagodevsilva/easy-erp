<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $usuario = $_SESSION['usuario'] ?? null;
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container">
        <a class="navbar-brand" href="/">Easy ERP</a>

        <button
            class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPath === '/' ? 'active border-bottom border-2' : '' ?>" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($currentPath, '/produtos') === 0 ? 'active border-bottom border-2' : '' ?>" href="/produtos">Produtos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($currentPath, '/cupons') === 0 ? 'active border-bottom border-2' : '' ?>" href="/cupons">Cupons</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($currentPath, '/pedidos') === 0 ? 'active border-bottom border-2' : '' ?>" href="/pedidos">Pedidos</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                <a class="nav-link position-relative <?= strpos($currentPath, '/carrinho') === 0 ? 'active border-bottom border-2' : '' ?>" href="/carrinho">
                    <i class="bi bi-cart3" style="font-size: 1.5rem;"></i>
                    <?php if (!empty($_SESSION['carrinho'])): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= array_sum(array_column($_SESSION['carrinho'], 'quantidade')) ?>
                    </span>
                    <?php endif; ?>
                </a>
                </li>
            </ul>

            <div class="nav-item ms-3">
                <a href="#" class="nav-link d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#perfilModal">
                    <?php if (!empty($usuario['foto'])): ?>
                        <img src="<?= $usuario['foto'] ?>" class="rounded-circle me-2" width="32" height="32" alt="Perfil">
                    <?php else: ?>
                        <i class="bi bi-person-circle me-2" style="font-size: 1.5rem;"></i>
                    <?php endif; ?>

                    <?php if (!empty($usuario['nome'])): ?>
                        <span><?= htmlspecialchars($usuario['nome']) ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="modal fade" id="perfilModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" enctype="multipart/form-data" action="/perfil/salvar" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Meu Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <?php if (!empty($usuario['foto'])): ?>
                    <img src="<?= $usuario['foto'] ?>" class="rounded-circle mb-3" width="80">
                <?php endif; ?>
                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Foto</label>
                    <input type="file" name="foto" class="form-control">
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="/logout" class="btn btn-outline-danger" onclick="return confirmarLogout();">Sair</a>
                </div>

                <script>
                    function confirmarLogout() {
                        <?php if (!empty($_SESSION['carrinho'])): ?>
                            return confirm('Você tem itens no carrinho. Deseja realmente sair e perder o carrinho?');
                        <?php else: ?>
                            return true;
                        <?php endif; ?>
                    }
                </script>
            </div>
        </form>
    </div>
</div>
