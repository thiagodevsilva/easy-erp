<?php
namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Classe simples para envio de e-mails.
 */
class Mailer
{
    private PHPMailer $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        // Configuração SMTP (ajuste conforme seu provedor)
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'seuemail@gmail.com';
        $this->mail->Password = 'suasenhaouappkey'; // usar App Password no Gmail
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = 587;

        $this->mail->setFrom('seuemail@gmail.com', 'Easy ERP');
    }

    /**
     * Envia um e-mail.
     *
     * @param string $destino  E-mail do destinatário.
     * @param string $assunto  Assunto do e-mail.
     * @param string $mensagem Corpo (HTML).
     * @return bool
     */
    public function enviar(string $destino, string $assunto, string $mensagem): bool
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($destino);
            $this->mail->isHTML(true);
            $this->mail->Subject = $assunto;
            $this->mail->Body = $mensagem;

            return $this->mail->send();
        } catch (Exception $e) {
            error_log("Erro ao enviar e-mail: " . $e->getMessage());
            return false;
        }
    }
}
