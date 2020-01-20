<?php 
	ob_start();
	session_start();

	$pageTitle = 'Homepage'; //Page Title variable for the login page that used in function getTitle in functions.php 


	include "init.php";
?>
	<div class="container show-all">
		<div class="row show-all-row">
			<?php 
				$numOfCols = 4;
				$rowCount = 0;
				$bootstrapColWidth = 12 / $numOfCols;
				$getAll = getAllfrom('items', 'Item_ID');
				foreach ($getAll as $item) {
					if ($item['Approve'] == 1 ){
					echo "<div class=' col-md-<?php echo $bootstrapColWidth;?>'>";
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
								
							echo "</figcaption>";
							echo "<div class='date'>" .$item['Add_Date'] . "</div>";
						echo "</figure>";
					echo "</div>";
					   $rowCount++;
   					 if($rowCount % $numOfCols == 0) {echo '</div><div class="row show-all-row small">';}
				}
			}

			?>
	   </div>

	</div>
	<div class="footer">
   		<p>Copyright &copy; 2020 All Rights Reserved By &reg; Ahmed Othman</p>	
	</div>



	


<?php 
  include $temp.'footer.php'; 
  ob_end_flush();
 ?>