<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require APPPATH.'libraries/phpmailer/src/Exception.php';
require APPPATH.'libraries/phpmailer/src/PHPMailer.php';
require APPPATH.'libraries/phpmailer/src/SMTP.php';

class Mailer {

  public static function sendMail($parameters) {
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->CharSet="UTF-8";
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'domingoramirez.com.ve';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'user@domingoramirez.com.ve';                 // SMTP username
      $mail->Password = 'PlG?t^B3S1Bq';                        // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;                                    // TCP port to connect to
      $mail->SMTPOptions = array(
        'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
        )
      );
  
      //Recipients
      $mail->setFrom('notifications@domingoramirez.com.ve', 'Notifications');

      foreach($parameters['mails'] AS $email) {
        $mail->addAddress($email);
      }
      
  
      //Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = $parameters['subject'];
      $mail->Body    = $parameters['body'];
      $mail->AltBody = 'Alternative text for no HTML mail clients';
  
      $mail->send();
    } catch (Exception $e) {
      throw new Exception($mail->ErrorInfo);
    }
  }
}
?>