<?php
	include 'libs/config.php';
	include 'libs/pooling.php';
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	
	$pool = new Pooling($mysqli);
	
	if(isset($_GET['s'])){
		switch ($_GET['s']){
		
			case 'login':
						echo $pool->authenticate(file_get_contents('php://input'));
						break;
			case 'getsession':
						echo json_encode($_SESSION['user']);
						break;
			case 'ads':
						echo $pool->getAds(file_get_contents('php://input'));
						break;
			
		}
			
	}


?>
