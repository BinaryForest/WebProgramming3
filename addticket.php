<?php

	session_start();
		
	$db = new mysqli('localhost', 'assignment3', 'password123', 'assignment3');
	if ($db->connect_error)
	{
		die ("Could not connect to db: " . $db->connect_error);
	}
	else;
		
	$result = $db->query("select Tickets.Ticket from Tickets");
	$entries = $result->num_rows;
	$result->data_seek($entries-1);
	$row = $result->fetch_row();
	$ticketNumber = $row[0]+1;
		
	$name = stripslashes($_POST["name"]);
	$name = $db->real_escape_string($name);
	
	
	$user = $_SESSION["user"];
	// Since all users give an email when registering we simply send an email to the address associated with this user
	$query = "select users.email from users where users.User = '$user'";
	$result = $db->query($query);
	$row = $result->fetch_row();
	$email = $row[0];
	
	$subject= stripslashes($_POST["subject"]);
	$subject = $db->real_escape_string($subject); 
	$body = stripslashes($_POST["body"]);
	$body = $db->real_escape_string($body);
	
	$currentdate = date("M d Y h:iA");
	
	$query = "insert into Tickets values ('$ticketNumber', '$currentdate' ,'$name', '$email', '$subject', '$body')";
	$db->query($query) or die ("Invalid insert " . $this->db->error);
	$query = "insert into TicketStatus values ('$ticketNumber', '', 'open')";
	$db->query($query) or die ("Invalid insert " . $this->db->error);
	
	// Email submitter and all techs about new ticket
	
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
	$subj = strip_tags("Tech Support Confirmation");
	$msg = strip_tags("Your tech support request was accepted, your ticket number is $ticketNumber.");

	$mail->addAddress($receiver);
	$mail->SetFrom($sender);
	$mail->Subject = "$subj";
	$mail->Body = "$msg";

	if(!$mail->send()) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} 
	else;


	$query = "SELECT users.Email FROM users WHERE users.Privilege = 'admin'"; // Get all administrator emails
	$result = $db->query($query);
	$rows = $result->num_rows;

	$newmail = new PHPMailer();

	$newmail->IsSMTP(); 
	$newmail->SMTPAuth = true;
	$newmail->SMTPSecure = "tls"; 
	$newmail->Host = "smtp.pitt.edu"; 
	$newmail->Port = 587; 
	$newmail->Username = "$mailuser"; 
	$newmail->Password = "$mailpass"; 

	$sender = strip_tags("Tech Support");
	$subj = strip_tags("Tech Support Ticket Added");
	$msg = strip_tags("A new request for technical support has been added with number $ticketNumber.");

	$newmail->SetFrom($sender);
	$newmail->Subject = "$subj";
	$newmail->Body = "$msg";

	for($i = 0; $i < $rows; $i++) // Add all tech's emails to the message to inform them a new ticket was added
	{
		$row = $result->fetch_row();
		$email = $row[0];
		//echo $email . "<br/>";

		$receiver = strip_tags("$email");
		$newmail->addAddress($receiver);
	}

	if(!$newmail->send()) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $newmail->ErrorInfo;
	} 
	else;
	
	echo "OK";
?>