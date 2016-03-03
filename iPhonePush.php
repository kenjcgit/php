<?php 
//echo "before end";
//die;
//echo "after end";
try
{
require_once('includes/app_config.php');

$timezone = new DateTimeZone('America/New_York');
        $date = new DateTime();
        $date->setTimezone($timezone);
        $time =  $date->format('H:i:s');

$deliverbefore = gmdate('Y-m-d\TH:i:s\Z', strtotime('+60 minutes'));


//For iphone
$apnsHost = 'gateway.push.apple.com';//for live change the url :- gateway.push.apple.com 
$apnsPort = 2195;
$apnsCert = 'apns-dev.pem';	
$iPadapnsCert = 'ipad-apns-dev.pem';				


$dt = date('Y-m-d H:i:s');
$to_time=strtotime($dt);	
$new_time = date("Y-m-d H:i",strtotime("-290 minutes",strtotime($dt)));//date("Y-m-d H:i:s", strtotime('-5 hours')).
		
	
	/*$sql="UPDATE notifications SET notiSent='Yes', notiSentDate=NOW() WHERE `notiSent`='No' AND (notiScheduleDate = '0000-00-00 00:00:00' ) AND `notiContentType` <> 'SM'  AND notiDeviceType='iphone' AND DATE(notiCreatedDate) < DATE(NOW()) ";
	mysql_query($sql);*/
	 
	 
	//$sql="SELECT ntf.* FROM notification as ntf WHERE (`ntfUdid` <> '' AND `ntfUdid` <> '(null)') AND   `ntfStatus`='No' AND ntfmsgText <> '' AND ntfdeviceType='iPhone' AND DATE(ntfCreatedDate) = DATE(NOW()) LIMIT 50 ";
	$sql="SELECT ntf.* FROM notification as ntf WHERE (`ntfUdid` <> '' AND `ntfUdid` <> '(null)') AND   `ntfStatus`='No' AND ntfmsgText <> '' AND ntfdeviceType='iPhone' LIMIT 50 ";
	
	$reso = mysql_query($sql);
	//echo $sql;
//	die;
	
	while($exec = mysql_fetch_array($reso))
	{	
		extract($exec);
		
		$badgeCnt = mysql_query("SELECT mem.memBadgeCount FROM notification as ns INNER JOIN members mem ON mem.memId=ns.ntfUmId WHERE ntfId=".$ntfId);
		
		$execBadge = mysql_fetch_array($badgeCnt);
		$memBadgeCount=$execBadge["memBadgeCount"];
			
			//Setup stream (connect to Apple Push Server)
			$ctx = stream_context_create();
			//stream_context_set_option($ctx, 'ssl', 'passphrase', 'password_for_apns.pem_file');
			stream_context_set_option($ctx, 'ssl', 'local_cert', $apnsCert);
			
			$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
			stream_set_blocking ($fp, 0); //This allows fread() to return right away when there are no errors. But it can also miss errors during last seconds of sending, as there is a delay before error is returned. Workaround is to pause briefly AFTER sending last notification, and then do one more fread() to see if anything else is there.

			if (!$fp) {
				//ERROR
				echo "Failed to connect (stream_socket_client): $err $errstrn";

			} else {

				$apple_expiry = time() + (90 * 24 * 60 * 60); //Keep push alive (waiting for delivery) for 90 days

				//Loop thru tokens from database
				
				$body = array();			
				
				
				$body['aps'] = array('alert' => $ntfmsgText, "sound"=> "Default",'badge' => $memBadgeCount+1,'msgType' => $ntfType, 'msgKey' => $ntmEvtId, 'notiId' => $ntfId  );
				
				//echo $notiToken;
				echo $payload = json_encode($body);
				$msg = pack("C", 1) . pack("N", $ntfId) . pack("N", $apple_expiry) . pack("n", 32) . pack('H*', str_replace(' ', '', $ntfUdid)) . pack("n", strlen($payload)) . $payload; //Enhanced Notification
				fwrite($fp, $msg); //SEND PUSH
				checkAppleErrorResponse($fp); //We can check if an error has been returned while we are sending, but we also need to check once more after we are done sending in case there was a delay with error response.
				
			
				//Workaround to check if there were any errors during the last seconds of sending.
				usleep(500000); //Pause for half a second. Note I tested this with up to a 5 minute pause, and the error message was still available to be retrieved

				checkAppleErrorResponse($fp);

				echo 'DONE!';

				//mysql_close($con);
				fclose($fp);
			}
			@mysql_query("UPDATE members SET memBadgeCount=memBadgeCount+1 WHERE memId=".$ntfUmId);
			@mysql_query("UPDATE notification SET ntfError='$err' WHERE ntfId=".$ntfId);
			@mysql_query("UPDATE notification SET ntfStatus='Yes', ntfSendDate=NOW() WHERE ntfId=".$ntfId);
		
		
	}				
						
	
}
catch (Exception $e)
{
    fatalError($e);
}
function checkAppleErrorResponse($fp) {
	echo $fp;
	print_r($fp);
    $apple_error_response = fread($fp, 6);

    if ($apple_error_response) {

        $error_response = unpack('Ccommand/Cstatus_code/Nidentifier', $apple_error_response);

        if ($error_response['status_code'] == '0') {
            $error_response['status_code'] = '0-No errors encountered';
        } else if ($error_response['status_code'] == '1') {
            $error_response['status_code'] = '1-Processing error';
        } else if ($error_response['status_code'] == '2') {
            $error_response['status_code'] = '2-Missing device token';
        } else if ($error_response['status_code'] == '3') {
            $error_response['status_code'] = '3-Missing topic';
        } else if ($error_response['status_code'] == '4') {
            $error_response['status_code'] = '4-Missing payload';
        } else if ($error_response['status_code'] == '5') {
            $error_response['status_code'] = '5-Invalid token size';
        } else if ($error_response['status_code'] == '6') {
            $error_response['status_code'] = '6-Invalid topic size';
        } else if ($error_response['status_code'] == '7') {
            $error_response['status_code'] = '7-Invalid payload size';
        } else if ($error_response['status_code'] == '8') {
            $error_response['status_code'] = '8-Invalid token';
        } else if ($error_response['status_code'] == '255') {
            $error_response['status_code'] = '255-None (unknown)';
        } else {
            $error_response['status_code'] = $error_response['status_code'].'-Not listed';
        }

        $msg= 'Response Command:<b>' . $error_response['command'] . '</b>&nbsp;&nbsp;&nbsp;Identifier:<b>' . $error_response['identifier'] . '</b>&nbsp;&nbsp;&nbsp;Status:<b>' . $error_response['status_code'] . '</b><br>';

        $msg.= 'Identifier is the rowID (index) in the database that caused the problem, and Apple will disconnect you from server. To continue sending Push Notifications, just start at the next rowID after this Identifier.<br>';

        return $msg;
    }

    return "";
}
?>
