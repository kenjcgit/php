<?php

error_reporting(0);

function getMember($memId)
{
		global $dbh;		
		$usersSQL="SELECT * FROM members  WHERE memStatus='Active' AND memId=".$memId;			
		$result = array();			
		foreach($dbh->query($usersSQL, PDO::FETCH_ASSOC) as $row)
		{
			$result[0] = $row;			
		}
		return $result;       		
}
function checkEventRegistered($memId,$evtId)
{
		global $dbh;		
		$eventsSQL="SELECT eadAttendeeStatus FROM eventattendees  WHERE eadEvtIdi='$evtId' AND eadMemId='$memId' ";
		$ret = "";
		$i=0;	
		foreach($dbh->query($eventsSQL, PDO::FETCH_ASSOC) as $row)
		{			
			$ret = $row["eadAttendeeStatus"];
			break;
		}
		return $ret;
}

function validateData($table,$req)
{
	$sql = "SHOW COLUMNS FROM $table;";
	$result=mysql_query($sql);
	$retStr=""	;
	
	$c="";
	$i=0;
	
	//print_r($req);
	
	while ($row = mysql_fetch_row($result)) 
	{
		$col=$row[0];		
		$tmptype=explode("(",$row[1]);		
		$ftype=$tmptype[0];
		$flen=str_replace(")","",$tmptype[1]);
	

	
		foreach($req as $key => $value)
		{
			//echo $key." ".$val." ".$col;
			//die;
			if($key == $col)
			{
				switch (strtoupper($ftype))
				{	
				case (strtoupper($ftype)=="TINYINT" || strtoupper($ftype) == "SMALLINT" || strtoupper($ftype)== "MEDIUMINT" || strtoupper($ftype) == "INT" || strtoupper($ftype) ==  "BIGINT" || strtoupper($ftype) == "FLOAT" || strtoupper($ftype) == "DOUBLE" || strtoupper($ftype) == "DECIMAL"):
				  //Validate Number with the same key for this data type
					if(!is_numeric($req[$col]))
						$retStr.= $key." contains invalid numbers \n ";
				  break;
				case (strtoupper($ftype) == "VARCHAR" || strtoupper($ftype) == "CHAR"):			  
				  //check length if more than 500 generate text area instead of text
				  if(strlen($req[$col]) > $flen)
					$retStr.= $key." exceed with allowed characters \n ";
				  break;							
				case (strtoupper($ftype) == "DATE" || strtoupper($ftype) ==  "DATETIME"):
						$date = date_parse($req[$col]);
						if (!$date["error_count"] == 0 && !checkdate($date["month"], $date["day"], $date["year"]))					
							$retStr.= $key." provide enter valid date\n ";					 
				 
				  break;	 			  					
				}
			}	
		}	
				
	
	} // endwhile
	return $retStr;		
}
?>