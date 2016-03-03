<?php
                require('../includes/app_config.php');
                require_once('inc/top.php');
                require_once('../classes/class.tags.php');
		if(!isset($_SESSION['ad_userid']) && empty($_SESSION['ad_userid']))
		{
			header("location:index.php");
			exit;
		}
		
		//LOOP
		$tagName = '';
$tagInfo = '';
$tagStatus = '';

		$error = '';
		
		
		$tags = new tags();
		
		if (isset($_REQUEST['Submit']))
		{
		    if($_REQUEST['tagStatus']=='')
			{
			     $_REQUEST['tagStatus']= 'Inactive';	
			}
			 
			/*Assign to local varriable*/
            
$tagName = $_REQUEST['tagName'];
$tagInfo = strip_tags($_REQUEST['tagInfo']);
$tagStatus = $_REQUEST['tagStatus'];
	
			
                        /*Assing to Class*/
                        
$tags->tagName    = $tagName;
$tags->tagInfo    = $tagInfo;
$tags->tagStatus    = $tagStatus;
 
			
			if ($_REQUEST['Submit'] == 'Submit')
			{
                            
                            if ($error=='') { 
                                $ret=$tags->checkduplicate($_REQUEST['tagName'],0);
                                if($ret)
                                {
                                    $error = 'This tag is already exists';
                                }
                            }
                            if ($error == '')
                            {					
                                    //Add New Page		   
                                    
                                    
                               
                             $row= explode(',', $_REQUEST['tagName']);
                             for($i=0; $i<count($row); $i++)
                            {
                            $tags->tagName    = $row[$i];
                            $tags->insert();	
                            
                               $last=  mysql_insert_id();
                              $sql = "INSERT INTO `eventtags` (`etTagId` ) VALUES ($last)";
                            $result = mysql_query($sql);
                           }
                        
                        
                        
                        
                                    // $tags->insert();
                                    //redirect page tagslist.php	
                                    header("location:tagslist.php?msg=" . base64_encode('Record has been successfully inserted.') . "&msgstyle=" . base64_encode("success_msg"));
                                    exit;
                            }
			}
			else if ($_REQUEST['Submit'] == 'Update')
			{
				$tagId = base64_decode($_REQUEST['cid']);
				$tags->tagId = $tagId; 				
				if ($error == '')
                            {
				//Check Page Name
                                $ret = $tags->checkduplicate($_REQUEST['tagName'],$tagId);
                                if ($ret)
                                {
                                        $error = 'This tag is already exists';
                                }
                            }
			if ($error == '')
                            {
                                //Update Page Data
                                $tags->update($tagId); 	
                                header("location:tagslist.php?limitstart=" . $_REQUEST['limitstart'] . "&pageno=" . $_REQUEST['pageno'] . "&msg=" . base64_encode('Record has been successfully updated.') . "&msgstyle=" . base64_encode("success_msg")); 
                            }
			}
		}
		
		if (isset($_REQUEST['cid']) && trim($_REQUEST['cid']) != '')
		{
			//Get tags Data
			$tags->select(base64_decode($_REQUEST['cid']));
			/*Set Local Varriables*/
			$tagName    = $tags->tagName;
$tagInfo    = $tags->tagInfo;
$tagStatus    = $tags->tagStatus;

			
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
                                <h4><i class="icon-reorder"></i><?= $pmsg ?>Edit Activity Tag</h4>
                            <?php }
                            else
                            { ?>
                                <h4><i class="icon-reorder"></i><?= $pmsg ?>Add Activity Tag</h4>
                            <?php } ?>
                            <div class="toolbar no-padding">
                                <h4>
                                    <i class="icon-reorder"></i><a href="tagslist.php">Activity Tag List</a>
                                </h4>
                                <div class="btn-group">
                                    <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                                </div>
                            </div>
                        </div>
                        
                <div class="widget-content">
                    <form class="form-horizontal row-border" enctype="multipart/form-data" method="post" id="frm_offer" name="frm_offer" onsubmit="return checktagsDetails();
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
                            <label class="col-md-2 control-label">Activity Tag: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input value="<?=$tagName?>"  maxlength="100"   name="tagName" class="form-control required" type="text" id="tagName"  />
                                  <span>(Maximum characters allowed 100)</span>
                                  </div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">Description: &nbsp;<span class="star-color"></span></label>
				  <div class="col-md-10"><textarea name="tagInfo" id="tagInfo" class="form-control"><?=$tagInfo?></textarea>
                                  <br>
                                <span>(Maximum characters allowed 200)</span>

                                  </div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">Status: &nbsp;<span class="star-color"></span></label>
				  <div class="col-md-10"><input type="checkbox" name="tagStatus" id="tagStatus" value='Active' <? if (isset($tagStatus) && $tagStatus == 'Active'){ echo "checked"; } ?>></div>
				 
				</div>
			<div class="btn-toolbar">
                            <input type="submit" name="Submit" value="<?= $mode ?>" class="btn btn-warning" data-loading-text="button"  />
                            <input type="button" name="Cancel" value="Cancel" onClick="window.location.href = 'tagslist.php'" class="btn" data-loading-text="button">
                    </div>
		</form>
		 </div>
        </div>
        </div>
    </div>
</div>