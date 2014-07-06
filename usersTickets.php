<?php

	session_start();
		
	$db = new mysqli('localhost', 'assignment3', 'password123', 'assignment3');
	if ($db->connect_error)
	{
		die ("Could not connect to db: " . $db->connect_error);
	}
	else;
	
	$user = stripslashes($_SESSION["user"]);
	$user = $db->real_escape_string($user);
	$query = "select Tickets.Ticket, Tickets.received, Tickets.subject, Tickets.body, Ticketstatus.tech, Ticketstatus.status from Tickets, Ticketstatus where Tickets.ticket = Ticketstatus.ticket AND Tickets.sender = '$user'";
	$result = $db->query($query) or die($db->error);
	$rows = $result->num_rows;
	
	echo "<table border = '1'><caption><h2>Your Tickets</h2></caption>";
	echo "<tr align = 'center'><th>Ticket #</th> <th>Receieved</th><th>Subject</th><th>Body</th><th>Tech</th><th>Status</th></tr>";
	for($i = 0; $i < $rows; $i++)
	{
		echo "<tr align = center>";
		$row = $result->fetch_row();  
		foreach ($row as $key => $value) 
		{
			echo "<td>$value</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
	
?>