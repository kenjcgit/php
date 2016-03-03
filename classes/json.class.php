<?php
class json {
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
	function json()
	{
		
	}	
	function insert($table, $dbfields,$dbvalues) 
	{
		$insertsql = "INSERT INTO `$table` ($dbfields) VALUES ($dbvalues)";		
		//print $insertsql;die;
		$result = mysql_query($insertsql);
		
		$this->_insert_id = mysql_insert_id();
		return $this->_insert_id;
	}	
	function update($table, $dbfields, $where) 
	{

			$updatesql = "UPDATE $table SET ";
			
			$i=0;
			
			$updatesql .=  $dbfields;
			
			$updatesql .= " WHERE $where";
			
			//print $updatesql; die;
			
			$result = mysql_query($updatesql);
			if($result)
				return true;
			else
				return false;
		
	}
	function select($table,$vars = "*", $where = "", $order_by = "", $group_by = "", $result_type = MYSQL_ASSOC )
	{ 		
		if ($vars != "*"){
			if (is_array($vars)){
				$vars = implode(",",$vars);
			}
		}
		//echo $where."<BR>"; 
		$artistjson=strpos(strtolower($where),strtolower("AND Artist like"));
		//echo $artistjson;
	//	die;
		//$artist = $where;
		if($table =="vw_artists")
		{
			/*New Code*/
			$select_sql = "select ".$vars." from vw_artists where 1 ".$where."  GROUP BY `Artist` ORDER BY `Artist`";	
			//echo $select_sql;
			// exit;
			$resourceb = $this->exec_query($select_sql);			
			$resultb = array();		   
			while($row = mysql_fetch_array($resourceb,$result_type))
			{	
				$resultb[] = $row;
				$i++;					
			}
			return $resultb;			
		/*Jahid Code*/	
//			$select_sql = "SELECT ".$vars." FROM ".$table." WHERE 1 ".$where."  GROUP BY `Artist` ";
//			echo $select_sql."<Br>"; 
//			$resourceb = $this->exec_query($select_sql);
//			$resultb = array();
//			$i=0;	
//			while($row = mysql_fetch_array($resourceb,$result_type)){
//				$resultb[$i] = $row;
//				$i++;
//			}
//			//print_r($resultb); die;
//			
//			$new = explode(' ',$where);
//			$select_sqls = "SELECT `Seriel Number`,`Song Title`,`sort_order` as 'Artist',`Date`, `Song Awards`,`Awards`, `Band Name`,`Members`,`Deaths`,`Location`,`Did You Know?`,`iTunes`,`song_year`,`song_rank`,`sort_order` FROM ".$table." WHERE 1 AND `sort_order` LIKE ".$new[4]." GROUP BY `Artist`  ";
//		//	echo $select_sqls; die;
//			$resources = $this->exec_query($select_sqls);
//			$results = array();
//			$j=0;	
//			while($row = mysql_fetch_array($resources,$result_type)){
//				$results[$j] = $row;
//				$j++;
//			}
//			//print_r($results); die;
//			$result = array_merge((array)$resultb, (array)$results);
//			$sortedByNameAsc = array();
//			$sortedByNameAsc = $this->sortByOneKey($result, 'Artist');
//			//return $sortedByNameAsc;
//			return $result;
		}
		else
		{
			$select_sql = "SELECT ".$vars." FROM ".$table." WHERE 1 ".$where." ".$group_by." ".$order_by." ";			
			//echo $select_sql;// die;		
			$resource = $this->exec_query($select_sql);
			$result = array();
			$i=0;	
			while($row = mysql_fetch_array($resource,$result_type)){
				$result[$i] = $row;
				$i++;
			}
			//$sortedByNameAsc = $this->sortByOneKey($result, 'Artist');
			//print_r($sortedByNameAsc,true);
			//exit;

			return $result;
		}
	}
	
	function selectband($table,$vars = "*", $band_name, $result_type = MYSQL_ASSOC )
	{ 		
		if ($vars != "*"){
			if (is_array($vars)){
				$vars = implode(",",$vars);
			}
		}
		//echo $band_name; die;
		$select_sql = "SELECT ".$vars." FROM ".$table." WHERE 1 AND `band_name` LIKE '".$band_name."%' GROUP BY `band_name` ";
		//echo $select_sql; die;
		$resourceb = $this->exec_query($select_sql);
		$resultb = array();
		$i=0;	
		while($row = mysql_fetch_array($resourceb,$result_type)){
			$resultb[$i] = $row;
			$i++;
		}
		//print_r($resultb); die;
		
		$select_sqls = "SELECT ".$vars." FROM ".$table." WHERE 1 AND `sort_order` LIKE '".$band_name."%' GROUP BY `band_name` ";
		//echo $select_sqls; die;
		$resources = $this->exec_query($select_sqls);
		$results = array();
		$j=0;	
		while($row = mysql_fetch_array($resources,$result_type)){
			$results[$j] = $row;
			$j++;
		}
		//print_r($results); die;
		$result = array_merge((array)$resultb, (array)$results);
		//print_r($result); die;
		$sortedByNameAsc = array();
		$sortedByNameAsc = $this->sortByOneKey($result, 'band_name');
		//return $sortedByNameAsc;		
		return $result;
	}
	
