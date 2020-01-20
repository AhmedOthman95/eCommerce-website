<?php

	$action = '';

	if (isset($_GET['action'])) {
		$action = $_GET['action'];
	}else {
		$action = 'Manage';
	}

	//If The Page Is Main Page

	if ($action == 'Manage') {
		echo "Welcome You Are In Main Category Page";
		echo '<a href="page.php?action=Add">Add New Category +</a>';
	} elseif ($action == "Add") {
		echo "Welcome You Are In Add Page";
	} elseif ($action == "Insert") {
		echo "Welcome You Are In Insert Page";
	} else{
		echo "Error There's No Such Page";
	}


 ?>