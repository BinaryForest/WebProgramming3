<?php

	session_start();
		
	$db = new mysqli('localhost', 'assignment3', 'password123', 'assignment3');
	if ($db->connect_error)
	{
		die ("Could not connect to db: " . $db->connect_error);
	}
	else;
	
	$ticket = $_POST["ticket"];
	$command = $_POST["command"];
	//echo "Displaying $ticket<br/>";
	
	if($command=="select")
	{
		$query = "SELECT Tickets.ticket, Tickets.received, Tickets.sender, Tickets.email, Tickets.subject, Tickets.body, Ticketstatus.tech, Ticketstatus.status FROM Tickets, Ticketstatus WHERE Tickets.ticket = Ticketstatus.ticket AND Tickets.ticket = '$ticket'";
		$result = $db->query($query) or die ($db->error);
		$rows = $result->num_rows;
			
			echo "<table name='table' id='table' border = '1'><caption><h2>Selected Ticket</h2></caption>";
			echo "<tr align = 'center'><th>Ticket #</th> <th>Receieved</th><th>Sender</th><th>Email</th><th>Subject</th><th>Body</th><th>Tech</th><th>Status</th></tr>";
			echo "<tr align = center>";
			$row = $result->fetch_row();  
			foreach ($row as $key => $value) 
			{
				echo "<td>$value</td>";
			}
			echo "</tr></table>";
	}
	else if($command == "closeOrOpen")
	{
		$query = "SELECT Ticketstatus.status FROM Ticketstatus WHERE Ticketstatus.ticket = $ticket";
		$result = $db->query($query);
		$row = $result->fetch_row();
		$newStatus = "";
		if($row[0] == "open")
		{
			$newStatus = "closed";
			$query = "UPDATE Ticketstatus SET Ticketstatus.status = 'closed' WHERE Ticketstatus.ticket = '$ticket'";
			$db->query($query) or die ($db->error);
		}
		else
		{
			$newStatus = "open";
			$query = "UPDATE Ticketstatus SET Ticketstatus.status = 'open' WHERE Ticketstatus.ticket = '$ticket'";
			$db->query($query) or die ($db->error);
		}
		
		
		$query = "SELECT Tickets.ticket, Tickets.received, Tickets.sender, Tickets.email, Tickets.subject, Tickets.body, Ticketstatus.tech, Ticketstatus.status FROM Tickets, Ticketstatus WHERE Tickets.ticket = Ticketstatus.ticket AND Tickets.ticket = '$ticket'";
		$result = $db->query($query) or die ($db->error);
		$rows = $result->num_rows;
			
			echo "<table name='table' id='table' border = '1'><caption><h2>Selected Ticket</h2></caption>";
			echo "<tr align = 'center'><th>Ticket #</th> <th>Receieved</th><th>Sender</th><th>Email</th><th>Subject</th><th>Body</th><th>Tech</th><th>Status</th></tr>";
			echo "<tr align = center>";
			$row = $result->fetch_row();  
			foreach ($row as $key => $value) 
			{
				echo "<td>$value</td>";
			}
			echo "</tr></table>";
			
		$user = $_SESSION["user"];
		$query = "SELECT Users.email FROM Users WHERE Users.user = '$user'";
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
		$subj = strip_tags("Tech Support Confirmation");
		$msg = strip_tags("The status of your ticket #$ticket has changed to $newStatus");

		$mail->addAddress($receiver);
		$mail->SetFrom($sender);
		$mail->Subject = "$subj";
		$mail->Body = "$msg";

		if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} 
		else;
	}
	else
	{
		echo "Unrecognized command entered";
	}
?>