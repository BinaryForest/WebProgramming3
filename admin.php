<?php
	if($_SERVER["HTTPS"] != "on") // Force HTTPS
	{
		header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
	}
	else;
	
	session_start();
	
	if(!isset($_SESSION['user']) || !isset($_SESSION['priv']) || $_SESSION['priv'] != "admin") // If user is not logged in, or is logged in and not an administrator, redirect to login
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
var selectedTicket;

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
	var toDisplay = "<input type='button' value='View All Tickets' onclick='allTickets()'><input type='button' value='Sort' onclick='sortBy()'><input type='button' value='View Selected Ticket' onclick='selectTicket()'><br/>"
	+"<input type='button' value='View My Tickets' onclick='myTickets()'><input type='button' value='Logout' onclick='logout()'><input type='button' value='View Unassigned Tickets' onclick='unassignedTickets()'><input type = 'button' value = 'Change Password' onclick = 'changePass()'>";
	
	display.innerHTML = toDisplay
	
	setTable("open");
}

function allTickets()
{
	var display = document.getElementById("choiceButtons");
	var toDisplay = "<input type='button' value='View Open Tickets' onclick='openTickets()'><input type='button' value='Sort' onclick='sortBy()'><input type='button' value='View Selected Ticket' onclick='selectTicket()'><br/>"
	+"<input type='button' value='View My Tickets' onclick='myTickets()'><input type='button' value='Logout' onclick='logout()'><input type='button' value='View Unassigned Tickets' onclick='unassignedTickets()'><input type = 'button' value = 'Change Password' onclick = 'changePass()'>";
	
	display.innerHTML = toDisplay
	
	setTable("all");
}

function myTickets()
{
	var display = document.getElementById("choiceButtons");
	var toDisplay = "<input type='button' value='View All Tickets' onclick='allTickets()'><input type='button' value='Sort' onclick='sortBy()'><input type='button' value='View Selected Ticket' onclick='selectTicket()'><br/>"
	+"<input type='button' value='View Open Tickets' onclick='openTickets()'><input type='button' value='Logout' onclick='logout()'><input type='button' value='View Unassigned Tickets' onclick='unassignedTickets()'><input type = 'button' value = 'Change Password' onclick = 'changePass()'>";
	
	display.innerHTML = toDisplay
	
	setTable("mine");
}

function unassignedTickets()
{
	var display = document.getElementById("choiceButtons");
	var toDisplay = "<input type='button' value='View All Tickets' onclick='allTickets()'><input type='button' value='Sort' onclick='sortBy()'><input type='button' value='View Selected Ticket' onclick='selectTicket()'><br/>"
	+"<input type='button' value='View My Tickets' onclick='myTickets()'><input type='button' value='Logout' onclick='logout()'><input type='button' value='View Open Tickets' onclick='openTickets()'><input type = 'button' value = 'Change Password' onclick = 'changePass()'>";
	
	display.innerHTML = toDisplay
	
	setTable("unassigned");
}

function setSelected(newTicket)
{
	selectedTicket = newTicket;
	//alert(newTicket + " was selected");
}

function selectTicket()
{
	//alert("Display ticket " + ticket);
	var display = document.getElementById("choiceButtons");
	var toDisplay = "<input type='button' value='Close/Open' onclick='closeOrOpen()'><input type='button' value='Assign/Unassign Self' onclick='assignSelf()'><input type='button' value='Email Submitter' onclick='emailSubmitter()'><input type='button' value='Delete Ticket' onclick='deleteTicket()'><br/>"
	+"<input type='button' value='Find All Tickets From Submitter' onclick='submitterTickets()'><input type='button' value='Find All Similar Tickets' onclick='similar()'><input type='button' value='Go Back To Main Ticket Display' onclick='openTickets()'>";
	display.innerHTML = toDisplay;
	
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
		
		var data = "ticket=" + selectedTicket + "&command=select";
		httpRequest.open('POST', 'selectTicket.php', true);
		httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		httpRequest.onreadystatechange = function() { displayTable(httpRequest);};
		httpRequest.send(data);
}

function deleteTicket()
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
		
		var data = "ticket=" + selectedTicket + "&command=delete";
		httpRequest.open('POST', 'selectTicket.php', true);
		httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		httpRequest.onreadystatechange = function() { confirmDelete(httpRequest);};
		httpRequest.send(data);
}

