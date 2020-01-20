<?php
	/*
		======================================
		== Manage Members Page
		== You can Add | Edit | Delete Members From Here
		======================================
	*/

	session_start();
	$pageTitle = 'Members';
	// Redirect to dashboard if user has registered
	if (isset($_SESSION['loggedIn'])) {


		include "init.php";

	$action = '';

	if (isset($_GET['action'])) {
		$action = $_GET['action'];
	}else {
		$action = 'Manage';
	}

	// Start Manage Page

	if ($action == "Manage") { // Manage Members Page 

			// Define a variable and check for it to return only pending members
			// We use this way to not rewrite the code of pending users page which is the same as of manage page code
			$query = '';
			if (isset($_GET['page']) && $_GET['page'] == 'Pending') { // page='Pending' exist in the href attribute of pending users in dashboard page
				$query = 'And RegStatus = 0'; // This Variable Will be put in the select stmt below
			}

			// Select All Users Except Admin
			$stmt = $conn->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC ");
			$stmt->execute(); 
			$rows = $stmt->fetchAll();

			if (!empty($rows)) {

				
		?>

				<h1 class="text-center">Manage Members</h1>
				<div class="container">
					<div class="table-responsive">
						<table class="main-table text-center table table-bordered">
							<tr>
								<th>#ID</th>
								<th>Image</th>
								<th>Username</th>
								<th>Email</th>
								<th>Full Name</th>
								<th>Register Date</th>
								<th>Control</th>
							</tr>
							
							<?php 
							foreach ($rows as $row) {
								echo "<tr>";
									echo "<td>" . $row['UserID'] . "</td>";
									echo "<td>";
										if (empty($row['avatar'])){
											echo "<img class='rounded' src='uploads/avatars/image.png' alt='' />";
										} else {
										echo "<img class='rounded' src='uploads/avatars/" . $row['avatar'] . "' alt='' />";
										}
									echo "</td>";
									echo "<td>" . $row['Username'] . "</td>";
									echo "<td>" . $row['Email'] . "</td>";
									echo "<td>" . $row['FullName'] . "</td>";
									echo "<td>".$row['Date'] ."</td>";
									echo "<td>
											<a href='members.php?action=Edit&userid=" .$row['UserID'] ."' class='btn btn-success'><i class='fa fa-edit icon'></i>Edit</a>
											<a href='members.php?action=Delete&userid=" .$row['UserID'] ."' class='btn btn-danger confirm'><i class='fa fa-close icon'></i>Delete</a>";
											if ($row['RegStatus'] == 0) {

												echo "<a href='members.php?action=Activate&userid=" .$row['UserID'] ."' class='btn btn-info  activate'><i class='fa fa-check icon'></i>Activate</a>";
											}
									echo "</td>";
								echo "</tr>";
							}


							?>						
						</table>
					</div>
					<a href='members.php?action=Add' class="btn btn-sm btn-primary" style="margin-bottom: 20px;"><i class="fa fa-plus" style="padding: 3px 6px;"></i> New Member</a>
				</div>
			<?php  } else {
				echo "<div class='container'>";
					echo "<div class='nice-message'>There're No Members To Show</div>";
					echo "<a href='members.php?action=Add' class='btn btn-sm btn-primary'><i class='fa fa-plus' style='padding: 3px 6px;''></i> New Member</a>";
				echo "</div>";
			} 
			}elseif ($action == "Add") { // Add page ?>
				
				<h1 class="text-center">Add New Member</h1>
				<div class="container">
					<form class="form-horizontal" action="?action=Insert" method="POST" enctype="multipart/form-data">	
						<!-- Start Username Field -->
						<div class="form-group row form-group-lg">
							<label class="col-sm-2 form-control-label" for="uname">Username</label>
							<div class="col-sm-10 col-md-6">
								<input type="text"  name="username" id="uname" class="form-control" autocomplete="off" placeholder="User Name to login" required="required" />

								
							</div>
						</div>
						<!-- End Username Field -->
						<!-- Start Password Field -->
						<div class="form-group row form-group-lg">
							<label class="col-sm-2" for="pass">Password</label>
							<div class="col-sm-10 col-md-6">
								<input type="password" name="password" id="pass" class="password form-control" autocomplete="new-password" required="required" placeholder="Password Must Be Strong" />
								<span><i class="fa fa-eye show-pass"></i></span>
							</div>
						</div>
						<!-- End Password Field -->
						<!-- Start Email Field -->
						<div class="form-group row form-group-lg">
							<label class="col-sm-2" for="email">Email</label>
							<div class="col-sm-10 col-md-6">
								<input type="email" name="email" id="email" placeholder="Email Must Be Valid" class="form-control" required="required" />
							</div>
						</div>
						<!-- End Email Field -->
						<!-- Start Fullname Field -->
						<div class="form-group row form-group-lg">
							<label class="col-sm-2" for="full">Full Name</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="full" id="full" placeholder="Full Name That appear on your profile" class="form-control" required="required" />
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
								<input type="submit"  value="Add Member"  class="btn btn-primary btn-sm" />
							</div>
						</div>
						<!-- End Submit Field -->	
					</form>
				</div>
										
				

		<?php	

			}elseif ($action == "Insert") {

				// Insert Page 


				// Check if the user come across a post request
				if ($_SERVER['REQUEST_METHOD'] == "POST") {

					echo "<h1 class='text-center'>Insert Member</h1>";
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
			 		$user = $_POST['username'];
			 		$pass = $_POST['password'];
			 		$email = $_POST['email'];
			 		$name = $_POST['full'];

			 		$hashPass = sha1($_POST['password']); // Encrypt the pass to send it to the database
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
			 		if (empty($pass)) {
			 			$formErrors[] = "Password Can't Be <strong>Empty</strong>";
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
			 			
			 			// use rand() function to generate rendom number before the name of file to not duplicate files names
			 			if (! empty($avatarName)){
			 			$avatar = rand(0, 100000) . '_' . $avatarName;
			 			move_uploaded_file($avatarTemp, "uploads\avatars\\".$avatar);
			 			 } else {
			 			 	$avatar = '';
			 			 }

			 			// Check If User Exists In The Database Using checkItem Function That Exists In functions.php file
			 			$check = checkItem("Username", "users", $user);
			 			if ($check == 1) {
			 				$msg = "<div class='alert alert-danger'>This User Name Already Exists.</div>";
			 				redirectHome($msg, 6, 'back');
			 			} else{

						 	// Insert Form Info Into The Database 

				 			$stmt = $conn->prepare("INSERT INTO 
				 									users(Username, Password, Email, FullName, RegStatus ,Date, avatar)
				 									VALUES(:user, :pass, :mail, :name, 1 ,now(), :zavatar)	");
				 			$stmt->execute(array(
				 				'user'		 => $user,
				 				'pass' 		 => $hashPass,
				 				'mail' 		 => $email,
				 				'name' 		 => $name,
				 				'zavatar' 	 => $avatar  

				 			));

						 	// Echo Success Mesage 
						 	if($stmt){
						 	$msg = "<div class='alert alert-success'>".$stmt->rowCount() . " User Inserted </div>";	

						 	redirectHome($msg,6, 'back');	
						 	}	
						 	 					 				
			 			}
		 			 }		 			
			 					 					 		
			 	} else { 
			 		// If the user try to access the page directly
			 		echo "<div class='container'>";
			 		$msg = "<div class='alert alert-danger'>Sorry You Can't Browse This Page Directly</div>";

			 		/* This Function Redirect To Home Page, This Function Exist in functions.php file
			 		** First Parameter Is The Message That Appear To User
			 		** Second Parameter Is The Time Before Redirecting
			 		*/
			 		redirectHome($msg,6);
			 		echo "</div>";
			 	}
			 	echo "</div>";

			}elseif ($action == "Edit") { 

			// Edit Page 

				// Check If Get Request ID is a numric and get the value of it to use it in the select stmt
				if (isset($_GET['userid']) && is_numeric($_GET['userid'])) {
					$userid = intval($_GET['userid']);

				} else {
					$userid = 0;
					
				}

					// Select user data from database

					$stmt = $conn->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1 ");
					$stmt->execute(array($userid));
					$row = $stmt->fetch();
					$count = $stmt->rowCount();

					// If The user exist in the database, then show the form
					if ($count > 0) { ?>

				
				<h1 class="text-center">Edit Member</h1>
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


			 <?php } else { //If the user doesn't exist in the database , echo a message and don't show the form

			 	echo "<div class='container'>";
			 	$msg = '<div class="alert alert-danger">There Is No Such ID</div>';
			 	redirectHome($msg,6);
			 	echo "</div>";
				 } 

			} elseif ($action == "Update") { //Update Page


				// Check if the user come across a post request
				if ($_SERVER['REQUEST_METHOD'] == "POST") {

					echo "<h1 class='text-center'>Update Member</h1>";
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

			 			// use rand() function to generate rendom number before the name of file to not duplicate files names
			 			if (! empty($avatarName)){
			 			$avatar = rand(0, 100000) . '_' . $avatarName;
			 			move_uploaded_file($avatarTemp, "uploads\avatars\\".$avatar);
			 			 } else {
			 			 	$avatar = '';
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
					 	$msg = "<div class='alert alert-success'>".$stmt->rowCount() . " Member Updated </div>";	
			 			/* This Function Redirect To Home Page, This Function Exist in functions.php file
			 			** First Parameter Is The Message That Appear To User
			 			** Second Parameter Is The Time Before Redirecting
			 			*/
			 			redirectHome($msg,6, 'back');
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
			}	elseif ($action == "Delete") {

				// Delete Page


				echo "<h1 class='text-center'>Delete Member</h1>";
				echo "<div class='container'>"; // Start a container to Show the messages in it				

				// Check If Get Request ID is a numric and get the value of it to use it in the select stmt
				if (isset($_GET['userid']) && is_numeric($_GET['userid'])) {
					$userid = intval($_GET['userid']);

				} else {
					$userid = 0;
					
				}

					// Check If User Exist In The database
					$check = checkItem('userid', 'users', $userid);

					// If The user exist in the database, then Delete the user
					if ($check > 0) { 
						$stmt = $conn->prepare("DELETE FROM users WHERE UserID = ? ");
						$stmt->execute(array($userid));

						if($stmt){
					 	$msg = "<div class='alert alert-success'>".$stmt->rowCount() . " Member Deleted </div>";	
					 	redirectHome($msg,6, 'back');
					 }
					} else {
						$msg =  "<div class='alert alert-danger'>This ID Doesn't Exist</div>";
						redirectHome($msg, 6);	
					}
					echo "</div>";
			}	elseif ($action == 'Activate') {
				// Activate Page


				echo "<h1 class='text-center'>Activate Member</h1>";
				echo "<div class='container'>"; // Start a container to Show the messages in it				

				// Check If Get Request ID is a numric and get the value of it to use it in the select stmt
				if (isset($_GET['userid']) && is_numeric($_GET['userid'])) {
					$userid = intval($_GET['userid']);

				} else {
					$userid = 0;
					
				}

					// Check If User Exist In The database
					$check = checkItem('userid', 'users', $userid);

					// If The user exist in the database, then activate the user
					if ($check > 0) { 
						$stmt = $conn->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ? ");
						$stmt->execute(array($userid));

						if($stmt){
					 	$msg = "<div class='alert alert-success'>".$stmt->rowCount() . " Member Activated </div>";	
					 	redirectHome($msg,6, 'back');
					 }
					} else {
						$msg =  "<div class='alert alert-danger'>This ID Doesn't Exist</div>";
						redirectHome($msg, 6);	
					}
					echo "</div>";
			}

		include $temp.'footer.php';
	} else {
			header('Location: index.php'); //Redirect to index page
			exit();
	}
 ?>