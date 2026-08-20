<?php 
/**
 * database class
 */
class Database
{
	private $con;
	private function db_connect()
		{
		    if($this->con){
		        return $this->con;
		    }
		    $DBHOST = "localhost";
		    $DBNAME = "mypos";
		    $DBUSER = "root";
		    $DBPASS = "root";
		    $DBDRIVER = "mysql";
		    try{
		        $this->con = new PDO("$DBDRIVER:host=$DBHOST;dbname=$DBNAME",$DBUSER,$DBPASS);
		        $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    }catch(PDOException $e){
		        die($e->getMessage());
		    }
		    return $this->con;
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
	public function lastInsertId()
	{
	    return $this->db_connect()->lastInsertId();
	}

	public function beginTransaction()
	{
	    return $this->db_connect()->beginTransaction();
	}

	public function commit()
	{
	    return $this->db_connect()->commit();
	}

	public function rollBack()
	{
	    return $this->db_connect()->rollBack();
	}
}