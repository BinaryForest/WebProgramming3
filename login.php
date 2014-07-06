<?php
if($_SERVER["HTTPS"] != "on")
	{
		header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
	}
else;

session_start();

if(isset($_SESSION['user']) && isset($_SESSION['priv']) && $_SESSION['priv'] == "admin")
{
	header("location: admin.php");
	exit();
}
else if(isset($_SESSION['user']) && isset($_SESSION['priv']) && $_SESSION['priv'] == "user")
{
	header("location: user.php");
	exit();
}
else;

?>

<!DOCTYPE html>
<html>
<head><title>Tech Support Login</title></head>

<script type="text/javascript">
function checkName() // Checks that user entered login info and, if so, calls PHP script to check the username and password
{
	var username = document.loginInfo.username.value;
	var pass = document.loginInfo.pass.value;
	
	if(username == "")
	{
		alert("Username not entered");
		document.loginInfo.username.focus();
	}
	else if(pass == "")
	{
		alert("Password not entered");
		document.loginInfo.pass.focus();
	}
	else
	{
		document.loginInfo.username.value = "";
		document.loginInfo.pass.value = "";
		
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
	{ // IE
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
		
		var data = 'username=' + username + '&pass=' + pass;
		httpRequest.open('POST', 'checkuser.php', true);
		httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		httpRequest.onreadystatechange = function() { responseToCheck(httpRequest, username);};
		httpRequest.send(data);
	}
}

function responseToCheck(httpRequest, username) // Takes the response from script that checked the user database and logs user in if it matches
{
	if(httpRequest.readyState==4)
	{
		if(httpRequest.status == 200)
		{
			var response = httpRequest.responseText;
			if(response == "INVALID")
			{
				alert("Invalid username or password");
			}
			else if(response == "ADMIN")
			{
				//alert("You are an admin!");
				window.location.href = "admin.php";
			}
			else if(response == "USER")
			{
				//alert("You are a user!");
				window.location.href = "user.php";
			}
		}
		else
		{
			alert("Problem with request");
		}
	}
	else;
}

function registerForms()
{
	var info = document.getElementById("loginInfo");
	var child = document.getElementById("login");
	info.removeChild(child);
	
	var paragraph = document.getElementById("loginP");
	child = document.getElementById("loginButton");
	paragraph.removeChild(child);
	
	paragraph = document.getElementById("regP");
	child = document.getElementById("regButton1");
	paragraph.removeChild(child);
	
	/*
	var email = document.createElement("input");
	email.setAttribute('type', 'text');
	email.setAttribute('name', 'email');
	email.setAttribute('value', '');
	email.setAttribute('size', '20');
	email.setAttribute('maxlength', '40');
	
	var text = document.createTextNode("Email: ");
	paragraph = document.getElementById("loginP");
	paragraph.appendChild(text);
	paragraph.appendChild(email);
	
	var regButton = document.createElement("input");
	regButton.setAttribute('type', 'button');
	regButton.setAttribute('value', "Register");
	text = document.createTextNode("Register");
	regButton.appendChild(text);
	regButton.setAttribute('onClick', 'register()');
	
	paragraph = document.getElementById("regP");
	paragraph.appendChild(regButton);
	*/
	
	var newInput = "Email: <input type = 'text' name = 'email' size = '20' maxlength = '40' value= ''><br/>";
	newInput += "<input type = 'button' value = 'Register' onClick = 'register()'>";
	paragraph.innerHTML = newInput;
	
	
}

function register() // Use username and password info to attempt to register a new user
{
	var username = document.loginInfo.username.value;
	var pass = document.loginInfo.pass.value;
	var email = document.loginInfo.email.value;
	
	if(username == "")
	{
		alert("Username not entered");
		document.loginInfo.username.focus();
	}
	else if(pass == "")
	{
		alert("Password not entered");
		document.loginInfo.pass.focus();
	}
	else if(email == "")
	{
		alert("Email not entered");
		document.loginInfo.email.focus();
	}
	else
	{
	/*
		document.loginInfo.username.value = "";
		document.loginInfo.pass.value = "";
		document.loginInfo.email.value = "";
	*/
		var httpRequest = new XMLHttpRequest();
		if(httpRequest.overrideMimeType)
		{
			httpRequest.overrideMimeType('text/xml');
		}
		else;

		if(!httpRequest)
		{
			alert("XMLHTTP instance could not be made!");
			return false;
		}
		else;
		
		var data = 'username=' + username + '&pass=' + pass + "&email=" + email;
		httpRequest.open('POST', 'adduser.php', true);
		httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		httpRequest.onreadystatechange = function() { addUser(httpRequest);};
		httpRequest.send(data);
	}
}

function addUser(httpRequest)
{
	if(httpRequest.readyState==4)
	{
		if(httpRequest.status == 200)
		{
			var response = httpRequest.responseText;
			if(response == "NO") // Indicates that there is no user with this name
			{
				window.location.href = "user.php";
			}
			else // Any other response means there is a user already registered with this username
			{
				alert("There is already a user registered with this name");
			}
		}
		else
		{
			alert("Problem with request");
		}
	}
	else;
}

</script>

<body>
<form name = "loginInfo" method = "POST" id = "loginInfo">
<h3 id="login">Please enter your username and password to log in</h3><br/>
<div>Username: <input type = "text" name = "username" size = "20" maxlength = "20" value = ""></div>
<div>Password: <input type = "password" name = "pass" size = "20" maxlength = "40" value = ""></div>
<div id="loginP"><input type = 'button' value = "Login" onclick = 'checkName()' id = "loginButton"></div>
<div id="regP"><input type = 'button' value = "Register New User" onclick = 'registerForms()' id="regButton1"></div>
</form>
</body>
</html>
