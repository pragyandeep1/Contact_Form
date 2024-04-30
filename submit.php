<?php
	$errors = array();
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

		if (empty($email)) {
			$errors["email"] = "Email is compulsory";
		}

		if (empty($subject)) {
			$errors["subject"] = "Subject is compulsory";
		}

		if (empty($message)) {
			$errors["message"] = "Message is compulsory";
		}

		if (empty($errors)) {
			save_to_database($_POST);
			send_email($_POST);
			echo "The form has been submitted successfully.";
		}
		else{
			foreach ($errors as $error) {
				echo $error."<br>";
			}
		}
	}

	function validateInput($data){
		$data = trim($data);
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
			echo "A new record entered successfully";
		}
		else{
			echo "Error ".$sql."<br>".$conn->error;
		}
		$conn->close();
	}

	function send_email($data){
		$to = "pragyandeep.2014@gmail.com";
		$subject = "Car Form";
		$message = "A request has been registered";
		$message = "\n\n Full Name: ".$data['fullname']."\n";
		$message = "Phone: ".$data['phone']."\n";
		$message = "Email: ".$data['email']."\n";
		$message = "Subject: ".$data['subject']."\n";
		$message = "Message: ".$data['message']."\n";

		$headers = "From: webmaster@example.com";

		if(mail($to, $subject, $message, $headers)){
			echo "Email sent successfully.";
		}
		else{
			"Wrong email credentials.";
		}
	}
?>