function confirmDelete(httpRequest)
{
	if(httpRequest.readyState==4)
	{
		if(httpRequest.status == 200)
		{
			var response = httpRequest.responseText;
			
			if(response == "OK")
			{
				alert("Ticket has been deleted");
				openTickets();
			}
			else
				alert(response);
		}
		else
			alert("There was a problem with the HTTP request");
	}
	else;
}

function assignSelf()
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
		
		var data = "ticket=" + selectedTicket + "&command=assign";
		httpRequest.open('POST', 'selectTicket.php', true);
		httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		httpRequest.onreadystatechange = function() { checkAssign(httpRequest);};
		httpRequest.send(data);
}

function checkAssign(httpRequest)
{
	
	if(httpRequest.readyState==4)
	{
		if(httpRequest.status == 200)
		{
			var response = httpRequest.responseText;
			
			if(response=="NO")
				alert("Cannot assign/unassign, ticket is assigned to another admin");
			else
				displayTable(httpRequest);
		}
		else
			alert("There was a problem with the HTTP request");
	}
	else;
}

function emailSubmitter()
{
	var tickets = document.getElementById("ticketTable");
	var emailForm = "<form name='emailForm' id='emailForm'>"
	+"Subject: <input type = 'text' name = 'subject' size = '30' maxlength = '30' value=''><br/>"
	+"Message:<br/> <textarea rows = '4' cols = '50' id='Body' name='body' value=''></textarea><br/>";
	
	tickets.innerHTML = emailForm;
	
	var buttons = document.getElementById("choiceButtons");
	var buttonDisplay = "<input type = 'button' value = 'Send' onClick = 'sendEmail()'><br/>"
	+"<input type = 'button' value = 'Go Back To Ticket' onClick = 'selectTicket()'></form>";
	
	buttons.innerHTML = buttonDisplay;
}

function sendEmail()
{
	var subject = document.emailForm.subject.value;
	var body = document.emailForm.body.value;
	
	if(subject=="")
	{
		alert("Problem not entered");
		document.emailForm.subject.focus();
	}
	else if(body=="")
	{
		alert("Problem Description not entered");
		document.emailForm.body.focus();
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
		
		var data = "ticket=" + selectedTicket + "&subject=" + subject + "&body=" + body;
		httpRequest.open('POST', 'emailUser.php', true);
		httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		httpRequest.onreadystatechange = function() { emailResponse(httpRequest);};
		httpRequest.send(data);
	}
}

function emailResponse(httpRequest)
{
	if(httpRequest.readyState==4)
	{
		if(httpRequest.status == 200)
		{
			var response = httpRequest.responseText;
			
			if(response=="OK")
				alert("Your email has been sent to the submitter!");
			else
				alert(response);
		}
		else
			alert("There was a problem with the HTTP request");
	}
	else;
}

function submitterTickets()
{
	var display = document.getElementById("choiceButtons");
	var toDisplay = "<input type='button' value='Close/Open' onclick='closeOrOpen()'><input type='button' value='Assign/Unassign Self' onclick='assignSelf()'><input type='button' value='Email Submitter' onclick='emailSubmitter()'><input type='button' value='Delete Ticket' onclick='deleteTicket()'><input type='button' value='Sort' onclick='sortBy()'><br/>"
	+"<input type='button' value='Find All Tickets From Submitter' onclick='submitterTickets()'><input type='button' value='Find All Similar Tickets' onclick='similar()'><input type='button' value='Go Back To Main Ticket Display' onclick='openTickets()'>";
	display.innerHTML = toDisplay;
	
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
		
		var data = "ticket=" + selectedTicket + "&command=submitter";
		httpRequest.open('POST', 'selectTicket.php', true);
		httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		httpRequest.onreadystatechange = function() { displayTable(httpRequest);};
		httpRequest.send(data);
}