	function SongByYearselect($table,$vars = "*", $where = "", $order_by = "", $group_by = "", $result_type = MYSQL_ASSOC )
	{ 		
		if ($vars != "*"){
			if (is_array($vars)){
				$vars = implode(",",$vars);
			}
		}
		//$select_sql = "SELECT ".$vars.",CAST(SUBSTRING(`Date`,7,3 ) as DECIMAL) FROM ".$table." WHERE 1 ".$where." ".$group_by." ".$order_by." ";
		$select_sql = "SELECT ".$vars." FROM ".$table." WHERE 1 ".$where." ".$group_by." ".$order_by." ";
		//echo $select_sql; die;
		
		$resource = $this->exec_query($select_sql);
		$result = array();
		$i=0;	
		while($row = mysql_fetch_array($resource,$result_type)){
			$result[$i] = $row;
			$i++;
		}
		return $result;
	}
	
	function exec_query($sql)
	{
		return @mysql_query($sql);
	}
	function delete($table, $where) 
	{
		
		$deletesql = "DELETE FROM $table WHERE 1 $where ";
		
		//echo $deletesql;die;
				
		$result = mysql_query($deletesql);
		
		return true;
	}	
	function getInsertId()
	{
		echo $this->_insert_id;
	}	
	function thumb($name,$filename,$new_w,$new_h,$path=""){
		
		$wh = getimagesize($path.$name);
		if($wh[0] < $new_w)
			$new_w = $wh[0];
			
		if($wh[1] < $new_h)
			$new_h = $wh[1];	
	
		$gd2=1;
		
		//echo "</br>".$name;
		//echo "</br>".$filename;
		//echo "</br>".$new_w;
		//echo "</br>".$new_h;
		//exit;
	
		$system=explode(".",$name);
	
		if(preg_match("/jpg|jpeg|JPG|JPEG/",$system[count($system)-1]))
			$src_img=@imagecreatefromjpeg($path.$name);
	
		if (preg_match("/gif|GIF/",$system[count($system)-1]))
			$src_img=@imagecreatefromgif($path.$name);
	
		if (preg_match("/png|PNG/",$system[count($system)-1]))	
			$src_img=@imagecreatefrompng($path.$name);
		
	
		$old_x=@imagesx($src_img);
	
		$old_y=@imagesy($src_img);
	
		if ($old_x > $old_y){
		
		   $thumb_w=$new_w;
	
		   $thumb_h=$new_w*($old_y/$old_x);
		}
		
		if ($old_x < $old_y){
		
			$thumb_w=$new_h*($old_x/$old_y);
	
			$thumb_h=$new_h;
	
		}
	
		if ($old_x == $old_y){
	
			$thumb_w=$new_w;
	
			$thumb_h=$new_h;
		}
	
		if ($gd2==1){
	
			$dst_img=@imagecreatetruecolor($thumb_w,$thumb_h);
	
			@imagefill($dst_img,0,0,imagecolorallocate($dst_img,255,255,255));
		 
			@imagecopyresized($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
	
		}
		else{
	
			$dst_img=@imagecreatetruecolor($thumb_w,$thumb_h);
		 
			@imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
			
	   }
	
	
		if (preg_match("/gif|GIF/",$system[1])){
	   
			@imagegif($dst_img,$path.$filename);
			chmod($path.$filename,0777);
		}
		else{
	
			@imagejpeg($dst_img,$path.$filename);
	
			chmod($path.$filename,0777);
		}
	
		@imagedestroy($dst_img);
	
		@imagedestroy($src_img);
	
	}
	function deleteimage($table,$fild,$path,$where)
	{
		$sql_res = @mysql_query("SELECT $fild from $table WHERE $where");
		$sql_row = @mysql_fetch_row($sql_res);
		@unlink("images/upload/".$path[$i]."/original/".$sql_row[0]);
		@unlink("images/upload/".$path[$i]."/thumb/".$sql_row[0]);
	}
	function fetchArray($rs)
	{
	
        return @mysql_fetch_array($rs);
	}
	function Sendemail($email_from, $email_to, $email_cc="", $email_bcc="", $email_subject, $email_message, $email_format="")
	{
	
	$smtp_yes=1;
	$email_format="html";
    $mail = new htmlMimeMail();	
	$mail->setSMTPParams('mail.projectsdemo.com', 587, 'projectsdemo.com',true,'test@projectsdemo.com','test');
	$mail->setReturnPath('vimal.dagli@smileofindia.com');

    // Set the sender address
    $mail->setFrom($email_from);
    
    // Set the reply-to address
    $mail->setReturnPath($email_from);
  
    // Set the mail subject
    $mail->setSubject($email_subject);
	
	if ($email_bcc != "")
		$mail->setBcc($email_cc);
	
	if ($email_cc != "")
		$mail->setCc($email_cc);

    // Set the mail body text
	
	if ($email_format == "html"){	
		//$content_type = "text/html";		
		$email_message = str_replace("{site_logo}","<img src='".$this->site_path."/images/logo.png' />",$email_message);
		$mail->setHTML($email_message);
	}
	else{
		//$content_type = "text/plain";
		$mail->setText($email_message);
	}
    
	if (is_array($email_to)){
		// Send the email!
	    $mail->send($email_to,'smtp');	
	}
	else{
		// Send the email!
	    $mail->send(array($email_to),'smtp');
	}
	//echo "Error:";
	//print_r($mail->errors);
	//exit;
}
function mailcheck($email)
{	
	if($email != '')
	{
		$sqlmail = "SELECT email FROM tbluser WHERE email ='".$email."' ";
		$resultmail = mysql_query($sqlmail);
		$nummail = mysql_num_rows($resultmail);
		//echo $nummail; die;
		if($nummail >= 1)
		{
			echo "This email address is already taken. Please choose a different email"; die; 
		}
		else
		{
			$result = ereg("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email);
			if(!$result)
			{
				echo "Enter a valid E-mail Address"; die;
			}
			else
			{
				return true;
			}
		}
	}
}

function fbmailcheck($email)
{	
	if($email != '')
	{
		$sqlmail = "SELECT email FROM tbluserfacebook WHERE email ='".$email."' ";
		$resultmail = mysql_query($sqlmail);
		$nummail = mysql_num_rows($resultmail);
		//echo $nummail; die;
		if($nummail >= 1)
		{
			echo "This email address is already taken. Please choose a different email"; die; 
		}
		else
		{
			$result = ereg("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email);
			if(!$result)
			{
				echo "Enter a valid E-mail Address"; die;
			}
			else
			{
				return true;
			}
		}
	}
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function Required($filed,$value)
						{
							
							if($value == "")
							{
								return false;
							}
							return true;				
						}
					function Email($filed,$value)
						{
							if($value == "")
							{
								return false;
							}
							else
							{
								if($this->checkEmail($value))
								{
									return true;
								}
								else
								{
									return false;
								}
							}
							return true;
											
						}	
											
						function Duplicate($filed,$value,$table)
						{
							if($value == "")
							{
								return false;
							}
							else
							{
								$where = $filed." = '".$value."'";
								$sql_res = @mysql_query("SELECT null from $table WHERE $where");
								$sql_row = @mysql_num_rows($sql_res);
								if($sql_row != 0)
								{
									return false;
									
								}
								return true;
							}				
						}
						
						function Duplicate1($filed,$value,$table,$wh)
						{
							if($value == "")
							{
								return false;
							}
							else
							{
								$where = $filed." = '".$value."'";
								
								$sql_res = @mysql_query("SELECT null from $table WHERE $where AND $wh");
								$sql_row = @mysql_num_rows($sql_res);
								if($sql_row != 0)
								{
									return false;
									
								}
								return true;
							}				
						}	
	function checkEmail($email)
	{	
	  //echo $email; die;
	  if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email))
	  {
		return false;
	  }
	  else
	  {
		return true;
	  }	
	}
	
	function sortByOneKey(array $array, $key, $asc = true) 
	{
	    $result2 = array();
	        
	    $values = array();
	    foreach ($array as $id => $value) 
	    {
	        $values[$id] = isset($value[$key]) ? $value[$key] : '';
	    }
	        
	    if ($asc) 
	    {
	        asort($values);
	    }
	    else 
	    {
	        arsort($values);
	    }
	    
	    foreach ($values as $key => $value) 
	    {
	        $result2[$key] = $array[$key];
	    }
	    //print_r($result2);    
	    return $result2;
	}
	
	
}	

?>