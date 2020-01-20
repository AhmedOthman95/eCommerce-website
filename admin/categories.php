<?php

	/*
		======================================
		== Category Page
		======================================
	*/

	session_start();
	$pageTitle = 'Categories';

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

	if ($action == "Manage") {

		$sort = 'ASC';
		$sort_array = array('ASC' , 'DESC' );

		if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
			$sort = $_GET['sort'];
		}

		$stmt3 = $conn->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordering $sort");
		$stmt3->execute();
		$cats = $stmt3->fetchAll();
		if (!empty($cats)) {
			
		 ?>

		<h1 class="text-center">Manage Categories</h1>
		<div class="container categories">
			<div class="card">
				<div class="card-header">
					<i class="fa fa-edit"></i>Manage Categories
					<div class="option pull-right">
						<i class="fa fa-sort"></i> Ordering: [
						<a class="<?php if ($sort == 'ASC'){ echo 'active'; } ?>" href="?sort=ASC">Asc</a>  |
						<a class="<?php if ($sort == 'DESC'){ echo 'active'; } ?>" href="?sort=DESC">Desc</a> ]
						<i class="fa fa-eye"></i> View:[
						<span class="active" data-view="full">Full</span>  |
						<span data-view="classic">Classic</span> ]
					</div>	
				</div>
				<div class="card-body">
					<?php 
						foreach ($cats as $cat) {
							echo "<div class='cat'>";
								echo "<div class='hidden-buttons'>";
									echo "<a href='categories.php?action=Edit&catid=".$cat['ID'] ."' class='btn btn-success btn-sm'><i class='fa fa-edit'></i>Edit</a>";
									echo "<a href='categories.php?action=Delete&catid=".$cat['ID'] ."' class='confirm btn btn-danger btn-sm'><i class='fa fa-close'></i>Delete</a>";
								echo "</div>";
								echo '<h3>'.$cat['Name'] . '</h3>';
								echo "<div class='full-view'>";
									echo '<p>';
									if ($cat['Description'] == '') {echo "This Category Has No Description"; } 
									else { echo $cat['Description']; }
									echo '</p>';									
									// Get Child Categories Of Each Parent Category
									$stmt4 = $conn->prepare("SELECT * FROM categories WHERE parent = {$cat['ID']} ORDER BY Ordering $sort");
									$stmt4->execute();
									$childCategory = $stmt4->fetchAll();
									if (! empty($childCategory)){
									echo "<h5 class='child-head'>Child Categories</h5>";
									echo "<ul class='list-unstyled child-cats'>";	
									foreach ($childCategory as $c ) {
											echo "<li class='child-link'>
													<a href='categories.php?action=Edit&catid=".$c['ID'] ."'>" .$c['Name']."</a>
													<a href='categories.php?action=Delete&catid=".$c['ID'] ."' class='show-delete confirm'>Delete</a>
												</li>";
									}
									echo "</ul>";
									}										
									if ($cat['Visibility'] == 1) { echo '<span class="global-span visible"><i class="fas fa-eye-slash"></i>Hidden</span>'; }
									if ($cat['Allow_Comment'] == 1) { echo '<span class="global-span comment"><i class="fas fa-ban"></i>Comment Disabled</span>'; }
									if ($cat['Allow_Ads'] == 1) { echo '<span class="global-span Ad"><i class="fas fa-ban"></i>Ads Disabled</span>'; }
								echo "</div>";
							echo "</div>";
							

							echo "<hr>";									
						}
					?>
				</div>
			</div>
			<a class="add-category btn btn-sm btn-primary" href="categories.php?action=Add"><i class="fa fa-plus" style="padding: 3px 6px;"></i> New Category</a>
		</div>
	<?php } else{
				echo "<div class='container'>";
					echo "<div class='nice-message'>There're No Categories To Show</div>";
					echo '<a class="add-category btn btn-sm btn-primary" href="categories.php?action=Add"><i class="fa fa-plus" style="padding: 3px 6px;"></i> New Category</a>';
				echo "</div>";		
	} ?>
