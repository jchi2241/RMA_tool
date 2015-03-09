<?php

	//possible security hole: password getting passed to server in plain text. 
	//improve session management security
	//solution? use https connection

	$current_password = $_POST['current_password'];
	$new_password = $_POST['new_password'];
	$confirm_new_password = $_POST['confirm_new_password'];

	session_start();

	if ( isset($_SESSION['username']) ) {

		$username = $_SESSION['username'];

		if ( $current_password && $new_password && $confirm_new_password ) {

			include 'configPDO.php';

			$sql = "SELECT password
					FROM users
					WHERE username = :username";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':username', $username);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			$hash = $result['password'];

			if ( password_verify($current_password, $hash) ) {

				if ( $new_password == $confirm_new_password ) {

					//bcrypt, salted, work => 2^10
					$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

					$sql = "UPDATE users
							SET password = :hashed_password
							WHERE username = :username";
					$stmt = $db->prepare($sql);
					$stmt->bindParam(':hashed_password', $hashed_password);
					$stmt->bindParam(':username', $username);
					$stmt->execute();

					echo "{$username}'s password change successful<br />";
					echo "<a href='change_password_page.php'>go back</a>";

				} else {

					echo "new passwords do not match<br />";
					echo "<a href='change_password_page.php'>go back</a>";

				}

			} else {

				echo "incorrect password<br />";
				echo "<a href='change_password_page.php'>go back</a>";

			}
			
		} else {

			echo "fill in all fields<br />";
			echo "<a href='change_password_page.php'>go back</a>";

		}

	} else {

		echo "No session exists";

	}

?>