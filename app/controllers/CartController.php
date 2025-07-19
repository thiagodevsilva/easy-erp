<?php
namespace App\Controllers;

use App\Database;
use App\Models\Pedido;
use App\Helpers\Mailer;

/**
 * Controller para gerenciar o Carrinho de Compras.
 */
class CartController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
    }

    /**
     * Exibe o carrinho com subtotal, frete, desconto e total calculados.
     */
    public function index(): void
    {
        $items = $_SESSION['carrinho'];

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }

        $frete = $this->calcularFrete($subtotal);

        $desconto = !empty($_SESSION['cupom_aplicado'])
            ? $_SESSION['cupom_aplicado']['desconto']
            : 0;

        $valorFinal = max(0, $subtotal + $frete - $desconto);

        // Garante que as variáveis existem mesmo com carrinho vazio
        $items = $items ?? [];
        $subtotal = $subtotal ?? 0;
        $frete = $frete ?? 0;
        $desconto = $desconto ?? 0;
        $valorFinal = $valorFinal ?? 0;

        include __DIR__ . '/../views/cart.php';
    }

    /**
     * Adiciona um item ao carrinho.
     */
    public function add(): void
    {
        $produtoId = (int) ($_POST['produto_id'] ?? 0);
        $variacao = $_POST['variacao'] ?? '';
        $quantidade = max(1, (int) ($_POST['quantidade'] ?? 1));

        if (!$produtoId) {
            header("Location: /produtos");
            exit;
        }

        $key = $produtoId . '-' . $variacao;

        if (isset($_SESSION['carrinho'][$key])) {
            $_SESSION['carrinho'][$key]['quantidade'] += $quantidade;
        } else {
            $pdo = Database::getConnection();
            $sql = "SELECT p.id, p.nome, p.preco, e.variacao 
                    FROM produtos p
                    LEFT JOIN estoque e ON e.produto_id = p.id AND e.variacao = ?
                    WHERE p.id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$variacao, $produtoId]);
            $produto = $stmt->fetch();

            if ($produto) {
                $_SESSION['carrinho'][$key] = [
                    'produto_id' => $produto['id'],
                    'nome' => $produto['nome'],
                    'variacao' => $produto['variacao'] ?? 'Padrão',
                    'preco' => $produto['preco'],
                    'quantidade' => $quantidade
                ];
            }
        }

        header("Location: /carrinho");
    }

    /**
     * Atualiza as quantidades dos itens no carrinho.
     */
    public function update(): void
    {
        if (!empty($_POST['quantidade'])) {
            foreach ($_POST['quantidade'] as $key => $qtd) {
                if (isset($_SESSION['carrinho'][$key])) {
                    $_SESSION['carrinho'][$key]['quantidade'] = max(1, (int) $qtd);
                }
            }
        }
        header("Location: /carrinho");
    }

    /**
     * Remove um item do carrinho.
     */
    public function remove(): void
    {
        $key = $_GET['key'] ?? '';
        unset($_SESSION['carrinho'][$key]);
        header("Location: /carrinho");
    }

    /**
     * Calcula o frete baseado no subtotal.
     */
    private function calcularFrete(float $subtotal): float
    {
        if ($subtotal >= 200) {
            return 0;
        } elseif ($subtotal >= 52 && $subtotal <= 166.59) {
            return 15;
        } else {
            return 20;
        }
    }

    /**
     * Aplica um cupom de desconto.
     */
    public function aplicarCupom(): void
    {
        $codigo = strtoupper(trim($_POST['codigo'] ?? ''));

        if (!$codigo) {
            $_SESSION['mensagem'] = "Informe um código de cupom.";
            header("Location: /carrinho");
            return;
        }

        $pdo = \App\Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM cupons WHERE codigo = ?");
        $stmt->execute([$codigo]);
        $cupom = $stmt->fetch();

        $subtotal = 0;
        foreach ($_SESSION['carrinho'] as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }

        if (!$cupom) {
            $_SESSION['mensagem'] = "Cupom inválido.";
        } elseif (strtotime($cupom['valido_ate']) < time()) {
            $_SESSION['mensagem'] = "Cupom expirado.";
        } elseif ($subtotal < $cupom['minimo']) {
            $_SESSION['mensagem'] = "Subtotal insuficiente para este cupom.";
        } else {
            $_SESSION['cupom_aplicado'] = [
                'codigo' => $cupom['codigo'],
                'desconto' => $cupom['desconto']
            ];
            $_SESSION['mensagem'] = "Cupom aplicado: {$cupom['codigo']}";
        }

        header("Location: /carrinho");
    }

    /**
     * Remove o cupom aplicado.
     */
    public function removerCupom(): void
    {
        unset($_SESSION['cupom_aplicado']);
        $_SESSION['mensagem'] = "Cupom removido.";
        header("Location: /carrinho");
    }

    /**
     * Exibe a tela de finalização do pedido (Checkout).
     *
     * - Mostra o resumo do carrinho (subtotal, frete, desconto e total final).
     * - Exibe o formulário para dados do cliente (nome, e-mail, endereço).
     * - Integra com a API do ViaCEP para preencher automaticamente endereço pelo CEP.
     *
     * @return void
     */
    public function checkout(): void
    {
        $items = $_SESSION['carrinho'] ?? [];

        if (empty($items)) {
            $_SESSION['mensagem'] = "Adicione itens ao carrinho antes de finalizar.";
            header("Location: /carrinho");
            exit;
        }

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }

        $frete = $this->calcularFrete($subtotal);
        $desconto = !empty($_SESSION['cupom_aplicado']) ? $_SESSION['cupom_aplicado']['desconto'] : 0;

        $total = max(0, $subtotal + $frete - $desconto);

        include __DIR__ . '/../views/checkout.php';
    }

    public function confirmarCheckout(): void
    {

        // die('confirmarCheckout foi chamado');
        $items = $_SESSION['carrinho'] ?? [];
        if (empty($items)) {
            header("Location: /carrinho");
            return;
        }

        $cliente = [
            'nome' => $_POST['nome'] ?? '',
            'email' => $_POST['email'] ?? '',
            'cep' => $_POST['cep'] ?? '',
            'rua' => $_POST['rua'] ?? '',
            'numero' => $_POST['numero'] ?? '',
            'bairro' => $_POST['bairro'] ?? '',
            'cidade' => $_POST['cidade'] ?? '',
            'estado' => $_POST['estado'] ?? ''
        ];

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }
        $frete = $this->calcularFrete($subtotal);
        $desconto = !empty($_SESSION['cupom_aplicado']) ? $_SESSION['cupom_aplicado']['desconto'] : 0;
        $total = max(0, $subtotal + $frete - $desconto);

        include __DIR__ . '/../views/checkout_confirm.php';
    }

    public function finalizarPedido(): void
    {
        $items = $_SESSION['carrinho'] ?? [];
        if (empty($items)) {
            header("Location: /carrinho");
            return;
        }

        $cliente = [
            'nome' => $_POST['nome'] ?? '',
            'email' => $_POST['email'] ?? '',
            'cep' => $_POST['cep'] ?? '',
            'rua' => $_POST['rua'] ?? '',
            'numero' => $_POST['numero'] ?? '',
            'bairro' => $_POST['bairro'] ?? '',
            'cidade' => $_POST['cidade'] ?? '',
            'estado' => $_POST['estado'] ?? ''
        ];

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }
        $frete = $this->calcularFrete($subtotal);
        $desconto = !empty($_SESSION['cupom_aplicado']) ? $_SESSION['cupom_aplicado']['desconto'] : 0;
        $total = max(0, $subtotal + $frete - $desconto);

        $pedido = new Pedido();
        $pedidoId = $pedido->criar($cliente, compact('subtotal','frete','desconto','total'), $items);

        if ($pedidoId) {
            $_SESSION['carrinho'] = [];
            unset($_SESSION['cupom_aplicado']);
            $_SESSION['mensagem'] = "Pedido #{$pedidoId} criado com sucesso!";
            header("Location: /produtos");

            if ($pedidoId) {
                // Monta corpo do e-mail
                $itensLista = '';
                foreach ($items as $item) {
                    $itensLista .= "<li>{$item['nome']} ({$item['variacao']}) - {$item['quantidade']} x R$ " .
                                    number_format($item['preco'], 2, ',', '.') . "</li>";
                }
            
                $mensagem = "
                    <h2>Obrigado pelo seu pedido!</h2>
                    <p>Seu pedido <strong>#{$pedidoId}</strong> foi recebido com sucesso.</p>
                    <p><strong>Itens:</strong></p>
                    <ul>{$itensLista}</ul>
                    <p><strong>Total:</strong> R$ " . number_format($total, 2, ',', '.') . "</p>
                    <p><strong>Endereço de entrega:</strong> {$cliente['rua']}, {$cliente['numero']} - {$cliente['bairro']} - {$cliente['cidade']}/{$cliente['estado']} - CEP {$cliente['cep']}</p>
                ";
            
                $mailer = new Mailer();
                $mailer->enviar($cliente['email'], "Confirmação do Pedido #{$pedidoId}", $mensagem);
            
                $_SESSION['carrinho'] = [];
                unset($_SESSION['cupom_aplicado']);
                $_SESSION['mensagem'] = "Pedido #{$pedidoId} criado com sucesso! Um e-mail foi enviado.";
                header("Location: /produtos");
            } else {
                $_SESSION['mensagem'] = "Erro ao salvar o pedido. Tente novamente.";
                header("Location: /checkout");
            }
        } else {
            $_SESSION['mensagem'] = "Erro ao salvar o pedido. Tente novamente.";
            header("Location: /checkout");
        }
    }
}
