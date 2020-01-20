<?php
	ob_start();
	session_start();
	$pageTitle = strtoupper($_GET['name']);
	 include "init.php";

?>

	<div class="container show-all">
		<?php
			echo "<h1 class='text-center'>".strtoupper($_GET['name'])."</h1>";
		 ?>
		<div class="row show-all-row">
			<?php 
				$numOfCols = 4;
				$rowCount = 0;
				$bootstrapColWidth = 12 / $numOfCols;			
				global $conn;
				if (isset($_GET['name'])){
				$tagName = $_GET['name']; /* Get Name Of TagFrom Get Request */	
				$getTag = $conn->prepare("SELECT * FROM items WHERE tags LIKE '%$tagName%' ORDER BY Item_ID ");
				$getTag->execute();
				$tags = $getTag->fetchAll();
				foreach ($tags as $tag) {
					if ($tag['Approve'] == 1 ){
					echo "<div class='col-md-<?php echo $bootstrapColWidth;?>'>";
						echo "<figure class='figure item-box'>";
							echo "<span class='price-tag'>$" .$tag['Price'] . "</span>";
							if (empty($tag['Image'])){
									echo "<img class='rounded profile-image ' src='admin/uploads/avatars/image.png' alt='' />";
							} else {
								echo "<img class='rounded  profile-image' src='admin/uploads/items/" . $tag['Image'] . "' alt='' />";
							}
							echo "<figcaption class='figure-caption'>";
								echo "<h3><a href='items.php?itemid=" .$tag['Item_ID'] . "'>" .$tag['Name'] . "</a></h3>";
								echo "<p>" .$tag['Description'] . "</p>";
								echo "<div class='date'>" .$tag['Add_Date'] . "</div>";
							echo "</figcaption>";
						echo "</figure>";
					echo "</div>";
					$rowCount++;
   					if($rowCount % $numOfCols == 0) {echo '</div><div class="row show-all-row small">';}
				} 
			}
		} else {
			echo "<div class='container'>";
				echo "<div class='alert alert-danger'>You Must Enter Tag Name</div>";
			echo "</div>";	
		}

			?>
	   </div>
	</div>

<?php  include $temp.'footer.php'; ?>