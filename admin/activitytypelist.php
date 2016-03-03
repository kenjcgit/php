<?php	
		require('../includes/app_config.php');
		require_once('inc/top.php');
		require_once('../classes/class.activitytype.php');
		if(!isset($_SESSION['ad_userid']) && empty($_SESSION['ad_userid']))
		{
			header("location:index.php");
			exit;
		}
		
$error='';
$activitytype = new activitytype();
if(isset($_REQUEST['act']) && $_REQUEST['act']=='del')
{
	//Remove Pages
	$activitytype->delete(base64_decode($_REQUEST['cid']));
	header("Location: activitytypelist.php?msg=" . base64_encode('Record has been successfully deleted.') . "&msgstyle=" . base64_encode("success_msg"));
}

//Count No. Of Records
$sqlCntPages="SELECT count(*) as Total FROM activitytype";
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

$form = 'frmactivitytypePageList';
					
$sk='';
$so='';
$sk=trim($_REQUEST['sk']);
$sd=date("Y-m-d",strtotime($_REQUEST['sd']));
$so=trim($_REQUEST['so']);
$sql='SELECT * FROM activitytype WHERE 1 = 1 ' ;
$wh='1';
$sk1=$sk;
$sk=str_replace('%','^^',$sk);
    if($so=='Active' || $so=='Inactive')
{		
        $sqlcond=" AND actStatus = '".$so."'";
}
if($sk!='' || $so!='')
{
    if($so=='actTypeName')
    {     
        $sql .=" AND actTypeName LIKE '%".$sk."%'";
    }
    if($so=='actInfo')
    {     
        $sql .=" AND actInfo LIKE '%".$sk."%'";
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
$sqlsearch = $sql.$sqlcond." GROUP BY actTypeId DESC ";
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
     var data="Id="+id+"&status="+status+"&field=actStatus&where=actTypeId&table=activitytype";    
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


<form name="frmactivitytypePageList" id="frmactivitytypePageList" method="post" action="activitytypelist.php">
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
                    <h4><i class="icon-reorder"></i>Activity Type List</h4>
                    <div class="toolbar no-padding">
                        <h4>
                            <i class="icon-reorder"></i><a href="activitytype.php">Add Activity Type</a>
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
                        <option value="actTypeName" <?=($so=='actTypeName')?"selected='selected'":""?>>Activity Type</option>
                        <option value="actInfo" <?=($so=='actInfo')?"selected='selected'":""?>>Description</option>
                        <option value="Active" <?=($so=='Active')?"selected='selected'":""?>>Active</option>
                        <option value="Inactive" <?=($so=='Inactive')?"selected='selected'":""?>>Inactive</option>
                    </select>
                    
                    <input type="submit" class="submitsearch" name="search" value="Search" />
                    <input type="button" name="reset" value="Reset"  class="submitreset"    onclick="window.location.href='activitytypelist.php'" /> 
                    
                    <div class="scroll">
                    <table class="table table-striped table-bordered table-hover table-checkable" id="keywords">
                        <thead>
                            <tr>
                                <th width="20">No.</th>
                                <th width="20">Activity Type</th>
                                <th width="20">Description</th>
                                <th width="20">Status</th>
                                 <th width="80">Edit</th>
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
                            
                                $actTypeId =$row['actTypeId'];
			        $actTypeName =$row['actTypeName'];
                                 $actInfo =$row['actInfo'];
			        $actStatus =$row['actStatus'];
				if($actStatus == "Active" )
                                {
                                    $newstatus = "Inactive";
                                }
                                else { 
                                    $newstatus= "Active";
                                }
                                
                                $cnt++;	
                                
                       ?>	
                            <tr>
                                <td class="align-center"><?=$cnt?></td>
                                <td class="align-center"><?=$actTypeName?></td>
                                 <td class="align-center"><?=$actInfo?></td>
                                <td class="align-center"><input type="checkbox" <?php if(isset($actStatus) &&  $actStatus=='Active'){ echo "checked";  }  ?>  onclick="ChangeStatus('<?=$actTypeId?>','<?=$newstatus?>');" class="uniform"></td>
                                
                                    <td class="align-center">
                                    <span class="btn-group">
                                            <a href="activitytype.php?script=edit&cid=<?=base64_encode($actTypeId)?>&limitstart=<?=$limitstart?>&pageno=<?=$limit?>" class="bs-tooltip edidel" title="Edit" class="btn btn-xs"><i class="icon-pencil"></i></a>
                                    </span>
                                </td>
                                <td class="align-center">
                                    <span class="btn-group">
                                    <a href="activitytypelist.php?cid=<?=base64_encode($actTypeId)?>&amp;act=del&limitstart=<?=$limitstart?>&pageno=<?=$limit?>" class="bs-tooltip edidel" onclick="return confirm('Are you sure you want to delete this record?');"title="Delete" class="btn btn-xs"><i class="icon-trash"></i></a>
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


	