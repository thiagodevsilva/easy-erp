<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container">
        <a class="navbar-brand" href="/">Easy ERP</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="/produtos">Produtos</a></li>
            <li class="nav-item"><a class="nav-link" href="/cupons">Cupons</a></li>
            <li class="nav-item"><a class="nav-link" href="/pedidos">Pedidos</a></li>
        </ul>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
            <a class="nav-link position-relative" href="/carrinho">
                <i class="bi bi-cart3" style="font-size: 1.5rem;"></i>
                <?php if (!empty($_SESSION['carrinho'])): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?= array_sum(array_column($_SESSION['carrinho'], 'quantidade')) ?>
                </span>
                <?php endif; ?>
            </a>
            </li>
        </ul>
        </div>
    </div>
</nav>
