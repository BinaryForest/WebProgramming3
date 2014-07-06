<?php
	if($_SERVER["HTTPS"] != "on")
	{
		header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
	}
	else;
	
	session_start();
	
	if(!isset($_SESSION['user']) || !isset($_SESSION['priv']) || $_SESSION['priv'] != "user")
	{
		header("location: login.php");
		exit();
	}
	else;
	
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>User Tech Support</title>

<script type="text/javascript">
var newTicketSelected = false;
var myTicketSelected = false;

function logout()
{
	window.location.href = "reset.php";
}

function newTicket()
{
	newTicketSelected = true;
	
	var selection = document.getElementById("selection");
	var newTicket = document.getElementById("new");
	selection.removeChild(newTicket);

	var header = document.getElementById("header");
	
	var ticketForm = document.createElement("form");
	ticketForm.setAttribute('name', 'ticketForm');
	
	var text = document.createTextNode("First Name: ");
	ticketForm.appendChild(text);
	
	var input = document.createElement("input");
	input.setAttribute('type', 'text');
	input.setAttribute('name', 'fname');
	input.setAttribute('value', '');
	input.setAttribute('size', '20');
	input.setAttribute('id', 'fname');
	
	ticketForm.appendChild(input);
	
	text = document.createElement("br");
	ticketForm.appendChild(text);
	
	text = document.createTextNode("Last Name: ");
	ticketForm.appendChild(text);
	
	input = document.createElement("input");
	input.setAttribute('type', 'text');
	input.setAttribute('name', 'lname');
	input.setAttribute('value', '');
	input.setAttribute('size', '20');
	input.setAttribute('id', 'lname');
	
	ticketForm.appendChild(input);
	
	text = document.createElement("br");
	ticketForm.appendChild(text);
	
	text = document.createTextNode("Email: ");
	ticketForm.appendChild(text);
	
	input = document.createElement("input");
	input.setAttribute('type', 'text');
	input.setAttribute('name', 'email');
	input.setAttribute('value', '');
	input.setAttribute('size', '30');
	input.setAttribute('maxlength', '40');
	input.setAttribute('id', 'email');
	
	ticketForm.appendChild(input);
	
	text = document.createElement("br");
	ticketForm.appendChild(text);
	
	text = document.createTextNode("Problem: ");
	ticketForm.appendChild(text);
	
	input = document.createElement("input");
	input.setAttribute('type', 'text');
	input.setAttribute('name', 'subject');
	input.setAttribute('value', '');
	input.setAttribute('size', '30');
	input.setAttribute('id', 'subject');
	
	ticketForm.appendChild(input);
	
	text = document.createElement("br");
	ticketForm.appendChild(text);
	
	text = document.createTextNode("Problem Description: ");
	ticketForm.appendChild(text);
	text = document.createElement("br");
	ticketForm.appendChild(text);
	
	input = document.createElement("textarea");
	input.setAttribute('rows', '4');
	input.setAttribute('cols', '50');
	input.setAttribute('value', '');
	input.setAttribute('name', 'body');
	input.setAttribute('id', 'body');
	
	ticketForm.appendChild(input);
	
	text = document.createElement("br");
	ticketForm.appendChild(text);
	
	input = document.createElement("input");
	input.setAttribute('type', 'button');
	input.setAttribute('value', 'Submit Ticket');
	input.setAttribute('onclick', 'submitNewTicket()');
	input.setAttribute('id', 'submitTicket');
	
	ticketForm.appendChild(input);
	
	header.appendChild(ticketForm);
	
	
}

function viewMyTickets()
{
	myTicketSelected = true;
}

function resetPass()
{

}

</script>
	
</head>


<body>
	<div id="header">
	
	</div>
	
	
	<form name="selection" id = "selection">
	<input type = 'button' value = "Submit New Ticket" onclick = 'newTicket()' id = "new"><br/>
	<input type = 'button' value = "View My Tickets" onclick = 'viewMyTickets()' id = "myTickets"><br/>
	<input type = 'button' value = "Reset Password" onclick = 'resetPass()' id = "reset"><br/>
	<input type = 'button' value = "Logout" onclick = 'logout()'><br/>
	</form>
</body>
</html>