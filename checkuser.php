<?php
	session_start();
	
	$db = new mysqli('localhost', 'assignment3', 'password123', 'assignment3');
	if ($db->connect_error)
	{
		die ("Could not connect to db: " . $db->connect_error);
	}
	else;
	
	$user = $_POST["username"];
	$pass = $_POST["pass"];
	
	$hashed = hash("md5", $pass);
	
	$query = "SELECT Users.user, Users.password, Users.privilege from Users WHERE Users.user = '$user' AND Users.password = '$hashed'";
	$result = $db->query($query); // Check to see if database has a user with this username and password
	$entries = mysqli_num_rows($result);
	if($entries < 1) // If no matching user in database we return an "INVALID" message to the login page
	{
		echo "INVALID";
	}
	else
	{
		$row = $result->fetch_row();
		$privilege = $row[2];
		$_SESSION['user'] = $user;
		
		if($privilege == "admin") // Check the privilege level of the user and return it so we know which page to refer them to;
		{
			echo "ADMIN";
			$_SESSION['priv'] = "admin";
		}
		else
		{
			echo "USER";
			$_SESSION['priv'] = "user";	
		}
	}
?>