<?php
	ob_start();
	session_start();

	$pageTitle = $_GET['pagename']; //Page Title variable for the login page that used in function getTitle in functions.php 

	include "init.php"; 
?>

	<div class="container show-all">
		<h1 class="text-center"><?php echo $_GET['pagename']; ?></h1>
		<div class="row show-all-row">
			<?php 
				$numOfCols = 4;
				$rowCount = 0;
				$bootstrapColWidth = 12 / $numOfCols;			
				if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])){
				$catID = intval($_GET['pageid']); /* Get ID Of Category From Get Request */
				$catItems = getItems('Cat_ID', $catID); /* Function getItems($where, $value) return All items in category whose id is sent in the get request */
				foreach ($catItems as $item) {
					if ($item['Approve'] == 1 ){
					echo "<div class='col-md-<?php echo $bootstrapColWidth;?>'>";
						echo "<figure class='figure item-box'>";
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
		} else {
			echo "<div class='container'>";
				echo "<div class='alert alert-danger'>Page ID Not Identified</div>";
			echo "</div>";	
		}

			?>
	   </div>
	</div>

<?php  include $temp.'footer.php'; ?>