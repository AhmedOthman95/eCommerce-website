<?php
	/*
		======================================
		== Manage Comments Page
		== You can  Edit | Delete | Approve Comments From Here
		======================================
	*/

	session_start();
	$pageTitle = 'Comments';
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

	if ($action == "Manage") { // Manage Comments Page 


			// Select All Comments
			$stmt = $conn->prepare("SELECT
										comments.*, items.Name AS Item_Name, users.Username 
									FROM 
										comments
									INNER JOIN 
										items
									ON 
										comments.Item_ID = items.Item_ID
									INNER JOIN 
										users
									ON 
										comments.User_ID = users.UserID
									ORDER BY 
										C_ID DESC		");
			$stmt->execute(); 
			$rows = $stmt->fetchAll();
			if (!empty($rows)) {
				
		?>

				<h1 class="text-center">Manage Comments</h1>
				<div class="container">
					<div class="table-responsive">
						<table class="main-table text-center table table-bordered">
							<tr>
								<th>#ID</th>
								<th>Comment</th>
								<th>Item Name</th>
								<th>User Name</th>
								<th>Add Date</th>
								<th>Control</th>
							</tr>
							
							<?php 
							foreach ($rows as $row) {
								echo "<tr>";
									echo "<td>" . $row['C_ID'] . "</td>";
									echo "<td>" . $row['Comment'] . "</td>";
									echo "<td>" . $row['Item_Name'] . "</td>";
									echo "<td>".$row['Username'] ."</td>";
									echo "<td>" . $row['Comment_Date'] . "</td>";
									echo "<td>
											<a href='comments.php?action=Edit&comid=" .$row['C_ID'] ."' class='btn btn-success'><i class='fa fa-edit icon'></i>Edit</a>
											<a href='comments.php?action=Delete&comid=" .$row['C_ID'] ."' class='btn btn-danger confirm'><i class='fa fa-close icon'></i>Delete</a>";
											if ($row['Status'] == 0) {
												echo "<a href='comments.php?action=Approve&comid=" .$row['C_ID'] ."' class='btn btn-info  activate'><i class='fa fa-check icon'></i>Approve</a>";
											}
									echo "</td>";
								echo "</tr>";
							}


							?>						
						</table>
					</div>
				</div>
				<?php } else {
				echo "<div class='container'>";
					echo "<div class='nice-message'>There're No Comments To Show</div>";
				echo "</div>";	
				}					
				 ?>
		<?php	

			}elseif ($action == "Edit") { 

				// Edit Page 

				// Check If Get Request ID is a numric and get the value of it to use it in the select stmt
				if (isset($_GET['comid']) && is_numeric($_GET['comid'])) {
					$comid = intval($_GET['comid']);

				} else {
					$comid = 0;
					
				}

					// Select Comment data from database

					$stmt = $conn->prepare("SELECT * FROM comments WHERE C_ID = ?");
					$stmt->execute(array($comid));
					$row = $stmt->fetch();
					$count = $stmt->rowCount();

					// If The Comment exist in the database, then show the form
					if ($count > 0) { ?>

				
				<h1 class="text-center">Edit Comment</h1>
				<div class="container">
					<form class="form-horizontal " action="?action=Update" method="POST">	
						 <!-- Hidden Field to send the user ID with the form data to use it in the database -->
						<input type="hidden" name="comid" value="<?php echo $comid  ?>" />
						<!-- Start Comment Field -->
						<div class="form-group row form-group-lg">
							<label class="col-sm-2 form-control-label" for="com">Comment</label>
							<div class="col-sm-10 col-md-6">
								<textarea id="com" class="form-control" name="comment"><?php echo $row['Comment']; ?></textarea>
							</div>
						</div>
						<!-- End Comment Field -->
						<!-- Start Submit Field -->
						<div class="form-group row">
							<div class="offset-sm-2 col-sm-10">
								<input type="submit" value="Save"  class="btn btn-primary btn-lg" />
							</div>
						</div>
						<!-- End Submit Field -->																			
					</form>

				</div>


			 <?php } else { //If the comment doesn't exist in the database , echo a message and don't show the form

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

			 		// Get Variables From The Form
			 		$comid = $_POST['comid'];
			 		$comment = $_POST['comment'];

			 		
				 	// Update the database with the form info
				 	$stmt = $conn->prepare("UPDATE comments SET Comment = ? WHERE C_ID = ?");
				 	$stmt->execute(array($comment, $comid));

				 	// Echo Success Mesage 
				 	if($stmt){
				 	$msg = "<div class='alert alert-success'>".$stmt->rowCount() . " Comment Updated </div>";	
		 			/* This Function Redirect To Home Page, This Function Exist in functions.php file
		 			** First Parameter Is The Message That Appear To User
		 			** Second Parameter Is The Time Before Redirecting
		 			*/
		 			redirectHome($msg,6, 'back');
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


				echo "<h1 class='text-center'>Delete Comment</h1>";
				echo "<div class='container'>"; // Start a container to Show the messages in it				

				// Check If Get Request ID is a numric and get the value of it to use it in the select stmt
				if (isset($_GET['comid']) && is_numeric($_GET['comid'])) {
					$comid = intval($_GET['comid']);

				} else {
					$comid = 0;
					
				}

					// Check If comment Exist In The database
					$check = checkItem('C_ID', 'comments', $comid);

					// If The comment exist in the database, then Delete the comment
					if ($check > 0) { 
						$stmt = $conn->prepare("DELETE FROM comments WHERE C_ID = ? ");
						$stmt->execute(array($comid));

						if($stmt){
					 	$msg = "<div class='alert alert-success'>".$stmt->rowCount() . " Comment Deleted </div>";	
					 	redirectHome($msg,6, 'back');
					 }
					} else {
						$msg =  "<div class='alert alert-danger'>This ID Doesn't Exist</div>";
						redirectHome($msg, 6);	
					}
					echo "</div>";
			}	elseif ($action == 'Approve') {

				// Approve Page


				echo "<h1 class='text-center'>Approve Comment</h1>";
				echo "<div class='container'>"; // Start a container to Show the messages in it				

				// Check If Get Request ID is a numric and get the value of it to use it in the select stmt
				if (isset($_GET['comid']) && is_numeric($_GET['comid'])) {
					$comid = intval($_GET['comid']);

				} else {
					$comid = 0;
					
				}

					// Check If Comment Exist In The database
					$check = checkItem('C_ID', 'comments', $comid);

					// If The Comment exist in the database, then Approve the comment
					if ($check > 0) { 
						$stmt = $conn->prepare("UPDATE comments SET Status = 1 WHERE C_ID = ? ");
						$stmt->execute(array($comid));

						if($stmt){
					 	$msg = "<div class='alert alert-success'>".$stmt->rowCount() . " Comment Approved </div>";	
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