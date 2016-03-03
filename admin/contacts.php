<?php
                require('../includes/app_config.php');
                require_once('inc/top.php');
                require_once('../classes/class.contacts.php');
		if(!isset($_SESSION['ad_userid']) && empty($_SESSION['ad_userid']))
		{
			header("location:index.php");
			exit;
		}
		
		//LOOP
		$cnName = '';
$cnEmail = '';
$cnPhone = '';
$cnAddress = '';
$cnLatitude = '';
$cnLongitude = '';
$cnStatus = '';
$cnCreatedDate = '';

		$error = '';
		
		
		$contacts = new contacts();
		
		if (isset($_REQUEST['Submit']))
		{
			if($_REQUEST['cnStatus']=='')
                        {
                            $_REQUEST['cnStatus']='Inactive';
                        }
			 
			/*Assign to local varriable*/
            
                        $cnName = $_REQUEST['cnName'];
$cnEmail = $_REQUEST['cnEmail'];
$cnPhone = $_REQUEST['cnPhone'];
$cnAddress = $_REQUEST['cnAddress'];
$cnLatitude = $_REQUEST['cnLatitude'];
$cnLongitude = $_REQUEST['cnLongitude'];
$cnStatus = $_REQUEST['cnStatus'];
$cnCreatedDate = date('Y-m-d H:i:s');
	
			
                        /*Assing to Class*/
                        
			$contacts->cnName    = $cnName;
$contacts->cnEmail    = $cnEmail;
$contacts->cnPhone    = $cnPhone;
$contacts->cnAddress    = $cnAddress;
$contacts->cnLatitude    = $cnLatitude;
$contacts->cnLongitude    = $cnLongitude;
$contacts->cnStatus    = $cnStatus;
$contacts->cnCreatedDate    = $cnCreatedDate;
 
			
			if ($_REQUEST['Submit'] == 'Submit')
			{
                            if ($error=='') { 
                                $ret=$contacts->checkduplicate($_REQUEST['cnEmail'],0);
                                if($ret)
                                {
                                    $error = 'Contacts already exists';
                                }
                            }
                            if ($error == '')
                            {					
                                    //Add New Page		   
                                    $contacts->insert();					
                                    //redirect page contactslist.php	
                                    header("location:contactslist.php?msg=" . base64_encode('Record has been successfully inserted.') . "&msgstyle=" . base64_encode("success_msg"));
                                    exit;
                            }
			}
			else if ($_REQUEST['Submit'] == 'Update')
			{
				$cnId = base64_decode($_REQUEST['cid']);
				$contacts->cnId = $cnId; 				
				if ($error == '')
                            {
				//Check Page Name
                                $ret = $contacts->checkduplicate($contacts->cnEmail,$cnId);
                                if($ret)
                                {
                                        $error = 'Contacts already exists';
                                }
                            }
			if ($error == '')
                            {
                                //Update Page Data
                                $contacts->update($cnId); 	
                                header("location:contactslist.php?limitstart=" . $_REQUEST['limitstart'] . "&pageno=" . $_REQUEST['pageno'] . "&msg=" . base64_encode('Record has been successfully updated.') . "&msgstyle=" . base64_encode("success_msg")); 
                            }
			}
		}
		
		if (isset($_REQUEST['cid']) && trim($_REQUEST['cid']) != '')
		{
			//Get contacts Data
			$contacts->select(base64_decode($_REQUEST['cid']));
			/*Set Local Varriables*/
			$cnName    = $contacts->cnName;
$cnEmail    = $contacts->cnEmail;
$cnPhone    = $contacts->cnPhone;
$cnAddress    = $contacts->cnAddress;
$cnLatitude    = $contacts->cnLatitude;
$cnLongitude    = $contacts->cnLongitude;
$cnStatus    = $contacts->cnStatus;
$cnCreatedDate    = $contacts->cnCreatedDate;

			
			$mode = 'Update';
		}
		else
		{
		
			$mode = 'Submit';
		}
		
		?>
