<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Helpers\Env;
Env::load(__DIR__ . '/../.env');

use App\Controllers\ProductController;
use App\Controllers\OrderController;
use App\Controllers\CouponController;
use App\Controllers\WebhookController;
use App\Controllers\CartController;
use App\Controllers\UserController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/':
        include __DIR__ . '/../app/views/home.php';
        break;

    case '/produtos':
        (new ProductController())->index();
        break;

    case '/produtos/criar':
        (new ProductController())->store();
        break;
    
    case '/produtos/atualizar':
        (new ProductController())->update();
        break;

    case '/produtos/editar':
        (new ProductController())->edit();
        break;
    
    case '/produtos/atualizar':
        (new ProductController())->update();
        break;
    
    case '/produtos/remover-variacao':
        (new ProductController())->deleteVariation();
        break;
    
    case '/produtos/deletar':
        (new ProductController())->destroy();
        break;

    case '/pedidos':
        (new OrderController())->index();
        break;

    case '/cupons':
        (new CouponController())->index();
        break;
    
    case '/cupons/criar':
        (new CouponController())->store();
        break;
    
    case '/cupons/deletar':
        (new CouponController())->destroy();
        break;

    case '/carrinho':
        (new CartController())->index();
        break;
    
    case '/carrinho/adicionar':
        (new CartController())->add();
        break;
    
    case '/carrinho/atualizar':
        (new CartController())->update();
        break;
    
    case '/carrinho/remover':
        (new CartController())->remove();
        break;

    case '/carrinho/aplicar-cupom':
        (new CartController())->aplicarCupom();
        break;
    
    case '/carrinho/remover-cupom':
        (new CartController())->removerCupom();
        break;

    case '/webhook':
        (new WebhookController())->handle();
        break;

    case '/checkout':
        (new CartController())->checkout();
        break;

    case '/checkout/confirmar':
        (new CartController())->confirmarCheckout();
        break;
    
    case '/checkout/finalizar':
        (new CartController())->finalizarPedido();
        break;

    case '/perfil/salvar':
        (new UserController())->salvarPerfil();
        break;
    
    case '/logout':
        session_start();
        session_destroy();
        header("Location: /");
        exit;

    default:
        http_response_code(404);
        echo "Página não encontrada!";
}
