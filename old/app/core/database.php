<?php 


/**
 * database class
 */
class Database
{

	private function db_connect(){

		$DBHOST = "127.0.0.1";
		$DBNAME = "pbcar2700225";
		$DBUSER = "pbcar2700225";
		$DBPASS = "7mjtb7v9vu";
		$DBDRIVER = "mysql";

		// $DBHOST = "mysql-marcellinbashizi.alwaysdata.net";
		// $DBNAME = "marcellinbashizi_pos";
		// $DBUSER = "281366_bash";
		// $DBPASS = "Bashizimus-1999";
		// $DBDRIVER = "mysql";

		try{

			$con = new PDO("$DBDRIVER:host=$DBHOST;dbname=$DBNAME",$DBUSER,$DBPASS);
		}catch(PDOException $e){

			echo $e->getMessage();
		}

		return $con;

	}


	public function query($query,$data = array())
	{

		$con = $this->db_connect();
		$smt = $con->prepare($query);
		$check = $smt->execute($data);

		if($check)
		{
			$result = $smt->fetchAll(PDO::FETCH_ASSOC);
			if(is_array($result) && count($result) > 0){

				return $result;
			}
			
		}

		return false;
	}



}
