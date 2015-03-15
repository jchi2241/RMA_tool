<!DOCTYPE html>

<?php	

	//possible security holes: password getting passed to server in plain text. 
	//improve session management security
	//solution? use https connection
 		
	if ( isset($_POST['username']) && isset($_POST['password']) ) {

		include '../configPDO.php';

		$username = $_POST['username'];
		$password = $_POST['password'];

		if ( $username && $password ) {

			$sql = "SELECT password
					FROM users
					WHERE username = :username OR email = :username";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':username', $username);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			//check if username/email exists
			if ( count($result) == 1 ) {

				$hash = $result[0]['password'];

				if ( password_verify($password, $hash) ) {

					echo 'Your in!<br />';
					echo "<a href='change_password_page.php'>Change password</a>";

					session_start();
					$_SESSION['username'] = $username;

				} else {

					echo 'Username and password do not match<br />';

				}

			} elseif ( count($result) > 1 ) {

				echo "what is going on, more than one username/email match found";

			} else {

				echo "no username/email found";

			}

		} else {

			echo "Fill in both username and password fields<br />";

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
		Username or Email: <input type="text" name="username"/><br />
		Password: <input type="password" name="password" /><br />
		<input type="submit" value="Log in" />
	</form>

</body>