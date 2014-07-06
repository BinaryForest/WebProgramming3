<?php
	session_start();
	
	$db = new mysqli('localhost', 'assignment3', 'password123', 'assignment3');
	if ($db->connect_error)
	{
		die ("Could not connect to db: " . $db->connect_error);
	}
	else;
	
	$user = stripslashes($_POST["username"]);
	$user = $db->real_escape_string($user);
	$pass = stripslashes($_POST["pass"]);
	$pass = $db->real_escape_string($pass);
	$email = stripslashes($_POST["email"]);
	$email = $db->real_escape_string($email);
	
	$emailChunks = explode(" ", $email);
	$email = implode("+", $emailChunks);
	
	$hashed = hash("md5", $pass);
	
	$query = "SELECT Users.user FROM Users WHERE Users.user = '$user'";
	$result = $db->query($query);
	$rows = $result->num_rows;
	if($rows > 0)
	{
		echo "YES";
	}
	else
	{
		echo "NO";
		$query = "INSERT into Users values('$user', '$email', '$hashed', 'user')";
		$db->query($query) or die($db->error);
		
		$_SESSION["user"] = $user;
		$_SESSION["priv"] = "user";
		
	}
?>