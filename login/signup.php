<!DOCTYPE html>

<?php

	//possible security hole: password getting passed to server in plain text. 
	//solution? use https connection

	if ( isset($_POST['firstname']) && isset($_POST['lastname']) && 
		isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password']) ) {

		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$confirm_password = $_POST['confirm_password'];

		if ( $firstname && $lastname && $email && $password && $confirm_password ) {

			if ( $password == $confirm_password ) {

				//bcrypt, salted, work => 2^10
				$hashed_password = password_hash($password, PASSWORD_DEFAULT);

				include '../configPDO.php';

				//check if email exists
				$sql = "SELECT COUNT(*)
						FROM users
						WHERE email = :email";
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':email', $email);
				$stmt->execute();

				if ( $stmt->fetch(PDO::FETCH_ASSOC)['COUNT(*)'] > 0 ) {

					echo "Email already exists.";

				} else {

					$sql = "INSERT INTO users (password, email, firstname, lastname)
							VALUES (:hashed_password, :email, :firstname, :lastname)";
					$stmt = $db->prepare($sql);
					$stmt->bindParam(':hashed_password', $hashed_password);
					$stmt->bindParam(':email', $email);
					$stmt->bindParam(':firstname', $firstname);
					$stmt->bindParam(':lastname', $lastname);
					$stmt->execute();

					echo "{$email} has successfully been created<br />";
					echo "<a href='signup.php'>create another user</a>";

				}

			} else {

				echo "passwords do not match<br />";

			}

		} else {

			echo "all fields must be filled in";

		}

	}

?>

<head>
<meta charset="utf-8">
<title>Sign up</title>
</head>
<body>
	<form action="" method="POST">
		email: <input type="text" name="email" /><br />
		first name: <input type="text" name="firstname" /><br />
		last name: <input type="text" name="lastname" /><br />
		password: <input type="password" name="password" /><br />
		confirm password: <input type="password" name="confirm_password" /><br />
		<input type="submit" value="create new user" />
	</form>
</body>