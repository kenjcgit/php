 <?php    require('../includes/app_config.php');  $userEmail=$_REQUEST['email'];                         //echo "$userEmail"; die;	  #echo "SELECT * FROM `tbluser` WHERE `userEmail` = '$userEmail' ";        $Select_Record ="SELECT * FROM `adminusers` WHERE `admEmail` = '$userEmail' ";		$sql = $dclass->query($Select_Record);		$cntrow = mysql_num_rows($sql);		if($cntrow > 0)		{ 		$rows = mysql_fetch_array($sql);		$fname=$rows['admName'];		$password= substr(str_shuffle(str_repeat("a123bs456sd8695efgh2684jik",2)),5,10); 				$subject='Forget Password Sent From Umergency .';						$message ="<html>					<head>					<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />					<title>Mail</title>					</head>										<body>					<table width='350' border='0'>					  <tr>						<td colspan='3'><strong></strong></td>					  </tr>					  <tr>						<td colspan='3' align='right'>.</td>					  </tr>					   					 <tr>						<td width='72'><strong>Username : </strong></td>						<td width='132'>".$fname."</td>						<td width='68'>&nbsp;</td>					  </tr>					  <tr>						<td width='72'><strong>Email : </strong></td>						<td width='132'>".$userEmail."</td>						<td width='68'>&nbsp;</td>					  </tr>					  <tr>						<td><strong>Password :</strong> </td>						<td>".$password."</td>						<td>&nbsp;</td>					  </tr>					  <tr>						<td>&nbsp;</td>						<td>&nbsp;</td>						<td>&nbsp;</td>					  </tr>					  <tr>						<td colspan='1' align='left'><strong>Regards,</strong></td>					  </tr>					  <tr>						<td colspan='3' align='left'><strong>UMERGENCY </strong></td>					  </tr>					</table>										</body>					</html>";                                           /*      $sqlGetAdminEmail="SELECT settings_value FROM tblsettings WHERE settings_field='sender_email'";				$resGetAdminEmail=mysql_query($sqlGetAdminEmail);										$rowGetAdminEmail=mysql_fetch_array($resGetAdminEmail);				$email_from=$rowGetAdminEmail['settings_value'];                            */                                    $email_from = 'testlast11@gmail.com'; 					//echo $email_from."  ".$userEmail."  ".$subject."  ".$message;exit;				//$gnrl->email($email_from, $email_to, "", "", $_REQUEST['subject'], $template_contents, "html");				$headers  = 'MIME-Version: 1.0' . "\r\n";				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";				$headers .= "From: UMERGENCY   - ".$email_from . "\r\n" .				'Reply-To: ' .$email_from. "\r\n";				//                       echo '<br />header:-'.$headers;//                        echo '<br />subject:-'.$subject;//                        echo '<br />message:-'.$message;//                        echo '<br />emailto:-'.$emailto;                                                       mail($userEmail,$subject,$message,$headers);                                                if(mail)                                {                                                                        $update = "UPDATE `adminusers` SET `admPassword`='".  base64_encode($password)."' WHERE `admEmail` = '$userEmail' ";                                    @$dclass->query($update);                                }                                                                                	        #mail($to,$subject,$message,$headers);//			echo $message; die;                       # exit;			#echo 'redirect';			$gnrl->redirectTo("index.php?msg=".base64_encode("Password is sent from Umergency . Please Check your email!##Success##"));				        }		else{                    $gnrl->redirectTo("index.php?msg=".base64_encode('your email is not valid!##Fail##'));			echo 'error';		}								?>