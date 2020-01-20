<?php 
	ob_start();
	session_start();

	$pageTitle = $_GET['name']; //Page Title variable for the login page that used in function getTitle in functions.php 


	include "init.php";

	if (isset($_SESSION['User'])) {

		$user = $_GET['name'];

		$getUser = $conn->prepare("SELECT * FROM users WHERE Username = ?");

		$getUser->execute(array($user));

		$info = $getUser->fetch();

		$count = $getUser->rowcount();

		if ($count > 0){
?>

	<h1 class="text-center"><?php echo $user.' Profile'; ?></h1>

	<div class="information block">
		<div class="container">
			<div class="img-container text-center">
			<?php
				if (empty($info['avatar'])){
					echo "<img class='rounded profile-image ' src='admin/uploads/avatars/image.png' alt='' />";
				} else {
				echo "<img class='rounded  profile-image' src='admin/uploads/avatars/" . $info['avatar'] . "' alt='' />";
				}		
			?>
			</div>	
			<div class="card">
				<div class="card-header bg-primary text-white"><?php echo $user.' Information';  ?></div>
				<div class="card-body">
					<ul class="list-unstyled">
						<li>
							<i class="fas fa-unlock-alt fa-fw"></i>
							<span>User Name</span> : <?php echo $info['Username']; ?>
					    </li>
						<li>
							<i class="far fa-envelope fa-fw"></i>
							<span>Email</span> : <?php echo $info['Email']; ?>
						</li>
						<li>
							<i class="fas fa-user fa-fw"></i>
							<span>Full Name</span> : <?php echo $info['FullName']; ?>
						</li>
						<li>
							<i class="far fa-calendar-alt fa-fw"></i>
							<span>Register Date</span> : <?php echo $info['Date']; ?>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="my-ads block">
		<div class="container">
			<div class="card">
				<div class="card-header bg-primary text-white"><?php echo $user.' Items'; ?></div>
				<div class="card-body show-all">
						<?php 
							$numOfCols = 4;
							$rowCount = 0;
							$bootstrapColWidth = 12 / $numOfCols;							
							$userID = $info['UserID']; /* Get ID Of User */
							$userItems = getItems('Member_ID', $userID); /* Function getItems($where, $value) return All items in category whose id is sent in the get request */
							if (! empty($userItems)){
							echo "<div class='row show-all-row'>";	
							foreach ($userItems as $item) {
								if($item['Approve'] == 1){
								echo "<div class='col-md-<?php echo $bootstrapColWidth;?>'>";
									echo "<figure class='figure item-box'>";
										if ($item['Approve'] == 0) { echo "<span class='approve-status'>Waiting Approval</span>"; };
										echo "<span class='price-tag'>$" .$item['Price'] . "</span>";
										if (empty($item['Image'])){
												echo "<img class='rounded profile-image ' src='admin/uploads/avatars/image.png' alt='' />";
										} else {
											echo "<img class='rounded  profile-image' src='admin/uploads/items/" . $item['Image'] . "' alt='' />";
										}
										echo "<figcaption class='figure-caption'>";
											echo "<h3><a href='items.php?itemid=" .$item['Item_ID'] . "'>" .$item['Name'] . "</a></h3>";
											echo "<p>" .$item['Description'] . "</p>";
											echo "<div class='date'>" .$item['Add_Date'] . "</div>";
										echo "</figcaption>";
									echo "</figure>";
								echo "</div>";
								$rowCount++;
			   					if($rowCount % $numOfCols == 0) {echo '</div><div class="row show-all-row small">';}								
							}
						}
							echo "</div>";
						} else {
							echo "There're No Ads To Show, Create <a href='newad.php'>New Ad</a>";
						}

						?>

				</div>
			</div>
		</div>
	</div>
	<div class="my-comments block">
		<div class="container">
			<div class="card">
				<div class="card-header bg-primary text-white"><?php echo $user." Latest Comments"; ?></div>
				<div class="card-body">
					<?php 
					// Select All Comments
					$stmt = $conn->prepare("SELECT
												comments.*, users.Username, items.Name 
											FROM 
												comments
											INNER JOIN 
												users
											ON 
												comments.User_ID = users.UserID
											INNER JOIN 
												items
											ON 
												comments.Item_ID = items.Item_ID											
											WHERE User_ID = ?	
											AND Comments.Status = 1 
											ORDER BY 
												C_ID DESC		");
					$stmt->execute(array($info['UserID'])); 
					$comments = $stmt->fetchAll();
					?>


					<?php foreach ($comments as $comment) { ?>
						<div class="comment-box">
							<div class='row'>
								<div class='col-sm-2 text-center item-name'>
									<?php echo  $comment['Name'] ?>
								</div>
								<div class='col-sm-10'>
									<p class="lead"><?php echo  $comment['Comment'] ?></p>
								</div>
							</div>
						</div>
						<hr class="custom-hr">
					<?php } ?>
				</div>
			</div>
		</div>
	</div>			
<?php
	} else {
		echo "<div class='container'>";
			$theMsg = "<div class='alert alert-danger'>There's No Such User</div>";
			redirectHome($theMsg, 6, 'back');
		echo "</div>";
	}
	}else {
		header('Location: login.php');
		exit();
	}

  include $temp.'footer.php'; 

  ob_end_flush();
 ?>