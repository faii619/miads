<?php

namespace App\Http\Controllers\mail;

use Illuminate\Http\Request;
// use PHPMailer\PHPMailer\Exception;
use Laravel\Lumen\Routing\Controller as BaseController;
use PHPMailer\PHPMailer\PHPMailer;

require base_path("vendor/autoload.php");

class MailController extends BaseController
{
  public function send_email($data)
  {
    $mail = new PHPMailer;
    $mail->CharSet = "utf-8";
    $mail->isSMTP();
    $mail->SMTPDebug = 2;
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = "niramon.sr619@gmail.com";
    $mail->Password = "niramon619";
    $mail->setFrom('niramon.sr619@gmail.com', 'MI Alumni system');
    // $mail->addReplyTo('geidtiphong@gmail.com', 'Geidtiphong Singseewo');
    $mail->addAddress($data['email']);
    // $mail->addAddress('geidtiphong@gmail.com', 'Geidtiphong Singseewo');
    $mail->Subject = 'Your username and new password.';
    // $mail->msgHTML(file_get_contents('contents.html'), __DIR__);
    $mail->msgHTML("username: ".$data['username']."Password: ".$data['password']);
    // $mail->AltBody = 'This is a plain-text message body';
    //$mail->addAttachment('images/phpmailer_mini.png');
    if (!$mail->send()) {
      // echo "Mailer Error: " . $mail->ErrorInfo;
    }
  }
}
