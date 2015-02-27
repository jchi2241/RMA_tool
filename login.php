<?php	

	//possible security holes: password getting passed to server in plain text. 
	//improve session management security
	//solution? use https connection

	$username = $_POST['username'];
	$password = $_POST['password'];

	if ($username && $password) {

		include 'configPDO.php';

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

				echo 'Password is incorrect.<br />';
				echo '<a href="login.html">Try again</>';

			}

		} elseif ( count($result) > 1 ) {

			echo "what is going on, more than one username/email match found";

		} else {

			echo "no username/email found";

		}

	} else {

		echo "Fill in both username and password fields<br />";
		echo '<a href="login.html">Go back</>';

	}

?>