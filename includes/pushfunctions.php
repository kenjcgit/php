<?php
function sendEmployeeRergistrationPush($deviceType,$notiToken,$notiContentId,$umId)
{
	//echo " dsd sd".$deviceType;
	$notiMessage = "Please complete your profile to be able to create or apply for a job!";
	$notiContentType = "EmpRegistration";
	if($deviceType=="iphone")	
		buildAPNS($notiMessage,$notiContentType,$notiContentId,$notiToken,$umId);
	if($deviceType=="android")	
		buildDroidCurl($notiMessage,$notiContentType,$notiContentId,$notiToken,$umId);
		
}
function sendEmployeeReviewPush($deviceType,$notiToken,$notiContentId,$umId)
{	
	$notiMessage = "A review of you has been posted, please click to read it!";
	$notiContentType = "EmpReview";
	if($deviceType=="iphone")	
		buildAPNS($notiMessage,$notiContentType,$notiContentId,$notiToken,$umId);
	if($deviceType=="android")	
		buildDroidCurl($notiMessage,$notiContentType,$notiContentId,$notiToken,$umId);		
}
function sendEmployeeSelectPush($deviceType,$notiToken,$notiContentId,$umId)
{
	
	$notiMessage = "Congratulation! You have been hired, please click to see the details!";
	$notiContentType = "EmpHire";
	if($deviceType=="iphone")	
		buildAPNS($notiMessage,$notiContentType,$notiContentId,$notiToken,$umId);
	if($deviceType=="android")	
		buildDroidCurl($notiMessage,$notiContentType,$notiContentId,$notiToken,$umId);
		
}
function sendEmployeeMessagePush($deviceType,$notiToken,$notiContentId,$umId)
{
	//echo " dsd sd".$deviceType;
	$notiMessage = "You have received a new message, please click to read it!";
	$notiContentType = "EmpMessage";
	if($deviceType=="iphone")	
		buildAPNS($notiMessage,$notiContentType,$notiContentId,$notiToken,$umId);
	if($deviceType=="android")	
		buildDroidCurl($notiMessage,$notiContentType,$notiContentId,$notiToken,$umId);
		
}
function sendEmployeeJobPush($deviceType,$notiToken,$notiContentId,$umId)
{
	//echo " dsd sd".$deviceType;
	$notiMessage = "A new job has been posted not far away from you, please click  to check it!";
	$notiContentType = "EmpJob";
	if($deviceType=="iphone")	
		buildAPNS($notiMessage,$notiContentType,$notiContentId,$notiToken,$umId);
	if($deviceType=="android")	
		buildDroidCurl($notiMessage,$notiContentType,$notiContentId,$notiToken,$umId);
		
}
function sendEmployerRergistrationPush($deviceType,$notiToken,$notiContentId,$umId)
{
	//echo " dsd sd".$deviceType;
	$notiMessage = "Please complete your profile to be able to create or apply for a job!";
	$notiContentType = "EmrRegistration";
	if($deviceType=="iphone")	
		buildAPNS($notiMessage,$notiContentType,$notiContentId,$notiToken,$umId);
	if($deviceType=="android")	
		buildDroidCurl($notiMessage,$notiContentType,$notiContentId,$notiToken,$umId);
		
}
function sendEmployerReviewPush($deviceType,$notiToken,$notiContentId,$umId)
{	
	$notiMessage = "A review of you has been posted, please click to read it!";
	$notiContentType = "EmrReview";
	if($deviceType=="iphone")	
		buildAPNS($notiMessage,$notiContentType,$notiContentId,$notiToken,$umId);
	if($deviceType=="android")	
		buildDroidCurl($notiMessage,$notiContentType,$notiContentId,$notiToken,$umId);		
}
function sendEmployerMessagePush($deviceType,$notiToken,$notiContentId,$umId)
{
	//echo " dsd sd".$deviceType;
	$notiMessage = "You have received a new message, please click to read it!";
	$notiContentType = "EmrMessage";
	if($deviceType=="iphone")	
		buildAPNS($notiMessage,$notiContentType,$notiContentId,$notiToken,$umId);
	if($deviceType=="android")	
		buildDroidCurl($notiMessage,$notiContentType,$notiContentId,$notiToken,$umId);
		
}
function buildAPNS($notiMessage,$notiContentType,$notiContentId,$notiToken,$umId)
{
	//echo "Sds ";
	$timezone = new DateTimeZone('America/New_York');
	$date = new DateTime();
	$date->setTimezone($timezone);
	$time =  $date->format('H:i:s');

	$deliverbefore = gmdate('Y-m-d\TH:i:s\Z', strtotime('+35 minutes'));


	//For iphone
	$apnsHost = 'gateway.push.apple.com';//for live change the url :- gateway.push.apple.com 
	$apnsPort = 2195;
	$apnsCert = 'apns-dev.pem';	
	$iPadapnsCert = 'apns-dev.pem';				


	$dt = date('Y-m-d H:i:s');
	$to_time=strtotime($dt);
	
	//Setup stream (connect to Apple Push Server)
	$ctx = stream_context_create();
	//stream_context_set_option($ctx, 'ssl', 'passphrase', 'password_for_apns.pem_file');
	stream_context_set_option($ctx, 'ssl', 'local_cert', $apnsCert);
	
	$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
	stream_set_blocking ($fp, 0); //This allows fread() to return right away when there are no errors. But it can also miss errors during last seconds of sending, as there is a delay before error is returned. Workaround is to pause briefly AFTER sending last notification, and then do one more fread() to see if anything else is there.

	if (!$fp) 
	{
		//ERROR
		//echo "Failed to connect (stream_socket_client): $err $errstrn";
		return "false";

	} else 
	{

		$apple_expiry = time() + (90 * 24 * 60 * 60); //Keep push alive (waiting for delivery) for 90 days

		//Loop thru tokens from database
		
		$body = array();			
		
		
		$body['aps'] = array('alert' => $notiMessage, "sound"=> "Default",'msgType' => $notiContentType, 'msgKey' => $notiContentId);
		
		//echo $notiToken;
		$payload = json_encode($body);
		$msg = pack("C", 1) . pack("N", $notiId) . pack("N", $apple_expiry) . pack("n", 32) . pack('H*', str_replace(' ', '', $notiToken)) . pack("n", strlen($payload)) . $payload; //Enhanced Notification
		fwrite($fp, $msg); //SEND PUSH
		checkAppleErrorResponse($fp); //We can check if an error has been returned while we are sending, but we also need to check once more after we are done sending in case there was a delay with error response.
	

		//Workaround to check if there were any errors during the last seconds of sending.
		usleep(500000); //Pause for half a second. Note I tested this with up to a 5 minute pause, and the error message was still available to be retrieved

		checkAppleErrorResponse($fp);
		addNotificationLog($umId,$notiContentType,$notiToken,"iphone",$notiMessage,$dt);

		return "true";

		//mysql_close($con);
		fclose($fp);
	}
}
function buildDroidCurl($notiMessage,$notiContentType,$notiContentId,$notiToken,$umId)
{
	$url = 'https://android.googleapis.com/gcm/send';
	$serverApiKey = "AIzaSyBxrAGJlu9N46MQRXEdksz7tL5V5yWebg4";
	//AIzaSyBapbkjWoJK8LvX3ePMj49x8tLWK77XYYE 

	$headers = array(
	 'Content-Type:application/json',
	 'Authorization:key=' . $serverApiKey
	 );
	
	$data = array(
	 'registration_ids' => array($notiToken)
	 , 'data' => array(
	 'type' => 'New'
	 , 'title' => 'GCM'
	 , 'msg' => $notiMessage
	 , 'msgType' => $notiContentType
	 , 'msgKey' => $notiContentId							 
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
	 addNotificationLog($umId,$notiContentType,$notiToken,"android",$notiMessage,$dt);
}
function addNotificationLog($ntfUmId,$ntfType,$ntfUdid,$ntfdeviceType,$ntfmsgText,$ntfSendDate)
{
$employee_workhistorySQL="INSERT INTO notification (`ntfUmId`, `ntfType`, `ntfUdid`, `ntfdeviceType`, `ntfmsgText`, `ntfStatus`, `ntfSendDate`) VALUES ('$ntfUmId', '$ntfType', '$ntfUdid', '$ntfdeviceType', '$ntfmsgText', 'Yes', '$ntfSendDate' )";
$resemployee_workhistory=mysql_query($employee_workhistorySQL);
}
function checkAppleErrorResponse($fp) {
	//echo $fp;
	//print_r($fp);
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