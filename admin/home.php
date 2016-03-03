<?php
require_once('../includes/app_config.php');
if(!isset($_SESSION['ad_userid']) && empty($_SESSION['ad_userid']))
		{
			header("location:index.php");
			exit;
		}
?>

<?php 
require_once('inc/top.php');
require_once('inc/contenttop.php');
require_once('inc/left.php');
//require_once($gnrl->includeleftfile($_SESSION['Sus_User_type']));
?>
<div id="alertMessage" class="error"></div> 
<div id="content">
	<div class="inner">
		<div class="onecolumn" >

			<div class=" clear">
<!--                          
                            <table>
                                <tr>
                                    <td>
                                    <marquee behavior="scroll" direction="up">Events<br>
                                   Event Name : <?=$evtName?></marquee>
                                    </td>
                                </tr>    
                            </table>-->

			<div class=" clear">                          

                        </div>
                             
                    <div class="content" style="float: left">
			<?php
                                $username=$gnrl->getName('admId',$_SESSION['ad_userid'],'adminusers','admName');
			?>
			<!--<span style="font-size:18px">Welcome <?=strtolower($username);?> <?php //=$gnrl->userType($_SESSION['Sus_User_type'])?></span>
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tbody>-->
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
					<tr>
					  <td>&nbsp;</td>
					</tr>
					<tr>
						<td>
							<div class="<?=$msg_class?>" style="color:green;" id="divErrorGlobal"><?=$error?></div>
						</td>
					</tr>
					<?php
					}
					?>
					</tbody>
				</table>
                                
                                 
                       
                        </div>
                    
		</div>
<?php require_once('inc/bottom.php');?>
