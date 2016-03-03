<?php 
try
{


//For android
$url = 'https://android.googleapis.com/gcm/send';
$serverApiKey = "AIzaSyDCkhjPVcb4N4l6zqD82WqSfpTGcolv00E";


$headers = array(
 'Content-Type:application/json',
 'Authorization:key=' . $serverApiKey
 );




$timezone = new DateTimeZone('America/New_York');
$date = new DateTime();
$date->setTimezone($timezone);
$time =  $date->format('H:i:s');

$deliverbefore = gmdate('Y-m-d\TH:i:s\Z', strtotime('+35 minutes'));


//For iphone
$apnsHost = 'gateway.push.apple.com';//for live change the url :- gateway.push.apple.com 
$apnsPort = 2195;
$apnsCert = 'apns-dev.pem';	

//Setup stream (connect to Apple Push Server)
$ctx = stream_context_create();
//stream_context_set_option($ctx, 'ssl', 'passphrase', 'password_for_apns.pem_file');
stream_context_set_option($ctx, 'ssl', 'local_cert', $apnsCert);

$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
stream_set_blocking ($fp, 0); //This allows fread() to return right away when there are no errors. But it can also miss errors during last seconds of sending, as there is a delay before error is returned. Workaround is to pause briefly AFTER sending last notification, and then do one more fread() to see if anything else is there.


if($fp)
{	
	
		//Check notification set or not by user
				$notiMessage="Push from PHP Certificate verification system for kenj ios"; 
				$notiToken = "e599c407070ef71fb3ad1a62a5f126c73dd477a90c80158bfdbc5bd9b0e60ba1";
				$title ="test Push";	
		
			
				
				if($_REQUEST['DeviceType']=='iPhone')
				{
					$apple_expiry = time() + (90 * 24 * 60 * 60); //Keep push alive (waiting for delivery) for 90 days

			//Loop thru tokens from database
			
					$body = array();			
					
					
					$body['aps'] = array('alert' => $notiMessage, "sound"=> "Default",'badge' => 1,'msgType' => 1, 'msgKey' => 1, 'notiId' => 1  );
					
					echo $notiToken;
					echo $payload = json_encode($body);
					$msg = pack("C", 1) . pack("N", $notiId) . pack("N", $apple_expiry) . pack("n", 32) . pack('H*', str_replace(' ', '', $notiToken)) . pack("n", strlen($payload)) . $payload; //Enhanced Notification
					fwrite($fp, $msg); //SEND PUSH
					checkAppleErrorResponse($fp); //We can check if an error has been returned while we are sending, but we also need to check once more after we are done sending in case there was a delay with error response.
					
					

					//Workaround to check if there were any errors during the last seconds of sending.
					usleep(500000); //Pause for half a second. Note I tested this with up to a 5 minute pause, and the error message was still available to be retrieved

					checkAppleErrorResponse($fp);

			echo 'DONE!';

			//mysql_close($con);
			fclose($fp);
					
				}
				else if($_REQUEST['DeviceType']=='Android' )
				{
					 
					 $data = array(
						 'registration_ids' => array($deviceToken)
						 , 'data' => array(
						 'type' => 'New'
						 , 'title' => $title
						 , 'msg' => $msg
						 
						 )
						 );
						 print_r($data);
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
					
				}		
	}

else
{	
	echo "Connection Failed";
	echo $errorString;
	echo $error;
}

}
catch (Exception $e)
{
    fatalError($e);
}
?>
