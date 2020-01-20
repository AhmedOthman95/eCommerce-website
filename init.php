<?php 
		
	// Error Reporting
	
	ini_set('display_errors', 'On');

	error_reporting(E_ALL);

	include 'admin/connect.php';

	$sessionUser = '';
	if (isset($_SESSION['User'])) {
		$sessionUser = $_SESSION['User'];
	}

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
	include $temp.'navbar.php'; 


	
	
?>