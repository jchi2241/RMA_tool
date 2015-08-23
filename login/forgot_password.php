<!DOCTYPE html>

<?php

	ini_set('display_errors', 1); error_reporting(-1);

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
				$name = $result["firstname"]." ".$result["lastname"];
				$subject = "iSmart Alarm [Product Replacement Login] - Temporary Password";

				function random_password( $length = 8 ) {
				    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!?@#$";
				    $password = substr( str_shuffle( $chars ), 0, $length );
				    return $password;
				}

				$temp_password = random_password(8);

				//bcrypt, salted, work => 2^10
				$hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);

				//set new temporary password
				$sql = "UPDATE users
						SET password = :hashed_password
						WHERE email = :email";
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':hashed_password', $hashed_password);
				$stmt->bindParam(':email', $email);
				$stmt->execute();

				$message = "Hi {$name},\r\n\r\nYour temporary password is: {$temp_password}\r\n\r\nYou can change your password at http://104.236.106.186/rma_1/login/change_password.php";
				
				// In case any of our lines are larger than 70 characters, we should use wordwrap()
				$message = wordwrap($message, 70, "\r\n");

				$headers   = array();
				$headers[] = "MIME-Version: 1.0";
				$headers[] = "Content-type: text/plain; charset=iso-8859-1";
				$headers[] = "From: iSmart Alarm Internal <ismartalarminternal@gmail.com>";
				$headers[] = "Reply-To: {$name} <{$email}>";
				$headers[] = "Subject: {$subject}";
				$headers[] = "X-Mailer: PHP/".phpversion();

				// Send
				mail($email, $subject, $message, implode("\r\n", $headers));
				

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
	<p>A new password will be sent to your email account</p>
	<form action="" method="POST">
		Email: <input type="email" name="email"/>
		<input type="submit" value="submit" />
	</form>
</body>