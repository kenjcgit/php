<?php
                require('../includes/app_config.php');
                require_once('inc/top.php');
                require_once('../classes/class.events.php');
		if(!isset($_SESSION['ad_userid']) && empty($_SESSION['ad_userid']))
		{
			header("location:index.php");
			exit;
		}

$Folder = "../uploads/events/";
$limitstart=$_REQUEST['limitstart'];
$pageno=$_REQUEST['pageno'];

//Delete Uploaded Image
if(isset($_REQUEST['script']) && $_REQUEST['script'] == 'del_proimage' && isset($_REQUEST['cid']) && $_REQUEST['cid'] != '')
{
    $orignal_path = $Folder.base64_decode($_REQUEST['profileImage']);
    @unlink($orignal_path);

    $sql = "UPDATE events SET evtImage ='' WHERE evtId = '".base64_decode($_REQUEST['cid'])."'";
    $rs = mysql_query($sql);

    $limitstart=$_REQUEST['limitstart'];
    $pageno=$_REQUEST['pageno'];
    $gnrl->redirectTo("events.php?limitstart=".$limitstart."&pageno=".$pageno."&cid=".$_REQUEST['cid']."&msg=".base64_encode("Image has been deleted successfully")."&msgstyle=".base64_encode("success_msg"));
}

		//LOOP
