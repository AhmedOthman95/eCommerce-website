<?php
	ob_start();
	session_start();
	$pageTitle = 'Login'; //Page Title variable for the login page that used in function getTitle in functions.php 

	// Redirect to index if user has registered
	if (isset($_SESSION['User'])) {
		header('Location: index.php'); //Redirect to index page
	}	
	include 'init.php';

	//check if user coming from HTTP post request
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){

		if (isset($_POST['login'])) {

			$user = $_POST['username'];
			$pass = $_POST['password'];
			$hashedPass = sha1($pass) ;

			//check if user exist in database
			$stmt = $conn->prepare("SELECT UserID, Username, Password FROM users WHERE Username = ? AND Password = ? ");
			$stmt->execute(array($user, $hashedPass));
			$get = $stmt->fetch();
			$count = $stmt->rowCount();

			// If count > 0 This means that user exist in the database
			if ($count > 0) {
				$_SESSION['User'] = $user; //Register Session name
				$_SESSION['uid'] = $get['UserID']; //Register Session ID
				header('Location: index.php'); //Redirect to index page
				exit();
			}
	   } else {
	   		$formErrors = array();

			// Get Upload File Variables

		/*	$avatarName = $_FILES['avatar']['name'];
			$avatarSize = $_FILES['avatar']['size'];
			$avatarTemp = $_FILES['avatar']['tmp_name'];
			$avatarType = $_FILES['avatar']['type'];

			// List Of Allowed file types to upload 
			$avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");


			// Get Avatar Extension
			// explode return an array of a string
			// explode divide the string depending on the delemeter which is the first parameter 
			// of the function
			// end -> get the latest item of the specified array
			$avatarExplode = explode('.', $avatarName);
			$avatarExtension = end($avatarExplode);
			$avatarExtension = strtolower($avatarExtension);*/

	   		$user 		= $_POST['username'];
	   		$password 	= $_POST['password'];
	   		$password2  = $_POST['confirm-password'];
	   		$email 		= $_POST['email'];

	   		if (isset($user)) {

	   			$filteredUser = filter_var($user, FILTER_SANITIZE_STRING); 

	   			if (strlen($filteredUser) < 4) {
	   				$formErrors[] = "<div class='alert alert-danger'>User Name Must Be Larger Than<strong> 4 Characters</strong></div>";
	   			}
	   		}
	   		if (isset($password) && isset($password2)) {

	   			if (empty($password)) {
	   				$formErrors[] = "<div class='alert alert-danger'>Sorry Password Can't Be Empty</div>";
	   			}

	   			$pass1 = sha1($password);
	   			$pass2 = sha1($password2);

	   			if ($pass1 !== $pass2) {
	   				$formErrors[] = "<div class='alert alert-danger'>Sorry Password Is Not Match</div>";
	   			}
	   		}
	   		if (isset($email)) {

	   			$filteredEmail = filter_var($email, FILTER_SANITIZE_EMAIL); 

	   			if (filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != true) {
	   				
	   				$formErrors[] = "<div class='alert alert-danger'>This Email Is Not Valide</div>";
	   			}
	   		}
		/*	if (! empty($avatarExtension) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
				$formErrors[] = "This Extension Is Not <strong>Allowed</strong>";
				$formErrors[] = "Allowed Extensions Are 'jpeg', 'jpg', 'png', 'gif'";
			}	   		
*/
	 		// Check if there is no error, proceed the Signup operation

	 		if (empty($formErrors)) {

	 			// use rand() function to generate rendom number before the name of file to not duplicate files names
	 		/*	if (! empty($avatarName)){
	 			$avatar = rand(0, 100000) . '_' . $avatarName;
	 			move_uploaded_file($avatarTemp, "admin\uploads\avatars\\".$avatar);
	 			 } else {
	 			 	$avatar = '';
	 			 }	 	 			
*/
	 			// Check If User Exists In The Database Using checkItem Function That Exists In functions.php file

	 			$check = checkItem("Username", "users", $user);

	 			if ($check == 1) {

	 				$formErrors[] = "<div class='alert alert-danger'>This User Already Exists.</div>";
	 				
	 			} else {

				 	// Insert Form Info Into The Database 
	 				
		 			$stmt = $conn->prepare("INSERT INTO 
		 									users(Username, Password, Email, RegStatus ,Date)
		 									VALUES(:user, :pass, :mail, 0 ,now())	");
		 			$stmt->execute(array(
		 				'user' => $user,
		 				'pass' => sha1($password),
		 				'mail' => $email

		 			));

				 	// Echo Success Mesage 

		 			$successMsg = "Congratulations! You Have Successfully Registered." ;
				 	 					 				
	 			}
 			}	   			   			   		
	   }	

	}	
 ?>	

	<div class="container login-page">
		<h1 class="text-center">
			<span class="log selected" data-class="login">Login</span> | 
			<span class="sign" data-class="signup">Signup</span>
		</h1>
		<!-- Start Login Form -->
		<form class="login"  action="<?php echo $_SERVER['PHP_SELF']  ?>" method="POST" >
			<div class="input-container">
				<input class="form-control" type="text" name="username" autocomplete="off" placeholder="User Name" required="required" />
			</div>
			<div class="input-container">
				<input class="form-control password" type="password" name="password" autocomplete="new-password" placeholder="Password" required="required" />
				<span><i class="fa fa-eye show-pass"></i></span>
		    </div>
			<input class="btn btn-primary btn-block" name="login" type="submit" value="Login" />
		</form>
		<!-- End Login Form --> 

		<!-- Start Signup Form -->
		<form class="signup"  action="<?php echo $_SERVER['PHP_SELF']  ?>" method="POST" enctype="multipart/form-data">
			<div class="input-container">
				<input class="form-control" type="text" name="username" autocomplete="off" placeholder="User Name" required="required" />
			</div>
			<div class="input-container">
				<input class="form-control password" type="password" name="password" autocomplete="new-password" placeholder="Password" required="required" />
				<span><i class="fa fa-eye show-pass"></i></span>
		    </div>
		    <div class="input-container">
				<input class="form-control password" type="password" name="confirm-password" autocomplete="new-password" placeholder="Confirm Password" required="required" />
				<span><i class="fa fa-eye show-pass"></i></span>
			</div>
			<div class="input-container">
				<input class="form-control" type="email" name="email" placeholder="Email" required="required" />
			</div>
			<!-- Start Profile Image Field -->
			<!--
			<div class="form-group row form-group-lg">
				<div class="col-sm-10 col-md-6  custom-file">
					<input type="file" name="avatar" class="custom-file-input" id="avatar">
					<label class="custom-file-label" for="avatar">Profile Image</label>
				</div>
			</div> -->
			<!-- End Profile Image Field -->		
			<input class="btn btn-success btn-block" name="signup" type="submit" value="Signup" />
		</form>
		<!-- End Signup Form -->
		<div class="the-errors text-center">
			<?php 
				if (! empty($formErrors)) {
					foreach ($formErrors as $error) {
						echo $error . '<br/>';
					}
				}
				if (isset($successMsg)) {
					echo "<div class='msg success'>" .$successMsg . "</div>";
				}
			 ?>
		</div>
	</div>

<?php 
	include $temp.'footer.php';
	ob_end_flush();
 ?>