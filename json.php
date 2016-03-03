<?php
//error_reporting(-1);
require_once('includes/app_config.php');
require_once('includes/jsonfunctions.php');
require_once('includes/pushfunctions.php');
include_once('../wp-includes/user.php');

//error_reporting(-1);
$date= date("Y-m-d h:i:s");
$cdate= date("Y-m-d");
$debug="false";
extract($_POST);
//extract($_GET);
if(isset($json))
{
	$json1= stripcslashes($json);
	$json1= stripcslashes($json1);
	$arr = json_decode(stripcslashes($json1));
	$arr1 = $arr[0];

	foreach($arr1 as $key => $value)
	{		
		
		$value = str_replace("'",'',$value);		
		$dbvalues[$key] = $value;
	}
	extract($dbvalues);
}


if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
}
else
{
	echo "Please insert Action";
	exit;
}


$sqlLimit="";
$start=0;
$end=0;
$pageCount=10;
if(isset($page))
{
	$pageNumber=$page;
	if($pageNumber==1)
	{
		$end=$pageCount;
	}
	else
	{
		$start= (($pageNumber-1)*$pageCount)+1;
		$end = $pageCount;
	}
	$sqlLimit=" LIMIT $start,$end";
	//$sqlLimit=" LIMIT $end";	
	
}

extract($glob);
$dbh = new PDO('mysql:host=localhost;dbname='.$glob['dbdatabase'], $glob['dbusername'], $glob['dbpassword'],array( PDO::ATTR_PERSISTENT => false));
$GLOBALS['dbh']=$dbh;

