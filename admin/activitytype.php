<?php
                require('../includes/app_config.php');
                require_once('inc/top.php');
                require_once('../classes/class.activitytype.php');
		if(!isset($_SESSION['ad_userid']) && empty($_SESSION['ad_userid']))
		{
			header("location:index.php");
			exit;
		}
		
		//LOOP
$actTypeName = '';
$actInfo = '';
$actStatus = '';

$error = '';
		
		
		$activitytype = new activitytype();
		
		if (isset($_REQUEST['Submit']))
		{
			if($_REQUEST['actStatus']=='')
			{
				$_REQUEST['actStatus']='Inactive';
			}
          			 
			/*Assign to local varriable*/
            
 $actTypeName = $_REQUEST['actTypeName'];
$actInfo = strip_tags($_REQUEST['actInfo']);
$actStatus = $_REQUEST['actStatus'];
	
			
                        /*Assing to Class*/
                        
$activitytype->actTypeName    = $actTypeName;
$activitytype->actInfo    = $actInfo;
$activitytype->actStatus    = $actStatus;
 
			
			if ($_REQUEST['Submit'] == 'Submit')
			{
                            if ($error=='') { 
                                $ret=$activitytype->checkduplicate($_REQUEST['actTypeName'],0);
                                if($ret)
                                {
                                    $error = 'This activity type is already exists';
                                }
                            }
                            if ($error == '')
                            {					
                                    //Add New Page		   
                                    $activitytype->insert();					
                                    //redirect page activitytypelist.php	
                                    header("location:activitytypelist.php?msg=" . base64_encode('Record has been successfully inserted.') . "&msgstyle=" . base64_encode("success_msg"));
                                    exit;
                            }
			}
			else if ($_REQUEST['Submit'] == 'Update')
			{
				$actTypeId = base64_decode($_REQUEST['cid']);
				$activitytype->actTypeId = $actTypeId; 				
				if ($error == '')
                            {
				//Check Page Name
                                $ret = $activitytype->checkduplicate($_REQUEST['actTypeName'],$actTypeId);
                                if ($ret)
                                {
                                        $error = 'This activity type is already exists';
                                }
                            }
			if ($error == '')
                            {
                                //Update Page Data
                                $activitytype->update($actTypeId); 	
                                header("location:activitytypelist.php?limitstart=" . $_REQUEST['limitstart'] . "&pageno=" . $_REQUEST['pageno'] . "&msg=" . base64_encode('Record has been successfully updated.') . "&msgstyle=" . base64_encode("success_msg")); 
                            }
			}
		}
		
		if (isset($_REQUEST['cid']) && trim($_REQUEST['cid']) != '')
		{
			//Get activitytype Data
			$activitytype->select(base64_decode($_REQUEST['cid']));
			/*Set Local Varriables*/
$actTypeName    = $activitytype->actTypeName;
$actInfo    = $activitytype->actInfo;
$actStatus    = $activitytype->actStatus;

			
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
                                <h4><i class="icon-reorder"></i><?= $pmsg ?>Edit Activity Type</h4>
                            <?php }
                            else
                            { ?>
                                <h4><i class="icon-reorder"></i><?= $pmsg ?>Add Activity Type</h4>
                            <?php } ?>
                            <div class="toolbar no-padding">
                                <h4>
                                    <i class="icon-reorder"></i><a href="activitytypelist.php">Activity Type List</a>
                                </h4>
                                <div class="btn-group">
                                    <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                                </div>
                            </div>
                        </div>
                        
                <div class="widget-content">
                    <form class="form-horizontal row-border" enctype="multipart/form-data" method="post" id="frm_offer" name="frm_offer" onsubmit="return checkactivitytypeDetails();
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
                            <label class="col-md-2 control-label">Activity Type: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-5"><input value="<?=$actTypeName?>"  maxlength="100"   name="actTypeName" class="form-control required" type="text" id="actTypeName"  />
                                  <span>(Maximum characters allowed 100)</span>
                                  </div>
                                  <br>
                                  
				</div>
                        
						<div class="form-group">
                            <label class="col-md-2 control-label">Description: &nbsp;<span class="star-color"></span></label>
				  <div class="col-md-10"><textarea name="actInfo" id="actInfo" class="form-control"><?=$actInfo?></textarea>
				 <br>
                                <span>(Maximum characters allowed 200)</span>
                                </div>
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">Status: &nbsp;<span class="star-color"></span></label>
				  <div class="col-md-10"><input type="checkbox" name="actStatus" id="actStatus" value='Active' <? if (isset($actStatus) && $actStatus == 'Active'){ echo "checked"; } ?>></div>
				 
				</div>
			<div class="btn-toolbar">
                            <input type="submit" name="Submit" value="<?= $mode ?>" class="btn btn-warning" data-loading-text="button"  />
                            <input type="button" name="Cancel" value="Cancel" onClick="window.location.href = 'activitytypelist.php'" class="btn" data-loading-text="button">
                    </div>
		</form>
		 </div>
        </div>
        </div>
    </div>
</div>