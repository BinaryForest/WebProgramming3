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

function logout()
{
	window.location.href = "reset.php";
}

function newTicket()
{
	var display = document.getElementById("display");
	
	var newForm = "<form name='ticketForm' id='ticketForm'>"
	+"First Name: <input type = 'text' name = 'fname' size = '15' maxlength = '15' value=''><br/>"
	+"Last Name: <input type = 'text' name = 'lname' size = '15' maxlength = '15' value=''><br/>"
	+"Problem: <input type = 'text' name = 'subject' size = '30' maxlength = '30' value=''><br/>"
	+"Description of Problem:<br/> <textarea rows = '4' cols = '50' id='Body' name='body' value=''></textarea><br/>"
	+"<input type = 'button' value = 'Submit Ticket' onClick = 'submitNewTicket()'><br/></form>";
	var toDisplay = "<form name='selection' id = 'selection'><br/>"
	+"<input type = 'button' value = 'View My Tickets' onclick = 'viewMyTickets()' id = 'myTickets'><br/>"
	+"<input type = 'button' value = 'Reset Password' onclick = 'resetPass()' id = 'reset'><br/>"
	+"<input type = 'button' value = 'Logout' onclick = 'logout()'><br/>";
	
	display.innerHTML = newForm + "<br/>" + toDisplay;
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
	
}

function resetPass()
{

}

</script>
	
</head>


<body>
	<div id="display">
	<form name="selection" id = "selection">
	<input type = 'button' value = "Submit New Ticket" onclick = 'newTicket()' id = "new"><br/>
	<input type = 'button' value = "View My Tickets" onclick = 'viewMyTickets()' id = "myTickets"><br/>
	<input type = 'button' value = "Reset Password" onclick = 'resetPass()' id = "reset"><br/>
	<input type = 'button' value = "Logout" onclick = 'logout()'><br/>
	</form>
	</div>
</body>
</html>