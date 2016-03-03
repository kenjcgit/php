<?php
class general{
var $currency_symbol = "$";
function distance($lat1, $lon1, $lat2, $lon2, $unit) 
{ 
	  $theta = $lon1 - $lon2; 
	  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
	  $dist = acos($dist); 
	  $dist = rad2deg($dist); 
	  $miles = $dist * 71 * 1.1515;
	  $unit = strtoupper($unit);
	
	  if ($unit == "K") {
		return ($miles * 1.609344); 
	  } else if ($unit == "M") {
		  return ($miles * 0.8684);
	 } else {
			return $miles;
	  }
}
function sanitize($string, $force_lowercase = true, $anal = true) {

    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",

                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",

                   "—", "–", ",", "<", ">", "/", "?");

    $clean = trim(str_replace($strip, "", strip_tags($string)));

    $clean = preg_replace('/\s+/', "-", $clean);

    //$clean = ($anal) ? preg_replace("/[^a-z0-9\._-]/", "", $clean) : $clean ;

    return ($force_lowercase) ?

        (function_exists('mb_strtolower')) ?

            mb_strtolower($clean, 'UTF-8') :

            strtolower($clean) :

        $clean;

}
function getOptions($id, $idField, $nameField, $tableName, $status)
    {
        $sql = "SELECT $idField, $nameField FROM $tableName WHERE $status='Active'";
        $result = @mysql_query($sql);
        while($row = @mysql_fetch_array($result)){?>
            <option value="<?=$row[$idField]?>" <?php if($row[$idField]==$id){echo "selected";} ?>><?=$row[$nameField]?></option>
        <?php }
    }
function getEvents($id, $idField, $nameField, $tableName, $status)
    {
        echo $sql = "SELECT $idField, $nameField FROM $tableName WHERE $status='Active' AND date(evtStartDate) > '".date("Y-m-d")."'";
        $result = @mysql_query($sql);
        while($row = @mysql_fetch_array($result)){?>
            <option value="<?=$row[$idField]?>" <?php if($row[$idField]==$id){echo "selected";} ?>><?=$row[$nameField]?></option>
        <?php }
    }
function Chkinput($data)
{
    $Replace = array("`", "'", "/","%","^","!");
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = str_replace($Replace, '', $data);
    return $data;
} 

function ImgUpload($Img){
   
        $imgName=substr($Img,-5);
        $chkExt=explode(".",$imgName);
        $ext=".".$chkExt[1];
            if(!preg_match('/\.(jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF)$/', $ext))
            {
               $return_Val = "Invalid file Type. Only jpg / jpeg / png /gif  are allowed.";
            }
            else
            {
                $return_Val = rand(000,999).time().$Img;
            }
            
            return $return_Val;
} 

function videoUpload($video)
{
        $imgName=substr($video,-5);
        $chkExt=explode(".",$imgName);
        $ext=".".$chkExt[1];
            if(!preg_match('/\.(ogg|ogv|avi|mpeg|mov|wmv|flv|mp4|swf|AVI|MP4|MOV|FLV|SWF)$/', $ext))
            {
               $return_Val = "Invalid file Type. Only ogg / ogv / avi / mpeg / mov / wmv / flv / mp4  are allowed.";
            }
            else
            {
                $return_Val = rand(000,999).time().$video;
            }
            
            return $return_Val;
}

function checkLeftAdmin($admin_id){
	global $dclass;
        
	$sqlGetUser = "SELECT * FROM usermaster WHERE usrStatus='Active'";
	
	if($dclass->numRows($sqlGetUser) > 0){
                $sql_admin = "SELECT * FROM rollpermissions as rp inner join systemmenus as sm ON sm.symId=rp.ropSymId WHERE ropRomId IN ($admin_id)";
		$res_admin = $dclass->query($sql_admin);
		$arr = array();
		while($row_admin = $dclass->fetchArray($res_admin)){
			$arr[] = str_replace(" ","",$row_admin['symMenus']);
		}	
                return $arr;
		
		
	}else{
		return true;
	}
}

function checksubAdmin($admin_id){
	global $dclass;
        
	$sqlGetUser = "SELECT * FROM usermaster WHERE usrStatus='Active'";
	
	if($dclass->numRows($sqlGetUser) > 0){
                $sql_admin = "SELECT * FROM rollpermissions as rp inner join systemmodule as sm ON sm.smpcId=rp.ropSmpId WHERE ropRomId IN ($admin_id)";
		$res_admin = $dclass->query($sql_admin);
		$arr = array();
		while($row_admin = $dclass->fetchArray($res_admin)){
			$arr[] = str_replace(" ","",$row_admin['sycModulePages']);
		}	
                return $arr;
		
		
	}else{
		return true;
	}
}

function commanmessages($mes)
{
        $msg_arr=array();
        $sql= "SELECT setting_values  FROM tblsettings WHERE setting_fields='".$mes."'";
        $res_query = mysql_query($sql);  
        $row_mes = mysql_fetch_array($res_query);
        $cnt_msg = mysql_num_rows($res_query);
        if($cnt_msg>0)
        {
            $msg_arr = $row_mes['setting_values'];
        }
        return $msg_arr; die;
        
}

function checkAdmin($admin_id,$getPage){
	global $dclass;
         $page='';
    $chkListPage=substr($getPage, -4);
    if($chkListPage!='List')
    {
         $page=substr($getPage, 0, -4);
         $isList=true;
    }
    else
    {
         $page=$getPage; 
         $isList=false;
    }
    
	$sqlGetUser = "SELECT * FROM usermaster WHERE usrStatus='Active'";
	
	if($dclass->numRows($sqlGetUser) > 0){
		 $sql_admin = "SELECT * FROM rollpermissions WHERE ropRomId IN ($admin_id) AND ropPageName='".$page."'";
		$res_admin = $dclass->query($sql_admin);
		$arr = array();
		while($row_admin = $dclass->fetchArray($res_admin)){
                        $add_flag=$row_admin['ropAdd'];
                        $edit_flag=$row_admin['ropEdit'];
                        $delete_flag=$row_admin['ropDelete'];
                        $view_flag=$row_admin['ropView'];
		}                
		if($add_flag!="Add" && $chkListPage=='List' && $edit_flag!='Edit')
                {  
			$this->redirectTo("home.php");
		}
                if($add_flag!="Add" && $chkListPage!='List' && !isset($_REQUEST['cid']) && trim($_REQUEST['cid'])=='' )
                {  
			$this->redirectTo("home.php");
                }
                if($edit_flag!="Edit" && $chkListPage!='List' &&  isset($_REQUEST['cid']) && trim($_REQUEST['cid'])!='')
                {  
			$this->redirectTo("home.php");
                }
                if($delete_flag=="Delete")
                {  
			return 1;
		}
               
               
	}else{
		return true;
	}
}