<?php
	}elseif ($action == "Add") { // Add Page ?>

				<h1 class="text-center">Add New Category</h1>
				<div class="container">
					<form class="form-horizontal " action="?action=Insert" method="POST">	
						<!-- Start Name Field -->
						<div class="form-group row form-group-lg">
							<label class="col-sm-2 form-control-label" for="cname">Name</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="name" id="cname" class="form-control" autocomplete="off" placeholder="Name Of The Category" required="required" />
							</div>
						</div>
						<!-- End Name Field -->
						<!-- Start Description Field -->
						<div class="form-group row form-group-lg">
							<label class="col-sm-2" for="desc">Description</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="description" id="desc" class="form-control" placeholder="Describe The Category" />
							</div>
						</div>
						<!-- End Description Field -->
						<!-- Start Ordering Field -->
						<div class="form-group row form-group-lg">
							<label class="col-sm-2" for="order">Ordering</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="ordering" id="order" placeholder="Number For Ordering The Categories" class="form-control" />
							</div>
						</div>
						<!-- End Ordering Field -->
						<!-- Start Cetegory Type field -->
						<div class="form-group row form-group-lg">
							<label class="col-sm-2" for="order">Category Parent</label>
							<div class="col-sm-10 col-md-6">
								<select name="parent">
									<option value="0">None</option>
										<?php 

										// getAllfrom function in functions.php return all records from a table
										global $conn;

										$getCat = $conn->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY ID ");
										$getCat->execute();
										$cats = $getCat->fetchAll();
										foreach ($cats as $cat) {
											echo "<option value='".$cat['ID'] ."'>".$cat['Name'] ."</option>";
										}
										?>
								</select>
							</div>
						</div>
						<!-- End Cetegory Type field -->
						<!-- Start Visibility Field -->
						<div class="form-group row form-group-lg">
							<label class="col-sm-2">Visible</label>
							<div class="col-sm-10 col-md-6">
								<div>
									<input id="vis-yes" type="radio" name="visibility" value="0" checked />
									<label for="vis-yes">Yes</label>
								</div>
								<div>
									<input id="vis-no" type="radio" name="visibility" value="1" />
									<label for="vis-no">No</label>
								</div>								
							</div>
						</div>
						<!-- End Visibility Field -->
						<!-- Start Commenting Field -->
						<div class="form-group row form-group-lg">
							<label class="col-sm-2">Allow Commenting</label>
							<div class="col-sm-10 col-md-6">
								<div>
									<input id="com-yes" type="radio" name="commenting" value="0" checked />
									<label for="com-yes">Yes</label>
								</div>
								<div>
									<input id="com-no" type="radio" name="commenting" value="1" />
									<label for="com-no">No</label>
								</div>								
							</div>
						</div>
						<!-- End Commenting Field -->
						<!-- Start Ads Field -->
						<div class="form-group row form-group-lg">
							<label class="col-sm-2">Allow Ads</label>
							<div class="col-sm-10 col-md-6">
								<div>
									<input id="ads-yes" type="radio" name="ads" value="0" checked />
									<label for="ads-yes">Yes</label>
								</div>
								<div>
									<input id="ads-no" type="radio" name="ads" value="1" />
									<label for="ads-no">No</label>
								</div>								
							</div>
						</div>
						<!-- End Ads Field -->												
						<!-- Start Submit Field -->
						<div class="form-group row">
							<div class="offset-sm-2 col-sm-10">
								<input type="submit" value="Add Category"  class="btn btn-primary btn-lg" />
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

					echo "<h1 class='text-center'>Insert Category</h1>";
					echo "<div class='container'>"; // Start a container to Show the messages in it

			 		// Get Variables From The Form
			 		$name 		= $_POST['name'];
			 		$desc 		= $_POST['description'];
			 		$parent 	= $_POST['parent'];
			 		$order 		= intval($_POST['ordering']);
			 		$visible 	= $_POST['visibility'];
			 		$comment 	= $_POST['commenting'];
			 		$ads 		= $_POST['ads'];

		 			// Check If Category Exists In The Database Using checkItem Function That Exists In functions.php file
		 			$check = checkItem("Name", "categories", $name);
		 			if ($check == 1) {
		 				$msg = "<div class='alert alert-danger'>This Category Already Exists.</div>";
		 				redirectHome($msg, 6, 'back');
		 			} else{

					 	// Insert Category Info Into The Database 

			 			$stmt = $conn->prepare("INSERT INTO 
			 									categories(Name, Description, parent, Ordering, Visibility, Allow_Comment ,Allow_Ads)
			 									VALUES(:zname, :zdesc, :zparent, :zorder, :zvisible, :zcomment , :zads)	");
			 			$stmt->execute(array(
			 				'zname'		 => $name,
			 				'zdesc' 	 => $desc,
			 				'zparent'	 => $parent,	
			 				'zorder' 	 => $order,
			 				'zvisible'   => $visible,
			 				'zcomment'   => $comment,
			 				'zads'		 => $ads 

			 			));

					 	// Echo Success Mesage 
					 	if($stmt){
					 	$msg = "<div class='alert alert-success'>".$stmt->rowCount() . " Category Inserted </div>";	

					 	redirectHome($msg,6, 'back');	
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
			 		redirectHome($msg,6, 'back');
			 		echo "</div>";
			 	}
			 	echo "</div>";
	}elseif ($action == "Edit") {
			
				// Edit Page 

				// Check If Get Request ID is a numeric and get the value of it to use it in the select stmt
				if (isset($_GET['catid']) && is_numeric($_GET['catid'])) {
					$catid = intval($_GET['catid']);

				} else {
					$catid = 0;
					
				}

					// Select category data from database

					$stmt = $conn->prepare("SELECT * FROM categories WHERE ID = ? ");
					$stmt->execute(array($catid));
					$cat = $stmt->fetch();
					$count = $stmt->rowCount();

					// If The category exist in the database, then show the form
				if ($count > 0) { ?>

					<h1 class="text-center">Edit Category</h1>
					<div class="container">
						<form class="form-horizontal " action="?action=Update" method="POST">	
						 <!-- Hidden Field to send the category ID with the form data to use it in the database -->
						<input type="hidden" name="catid" value="<?php echo $catid  ?>" />							
							<!-- Start Name Field -->
							<div class="form-group row form-group-lg">
								<label class="col-sm-2 form-control-label" for="cname">Name</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="name" id="cname" class="form-control" placeholder="Name Of The Category" required="required" value="<?php echo $cat['Name']; ?>" />
								</div>
							</div>
							<!-- End Name Field -->
							<!-- Start Description Field -->
							<div class="form-group row form-group-lg">
								<label class="col-sm-2" for="desc">Description</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="description" id="desc" class="form-control" placeholder="Describe The Category" value="<?php echo $cat['Description']; ?>"/>
								</div>
							</div>
							<!-- End Description Field -->
							<!-- Start Ordering Field -->
							<div class="form-group row form-group-lg">
								<label class="col-sm-2" for="order">Ordering</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="ordering" id="order" placeholder="Number For Ordering The Categories" class="form-control" value="<?php echo $cat['Ordering']; ?>" />
								</div>
							</div>
							<!-- End Ordering Field -->
							<!-- Start Cetegory Type field -->
							<div class="form-group row form-group-lg">
								<label class="col-sm-2" for="order">Category Parent</label>
								<div class="col-sm-10 col-md-6">
									<select name="parent">
										<option value="0">None</option>
											<?php 

											// getAllfrom function in functions.php return all records from a table
											global $conn;

											$getCat = $conn->prepare("SELECT * FROM categories WHERE parent = 0  ORDER BY ID ");
											$getCat->execute();
											$cats = $getCat->fetchAll();
											foreach ($cats as $c) {
												echo "<option value='".$c['ID'] ."'" ;
												  if($cat['parent'] == $c['ID']){echo 'selected';}; 
												echo ">".$c['Name'] ."</option>";
											}
											?>
									</select>
								</div>
							</div>
							<!-- End Cetegory Type field -->							
							<!-- Start Visibility Field -->
							<div class="form-group row form-group-lg">
								<label class="col-sm-2">Visible</label>
								<div class="col-sm-10 col-md-6">
									<div>
										<input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($cat['Visibility'] == 0) { echo 'checked';} ?> />
										<label for="vis-yes">Yes</label>
									</div>
									<div>
										<input id="vis-no" type="radio" name="visibility" value="1" <?php if ($cat['Visibility'] == 1) { echo 'checked';} ?> />
										<label for="vis-no">No</label>
									</div>								
								</div>
							</div>
							<!-- End Visibility Field -->
							<!-- Start Commenting Field -->
							<div class="form-group row form-group-lg">
								<label class="col-sm-2">Allow Commenting</label>
								<div class="col-sm-10 col-md-6">
									<div>
										<input id="com-yes" type="radio" name="commenting" value="0" <?php if ($cat['Allow_Comment'] == 0) { echo 'checked';} ?> />
										<label for="com-yes">Yes</label>
									</div>
									<div>
										<input id="com-no" type="radio" name="commenting" value="1" <?php if ($cat['Allow_Comment'] == 1) { echo 'checked';} ?>/>
										<label for="com-no">No</label>
									</div>								
								</div>
							</div>
							<!-- End Commenting Field -->
							<!-- Start Ads Field -->
							<div class="form-group row form-group-lg">
								<label class="col-sm-2">Allow Ads</label>
								<div class="col-sm-10 col-md-6">
									<div>
										<input id="ads-yes" type="radio" name="ads" value="0" <?php if ($cat['Allow_Ads'] == 0) { echo 'checked';} ?> />
										<label for="ads-yes">Yes</label>
									</div>
									<div>
										<input id="ads-no" type="radio" name="ads" value="1" <?php if ($cat['Allow_Ads'] == 1) { echo 'checked';} ?> />
										<label for="ads-no">No</label>
									</div>								
								</div>
							</div>
							<!-- End Ads Field -->												
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
	}elseif ($action == "Update") {

				// Check if the user come across a post request
				if ($_SERVER['REQUEST_METHOD'] == "POST") {

					echo "<h1 class='text-center'>Update Category</h1>";
					echo "<div class='container'>"; // Start a container to Show the messages in it

			 		// Get Variables From The Form
			 		$id 		= $_POST['catid'];
			 		$name 		= $_POST['name'];
			 		$desc 		= $_POST['description'];
			 		$order 		= $_POST['ordering'];
			 		$parent 	= $_POST['parent'];
			 		$visible 	= $_POST['visibility'];
			 		$comment 	= $_POST['commenting'];
			 		$ads 		= $_POST['ads'];

				 	// Update the database with the form info
				 	$stmt = $conn->prepare("UPDATE
				 							 	categories
				 							 SET
				 							 	Name 			= ? ,
				 							 	Description 	= ? ,
				 							 	parent 			= ? , 
				 							 	Ordering 		= ? ,
				 							 	Visibility 		= ? ,
				 							 	Allow_Comment 	= ? ,
				 							 	Allow_Ads 		= ?
				 							 WHERE 
				 							 	ID = ?");
				 	$stmt->execute(array($name, $desc, $parent, $order, $visible, $comment, $ads, $id));

				 	// Echo Success Mesage 
				 	if($stmt){
				 	$msg = "<div class='alert alert-success'>".$stmt->rowCount() . " Category Updated </div>";	
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
	}elseif ($action == "Delete") {

				// Delete Page

				echo "<h1 class='text-center'>Delete Category</h1>";
				echo "<div class='container'>"; // Start a container to Show the messages in it				

				// Check If Get Request ID is a numric and get the value of it to use it in the select stmt
				if (isset($_GET['catid']) && is_numeric($_GET['catid'])) {
					$catid = intval($_GET['catid']);

				} else {
					$catid = 0;
					
				}

					// Check If category Exist In The database
					$check = checkItem('ID', 'categories', $catid);

					// If The Category exist in the database, then Delet the Category
					if ($check > 0) { 
						$stmt = $conn->prepare("DELETE FROM categories WHERE ID = ? ");
						$stmt->execute(array($catid));


						if($stmt){
					 	$msg = "<div class='alert alert-success'>".$stmt->rowCount() . " Category Deleted </div>";	
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


 