<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require './plugins/Exception.php';
    require './plugins/PHPMailer.php';
    require './plugins/SMTP.php';

    $errors = array();
    $confirm = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        /*Validation*/
        $fullname = validateInput($_POST["fullname"]);
        $phone = validateInput($_POST["phone"]);
        $email = validateInput($_POST["email"]);
        $subject = validateInput($_POST["subject"]);
        $message = validateInput($_POST["message"]);

        if (empty($fullname)) {
            $errors["fullname"] = "Full name is compulsory";
        }

        if (empty($phone)) {
            $errors["phone"] = "Phone number is compulsory";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Email is invalid";
        }

        if (empty($subject)) {
            $errors["subject"] = "Subject is compulsory";
        }

        if (empty($message)) {
            $errors["message"] = "Message is compulsory";
        }

        if (empty($errors)) {
            $database_result = save_to_database($_POST);
            $email_result = send_email($_POST);

            if ($database_result && $email_result) {
                $confirm = "The form has been submitted successfully.";
            } else {
                $confirm = "There was an error processing your request. Please contact the administrator.";
            }
        }
        else{
            foreach ($errors as $error) {
                echo $error."<br>";
            }
        }

        echo $confirm;
    }

    function validateInput($data){
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function save_to_database($data){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "techsolvassignment";
        $conn = new mysqli($servername, $username, $password, $dbname);

        if($conn->connect_error){
            die("Bad connection ".$conn->connect_error);
        }

        $fullname = $conn->real_escape_string($data['fullname']);
        $phone = $conn->real_escape_string($data['phone']);
        $email = $conn->real_escape_string($data['email']);
        $subject = $conn->real_escape_string($data['subject']);
        $message = $conn->real_escape_string($data['message']);
        $ip_address = $_SERVER['REMOTE_ADDR'];

        $sql = "INSERT INTO contact_form(fullname, phone, email, subject, message, ip_address) VALUES('$fullname','$phone','$email','$subject','$message','$ip_address')";

        if($conn->query($sql)===TRUE){
            return true;
        }
        else{
            echo "Error ".$sql."<br>".$conn->error;
            return false;
        }
        $conn->close();
    }

    function send_email($data){
        $mail = new PHPMailer(true);
        $to = $_POST["email"];
        $subject = "Car Form";
        $message = "A request has been registered\n\n";
        $message .= "Full Name: ".$data['fullname']."\n";
        $message .= "Phone: ".$data['phone']."\n";
        $message .= "Email: ".$data['email']."\n";
        $message .= "Subject: ".$data['subject']."\n";
        $message .= "Message: ".$data['message']."\n";

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pragyandeepmohanty@gmail.com';
        $mail->Password = 'hxkqvlxwsbnncjvx';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('pragyandeepmohanty@gmail.com', 'Luxury Car');
        $mail->addAddress($to, 'User');
        $mail->Subject = $subject;
        $mail->Body = $message;

        if($mail->send()){
            return true;
        }
        else{
            return false;
        }
    }
?>