function logInsert($menu,$module,$page,$userid,$action,$logActId='',$oldvalue='',$newvalue='',$ip,$browser)
{
        
        $newVal='';
        foreach ($newvalue as $k => $v) 
        {
            $k; 
            $v; 
            $newVal.=substr($k,3)." : ".$v." ### ";
        }
        foreach ($oldvalue as $colm => $rowval){
            $colm; 
            $rowval; 
            $oldVal.=substr($colm,3)." : ".$rowval." ### ";
        }
        
   $insertLog="insert into logtable(logMenu,logModule,logPage,logUserId,logAction,logIp,logTime,logBrowser,logActId,logOldValue,logNewValue,logCreatedDate)
                                    values('".$menu."','".$module."','".$page."','".$userid."','".$action."','".$ip."',NOW(),'".$browser."','".$logActId."','".$oldVal."','".$newVal."',NOW())";
   $resLog= mysql_query($insertLog);
}

function checkSubPerm($page_name,$userid,$usertype,$perm){
	global $dclass;
	$sql = "SELECT `userid` FROM tblpermission WHERE page = '$page_name' AND userid='$userid' AND usertype='$usertype' AND `$perm` = 'y'";
	$res = $dclass->query($sql);
	$checked = "";
	if($dclass->numRows($sql)>0){	
		$checked = "checked";
	}
	return $checked;
} 

	//var $currency_symbol = "$";
	//static page name 
	var $staticPage = array('Testimonials','Testimonials-details','Sitemap','contact-us','news','news-details');
	
	  function get_field_value($table,$primary_field,$id,$field){
	  
		 $sql ="SELECT * FROM $table where $primary_field = '$id' ";
	
		 $res = mysql_query($sql);
	
		 $value="";
	
		 if(mysql_num_rows($res) > 0){
		 
			$row=mysql_fetch_array($res);
			$value=$row[$field];
		  }
	
		 return $value;
	   }
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function get_file_name($filename)
	{
		if(strstr($filename,"/")){$filename = str_replace("/","",$filename);}
		if(strstr($filename,"&")){$filename = str_replace("&","and",$filename);}
		if(strstr($filename,'"')){$filename = str_replace('"','_',$filename);}	
		if(strstr($filename,'>')){$filename = str_replace('>','',$filename);}	
		if(strstr($filename,'<')){$filename = str_replace('<','',$filename);}
		if(strstr($filename,"'")){$filename = str_replace("'","",$filename);}  
		if(strstr($filename," ")){$filename = str_replace(" ","_",$filename);} 
		
		return $filename;
	}
	
		function checkBlank($arr)
	{	
		if($arr==''){
			return false;
		}else{
			return true;
		}
	}
	function count_days( $a, $b )
	{
		#
		// First we need to break these dates into their constituent parts:
		#
		$gd_a = getdate( $a );
		#
		$gd_b = getdate( $b );
		#
		 
		#
		// Now recreate these timestamps, based upon noon on each day
		#
		// The specific time doesn't matter but it must be the same each day
		#
		$a_new = mktime( 12, 0, 0, $gd_a['mon'], $gd_a['mday'], $gd_a['year'] );
		#
		$b_new = mktime( 12, 0, 0, $gd_b['mon'], $gd_b['mday'], $gd_b['year'] );
		#
		 
		#
		// Subtract these two numbers and divide by the number of seconds in a
		#
		// day. Round the result since crossing over a daylight savings time
		#
		// barrier will cause this time to be off by an hour or two.
		#
		if($a_new<$b_new)
		{
			$days=round( abs( $a_new - $b_new ) / 86400 );
			$days=-$days;
			
		}
		else
		{
			$days=round( abs( $a_new - $b_new ) / 86400 );
			
		}
		return $days;
	}	

	function insert_initial_data($site_id,$site_name)
	{
		global $dclass;	
		//echo "In".TRIAL_SITE_PATH.$site_name; 		
		//mkdir(TRIAL_SITE_PATH."images/upload/".$site_name,0777);
		//mkdir(TRIAL_SITE_PATH."tempates/".$site_name,0777);
		//mkdir(TRIAL_SITE_PATH."file_uploader/".$site_name,0777);
		
		/*$source =TRIAL_SITE_PATH.'images/upload/test';
		$destination = TRIAL_SITE_PATH.'images/upload/'.$site_name;
		full_copy($source, $destination);
		
		$source =TRIAL_SITE_PATH.'templates/test';
		$destination = TRIAL_SITE_PATH.'templates/'.$site_name;
		full_copy($source, $destination);
		
		$source =TRIAL_SITE_PATH.'file_uploader/test';
		$destination = TRIAL_SITE_PATH.'file_uploader/'.$site_name;
		full_copy($source, $destination);
		
		$source =TRIAL_SITE_PATH.'language/test';
		$destination = TRIAL_SITE_PATH.'language/'.$site_name;
		full_copy($source, $destination);*/
				
	
		if($site_id!='' && $site_id>0)
		{
			$sqlSetting = $dclass->select("*","tblsettings"," AND site_id=0");
		
			for($i=0;$i<count($sqlSetting);$i++)
			{
				$sqlInsert = "INSERT INTO tblsettings(`fieldname`,`value`,`site_id`) VALUES('".$sqlSetting[$i]['fieldname']."','".$sqlSetting[$i]['value']."','$site_id')";
				$rsInsert = $dclass->query($sqlInsert);
			}
			
			$sqlLang = $dclass->select("*","tbllanguagesmain");
			for($i=0;$i<count($sqlLang);$i++)
			{
				$sqlInsert = "INSERT INTO tbllanguages(`language`,`lang_flag`,`lang_code`,`sort_order`,`charset`,`status`,`site_id`) VALUES('".$sqlLang[$i]['language']."','".$sqlLang[$i]['lang_flag']."','".$sqlLang[$i]['lang_code']."','".$sqlLang[$i]['sort_order']."','".$sqlLang[$i]['charset']."','".$sqlLang[$i]['status']."','$site_id')";
				$rsInsert = $dclass->query($sqlInsert);	
				$langInsid = mysql_insert_id();
					if($i=='0')		
					{
					 $lids = $langInsid;
					}		
			}			
			
			$cids = general::getName("intid",$site_id,"tblsites","customer_id");
			$sitesemail = general::getName("intid",$cids,"tblcustomer","email");
						
			$inc_cnt['varemailrecive']  = $sitesemail;
			$inc_cnt['varsenderemial'] = $sitesemail;
			$inc_cnt['varenquiryemail'] = $sitesemail;
			$inc_cnt['varrequestemail'] = $sitesemail;
			$inc_cnt['varregisteremail'] = $sitesemail;
			$inc_cnt['langid'] = $lids;			
		
			$updatesql = '';
			$res = 0;
			foreach ( $inc_cnt as $k => $v) {
				$updatesql = "UPDATE tblsettings SET ";
				$updatesql .= " value = '$v' ";				
				$updatesql .= "WHERE fieldname = '$k' AND site_id='".$site_id."'  ";
				$result = mysql_query($updatesql);
			}			
			
			$sqltemp = $dclass->select("*","tbl_ct_template"," AND site_id=-1");
			for($i=0;$i<count($sqltemp);$i++)
			{
			    $codeln=general::getName("intid",$sqltemp[$i]['languageid'],"tbllanguagesmain","lang_code");
			    $changeln=general::getName("lang_code",$codeln,"tbllanguages","intid");		        
				$varmessage = addslashes(stripslashes($sqltemp[$i]['varmessage']));
			    $sqlInserttemp = "INSERT INTO `tbl_ct_template` ( `languageid`, `staticname`, `varname`, `varsubject`, `varmessage`, `dtcreate`, `dtmdf`, `delete`, `status`, `site_id`) VALUES
( '".$changeln."', '".$sqltemp[$i]['staticname']."', '".$sqltemp[$i]['varname']."', '".$sqltemp[$i]['varsubject']."', '".$sqltemp[$i]['varmessage']."', now(),  now(), 'N', 'Active', '$site_id')";
				$resInserttemp = $dclass->query($sqlInserttemp);			
			}
			
			$sqlcms = $dclass->select("*","tblcms"," AND site_id=-1");
			for($i=0;$i<count($sqlcms);$i++)
			{
			     $codeln=general::getName("intid",$sqlcms[$i]['languageid'],"tbllanguagesmain","lang_code");
			     $changeln=general::getName("lang_code",$codeln,"tbllanguages","intid");		        
				 
			    $sqlInsertcms = "INSERT INTO `tblcms` (`languageid`, `pagename`, `dtcreate`, `dtmdf`, `intsortorder`, `status`, `intparentid`, `delete`, `site_id`) VALUES
('".$changeln."', '".$sqlcms[$i]['pagename']."', now() , now() , 0, 'Active', 0, 'N', '$site_id')";
				$resInsertcms = $dclass->query($sqlInsertcms);	
				$cmid = mysql_insert_id();		
				
				    $sqlcont = $dclass->select("*","tblcmscontents"," AND cmsid='".$sqlcms[$i]['cmsid']."' ");
					for($j=0;$j<count($sqlcont);$j++)
					{
					 $cmscon = "INSERT INTO `tblcmscontents` (`cmsid`, `languageid`, `pageheading`, `pagebrtitle`, `contents`, `metakeywords`, `metadescriription`, `menu_title`) VALUES
		( '$cmid', '".$changeln."' , '".$sqlcont[$j]['pageheading']."', '".$sqlcont[$j]['pagebrtitle']."', '".$sqlcont[$j]['contents']."', '".$sqlcont[$j]['metakeywords']."', '".$sqlcont[$j]['metadescriription']."', '".$sqlcont[$j]['menu_title']."')";
						 $rescmscon = $dclass->query($cmscon);	
					}	 
			}
			
			$sqlCredit="INSERT INTO `tblcreditfedility` (`credit_value`,`site_id` )VALUES ('100','$site_id')";
			$resCredit=$dclass->query($sqlCredit); 
		}
	}
	//////////////////////////////////////// To create the thumbnails of images ////////////////////////////////////////
	
	function createThumb($name,$filename,$new_w,$new_h,$path=""){
	
		$wh = getimagesize($path.$name);
		
		if($wh[0] < $new_w)
			$new_w = $wh[0];
			
		if($wh[1] < $new_h)
			$new_h = $wh[1];	
	
		$gd2=1;
		
		$system=explode(".",$name);
	
		if(preg_match("/jpg|jpeg|JPG|JPEG/",$system[1])){
		
		   $src_img=imagecreatefromjpeg($path.$name);
		}
	
		if (preg_match("/gif|GIF/",$system[1])){
		
		   $src_img=imagecreatefromgif($path.$name);
		}
		
		if (preg_match("/png|PNG/",$system[1])){
		
		   $src_img=imagecreatefrompng($path.$name);
		}
		
		$old_x=imageSX($src_img);
	
		$old_y=imageSY($src_img);
	
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
	
		   $dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
	
		   imagefill($dst_img,0,0,imagecolorallocate($dst_img,255,255,255));
	
		   imagecopyresized($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
	
		}
		else{
	
		   $dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
	
		   imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
	   }
	
	   if (preg_match("/gif|GIF/",$system[1])){
	
		   imagegif($dst_img,$path.$filename);
		   
		   chmod($path.$filename,0777);
	   }
	   else{
	   
		   imagejpeg($dst_img,$path.$filename);
	
		   chmod($path.$filename,0777);
	   }
	
	   imagedestroy($dst_img);
	
	   imagedestroy($src_img);
	
	}
	
	function getSetting()
	{
		$sql = mysql_query("SELECT * FROM `tblsettings`WHERE site_id=-1 ");
		while($row = mysql_fetch_array($sql)) {
			$settings[$row['fieldname']] = $row['value'];
		}
	  return $settings;
	}
	
	function redirectTo($url){
		@header("location:$url");
		echo '<script language="javascript">location.href="'.$url.'";</script>';
	}
	
	//function to check the authentication.
	function authentication() {		
		//if(!isset($_SESSION['admin']) && empty($_SESSION['admin']))
		if(isset($_SESSION['admin']) && $_SESSION['admin']!='')
		{
		  return true;
		}
			return false;
	}

	//function to check the authentication.
	function userAuthentication() {
		if(!isset($_SESSION['user']) && empty($_SESSION['user']))
			return false;
		return true;
	}
	
	function lauthentication() {
		if(!isset($_SESSION['ladmin']) && empty($_SESSION['ladmin']))
			return false;
		return true;
	}
	////////////////////// function to get the name field from the id field of a table ///////////////////////////////////
	
	function getName($id,$idValue,$table,$name){
		
		$sqlSelect = "SELECT `".$name."`
					 FROM `".$table."`
					 WHERE `".$id."` = '".$idValue."'
					 ";		
					 

		$relSelect = mysql_query($sqlSelect);
		$nameValue = "";
		while ($row = mysql_fetch_array($relSelect)){		
			$nameValue = $row[$name];
		}
		
		//if ($idValue == 0)
			//$nameValue = "----";
		//echo $nameValue;
		return $nameValue;
	}
	
		function getNameLang($id,$idValue,$table,$name,$lang){
		
		 $sqlSelect = "SELECT `".$name."`
					 FROM `".$table."`
					 WHERE `".$id."` = '".$idValue."' AND `languageid` = '".$lang."' 
					 ";		
		$relSelect = mysql_query($sqlSelect);
		$nameValue = "";
		while ($row = mysql_fetch_array($relSelect)){		
			$nameValue = $row[$name];
		}
		
		//if ($idValue == 0)
			//$nameValue = "----";
		//echo $nameValue;
		return $nameValue;
	}
	
	function getParentName($id,$idValue,$table,$name,$cond){
		
		$sqlSelect = "SELECT ".$name."
					 FROM ".$table."
					 WHERE ".$id." = '".$idValue."' ".$cond."
					 ";	
	//	die;	
		$relSelect = mysql_query($sqlSelect);
		$nameValue = "";
		while ($row = mysql_fetch_array($relSelect)){		
			$nameValue = $row[$name];
		}
		
		//if ($idValue == 0)
			//$nameValue = "----";
		//echo $nameValue;
		return $nameValue;
	}
	
	function getNames_old($id,$idValue,$table,$name){
		
		echo $sqlSelect = "SELECT `".$name."`
					 FROM `".$table."`
					 WHERE `".$id."` = '".$idValue."'
					 ";		
				
		$relSelect = mysql_query($sqlSelect);
		$nameValue = array();
		while ($row = mysql_fetch_array($relSelect)){		
			$nameValue[] = $row[$name];
		}
		
		//if ($idValue == 0)
		//	$nameValue = "----";
		
		return $nameValue;
	}
	
	
	function getNames($id,$idValue,$table,$name){
		
		if($name == '*'){
			$sqlSelect = "SELECT *
					 FROM `".$table."`
					 WHERE `".$id."` = '".$idValue."'
					 ";
			$relSelect = mysql_query($sqlSelect);
			$nameValue = array();
			while ($row = mysql_fetch_array($relSelect)){		
				$nameValue[] = $row;
			}		 
		}
		else{
			$sqlSelect = "SELECT `".$name."`
					 FROM `".$table."`
					 WHERE `".$id."` = '".$idValue."'
					 ";	
			$relSelect = mysql_query($sqlSelect);
			$nameValue = array();
			while ($row = mysql_fetch_array($relSelect)){		
				$nameValue[] = $row[$name];
			}		 	
		}
			 
		
		
		//if ($idValue == 0)
		//	$nameValue = "----";
		
		return $nameValue;
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function getCombo($id,$idValue,$table,$name,$selctval)
	{
		if(isset($idValue) && $idValue!="")
		{
			echo $sqlSelect = "SELECT *
					 FROM `".$table."`
					 WHERE `".$id."` = '".$idValue."'
					 ";			
		}
		else
		{
			echo $sqlSelect = "SELECT *
					 FROM `".$table."`
					 ";		
		}
		 
		$relSelect = mysql_query($sqlSelect);
		$option = "";
		echo $selctval;
		while ($row = mysql_fetch_array($relSelect))
		{
			if($row[$id] == $selctval)
			{
				$select = "selected = 'selected'";
				$option .= '<option value="'.$row[$id].'" '.$select.'>'.$row[$name].'</option>';
			}
			else
			{
				$option .= '<option value="'.$row[$id].'">'.$row[$name].'</option>';
			}
		}
		echo $option;
		//return $option;
	}	
	
	function getLocationTrees($parentId = '0', $level='', $selected_value, $old_pid ){
		$sqlCountry1 ="SELECT blogcat.*,blogcatc.*
				 FROM tblblogcategory blogcat, tblblogcatcontents blogcatc
				 WHERE blogcat.categoryid = blogcatc.categoryid AND blogcatc.languageid = '".$_SESSION['lang_admin']."' AND blogcat.parentid = '".$parentId."'
				 ORDER BY blogcat.categoryid ASC
				 ";	
		$relCountry1 = mysql_query($sqlCountry1);
	
		while ( $rowCountry1 = mysql_fetch_array($relCountry1)){						
			
			if ($rowCountry1['parentid']  == $old_pid && $rowCountry1['parentid'] != 0){
				$level = substr($level,0,strlen($level)-13);
				$old_pid = $rowCountry1['parentid'];
			}
			if ($rowCountry1['parentid'] == 0)
				$level = "" ;
			$option_value = $rowCountry1['categoryid'];
			$option_text = $level.$rowCountry1['name'];
			
			if(is_array($selected_value)){
				if(in_array($option_value,$selected_value))
					$selection = "selected='selected'";
				else
					$selection = "";
			}
			else if($option_value==$selected_value)
				$selection = "selected='selected'";
			else
				$selection = "";		
			
			echo "<option value=\"$option_value\" $selection>$option_text</option>\n";
			$sqlLocation1 ="SELECT blogcat.*,blogcatc.*
				 FROM tblblogcategory blogcat, tblblogcatcontents blogcatc
				 WHERE blogcat.categoryid = blogcatc.categoryid AND blogcatc.languageid = '".$_SESSION['lang_admin']."' AND blogcat.parentid = '".$rowCountry1['categoryid']."'
				 ORDER BY blogcat.categoryid ASC
				 ";
			$relLocation1 = mysql_query($sqlLocation1);				
			$numLocation1 = mysql_num_rows($relLocation1);
			if (($old_pid <= $rowCountry1['parentid']) || $old_pid == 0){
					$level .= '&raquo;&nbsp;';
					$old_pid = $rowCountry1['parentid'];
				}
			if ($numLocation1>0){		
				$this->getLocationTrees($rowCountry1['categoryid'],$level,$selected_value,$rowCountry1['parentid']);
			}		
		
		}
	}
	
	function getLocationTree($parentId = '0', $level='', $selected_value ){		
		
		$sqlCountry ="SELECT * 
				 FROM jp_location
				 WHERE `lc_cmbstatus` = 'active'
				 AND `lc_parentid` = '".$parentId."'
				 ";	
	
		$relCountry = mysql_query($sqlCountry);	
	
		while ( $rowCountry = mysql_fetch_array($relCountry)){				
			
			//$options[] = array("value" => $rowCountry['lc_locid'], "text" => $level.$rowCountry['lc_txtname']);
			
			if ($rowCountry['lc_parentid'] == 0)
				$level = "" ;
			
			$option_value = $rowCountry['lc_locid'];
			
			$option_text = $level.$rowCountry['lc_txtname'];				
			
			if($option_value==$selected_value)
				$selection = "selected='selected'";
			else
				$selection = "";
			
			echo "<option value=\"$option_value\" $selection>$option_text</option>\n";
			
			$sqlLocation ="SELECT *
				 FROM jp_location
				 WHERE `lc_cmbstatus` = 'active'
				 AND `lc_parentid` = '".$rowCountry['lc_locid']."'
				 ";
	
			$relLocation = mysql_query($sqlLocation);				
			
			$numLocation = mysql_num_rows($relLocation);
			
			if ($numLocation>0){
				$level .= '&nbsp;&nbsp;';
				$this->getLocationTree($rowCountry['lc_locid'],$level,$selected_value);
			}		
		
		}
	
	}
	
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	//////////////////////////////// Check email ////////////////////////////////////////////
	
	function checkEmail($email){	   
		
	  // checks proper syntax
	  if(!preg_match( "/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+$/", $email)){
	  
		return false;
	  }
	  else{
		return true;
	  }
	  
	
	  // gets domain name
	/*  list($username,$domain)=split('@',$email);
	  // checks for if MX records in the DNS
	  $mxhosts = array();
	  if(!getmxrr($domain, $mxhosts)){
		// no mx records, ok to check domain
		if (!fsockopen($domain,25,$errno,$errstr,30)){
		  return false;
		}
		else{
		  return true;
		}
	  }
	  else{
		// mx records found
		foreach ($mxhosts as $host){
		  if (fsockopen($host,25,$errno,$errstr,30)){
			return true;
		  }
		}
		return false;
	  }*/
	}
	
	
	
	//////////////////////////////////Function for uploading images//////////////////////////////////////////
	function uploadImage($file_name, $file_path, $table_name, $field_name, $primary_key, $insert_id, $action, $loop=""){
		
		global $dclass;	
		
		$sSql ="SELECT * from `".$table_name."` WHERE `".$primary_key."` = '".$insert_id."'";
				 $rs1 = $dclass->query($sSql);
				 while ( $row = $dclass->fetchArray($rs1) ){
					echo $file_org = $row[$field_name];
					if (strstr('?~~',$file_org))
					  $flname =	explode('?~~',$file_org);
					else
					  $flname[0] =$file_org;
				 }
		$files = "";
		
		if ($loop == "")
			$loop = 1;
		
		$filepath = $file_path;		
			
		$allowed = array('image/pjpeg','image/jpeg','image/gif','image/png');
	
	if ($loop == 1){
	
		if(!isset($_FILES[$file_name]['name']) || $_FILES[$file_name]['name'] != ""){
		
		$filename = $_FILES[$file_name]['name'];
	
		if ( in_array($_FILES[$file_name]['type'],$allowed)){
			
			 if ($_FILES[$file_name]['size'] <= 1000000){
									
					 $filename_db = $insert_id."_".$filename;
					 
					 $filedestination = $filepath.$filename_db;				 
									 
					 if (move_uploaded_file($_FILES[$file_name]['tmp_name'], $filedestination)){
												
								$files .= $filename_db;
						
					 }	
				 }
			 }
		}
	}
	else{	
	for ($i=0; $i<$loop; $i++){
		
	  //if(!isset($_FILES[$file_name]['name'][$i]) || $_FILES[$file_name]['name'][$i] != ""){
		if (isset($flname[$i]) && $flname[$i] !="" && $_FILES[$file_name]['name'][$i] == ""){
			if ($i == 0)
				$files .= $flname[$i];
			else							
				$files .= "?~~".$flname[$i];
		}
		if(!isset($_FILES[$file_name]['name'][$i]) || $_FILES[$file_name]['name'][$i] != ""){
		
		$filename = $_FILES[$file_name]['name'][$i];
	
		if ( in_array($_FILES[$file_name]['type'][$i],$allowed)){
			
			 if ($_FILES[$file_name]['size'][$i] <= 1000000){
									
					 $filename_db = $insert_id."_".$filename;
					 
					 echo $filedestination = $filepath.$filename_db;				 
									 
					 if (move_uploaded_file($_FILES[$file_name]['tmp_name'][$i], $filedestination)){
							
							if ($i == 0)
								$files .= $filename_db;
							else
								$files .= "?~~".$filename_db;
							}	
						 }
				   }
			 }
		}
	}						
							$dbUpdate[$field_name]=$files;
							$where = "`".$primary_key."` = '".$insert_id."'";
							$dclass->update($table_name,$dbUpdate,$where);
	
		  if ($action != "edit"){
		  
			  if(!isset($_FILES[$file_name]['name'][0]) || $_FILES[$file_name]['name'][0] != ""){
				 
				  $where = " `".primary_key."` = '".$insert_id."' ";
	
				  $dclass->delete($table_name,$where);
			 
			  }
		  }
	  //}
	
	}
	
	function full_copy( $source, $target ) {
		
		if ( is_dir( $source ) ) {
			@mkdir( $target );
			$d = dir( $source );
			while ( FALSE !== ( $entry = $d->read() ) ) {
				if ( $entry == '.' || $entry == '..' ) {
					continue;
				}
				$Entry = $source . '/' . $entry; 
				if ( is_dir( $Entry ) ) {
					full_copy( $Entry, $target . '/' . $entry );
					continue;
				}
				copy( $Entry, $target . '/' . $entry );
			}
	 
			$d->close();
		}else {
			copy( $source, $target );
		}
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
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
	//thubm creation in banner
	function bannerThumb($name,$filename,$new_w,$new_h,$path=""){
		
		$wh = getimagesize($path.$name);
	
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
	
		   $thumb_w=$new_w;
			$thumb_h=$new_h;
	
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
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function email_old($emailFrom, $emailTo, $emailCc="", $emailBcc="", $emailSubject, $emailMessage, $emailFormat=""){
	
			$headers = "";
	
	
			if ($emailFormat == "html")
					$content_type = "text/html";
			else
					$content_type = "text/plain";
					
	
			$headers = "Content-type: " . $content_type . "charset=iso-8859-1 \r\n";
	
	
			$headers .= "From: " . $emailFrom . "\n";
			if ($emailCc != "") {
					$headers .= "Cc: " . $emailCc . "\r\n";
			}
			if ($emailBcc != "") {
					$headers .= "Bcc: " . $emailBcc . "\n";
			}
	
			mail($emailTo, $emailSubject, $emailMessage, $headers);
	
	}
	
	/*function email($email_from, $email_to, $email_cc="", $email_bcc="", $email_subject, $email_message, $email_format=""){
	
		// Instantiate a new HTML Mime Mail object
		$mail = new htmlMimeMail();		
		if($smtp_yes){
			$mail->setSMTPParams('fusion-sr2', 25, 'example.com');
			$mail->setReturnPath('test@fusion.in');
		}
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
			$mail->setHTML($email_message);
		}
		else{
			//$content_type = "text/plain";
			$mail->setText($email_message);
		}
		
		if (is_array($email_to)){
			// Send the email!
			if($smtp_yes){$mail->send($email_to,'smtp');}
			else{$mail->send($email_to);}	
		}
		else{
			// Send the email!
			if($smtp_yes){$mail->send(array($email_to),'smtp');}
			else{$mail->send(array($email_to));}
		}
	
	}
	*/
	function emailattach($email_from, $email_to, $email_cc="", $email_bcc="", $email_subject, $email_message, $email_format="",$attchment_path=""){
	
		// Instantiate a new HTML Mime Mail object
		$mail = new htmlMimeMail();		
	
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
	
		$smtp='1';
	
		// Set the mail body text
		if($smtp){
			$mail->setSMTPParams('fusion-sr2', 25, 'example.com');
			$mail->setReturnPath('test@fusion.in');
		}
		if ($email_format == "html"){	
			//$content_type = "text/html";		
			$mail->setHTML($email_message);
		}
		else{
			//$content_type = "text/plain";
			$mail->setText($email_message);
		}
		if($attchment_path != NULL){
			$attachment = $mail->getFile($attchment_path);
			$mail->addAttachment($attachment, basename($attchment_path), 'application/pdf');
		}
		if (is_array($email_to)){
			// Send the email!
			if($smtp){$mail->send(array($email_to),'smtp');}
			else{$mail->send(array($email_to));}
		}
		else{
			// Send the email!
			if($smtp){$mail->send(array($email_to),'smtp');}
			else{$mail->send(array($email_to));}
		}
	}
	
	
	/*function email($email_from, $email_to, $email_cc="", $email_bcc="", $email_subject, $email_message, $email_format=""){
    $smtp_yes='1';
    // Instantiate a new HTML Mime Mail object
    $mail = new htmlMimeMail();		

	//   $mail->setSMTPParams('mail.mila-noga.com', 25, 'mila-noga.com',true,'noreply@mila-noga.com','replyno');
	
	  $mail->setSMTPParams('smtp.gmail.com',25,'smtp.gmail.com',true,'tummyfilrs@gmail.com','3178fipl');
       
	 //  $mail->setSMTPParams('smtp.gmail.com',465,'gmail.com',true,'tummyfilrs@gmail.com','3178fipl');
	//$mail->setReturnPath('tummyfilrs@gmail.com');
	
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
		$mail->setHTML($email_message);
	}
	else{
		//$content_type = "text/plain";
		$mail->setText($email_message);
	}
    
	if (is_array($email_to)){
		// Send the email!
	   $test = $mail->send($email_to);	
	}
	else{
		// Send the email!
	    $test = $mail->send(array($email_to));
	}
	return $test;
}*/


function email($email_from, $email_to, $email_cc="", $email_bcc="", $email_subject, $email_message, $email_format=""){//
      $smtp_yes=1;
	$email_format="html";
    // Instantiate a new HTML Mime Mail object
    $mail = new htmlMimeMail();		

	$mail->setSMTPParams('ssl://smtp.gmail.com', 465, 'gmail.com',true,'test.mailaccounts@gmail.com','3178fipl');
	$mail->setReturnPath('test.mailaccounts@gmail.com'); 
	
	//'info@snagstr.com','Ashah@76' 
	
	//$mail->setSMTPParams('ssl://smtp.ipage.com', 465, 'ipage.com',true,'info@snagstr.com','Ashah@76');
	//$mail->setReturnPath('info@snagstr.com');
		
    // Set the sender address
    $mail->setFrom(SITE_NAME."<".$email_from.">");
    
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
		$content_type = "text/html";		
		//$email_message = str_replace("{site_logo}","<img src='".$this->site_path."/images/logo.jpg' />",$email_message);
		$mail->setHTML($email_message);
	}
	else{
		$content_type = "text/plain";
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
} 

/*	function email($email_from, $email_to, $email_cc="", $email_bcc="", $email_subject, $email_message, $email_format="",$smtp_yes){
	$smtp_yes=1;
	//$smtp_yes="";
    // Instantiate a new HTML Mime Mail object
    $mail = new htmlMimeMail();		
	if($smtp_yes)
	{
	    //$mail->setSMTPParams('192.68.100.105', 25, 'example.com',true,'test@fusion.com','P@ssword009'); 
		//$mail->setSMTPParams('fusionad8', 25, 'example.com',true,'test@fusion.com','P@ssword009');
		$mail->setSMTPParams('smtp.gmail.com', 25, 'example.com',true,'tummyfilrs@gmail.com','3178fipl');		
		$mail->setReturnPath('tummyfilrs@gmail.com');		
	}
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
		$mail->setHTML($email_message);
	}
	else{
		//$content_type = "text/plain";
		$mail->setText($email_message);
	}
	
	if (is_array($email_to)){
		// Send the email!
		if($smtp_yes){
			$test = $mail->send( $email_to,'smtp');
		}
		else{
			$test=$mail->send($email_to);		
		}	
	}
	else{
		// Send the email!
		if($smtp_yes){ $test = $mail->send(array($email_to),'smtp');}
		else{
		$test=$mail->send(array($email_to));}
	}
	return $test;
}*/
	/*function email($email_from, $email_to, $email_cc="", $email_bcc="", $email_subject, $email_message, $email_format=""){
		//$smtp_yes=""; //live server
		$smtp_yes=1; //local server
		// Instantiate a new HTML Mime Mail object
		$mail = new htmlMimeMail();		
		if($smtp_yes)
		{
		$mail->setSMTPParams('192.68.100.103', 25, 'example.com',true,'test@fusion.com','P@ssword009'); 
		}
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
			$mail->setHTML($email_message);
		}
		else{
			//$content_type = "text/plain";
			$mail->setText($email_message);
		}
		
		if (is_array($email_to)){
			// Send the email!
			if($smtp_yes){$mail->send($email_to,'smtp');}
			else{$mail->send($email_to);}	
		}
		else{
			// Send the email!
			if($smtp_yes){$mail->send(array($email_to),'smtp');}
			else{$mail->send(array($email_to));}
		}
	
	}
	*/
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	function getdates($date)
	{
		$date1=explode("-",$date);
		$day =$date1[0];
		$month=$date1[1];
		$year=$date1[2];
		$newdate=$year."-".$month."-".$day;
		return $newdate;
	}
	
	function putdates($date)
	{
		$date=substr($date,0,10);
		$date1=explode("-",$date);
		$year=$date1[0];
		$month=$date1[1];
		$day=$date1[2];
		$newdate=$day."-".$month."-".$year;
		return $newdate;
	}
	
	function printdate($date)
	{
		$date1=explode("-",$date);
		$year=$date1[0];
		$month=$date1[1];
		$day=$date1[2];
		$day1=explode(":",$day);
		$aday=$day1[0];
		$aday=substr($aday,0,2);
		$hr=$day1[0];
		$month_array = array("Jan","Feb","Mar","Apr","May","June","Jul","Aug","Sep","Oct","Nov","Dec");
		//$month = $month_array[$month-1];
		$newdate=$aday."-".$month."-".$year;
		return $newdate;
	}
	
	function subString($string,$length){
			if(strlen($string) > $length){
				$string1 = substr($string,0,$length)."...";
			}
			else{
				$string1 = $string; 
			}
			return $string1;
		}
		
    function correctURL($url){
		$test_arr=split("//",$url);
		if($test_arr[0]=="http:" || $test_arr[0]=="https:")
			return $url;
		else
			return "http://".$url;
	}
	
	////////////// funtion to fetch to content of the given url ////////////////////////////
	
	function fetchURL($address) {
		$host = $address;
		$contents = '';
		$handle = @fopen($host, "rb");
		if($handle){
			while (!@feof($handle)) {
			  $contents .= @fread($handle, 8192);
			}
			@fclose($handle);   
		}
		//print_r($contents);
		return $contents;
	}
	
	
		
	
	function disableRightclick(){
		echo '<script language="javascript" type="text/javascript" src="js/disablerightkey.js"></script>';
	}
	
	function getImageType()
	{
		global $dclass;	
		$result = $dclass->select("*","tbladvbannertype","AND status = 'Active'");
		return $result;
	}
	
	function getBannerWidthHeight($id)
	{
		global $dclass;	
	
		$result = $dclass->select("*","tbladvbannertype","AND id = $id AND status = 'Active'");
		return $result;
	}
	
	function increaseImression($bannerId)
	{
		global $dclass;	
	
		$inc_cnt['bannerid'] = $bannerId;
		$inc_cnt['cpi'] = '1';
		$inc_cnt['cpc'] = '0';
		$sql = $dclass->insert("tbladvbannertrack",$inc_cnt);
	}
	
	function increaseClick($bannerId)
	{
		global $dclass;	
	
		$inc_cnt['bannerid'] = $bannerId;
		$inc_cnt['cpi'] = '0';
		$inc_cnt['cpc'] = '1';
		$sql = $dclass->insert("tbladvbannertrack",$inc_cnt);
	}
	
	function getBannerTrack($bannerid)
	{
		global $dclass;	
	
		$result = $dclass->select("sum(cpi) cpi,sum(cpc) cpc","tbladvbannertrack","AND bannerid = $bannerid");
		return $result;
	}
	
	function getBannerTypeId($banType)
	{
		global $dclass;	
	
		$result = $dclass->select("id","tbladvbannertype","AND label = '$banType'");
		return $result[0]['id'];
		
	}
	
	function getLeftBanner()
	{
		global $dclass;	
	
		$pageName = explode('.',basename($_SERVER['REQUEST_URI']));
		$currentDate = date('Y-m-d');
		$bannertypeid = $this->getBannerTypeId('LEFT');
		$sql = "SELECT * FROM tbladvbanner WHERE pagename = '".$pageName[0]."' AND expdate >= '".$currentDate."' AND bannertypeid = '".$bannertypeid."' AND status = 'Active' ORDER BY sortorder LIMIT 0,1";
		$query = $dclass->query($sql);
		$numOfRows = $dclass->numOfRows($query);
		if($numOfRows > 0)
		{
			$leftBanner = array();
			while($row = $dclass->fetchArray($query))
			{
				$leftBanner['bannerid'] = $row['bannerid'];
				$leftBanner['bannertitle'] = $row['bannertitle'];
				$leftBanner['imagename'] = $row['imagename'];
				$leftBanner['bannerurl'] = $row['bannerurl'];
			}
		}
		else
		{
			$sql = "SELECT * FROM tbladvbanner WHERE expdate >= '".$currentDate."' AND bannertypeid = '".$bannertypeid."' AND status = 'Active' ORDER BY RAND() LIMIT 0,1";
			$query = $dclass->query($sql);
			$leftBanner = array();
			
			while($row = $dclass->fetchArray($query))
			{
				$leftBanner['bannerid'] = $row['bannerid'];
				$leftBanner['bannertitle'] = $row['bannertitle'];
				$leftBanner['imagename'] = $row['imagename'];
				$leftBanner['bannerurl'] = $row['bannerurl'];
			}
		}
		$this->increaseImression($leftBanner['bannerid']);
		return $leftBanner;
	
	}
	
	function getFAQs($where)
	{
		global $dclass;	
	
		$sql = $dclass->query("SELECT * FROM tblfaqcontents WHERE $where");	
		$row = $dclass->fetchArray($sql);
		return $row['faqque'];
	}
	
	function days_between($dformat, $beginDate, $endDate)
	{
		$date_parts1=explode($dformat, $beginDate);
		$date_parts2=explode($dformat, $endDate);
			
		$a_new = mktime( 12, 0, 0, $date_parts1[1], $date_parts1[2], $date_parts1[0] );
		$b_new = mktime( 12, 0, 0, $date_parts2[1], $date_parts2[2], $date_parts2[0] );
	
		return round( ( $b_new - $a_new ) / 86400 );
	
	}
	
	function changeEventDate($eventfrom,$eventto)
	{
		$dates = explode(' ',$eventfrom);
		$date1 = explode(' ',$eventto);
		$fromDate = explode('-',$dates[0]);
		$toDate = explode('-',$date1[0]);
		
		$month1 = date('M',strtotime($dates[0]));
		$month2 = date('M',strtotime($date1[0]));
		if($month1 == $month2) {
			$date = $month1.' ';
	
			if($fromDate[2] != $toDate[2])
				$date .= $fromDate[2].'-'.$toDate[2];
			else
				$date .= $fromDate[2];
		}
		else {
			if($fromDate[2] != $toDate[2])
				$date = $month1.' '.$fromDate[2].' to '.$month2.' '.$toDate[2];
			else
				$date = $month1.','.$month2.' '.$fromDate[2];
		}
		
		
		$date .= ' '.$fromDate[0];
		$dateArray['date'] = $date;
		$dateArray['time'] = $dates[1].' to '.$date1[1];
	
		return $dateArray;
	}

	function blogDateFormat($date)
	{
		$dateSql = mysql_query("SELECT value FROM tblsettings WHERE fieldname = 'blogdateformat'");
		$dateSettings = mysql_fetch_array($dateSql);
		$blogDate = $dateSettings['value'];

		return date($blogDate,strtotime($date));
	}
	
	function blogDateTimeFormat($date)
	{
		$dateSql = mysql_query("SELECT value FROM tblsettings WHERE fieldname = 'blogdateformat'");
		$dateSettings = mysql_fetch_array($dateSql);
		$blogDate = $dateSettings['value'];

		return date($blogDate.' h:i',strtotime($date));
	}
	
	function checkBlogSettings($userName,$userEmail)
	{
		$requireNameEmail = general::getName('fieldname','blogrequirenameemail','tblsettings','value');
		$requireRegistration = general::getName('fieldname','blogcommentregistration','tblsettings','value');
		
		if($requireNameEmail == 1 && $requireRegistration == 1)
		{
			if(general::userAuthentication())
			{
				$blogSetting['login'] = 0;
				$blogSetting['logged'] = 0;
				$blogSetting['userid'] = $_SESSION['userid'];
				$blogSetting['classname'] = "";
				$blogSetting['name'] = $_SESSION['fname'];
				$blogSetting['classemail'] = "";
				$blogSetting['email'] = $_SESSION['email'];
			}
			else
			{
				$blogSetting['login'] = 1;
				$blogSetting['logged'] = 1;
			}
		}
		elseif($requireNameEmail == 1 && $requireRegistration == '')
		{
			if(general::userAuthentication())
			{
				$blogSetting['login'] = 0;
				$blogSetting['logged'] = 0;
				$blogSetting['classname'] = "";
				$blogSetting['name'] = $_SESSION['fname'];
				$blogSetting['classemail'] = "";
				$blogSetting['email'] = $_SESSION['email'];
			}
			else
			{
				$blogSetting['login'] = 0;
				$blogSetting['logged'] = 1;
				$blogSetting['style'] = "";
				$blogSetting['classname'] = "validate[required]";
				$blogSetting['name'] = $userName;
				$blogSetting['classemail'] = "validate[required,custom[email]]";
				$blogSetting['email'] = $userEmail;
			}
		}
		elseif($requireNameEmail == '' && $requireRegistration == 1)
		{
			if(general::userAuthentication())
			{
				$blogSetting['login'] = 0;
				$blogSetting['logged'] = 0;
				$blogSetting['classname'] = "";
				$blogSetting['name'] = $_SESSION['fname'];
				$blogSetting['classemail'] = "";
				$blogSetting['email'] = $_SESSION['email'];
			}
			else
			{
				$blogSetting['login'] = 1;
				$blogSetting['logged'] = 1;
			}
		}
		else
		{
			if(general::userAuthentication())
			{
				$blogSetting['login'] = 0;
				$blogSetting['logged'] = 0;
				$blogSetting['classname'] = "";
				$blogSetting['name'] = $_SESSION['fname'];
				$blogSetting['classemail'] = "";
				$blogSetting['email'] = $_SESSION['email'];
			}
			else
			{
				$blogSetting['login'] = 0;
				$blogSetting['logged'] = 1;
				$blogSetting['style'] = "";
				$blogSetting['classname'] = "";
				$blogSetting['name'] = $userName;
				$blogSetting['classemail'] = "validate[custom[email]]";
				$blogSetting['email'] = $userEmail;
			}
		}
	
		return $blogSetting;
	}
	
	function checkCloseCommentDay($date)
	{
		$closeComment = general::getName('fieldname','blogclosecommentsforoldposts','tblsettings','value');
		if($closeComment == 1)
		{
			$blogCloseDay = general::getName('fieldname','blogclosecommentsdaysold','tblsettings','value');
			$numberOfDays = general::days_between('-', $date, date('Y-m-d'));
			if($blogCloseDay >= $numberOfDays)
				return true;
			else
				return false;
		}
		return true;
	}
	function checkBlogCommentApprove()
	{
		$blogCommentApprove = general::getName('fieldname','blogcommentapprove','tblsettings','value');
		if($blogCommentApprove == 1)
			return 'Inactive';
		else
			return 'Active';
	}

	function rssActive()
	{
		$blogrssActive = general::getName('fieldname','blogrss','tblsettings','value');
		if($blogrssActive == 1)
			return true;
		else
			return false;
	}

	function checkRssLimit()
	{
		$blogrssrecords = general::getName('fieldname','blogrssrecords','tblsettings','value');
		if($blogrssrecords > 0)
			return 'LIMIT 0,'.$blogrssrecords;
		else
			return '';
	}
	
function getImage($path,$info){
	return "memimages.php?imgfile=$path&amp;max_width=$info[width]";
}

	function displaydate($date)
	{
		$date1=explode("-",$date);
		$dtime=substr($date,10);
		$year=$date1[0];
		$month=$date1[1];
		$day=$date1[2];
		$day1=explode(":",$day);
		$aday=$day1[0];
		$aday=substr($aday,0,2);
		$hr=$day1[0];
		$month_array = array("Jan","Feb","Mar","Apr","May","June","Jul","Aug","Sep","Oct","Nov","Dec");
		$month = $month_array[$month-1];
		$newdate=$aday."-".$month."-".$year." ".$dtime;
		return $newdate;
	}
	
	 function getinvoiceid($oid)
  {
		   $settings = $this->getSetting();
    	   $invoice_digit = $settings['invoice_digit'];

           $invoiceid_format = '';
   
           if(strlen($oid)==1)
			{
			 $invoiceid_format = "000".$oid;
			}
			else if(strlen($oid)==2)
			{
			  $invoiceid_format = "00".$oid;
			}
			else if(strlen($oid)==3)
			{
			  $invoiceid_format = "0".$oid;
			}
			else if(strlen($oid)==4)
			{
			  $invoiceid_format = $oid;
			}
			else
			{
			  $invoiceid_format = $oid;
			}
		 
   return $invoice_digit.$invoiceid_format;
  }
	
		function currency_symbol()
	{
		$settings = general::getSetting();
		$currency  = $settings['currency'];
		$currency  = general::getName('id',$currency,'tblcurrency','currency_symbol');
  		return $currency;
	}
	
}
?>