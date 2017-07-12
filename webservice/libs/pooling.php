<?php 

class Pooling{
	public $mysqli;
	
	function __construct($mysqli){
		$this->mysqli = $mysqli;
	}
	public function authenticate($data){
		$data = json_decode($data);
		
		$result = $this->mysqli->query('SELECT id, name, email FROM users WHERE email="'.$data->email.'" AND password=MD5("'.$data->password.'")');
		
		$user = $result->fetch_object();
		
		if(isset($user->id)){
			$this->setSession($user);
			$user->status = 200;
			$user->message ='Success';
			return json_encode($user);
		}
		else{
			$user = new StdClass();
			$user->status = 400;
			$user->message ='Fail';
			return json_encode($user);
		}
		
	}
	private function setSession($user){
		$_SESSION['user'] = $user;
	}
	
	public function getAds($data=0){
		$data = json_decode($data);
		$where = '';
		
		if($data->id)
			$where = ' AND ad.id = "'.$data->id.'"';
			
		$result = $this->mysqli->query('SELECT ad.id,
										  ad.title,
										  ad.description,
										  ad.amount_per_seat,
										  ad.no_of_seats,
										  DATE_FORMAT(ad.start_time, "%h:%i%p") start_time,
										  IF(CURDATE() = DATE_FORMAT(ad.start_time, "%Y-%m-%d"), "Today", DATE_FORMAT(ad.start_time, "%d %M")) AS today,
										  DATE_FORMAT(ad.start_time, "%h:%i %p") start_time,
										  gf.name AS from_name,
										  gt.name AS to_name,
										  (CASE ad.gender
											WHEN 1
											THEN "M"
											WHEN 2
											THEN "F"
											ELSE "M/F"
										  END ) AS gender
										FROM ads ad
										LEFT JOIN geo_locations gf ON ad.place_from = gf.id
										LEFT JOIN geo_locations gt ON ad.place_to = gt.id
										WHERE ad.status = 1 and ad.start_time > NOW() '.$where."
										ORDER BY ad.start_time
										"
									);
		
		$records = array();
		
		while($row = $result->fetch_object()){
			$records[] = $row;
		}
		return json_encode($records);
	}
}
?>