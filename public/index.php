<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ProductController;
use App\Controllers\OrderController;
use App\Controllers\CouponController;
use App\Controllers\WebhookController;

// Obter a URI atual
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Roteamento básico
switch ($uri) {
    case '/':
        echo "Mini ERP - Home";
        break;

    case '/produtos':
        (new ProductController())->index();
        break;

    case '/pedidos':
        (new OrderController())->index();
        break;

    case '/cupons':
        (new CouponController())->index();
        break;

    case '/webhook':
        (new WebhookController())->handle();
        break;

    default:
        http_response_code(404);
        echo "Página não encontrada!";
}
