<?php 
	
	function lang($phrase){
		static $lang = array(
			'Message' => 'Welcome in arabic' , 
			'Admin' => 'Ahmed Othman'
		);
		return $lang[$phrase];

	}


?>