function similar()
{
	var display = document.getElementById("choiceButtons");
	var toDisplay = "<input type='button' value='Close/Open' onclick='closeOrOpen()'><input type='button' value='Assign/Unassign Self' onclick='assignSelf()'><input type='button' value='Email Submitter' onclick='emailSubmitter()'><input type='button' value='Delete Ticket' onclick='deleteTicket()'><input type='button' value='Sort' onclick='sortBy()'><br/>"
	+"<input type='button' value='Find All Tickets From Submitter' onclick='submitterTickets()'><input type='button' value='Find All Similar Tickets' onclick='similar()'><input type='button' value='Go Back To Main Ticket Display' onclick='openTickets()'>";
	display.innerHTML = toDisplay;
	
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
		
		var data = "ticket=" + selectedTicket + "&command=similar";
		httpRequest.open('POST', 'selectTicket.php', true);
		httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		httpRequest.onreadystatechange = function() { displayTable(httpRequest);};
		httpRequest.send(data);
}
function closeOrOpen()
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
		
		var data = "ticket=" + selectedTicket + "&command=closeOrOpen";
		httpRequest.open('POST', 'selectTicket.php', true);
		httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		httpRequest.onreadystatechange = function() { displayTable(httpRequest);};
		httpRequest.send(data);
}

function changePass()
{
	var display = document.getElementById("ticketTable");
	
	var passForm = "<form name='passForm' id='passForm'>"
		+"Old Password: <input type = 'password' name = 'oldPass' size = '30' maxlength = '30' value=''><br/>"
		+"New Password: <input type = 'password' name = 'newPass' size = '30' maxlength = '30' value=''><br/>"
		+"<input type = 'button' value = 'Change Password' onclick = 'checkNewPass()'></form>"
		
	display.innerHTML = passForm + "<br/>";
	
	display = document.getElementById("choiceButtons");
	
	var toDisplay = "<input type = 'button' value = 'Go Back To Table' onclick = 'openTickets()'>";
		
	display.innerHTML = toDisplay;
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


/*
* Checks which sorting option was selected and calls the sortTable function using that as a parameter
*/
function sortBy()
{
	if(document.getElementById("ticketRadio").checked || document.getElementById("receivedRadio").checked) // Since ticket numbers are assigned incrementally as received ticket number ordering and date ordering are the same
	{
		//alert("Sort by ticket");
		sortTable(0);
	}
	else if(document.getElementById("senderRadio").checked)
	{
		//alert("Sort by sender");
		sortTable(2);
	}
	else if(document.getElementById("emailRadio").checked)
	{
		//alert("Sort by email");
		sortTable(3);
	}
	else if(document.getElementById("subjectRadio").checked)
	{
		//alert("Sort by subject");
		sortTable(4);
	}
	else if(document.getElementById("bodyRadio").checked)
	{
		sortTable(5);
	}
	else
		alert("No sorting option selected");
}

/*
*	Makes an array of the ticketTable body using the relevant column info and sorts the array by the chosen column,
*   then changes the table body to reflect the newly sorted arrangement
*/
function sortTable(column)
{
	var table = document.getElementById('table');
	var tableBody = table.getElementsByTagName('tbody')[0];
	var tableRows = tableBody.getElementsByTagName('tr');
	
	var rows = new Array();
	var i;
	var value;
	var tableCol
	// Make array containing the relevant column values for each row of the table, and keep track of their old place in the table
	for(i = 0; i < tableRows.length; i++)
	{
		rows[i] = new Object;
		rows[i].old = i;
		tableCol = tableRows[i].getElementsByTagName('td')[column];
		value = tableCol.firstChild.nodeValue;
		rows[i].value = value;
	}
	if(column < 1) // Sorting by ticket number, need to use our intSort
	{
		rows.sort(intSort)
	}
	
	else // Every other option means sorting alphabetically
	{
		rows.sort(alphaSort);
	}
	
	var sortedBody = document.createElement('tbody');
	
	// Go through each row in the old table body and build a new table body using our sorted order
	for (i = 0; i < tableRows.length; i++) 
	{
		sortedBody.appendChild(tableRows[rows[i].old].cloneNode(true));
	}	
	// Finally, replace the old body with our sorted one
	table.replaceChild(sortedBody, tableBody);
}



// Since Javascript sorts alphabetically by default we need to implement a sorting function for integers
function intSort(x, y) 
{
	var xInt = parseInt(x.value);
	var yInt = parseInt(y.value);
	return (xInt - yInt);
}

// Can't use the default js sort because these are objects so we need their values accessed for sorting
function alphaSort(x, y)
{
	var xString = x.value;
	var yString = y.value;
	
	if(xString == yString)
		return 0;
	else if(xString < yString)
		return -1;
	else
		return 1;
}

</script>
</head>

<body>

<div id='ticketTable'></div>
<div id='choiceButtons'></div>

<script type="text/javascript">
openTickets(); // Initialize page by showing the user all open tickets
</script>
</body>
</html>