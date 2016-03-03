<?php
                require('../includes/app_config.php');
                require_once('inc/top.php');
                require_once('../classes/class.eventattendees.php');
		if(!isset($_SESSION['ad_userid']) && empty($_SESSION['ad_userid']))
		{
			header("location:index.php");
			exit;
		}
		
		//LOOP
		$eadEvtIdi = '';
$eadMemId = '';
$eadStatus = '';
$eadCreatedDate = '';

		$error = '';
		
		
		$eventattendees = new eventattendees();
		
		if (isset($_REQUEST['Submit']))
		{
				
			 
			/*Assign to local varriable*/
            
                        $eadEvtIdi = $_REQUEST['eadEvtIdi'];
$eadMemId = $_REQUEST['eadMemId'];
$eadStatus = $_REQUEST['eadStatus'];
$eadCreatedDate = $_REQUEST['eadCreatedDate'];
	
			
                        /*Assing to Class*/
                        
			$eventattendees->eadEvtIdi    = $eadEvtIdi;
$eventattendees->eadMemId    = $eadMemId;
$eventattendees->eadStatus    = $eadStatus;
$eventattendees->eadCreatedDate    = $eadCreatedDate;
 
			
			if ($_REQUEST['Submit'] == 'Submit')
			{
                            if ($error=='') { 
                                $ret=$eventattendees->checkduplicate($eventattendees->eadId,0);
                                if($ret)
                                {
                                    $error = 'eventattendees already exists';
                                }
                            }
                            if ($error == '')
                            {					
                                    //Add New Page		   
                                    $eventattendees->insert();					
                                    //redirect page eventattendeeslist.php	
                                    header("location:eventattendeeslist.php?msg=" . base64_encode('Record has been successfully inserted.') . "&msgstyle=" . base64_encode("success_msg"));
                                    exit;
                            }
			}
			else if ($_REQUEST['Submit'] == 'Update')
			{
				$eadId = base64_decode($_REQUEST['cid']);
				$eventattendees->eadId = $eadId; 				
				if ($error == '')
                            {
				//Check Page Name
                                $ret = $eventattendees->checkduplicate($eventattendees->eadId,$eadId);
                                if ($ret)
                                {
                                        $error = 'eventattendees already exists';
                                }
                            }
			if ($error == '')
                            {
                                //Update Page Data
                                $eventattendees->update($eadId); 	
                                header("location:eventattendeeslist.php?limitstart=" . $_REQUEST['limitstart'] . "&pageno=" . $_REQUEST['pageno'] . "&msg=" . base64_encode('Record has been successfully updated.') . "&msgstyle=" . base64_encode("success_msg")); 
                            }
			}
		}
		
		if (isset($_REQUEST['cid']) && trim($_REQUEST['cid']) != '')
		{
			//Get eventattendees Data
			$eventattendees->select(base64_decode($_REQUEST['cid']));
			/*Set Local Varriables*/
			$eadEvtIdi    = $eventattendees->eadEvtIdi;
$eadMemId    = $eventattendees->eadMemId;
$eadStatus    = $eventattendees->eadStatus;
$eadCreatedDate    = $eventattendees->eadCreatedDate;

			
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
                                <h4><i class="icon-reorder"></i><?= $pmsg ?>Edit eventattendees</h4>
                            <?php }
                            else
                            { ?>
                                <h4><i class="icon-reorder"></i><?= $pmsg ?>Add eventattendees</h4>
                            <?php } ?>
                            <div class="toolbar no-padding">
                                <h4>
                                    <i class="icon-reorder"></i><a href="eventattendeeslist.php">eventattendees List</a>
                                </h4>
                                <div class="btn-group">
                                    <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                                </div>
                            </div>
                        </div>
                        
                <div class="widget-content">
                    <form class="form-horizontal row-border" enctype="multipart/form-data" method="post" id="frm_offer" name="frm_offer" onsubmit="return checkeventattendeesDetails();
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
                            <label class="col-md-2 control-label">eadEvtIdi: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input value="<?=$eadEvtIdi?>"  name="eadEvtIdi" class="form-control number" type="text" id="eadEvtIdi"  /></div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">eadMemId: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input value="<?=$eadMemId?>"  name="eadMemId" class="form-control number" type="text" id="eadMemId"  /></div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">eadStatus: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input type="checkbox" name="eadStatus" id="eadStatus" value='Active' <? if (isset($eadStatus) && $eadStatus == 'Active'){ echo "checked"; } ?>></div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">eadCreatedDate: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input value="<?=$eadCreatedDate?>" maxlength="" name="eadCreatedDate" class="form-control" type="text" id="eadCreatedDate"  /></div>
				 
				</div>
			<div class="btn-toolbar">
                            <input type="submit" name="Submit" value="<?= $mode ?>" class="btn btn-warning" data-loading-text="button"  />
                            <input type="button" name="Cancel" value="Cancel" onClick="window.location.href = 'eventattendeeslist.php'" class="btn" data-loading-text="button">
                    </div>
		</form>
		 </div>
        </div>
        </div>
    </div>
</div>