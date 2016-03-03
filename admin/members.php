<?php
                require('../includes/app_config.php');
                require_once('inc/top.php');
                require_once('../classes/class.members.php');
		if(!isset($_SESSION['ad_userid']) && empty($_SESSION['ad_userid']))
		{
			header("location:index.php");
			exit;
		}
		
		//LOOP
		$memName = '';
$memMobile = '';
$memEmail = '';
$memDob = '';
$memGender = '';
$memStatus = '';
$memDeviceType = '';
$memUDID = '';
$memNewEventPush = '';
$memGeneralPush = '';
$memActivityReminderPush = '';
$memEventReminderPush = '';
$memPush = '';
$memCreatedDate = '';

		$error = '';
		
		
		$members = new members();
		
		if (isset($_REQUEST['Submit']))
		{
				
			 
			/*Assign to local varriable*/
            
                        $memName = $_REQUEST['memName'];
$memMobile = $_REQUEST['memMobile'];
$memEmail = $_REQUEST['memEmail'];
$memDob = $_REQUEST['memDob'];
$memGender = $_REQUEST['memGender'];
$memStatus = $_REQUEST['memStatus'];
$memDeviceType = $_REQUEST['memDeviceType'];
$memUDID = $_REQUEST['memUDID'];
$memNewEventPush = $_REQUEST['memNewEventPush'];
$memGeneralPush = $_REQUEST['memGeneralPush'];
$memActivityReminderPush = $_REQUEST['memActivityReminderPush'];
$memEventReminderPush = $_REQUEST['memEventReminderPush'];
$memPush = $_REQUEST['memPush'];
$memCreatedDate = $_REQUEST['memCreatedDate'];
	
			
                        /*Assing to Class*/
                        
			$members->memName    = $memName;
$members->memMobile    = $memMobile;
$members->memEmail    = $memEmail;
$members->memDob    = $memDob;
$members->memGender    = $memGender;
$members->memStatus    = $memStatus;
$members->memDeviceType    = $memDeviceType;
$members->memUDID    = $memUDID;
$members->memNewEventPush    = $memNewEventPush;
$members->memGeneralPush    = $memGeneralPush;
$members->memActivityReminderPush    = $memActivityReminderPush;
$members->memEventReminderPush    = $memEventReminderPush;
$members->memPush    = $memPush;
$members->memCreatedDate    = $memCreatedDate;
 
			
			if ($_REQUEST['Submit'] == 'Submit')
			{
                            if ($error=='') { 
                                $ret=$members->checkduplicate($members->memId,0);
                                if($ret)
                                {
                                    $error = 'members already exists';
                                }
                            }
                            if ($error == '')
                            {					
                                    //Add New Page		   
                                    $members->insert();					
                                    //redirect page memberslist.php	
                                    header("location:memberslist.php?msg=" . base64_encode('Record has been successfully inserted.') . "&msgstyle=" . base64_encode("success_msg"));
                                    exit;
                            }
			}
			else if ($_REQUEST['Submit'] == 'Update')
			{
				$memId = base64_decode($_REQUEST['cid']);
				$members->memId = $memId; 				
				if ($error == '')
                            {
				//Check Page Name
                                $ret = $members->checkduplicate($members->memId,$memId);
                                if ($ret)
                                {
                                        $error = 'members already exists';
                                }
                            }
			if ($error == '')
                            {
                                //Update Page Data
                                $members->update($memId); 	
                                header("location:memberslist.php?limitstart=" . $_REQUEST['limitstart'] . "&pageno=" . $_REQUEST['pageno'] . "&msg=" . base64_encode('Record has been successfully updated.') . "&msgstyle=" . base64_encode("success_msg")); 
                            }
			}
		}
		
		if (isset($_REQUEST['cid']) && trim($_REQUEST['cid']) != '' && $_REQUEST['script']=='view')
		{

                    //Get members Data
$members->select(base64_decode($_REQUEST['cid']));
echo $sql="SELECT evtName, memName, memGender, memDob, memEmail, memMobile, `eadId`, `eadEvtIdi`, `eadMemId` FROM `eventattendees` ,`members` ,`events` WHERE evtId=eadEvtIdi AND memId='". base64_decode($_REQUEST['cid'])."'";
			/*Set Local Varriables*/
			$memName    = $members->memName;
$memMobile    = $members->memMobile;
$memEmail    = $members->memEmail;
$memDob    = $members->memDob;
$memGender    = $members->memGender;
$memStatus    = $members->memStatus;
$memDeviceType    = $members->memDeviceType;
$memUDID    = $members->memUDID;
$memNewEventPush    = $members->memNewEventPush;
$memGeneralPush    = $members->memGeneralPush;
$memActivityReminderPush    = $members->memActivityReminderPush;
$memEventReminderPush    = $members->memEventReminderPush;
$memPush    = $members->memPush;
$memCreatedDate    = $members->memCreatedDate;

			
			$mode = 'Update';
		}
		else
		{
		
			$mode = 'Submit';
		}
		
		?>

        <script type="text/javascript">
            jQuerX(document).ready(function () {
        jQuerX("#frm_offer").validate();
        });
        </script><div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="widget box">
                                    <div class="widget-header">
                            <?php if (isset($_REQUEST['cid']))
                            { ?>
                                <h4><i class="icon-reorder"></i><?= $pmsg ?>Edit members</h4>
                            <?php }
                            else
                            { ?>
                                <h4><i class="icon-reorder"></i><?= $pmsg ?>Add members</h4>
                            <?php } ?>
                            <div class="toolbar no-padding">
                                <h4>
                                    <i class="icon-reorder"></i><a href="memberslist.php">members List</a>
                                </h4>
                                <div class="btn-group">
                                    <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                                </div>
                            </div>
                        </div>
                        
                <div class="widget-content">
                    <form class="form-horizontal row-border" enctype="multipart/form-data" method="post" id="frm_offer" name="frm_offer" onsubmit="return checkmembersDetails();
                            return false;" >

                        <!---/******************  Start Messages  *******************/------>
                        <?php
                        $msgstyle = "error_msg";
                        if (isset($_REQUEST['msg']) && $_REQUEST['msg'] != '')
                        {
                            $error = base64_decode($_REQUEST['msg']);
                            $msgstyle = base64_decode($_REQUEST['msgstyle']);
                        }
                        if ($error != "" || $img_error = '')
                        {
                            ?>
                            <table style="margin-left: 24px; margin-bottom: 25px;">
                                <tr><td  align="left"  class="<?= $msgstyle ?>"><?= $error . $img_error; ?></td>
                            </tr>
                            <tr><td></td></tr>
                            </table>
                            <?php } ?>	
                        <!---/******************  End Messages  *******************/------>
                        <div class="form-group">
                            <label class="col-md-2 control-label"></label>
                            <div align="right" class="col-md-10"><span class="star-color">*</span> Denotes required field </div>
                        </div>
						<div class="form-group">
                            <label class="col-md-2 control-label">memName: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input value="<?=$memName?>"  maxlength="100"   name="memName" class="form-control required" type="text" id="memName"  /></div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">memMobile: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input value="<?=$memMobile?>"  maxlength="20"   name="memMobile" class="form-control required" type="text" id="memMobile"  /></div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">memEmail: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input value="<?=$memEmail?>"  maxlength="100"   name="memEmail" class="form-control required" type="text" id="memEmail"  /></div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">memDob: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input value="<?=$memDob?>" name="memDob" class="form-control" type="text" id="memDob"  /></div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">memGender: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input type="checkbox" name="memGender" id="memGender" value='Male' <? if (isset($memGender) && $memGender == 'Male'){ echo "checked"; } ?>></div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">memStatus: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input type="checkbox" name="memStatus" id="memStatus" value='Active' <? if (isset($memStatus) && $memStatus == 'Active'){ echo "checked"; } ?>></div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">memDeviceType: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input type="checkbox" name="memDeviceType" id="memDeviceType" value='iphone' <? if (isset($memDeviceType) && $memDeviceType == 'iphone'){ echo "checked"; } ?>></div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">memUDID: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input value="<?=$memUDID?>"  maxlength="255"   name="memUDID" class="form-control required" type="text" id="memUDID"  /></div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">memNewEventPush: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input type="checkbox" name="memNewEventPush" id="memNewEventPush" value='Yes' <? if (isset($memNewEventPush) && $memNewEventPush == 'Yes'){ echo "checked"; } ?>></div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">memGeneralPush: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input type="checkbox" name="memGeneralPush" id="memGeneralPush" value='Yes' <? if (isset($memGeneralPush) && $memGeneralPush == 'Yes'){ echo "checked"; } ?>></div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">memActivityReminderPush: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input type="checkbox" name="memActivityReminderPush" id="memActivityReminderPush" value='Yes' <? if (isset($memActivityReminderPush) && $memActivityReminderPush == 'Yes'){ echo "checked"; } ?>></div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">memEventReminderPush: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input type="checkbox" name="memEventReminderPush" id="memEventReminderPush" value='Yes' <? if (isset($memEventReminderPush) && $memEventReminderPush == 'Yes'){ echo "checked"; } ?>></div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">memPush: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input type="checkbox" name="memPush" id="memPush" value='Yes' <? if (isset($memPush) && $memPush == 'Yes'){ echo "checked"; } ?>></div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">memCreatedDate: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input value="<?=$memCreatedDate?>" maxlength="" name="memCreatedDate" class="form-control" type="text" id="memCreatedDate"  /></div>
				 
				</div>
			<div class="btn-toolbar">
                            <input type="submit" name="Submit" value="<?= $mode ?>" class="btn btn-warning" data-loading-text="button"  />
                            <input type="button" name="Cancel" value="Cancel" onClick="window.location.href = 'memberslist.php'" class="btn" data-loading-text="button">
                    </div>
		</form>
		 </div>
        </div>
        </div>
    </div>
</div>