<?php
	if($_SERVER["HTTPS"] != "on")
	{
		header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
	}
	else;
	
	session_start();
	
	if(!isset($_SESSION['user']) || !isset($_SESSION['priv']) || $_SESSION['priv'] != "admin")
	{
		header("location: login.php");
		exit();
	}
	else;
	
	$db = new mysqli('localhost', 'assignment3', 'password123', 'assignment3');
	if ($db->connect_error)
	{
		die ("Could not connect to db: " . $db->connect_error);
	}
	else;
?>

<!DOCTYPE html>
<html>
<head>
	<title>Tech Support Administration</title>
<script type="text/javascript">
function logout()
{
	window.location.href = "reset.php";
}

// Calls setAdminTable script via AJAX that will return a database query relevant to selected choice
function setTable(choice)
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
		
		var data = "choice=" + choice;
		httpRequest.open('POST', 'setAdminTable.php', true);
		httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		httpRequest.onreadystatechange = function() { displayTable(httpRequest);};
		httpRequest.send(data);
}

// Displays the table returned from setAdminTable.php
function displayTable(httpRequest)
{
	if(httpRequest.readyState==4)
	{
		if(httpRequest.status == 200)
		{
			var response = httpRequest.responseText;
			
			var tickets = document.getElementById("ticketTable");
			
			tickets.innerHTML = response;
		}
		else
			alert("There was a problem with the HTTP request");
	}
	else;
}

// Each of the following four functions changes the buttons displayed and calls setTable with their corresponding parameters
function openTickets()
{
	var display = document.getElementById("choiceButtons");
	var toDisplay = "<input type='button' value='View All Tickets' onclick='allTickets()'><input type='button' value='Sort' onclick='sort()'><input type='button' value='View Selected Ticket' onclick='selectTicket'><br/>"
	+"<input type='button' value='View My Tickets' onclick='myTickets()'><input type='button' value='Logout' onclick='logout()'><input type='button' value='View Unassigned Tickets' onclick='unassignedTickets()'>";
	
	display.innerHTML = toDisplay
	
	setTable("open");
}

function allTickets()
{
	var display = document.getElementById("choiceButtons");
	var toDisplay = "<input type='button' value='View Open Tickets' onclick='openTickets()'><input type='button' value='Sort' onclick='sort()'><input type='button' value='View Selected Ticket' onclick='selectTicket'><br/>"
	+"<input type='button' value='View My Tickets' onclick='myTickets()'><input type='button' value='Logout' onclick='logout()'><input type='button' value='View Unassigned Tickets' onclick='unassignedTickets()'>";
	
	display.innerHTML = toDisplay
	
	setTable("all");
}

function myTickets()
{
	var display = document.getElementById("choiceButtons");
	var toDisplay = "<input type='button' value='View All Tickets' onclick='allTickets()'><input type='button' value='Sort' onclick='sort()'><input type='button' value='View Selected Ticket' onclick='selectTicket'><br/>"
	+"<input type='button' value='View Open Tickets' onclick='openTickets()'><input type='button' value='Logout' onclick='logout()'><input type='button' value='View Unassigned Tickets' onclick='unassignedTickets()'>";
	
	display.innerHTML = toDisplay
	
	setTable("mine");
}

function unassignedTickets()
{
	var display = document.getElementById("choiceButtons");
	var toDisplay = "<input type='button' value='View All Tickets' onclick='allTickets()'><input type='button' value='Sort' onclick='sort()'><input type='button' value='View Selected Ticket' onclick='selectTicket'><br/>"
	+"<input type='button' value='View My Tickets' onclick='myTickets()'><input type='button' value='Logout' onclick='logout()'><input type='button' value='View Open Tickets' onclick='openTickets()'>";
	
	display.innerHTML = toDisplay
	
	setTable("unassigned");
}
</script>
</head>

<body>

<div id='ticketTable'><br/></div>
<div id='choiceButtons'></div>

<script type="text/javascript">
openTickets();
</script>
</body>
</html>