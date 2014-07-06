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
	
	$oldPass = stripslashes($_POST["oldPass"]);
	$oldPass = $db->real_escape_string($oldPass);
	
	$newPass = stripslashes($_POST["newPass"]);
	$newPass = $db->real_escape_string($newPass);
	
	$hashedOld = hash("md5", $oldPass);
	$hashedNew = hash("md5", $newPass);
	
	$query = "SELECT Users.user FROM Users WHERE Users.user = '$user' AND Users.password = '$hashedOld'";
	$result = $db->query($query) or die($db->error);
	$rows = $result->num_rows;
	if($rows < 1) // incorrect old password was given, user may not be the one currently accessing account
		echo "WRONG";
	else
	{
		$query = "UPDATE Users SET Users.password = '$hashedNew' WHERE Users.user = '$user'";
		$db->query($query) or die($db->error);
		echo "OK";
	}
	
	
?>