$evtactTypeId = '';
$evtName = '';
$evtStartDate = '';
$evtEndDate = '';
$evtStartTime = '';
$evtEndTime = '';
$evtInfo = '';
$evtImage = '';
$evtLocatioName = '';
$evtAddress = '';
$evtLatitude = '';
$evtLongitude = '';
$evtTags = '';
$evtStatus = '';
$evtCreatedDate = '';

		$error = '';
		
		
		$events = new events();
		
		if (isset($_REQUEST['Submit']))
		{
				if($_REQUEST['evtStatus']=='')
                                {
                                    $_REQUEST['evtStatus']='Inactive';
                                }
                                
 $evtInfo = mysql_real_escape_string($_REQUEST['evtInfo']);
if ($evtInfo == '')
{
$error = 'Descrption Field is Madantory';
}
if(isset($_FILES['evtImage']['name']) && $_FILES['evtImage']['name']!='')
    {
        //Set file Name
        $Image1 = $_FILES['evtImage']['name'];
        $Image = $gnrl->sanitize($Image1, $force_lowercase = true, $anal = true);

        //Validation OF image
        $IMG = $gnrl->ImgUpload($Image);
        $pos = strpos($IMG,"Invalid");
 
        if ($pos !== false)
        {
            $error = $IMG;
        }
        else
        {
            if($error == '')
            {
                 move_uploaded_file($_FILES['evtImage']['tmp_name'],$Folder.$IMG);
                 $evtImage = $IMG;
            }
        }

    }else{
        $evtImage = $_REQUEST['evtImage'];
    }

			/*Assign to local varriable*/
            
$evtactTypeId = $_REQUEST['evtactTypeId'];
$evtName = $_REQUEST['evtName'];
$evtStartDate = date("Y-m-d H:i:s",strtotime($_REQUEST['evtStartDate']));
$evtEndDate = date("Y-m-d H:i:s",strtotime($_REQUEST['evtStartDate']));
$evtStartTime = $_REQUEST['evtStartTime'];
$evtEndTime = $_REQUEST['evtStartTime'];
$evtInfo = $_REQUEST['evtInfo'];
//$evtImage = $_REQUEST['evtImage'];
$evtLocatioName = $_REQUEST['evtLocatioName'];
$evtAddress = $_REQUEST['evtAddress'];
$evtLatitude = $_REQUEST['evtLatitude'];
$evtLongitude = $_REQUEST['evtLongitude'];
$evtTags = $_REQUEST['evtTags'];
$evtStatus = $_REQUEST['evtStatus'];
$evtCreatedDate = date('Y-m-d H:i:s');
	
			
                        /*Assing to Class*/
                        
$events->evtactTypeId    = $evtactTypeId;
$events->evtName    = $evtName;
$events->evtStartDate    = $evtStartDate;
$events->evtEndDate    = $evtEndDate;
$events->evtStartTime    = $evtStartTime;
$events->evtEndTime    = $evtEndTime;
$events->evtInfo    = $evtInfo;
$events->evtImage    = $evtImage;
$events->evtLocatioName    = $evtLocatioName;
$events->evtAddress    = $evtAddress;
$events->evtLatitude    = $evtLatitude;
$events->evtLongitude    = $evtLongitude;
$events->evtTags    = $evtTags;
$events->evtStatus    = $evtStatus;
$events->evtCreatedDate    = $evtCreatedDate;
 
			
			if ($_REQUEST['Submit'] == 'Submit')
			{
                          
                            if ($error=='') 
                                { 
                                     $ret=$events->checkduplicate($_REQUEST['evtName'],0);
                                     if($ret)
                                     {
                                           $error = 'Event is already exits';
                                     }
                               
                              $newfile='';
                               if($_REQUEST['evtTags']!='')
			       {
				$spArray=array();
				$spArray=explode(',', $_REQUEST['evtTags']);
				
				$expSpIds='';
				for($i=0;$i<count($spArray);$i++)
				{
					if(trim($spArray[$i])!='')
					{
						//Check for specialization existence
					        $sqlChkSpExt="SELECT tagId FROM tags WHERE tagName='".trim($spArray[$i])."'";
						$resChkSpExt=mysql_query($sqlChkSpExt);
                                                
						
						if(mysql_num_rows($resChkSpExt)<=0)
						{
							//Add specialization
                                                          
							$sqlAddSp="INSERT INTO events(evtTags) VALUES('".trim($spArray[$i])."','','Not Approved')";
							$resAddSp=mysql_query($sqlAddSp);
                                                       
							$expSpIds.=mysql_insert_id().",";
                                                       
							 							
						}
						else
						{
							//Get specialization
							$rowChkSpExt=mysql_fetch_array($resChkSpExt);
                                                         //$events->evtTags = $row[$i];
							$expSpIds.=$rowChkSpExt['tagId'].",";
                                                        	
						}
                                                
					}	
				}					
				
		          if(strlen($expSpIds)>0)
			   $expSpIds=substr($expSpIds,0,strlen($expSpIds)-1);
			}
                         
			//echo $sqlAddExpert;exit;
//                            $resAddExpert=mysql_query($sqlAddExpert);
//                            $expertId=mysql_insert_id(); 
                        
                        $events->insert();	
                        $evtId = @mysql_insert_id();
                            
		          $expSpArray=explode(",",$expSpIds);
                          
                          
			   for($i=0;$i<count($expSpArray);$i++)
			{
                              
                                    $events->evtTags = $expSpArray[$i];
                                    
                                    $expertId=mysql_insert_id();
			            $sqlAddExSp="INSERT INTO eventtags(etEvtId, etTagId) VALUES('".$evtId."', '" .$expSpArray[$i]. "')";
				    $resAddExSp=mysql_query($sqlAddExSp);
                       }
                
                        
                       
			header("location:eventslist.php?msg=" . base64_encode('Record has been successfully inserted.') . "&msgstyle=" . base64_encode("success_msg"));		
                        exit; 
                    }
                }     
                   
                if ($_REQUEST['Submit'] == 'Copy')
			{
                          
                            if ($error=='') 
                                { 
                                     $ret=$events->checkduplicate($_REQUEST['evtName'],0);
                                     if($ret)
                                     {
                                           $error = 'Event is already exits';
                                     }
                               
                              $newfile='';
                               if($_REQUEST['evtTags']!='')
			       {
				$spArray=array();
				$spArray=explode(',', $_REQUEST['evtTags']);
				
				$expSpIds='';
				for($i=0;$i<count($spArray);$i++)
				{
					if(trim($spArray[$i])!='')
					{
						//Check for specialization existence
					        $sqlChkSpExt="SELECT tagId FROM tags WHERE tagName='".trim($spArray[$i])."'";
						$resChkSpExt=mysql_query($sqlChkSpExt);
                                                
						
						if(mysql_num_rows($resChkSpExt)<=0)
						{
							//Add specialization
                                                          
							$sqlAddSp="INSERT INTO events(evtTags) VALUES('".trim($spArray[$i])."','','Not Approved')";
							$resAddSp=mysql_query($sqlAddSp);
                                                       
							$expSpIds.=mysql_insert_id().",";
                                                       
							 							
						}
						else
						{
							//Get specialization
							$rowChkSpExt=mysql_fetch_array($resChkSpExt);
                                                         //$events->evtTags = $row[$i];
							$expSpIds.=$rowChkSpExt['tagId'].",";
                                                        	
						}
                                                
					}	
				}					
				
		          if(strlen($expSpIds)>0)
			   $expSpIds=substr($expSpIds,0,strlen($expSpIds)-1);
			}
                         
			//echo $sqlAddExpert;exit;
//                            $resAddExpert=mysql_query($sqlAddExpert);
//                            $expertId=mysql_insert_id(); 
                        
                        $events->insert();	
                        $evtId = @mysql_insert_id();
                            
		          $expSpArray=explode(",",$expSpIds);
                          
                          
			   for($i=0;$i<count($expSpArray);$i++)
			{
                              
                                    $events->evtTags = $expSpArray[$i];
                                    
                                    $expertId=mysql_insert_id();
			            $sqlAddExSp="INSERT INTO eventtags(etEvtId, etTagId) VALUES('".$evtId."', '" .$expSpArray[$i]. "')";
				    $resAddExSp=mysql_query($sqlAddExSp);
                       }
                
                        
                       
			header("location:eventslist.php?msg=" . base64_encode('Record has been successfully copied.') . "&msgstyle=" . base64_encode("success_msg"));		
                        exit; 
                    }
                }     
                   
                         /*if ($error == '')
                            {
                                    $row= explode(',', $_REQUEST['evtTags']);
                                     for($i=0; $i<count($row); $i++)
                                     {
                                        if(trim($row[$i])!='')
                           {
                                        $sqlChkSpExt="SELECT evtId FROM events WHERE evtTags='".trim($spArray[$i])."'";
                                    $sql= "SELECT tagId From tags WHERE tagName='".$row[$i]."'";
                                    $res= mysql_query($sql);
                                    $count= mysql_num_rows($res);
                                    $flag=1;
                                    
                                    if($count==0)
                                    {
                                        $flag=0;
                                        
                                       
                                    }
                                    
                                    if($flag)
                                    {
                                        $events->evtTags = $row[$i];
                                        $events->insert();	

                                        $last=  mysql_insert_id();
                                        $sql = "INSERT INTO `eventtags` (`etEvtId` ) VALUES ($last)";
                                        $result = mysql_query($sql); 
                                        
                                    }
                                    else
                                    {
                                        $error="Please verify activity tags, those are not exists in master";
                                        
                                    }

                                  
                                    //$events->insert();
                                }
                                if($error=='')
                                {
                                    header("location:eventslist.php?msg=" . base64_encode('Record has been successfully inserted.') . "&msgstyle=" . base64_encode("success_msg"));
                                    exit;
                                }
                                
                           }*/
                        
                                    //Add New Page		   
                                    	
                                    
                       
                                    //redirect page eventslist.php	
                                    
                            
			
			else if ($_REQUEST['Submit'] == 'Update')
			{
				$evtId = base64_decode($_REQUEST['cid']);
				$events->evtId = $evtId; 				
				if ($error == '')
                                 {
				//Check Page Name
                                $ret = $events->checkduplicate($_REQUEST['evtName'],$evtId);
                                if ($ret)
                                {
                                        $error = 'Event is already exits';
                                }
                              }
			if ($error == '')
                            {
                                                                //Update Page Data
                                $events->update($evtId); 	
                                header("location:eventslist.php?limitstart=" . $_REQUEST['limitstart'] . "&pageno=" . $_REQUEST['pageno'] . "&msg=" . base64_encode('Record has been successfully updated.') . "&msgstyle=" . base64_encode("success_msg")); 
                            }
			}
		}
		
      	if (isset($_REQUEST['cid']) && trim($_REQUEST['cid']) != '')
		{
			//Get events Data
			$events->select(base64_decode($_REQUEST['cid']));
			/*Set Local Varriables*/
			$evtactTypeId    = $events->evtactTypeId;
            $evtName    = $events->evtName;
            $evtStartDate    = $events->evtStartDate;
            $evtEndDate    = $events->evtEndDate;
            $evtStartTime    = $events->evtStartTime;
            $evtEndTime    = $events->evtEndTime;
            $evtInfo    = $events->evtInfo;
            $evtImage    = $events->evtImage;
            $evtLocatioName    = $events->evtLocatioName;
            $evtAddress    = $events->evtAddress;
            $evtLatitude    = $events->evtLatitude;
            $evtLongitude    = $events->evtLongitude;
            $evtTags    = $events->evtTags;
            $evtStatus    = $events->evtStatus;
            $evtCreatedDate    = $events->evtCreatedDate;

			
			$mode = 'Update';
		}
		else if($script=='copy' && $evtId!='')
        {
            //Get events Data
			$events->select($evtId);
			/*Set Local Varriables*/
			$evtactTypeId    = $events->evtactTypeId;
            $evtName    = $events->evtName." copy";
            $evtStartDate    = $events->evtStartDate;
            $evtEndDate    = $events->evtEndDate;
            $evtStartTime    = $events->evtStartTime;
            $evtEndTime    = $events->evtEndTime;
            $evtInfo    = $events->evtInfo;
            $evtImage    = $events->evtImage;
            $evtLocatioName    = $events->evtLocatioName;
            $evtAddress    = $events->evtAddress;
            $evtLatitude    = $events->evtLatitude;
            $evtLongitude    = $events->evtLongitude;
            $evtTags    = $events->evtTags;
            $evtStatus    = $events->evtStatus;
            $evtCreatedDate    = $events->evtCreatedDate;
            
            $mode = 'Copy';
        }
        else
		{
		
			$mode = 'Submit';
		}
		
		?>
