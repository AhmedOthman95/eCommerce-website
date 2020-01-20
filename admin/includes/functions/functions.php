<?php
	
	/*
	** Title Function v1.0 
	** That Echo The Page Title In Case The Page  
	** Has The Variable $pageTitle And Echo Default Title For Other Pages
	*/
	
	function getTitle() {
		global $pageTitle;

		if (isset($pageTitle)) {
			
			echo $pageTitle;
		} else {
			echo "Default";
		}
	}

	/*
	** Home Redirect Function v2.0
	** To redirect User To home Page When There Is An Error In Accessing  A Page 
	** This Function Accept Parameters
	** $msg = Echo The Message [Error | Success | Warning]
	** $url = The Link You Want To Redirect To
	** $seconds = Seconds Before Redirecting
	*/

	function redirectHome($msg, $seconds = 3, $url = null) {

		if ($url === null) {
			$url = 'index.php';
			$link = 'Home Page';
		} else {
			if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
					$url = $_SERVER['HTTP_REFERER'];
					$link = 'Previos Page';
			}else {
				$url = "index.php";
				$link = 'Home Page';
			}
			
		}
		echo $msg;
		echo "<div class='alert alert-info'>You Will Be Directed To $link  After $seconds seconds.</div>";

		header("refresh:$seconds;url=$url");
		exit();
	}

	/*
	** Check Items Function v1.0
	** Function To Check Items In Database Before Insert A New Item To Not Dublicate Data[Function Accept Parameters]
	** $select = The Items To Select [e.g. user, item, category]
	** $from = The Table To select From [e.g. users, items, categories]
	** $value = The Value Of Select [e.g. Othman, Box, Electronics]
	*/

	function checkItem($select, $from, $value){
		global $conn;
		$statement = $conn->prepare("SELECT $select FROM $from WHERE $select = ? ");
		$statement->execute(array($value));
		$count = $statement->rowCount();

		return $count;
	}

	/*
	** Count Number Of Items v1.0
	** Function To Count Number Of Item Records In Database
	** $item = The Item To Count
	** $table = Table That Contain The Item
	*/
	function countItems($item, $table) {
		global $conn; 

		$stmt2 = $conn->prepare("SELECT COUNT($item) FROM $table");
		$stmt2->execute();

		return $stmt2->fetchColumn();
	}

	/*
	** Get Latest Records Function v1.0
	** Function To Get Latest Items From Databse [Members, Items, Comments ]
	** $select = Field To Select
	** $table = Table To Select From
	** $order = Used To Order The Result Of Select Statament
	** $limit = Number Of Records To get
	*/

	function getLatest($select, $table, $order,$limit = 5) {
		global $conn;

		$getStmt = $conn->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
		$getStmt->execute();
		$rows = $getStmt->fetchAll();

		return $rows;
	}

	?>
 