<?php
	session_start();
		
	$db = new mysqli('localhost', 'assignment3', 'password123', 'assignment3');
	if ($db->connect_error)
	{
		die ("Could not connect to db: " . $db->connect_error);
	}
	else;
	
	$ticket = $_POST["ticket"];
	$user = $_SESSION["user"];
	$subject = $_POST["subject"];
	$body = $_POST["body"];
	
	$query = "SELECT Tickets.email FROM Tickets WHERE Tickets.ticket = '$ticket'";
	$result = $db->query($query);
	$row = $result->fetch_row();
	$email = $row[0];
	
	
	$mailpath = 'C:\xampp\xamppfiles\phpmailer';
	$incpath = 'C:\xampp\xamppfiles\data';
	$path = get_include_path();
	set_include_path($path . PATH_SEPARATOR . $mailpath . PATH_SEPARATOR . $incpath);
	require 'PHPMailerAutoload.php';
	include 'data.php';
	$mail = new PHPMailer();

	$mail->IsSMTP(); 
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = "tls"; 
	$mail->Host = "smtp.pitt.edu"; 
	$mail->Port = 587; 
	$mail->Username = "$mailuser"; 
	$mail->Password = "$mailpass"; 

	$sender = strip_tags("Tech Support");
	$receiver = strip_tags("$email");
	$subj = strip_tags("$subject");
	$msg = strip_tags("$body");

	$mail->addAddress($receiver);
	$mail->SetFrom($sender);
	$mail->Subject = "$subj";
	$mail->Body = "$msg";

	if(!$mail->send()) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} 
	else;
	
	echo "OK";

?>