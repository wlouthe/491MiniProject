<?php
	require 'PHPMailer-master/PHPMailerAutoload.php';

	$mysqlusername = "ll37";
    $mysqlpassword = "burton37";
    $mysqlserver = "sql2.njit.edu";
    $mysqldb = "ll37";

    $mysqli = mysqli_connect($mysqlserver,$mysqlusername, $mysqlpassword, $mysqldb);
	
	echo '<?xml version="1.0" encoding="UTF-8"?>';
		
	if (isset($_POST["email"])){

		$results = $mysqli->query("SELECT * FROM Users WHERE Email = '".$_POST["email"]."';");
		$row = $results->fetch_assoc();
		
		$results2 = $mysqli->query("SELECT * FROM ConfirmationCodes WHERE Email = '".$_POST["email"]."';");
		$row2 = $results2->fetch_assoc();

		if($row["Email"] == $_POST["email"] || $row2["Email"] == $_POST["email"])
		{
			$message = "An attempt to register this email address was made while already registered with the database. Please try a different email.";
		}
		else
		{
			$code = md5($_POST["email"].time());
			$mysqli->query("INSERT INTO ConfirmationCodes (Email, ConfirmationCode) VALUES ('".$_POST["email"]."','".$code."');");
			$message = "If you have recently registered for the NJIT ACM forum, please click the link below. Otherwise please ignore this message.\r\nhttp://web.njit.edu/~ll37/491MiniProject/signupconfirmation.php?mycode=".$code."";
		}

		$subject = 'ACM Forum Registration Validation';
		// message goes here
		
		//from email address goes here
		$headers = 'From: NJIT Project Mailer';
		//list of addresses to mail to
		//$list = array("");
		$list = $_POST["email"];

		$mail = new PHPMailer(true);
		$send_using_gmail =true;
		//Send mail using gmail
		if($send_using_gmail){
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->SMTPAuth = true; // enable SMTP authentication
			$mail->SMTPSecure = "ssl"; // sets the prefix to the servier
			$mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server
			$mail->Port = 465; // set the SMTP port for the GMAIL server
			$mail->Username = "njitprojectmailer@gmail.com"; // GMAIL username
			$mail->Password = "1qA2wS3eD4rF"; // GMAIL password
		}

		//Typical mail data
		$mail->Subject = $subject;
		$mail->Body = $message;

		echo '<SuccessOrFail>';
		$mail->AddAddress($list);
		$mail->SetFrom("njitprojectmailer@gmail.com", "NJIT Project Mailer");//gmail address, name i.e. wlouthe@gmail.com, 
		 try{
			$mail->Send();
			echo "Success!";
			echo "\nINSERT INTO ConfirmationCodes (Email, ConfirmationCode) VALUES ('".$_POST["email"]."','".$code."');";
		 } catch(Exception $e){
			//Something went bad
			echo "Fail :(";
		 }
		$mail->ClearAllRecipients();
		echo '</SuccessOrFail>';
	}
?>
