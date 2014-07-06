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
	+"Problem: <input type = 'text' name = 'subject' size = '30' maxlength = '30' value=''><br/>"
	+"Description of Problem:<br/> <textarea rows = '4' cols = '50' id='Body' name='body' value=''></textarea><br/>"
	+"<input type = 'button' value = 'Submit Ticket' onClick = 'submitNewTicket()'></form>";
	var toDisplay = "<form name='selection' id = 'selection'><br/>"
	+"<input type = 'button' value = 'View My Tickets' onclick = 'viewMyTickets()' id = 'myTickets'><br/>"
	+"<input type = 'button' value = 'Change Password' onclick = 'changePass()' id = 'reset'><br/>"
	+"<input type = 'button' value = 'Logout' onclick = 'logout()'><br/>";
	
	display.innerHTML = newForm + "<br/>" + toDisplay;
}

function submitNewTicket()
{
	var subject = document.ticketForm.subject.value;
	var body = document.ticketForm.body.value;
	
	if(subject=="")
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
		
		var data = "subject=" + subject + "&body=" + body;
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

	httpRequest.open('POST', 'usersTickets.php', true);
	httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

	httpRequest.onreadystatechange = function() { displayMyTickets(httpRequest);};
	httpRequest.send();
}

function displayMyTickets()
{
	if(httpRequest.readyState==4)
	{
		if(httpRequest.status == 200)
		{
			var response = httpRequest.responseText;
			
			
			var display = document.getElementById("display");
	
			var toDisplay = "<form name='selection' id = 'selection'><br/>"
			+"<input type = 'button' value = 'Submit New Ticket' onclick = 'newTicket()' id = 'new'><br/>"
			+"<input type = 'button' value = 'Change Password' onclick = 'changePass()' id = 'reset'><br/>"
			+"<input type = 'button' value = 'Logout' onclick = 'logout()'><br/></form>";
			
			display.innerHTML = response + "<br/>" + toDisplay;
		}
		else
			alert("There was a problem with the HTTP request");
	}
	else;
}

function changePass()
{
	var display = document.getElementById("display");
	
	var passForm = "<form name='passForm' id='passForm'>"
		+"Old Password: <input type = 'password' name = 'oldPass' size = '30' maxlength = '30' value=''><br/>"
		+"New Password: <input type = 'password' name = 'newPass' size = '30' maxlength = '30' value=''><br/>"
		+"<input type = 'button' value = 'Change Password' onclick = 'checkNewPass()'></form>"
	
	var toDisplay = "<form name='selection' id = 'selection'><br/>"
		+"<input type = 'button' value = 'Submit New Ticket' onclick = 'newTicket()' id = 'new'><br/>"
		+"<input type = 'button' value = 'View My Tickets' onclick = 'viewMyTickets()' id = 'myTickets'><br/>"
		+"<input type = 'button' value = 'Logout' onclick = 'logout()'><br/></form>";
		
	display.innerHTML = passForm + "<br/>" + toDisplay;
}

function checkNewPass()
{
	var oldPass = document.passForm.oldPass.value;
	var newPass = document.passForm.newPass.value;
	
	if(oldPass=="")
	{
		alert("Old password not entered");
		document.passForm.oldPass.focus();
	}
	else if(newPass=="")
	{
		alert("New password not entered");
		document.passForm.newPass.focus();
	}
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

	var data = "oldPass=" + oldPass + "&newPass=" + newPass;
	
	httpRequest.open('POST', 'changePass.php', true);
	httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

	httpRequest.onreadystatechange = function() { passwordChanged(httpRequest);};
	httpRequest.send(data);
}

function passwordChanged()
{
	if(httpRequest.readyState==4)
	{
		if(httpRequest.status == 200)
		{
			var response = httpRequest.responseText;
			if(response == "OK")
			{
				alert("Your password has been changed");
			}
			else if(response == "WRONG")
			{
				alert("Your old password was incorrect, please ensure that you entered it correctly");
			}
			else
			{
				alert("There was an error in changing your password");
				//alert(response);
			}
		}
		else
			alert("There was a problem with the HTTP request");
	}
	else;
}

</script>
	
</head>


<body>
	<div id="display">
	<form name="selection" id = "selection">
	<input type = 'button' value = "Submit New Ticket" onclick = 'newTicket()' id = "new"><br/>
	<input type = 'button' value = "View My Tickets" onclick = 'viewMyTickets()' id = "myTickets"><br/>
	<input type = 'button' value = "Change Password" onclick = 'changePass()' id = "reset"><br/>
	<input type = 'button' value = "Logout" onclick = 'logout()'><br/>
	</form>
	</div>
</body>
</html>