<?php
namespace App\Controllers;

class UserController
{
    public function salvarPerfil(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $uploadDir = __DIR__ . '/../../public/uploads';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $fotoPath = $_SESSION['usuario']['foto'] ?? null;

        if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('user_') . '.' . $ext;
            $destino = $uploadDir . '/' . $fileName;
        
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                $fotoPath = '/uploads/' . $fileName;
            }
        }

        $_SESSION['usuario'] = [
            'nome' => $nome,
            'email' => $email,
            'foto' => $fotoPath
        ];

        $_SESSION['mensagem'] = "Perfil atualizado!";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
