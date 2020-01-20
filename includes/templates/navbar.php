
<div class="upper-bar">
	<div class="container">
		<?php 
			if (isset($_SESSION['User'])) { 
				global $conn;
				$getAll = $conn->prepare("SELECT * FROM users  WHERE Username = ? ");
				$getAll->execute(array($_SESSION['User']));
				$rows = $getAll->fetchAll();
				foreach ($rows as $row) {				
				if (empty($row['avatar'])){
					echo "<img class='rounded-circle profile-image' src='admin/uploads/avatars/image.png' alt='' />";
				} else {
				echo "<img class='rounded-circle  profile-image' src='admin/uploads/avatars/" . $row['avatar'] . "' alt='' />";
				}
			}
 ?>
					
				<div class="dropdown my-info pull-right ">
				  <span class="btn dropdown-toggle" data-toggle="dropdown" id="dropdownMenu">
				  	<?php echo $sessionUser; ?><span class="caret"></span>
				  </span>
				  <div class="dropdown-menu" aria-labelledby="dropdownMenu">
				  	<li><a class="dropdown-item" href="profile.php">My Profile</a></li>
				  	<li><a class="dropdown-item" href="newad.php">New Item</a></li>
				  	<li><a class="dropdown-item" href="logout.php">Logout</a></li>
				  </div>
				</div>

				<?php 

			} else {

		?>
		<div style="overflow: hidden;">
			<a href="login.php">
				<span class="pull-right login">Login/Signup</span>
			</a>
		</div>
		<?php } ?>
	</div>
</div>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<div class="container">
	  <a class="navbar-brand" href="index.php">HomePage</a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  </button>

	  <div class="collapse navbar-collapse" id="app-nav">
	    <ul class="navbar-nav ml-auto">
	    	<?php
				foreach (getParentCat() as $cat) {
					echo
					 "<li class='nav-link'>
					 	<a class='cat-link' href='categories.php?pageid=" .$cat['ID']."&pagename=" .$cat['Name'] . "'>"
					 	. $cat['Name'] . 
					   "</a>
					 </li>";
				}
	    	 ?>
	      </ul>
    </div>
   </div> 
</nav>

