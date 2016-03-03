<?php
require('../includes/app_config.php');
require_once('../classes/database.class.php');
	$dclass = new database();
if(isset($_REQUEST['act']) && $_REQUEST['act']=='logout')
{
	
	unset($_SESSION['ad_userid']);
	unset($_SESSION['ad_email']);
	unset($_SESSION['ad_username']);
       
	unset($_SESSION['type']);
	$msg="Logged out successfully##Success##";
	$gnrl->redirectTo("index.php?msg=".base64_encode($msg));
}

    @session_start();
if(isset($_REQUEST['Submit']))
{
	//print_r($_REQUEST);die;
                        //setcookie("email", $_POST['ad_username'], mktime()+(86400*30), "/");
                        //setcookie("lastsave", time(), mktime()+(86400*30), "/");
        extract($_REQUEST);
	if($ad_username=='')
	{
		$error="Please enter username##Fail##";
	}
	if($error=='')
	{
		if($ad_password=='')
		{
			$error="Please enter password##Fail##";
		}
	}
	if($error=='')
	{
		if($ad_password=='')
		{
			$error="Please enter password##Fail##";
		}
	}

       
        
	if($error=='')
	{
               
                    
//                  $sqlChk="SELECT * FROM `adminusers` WHERE  `admName` = '$ad_username' AND  `admPassword` = '".base64_encode($ad_password)."' AND admStatus='Active' ";
//	#echo	$sqlChk = $dclass->query("SELECT * FROM `tbladminusers` WHERE `name` = '$ad_username' AND `password` = '".$ad_password."' AND status='Active'");		
//		 $ews=mysql_query($sqlChk); 
//     //   echo $aa= mysql_num_rows($ews);       
//            
//                if(mysql_num_rows($ews)>0)
//		{
//                   
//                        @session_start();
//                         if($error=='')
//                         {
//
//                           $original_captcha = $_SESSION["captcha"]; // session 'captcha' has been alreadey created in captcha.php file, if you want to rename the session open the captcha.php file and find and change the name of the session
//                           $user_captcha = $_POST["captcha"];
//                           if($user_captcha != $original_captcha)
//                           {
//                               $msg="Captcha not match##Fail##";
//                               $gnrl->redirectTo("index.php?u=".base64_encode($ad_username)."&p=".base64_encode($ad_password)."&msg=".base64_encode($msg));
//                           }
//                            else
//                            {
//
//                           $rowChk = mysql_fetch_array($ews);
//                           $_SESSION['ad_userid']=$rowChk['usrId'];
//                           $_SESSION['ad_username']=$rowChk['admName'];
//                           $_SESSION['ad_email']=$rowChk['usrEmail'];
//                           $_SESSION['type']="User";
//                           $_SESSION['usrRollType']=$rowChk['usrRollType'];
//                           $gnrl->redirectTo("home.php");
//                          
//                           }
//                        }
//                        else
//                        {
//			$msg = "Invalid Username or Password, Please try again.##Fail##";
//			$gnrl->redirectTo("index.php?msg=".base64_encode($msg));
//                        }
//                        
//
//		}		
//                else {
                $sqlChk="SELECT * FROM `adminusers` WHERE BINARY `admName` = '$ad_username' AND BINARY `admPassword` = '".base64_encode($ad_password)."' AND admStatus='Active'";
	#echo	$sqlChk = $dclass->query("SELECT * FROM `tbladminusers` WHERE `name` = '$ad_username' AND `password` = '".$ad_password."' AND status='Active'");		
		$ews=mysql_query($sqlChk); 
     //   echo $aa= mysql_num_rows($ews);       
                
                if(mysql_num_rows($ews)>0)
		{
                    @session_start();
                    if($error=='')
                    {

                        $original_captcha = $_SESSION["captcha"]; // session 'captcha' has been alreadey created in captcha.php file, if you want to rename the session open the captcha.php file and find and change the name of the session
                        $user_captcha = $_POST["captcha"];
                        if($user_captcha != $original_captcha)
                        {
                            $msg="Captcha not match##Fail##";
                            $gnrl->redirectTo("index.php?u=".base64_encode($ad_username)."&p=".base64_encode($ad_password)."&msg=".base64_encode($msg));
                        }
                        else
                        {

                           $rowChk = mysql_fetch_array($ews);
                           $_SESSION['ad_userid']=$rowChk['admId'];
                           $_SESSION['ad_username']=$rowChk['admName'];
                           $_SESSION['ad_email']=$rowChk['admEmail'];
                           $_SESSION['type']="Admin";
                           $gnrl->redirectTo("home.php");
                        }
                    }
                }
                else
                {
                    $msg = "Invalid Username or Password, Please try again.##Fail##";
                    $gnrl->redirectTo("index.php?msg=".base64_encode($msg));
                }
        }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title>Admin | Kenjc</title>

<!--=== CSS ===-->

<!-- Bootstrap -->
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<!-- Theme -->
<link href="assets/css/main.css" rel="stylesheet" type="text/css" />
<link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
<link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
<link href="assets/css/icons.css" rel="stylesheet" type="text/css" />

<!-- Login -->
<link href="assets/css/login.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="assets/css/fontawesome/font-awesome.min.css">
<!--[if IE 7]>
<link rel="stylesheet" href="assets/css/fontawesome/font-awesome-ie7.min.css">
<![endif]-->

<!--[if IE 8]>
<link href="assets/css/ie8.css" rel="stylesheet" type="text/css" />
<![endif]-->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>

<!--=== JavaScript ===-->

<script type="text/javascript" src="assets/js/libs/jquery-1.10.2.min.js"></script>

<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/js/libs/lodash.compat.min.js"></script>



<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="assets/js/libs/html5shiv.js"></script>
<![endif]-->

<!-- Beautiful Checkboxes -->
<script type="text/javascript" src="plugins/uniform/jquery.uniform.min.js"></script>

<!-- Form Validation -->
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>

<!-- Slim Progress Bars -->
<script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>

<!-- App -->
<script type="text/javascript" src="assets/js/login.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#change_captcha").click(function(){
		$("#as_captcha").attr("src", "captcha.php?"+(new Date()).getTime()); // browser will save the captcha image in its cache so add '?some_unique_char' to set new URL for each click
                
                $("#captcha").val('') ;
	});
        $("#frgtPassLink").click(function(){
            $('#divErrorGlobal').hide();
        });
});


