<?php	
		require('../includes/app_config.php');
		require_once('inc/top.php');
		require_once('../classes/class.eventattendees.php');
               
		if(!isset($_SESSION['ad_userid']) && empty($_SESSION['ad_userid']))
		{
			header("location:index.php");
			exit;
		}
		
$error='';
$eventattendees = new eventattendees();
if(isset($_REQUEST['act']) && $_REQUEST['act']=='del')
{
	//Remove Pages
	$eventattendees->delete(base64_decode($_REQUEST['cid']));
	header("Location: eventattendeeslist.php?msg=" . base64_encode('Record has been successfully deleted.') . "&msgstyle=" . base64_encode("success_msg"));
}

//Count No. Of Records
$sqlCntPages="SELECT count(*) as Total FROM eventattendees";
$resCntPages=$dclass->query($sqlCntPages);
while($row=$dclass->fetchArray($resCntPages))
{
	$nototal = $row['Total'];
}

if (isset($_REQUEST['pageno'])){
	$limit = $_REQUEST['pageno'];
}
else{
	 $limit = 25;
}

$form = 'frmeventattendeesPageList';
					
$sk='';
$so='';
$sk=trim($_REQUEST['sk']);
$sd=date("Y-m-d",strtotime($_REQUEST['sd']));
$so=trim($_REQUEST['so']);
$sql="SELECT `evtName`,`memName`,`eadId`, `eadEvtIdi`, `eadMemId` FROM `eventattendees` ,`members` ,`events` WHERE evtId=eadEvtIdi AND memId=eadMemId AND memId='". base64_decode($_REQUEST['cid'])."'";
$wh='1';
$sk1=$sk;
$sk=str_replace('%','^^',$sk);
if($so=='Active' || $so=='Inactive')
{		
        $sqlcond=" AND evtStatus = '".$so."'";
}
if($sk!='' || $so!='')
{
    if($so=='evtName')
    {     
      $sql .=" AND evtName LIKE '%".$sk."%'";
    }
    
    if($so=='evtCreatedDate')
    {    
        $sql .=" AND evtCreatedDate LIKE '%".$sd."%'";
    } 
}

if($_REQUEST['search']!='' && isset($_REQUEST['search']))
{
    if($sd=='1970-01-01' && $sk=='')
    {
        $error="Please search keyword";
    }
    if($so=='' && $so!='Active' && $so!='Inactive' && $sd=='1970-01-01')
    {
            $error="Please select search keyword";
    }
}
$sqlsearch = $sql.$sqlcond." GROUP BY eadId DESC ";
$sql1 = mysql_query($sqlsearch);
$nototal = mysql_num_rows($sql1);
    


if (isset($_REQUEST['limitstart'])){
	 $limitstart = $_REQUEST['limitstart'];
}
else{
	 $limitstart = 0;
}
$pagen = new vmPageNav($nototal, $limitstart, $limit, $form );
?>	
<?php
        if (isset($_REQUEST['act']))
					{
					 $msgstyle=success_msg;					
					}
					else
					{
					 $msgstyle=error_msg;
					}
					 if(isset($_REQUEST['msg']) && $_REQUEST['msg']!='')
					 {
						$error=base64_decode($_REQUEST['msg']);
						$msgstyle=base64_decode($_REQUEST['msgstyle']);
					 }
					 if($error!="")
					 {
					 ?>
						<table style="margin-left: 24px; margin-bottom: 25px;" id="msg">
						<tr>
                                                    <td  align="left"   class="<?=$msgstyle?>">
									<?=$error;?></td>
						</tr>
                                                <tr>
							<td ></td>
						</tr>
                                                </table>
				 <?php } ?>
                    
<script type="text/javascript">

jQuerX(function() {
    jQuerX( "#Date1" ).datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat:'dd-mm-yy'
    });
});

jQuerX(function(){
    jQuerX("#keywords").tablesorter({
        sortList: [[0,0]],
        headers: {
             0: { sorter: false },
             4: { sorter: "shortDate" }
        }
    });
});

function ChangeStatus(id,status)
{
    
    // field is the name of the status field in database
    //where is unique id field of the table
     var data="Id="+id+"&status="+status+"&field=catStatus&where=catId&table=campuscategory";    
//     alert(data);
     var path="../userstatuschange.php";
     $.ajax({
        type: "POST",
        url: path,
        data: data,
        success: function(data)
        {
//            alert(data);
            $("#updtMsg").css("color", "green");
            $("#updtMsg").html('');               
            $("#updtMsg").html(data);  
            
            timedMsg();
            //window.location.reload();
        }
    }) 
}

function timedMsg()
{
   var t=setTimeout("document.getElementById('updtMsg').style.display='none';",4000);
}

</script>


