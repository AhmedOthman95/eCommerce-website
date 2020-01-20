<?php 

	session_start(); //Start the session

	session_unset(); //Unset the data of the session

	session_destroy(); //Destroy the session

	header('Location: index.php');

	exit();

 ?> 