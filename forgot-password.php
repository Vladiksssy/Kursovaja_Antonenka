<?php
require 'vendor/autoload.php';
require_once("db.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $temporaryPassword = generateTemporaryPassword();
    $encryptedPassword = base64_encode(strrev(md5($temporaryPassword)));

    $sql = "UPDATE users SET password='$encryptedPassword' WHERE email='$email'";
    if ($conn->query($sql) === true) {
         
        $phpmailer = new PHPMailer();
        
        try {
            $phpmailer->isSMTP();
            $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
            $phpmailer->SMTPAuth = true;
            $phpmailer->Port = 2525;
            $phpmailer->Username = '36600c33a8f396';
            $phpmailer->Password = '5b74fef922554f';

            $phpmailer->setFrom('vladislava2003love@gmail.com', 'Sender Name');
            $phpmailer->addAddress('vlada@gmail.com','v');
            $phpmailer->Subject = 'Password Recovery';
            $phpmailer->Body = 'Your temporary password: ' . $temporaryPassword;

            if ($phpmailer->send()) {
                echo "Temporary password sent successfully.";
            } else {
                echo "Error sending email: " . $phpmailer->ErrorInfo;
            }
        } catch (Exception $e) {
            echo 'Error sending email: ', $e->getMessage();
        }
    } else {
        echo $conn->error;
    }
}

header("Location: index.php");
$conn->close();

function generateTemporaryPassword() {

    $length = 8;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $temporaryPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $temporaryPassword .= $characters[$index];
    }
    return $temporaryPassword;
}
?>
