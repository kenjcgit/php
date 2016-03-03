<?php 
date_default_timezone_set('Australia/Perth');
try
{
require_once('includes/app_config.php');

//For android
$gcmurl = 'https://android.googleapis.com/gcm/send';
$serverApiKey = "AIzaSyDCkhjPVcb4N4l6zqD82WqSfpTGcolv00E";
$headers = array(
 'Content-Type:application/json',
 'Authorization:key=' . $serverApiKey
 );

//For iphone
$apnsHost = 'gateway.sandbox.push.apple.com';//for live change the url :- gateway.push.apple.com 
$apnsPort = 2195;
$apnsCert = 'apns-devtesting.pem';	
			
$streamContext = stream_context_create();

stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
$apns = @stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);

$dt = date('Y-m-d H:i:s');
$to_time=strtotime($dt);	
	

	//$deviceToken = '311ad180fef55d6551dcde2d3c5d16ed7df45779a82480415e6dc902f0983d7b';
	$userDeviceType=$_REQUEST["userDeviceType"];	
	$devicetoken=$_REQUEST["devicetoken"];
	
	$notiMessage="Message from Ken JC android Manual Push";
	$notiContentType= "FR";
	$notiContentId = "77";
	
	//notiMessage=Michle commented on your photo&notiContentType=CP&notiContentId=2888&badgeCount=5
	extract($_REQUEST);
	$devicetoken="APA91bEPMo0dztwgdSnYdFsp0gdrZHIYH4SFzJfX-ev6dhZ-Ph8vuKbc1SbPDXvlahh299fzy09asXJ-2R73Y1vFX-yeNWpUWLTXNeu5ZZ8QjwIcVkXQasE";//"a09e270c6f03f37dd9454a63b57ae9d3d8ec6a2f4f78f1fa0da1e809a5b3d54d";
	$userDeviceType="Android";
	
	if($userDeviceType=='iPhone')
	{
		if($apns)
		{
			$body = array();
			//$body['aps'] = array('alert' => $msg,'title' => $title,'url' => $url,'intId'=>$intId, "sound"=> "Default");
			$body['aps'] = array('alert' => $notiMessage, "sound"=> "Default",'badge' => $badgeCount,'msgType' => $notiContentType, 'msgKey' => $notiContentId  );
			$payload = json_encode($body);
			echo $apnsMessage = chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $devicetoken)) . pack("n",strlen($payload)) . $payload;
			@fwrite($apns, $apnsMessage);
			#@mysql_query("UPDATE  tblformdata SET scrubReqStatus='Yes', pushMessageSentDate=NOW(),nextScrubSentDate=DATE_ADD(NOW(), INTERVAL 1 DAY) WHERE intId=".$exec['intId']);
		}
		else
		{	
			echo "Connection Failed";
			echo $errorString;
			echo $error;
		}
		@fclose($apns);
	}
	else if($userDeviceType=='Android' )
	{	
		
			$data = array(
						 'registration_ids' => array($devicetoken)
						 , 'data' => array(
						 'type' => 'New'
						 , 'title' => 'GCM'
						 , 'msg' => $notiMessage
						 , 'notiId' => 1							 
						 )
						 );
			
			
			
		print_r($data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $gcmurl);
		if ($headers)
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		$response = curl_exec($ch);
		curl_close($ch);
		echo "<hr>";
		print ($response);
		echo $response;
		#@mysql_query("UPDATE  tblformdata SET scrubReqStatus='Yes', pushMessageSentDate=NOW(),nextScrubSentDate=DATE_ADD(NOW(), INTERVAL 1 DAY) WHERE intId=".$exec['intId']);	
		
		
	}	
	
}
catch (Exception $e)
{
    fatalError($e);
}
?>
