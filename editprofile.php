<?php 
	ob_start();
	session_start();

	$pageTitle = 'Edit Profile'; //Page Title variable for the login page that used in function getTitle in functions.php 


	include "init.php";

	if (isset($_SESSION['User'])) {

		$getUser = $conn->prepare("SELECT * FROM users WHERE Username = ?");

		$getUser->execute(array($sessionUser)); // $sessionUser variable defined in init.php nad equal $_SESSION['User']

		$info = $getUser->fetch();

		$action = $_GET['action'];

		if ($action == "Edit"){
		
		// Edit Page 

			// Check If Get Request ID is a numric and get the value of it to use it in the select stmt
			if (isset($_GET['userid']) && is_numeric($_GET['userid'])) {
				$userid = intval($_GET['userid']);

			} else {
				$userid = 0;
				
			}
				if ($_GET['userid'] == $_SESSION['uid']){

 ?>

				
					<h1 class="text-center">Edit Profile</h1>
					<div class="container">
						<form class="form-horizontal " action="?action=Update" method="POST" enctype="multipart/form-data">	
							 <!-- Hidden Field to send the user ID with the form data to use it in the database -->
							<input type="hidden" name="userid" value="<?php echo $userid  ?>" />
							<!-- Start Username Field -->
							<div class="form-group row form-group-lg">
								<label class="col-sm-2 form-control-label" for="uname">Username</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="username" id="uname" class="form-control" autocomplete="off" value="<?php echo $row['Username'] ?>" required="required" />

								</div>
							</div>
							<!-- End Username Field -->
							<!-- Start Password Field -->
							<div class="form-group row form-group-lg">
								<label class="col-sm-2" for="pass">Password</label>
								<div class="col-sm-10 col-md-6">
									<input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
									<input type="password" name="newpassword" id="pass" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Don't Want To Change Your Password" />
								</div>
							</div>
							<!-- End Password Field -->
							<!-- Start Email Field -->
							<div class="form-group row form-group-lg">
								<label class="col-sm-2" for="email">Email</label>
								<div class="col-sm-10 col-md-6">
									<input type="email" name="email" id="email" value="<?php echo $row['Email'] ?>" class="form-control" required="required" />
								</div>
							</div>
							<!-- End Email Field -->
							<!-- Start Fullname Field -->
							<div class="form-group row form-group-lg">
								<label class="col-sm-2" for="full">Full Name</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="full" id="full" value="<?php echo $row['FullName'] ?>" class="form-control" required="required" />
								</div>
							</div>
							<!-- End Fullname Field -->
							<!-- Start Profile Image Field -->
							<div class="form-group row form-group-lg">
								<label class="col-sm-2 custom-file label">Profile Image</label>
								<div class="col-sm-10 col-md-6  custom-file">
									<input type="file" name="avatar" class="custom-file-input" id="avatar">
	    							<label class="custom-file-label" for="avatar">Choose file</label>
								</div>
							</div>
							<!-- End Profile Image Field -->							
							<!-- Start Submit Field -->
							<div class="form-group row">
								<div class="offset-sm-2 col-sm-10">
									<input type="submit" value="Save"  class="btn btn-primary btn-lg" />
								</div>
							</div>
							<!-- End Submit Field -->																			
						</form>

					</div>


			 <?php  
				 } else {
				 	echo "<div class='container'>";
				 	$msg = '<div class="alert alert-danger">This Is Not Your ID</div>';
				 	redirectHome($msg,6);
				 	echo "</div>";				 	
				 }  

			} elseif ($action == "Update") { //Update Page


				// Check if the user come across a post request
				if ($_SERVER['REQUEST_METHOD'] == "POST") {

					echo "<h1 class='text-center'>Update Profile</h1>";
					echo "<div class='container'>"; // Start a container to Show the messages in it

					// Get Upload File Variables

					$avatarName = $_FILES['avatar']['name'];
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
					$avatarExtension = strtolower($avatarExtension);


			 		// Get Variables From The Form
			 		$id = $_POST['userid'];
			 		$user = $_POST['username'];
			 		$email = $_POST['email'];
			 		$name = $_POST['full'];

			 		// Password Trick
			 		// Condition ? True : False

			 		$pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

			 		// Validate The Form
			 		$formErrors = array();

			 		if (strlen($user) < 4) {
			 			$formErrors[] = "User Name Can't Be Less Than <strong>4 Characters</strong> ";
			 		}
			 		if (strlen($user) > 20) {
			 			$formErrors[] = "User Name Can't Be More Than <strong>20 Characters</strong> ";
			 		}			 		
			 		if (empty($user)) {
			 			$formErrors[] = "User Name Can't Be <strong>Empty</strong>";
			 		}			 		
			 		if (empty($email)) {
			 			$formErrors[] = "Email Can't Be <strong>Empty</strong>";
			 		}
			 		if (empty($name)) {
			 			$formErrors[] = "Full Name Can't Be <strong>Empty</strong>";
			 		}
					if (! empty($avatarExtension) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
						$formErrors[] = "This Extension Is Not <strong>Allowed</strong>";
						$formErrors[] = "Allowed Extensions Are 'jpeg', 'jpg', 'png', 'gif'";
					}			 		
			 		// Loop into errors array and print it
			 		foreach ($formErrors as $error) {
			 					 			echo "<div class='alert alert-danger'>" . $error . "</div>";
			 		}
			 		// Check if there is no error, proceed the update operation
			 		if (empty($formErrors)) {

			 			$stmt = $conn->prepare("SELECT * FROM users WHERE UserID = ?");
						$stmt->execute(array($id));
						$userinfo = $stmt->fetch();
			 			// use rand() function to generate rendom number before the name of file to not duplicate files names
			 			if (! empty($avatarName)){
			 			$avatar = rand(0, 100000) . '_' . $avatarName;
			 			move_uploaded_file($avatarTemp, "admin\uploads\avatars\\".$avatar);
			 			 } else {
			 			 	$avatar = $userinfo['avatar'];
			 			 }	 			

			 			// Check If The User Name Exist In The Database Before Updating in order not to duplicate user name

			 			$stmt2 = $conn->prepare("SELECT * FROM users WHERE Username = ? AND UserId != ?");

			 			$stmt2->execute(array($user, $id));

			 			$count = $stmt2->rowCount(); 

			 			if ($count == 1) {
			 				$msg = "<div class='alert alert-danger'>Sorry, This User Name Already Exists. </div>";
			 				redirectHome($msg,6, 'back');
			 			}else {

					 	// Update the database with the form info
					 	$stmt = $conn->prepare("UPDATE users SET Username = ? , Email = ? , FullName = ? , Password = ?, avatar = ? WHERE UserID = ?");
					 	$stmt->execute(array($user, $email, $name, $pass, $avatar, $id));

					 	// Echo Success Mesage 
					 	if($stmt){
					 	$msg = "<div class='alert alert-success'> Profile Updated </div>";	
			 			/* This Function Redirect To Home Page, This Function Exist in functions.php file
			 			** First Parameter Is The Message That Appear To User
			 			** Second Parameter Is The Time Before Redirecting
			 			*/
			 			redirectHome($msg,6, 'back');
			 				}
			 			}
					 			 					 				
			 		}
			 	}

			 					 					 		
			 	} else {
			 		// If the user try to access the page directly

			 		echo "<div class='container'>";
			 		$msg = "<div class='alert alert-danger'>Sorry You Can't Browse This Page Directly</div>";
			 		redirectHome($msg,6);
			 		echo "</div>";
			 	}
			 	echo "</div>"; // End of div container the contain the messages
 
	
	}else {
		header('Location: login.php');
		exit();
	}

  include $temp.'footer.php'; 

  ob_end_flush();
 ?>		