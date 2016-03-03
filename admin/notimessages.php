<?php
                require('../includes/app_config.php');
                require_once('inc/top.php');
                require_once('../classes/class.notimessages.php');
		if(!isset($_SESSION['ad_userid']) && empty($_SESSION['ad_userid']))
		{
			header("location:index.php");
			exit;
		}
		
		//LOOP
		$ntmType = '';
        $ntmTitle = '';
        $ntmEvtId = '';
        $ntmDescription = '';
        $ntmInterval = '';
        $ntmDate = '';
        $ntmTime = '';
        $ntmCategory = '';
        $ntmStatus = '';
        $ntmCreatedDate = '';

		$error = '';
		
		
		$notimessages = new notimessages();
		
		if (isset($_REQUEST['Submit']))
		{
			if($_REQUEST['ntmType']=='')
            {
                $_REQUEST['ntmType']='Manual';
            }

			/*Assign to local varriable*/
            
            $ntmType = $_REQUEST['ntmType'];
            $ntmTitle = $_REQUEST['ntmTitle'];
            $ntmEvtId = $_REQUEST['ntmEvtId'];
            $ntmDescription = @mysql_real_escape_string($_REQUEST['ntmDescription']);
            $ntmInterval = $_REQUEST['ntmInterval'];
			$ntmDate = date("Y-m-d",strtotime($_REQUEST['ntmDate']));
            $ntmTime = $_REQUEST['ntmTime'];
            $ntmCategory = $_REQUEST['ntmCategory'];
            $ntmStatus = $_REQUEST['ntmStatus'];
            $ntmCreatedDate = date('Y-m-d H:i:s');
	
			
            /*Assing to Class*/
                        
			$notimessages->ntmType    = $ntmType;
            $notimessages->ntmTitle    = $ntmTitle;
            $notimessages->ntmEvtId    = $ntmEvtId;
            $notimessages->ntmDescription    = $ntmDescription;
            $notimessages->ntmInterval    = $ntmInterval;

            $notimessages->ntmTime    = $ntmTime;
            $notimessages->ntmCategory    = $ntmCategory;
            $notimessages->ntmStatus    = $ntmStatus;
            $notimessages->ntmCreatedDate    = $ntmCreatedDate;
 
			
			if ($_REQUEST['Submit'] == 'Submit')
			{
                if ($error=='') { 
                    $ret=$notimessages->checkduplicate($_REQUEST[ntmTitle],0);
                    if($ret)
                    {
                        $error = 'Notification already exists';
                    }
                }
                if ($error == '')
                {	

                    if($_REQUEST['ntmInterval']=='Default')
                    {
						
						
						$sqlDate = "SELECT evtStartDate FROM events WHERE evtId='$ntmEvtId'";
						$resultDate = @mysql_query($sqlDate);
						$rowDate = @mysql_fetch_array($resultDate);
						$evtStartDate = $rowDate['evtStartDate'];
						if(!empty($evtStartDate) and $evtStartDate<date('Y-m-d'))
						{
							$notimessages->ntmDate = date('Y-m-d');
						}
						else
						{
							//$notimessages->ntmDate =date('Y-m-d', strtotime('-2 days', strtotime($evtStartDate)));
							$notimessages->ntmDate =$evtStartDate;
						}
					   
                    }
                    if($_REQUEST['ntmInterval']=='Date')
                    {
                          $notimessages->ntmDate = date("Y-m-d",strtotime($ntmDate));
                    }


                        //Add New Page		   
                    $notimessages->insert();					
                    //redirect page notimessageslist.php	
                    header("location:notimessageslist.php?msg=" . base64_encode('Record has been successfully inserted.') . "&msgstyle=" . base64_encode("success_msg"));
                    exit;
                }
			}
			else if ($_REQUEST['Submit'] == 'Update')
			{
				$ntmId = base64_decode($_REQUEST['cid']);
				$notimessages->ntmId = $ntmId; 				
				if ($error == '')
                {
                    //Check Page Name
                    $ret = $notimessages->checkduplicate($_REQUEST['ntmTitle'],$ntmId);
                    if ($ret)
                    {
                        $error = 'Notification already exists';
                    }
                }
                if ($error == '')
                {
					if($_REQUEST['ntmInterval']=='Default')
                    {
                       $sqlDate = "SELECT evtStartDate FROM events WHERE evtId='$ntmEvtId'";
						$resultDate = @mysql_query($sqlDate);
						$rowDate = @mysql_fetch_array($resultDate);
						$evtStartDate = $rowDate['evtStartDate'];
						if(!empty($evtStartDate) and $evtStartDate<date('Y-m-d'))
						{
							$notimessages->ntmDate = date('Y-m-d');
						}
						else
						{
							//$notimessages->ntmDate =date('Y-m-d', strtotime('-2 days', strtotime($evtStartDate)));
							$notimessages->ntmDate = $evtStartDate;
						}
                    }
                    if($_REQUEST['ntmInterval']=='Date')
                    {
                          $notimessages->ntmDate = date("Y-m-d",strtotime($ntmDate));
                    }
					if($notimessages->ntmDate>=date("Y-m-d",strtotime($ntmDate)))
					{
						@mysql_query("UPDATE  `notimessages` SET ntmSent='No', ntm48hours='No', ntm24hours='No' WHERE ntmId='$ntmId'");
					}

					
                    //Update Page Data
                    $notimessages->update($ntmId); 	
                    header("location:notimessageslist.php?limitstart=" . $_REQUEST['limitstart'] . "&pageno=" . $_REQUEST['pageno'] . "&msg=" . base64_encode('Record has been successfully updated.') . "&msgstyle=" . base64_encode("success_msg")); 
                }
			}
		}
		
		if (isset($_REQUEST['cid']) && trim($_REQUEST['cid']) != '')
		{
			//Get notimessages Data
			$notimessages->select(base64_decode($_REQUEST['cid']));
			/*Set Local Varriables*/
			$ntmType    = $notimessages->ntmType;
            $ntmTitle    = $notimessages->ntmTitle;
            $ntmEvtId    = $notimessages->ntmEvtId;
            $ntmDescription    = $notimessages->ntmDescription;
            $ntmInterval    = $notimessages->ntmInterval;
            $ntmDate    = $notimessages->ntmDate;
            $ntmTime    = $notimessages->ntmTime;
            $ntmCategory    = $notimessages->ntmCategory;
            $ntmStatus    = $notimessages->ntmStatus;
            $ntmCreatedDate    = $notimessages->ntmCreatedDate;

			
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
            jQuerX(function() {
                jQuerX( "#ntmDate" ).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat:'dd-mm-yy'
                });
            });
            jQuerX(function(){
            $( "#evtStartTime, #evtEndTime" ).datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 1,
                onSelect: function( selectedDate ) {
                    if(this.id == 'evtStartTime'){
                      var dateMin = $('#evtStartTime').datepicker("getDate");
                      var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 1); 
                      var rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 31); 
                      $('#evtEndTime').datepicker("option","minDate",rMin);
                      $('#evtEndTime').datepicker("option","maxDate",rMax);   

                      $('#evtStartTime').datepicker("option","dateFormat",'dd-mm-yy');
                      $('#evtEndTime').datepicker("option","dateFormat",'dd-mm-yy');
                    }

                }
            });
        })
        </script>
        <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
				
				$("#ntmInterval").change(function(){
					if($("#ntmInterval").val()=='Default')
					{
						$("#ntmTime").removeClass("required");
						$("#ntmTimeStar").css("display","none");
					}
					else
					{
						$("#ntmTime").addClass("required");
						$("#ntmTimeStar").css("display","");
					}
				})
				
                 $("#nevent").hide();
             $("#ntmType").click(function(){
               
                   $("#eventId").show();
                    $(".defult").show();
                    $("#nevent").show();
                       $("#ntmEvtId").addClass("required");

             });
             $("#ntmType1").click(function(){

                   $("#eventId").hide();
                    $(".defult").hide();
                     $("#nevent").hide();
                    $("#ntmEvtId").removeClass("required");


             });
         }); 

        
        </script>
        <script type="text/javascript">
           $(document).ready(function() {
              
            $("#ntmInterval").click(function(){
                  $("#eventDate").hide();
                  $("#ntmDate").removeClass("required");

            });
            $("#ntmInterval1").click(function(){

                  $("#eventDate").show();
                            $("#ntmDate").addClass("required");


            });
        }); 


        </script>
        <script>
 $(document).ready(function() {
     $("#ntmType").click(function(){

                  $("#eventId").show();
                  $("#eventId").removeClass("required");

            });
            $("#ntmType1").click(function(){

                  $("#eventId").hide();
                            $("#eventId1").addClass("required");


            });
  });

