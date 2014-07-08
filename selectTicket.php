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
	$user = $_SESSION["user"];
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
		
		//$query = "SELECT Users.email FROM Users WHERE Users.user = '$user'";
		//$result = $db->query($query);
		//$row = $result->fetch_row();
		$email = $row[3];
			
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
	else if($command == "assign")
	{
		$query = "SELECT Ticketstatus.tech FROM Ticketstatus WHERE Ticketstatus.ticket = '$ticket'";
		$result = $db->query($query);
		$row = $result->fetch_row();
		if($row[0] == $user)
		{
			$query = "UPDATE Ticketstatus SET Ticketstatus.tech = '' WHERE Ticketstatus.ticket = '$ticket'";
			$db->query($query);
			
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
		else if($row[0] == "")
		{
			$query = "UPDATE Ticketstatus SET Ticketstatus.tech = '$user' WHERE Ticketstatus.ticket = '$ticket'";
			$db->query($query);
			
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
		else
			echo "NO";
	}
	else if($command == "similar")
	{
		echo "<table name='table' id='table' border = '1'><caption><h2>Similar Tickets</h2></caption>";
		echo "<thead><tr><th>Ticket #</th> <th>Receieved</th> <th>Sender Name</th> <th>Sender Email</th> <th>Subject</th> <th>Body</th> <th>Tech</th> <th>Status</th><th>Select</th></tr></thead>";
		$query = "SELECT tickets.Subject FROM tickets WHERE tickets.Ticket = '$ticket'";
		$result = $db->query($query);
		$row = $result->fetch_row();
		$subject = $row[0];
		//echo $subject . "<br/>";
		$subjectChunks = explode(" ", $subject);
		
		$query = "SELECT tickets.Ticket, tickets.Subject FROM tickets ORDER BY tickets.Ticket";
		$similarResult = $db->query($query);
		$similarRows = $similarResult->num_rows;
		echo "<tbody>";
		for($i = 0; $i < $similarRows; $i++)
		{
			$match = false;
			$simRow = $similarResult->fetch_row();
			$simSubj = $simRow[1];
			//echo $simSubj . "<br/>";
			for($x = 0; $x < count($subjectChunks); $x++) // Check each word in the selected subject against compared subject
			{
				$word = $subjectChunks[$x];
				$simChunks = explode(" ", $simSubj);
				for($z = 0; $z < count($simChunks); $z++)
				{
					if(strtolower($simChunks[$z]) == strtolower($word)) 
					{
						$match = true;		// If we find a match they are similar
						//echo "Match found: '$word' in '$simSubj' <br/>"; 
					}
					else;
				}
			}
			if($match)
			{
				$ticket = $simRow[0];
				//echo "Setting ticket to $ticket <br/>";
				$query = "select tickets.Ticket, tickets.Received, tickets.Sender, tickets.Email, tickets.Subject, tickets.Body, ticketstatus.Tech, ticketStatus.Status FROM tickets, ticketStatus WHERE tickets.Ticket = ticketstatus.Ticket AND tickets.Ticket = '$ticket' ORDER BY tickets.Ticket";
				$result = $db->query($query);
				echo "<tr align = center>";
				$row = $result->fetch_row();  
				foreach ($row as $key => $value) 
				{
					echo "<td>$value</td>";
				}
				$radio = $row[0];
				echo "<td><input type = 'radio' name = 'selection' onclick = 'setSelected($radio)'></td>";
				echo "</tr>";
			}
			else;
		}
		echo "</tbody>";
		echo "<tr>";
		echo "<td>Sort By <input type = 'radio' name = 'sort' value = 'ticket' id = 'ticketRadio'> </td>";
		echo "<td>Sort By <input type = 'radio' name = 'sort' value = 'received' id = 'receivedRadio'> </td>";
		echo "<td>Sort By <input type = 'radio' name = 'sort' value = 'sender' id = 'senderRadio'> </td>";
		echo "<td>Sort By <input type = 'radio' name = 'sort' value = 'email' id = 'emailRadio'> </td>";
		echo "<td>Sort By <input type = 'radio' name = 'sort' value = 'subject' id = 'subjectRadio'> </td>";
		echo "<td>Sort By <input type = 'radio' name = 'sort' value = 'body' id = 'bodyRadio'> </td>";
		echo "</tr>";
		echo "</table>";
	}
	else if($command == "submitter")
	{
		$query = "SELECT tickets.Sender FROM tickets WHERE tickets.Ticket = '$ticket'";
		$result = $db->query($query);
		$row = $result->fetch_row();
		$sender = $row[0];
		$query = "select tickets.Ticket, tickets.Received, tickets.Sender, tickets.Email, tickets.Subject, tickets.Body, ticketstatus.Tech, ticketStatus.Status FROM tickets, ticketStatus WHERE tickets.Ticket = ticketstatus.Ticket AND tickets.Sender = '$sender' ORDER BY tickets.Ticket";
		$result = $db->query($query);

		echo "<table name='table' id='table' border = '1'><caption><h2>Tickets From $sender</h2></caption>";
		echo "<thead><tr><th>Ticket #</th> <th>Receieved</th> <th>Sender Name</th> <th>Sender Email</th> <th>Subject</th> <th>Body</th> <th>Tech</th> <th>Status</th><th>Select</th></tr></thead>";
		if($result->num_rows == 0)
		{
		
		}
		else
		{
			echo "<tbody>";
			$rows = $result->num_rows;
			for($i = 0; $i < $rows; $i++)
			{
				echo "<tr align = center>";
				$row = $result->fetch_row();  
				foreach ($row as $key => $value) 
				{
					echo "<td>$value</td>";
				}
				$radio = $row[0];
				echo "<td><input type = 'radio' name = 'selection' onclick = 'setSelected($radio)'></td>";
				echo "</tr>";
			}
			echo "</tbody>";
			echo "<tr>";
			echo "<td>Sort By <input type = 'radio' name = 'sort' value = 'ticket' id = 'ticketRadio'> </td>";
			echo "<td>Sort By <input type = 'radio' name = 'sort' value = 'received' id = 'receivedRadio'> </td>";
			echo "<td>Sort By <input type = 'radio' name = 'sort' value = 'sender' id = 'senderRadio'> </td>";
			echo "<td>Sort By <input type = 'radio' name = 'sort' value = 'email' id = 'emailRadio'> </td>";
			echo "<td>Sort By <input type = 'radio' name = 'sort' value = 'subject' id = 'subjectRadio'> </td>";
			echo "<td>Sort By <input type = 'radio' name = 'sort' value = 'body' id = 'bodyRadio'> </td>";
			echo "</tr>";
			echo "</table>";
		}
	}
	else if($command == "delete")
	{
		$query = "DELETE FROM tickets WHERE tickets.Ticket = '$ticket'";
		$db->query($query);
		$query = "DELETE FROM ticketstatus WHERE ticketstatus.Ticket = '$ticket'";
		$db->query($query);
		echo "OK";
	}
	else
	{
		echo "Unrecognized command entered";
	}
?>