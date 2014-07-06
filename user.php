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
	/*
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
	*/
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

function submitNewTicket()
{
	var fname = document.ticketForm.fname.value;
	var lname = document.ticketForm.lname.value;
	var subject = document.ticketForm.subject.value;
	var body = document.ticketForm.body.value;
	
	if(fname == "")
	{
		alert("First name not entered");
		document.ticketForm.fname.focus();
	}
	else if(lname=="")
	{
		alert("Last name not entered");
		document.ticketForm.lname.focus();
	}
	else if(subject=="")
	{
		alert("Problem not entered");
		document.ticketForm.subject.focus();
	}
	else if(body=="")
	{
		alert("Problem Description not entered");
		document.ticketForm.body.focus();
	}
	else
	{
		
		if (window.XMLHttpRequest) 
		{ 
			httpRequest = new XMLHttpRequest();
			if (httpRequest.overrideMimeType) 
			{
				httpRequest.overrideMimeType('text/xml');
			}
			else;
		}
		else if (window.ActiveXObject) 
		{
			try 
			{
				httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e) 
			{
				try 
				{
					httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch (e) {}
			}
		}
		
		if(!httpRequest)
		{
			alert("XMLHTTP instance could not be made!");
			return false;
		}
		else;
		
		var data = "name=" + fname + " " + lname + "&subject=" + subject + "&body=" + body;
		httpRequest.open('POST', 'addticket.php', true);
		httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		httpRequest.onreadystatechange = function() { ticketConfirm(httpRequest);};
		httpRequest.send(data);
	}
}

function ticketConfirm(httpRequest)
{
	if(httpRequest.readyState==4)
	{
		if(httpRequest.status == 200)
		{
			var response = httpRequest.responseText;
			if(response == "OK")
			{
				alert("Your ticket has been added and an email has been sent with your ticket number");
				document.ticketForm.fname.value = "";
				document.ticketForm.lname.value = "";
				document.ticketForm.subject.value = "";
				document.ticketForm.body.value = "";
			}
			else
			{
				alert("There was a problem with your ticket submission");
			}
		}
		else
			alert("There was a problem with the HTTP request");
	}
	else;
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
	<div id="display">
	
	</div>
	
	
	<form name="selection" id = "selection">
	<input type = 'button' value = "Submit New Ticket" onclick = 'newTicket()' id = "new"><br/>
	<input type = 'button' value = "View My Tickets" onclick = 'viewMyTickets()' id = "myTickets"><br/>
	<input type = 'button' value = "Reset Password" onclick = 'resetPass()' id = "reset"><br/>
	<input type = 'button' value = "Logout" onclick = 'logout()'><br/>
	</form>
</body>
</html>