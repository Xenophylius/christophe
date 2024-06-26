<?php 
  require_once "vendor/autoload.php";
  require_once "verifyData.php";
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;
  $debug = true ;

  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->load();

  if (isset($_POST['name']) && !empty($_POST['name']) 
        && isset($_POST['mail']) && !empty($_POST['mail'])
        && isset($_POST['message']) && !empty($_POST['message'])) {

        $nameVerify = valid_donnees($_POST['name']);
        $mailVerify = valid_donnees($_POST['mail']);
        $messageVerify = valid_donnees($_POST['message']);

    try {
      // Tentative de création d’une nouvelle instance de la classe PHPMailer, avec les exceptions activées
      $mail = new PHPMailer (true);
      // (…)

      $mail->isSMTP();
      $mail->SMTPAuth = true;
      
      // Informations personnelles
      $mail->Host = "smtp.elasticemail.com";
      $mail->Port = 2525;
      $mail->Username = $_ENV['KEY_ADDRESS'];
      $mail->Password = $_ENV['KEY_MAIL'];
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

      // Expéditeur
      $mail->setFrom($_ENV['KEY_ADDRESS'], $mailVerify);
      // Destinataire dont le nom peut également être indiqué en option
      $mail->addAddress($_ENV['KEY_ADDRESS'], 'Christophe Delahaye');

      $mail->isHTML(true);
      // Objet
      $mail->Subject = 'Mail de contact - ' . $nameVerify . ' ' . $mailVerify;
      // HTML-Content
      $mail->Body = $messageVerify;
      $mail->AltBody = 'Le texte comme simple élément textuel';

      $mail->CharSet = 'UTF-8';
      $mail->Encoding = 'base64';

      $mail->send();
      
      header("Location: index.php?success=Mail envoyé");
      
      exit;

    } catch (Exception $e) {

        header("Location: index.php?error=Une erreur est survenue. Veuillez rééssayer.&message=".$e->getMessage());
        exit;
    }
  }
?> 
