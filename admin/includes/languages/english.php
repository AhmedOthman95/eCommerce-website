<?php 
	
	function lang($phrase){
		static $lang = array(
			//Navbar Links
			'HOME_ADMIN' 		=> 	'Home' , 
			'CATEGORIES' 		=> 	'Categories',
			'EDIT_PROFILE'	    => 	'Edit Profile',
			'SETTINGS'		    => 	'Settings',
			'LOGOUT' 			=> 	'Logout',
			'ITEMS'			    => 	'Items',
			'MEMBERS'		    => 	'Members',
			'COMMENTS'			=>  'Comments',
			'STATISTICS' 		=> 	'Statistics',
			'LOGS'			    => 	'Logs'
			
		);
		return $lang[$phrase];

	}


?>