$("#ntmType").click(function(){

                  $("#eventId").show();
                 
                  $("#eventId").removeClass("required");

            });
            $("#ntmType1").click(function(){

                  $("#eventId").hide();
                            $("#eventId").addClass("required");


            });
  });
//var radio = $('input[type=radio][name=ntmType]:checked').val();
//
//
//if(radio=='Manual')
//{
//   
// $("#nevent").hide();
//}
//if(radio=='Automatic')
//{
//   $("#nevent").show(); 
//}
//
//
//       
//}
        </script>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget box">
                        <div class="widget-header">
                <?php if (isset($_REQUEST['cid']))
                { ?>
                    <h4><i class="icon-reorder"></i><?= $pmsg ?>Edit Notification</h4>
                <?php }
                else
                { ?>
                    <h4><i class="icon-reorder"></i><?= $pmsg ?>Add Notification</h4>
                <?php } ?>
                <div class="toolbar no-padding">
                    <h4>
                        <i class="icon-reorder"></i><a href="notimessageslist.php">Notification List</a>
                    </h4>
                    <div class="btn-group">
                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                    </div>
                </div>
            </div>
                        
            <div class="widget-content">
                <form class="form-horizontal row-border" enctype="multipart/form-data" method="post" id="frm_offer" name="frm_offer" onsubmit="return checknotimessagesDetails();
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
                        <label class="col-md-2 control-label">Notification Type: &nbsp;</label>
                        <div class="col-md-10">
                            <input  type="radio" name="ntmType" id="ntmType" value='Automatic' <?php if (isset($ntmType) && $ntmType == 'Automatic'){ echo "checked='checked'"; } ?>  >Automatic
                            <input  " type="radio" name="ntmType" id="ntmType1" value='Manual'  <?php if (isset($ntmType) && $ntmType == 'Manual'){ echo "checked='checked'"; } ?>>Manual
                        </div>
                        <label for="ntmType" class="error" generated="true"></label>
				 
                    </div>
                    <div class="form-group" >
                        <label class="col-md-2 control-label">Notification Title: &nbsp;<span class="star-color">*</span></label>
                            <div class="col-md-5"><input value="<?=$ntmTitle?>" name="ntmTitle" class="form-control required" type="text" id="ntmTitle"   />
                                <label for="ntmTitle" class="error" generated="true"></label>
                               <br>
                                <span>(Maximum characters allowed 100)</span>
                              </div>
				 
                    </div>
                        <div id="eventId"  style="display: none">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Select Relevant Event  : &nbsp;<span class="star-color">*</span><span class="star-color"></span></label>
                                <div class="col-md-5">
                                    <select name="ntmEvtId" id="ntmEvtId" class="form-control">
                                        <option value="">--Select--</option>
                                        <?php echo $gnrl->getEvents($ntmEvtId, "evtId", "evtName", "events", "evtStatus"); ?>
                                    </select>
                                    <label for="ntmEvtId" class="error" generated="true"></label>
                                    <br>
                                </div>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-md-2 control-label">Description : &nbsp;<span class="star-color">*</span></label>
                            <div class="col-md-5">
                                <textarea name="ntmDescription" id="ntmDescription" class="form-control required" maxlength="80"><?=$ntmDescription?></textarea>
                                <label for="ntmDescription" class="error" generated="true"></label>
                                <br>
                                <span>(Maximum 80 Characters)</span>
                            </div>
                        </div>
                          <div class="form-group">
                               
                                <label class="col-md-2 control-label" style="width: 12%">Interval Type:</label>
                                    <div class="col-md-2">
                                        <span class="defult" style="display: <?=($ntmType=='Automatic')?"":"none"?>"> <input type="radio" name="ntmInterval"  id="ntmInterval" value='Default' <?php if (isset($ntmInterval) && $ntmInterval == 'Default'){ echo "checked"; } ?>>Default</span>
                                      <input type="radio" name="ntmInterval"  id="ntmInterval1" value='Date' <?php if (isset($ntmInterval) && $ntmInterval == 'Date'){ echo "checked"; } ?> >Select Date
                                      
                                  </div>
                            <div id="eventDate">
                                <label class="col-md-2 control-label" style="width: 12%">Event  Date : &nbsp;<span class="star-color">*</span></label>
                                <div class="col-md-2" style="width: 20%; margin-right: 10%px;"><input value="<?php if(!empty($ntmDate)){ echo date("Y-m-d",strtotime($ntmDate)); }else {}?>"  maxlength="" name="ntmDate" type="text" id="ntmDate" class="form-control required"  />
                                    <label for="ntmDate" class="error" generated="true"></label>
                                </div>
                            </div>
                                <label class="col-md-2 control-label" style="width: 12%">Time : &nbsp;<span id="ntmTimeStar" class="star-color">*</span></label>
                                <div class="col-md-3" style="width: 15%">
                                <select name="ntmTime" id="ntmTime" class="form-control required">
                                    <option value="">-Select Time-</option>
                                                <?php 
                                                foreach (range(0,23) as $fullhour) {
                                                $fullhour2digit = strlen($fullhour)==1 ? '0' . $fullhour : $fullhour;
                                                $parthour = $fullhour > 12 ? $fullhour - 12 : $fullhour;
                                                $parthour .= $fullhour > 11 ? ":00 pm" : ":00 am";
                                                $parthour = $parthour=='0:00 am' ? '' : $parthour;
                                                $parthour = $parthour=='12:00 pm' ? '' : $parthour;

                                                $parthalf = $fullhour > 12 ? $fullhour - 12 : $fullhour;
                                                $parthalf .= $fullhour > 11 ? ":30 pm" : ":30 am";


                                                //SHOWS THE TEST FOR 'SELECTED' IN THE OPTION TAG
                                                     echo '<option ';
//                                                     if (date("H:i:s", strtotime($ntmTime)) === $fullhour2digit . ':00:00')
//                                                       {echo ' SELECTED ';}
                                                     echo 'value="' . $fullhour2digit . ':00:00">' .  $parthour . '</option>';
                                                     echo '<option ';
                                                     if (date("H:i:s", strtotime($ntmTime)) === $fullhour2digit  . ':30:00')
                                                        {echo ' SELECTED ';}
                                                     echo 'value="' . $fullhour2digit . ':30:00">' .  $parthalf . '</option>';
                                                }
                                                ?>
                                </select> 
                                    <label for="ntmTime" class="error" generated="true"></label>
                                    
                                <br>
                                
                            </div>
                                
                          </div>
                                 
                            
                                 <div class="form-group" >
                                  <label for="device" class="col-md-2 control-label">Notification Category : &nbsp;<span class="star-color">*</span></label>
	                          <div class="col-md-2">
                                  <select id="crSymbol" name="ntmCategory" class="form-control required">
                                      <option value="">--Select--</option>
                                            <option value="New Event"<?php if($ntmCategory=='New Event'){ echo "selected"; }?>>New Event</option>
											<option id="nevent" value="Event Reminder"<?php if($ntmCategory=='Event Reminder'){ echo "selected"; }?>>Event Reminder</option>
                                            <option value="General"<?php if($ntmCategory=='General'){ echo "selected"; }?>>General</option>
                                            <!--<option value="Activity Remainder"<?php if($ntmCategory=='Activity Remainder'){ echo "selected"; }?>>Activity Remainder</option>
                                            <option value="Event Remainder"<?php if($ntmCategory=='Event Remainder'){ echo "selected"; }?>>Event Remainder</option>
                                            <option value="Push"<?php if($ntmCategory=='Push'){ echo "selected"; }?>>Push</option>
                                            <option value="ETC"<?php if($ntmCategory=='ETC'){ echo "selected"; }?>>ETC</option> -->
                                            <option value="Adult Activities"<?php if($ntmCategory=='Adult Activities'){ echo "selected"; }?>>Adult Activities</option>
                                            <option value="Youth Activities"<?php if($ntmCategory=='Youth Activities'){ echo "selected"; }?>>Youth Activities</option>
                                            <option value="Mini Maccabi Activities"<?php if($ntmCategory=='Mini Maccabi Activities'){ echo "selected"; }?>>Mini Maccabi Activities</option>
                                            <option value="Bogrim"<?php if($ntmCategory=='Bogrim'){ echo "selected"; }?>>Bogrim</option>
                                            <option value="Non Community Events"<?php if($ntmCategory=='Non Community Events'){ echo "selected"; }?>>Non Community Events</option>
                                             
                                    </select>                                    
                                    <br>
                                   </div>
                                 </div>
                       
                        
						<div class="form-group">
                            <label class="col-md-2 control-label">Status: &nbsp;<span class="star-color"></span></label>
				  <div class="col-md-1">
                                      <select name="ntmStatus" class="form-control" id="ntmStatus" >
                                        <option  <?=isset($ntmStatus) &&  $ntmStatus=='Active'?"selected='selected'":''?>value=Active>Active</option>
                                         <option  <?=isset($ntmStatus) &&  $ntmStatus=='Inactive'?"selected='selected'":''?>value=Inactive>Inactive</option>
                                      </select>

                                      </div>
				 
				</div>
						
			<div class="btn-toolbar">
                            <input type="submit" name="Submit" value="<?= $mode ?>" class="btn btn-warning" data-loading-text="button"  />
                            <input type="button" name="Cancel" value="Cancel" onClick="window.location.href = 'notimessageslist.php'" class="btn" data-loading-text="button">
                    </div>
		</form>
		 </div>
        </div>
        </div>
    </div>
</div>