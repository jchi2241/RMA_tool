<!DOCTYPE html>

<?php

	if ( isset($_POST['email']) ) {

		$email = $_POST['email'];

		if ($email) {

			include '../configPDO.php';

			$sql = "SELECT firstname, lastname
					FROM users
					WHERE email = :email";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':email', $email);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			if ( $result ) {

				echo "A new temporary password has been sent to you";

				//generate new password. email new password to $result["email"];
				$to = $email;
				$name = $result["firstname"]." ".$result["lastname"];
				$subject = "iSmart Alarm [Product Replacement] - Temporary Password";
				$message = "Line 1\r\nLine 2\r\nLine 3";
				// In case any of our lines are larger than 70 characters, we should use wordwrap()
				$message = wordwrap($message, 70, "\r\n");

				$headers   = array();
				$headers[] = "MIME-Version: 1.0";
				$headers[] = "Content-type: text/plain; charset=iso-8859-1";
				$headers[] = "From: iSmart Alarm Internal <ismartalarminternal@gmail.com>";
				$headers[] = "Reply-To: {$name} <{$to}>";
				$headers[] = "Subject: {$subject}";
				$headers[] = "X-Mailer: PHP/".phpversion();

				mail($to, $subject, $message, implode("\r\n", $headers));
				// Send

			} else {

				echo "No matching email address found. Try again.";

			}

		}

	}

?>

<head>
<meta charset="utf-8">
<title>Forgot Password</title>
</head>
<body>
	<h1>Forgot Password</h1>
	<p>A new temporary password will be emailed to you</p>
	<form action="" method="POST">
		Email: <input type="email" name="email"/>
		<input type="submit" value="submit" />
	</form>
</body>