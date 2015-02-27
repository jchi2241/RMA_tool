<?php

	//possible security hole: password getting passed to server in plain text. 
	//solution? use https connection

	$username = $_POST['username'];
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$confirm_password = $_POST['confirm_password'];

	if ( $username && $firstname && $lastname && $email && $password && $confirm_password ) {

		if ( $password == $confirm_password ) {

			//bcrypt, salted, work => 2^10
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);

			include 'configPDO.php';

			//check if username and email exist
			$sql = "SELECT COUNT(*)
					FROM users
					WHERE username = :username OR email = :email";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':username', $username);
			$stmt->bindParam(':email', $email);
			$stmt->execute();

			if ( $stmt->fetch(PDO::FETCH_ASSOC)['COUNT(*)'] > 0 ) {

				echo "Username and/or Email already exists.";

			} else {

				$sql = "INSERT INTO users (username, password, email, firstname, lastname)
						VALUES (:username, :hashed_password, :email, :firstname, :lastname)";
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':username', $username);
				$stmt->bindParam(':hashed_password', $hashed_password);
				$stmt->bindParam(':email', $email);
				$stmt->bindParam(':firstname', $firstname);
				$stmt->bindParam(':lastname', $lastname);
				$stmt->execute();

				echo "{$username} has successfully been created<br />";
				echo "<a href='signup.html'>create another user</a>";

			}

		} else {

			echo "passwords do not match<br />";
			echo "<a href='signup.html'>go back</a>";

		}

	} else {

		echo "one field is missing";
		echo "<a href='signup.html'>go back</a>";

	}

?>