switch ($action) 
{
	
	
	case 'ResetBadge':
		@mysql_query("UPDATE members SET memBadgeCount='$badge' WHERE memId='$memId'");
		$result[0]["status"]='true';
		echo $string = str_replace('\/','/',json_encode($result));       
		break;	
	case 'getnotifications':
		if(isset($ntfType))
			$whe= " AND ntfType='$ntfType'"	;
		$notificationsSQL="SELECT mem.memName, noti.ntfType, noti.ntfmsgText, noti.ntfSendDate, evt.evtName, evt.evtStartDate, evt.evtImage, evt.evtAddress, evt.evtLatitude, evt.evtLongitude, evt.evtInfo FROM notification AS noti Left Join events AS evt ON noti.ntmEvtId = evt.evtId Inner Join members AS mem ON noti.ntfUmId = mem.memId  WHERE ntfUmId='$memId' $whe ORDER BY ntfSendDate DESC $sqlLimit";
		$resnotifications=mysql_query($notificationsSQL);
		$result = array();	
		$i=0;	
		while($row = mysql_fetch_array($resnotifications,MYSQL_ASSOC))
		{
			$result[$i] = $row;
			$i++;
		}
		echo $string = str_replace('\/','/',json_encode($result));       
		break;	
	case 'GetActivities':
	
		$activitytypeSQL="SELECT actTypeId, actTypeName, actInfo  FROM activitytype WHERE actStatus='Active' ORDER BY actTypeName ";	
		$result = array();	
		$i=0;	
		foreach($dbh->query($activitytypeSQL, PDO::FETCH_ASSOC) as $row)
		{
			$result[$i] = $row;
			$i++;
		}
		echo $string = str_replace('\/','/',json_encode($result));       
		break; 
	case 'GetEvents':
		//print_r($dbvalues);
		
		if(isset($actTypeId) && $actTypeId!="")
				$whe.= " AND evtactTypeId=".$actTypeId;
		if(isset($sortType) && $sortType=="1")
				$whe.= " AND evtStartDate >= CURDATE()";	
		if(isset($sortType) && $sortType=="2")
				$whe.= " AND evtStartDate < CURDATE()";		
			
		
		 $eventsSQL="SELECT evtId, evtactTypeId, evtName, evtStartDate, evtEndDate, evtStartTime, evtEndTime, evtInfo, evtImage, evtLocatioName, evtAddress, evtLatitude, evtLongitude, evtTags FROM events WHERE evtStatus='Active' $whe  ORDER BY evtStartDate $sqlLimit";
		$result = array();	
		$i=0;	
		foreach($dbh->query($eventsSQL, PDO::FETCH_ASSOC) as $row)
		{
			$result[$i] = $row;
			if($memId>0)
				$result[$i]["alreadyregistered"]=checkEventRegistered($memId,$row["evtId"]) 		;
			$i++;
		}
		echo $string = str_replace('\/','/',json_encode($result));       
		break; 
	case 'AttendEvent':
		
		$eventsSQL="SELECT eadId FROM eventattendees  WHERE eadEvtIdi='$eadEvtIdi' AND eadMemId='$eadMemId' ";
		$result = array();	
		$i=0;	
		foreach($dbh->query($eventsSQL, PDO::FETCH_ASSOC) as $row)
		{			
			$i++;
			$eadId=$row["eadId"];
			@mysql_query("UPDATE eventattendees SET eadAttendeeStatus='$eadAttendeeStatus' WHERE eadId='$eadId'");
		}
		if($i>0)
		{
				$result[0]["eadId"]=$eadId;
				$result[0]["status"]='true';
		}
		else
		{
			$eventattendeesSQL="INSERT INTO eventattendees (`eadEvtIdi`, `eadMemId`,`eadAttendeeStatus`) VALUES ('$eadEvtIdi', '$eadMemId','$eadAttendeeStatus')";
			$reseventattendees=mysql_query($eventattendeesSQL);
			$id = mysql_insert_id();
			if($id >0)
			{
				$result[0]["eadId"]=$id;
				$result[0]["status"]='true';			
			}
			else
			{
				$result[0]["eadId"]=0;
				$result[0]["status"]='false';		
			}
		}
		echo $string = str_replace('\/','/',json_encode($result));			
		break;	
	case 'GetContacts':
	
		$contactsSQL="SELECT cnId, cnName, cnEmail, cnPhone, cnAddress, cnStatus, cnCreatedDate, cnLatitude,cnLongitude FROM contacts WHERE cnStatus='Active' ORDER BY cnName $sqlLimit ";	
		$result = array();	
		$i=0;	
		foreach($dbh->query($contactsSQL, PDO::FETCH_ASSOC) as $row)
		{
			$result[$i] = $row;
			$i++;
		}
		echo $string = str_replace('\/','/',json_encode($result));       
		break; 
	Case 'SignUp':
		
		$retStr=validateData("members",$dbvalues);
		if($retStr=="")
		{
			if(!isset($memName) || $memEmail=="" || $memDob=="" || $memDeviceType=="" || $memUDID=="" || $memPassword=="")
			{
				$retStr='Required data is missing';
				$i=1;
			}
			else
			{
				$customersSQL="SELECT memId FROM members WHERE memEmail='$memEmail'";					
				$result = array();	
				$i=0;	
				foreach($dbh->query($customersSQL, PDO::FETCH_NAMED) as $row)
				{			
					$retStr='Email id already in used';
					$i++;
				}
			}
			if($i==0)
			{

				$membersSQL="INSERT INTO members (`memName`, `memMobile`, `memEmail`, `memDob`, `memGender`, `memDeviceType`, `memUDID`, `memNewEventPush`, `memGeneralPush`, `memActivityReminderPush`, `memEventReminderPush`, `memPush`, `memPassword`) VALUES ('$memName', '$memMobile', '$memEmail', '$memDob', '$memGender', '$memDeviceType', '$memUDID', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '".base64_encode($memPassword)."')";
				$resmembers=mysql_query($membersSQL);
				$id = mysql_insert_id();
				if($id >0)
				{
					$result[0]["memId"]=getMember($id);
					$result[0]["status"]='true';			
				}
				else
				{
					$result[0]["memId"]=0;
					$result[0]["status"]='false';		
				}
			}
			else
			{

					
					$result[0]["memId"]=0;
					$result[0]["status"]=$retStr;;
			}			
		}	
		else
		{
					$result[0]["memId"]=0;
					$result[0]["status"]=$retStr;
		}
			
		echo $string = str_replace('\/','/',json_encode($result));		
		break;		
	case 'Login':
		
		
		$usersSQL="SELECT memId FROM members WHERE  memEmail='$memEmail' AND memPassword='".base64_encode($memPassword)."' AND memStatus='Active'";	
		$result = array();	
		$i=0;			
		//$result[0]["status"]='Invalid username or password';
		foreach($dbh->query($usersSQL, PDO::FETCH_ASSOC) as $row)
		{			
			
			$result = getMember($row["memId"]);						
			$i++;
		}
		if($i==0)
		{
			
			$wpUserSQL = "SELECT * FROM ken_users WHERE user_email='".$memEmail."'";
			$result = array();						
			foreach($dbh->query($wpUserSQL, PDO::FETCH_ASSOC) as $row)
			{						
				/*insert into our user table*/
				//echo " in 2";	
				/*check email already in our table or not*/
				$customersSQL="SELECT memId FROM members WHERE memEmail='$memEmail' AND memUserOrigin='WP' ";					
				$result = array();					
				foreach($dbh->query($customersSQL, PDO::FETCH_NAMED) as $row2)
				{			
					$wpInsSQL="UPDATE members SET memPassword='".base64_encode($memPassword)."' memEmail='$memEmail'";
					$resmembers=mysql_query($wpInsSQL);
					$result = getMember($row2["memId"]);						
					$i++;
				}
				if($i==0)
				{
					//echo " in 3";
					extract($row)	;
					$membersSQL="INSERT INTO members (`memName`, `memMobile`, `memEmail`, `memDob`, `memGender`, `memDeviceType`, `memUDID`, `memNewEventPush`, `memGeneralPush`, `memActivityReminderPush`, `memEventReminderPush`, `memPush`, `memPassword`) VALUES ('$user_nicename', '', '$user_email', '', '', '', '', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '".base64_encode($memPassword)."')";
					$resmembers=mysql_query($membersSQL);
					$id = mysql_insert_id();
					if($id >0)
					{
						$result[0] =getMember($id);
						$result[0]["status"]='true';			
						$i++;
					}
					else					
						$result[0]["status"]='Invalid username or password';
					
					
					
				}
				
			}
			if($i==0)
				{
					$result[0]["status"]='Invalid username or password';
				}
			
			
			
		}		
		echo $string = str_replace('\/','/',json_encode($result));       
		break;
	case 'ForgotPassword':
		$usersSQL="SELECT memId FROM members WHERE  memEmail='$memEmail'  AND memStatus='Active'";	
		$result = array();	
		$i=0;	
		foreach($dbh->query($usersSQL, PDO::FETCH_ASSOC) as $row)
		{
			$result[0]["memId"]=$row["memId"];
			$result[0]["status"]='true';	
			
			$res=getMember($row["memId"]);
			
			$i++;
		}
		if($i==0)
		{
			
			$result[0]["status"]='Invalid Email Address';	
		}
		echo $string = str_replace('\/','/',json_encode($result));       
		break;
	case 'UpdateDeviceInfo':		
		$usersSQL="UPDATE members SET `memUDID`='$memUDID', `memDeviceType`='$memDeviceType' WHERE memId=".$memId;
		$resusers=mysql_query($usersSQL);		
		$result[0]["status"]='Successfully updated';			
		echo $string = str_replace('\/','/',json_encode($result));			
		break;	
	case 'ChangePassword':		
		$usersSQL="UPDATE members SET `memPassword`='".base64_encode($memPassword)."' WHERE memId=".$memId;
		$resusers=mysql_query($usersSQL);		
		
		/*WP integration */
		include_once('../wp-includes/pluggable.php');
		$usersSQL="SELECT memEmail FROM members WHERE  memId='$memId'  ";	
		$result = array();	
		$i=0;	
		foreach($dbh->query($usersSQL, PDO::FETCH_ASSOC) as $row)
		{
			$memEmail=$row["memEmail"];
			$i++;
		}
		
		$wpUserSQL = "SELECT * FROM ken_users WHERE user_email='".$memEmail."'";
		$result = array();	
		$i=0;	
		foreach($dbh->query($wpUserSQL, PDO::FETCH_ASSOC) as $row)
		{
			$user_id=$row["ID"];
			$i++;
		}		
		$password = $memPassword;
		wp_set_password( $password, $user_id );

		/*over*/
		$result[0]["status"]='Successfully updated';			
		echo $string = str_replace('\/','/',json_encode($result));			
		break;
	Case 'EditProfile':		
		$retStr=validateData("members",$dbvalues);
		if($retStr=="")
		{
			if(!isset($memName) || !isset($memMobile) || $memEmail=="" || $memDob=="" || $memGender=="" || $memDeviceType=="" || $memUDID=="" || $memId=="")
			{
				$retStr='Required data is missing';
				$i=1;
			}
			else
			{
				$customersSQL="SELECT memId FROM members WHERE memEmail='$memEmail' AND memId <>'$memId'";					
				$result = array();	
				$i=0;	
				foreach($dbh->query($customersSQL, PDO::FETCH_NAMED) as $row)
				{			
					$retStr='Email id already in used';
					$i++;
				}
			}
			if($i==0)
			{

					$membersSQL="UPDATE members SET `memName`='$memName', `memMobile`='$memMobile', `memEmail`='$memEmail', `memDob`='$memDob', `memGender`='$memGender', `memDeviceType`='$memDeviceType', `memUDID`='$memUDID' WHERE memId=".$memId;
					$resusers=mysql_query($membersSQL);	
					$result=getMember($memId);					
					$result[0]["status"]='true';//.mysql_error.$membersSQL;					
			}
			else
			{

					
					$result[0]["memId"]=0;
					$result[0]["status"]=$retStr;;
			}			
		}	
		else
		{
					$result[0]["memId"]=0;
					$result[0]["status"]=$retStr;
		}
			
		echo $string = str_replace('\/','/',json_encode($result));		
		break;
	/*case 'ChangePassword':		
		$usersSQL="UPDATE members SET `memPassword`='".base64_encode($memPassword)."' WHERE memId=".$memId;
		$resusers=mysql_query($usersSQL);		
		$result[0]["status"]='Successfully updated';			
		echo $string = str_replace('\/','/',json_encode($result));			
		break;*/
	Case 'EditNotificationSettings':		
		$retStr=validateData("members",$dbvalues);
		if($retStr=="")
		{
			if(!isset($memNewEventPush) || !isset($memGeneralPush) || $memActivityReminderPush=="" || $memEventReminderPush=="" || $memPush==""  || $memId=="")
			{
				$retStr='Required data is missing';
				$i=1;
			}
			
			if($i==0)
			{

					$membersSQL="UPDATE members SET  `memNewEventPush`='$memNewEventPush', `memGeneralPush`='$memGeneralPush', `memActivityReminderPush`='$memActivityReminderPush', `memEventReminderPush`='$memEventReminderPush', `memPush`='$memPush', `memCreatedDate`='$memCreatedDate', `memETC`='$memETC', `memAdultActivities`='$memAdultActivities', `memYouthActivities`='$memYouthActivities', `memMiniMaccabiActivities`='$memMiniMaccabiActivities', `memBogrim`='$memBogrim' , `memNonCommunityEvents` ='$memNonCommunityEvents'   WHERE memId=".$memId;
					$resusers=mysql_query($membersSQL);	
					$result=getMember($memId);					
					$result[0]["status"]='true';					
			}
			else
			{

					
					$result[0]["memId"]=0;
					$result[0]["status"]=$retStr;;
			}			
		}	
		else
		{
					$result[0]["memId"]=0;
					$result[0]["status"]=$retStr;
		}
			
		echo $string = str_replace('\/','/',json_encode($result));		
		break;	
	default:		
		$arr2[0]["Status"]= "Please pass proper JSON parameters";
		echo $string = str_replace('\"','',str_replace("\/","/",json_encode($arr2)));
		break;
}

?>