<script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">

 <script src="http://code.jquery.com/ui/1.11.3/jquery-ui.js"></script>

<style>
    .black_overlay{
        display: none;
        position: fixed;
        top: 0%;
        left: 0%;
        width: 100%;
        height: 100%;
        background-color: black;
        z-index:1001;
        -moz-opacity: 0.8;
        opacity:.80;
        filter: alpha(opacity=80);
    }
    .white_content {
        background-color: white;
        border: 8px solid grey;
        display: none;
        height: 70%;
        left: 24%;
        overflow: auto;
        padding: 16px;
        position: fixed;
        top: 21%;
        width: 54%;
        z-index: 1002;
    }
</style>
 <style>
 
 .ui-menu-item {
font-size: 12px;
}
 
 .ui-menu-item:hover {
	color:black;
	background-color:#f9aa1a;
	background-image:none;
	}
	
	.ui-state-hover, .ui-widget-content .ui-state-hover, .ui-widget-header .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus, .ui-widget-header .ui-state-focus {
    color:black;
	background-color:#f9aa1a;
	background-image:none;
}
 </style>
  <?php
 
 	//Create specialization string for javascript
	
	$sqlGetSpecialization="SELECT tagName FROM tags ORDER BY tagId ASC";
	$resGetSpecialization=mysql_query($sqlGetSpecialization);
	
	$tagString='';
	while($rowGetSpecialization=mysql_fetch_array($resGetSpecialization)) 
 	{
		$tagString .= "'".ucfirst($rowGetSpecialization['tagName'])."',";
	}
	
	$tagString=substr($tagString,0,strlen($tagString)-1);
 ?>
 <script>
  $(function() {
    var availableTags = [
      <?=$tagString?>
    ];
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( "#evtTags" )
      // don't navigate away from the field on tab when selecting an item
      .bind( "keydown", function( event ) {
          
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          // delegate back to autocomplete, but extract the last term
          response( $.ui.autocomplete.filter(
            availableTags, extractLast( request.term ) ) );
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      }).focus(function(){
////                                $(this).trigger('keydown.autocomplete');
                                 $(this).autocomplete('search');
                                         
                            });    
  });
  </script>