$(document).ready(function(){
	"use strict";

	Login.init(); // Init login JavaScript
});
</script>

</head>
<body class="login">
<!-- Logo -->
<div class="logo">
    <img src="./images/logo.png" alt="Kenjc" />
<!--	<strong>ME</strong>LON-->
   
</div>
<!-- /Logo -->

<!-- Login Box -->
<div class="box">
<div class="content">






<!-- Login Formular -->			
<!--<form class="form-vertical login-form" action="" method="post">-->
<form name="formLogin" id="formLogin" class="form-vertical login-form" method="post" action="index.php">
<!-- Title -->
<h3 class="form-title">Admin</h3>


<!--<div class="alert fade in alert-danger" style="display: none;">
<i class="icon-remove close" data-dismiss="alert"></i>
Enter any username and password.
</div>-->

<!-- Input Fields -->
<div class="form-group">
<!--<label for="username">Username:</label>-->
<div class="input-icon">
<i class="icon-user"></i>
<input type="text" name="ad_username" id="ad_username" class="form-control" placeholder="Username" autofocus data-rule-required="true" required="" data-msg-required="Please enter your username." value="<?=base64_decode($_REQUEST['u'])?>"  />

</div>
</div>
<div class="form-group">
<!--<label for="password">Password:</label>-->
<div class="input-icon">
<i class="icon-lock"></i>
<input type="password" name="ad_password" id="ad_password" class="form-control" placeholder="Password" data-rule-required="true" required="" data-msg-required="Please enter your password."  value="<?=base64_decode($_REQUEST['p'])?>"/>
</div>
</div>

