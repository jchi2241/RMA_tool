<!DOCTYPE html>

<?php

	if ( isset($_POST['email']) ) {

		$email = $_POST['email'];

		if ($email) {

			include '../configPDO.php';

			$sql = "SELECT email
					FROM users
					WHERE email = :email";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':email', $email);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			if ( $result ) {

				echo "1 result found";

				//generate new password. email new password to $result["email"];
				$to = $result['email'];
				$subject = "iSmart Alarm [Product Replacement] - Temporary Password";
				$message = "Line 1\r\nLine 2\r\nLine 3";
				// In case any of our lines are larger than 70 characters, we should use wordwrap()
				$message = wordwrap($message, 70, "\r\n");
				// Send
				mail($to, $subject, $message);

			} else {

				echo "No email address found. Try again.";

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