<style>
#map-canvas {
  width: 100%;
  height: 100%;
}
</style>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
      var map;
      var geocoder;
      var mapOptions = { center: new google.maps.LatLng(0.0, 0.0), zoom: 2,
        mapTypeId: google.maps.MapTypeId.ROADMAP };

     function initialize() {
      var map;
      var position = new google.maps.LatLng(document.getElementById('evtLatitude').value,document.getElementById('evtLongitude').value);    // set your own default location.
      var myOptions = {
        zoom: 13,
        center: position
      };
      var map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
      google.maps.event.addListener(map, 'click', function(event) {
            placeMarker(event.latLng);
            });

      // We send a request to search for the location of the user.  
      // If that location is found, we will zoom/pan to this place, and set a marker
      navigator.geolocation.getCurrentPosition(locationFound, locationNotFound);

      function locationFound(position) {
        // we will zoom/pan to this place, and set a marker
        var location = new google.maps.LatLng(document.getElementById('evtLatitude').value, document.getElementById('evtLongitude').value);
        // var accuracy = position.coords.accuracy;

        map.setCenter(location);
        var marker = new google.maps.Marker({
            position: location, 
            map: map, 
            draggable: true,
            title: "You are here! Drag the marker to the exact location."
        });
        // set the value an value of the <input>
        updateInput(location.lat(), location.lng());

        // Add a "drag end" event handler
        google.maps.event.addListener(marker, 'dragend', function() {
          updateInput(this.position.lat(), this.position.lng());
        });

    
      }

      function locationNotFound() {
        // location not found, you might want to do something here
      }

    }
    
    function updateInput(lat, lng) {
      document.getElementById("evtLatitude").value = lat;
      document.getElementById("evtLongitude").value = lng;
      
    }
    function viewmap(){
        initialize();
      
    
        document.getElementById('light').style.display='block';
        document.getElementById('fade').style.display='block';
    }
    
    
    
    
  

    
      
