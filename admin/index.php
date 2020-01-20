<?php 
	session_start();
	$noNavbar = ''; // init.php page check for that variable to not include the navbar page
	$pageTitle = 'Login'; //Page Title variable for the login page that used in function getTitle in functions.php

	// Redirect to dashboard if user has registered
	if (isset($_SESSION['loggedIn'])) {
		header('Location: dashboard.php'); //Redirect to dashboard page
	}
	include "init.php";


	//check if user coming from http post request
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){

		$username = $_POST['user'];
		$password = $_POST['pass'];
		$hashedPass = sha1($password) ;

		//check if user exist in database
		$stmt = $conn->prepare("SELECT UserID, Username, Password FROM users WHERE Username = ? AND Password = ? AND GroupID = 1 LIMIT 1 ");
		$stmt->execute(array($username, $hashedPass));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();

		// If count > 0 This means that user exist in the database
		if ($count > 0) {
			$_SESSION['loggedIn'] = $username; //Register Session name
			$_SESSION['ID'] = $row['UserID']; //Register Session ID
			header('Location: dashboard.php'); //Redirect to dashboard page
			exit();
		}

	}

	?>
	<form class="login" action="<?php echo $_SERVER['PHP_SELF']  ?>" method="POST">
		<h4 class="text-center">Admin Login</h4>
		<input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off" />
		<input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password" />
		<input class="btn btn-primary btn-block" type="submit" value="Login" />
	</form>
 <?php include $temp.'footer.php';  ?>