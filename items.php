<?php 
	ob_start();
	session_start();

	$pageTitle = 'Show Items'; //Page Title variable for the login page that used in function getTitle in functions.php 


	include "init.php";

	// Check If Get Request ID is a numric and get the value of it to use it in the select stmt

	if (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) {
		$itemid = intval($_GET['itemid']);

	} else {
		$itemid = 0;
		
	}

	// Select user data from database

	$stmt = $conn->prepare("SELECT 
								items.*, 
								categories.Name AS Category_Name,
							    users.Username
							FROM 
								items  
							INNER JOIN 
								categories 
						    ON  
						    	Categories.ID = items.Cat_ID 
							INNER JOIN 
								users
						    ON
						    	 users.UserID = items.Member_ID							    	 
						    WHERE
						        Item_ID = ?
						    AND 
						    	Approve = 1    ");
	$stmt->execute(array($itemid));
	$count = $stmt->rowCount();


	if ($count > 0) {

		$item = $stmt->fetch();
	
?>

	<h1 class="text-center"><?php echo $item['Name']; ?></h1>
	<div class="container item-show">
		<div class="row item-center ">
			<div class="col-lg-4 items-show">
				<figure class='figure item-box'>
					<span class='price-tag'>$<?php echo $item['Price'] ?></span>
					<?php 
					if (empty($item['Image'])){
							echo "<img class='rounded profile-image ' src='admin/uploads/avatars/image.png' alt='' />";
					} else {
						echo "<img class='rounded  profile-image' src='admin/uploads/items/" . $item['Image'] . "' alt='' />";
					}
					?>
					<figcaption class='figure-caption'>
						<h3><a href='items.php?itemid=<?php echo $item["Item_ID"]?>' ><?php echo $item['Name'] ?></a></h3>
						<p><?php echo $item["Description"] ?></p>
						<div class="date"><?php echo $item["Add_Date"] ?></div>
					</figcaption>
				</figure>	
			</div>
			<div class="col-lg-8 item-info">
				
				<p class="text-center"><?php echo $item['Description']; ?></p>
				<ul class="list-unstyled">
					<li>
						<i class="far fa-calendar-alt fa-fw"></i>
						<span>Added Date</span> : <?php echo $item['Add_Date']; ?>
					</li>
					<li>
						<i class="fas fa-money-bill-wave fa-fw"></i>
						<span>Price</span> : $<?php echo $item['Price']; ?>
					</li>
					<li>
						<i class="fas fa-flag fa-fw"></i>
						<span>Made In</span> : <?php echo $item['Country_Made']; ?>
					</li>
					<li>
						<i class="fas fa-tags fa-fw"></i>
						<span>Category</span> : <a href="categories.php?pageid=<?php echo $item['Cat_ID']; ?>&pagename=<?php echo
						$item['Name'];?>"><?php echo $item['Category_Name']; ?></a>
					</li>
					<li>
						<i class="fas fa-user fa-fw"></i>
						<span>Added By</span> : <a href="publicProfile.php?name=<?php echo $item['Username']; ?>"><?php echo $item['Username']; ?></a>
					</li>
					<li class="tags-items">
						<i class="fas fa-tags fa-fw"></i>
						<span>Tags</span> :
						<?php
							$allTags = explode(",", $item['tags']);
							foreach ($allTags as $tag) {
								$tag = str_replace(' ', '', $tag);
								if (! empty($tag)){
								echo "<a href='tags.php?name=".strtolower($tag)."'>".$tag . "</a> " ;
							}
							}
						 ?>
					</li>					
				</ul>
			</div>
		</div>
		
		<hr class="custom-hr">

		<?php if (isset($_SESSION['User'])) { 	?>
		<!-- Start Add Comment Section -->
		<div class="row">
			<div class="offset-md-3">
				<div class="add-comment">
					<h3>Add Your Comment</h3>
					<!-- Here We concatenate '?itemid=' with php_self as when submit it send the form info to items.php page -->
					<!-- and the complete link of the page is items.php?itemid= so we make the concatenate -->
					<form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['Item_ID'] ?>" method="POST">
						<textarea name="comment" ></textarea>
						<input class="btn btn-primary" type="submit" value="Add Comment" />
					</form>
					<?php
						if ($_SERVER['REQUEST_METHOD'] == 'POST') {
							$comment =  filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
							$userid = $_SESSION['uid'];
							$itemid = $item['Item_ID'];

							if (! empty($comment)) {
								$stmt = $conn->prepare("INSERT INTO
													 comments(Comment, Status, Comment_Date, Item_ID, User_ID) 
													 VALUES ( :zcomment, 0, NOW(), :zitemid, :zuserid) "); 
								$stmt->execute(array(

									':zcomment' => $comment,
									':zitemid' => $itemid,
									':zuserid' => $userid  
								));

								if($stmt){
									echo "<div class='alert alert-success'>Comment Added</div>";
								}
							} else {
								echo "<div class='alert alert-danger'>You Must Write A Comment</div>";
							}
						}

					 ?>
				</div>
			</div>
		</div>
		<!-- End Add Comment Section -->
	<?php } else {
		echo "<div style='margin-bottom: 20px;'>";
			echo "<a href='login.php'>Login</a> Or <a href='login.php'>Register</a> To Add Comment";
		echo "</div>";
	} ?>
		<hr class="custom-hr">
		<?php 
		// Select All Comments
		$stmt = $conn->prepare("SELECT
									comments.*, users.Username, users.avatar 
								FROM 
									comments
								INNER JOIN 
									users
								ON 
									comments.User_ID = users.UserID
								WHERE Item_ID = ?	
								AND Status = 1 
								ORDER BY 
									C_ID DESC		");
		$stmt->execute(array($item['Item_ID'])); 
		$comments = $stmt->fetchAll();
		?>


		<?php foreach ($comments as $comment) { ?>
			<div class="comment-box">
				<div class='row'>
					<div class='col-md-2 text-center'>
						<?php
						if (empty($comment['avatar'])){
								echo "<img class='img-fluid rounded-circle img-thumbnail d-block mx-auto' src='admin/uploads/avatars/image.png' alt='' />";
						} else {
							echo "<img class='img-fluid rounded-circle img-thumbnail d-block mx-auto' src='admin/uploads/avatars/" . $comment['avatar'] . "' alt='' />";
						}
						 echo  $comment['Username'] 
						?>
					</div>
					<div class='col-md-10'>
						<p class="lead"><?php echo  $comment['Comment'] ?></p>
					</div>
				</div>
			</div>
			<hr class="custom-hr">	
	<?php 	}

		?>		

	</div>

		
<?php
	} else {
		echo "<div class='container'>";
			$theMsg = "<div class='alert alert-danger'>There's No Such ID Or Item Is Waiting Approval</div>";
			redirectHome($theMsg, 6, 'back');
		echo "</div>";
	}

  include $temp.'footer.php'; 

  ob_end_flush();
 ?>