</script>    

<script type="text/javascript">

    jQuerX(document).ready(function () {

        jQuerX("#frm_offer").validate();
        
        var oFCKeditor = new FCKeditor( 'evtInfo' ) ;
        oFCKeditor.BasePath = "fckeditor/" ;
        oFCKeditor.ReplaceTextarea() ;

    });
    
</script>
<script type="text/javascript">
    
$(document).ready(function(){
        $("#evtImage").mouseleave(function(){
            var f=this.files[0];
            var msize = f.size ;
            if(msize >= 2109440) { 
               $("#file_msg").text("Please Select File Below 2 MB");
               $('input[type=submit]').attr('disabled',true);
               }
            else { 
                $("#file_msg").hide();
                $('input[type=submit]').attr('disabled',false);
                $("#Submit").click(function(){   
            $(".img_upload").show(); });
            }
          });      
});

</script>
<script type="text/javascript">
jQuerX(function(){
    
    $("#evtAddress").change(function(){
    var address = $("#evtAddress").val().replace(/\s/g,"+");

    $.ajax({
        url: "http://maps.google.com/maps/api/geocode/json?address="+address+"&sensor=false",
        dataType: "json",
        success : function (data){
                $("#evtLocatioName").val(data.results[0].formatted_address);
                $("#evtLatitude").val(data.results[0].geometry.location.lat);
                $("#evtLongitude").val(data.results[0].geometry.location.lng);
            }
        });
    });    
    
    
    
$( "#evtStartDate, #evtEndDate" ).datepicker({
    defaultDate: "+1w",
    changeMonth: true,
    numberOfMonths: 1,
    onSelect: function( selectedDate ) {
        if(this.id == 'evtStartDate'){
          var dateMin = $('#evtStartDate').datepicker("getDate");
          var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 1); 
          var rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 31); 
          $('#evtEndDate').datepicker("option","minDate",rMin);
          $('#evtEndDate').datepicker("option","maxDate",rMax);   

          $('#evtStartDate').datepicker("option","dateFormat",'dd-mm-yy');
          $('#evtEndDate').datepicker("option","dateFormat",'dd-mm-yy');
        }

    }
});
})
</script>
<script type="text/javascript">

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
<script type="text/javascript">
    jQuerX(document).ready(function () {
        jQuerX("#frm_offer").validate({
    
    event: 'blur',
    rules: {
          
         stuProfilePic:{
                     accept:"jpg,jpeg,png"
        }
         
    },
     messages: {
            
        stuProfilePic: {
            accept: "Only image type jpg/jpeg/png is allowed"           
        }
     }
  });
</script>
<script>
            $(document).ready(function(){
          jQuery(".validatename").keypress(function(e){
          
        if ((e.which > 96 && e.which < 123 ) || (e.which > 47 && e.which < 58 ) || (e.which > 64 && e.which < 91 ) || (e.which == 8) || (e.which == 32) ) {            
        }
        else
            {
               return false;
         }
    }); 
 
    });
</script>      

        <div class="container">
            <div id="light" class="white_content"><a href = "javascript:void(0)" onclick = "document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'" style="float: right;">Close</a>
<div id="map-canvas"></div>
<!--Location:<br>-->
<!--<input id="my_location" readonly="readonly">-->
    </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="widget box">
                                    <div class="widget-header">
                            <?php if (isset($_REQUEST['cid']))
                            { ?>
                                <h4><i class="icon-reorder"></i><?= $pmsg ?>Edit Event</h4>
                            <?php }
                            else
                            { ?>
                                <h4><i class="icon-reorder"></i><?= $pmsg ?>Add Event</h4>
                            <?php } ?>
                            <div class="toolbar no-padding">
                                <h4>
                                    <i class="icon-reorder"></i><a href="eventslist.php">Event List</a>
                                </h4>
                                <div class="btn-group">
                                    <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                                </div>
                            </div>
                        </div>
                        
                <div class="widget-content">
                    <form class="form-horizontal row-border" enctype="multipart/form-data" method="post" id="frm_offer" name="frm_offer" onsubmit="return checkeventsDetails();
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
                            <label class="col-md-2 control-label">Activity Type : &nbsp;<span class="star-color">*</span></label>
                            <div class="col-md-10">
                                <select name="evtactTypeId" id="evtactTypeId" class="form-control required">
                                            <option value="">--Select--</option>
                                            <?php echo $gnrl->getOptions($evtactTypeId, "actTypeId", "actTypeName", "activitytype", "actStatus"); ?>
                                        </select>
                                <br>
                            </div>
                        </div>
		              
                        <div class="form-group">
                            <label class="col-md-2 control-label">Event Title: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-5"><input value="<?=($evtName!='')?$evtName:$_REQUEST['evtName']?>"  maxlength="100"   name="evtName" class="form-control required validatename" type="text" id="evtName"  />
                                   <br>
                                <span>(Maximum characters allowed 100)</span>
                                  </div>
				 
				</div>
                        
                          <div class="form-group">
                            <label class="col-md-2 control-label">Event  Date : &nbsp;<span class="star-color">*</span></label>
                            <div class="col-md-3" style="width: 20%; margin-right: 10%px;"><input value="<?php if(!empty($evtStartDate)){ echo date("d-M-Y",strtotime($evtStartDate)); }else {}?>"  maxlength="" name="evtStartDate" type="text" id="evtStartDate" class="form-control required" readonly="readonly"  />
                                <br>
                            </div>
                            <label class="col-md-2 control-label" style="width: 55px;"><img src="../admin/images/cal2.png"></label>
                             <label class="col-md-2 control-label">Event Time : &nbsp;<span class="star-color">*</span></label>
                              <div class="col-md-2" style="width: 20%; margin-right: 10%px;">
                                <select name="evtStartTime" id="evtStartTime" class="form-control required">
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
                                                     if (date("H:i:s", strtotime($evtStartTime)) === $fullhour2digit . ':00:00')
                                                        {echo ' SELECTED ';}
                                                     echo 'value="' . $fullhour2digit . ':00:00">' .  $parthour . '</option>';
                                                     echo '<option ';
                                                     if (date("H:i:s", strtotime($evtStartTime)) === $fullhour2digit  . ':30:00')
                                                        {echo ' SELECTED ';}
                                                     echo 'value="' . $fullhour2digit . ':30:00">' .  $parthalf . '</option>';
                                                }
                                                ?>
                                </select> 
                                <br>
                            </div>
                        </div>
                        
                        
                            
                        
                        <div class="form-group">
                        <label class="col-md-2 control-label"> Event Image :  <span class="star-color">*</span></label>
                        <div class="col-md-5">
                      <?php  if(isset($evtImage) && $_GET['cid']!=""  &&  $evtImage!="" && file_exists($Folder.$evtImage))
                                                {
                                                ?>
                                                <script type="text/javascript">
                                                 $(document).ready(function(){
                                                     $(".img_upload").hide();
                                                     $(".val_msg").hide();
                                                     $("#evtImage").hide();
                                                     $(".delete_icon").click(function(){
                                                            $(".delete_icon").hide();
                                                            $(".img_frm").hide();
                                                            $("#evtImage").show();
                                                            $(".val_msg").show();
                                                            $("#evtImage").addClass("required");
                                                     });
                                                      
                                                  });
                                                </script>
                                                <img class="img_frm" src="<?=$Folder.$evtImage?>" height="100px" width="100px">
                                               
                                                    <input value="<?=$evtImage?>"  maxlength="255" size="40"  name="evtImage"  type="file" id="evtImage" class="form-control <?php if(isset($evtImage) && $_GET['cid']!="" && $_REQUEST['script']='edit'){}else{ echo required; }  ?>" />
                                                    <?php $limitstart=$_REQUEST['limitstart'];
                                                    $pageno=$_REQUEST['pageno']; ?>
                                                    <img src="../admin/images/icon_delete_comment.png" class="delete_icon" />

                                                    <input type="hidden" name="evtImage" id="evtImage" value="<?=$evtImage?>" />
                                                    <span id="file_msg" style="color: red; font-style:italic"></span>
                                                    <img class="img_upload" src="../admin/images/indicator.gif">
                                                    <span class="val_msg">(Upload All type Image and Max size allowed to 2 MB)</span>
                                                    <br>
                                                    <?php 

                                                } 
                                                else
                                                {
                                                 ?>
                                                     
                                                 <input value=""  maxlength="255" size="40"  name="evtImage"  type="file" id="evtImage" class="form-control required" />
                                                
                                                

                                                <span id="file_msg" style="color: red; font-style:italic"></span>
                                                <img src="../admin/images/indicator.gif" class="img_upload">
                                                <span>(Upload All type Image and Max size allowed to 2 MB)</span>
                                                <br>
                                                <?php 

                                                }
                                                ?>
                                                
                                        <br>
                        </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Description : &nbsp;<span class="star-color">*</span></label>
                            <div class="col-md-10">
                                <textarea name="evtInfo" id="evtInfo" class="form-control" maxlength="4000"><?=$evtInfo?></textarea>
                                
                                <br>
                                <span>(Maximum characters allowed 4000)</span>
                            </div>
                        </div>


						
						<div class="form-group">
                            <label class="col-md-2 control-label">Location Name: &nbsp;</label>
				  <div class="col-md-10" id="us2">
                                      
                                      <input value="<?php echo $evtLocatioName ?>"  maxlength="100"   name="evtLocatioName" class="form-control validatename" type="text" id="evtLocatioName"  />
                                  <br>
                                <span>(Maximum characters allowed 100)</span>

                                  </div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">Location Address: &nbsp;</label>
				  <div class="col-md-5">
                                      <textarea name="evtAddress" id="evtAddress" class="form-control" maxlength="200"><?=$evtAddress?></textarea>
                                 <br>
                                <span>(Maximum characters allowed 255)</span>
                                  </div>
				 
				</div>
                             <div class="form-group">
                                    <label class="col-md-2 control-label">Location : &nbsp;</label>
                                    <div class="col-md-10">
                                        <img src="../admin/images/map_icon.png" alt="GetPosition" onclick="viewmap()">  Click To Get Position
            <!--                                <label for="locLatitude"><?//=$locLatitude?></label>, <label for="locLongitude"><?//=$locLongitude?></label>-->
                                    </div>
                                   
                                </div>
                                       <div class="form-group">
                                           <label class="col-md-2 control-label">Longitude : &nbsp;</label>
                                           <!--<div class="col-md-2"><input type="text" name="evtLongitude" id="evtLongitude" value="<?=($evtLongitude!='')?$evtLongitude:"-117.257767"; ?>" class="form-control" readonly></div>
                                     <label class="col-md-2 control-label"> Latitutde: &nbsp;</label>
                                     <div class="col-md-2"><input type="text" name="evtLatitude" id="evtLatitude" value="<?=($evtLatitude!='')?$evtLatitude:"32.842674"; ?>" class="form-control" readonly></div> -->
                                        <div class="col-md-2"><input type="text" name="evtLongitude" id="evtLongitude" value="<?=($evtLongitude!='')?$evtLongitude:""; ?>" class="form-control" readonly></div>
                                     <label class="col-md-2 control-label"> Latitutde: &nbsp;</label>
                                     <div class="col-md-2"><input type="text" name="evtLatitude" id="evtLatitude" value="<?=($evtLatitude!='')?$evtLatitude:""; ?>" class="form-control" readonly></div>
                                        <!--<input type="text" value="<?=$evtLatitude?>" name="locLatitude" id="locLatitude" maxlength="20" class="form-control number" >--> 
                                        <br>
                                    </div>
						
						<div class="form-group">
                            <label class="col-md-2 control-label">Activity Tags: &nbsp;<span class="star-color">*</span></label>
                            <div class="col-md-10"><input value="<?=$evtTags?>"  maxlength="500"   name="evtTags" class="form-control required" type="text" id="evtTags"  />
                                  <br>
                                <span>(Activity tags comma saprated Ex:Adult,Maccabi,Majane  )</span>

                                  </div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">Status: &nbsp;</label>
				  <div class="col-md-10"><input type="checkbox" name="evtStatus" id="evtStatus" value='Active' <?php if (isset($evtStatus) && $evtStatus == 'Active'){ echo "checked"; } ?>></div>
				 
				</div>
						
			<div class="btn-toolbar">
                              <input type="submit" name="Submit" value="<?= $mode ?>" class="btn btn-warning" data-loading-text="button"  />
                             <input type="button" name="Cancel" value="Cancel" onClick="window.location.href = 'eventslist.php'" class="btn" data-loading-text="button">
                    </div>
		</form>
		 </div>
        </div>
        </div>
    </div>
</div>