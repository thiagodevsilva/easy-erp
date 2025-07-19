<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">

    <title><?= $title ?? 'Easy ERP' ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="container py-4">

<?php require_once __DIR__ . '/../helpers/session.php'; ?>

<?php include __DIR__ . '/partials/navbar.php'; ?>
<?php include __DIR__ . '/partials/alerts.php'; ?>

<main>
    <?= $content ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
