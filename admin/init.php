<?php 
		
	include 'connect.php';

	// Routes
	$temp = "includes/templates/"; //Templates directory
	$lang = "includes/languages/"; //languages directory
	$func = "includes/functions/"; //Functions directory
	$css = "Design/css/"; // css directory
	$js = "Design/js/"; // js directory
	

	//Include Important Files
	include $func.'functions.php';
	include $lang.'english.php';
	include $temp.'header.php'; 


	//Include The Navbar In All Pages Except The Page With $noNavbar variable
	if (!isset($noNavbar)) { include $temp.'navbar.php'; }
	
	
?>