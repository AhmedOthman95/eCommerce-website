<?php 
	session_start();
	// Redirect to dashboard if user has registered
	if (isset($_SESSION['loggedIn'])) {

		$pageTitle = 'Dashboard'; //Page Title variable for the login page that used in function getTitle in functions.php

		include "init.php";

		/* Start Dashboard Page */
		
		?>
		<div class="container home-stats text-center">
			<h1>Dashboard</h1>
			<div class="row">
				<div class="col-md-6 col-lg-3">
					<div class="stat st-members">
						<i class="fa fa-users"></i>
						<div class="info">
							Total Members
							<span>
								<a href="members.php" target="_blank"><?php echo countItems('UserID', 'users'); ?></a>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-lg-3">
					<div class="stat st-pending">
						<i class="fa fa-user-plus"></i>
						<div class="info">
							Pending Members
							<span>
								<a href="members.php?action=Manage&page=Pending"><?php echo checkItem("RegStatus", "users", 0) ?></a>
							</span>							
						</div>
					</div>
				</div>
				<div class="col-md-6 col-lg-3">
					<div class="stat st-items">
						<div class="icon">
							<i class="fa fa-tag"></i>
						</div>
						<div class="info">
							Total Items
							<span>
								<a href="items.php" target="_blank"><?php echo countItems('Item_ID', 'items'); ?></a>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-lg-3">
					<div class="stat st-comments">
						<i class="fas fa-comments"></i>
						<div class="info">
							Total Comments
							<span><a href="comments.php" target="_blank"><?php echo countItems('C_ID', 'comments'); ?></a></span>
						</div>	
					</div>
				</div>												
			</div>
		</div>

		<div class="container latest">
			<!-- Start Lates Users And Items -->
			<div class="row latest-row">
				<div class="col-sm-6">
					<div class="card">
						<div class="card-header">
							<i class="fa fa-users"></i> Latest 5 Registered Users
							<span class="toggle-info pull-right">
								<i class="fa fa-minus fa-lg"></i>
							</span>
						</div>
						<div class="card-body">
							<ul class="list-unstyled latest-users">
								<?php
									$theLatestUsers = getLatest("*", "users", "UserID",5);
									if (!empty($theLatestUsers)) {
									foreach ($theLatestUsers as $user) {
										echo '<li>' . $user['Username'] .
											 '<a href="members.php?action=Edit&userid='.$user['UserID'].'">
											 	 <span class="btn btn-success pull-right">
													 <i class="fa fa-edit"></i>Edit';
													if ($user['RegStatus'] == 0) {
														echo "<a href='members.php?action=Activate&userid=" .$user['UserID'] ."' class='btn btn-info pull-right activate'><i class='fa fa-check icon'></i>Activate</a>";
													}										 		 	 
										echo '</a></span>
										 </li>';
									} 
								  } else {
								  	echo "There're No Users To Show";
								  }	
								?>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="card">
						<div class="card-header">
							<i class="fa fa-tag"></i> Latest 5 Items
							<span class="toggle-info pull-right">
								<i class="fa fa-minus fa-lg"></i>
							</span>							
						</div>
						<div class="card-body">
							<ul class="list-unstyled latest-users">
								<?php
									$theLatestItems = getLatest("*", "items", "Item_ID",5);
									if (!empty($theLatestItems)){
									foreach ($theLatestItems as $item) {
										echo '<li>' . $item['Name'] .
											 '<a href="items.php?action=Edit&itemid='.$item['Item_ID'].'">
											 	 <span class="btn btn-success pull-right">
													 <i class="fa fa-edit"></i>Edit';
													if ($item['Approve'] == 0) {
														echo "<a href='items.php?action=Approve&itemid=" .$item['Item_ID'] ."' class='btn btn-info pull-right activate'><i class='fa fa-check icon'></i>Approve</a>";
													}										 		 	 
										echo '</a></span>
										 </li>';
									}
							      }	else {
							      		echo "There're No Items To Show";
							      } 
								?>
							</ul>
						</div>
					</div>
				</div>				
			</div>
			<!-- End Lates Users And Items -->
			<!-- Start Lates Comments -->
			<div class="row latest-row">
				<div class="col-sm-6">
					<div class="card">
						<div class="card-header">
							<i class="fas fa-comments"></i> Latest 5 Comments
							<span class="toggle-info pull-right">
								<i class="fa fa-minus fa-lg"></i>
							</span>
						</div>
						<div class="card-body">
							<?php 
								// Select All Comments
								$stmt = $conn->prepare("SELECT
															comments.*, users.Username, users.UserID 
														FROM 
															comments
														INNER JOIN 
															users
														ON 
															comments.User_ID = users.UserID 
														ORDER BY 
															C_ID DESC	
														LIMIT 5	");
								$stmt->execute(); 
								$rows = $stmt->fetchAll();
								if (!empty($rows)){
								foreach ($rows as $row) {
									echo "<div class='comment-box'>";
										echo "<span class='member-name'><a href='members.php?action=Edit&userid=".$row['UserID']."'>".$row['Username']."</a></span>";
										echo "<p class='member-comment'>".$row['Comment']."</p>";
									echo "</div>";
								}
							  } else {
							  	echo "There're No Comments To Show";
							  }	
							?>
						</div>
					</div>
				</div>			
			</div>
			<!-- End Lates Comments -->
		</div>
		
		<?php  /* End Dashboard Page */
		include $temp.'footer.php';
	} else {
			header('Location: index.php'); //Redirect to index page
			exit();
	}