<?php 
	ob_start();
	session_start();

	$pageTitle = 'Edit Item'; //Page Title variable for the login page that used in function getTitle in functions.php 


	include "init.php";


	if (isset($_SESSION['User'])) {

			$action = $_GET['action'];


			if ($action == "Edit") {
					
						// Edit Page 

						// Check If Get Request ID is a numric and get the value of it to use it in the select stmt
						if (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) {
							$itemid = intval($_GET['itemid']);

						} else {
							$itemid = 0;
							
						}

						// Select user data from database

						$stmt = $conn->prepare("SELECT * FROM items WHERE Item_ID = ? AND Member_ID = ?");
						$stmt->execute(array($itemid, $_SESSION['uid']));
						$item = $stmt->fetch();
						$count = $stmt->rowCount();

						// If The user exist in the database, then show the form
						if ($count > 0) { ?>
							<h1 class="text-center">Edit Item</h1>
							<div class="container">
								<form class="form-horizontal " action="?action=Update" method="POST" enctype="multipart/form-data">
									 <!-- Hidden Field to send the user ID with the form data to use it in the database -->
									<input type="hidden" name="itemid" value="<?php echo $itemid  ?>" />	
									<!-- Start Name Field -->
									<div class="form-group row form-group-lg">
										<label class="col-sm-2 form-control-label" for="iname">Name</label>
										<div class="col-sm-10 col-md-6">
											<input type="text" name="name" id="iname" class="form-control" placeholder="Name of The Item" required="required" value="<?php echo $item['Name'] ?>"  />
										</div>
									</div>
									<!-- End Name Field -->
									<!-- Start Description Field -->
									<div class="form-group row form-group-lg">
										<label class="col-sm-2 form-control-label" for="desc">Description</label>
										<div class="col-sm-10 col-md-6">
											<input type="text" name="description" id="desc" class="form-control" placeholder="Description of The Item" required="required" value="<?php echo $item['Description'] ?>"  />
										</div>
									</div>
									<!-- End Description Field -->
									<!-- Start Price Field -->
									<div class="form-group row form-group-lg">
										<label class="col-sm-2 form-control-label" for="price">Price</label>
										<div class="col-sm-10 col-md-6">
											<input type="text" name="price" id="price" class="form-control" placeholder="Price of The Item" required="required" value="<?php echo $item['Price'] ?>" />
										</div>
									</div>
									<!-- End Price Field -->
									<!-- Start Country Field -->
									<div class="form-group row form-group-lg">
										<label class="col-sm-2 form-control-label" for="country">Country</label>
										<div class="col-sm-10 col-md-6">
											<input type="text" name="country" id="country" class="form-control" placeholder="Country of Made" required="required" value="<?php echo $item['Country_Made'] ?>"  />
										</div>
									</div>
									<!-- End Country Field -->	
									<!-- Start Status Field -->
									<div class="form-group row form-group-lg">
										<label class="col-sm-2 form-control-label">Status</label>
										<div class="col-sm-10 col-md-6">
											<select name="status">
												<option value="1" <?php if ($item['Status'] == 1){ echo 'selected'; } ?>>New</option>
												<option value="2" <?php if ($item['Status'] == 2){ echo 'selected'; } ?>>Like New</option>
												<option value="3" <?php if ($item['Status'] == 3){ echo 'selected'; } ?>>Used</option>
												<option value="4" <?php if ($item['Status'] == 4){ echo 'selected'; } ?>>Very Old</option>
											</select>
										</div>
									</div>
									<!-- End Status Field -->
									<!-- Start Members Field -->
									<div class="form-group row form-group-lg">
										<label class="col-sm-2 form-control-label">Member</label>
										<div class="col-sm-10 col-md-6">
											<select name="member">
												<?php 
													$stmt = $conn->prepare("SELECT * FROM users");
													$stmt->execute();
													$users = $stmt->fetchAll();
													foreach ($users as $user) {
														echo "<option value='".$user['UserID'] ."'";
														if ($item['Member_ID'] == $user['UserID']){ echo 'selected'; }
														echo ">".$user['Username'] ."</option>";
													}
												?>
											</select>
										</div>
									</div>
									<!-- End Members Field -->																					<!-- Start Categories Field -->
									<div class="form-group row form-group-lg">
										<label class="col-sm-2 form-control-label">Category</label>
										<div class="col-sm-10 col-md-6">
											<select name="category">
												<?php 
													$stmt2 = $conn->prepare("SELECT * FROM categories");
													$stmt2->execute();
													$cats = $stmt2->fetchAll();
													foreach ($cats as $cat) {
														echo "<option value='".$cat['ID'] ."'";
														if ($item['Cat_ID'] == $cat['ID']){ echo 'selected'; }
														echo ">".$cat['Name'] ."</option>";
													}
												?>
											</select>
										</div>
									</div>
									<!-- End Categories Field -->
									<!-- Start Tags Field -->
									<div class="form-group row form-group-lg">
										<label class="col-sm-2 form-control-label" for="country">Tags</label>
										<div class="col-sm-10 col-md-6">
											<input type="text" name="tags" id="myTags" class="form-control" placeholder="Tag Describe Your Item e.g. Ahmed, Handmade, Discount, Guarantee, ..."
											value="<?php echo $item['tags'] ?>"  />
										</div>
									</div>
									<!-- End Tags Field -->	
									<!-- Start Item Image Field -->
									<div class="form-group row form-group-lg">
										<label class="col-sm-2 custom-file label">Item Image</label>
										<div class="col-sm-10 col-md-6  custom-file">
											<input type="file" name="avatar" class="custom-file-input" id="avatar" >
			    							<label class="custom-file-label" for="avatar">Choose file</label>
										</div>
									</div>
									<!-- End Item Image Field -->												
									<!-- Start Submit Field -->
									<div class="form-group row">
										<div class="offset-sm-2 col-sm-10">
											<input type="submit" value="Save Item"  class="btn btn-primary btn-sm" />
										</div>
									</div>
									<!-- End Submit Field --> 
								</form>
				
							</div>	

					 <?php } else { //If the user doesn't exist in the database , echo a message and don't show the form

					 	echo "<div class='container'>";
					 	$msg = '<div class="alert alert-danger">There Is No Such ID Or This Not Your Item</div>';
					 	redirectHome($msg,6, 'back');
					 	echo "</div>";
						 } 
			} elseif ($action == "Update") { //Update Page

				// Check if the user come across a post request
				if ($_SERVER['REQUEST_METHOD'] == "POST") {

					echo "<h1 class='text-center'>Update Item</h1>";
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
			 		$id = $_POST['itemid'];
			 		$name = $_POST['name'];
			 		$desc = $_POST['description'];
			 		$price = $_POST['price'];
			 		$country = $_POST['country'];
			 		$status = $_POST['status'];
			 		$member = $_POST['member'];
			 		$category = $_POST['category'];
			 		$tags = $_POST['tags'];

			 		// Validate The Form

			 		$formErrors = array();
		 		
			 		if (empty($name)) {
			 			$formErrors[] = "Item Name Can't Be <strong>Empty</strong>";
			 		}
			 		if (empty($desc)) {
			 			$formErrors[] = "Description Field Can't Be <strong>Empty</strong>";
			 		}			 					 		
			 		if (empty($price)) {
			 			$formErrors[] = "Price Can't Be <strong>Empty</strong>";
			 		}
			 		if (empty($country)) {
			 			$formErrors[] = "Country Can't Be <strong>Empty</strong>";
			 		}
			 		if ($status == 0) {
			 			$formErrors[] = "You Must Choose a <strong>Status</strong>";
			 		}
			 		if ($member == 0) {
			 			$formErrors[] = "You Must Choose a <strong>Member</strong>";
			 		}
			 		if ($category == 0) {
			 			$formErrors[] = "You Must Choose a <strong>Category</strong>";
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

			 			$stmt = $conn->prepare("SELECT * FROM items WHERE Item_ID = ? AND Member_ID = ?");
						$stmt->execute(array($id, $_SESSION['uid']));
						$item = $stmt->fetch();
			 			// use rand() function to generate rendom number before the name of file to not duplicate files names
			 			if (! empty($avatarName)){
			 			$avatar = rand(0, 100000) . '_' . $avatarName;
			 			move_uploaded_file($avatarTemp, "admin\uploads\items\\".$avatar);
			 			 } else {
			 			 	$avatar = $item['Image'];
			 			 } 


					 	// Update the database with the form info

					 	$stmt = $conn->prepare("UPDATE
					 								 items 
					 							SET
					 								 Name 		= ? ,
					 								 Description = ? ,
					 								 Price = ? ,
					 								 Country_Made = ?,
					 								 Image = ?,
					 								 Status = ?,
					 								 Cat_ID = ?,
					 								 Member_ID = ?,
					 								 tags = ?
					 							 WHERE
					 							 	 Item_Id = ?");
					 	$stmt->execute(array($name, $desc, $price, $country, $avatar, $status,  $category, $member, $tags, $id));

					 	// Echo Success Mesage 
					 	if ($stmt){
					 	$msg = "<div class='alert alert-success'>".$stmt->rowCount() . " Item Updated </div>";	
			 			/* This Function Redirect To Home Page, This Function Exist in functions.php file
			 			** First Parameter Is The Message That Appear To User
			 			** Second Parameter Is The Time Before Redirecting
			 			*/
			 			redirectHome($msg,6, 'back');
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

	}elseif ($action == "Delete") {

				// Delete Page


				echo "<h1 class='text-center'>Delete Item</h1>";
				echo "<div class='container'>"; // Start a container to Show the messages in it				

				// Check If Get Request ID is a numric and get the value of it to use it in the select stmt
				if (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) {
					$itemid = intval($_GET['itemid']);

				} else {
					$itemid = 0;
					
				}

					// Check If item Exist In The database
					$check = checkItem('Item_ID', 'items', $itemid);

					// If The item exist in the database, then Delete the item
					if ($check > 0) { 
						$stmt = $conn->prepare("DELETE FROM items WHERE Item_ID = ? ");
						$stmt->execute(array($itemid));

						if($stmt){
					 	$msg = "<div class='alert alert-success'>".$stmt->rowCount() . " Item Deleted </div>";	
					 	redirectHome($msg,6, 'back');
					 }
					} else {
						$msg =  "<div class='alert alert-danger'>This ID Doesn't Exist</div>";
						redirectHome($msg, 6);	
					}
					echo "</div>";
			
	}			


	} else {
		header('Location: login.php');
		exit();
	}

  include $temp.'footer.php'; 

  ob_end_flush();
		

?>		