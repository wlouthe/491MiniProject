<html>
<head>
</head>
<body>
<?php
	if(isset($_POST["mycode"])&&isset($_POST["email"])&&isset($_POST["password"])&&isset($_POST["cpassword"]))
	{
		$mysqlusername = "ll37";
		$mysqlpassword = "burton37";
		$mysqlserver = "sql2.njit.edu";
		$mysqldb = "ll37";

		$mysqli = mysqli_connect($mysqlserver,$mysqlusername, $mysqlpassword, $mysqldb);
	
		$results = $mysqli->query("SELECT * FROM ConfirmationCodes WHERE ConfirmationCode = '".$_POST["mycode"]."';");
		$row = $results->fetch_assoc();
		if($row["Email"] == $_POST["email"] && $_POST["password"] == $_POST["cpassword"])
		{
		    //echo "INSERT INTO Users (Email, Password) VALUES ('".$_POST["email"]."','".crypt(htmlspecialchars($_POST["password"],ENT_QUOTES),'$6$rounds=5000$'.$_POST["email"].$_POST["email"].'$')."')";
			$mysqli->query("INSERT INTO Users (Email, Password) VALUES ('".$_POST["email"]."','".crypt(htmlspecialchars($_POST["password"],ENT_QUOTES),'$6$rounds=5000$'.$_POST["email"].$_POST["email"].'$')."')");
			$mysqli->query("DELETE FROM ConfirmationCodes WHERE Email = '".$_POST["email"]."'");
			echo "Success! You may now log in.";
		}
		else
		{
			echo 'FAILURE, Incorrect or invalid email<form method="post" action="signupconfirmation.php">
Please confirm your email address and choose your password:
<table>
<input name="mycode" type="hidden" value="'.$_GET["mycode"].'"
<tr><td>Email:</td><td><input type="email" name="email"></td></tr>
<tr><td>Password:</td><td><input type="password" name="password"></td></tr>
<tr><td>Confirm Password:</td><td><input type="password" name="cpassword"></td></tr>
<tr><td><input type="submit"></td></tr>
</table>
</form>';
		}
	}
	else
		{
			echo '<form method="post" action="signupconfirmation.php">
Please confirm your email address and choose your password:
<table>
<input name="mycode" type="hidden" value="'.$_GET["mycode"].'"
<tr><td>Email:</td><td><input type="text" name="email"></td></tr>
<tr><td>Password:</td><td><input type="password" name="password"></td></tr>
<tr><td>Confirm Password:</td><td><input type="password" name="cpassword"></td></tr>
<tr><td><input type="submit"></td></tr>
</table>
</form>';
		}
?>

</body>
</html>