<p class="contact"><label for="password">Enter Captcha Code</label>    <img src="captcha.php" id="as_captcha" alt="Captcha" /></p> 
              
<input type="text" value="<?=$_REQUEST['captcha'];?>" name="captcha" id="captcha" class="form-control"  data-rule-required="true"  required="" type="text"  />
<span class="a1" id="change_captcha" style="cursor: pointer;"><p>Can't read?</p> <p style="margin-left:70px;margin-top: -28px;color: blue" onmouseover="this.style.color='green'" onmouseout="this.style.color='blue'">Try another one</p></span>
<!-- /Input Fields -->

<!-- Form Actions -->
<div class="form-actions">
<button type="submit" style="float:right" class="submit btn btn-warning pull-right"  name="Submit" id="Submit">
    Sign In <i class="icon-angle-right"></i>
</button>
<!--<input type="submit" name="Submit" id="Submit" value="Sign In >" class="submit btn btn-warning pull-right" style="width:65px; border-radius:7px;" />-->
</div>
</form>

<!--<form class="form-vertical register-form" action="index.php" method="post" style="display: none;">
Title 
<h3 class="form-title">Sign Up for Free</h3>

Input Fields 
<div class="form-group">
<div class="input-icon">
<i class="icon-user"></i>
<input type="text" name="ad_username" id="ad_username" class="form-control" placeholder="Username" autofocus data-rule-required="true" />
</div>
</div>
<div class="form-group">
<div class="input-icon">
<i class="icon-lock"></i>
<input type="password" name="ad_password" id="ad_password" class="form-control" placeholder="Password" id="register_password" data-rule-required="true" />
</div>
</div>
<div class="form-group">
<div class="input-icon">
<i class="icon-ok"></i>
<input type="password" name="password_confirm" class="form-control" placeholder="Confirm Password" data-rule-required="true" data-rule-equalTo="#register_password" />
</div>
</div>
<div class="form-group">
<div class="input-icon">
<i class="icon-envelope"></i>
<input type="text" name="Email" class="form-control" placeholder="Email address" data-rule-required="true" data-rule-email="true" />
</div>
</div>
<div class="form-group spacing-top">
<label class="checkbox"><input type="checkbox" class="uniform" name="remember" data-rule-required="true" data-msg-required="Please accept ToS first."> I agree to the <a href="javascript:void(0);">Terms of Service</a></label>
<label for="remember" class="has-error help-block" generated="true" style="display:none;"></label>
</div>
/Input Fields 

Form Actions 
<div class="form-actions">
<button type="button" class="back btn btn-default pull-left">
<i class="icon-angle-left"></i> Back</i>
</button>
<button type="submit" class="submit btn btn-warning pull-right">
Sign Up <i class="icon-angle-right"></i>
</button>
</div>
</form>-->

<!-- /Register Formular -->
</div> <!-- /.content -->
<!-- Forgot Password Form -->
<div class="inner-box" >
<div class="content">
<!-- Close Button -->
<i class="icon-remove close hide-default"></i>

<!-- Link as Toggle Button -->
<a href="#" class="forgot-password-link" id="frgtPassLink">Forgot Password?</a>

<!-- Forgot Password Formular -->
<form class="form-vertical forgot-password-form hide-default" action="forgetpassword.php"  method="post">
<!-- Input Fields -->
<div class="form-group">
<!--<label for="email">Email:</label>-->
<div class="input-icon">
<i class="icon-envelope"></i>
<input type="text" name="email" id="email" class="form-control" placeholder="Enter email address" data-rule-required="true" data-rule-email="true" data-msg-required="Please enter your email." />
<label for="ad_password" style="display:none;color:#B94A48;" id="erf" generated="true">Please enter your email.</label>
<label for="ad_password" style="display:none;color:#B94A48;" id="erf1" generated="true">Invalid your email.</label>
</div>
</div>
<!-- /Input Fields -->

