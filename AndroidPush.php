<?php 
try
{
require_once('includes/app_config.php');
$url = 'https://android.googleapis.com/gcm/send';
$serverApiKey = "AIzaSyDCkhjPVcb4N4l6zqD82WqSfpTGcolv00E";
//AIzaSyBapbkjWoJK8LvX3ePMj49x8tLWK77XYYE 

$headers = array(
 'Content-Type:application/json',
 'Authorization:key=' . $serverApiKey
 );

//Deliver before timestamp
$deliverbefore = gmdate('Y-m-d\TH:i:s\Z', strtotime('+60 minutes'));

$dt = date('Y-m-d H:i:s');
$to_time=strtotime($dt);	
$new_time = date("Y-m-d H:i",strtotime("-290 minutes",strtotime($dt)));//date("Y-m-d H:i:s", strtotime('-5 hours')).	
	
	//$sql="SELECT ntf.* FROM notification as ntf WHERE (`ntfUdid` <> '' AND `ntfUdid` <> '(null)') AND   `ntfStatus`='No' AND ntfmsgText <> '' AND ntfdeviceType='iPhone' AND DATE(ntfCreatedDate) = DATE(NOW()) LIMIT 50 ";
	$sql="SELECT ntf.* FROM notification as ntf WHERE (`ntfUdid` <> '' AND `ntfUdid` <> '(null)') AND   `ntfStatus`='No' AND ntfmsgText <> '' AND ntfdeviceType='Android' LIMIT 50 ";
	
	$reso = mysql_query($sql);
	//echo $sql;
	//die;
	
	while($exec = mysql_fetch_array($reso))
	{	
		extract($exec);
	
		//$badgeCnt = mysql_query("SELECT ns.*,um.umUnreadNotification FROM notifications as ns INNER JOIN usermaster um ON um.umId=ns.notiReceiverId WHERE `notiToken` <> '' AND `notiToken` <> '(null)' AND   `notiSent`='No' AND notiMessage <> '' AND notiId=".$notiId);
		
		//$execBadge = mysql_fetch_array($badgeCnt);
		//$umUnreadNotification=$execBadge["umUnreadNotification"];
		
		
			$data = array(
			 'registration_ids' => array($ntfUdid)
			 , 'data' => array(
			 'type' => 'New'
			 , 'title' => 'GCM'
			 , 'msg' => $ntfmsgText
			 , 'msgType' => $ntfType
			 , 'msgKey' => $ntmEvtId	
			,	'badge' => $memBadgeCount+1
			 , 'notiId' => $ntfId		
			 )
			 );
			 
			 
			 
			 
			//print_r($data);
			 $ch = curl_init();
			 curl_setopt($ch, CURLOPT_URL, $url);
			 if ($headers)
			 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			 curl_setopt($ch, CURLOPT_POST, true);
			 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			
			 $response = curl_exec($ch);
			
			curl_close($ch);
			 print ($response);
		
		
			@mysql_query("UPDATE members SET memBadgeCount=memBadgeCount+1 WHERE memId=".$ntfUmId);
			@mysql_query("UPDATE notification SET ntfError='$err' WHERE ntfId=".$ntfId);
			@mysql_query("UPDATE notification SET ntfStatus='Yes', ntfSendDate=NOW() WHERE ntfId=".$ntfId);
		
		//echo "<br>";
	}				
						
	
}
catch (Exception $e)
{
    fatalError($e);
}
?>