<form name="frmeventattendeesPageList" id="frmeventattendeesPageList" method="post" action="eventattendeeslist.php?view&cid=<?=$_REQUEST['cid']?>&limitstart=<?=$limitstart?>&pageno=<?=$limit?>'">
<div class="container">
    <input type="hidden" id="limit" name="limit" value="<?=$limit?>" /> 
    
    <div class="page-header">
        <div id="updtMsg"></div>
    </div>
    <!--=== Managed Tables ===-->
    
    <!--=== Normal ===-->
    <div class="row">
        <div class="col-md-12">
            <div class="widget box">
                
                <div class="widget-header">
                    <h4><i class="icon-reorder"></i>Event Attendees List</h4>
                    <div class="toolbar no-padding">
                        <h4>
                            <i class="icon-reorder"></i><a href="memberslist.php?script=view&cid=<?= $_REQUEST['cid']?>">Back</a>
                        </h4>
                        <div class="btn-group">
                            <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                        </div>
                    </div>
                </div>
                
                <div class="widget-content">                                           
                    <label class="label_15"></label>
                    <input type="text" class="textsearch" value="<?=$_REQUEST['sk']?>" size="25" name="sk" >
                    <input type="text" class="textsearch" value="<?=$_REQUEST['sd']?>" size="25" name="sd" id="Date1" placeholder="Select Date">
                    <select  name="so" id="so" class="optionsearch">
                        <!--<option value="">-----Select-----</option>-->   
                        <option value="evtName" <?=($so=='evtName')?"selected='selected'":""?>>Event Name</option>
                        
                        <option value="catCreatedDate" <?=($so=='catCreatedDate')?"selected='selected'":""?>>Created date</option>
                        <option value="Active" <?=($so=='Active')?"selected='selected'":""?>>Active</option>
                        <option value="Inactive" <?=($so=='Inactive')?"selected='selected'":""?>>Inactive</option>
                    </select>
                    
                    <input type="submit" class="submitsearch" name="search" value="Search"  onclick="window.location.href='eventattendeeslist.php?script=view&cid=<?=$_REQUEST['cid']?>&limitstart=<?=$limitstart?>&pageno=<?=$limit?>'" />
                    <input type="button" name="reset" value="Reset"  class="submitreset"    onclick="window.location.href='eventattendeeslist.php?script=view&cid=<?=$_REQUEST['cid']?>&limitstart=<?=$limitstart?>&pageno=<?=$limit?>'" /> 
                    
                    <div class="scroll">
                       <div>
                    <span style="color: white;font-weight: bold; background-color: grey">Member Name :<?php echo $dclass->getName("memName",'members','memId',base64_decode($_REQUEST['cid']))?></span> 
                    
                    </div>
                       
                    <table class="table table-striped table-bordered table-hover table-checkable" id="keywords">
                        <thead>
                            <tr>
                                <th width="20">No.</th>
                                <th width="20">Event Name.</th>
                                
                                
                                 
                                <th width="85">Delete</th>
                                </tr>
                        </thead>
                        <tbody>
                        <?php
                        
                        $numPages=0;
                        //Get table Details
                        $sqlPages=$sqlsearch."LIMIT $limitstart,$limit" ;
                        $resPages=$dclass->query($sqlPages);
                        if($resPages)
                            $numPages = mysql_num_rows($resPages);
                        $cnt=$limitstart;
                        
                        while($row=$dclass->fetchArray($resPages))
                            {
                            
                                $eadId =$row['eadId'];
                                $evtName= $row['evtName'];
                                $cnt++;	
                                
                       ?>	
                            <tr>
                                <td class="align-center"><?=$cnt?></td>
                                <td class="align-center"><?=$evtName?></td>
                                    
                                <td class="align-center">
                                    <span class="btn-group">
                                    <a href="eventattendeeslist.php?cid=<?=base64_encode($eadId)?>&amp;act=del&limitstart=<?=$limitstart?>&pageno=<?=$limit?>" class="bs-tooltip edidel" onclick="return confirm('Are you sure you want to delete this record?');"title="Delete" class="btn btn-xs"><i class="icon-trash"></i></a>
                                </span>
                            </td>
                            </tr>
                            <?php
                                }
                                if($numPages<=0)
                                {
                            ?>	
                            <tr>
                                <td colspan="12"  class="error_msg">
                                    <?php echo ('No record found!');	?>
                                </td>
                            </tr>	 
                            <?php		
                                }
                                ?>	
                        </tbody>
                    </table>
                    </div>
                    
                    <div style="text-align:center">
                    <?php
                    if($numPages>0)
                        {
                        $pagen->writePagesLinks();
                        echo "<br>";
                        $pagen->writeLimitBox();                                                                                    
                        }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Normal -->
    
    <!--=== no-padding ===-->
    <div class="row">
        
    </div>
    <!-- /no-padding -->
    <!--=== no-padding and table-tabletools ===-->
    <div class="row"></div>
    <!-- /no-padding and table-tabletools -->
    <!--=== no-padding and table-colvis ===-->
    <div class="row"></div>
    <!-- /no-padding and table-colvis -->
    <!--=== Horizontal Scrolling ===-->
    <div class="row"></div>
    <!-- /Normal -->
    <!-- /Page Content -->
</div>
</form>


	