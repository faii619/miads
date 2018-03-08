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
    $status = 0;
    // return $data;
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'niramon.sr619@gmail.com';                 // SMTP username
        $mail->Password = 'niramon619';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('niramon.sr619@gmail.com', 'MI Alumni system');
        $mail->addAddress($data['email']);     // Add a recipient
        // $mail->addAddress('ellen@example.com');               // Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        if ($data['email_cc'] != 0) {
          $mail->addCC($data['email_cc']);
        }

        if ($data['email_bcc'] != 0) {
          $mail->addBCC($data['email_bcc']);
        }

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $data['subject'];
        $mail->Body    = $data['body'];
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        $status = 1;        
        // echo 'Message has been sent';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
    return $status;
  }
}