<script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">

 <script src="http://code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
 <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
        <script type="text/javascript">
            jQuerX(document).ready(function () {
        jQuerX("#frm_offer").validate();
        });
        </script>
<script type="text/javascript">
jQuerX(function(){
    
    $("#cnAddress").change(function(){
    var address = $("#cnAddress").val().replace(/\s/g,"+");

    $.ajax({
        url: "http://maps.google.com/maps/api/geocode/json?address="+address+"&sensor=false",
        dataType: "json",
        success : function (data){
                $("#cnLatitude").val(data.results[0].geometry.location.lat);
                $("#cnLongitude").val(data.results[0].geometry.location.lng);
            }
        });
    });    
})
</script>
        
        
        
        
        <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="widget box">
                                    <div class="widget-header">
                            <?php if (isset($_REQUEST['cid']))
                            { ?>
                                <h4><i class="icon-reorder"></i><?= $pmsg ?>Edit Contact</h4>
                            <?php }
                            else
                            { ?>
                                <h4><i class="icon-reorder"></i><?= $pmsg ?>Add Contact</h4>
                            <?php } ?>
                            <div class="toolbar no-padding">
                                <h4>
                                    <i class="icon-reorder"></i><a href="contactslist.php">Contact List</a>
                                </h4>
                                <div class="btn-group">
                                    <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                                </div>
                            </div>
                        </div>
                        
                <div class="widget-content">
                    <form class="form-horizontal row-border" enctype="multipart/form-data" method="post" id="frm_offer" name="frm_offer" onsubmit="return checkcontactsDetails();
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
                            <label class="col-md-2 control-label">Name: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-5"><input value="<?=$cnName?>"  maxlength="100"   name="cnName" class="form-control required lettersonly" type="text" id="cnName"  />
                                   <br>
                                <span>(Maximum characters allowed 100)</span>
                                  </div>
				 
				</div>
                                    <div class="form-group">
                            <label class="col-md-2 control-label">Phone: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-5"><input value="<?=$cnPhone?>"  maxlength="20"   name="cnPhone" class="form-control required number" type="text" id="cnPhone"  /></div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">Email: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-5"><input value="<?=$cnEmail?>"  maxlength="100"   name="cnEmail" class="form-control required email" type="text" id="cnEmail"  /></div>
				 
				</div>
						
						<div class="form-group">
                            <label class="col-md-2 control-label">Address: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-5">
                                <textarea name="cnAddress" id="cnAddress" class="form-control required" maxlength="200"><?=$cnAddress?></textarea>
                                 <br>
                                <span>(Maximum characters allowed 255)</span>
                                  </div>

				</div>
                                <div class="form-group">
                                           <label class="col-md-2 control-label">Latitutde : &nbsp;</label>
                                           <div class="col-md-2"><input type="text" name="cnLatitude" id="cnLatitude" value="<?=$cnLatitude; ?>" class="form-control" readonly></div>
                                     <label class="col-md-2 control-label"> Longitude : &nbsp;</label>
                                     <div class="col-md-2"><input type="text" name="cnLongitude" id="cnLongitude" value="<?=$cnLongitude; ?>" class="form-control" readonly></div>
                                        
                                        <!--<input type="text" value="<?=$evtLatitude?>" name="locLatitude" id="locLatitude" maxlength="20" class="form-control number" >--> 
                                        <br>
                                    </div>
                        
						<div class="form-group">
                            <label class="col-md-2 control-label">Status: &nbsp;<span class="star-color"></span></label>
				  <div class="col-md-10"><input type="checkbox" name="cnStatus" id="cnStatus" value='Active' <? if (isset($cnStatus) && $cnStatus == 'Active'){ echo "checked"; } ?>></div>
				 
				</div>
						
			<div class="btn-toolbar">
                            <input type="submit" name="Submit" value="<?= $mode ?>" class="btn btn-warning" data-loading-text="button"  />
                            <input type="button" name="Cancel" value="Cancel" onClick="window.location.href = 'contactslist.php'" class="btn" data-loading-text="button">
                    </div>
		</form>
		 </div>
        </div>
        </div>
    </div>
</div>