<?php 
	ob_start();
	session_start();
	$pageTitle = 'Create New Item'; //Page Title variable for the login page that used in function getTitle in functions.php 
	include "init.php";

	if (isset($_SESSION['User'])) {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$formErrors = array();

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
					


			$name 		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
			$desc 		= filter_var($_POST['description'], FILTER_SANITIZE_STRING);
			$price 		= filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
			$country 	= filter_var($_POST['country'], FILTER_SANITIZE_STRING);
			$status 	= filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
			$category 	= filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
			$tags 		= $_POST['tags'];

			if (empty($name)) {
				
				$formErrors[] = "Item Name Can't Be Empty";	
			}
			if (empty($desc)) {
				
				$formErrors[] = "Item Description Can't Be Empty";	
			}
			if (empty($country)) {
				
				$formErrors[] = "Country Name Can't Be Empty";	
			}								
			if (empty($price)) {
				
				$formErrors[] = "Item Price Can't Be Empty";	
			}
			if (empty($status)) {
				
				$formErrors[] = "Item Status Can't Be Empty";	
			}	
			if (empty($category)) {
				
				$formErrors[] = "Item Category Can't Be Empty";	
			}
			if (! empty($avatarExtension) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
				$formErrors[] = "This Extension Is Not <strong>Allowed</strong>";
				$formErrors[] = "Allowed Extensions Are 'jpeg', 'jpg', 'png', 'gif'";
			}				

	 		// Check if there is no error, proceed the update operation

	 		if (empty($formErrors)) {

	 			// use rand() function to generate rendom number before the name of file to not duplicate files names
	 			if (! empty($avatarName)){
	 			$avatar = rand(0, 100000) . '_' . $avatarName;
	 			move_uploaded_file($avatarTemp, "admin\uploads\items\\".$avatar);
	 			 } else {
	 			 	$avatar = '';
	 			 }	 			

			 	// Insert Form Info Into The Database 

	 			$stmt = $conn->prepare("INSERT INTO 
	 									items(Name, Description, Price, Country_Made, Image, Status ,Add_Date, Cat_ID, Member_ID, tags)
	 									VALUES(:zname, :zdesc, :zprice, :zcountry, :zavatar, :zstatus ,now(), :zcat, :zmember, :ztags)	");
	 			$stmt->execute(array(
	 				'zname' 	=>  $name,
	 				'zdesc' 	=>  $desc,
	 				'zprice' 	=>  $price,
	 				'zcountry'  =>  $country,
	 				':zavatar'  =>  $avatar,
	 				'zstatus' 	=>  $status,
	 				'zcat' 		=>  $category,
	 				'zmember' 	=>  $_SESSION['uid'],
	 				'ztags'		=>  $tags

	 			));

 				// Echo Success Mesage 
	
				if ($stmt) {
					
 						$msg = "<div class='alert alert-success text-center'>Item Added</div>";

				}		
				 	 					 				
 			} 			 			

														

		}



?>

	<h1 class="text-center"><?php echo $pageTitle; ?></h1>
	<div class="create-ad block">
		<div class="container">
			<div class="card">
				<div class="card-header bg-primary text-white"><?php echo $pageTitle; ?></div>
				<div class="card-body">
					<div class="row ">
						<div class="col-lg-4">
							<figure class='figure item-box live-preview'>
								<span class='price-tag'>$<span class="live-price">0</span></span>
								<img src='image.png' alt='' class='figure-img img-fluid rounded' />
								<figcaption class='figure-caption'>
									<h3 class="live-name">Name</h3>
									<p class="live-desc">Description</p>
								</figcaption>
							</figure>					
					    </div>					
						<div class="col-lg-8">
							<form class="form-horizontal " action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">	
								<!-- Start Name Field -->
								<div class="form-group row form-group-lg">
									<label class="col-sm-2 form-control-label" for="iname">Name</label>
									<div class="col-sm-10 col-md-9">
										<input type="text" name="name" id="iname" class="form-control live" placeholder="Name of The Item"  data-class="live-name"   />
									</div>
								</div>
								<!-- End Name Field -->
								<!-- Start Description Field -->
								<div class="form-group row form-group-lg">
									<label class="col-sm-2 form-control-label" for="desc">Description</label>
									<div class="col-sm-10 col-md-9">
										<input type="text" name="description" id="desc" class="form-control live live-desc" placeholder="Description of The Item"  data-class="live-desc"  />
									</div>
								</div>
								<!-- End Description Field -->
								<!-- Start Price Field -->
								<div class="form-group row form-group-lg">
									<label class="col-sm-2 form-control-label" for="price">Price</label>
									<div class="col-sm-10 col-md-9">
										<input type="text" name="price" id="price" class="form-control live live-price" placeholder="Price of The Item"  data-class="live-price"/>
									</div>
								</div>
								<!-- End Price Field -->
								<!-- Start Country Field -->
								<div class="form-group row form-group-lg">
									<label class="col-sm-2 form-control-label" for="country">Country</label>
									<div class="col-sm-10 col-md-9">
										<input type="text" name="country" id="country" class="form-control" placeholder="Country of Made"   />
									</div>
								</div>
								<!-- End Country Field -->	
								<!-- Start Status Field -->
								<div class="form-group row form-group-lg">
									<label class="col-sm-2 form-control-label">Status</label>
									<div class="col-sm-10 col-md-9">
										<select name="status" >
											<option value="">...</option>
											<option value="1">New</option>
											<option value="2">Like New</option>
											<option value="3">Used</option>
											<option value="4">Very Old</option>
										</select>
									</div>
								</div>
								<!-- End Status Field -->
								<!-- Start Categories Field -->
								<div class="form-group row form-group-lg">
									<label class="col-sm-2 form-control-label">Category</label>
									<div class="col-sm-10 col-md-9">
										<select name="category" >
											<option value="">...</option>
											<?php 
												// getAllfrom function in functions.php return all records from a table
												$cats = getAllfrom('categories', 'ID');
												foreach ($cats as $cat) {
													echo "<option value='".$cat['ID'] ."'>".$cat['Name'] ."</option>";
												}
											?>
										</select>
									</div>
								</div>
								<!-- End Categories Field -->	
								<!-- Start Tags Field -->
								<div class="form-group row form-group-lg">
									<label class="col-sm-2 form-control-label" for="country">Tags</label>
									<div class="col-sm-10 col-md-9">
										<input type="text" name="tags" id="add-tags" class="form-control" placeholder="Tag Describe Your Item e.g. Ahmed, Handmade, Discount, Guarantee, ..."  />
									</div>
								</div>
								<!-- End Tags Field -->	
								<!-- Start Item Image Field -->
								<div class="form-group row form-group-lg">
									<label class="col-sm-2 custom-file label">Item Image</label>
									<div class="col-sm-10 col-md-9  custom-file">
										<input type="file" name="avatar" class="custom-file-input" id="avatar" style="width: 50px;">
		    							<label class="custom-file-label" for="avatar">Choose file</label>
									</div>
								</div>
								<!-- End Item Image Field -->																
								<!-- Start Submit Field -->
								<div class="form-group row">
									<div class="offset-sm-2 col-sm-10">
										<input type="submit" value="Add Item"  class="btn btn-primary btn-sm" />
									</div>
								</div>
								<!-- End Submit Field -->
							</form>
						</div>
					</div>	
					
						<?php 
						
							if (! empty($formErrors)) {
								foreach ($formErrors as $error) {
									echo "<div class='alert alert-danger'>" .$error . "</div>";
								}
							}
							if (isset($msg)) {
								echo $msg;
							}
							

						?>
					<!-- End Looping Through Errors -->
				</div>
			</div>
		</div>
	</div>	

<?php
	
	} else {
		header('Location: login.php');
		exit();
	}

  include $temp.'footer.php'; 

  ob_end_flush();
 ?>