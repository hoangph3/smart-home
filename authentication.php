<?php declare(strict_types=1);
require_once 'utils.php';
require_once "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Save image
$img = $_POST['image'];
$folderPath = "upload/";

$image_parts = explode(";base64,", $img);
$image_type_aux = explode("image/", $image_parts[0]);
$image_type = $image_type_aux[1];

$image_base64 = base64_decode($image_parts[1]);
$fileName = uniqid() . '.png';

$file = $folderPath . $fileName;
file_put_contents($file, $image_base64);

print_r($fileName);

// Send image
$mail = new PHPMailer(true);
//Enable SMTP debugging.
$mail->SMTPDebug = 1;                               
//Set PHPMailer to use SMTP.
$mail->isSMTP();            
//Set SMTP host name                          
$mail->Host = "smtp.gmail.com";
//Set this to true if SMTP host requires authentication to send email
$mail->SMTPAuth = true;                          
//Provide username and password     
$mail->Username = "daohambacnhat@gmail.com";                 
$mail->Password = "daohambacmot";                           
//If SMTP requires TLS encryption then set it
$mail->SMTPSecure = "tls";                           
//Set TCP port to connect to
$mail->Port = 587;                                   

$mail->From = "smarthome@hust.edu.vn";             
$mail->FromName = "Smart Home";

$mail->addAddress("phamhoanghxh1@gmail.com");

$mail->isHTML(true);

$mail->Subject = "Notification";
$mail->Body = '<h2>Do you know me? :)</h2>' . '<img src="cid:avatar">';

$mail->addEmbeddedImage(dirname(__FILE__) . '/' . $file, 'avatar');
try {
    $mail->send();
    echo "Message has been sent successfully!";
} 
catch (Exception $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}

echo "<script>window.location.assign('index.php')</script>";
