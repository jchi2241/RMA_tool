<!DOCTYPE html>

<?php	

	//possible security holes: password getting passed to server in plain text. 
	//improve session management security
	//solution? use https connection


	//if already logged in, redirect to index
	session_start();

	if ( isset($_SESSION['email']) ) {

		header("location: ../index.php");
		die();

	}
 		
	if ( isset($_POST['email']) && isset($_POST['password']) ) {

		include '../configPDO.php';

		$email = $_POST['email'];
		$password = $_POST['password'];

		if ( $email && $password ) {

			$sql = "SELECT password, firstname, lastname
					FROM users
					WHERE email = :email";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':email', $email);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$name = substr($result[0]['firstname'], 0, 1). ". " . $result[0]['lastname'];

			//check if username/email exists
			if ( count($result) == 1 ) {

				$hash = $result[0]['password'];

				if ( password_verify($password, $hash) ) {

					//redirect to the main page
					header("Location: ../index.php");

					session_start();
					$_SESSION['email'] = $email;
					$_SESSION['name'] = $name;

				} else {

					echo 'Email and Password do not match<br />';

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