<?php
class database {

	var $_sql			= '';	
	/** @var Internal variable to hold the connector resource */
	var $_resource		= '';
	/** @var Internal variable to hold the query result*/
	var $_result        = ''; 
		/** @var Internal variable to hold the query result*/
	var $_insert_id        = ''; 

	//$host = '';
	/**
	* Database object constructor
	* @param string Database host
	* @param string Database user name
	* @param string Database user password
	* @param string Database name
	* @param string Common prefix for all tables
	* @param boolean If true and there is an error, go offline
	*/
	function database() {
		global $glob;
		$host = $glob['dbhost'];
		$user = $glob['dbusername'];
		$pass = $glob['dbpassword'];
		$db = $glob['dbdatabase'];
		if ($this->_resource = @mysql_connect( $host, $user, $pass )) {
			mysql_select_db($db) or die('Cant select database'.mysql_error());
			//echo "in";
		}
		else{
			echo "Could not connect to the database!";
			exit;
		}				
	}
	function getName($nameField, $table, $idField, $id)
    {
        $SQL = "SELECT $nameField FROM $table WHERE $idField='$id'" ;
        $result = @mysql_query($SQL);
        $row = @mysql_fetch_array($result);
        return $row[$nameField];
    } 

	/**
	* Execute the query
	* @return mixed A database resource if successful, FALSE if not.
	*/
	function query($sql) {
	
		$_sql = $sql;		
		//echo $sql;
		return $_result = mysql_query($_sql);				
	}
	
	function insert($table, $dbfields) {
	
		$field = array();
		
		$value = array();
		
		foreach ( $dbfields as $k => $v) {
		  		  
			$field[] = $k;
			
			$value[] = $v;			
		}
		
		$f = implode('`,`',$field);
		
		$val = implode("','",$value);
		
	  	$insertsql = "INSERT INTO `$table` (`$f`) VALUES ('$val')";	
		//echo $insertsql;exit;
		$result = mysql_query($insertsql);								
		
		$this->_insert_id = mysql_insert_id();
			
		return $this->_insert_id;
	}
	
	function update($table, $dbfields, $where) {

		 $updatesql = "UPDATE $table SET ";
		
		$i=0;
		
		foreach ( $dbfields as $k => $v) {
			if ($i==0){
				$updatesql .= " $k = '$v' ";				
			}
			else{
				$updatesql .= ", $k = '$v' ";
			}			
			$i++;
		}
		
		$updatesql .= " WHERE $where";		
		//echo $updatesql;exit;
		$result = mysql_query($updatesql);
		if($result)
			return true;
		else
			return false;
	}
	
	function updateSettings($table,$dbfields)
	{
		$updatesql = '';
		$res = 0;
		foreach ( $dbfields as $k => $v) {
			$updatesql = "UPDATE $table SET ";
			$updatesql .= " value = '$v' ";				
			$updatesql .= "WHERE fieldname = '$k' AND site_id=-1; ";
			$result = mysql_query($updatesql);
			if(!$result)
				$res = 1;
		}
		if($res != 1)
			return true;
		else
			return false;
	
	}
	function select($vars = "*", $table, $where = "", $order_by = "", $group_by = "", $result_type = MYSQL_ASSOC ){
		
		if ($vars != "*"){
			if (is_array($vars)){
				$vars = implode(",",$vars);
			}
		}				
				
		$select_sql = "SELECT ".$vars." FROM ".$table." WHERE 1 ".$where." ".$order_by." ".$group_by;
		
		//echo $select_sql;
				
		$resource = $this->exec_query($select_sql);
		
		$result = array();
		
		while($row = mysql_fetch_array($resource,$result_type)){
			$result[] = $row;
		}
		
		return $result;
	}
	function exec_query($sql){
		return @mysql_query($sql);
	}
	function delete($table, $where) {

		$deletesql = "DELETE FROM $table WHERE $where ";
		
		//exit;
				
		$result = mysql_query($deletesql);
		
		return true;
	}
	
	function getInsertId(){
		echo $this->_insert_id;
	}
	
	function numRows($sql){
		$_sql = $sql;
		$_result = mysql_query($_sql);
		$results = mysql_num_rows($_result);
		mysql_free_result($_result);
		return $results;
	}
	
	function numOfRows($sql){
		$_sql = $sql;
	//	$results = mysql_num_rows($_sql);
		return $results;
	}
	
	function dbClose(){
		mysql_close($this->_resource);
	}
	
	function fetchArray($rs){
	
        return @mysql_fetch_array($rs);
	}
	function selectdata($query)
	{
		if($data=mysql_query($query))
		{
			if(mysql_num_rows($data)>0)
			{
				while($row=mysql_fetch_assoc($data))
				{
					$array[]=$row;
				}
				return $array;
			}
		}
	}
}		
?>