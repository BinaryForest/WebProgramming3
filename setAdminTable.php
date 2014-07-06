<?php

	session_start();
		
	$db = new mysqli('localhost', 'assignment3', 'password123', 'assignment3');
	if ($db->connect_error)
	{
		die ("Could not connect to db: " . $db->connect_error);
	}
	else;
	
	$choice = $_POST["choice"];
	$user = $_SESSION["user"];
	
	if($choice == "open")
	{
		$query = "SELECT Tickets.ticket, Tickets.received, Tickets.sender, Tickets.email, Tickets.subject, Ticketstatus.Tech, Ticketstatus.status FROM Tickets, Ticketstatus WHERE Tickets.ticket = Ticketstatus.ticket AND Ticketstatus.status = 'open'";
		$result = $db->query($query) or die ($db->error);
		$rows = $result->num_rows;
		
		echo "<table id='ticketTable' border = '1'><caption><h2>Open Tickets</h2></caption>";
		echo "<tr align = 'center'><th>Ticket #</th> <th>Receieved</th><th>Sender</th><th>Email</th><th>Subject</th><th>Tech</th><th>Status</th></tr>";
		
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
	}
	else if($choice == "all")
	{
		$query = "SELECT Tickets.ticket, Tickets.received, Tickets.sender, Tickets.email, Tickets.subject, Ticketstatus.Tech, Ticketstatus.status FROM Tickets, Ticketstatus WHERE Tickets.ticket = Ticketstatus.ticket";
		$result = $db->query($query) or die ($db->error);
		$rows = $result->num_rows;
		
		echo "<table id='ticketTable' border = '1'><caption><h2>All Tickets</h2></caption>";
		echo "<tr align = 'center'><th>Ticket #</th> <th>Receieved</th><th>Sender</th><th>Email</th><th>Subject</th><th>Tech</th><th>Status</th></tr>";
		
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
	}
	else if($choice == "mine")
	{
		$query = "SELECT Tickets.ticket, Tickets.received, Tickets.sender, Tickets.email, Tickets.subject, Ticketstatus.Tech, Ticketstatus.status FROM Tickets, Ticketstatus WHERE Tickets.ticket = Ticketstatus.ticket AND Ticketstatus.tech = '$user'";
		$result = $db->query($query) or die ($db->error);
		$rows = $result->num_rows;
		
		echo "<table id='ticketTable' border = '1'><caption><h2>Your Tickets</h2></caption>";
		echo "<tr align = 'center'><th>Ticket #</th> <th>Receieved</th><th>Sender</th><th>Email</th><th>Subject</th><th>Tech</th><th>Status</th></tr>";
		
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
	}
	else if($choice == "unassigned")
	{
		$query = "SELECT Tickets.ticket, Tickets.received, Tickets.sender, Tickets.email, Tickets.subject, Ticketstatus.Tech, Ticketstatus.status FROM Tickets, Ticketstatus WHERE Tickets.ticket = Ticketstatus.ticket AND Ticketstatus.tech = ''";
		$result = $db->query($query) or die ($db->error);
		$rows = $result->num_rows;
		
		echo "<table id='ticketTable' border = '1'><caption><h2>Unassigned Tickets</h2></caption>";
		echo "<tr align = 'center'><th>Ticket #</th> <th>Receieved</th><th>Sender</th><th>Email</th><th>Subject</th><th>Tech</th><th>Status</th></tr>";
		
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
	}
	else
	{
		echo "There was a problem";
	}
	
?>