<button type="submit" onclick="return emailcheck();" class="submit btn btn-default btn-block" onclick="return emailcheck();">
Reset your Password
</button>
</form>
<!-- /Forgot Password Formular -->

<!-- Shows up if reset-button was clicked -->
<div class="forgot-password-done hide-default">
<i class="icon-ok success-icon"></i> <!-- Error-Alternative: <i class="icon-remove danger-icon"></i> -->
<span>Great. We have sent you an email.</span>
</div>
</div> <!-- /.content -->
</div>
<!-- /Forgot Password Form -->
</div>
<style>
   .error_msg{
   color:red;}
   .success_msg{
   color:green;}
</style>			
					<?php
					 if(isset($_REQUEST['msg']) && $_REQUEST['msg']!='')
					 {
						$error=base64_decode($_REQUEST['msg']); 
						$check_msg=strpos($error,"##Success##");
						
						if($check_msg>0)
						{
							$msg_class="success_msg";
							$error=substr($error,0,$check_msg);
						}
						else 
						{
							$check_msg_fail=strpos($error,"##Fail##");
				
							if($check_msg_fail>0)
							{
								$msg_class="error_msg";
								$error=substr($error,0,$check_msg_fail);
							}
						}
					 }
					 if($error!="")
					 {
						$check_msg=strpos($error,"##Success##");
	
						if($check_msg>0)
						{
							$msg_class="success_msg";
							$error=substr($error,0,$check_msg);
						}
						else 
						{
							$check_msg_fail=strpos($error,"##Fail##");
				
							if($check_msg_fail>0)
							{
								$msg_class="error_msg";
								$error=substr($error,0,$check_msg_fail);
							}
						}	
					?>
<!--                                        <table>
					<tr>
						<td >  
                                                    <div style="margin-left: 574px;color: #FF0000;margin-top: -84px;" class="<?=$msg_class?>" id="divErrorGlobal"><?=$error?></div>
						</td>
					</tr>
                                        </table>-->
                                        <div class="form-group">
                                            <?php 
                                            if($error=='Invalid Username or Password, Please try again.'){ ?>
                                                <p style="text-align: center"  class="<?=$msg_class?>" id="divErrorGlobal"><?=$error?></p>
                                           <?php  } else { 
                                            ?>
                                            <p style="text-align: center"  class="<?=$msg_class?>" id="divErrorGlobal"><?=$error?></p>
                                            <?php } ?>
                                        </div>
					<?php
					}
					?>
   	<?php /*?><div class="login-row">
  	<div class="logincaption">Restaurant&nbsp;:</div>
  	<div class="login-formbg"><div class="tip" style="padding-top:12px; padding-left:15px; background:none; border:none;">
  			<select name="rnm_id" id="rnm_id" style="background:none; border:none; width:180px;">
  			  <option value="0">----- Select -----</option>
			  <option value="admin">Admin</option>
			  <?php
			  $sqlGetRest=$dclass->select("Rnm_id,Rnm_Title","t_rest_name_mast"," AND Rnm_status='Active'");
			  for($i=0;$i<count($sqlGetRest);$i++)
			  {  
			  ?>
  			  <option value="<?=$sqlGetRest[$i]['Rnm_id']?>"><?=$sqlGetRest[$i]['Rnm_Title']?></option>
			  <?php
			  }
			  ?>
  			</select>
          </div></div>
   </div><?php */?>
     

<!-- 	 <div class="login-forgetpass" style="width:187px"><a href="forgotpassword.php">[Forgot Password?]</a>
<!--             <input type="checkbox" name="rme" value="" style="vertical-align:bottom"><span style="color:#1F92FF">Remember me</span>-->
<!--         </div> -->
	</body>
</html>
