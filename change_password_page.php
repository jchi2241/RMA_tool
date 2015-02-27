<!DOCTYPE html>

<?php

	//possible security hole: password getting passed to server in plain text. 
	//improve session management security
	//solution? use https connection

	session_start();

	if ( !isset($_SESSION['username']) ) {

		echo "no session exists<br />";
		echo "<a href='login.html'>Log in</a>";
		die();

	}

?>

<head>
<title>Change Password</title>
</head>
<body>
	<h1>Change password</h1>
	<form action="change_password.php" method="POST">
		Current password: <input type="password" name="current_password" /><br />
		New password: <input type="password" name="new_password" /><br />
		Confirm new password: <input type="password" name="confirm_new_password" /><br />
		<input type="submit" value="Change Password" />
	</form>

	<?php

		if ( isset($_SESSION['username']) ) {

			echo "<a href='logout.php'>Log out</a>";

		}

	?>
</body>