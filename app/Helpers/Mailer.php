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

        $this->mail->CharSet = 'UTF-8';
        $this->mail->Encoding = 'base64';

        $this->mail->isSMTP();
        $this->mail->Host = Env::get('MAIL_HOST');
        $this->mail->SMTPAuth = true;
        $this->mail->Username = Env::get('MAIL_USER');
        $this->mail->Password = Env::get('MAIL_PASS');
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = (int)Env::get('MAIL_PORT');

        $this->mail->setFrom(Env::get('MAIL_FROM'), Env::get('MAIL_FROM_NAME', 'Easy ERP'));

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
