<!DOCTYPE html>

<?php

	//possible security hole: password getting passed to server in plain text. 
	//improve session management security
	//solution? use https connection

	session_start();

	if ( !isset($_SESSION['email']) ) {

		echo "no session exists<br />";
		echo "<a href='login.php'>Log in</a>";
		die();

	}

	//possible security hole: password getting passed to server in plain text. 
	//improve session management security
	//solution? use https connection?

	if ( isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_new_password']) ) {

		$current_password = $_POST['current_password'];
		$new_password = $_POST['new_password'];
		$confirm_new_password = $_POST['confirm_new_password'];

		if ( isset($_SESSION['email']) ) {

			$email = $_SESSION['email'];

			if ( $current_password && $new_password && $confirm_new_password ) {

				include '../configPDO.php';

				$sql = "SELECT password
						FROM users
						WHERE email = :email";
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':email', $email);
				$stmt->execute();
				$result = $stmt->fetch(PDO::FETCH_ASSOC);

				$hash = $result['password'];

				if ( password_verify($current_password, $hash) ) {

					if ( $new_password == $confirm_new_password ) {

						//bcrypt, salted, work => 2^10
						$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

						$sql = "UPDATE users
								SET password = :hashed_password
								WHERE email = :email";
						$stmt = $db->prepare($sql);
						$stmt->bindParam(':hashed_password', $hashed_password);
						$stmt->bindParam(':email', $email);
						$stmt->execute();

						echo "password change successful<br />";

					} else {

						echo "new passwords do not match<br />";

					}

				} else {

					echo "incorrect current password<br />";

				}
				
			} else {

				echo "fill in all fields<br />";

			}

		} else {

			echo "No session exists";

		}

	}

?>

<head>
<meta charset="utf-8">
<title>Change Password</title>
</head>
<body>
	<h1>Change password</h1>
	<form action="" method="POST">
		Current password: <input type="password" name="current_password" /><br />
		New password: <input type="password" name="new_password" /><br />
		Confirm new password: <input type="password" name="confirm_new_password" /><br />
		<input type="submit" value="Change Password" />
	</form>

	<?php

		if ( isset($_SESSION['email']) ) {

			echo "<a href='logout.php'>Log out</a>";

		}

	?>
</body>