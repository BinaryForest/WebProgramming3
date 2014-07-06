<!DOCTYPE html>
<html>
 <head>
  <title>Assignment 3 DB Initialization</title>
 </head>
 <body>
 <?php
	$db = new mysqli('localhost', 'assignment3', 'password123', 'assignment3');
	if ($db->connect_error)
	{
		die ("Could not connect to db: " . $db->connect_error);
	}
	else;
	$db->query("drop table Tickets"); 
	$db->query("drop table TicketStatus");
	$db->query("drop table Users");
	
	$result = $db->query(
		"create table Tickets (Ticket int primary key not null, Received char(30) not null, Sender char(30) not null, Email char(30) not null, Subject char(40) not null, Body char(200) not null)") or die ("Invalid: " . $db->error);
	$tickets = file("tickets.flat");
	
	foreach ($tickets as $ticketstring)
	{
		$ticketstring = rtrim($ticketstring);
		$ticket = preg_split("/[~]+/", $ticketstring);
		$query = "insert into Tickets values ('$ticket[0]','$ticket[1]','$ticket[2]','$ticket[3]','$ticket[4]','$ticket[5]')";
		$db->query($query) or die ("Invalid insert " . $db->error);
	} 
	
	$result = $db->query(
		"create table TicketStatus (Ticket int primary key not null, Tech char(30), Status char(7) not null)") or die("Invalid: " . $db->error);
	$tickets = file("ticketstatus.flat");
	
	foreach ($tickets as $ticketstring)
	{
		$ticketstring = rtrim($ticketstring);
		$ticket = preg_split("/[~]+/", $ticketstring);
		
		if(count($ticket) == 3)
			$query = "insert into TicketStatus values ('$ticket[0]', '$ticket[1]', '$ticket[2]')";
		else
			$query = "insert into TicketStatus values ('$ticket[0]', '', '$ticket[1]')";
		$db->query($query) or die ("Invalid insert " . $db->error);
	}
	
	$result = $db->query(
		"create table Users (User char(20) primary key not null, Email char(40) not null, Password char(80) not null, Privilege char(8) not null)") or die("Invalid: " . $db->error);
	$admins = file("myusers.flat");
	foreach ($admins as $adminstring)
	{
		$adminstring = rtrim($adminstring);
		$admin = preg_split("/[~]+/", $adminstring);
		$hashed = hash("md5", $admin[2]); // Hashing admin passwords rather than storing them in plain text
		$query = "insert into Users values ('$admin[0]','$admin[1]', '$hashed', '$admin[3]')";
		$db->query($query) or die ("Invalid insert " . $db->error);
	}
	
	header("Location: index.html");
	exit();
 ?>
 </body>
</html>
