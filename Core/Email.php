<?php

namespace Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email
{
  public static function send(string $to, string $subject, string $body): bool
  {
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $config = require dirname(__DIR__, 1) . "/.env.php";

    try {
      $mail->isSMTP();
      $mail->Host       = $config["mail"]['MAIL_Host'];
      $mail->SMTPAuth   = $config["mail"]['MAIL_SMTPAuth'];
      $mail->Username   = $config["mail"]['MAIL_Username'];
      $mail->Password   = $config["mail"]['MAIL_Password'];
      $mail->SMTPSecure = $config["mail"]['MAIL_SMTPSecure'];
      $mail->Port       = $config["mail"]['MAIL_Port'];

      $from = $mail->Username ?? '';
      if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
        throw new \Exception("Endereço de e-mail do remetente inválido.");
      }

      $mail->setFrom($from, 'Mini ERP');
      $mail->addAddress($to);

      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body    = $body;

      $mail->send();

      return true;
    } catch (Exception $e) {
      error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
      return false;
    }
  }
}
