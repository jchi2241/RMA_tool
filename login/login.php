<!DOCTYPE html>

<?php	

	//possible security holes: password getting passed to server in plain text. 
	//improve session management security
	//solution? use https connection
 		
	if ( isset($_POST['email']) && isset($_POST['password']) ) {

		include '../configPDO.php';

		$email = $_POST['email'];
		$password = $_POST['password'];

		if ( $email && $password ) {

			$sql = "SELECT password
					FROM users
					WHERE email = :email";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':email', $email);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			//check if username/email exists
			if ( count($result) == 1 ) {

				$hash = $result[0]['password'];

				if ( password_verify($password, $hash) ) {

					echo 'Your in!<br />';
					echo "<a href='change_password.php'>Change password</a>";

					session_start();
					$_SESSION['email'] = $email;

				} else {

					echo 'Email and password do not match<br />';

				}

			} elseif ( count($result) > 1 ) {

				echo "what is going on, more than one email match found";

			} else {

				echo "no email found";

			}

		} else {

			echo "Fill in both email and password fields<br />";

		}	

	}

?>

<head>
	<meta charset="utf-8">
	<title>RMA - Login</title>
</head>

<body>
	<h1>Log in</h1>
	<form action="" method="POST">
		Email: <input type="text" name="email"/><br />
		Password: <input type="password" name="password" /><br />
		<input type="submit" value="Log in" />
	</form>

</body>