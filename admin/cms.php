<?php
                require('../includes/app_config.php');
                require_once('inc/top.php');
                require_once('../classes/class.cms.php');
		if(!isset($_SESSION['ad_userid']) && empty($_SESSION['ad_userid']))
		{
			header("location:index.php");
			exit;
		}
		
		//LOOP
		$cmsName = '';
$cmsContents = '';
$cmsStatus = '';
$cmsIsSystemPage = '';
$cmsCreatedDate = '';

		$error = '';
		
		
		$cms = new cms();
		
		if (isset($_REQUEST['Submit']))
		{
			
 $cmsContents = mysql_real_escape_string($_REQUEST['cmsContents']);



    if ($cmsContents == '')

    {

        $error = 'Content Field Is Madantory';

    }

			 
			/*Assign to local varriable*/
            
                        $cmsName = $_REQUEST['cmsName'];
$cmsContents = strip_tags($_REQUEST['cmsContents']);
$cmsStatus = $_REQUEST['cmsStatus'];
$cmsIsSystemPage = $_REQUEST['cmsIsSystemPage'];
$cmsCreatedDate = date("Y-m-d H:i:s");
	
			
                        /*Assing to Class*/
                        
			$cms->cmsName    = $cmsName;
$cms->cmsContents    = $cmsContents;
$cms->cmsStatus    = $cmsStatus;
$cms->cmsIsSystemPage    = $cmsIsSystemPage;
$cms->cmsCreatedDate    = $cmsCreatedDate;
 
			
			if ($_REQUEST['Submit'] == 'Submit')
			{
                            if ($error=='') { 
                                $ret=$cms->checkduplicate($_REQUEST['cmsName'],0);
                                if($ret)
                                {
                                    $error = 'Page already exists';
                                }
                            }
                            if ($error == '')
                            {					
                                    //Add New Page		   
                                    $cms->insert();					
                                    //redirect page cmslist.php	
                                    header("location:cmslist.php?msg=" . base64_encode('Record has been successfully inserted.') . "&msgstyle=" . base64_encode("success_msg"));
                                    exit;
                            }
			}
			else if ($_REQUEST['Submit'] == 'Update')
			{
				$cmsId = base64_decode($_REQUEST['cid']);
				$cms->cmsId = $cmsId; 				
				if ($error == '')
                            {
				//Check Page Name
                                $ret = $cms->checkduplicate($cms->cmsName,$cmsId);
                                if ($ret)
                                {
                                        $error = 'Page already exists';
                                }
                            }
			if ($error == '')
                            {
                                //Update Page Data
                                $cms->update($cmsId); 	
                                header("location:cmslist.php?limitstart=" . $_REQUEST['limitstart'] . "&pageno=" . $_REQUEST['pageno'] . "&msg=" . base64_encode('Record has been successfully updated.') . "&msgstyle=" . base64_encode("success_msg")); 
                            }
			}
		}
		
		if (isset($_REQUEST['cid']) && trim($_REQUEST['cid']) != '')
		{
			//Get cms Data
			$cms->select(base64_decode($_REQUEST['cid']));
			/*Set Local Varriables*/
			$cmsName    = $cms->cmsName;
$cmsContents    = $cms->cmsContents;
$cmsStatus    = $cms->cmsStatus;
$cmsIsSystemPage    = $cms->cmsIsSystemPage;
$cmsCreatedDate    = $cms->cmsCreatedDate;

			
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
        
         var oFCKeditor = new FCKeditor( 'cmsContents' ) ;
    oFCKeditor.BasePath = "fckeditor/" ;
    oFCKeditor.ReplaceTextarea() ;
        
        });
        </script><div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="widget box">
                                    <div class="widget-header">
                            <?php if (isset($_REQUEST['cid']))
                            { ?>
                                <h4><i class="icon-reorder"></i><?= $pmsg ?>Edit CMS</h4>
                            <?php }
                            else
                            { ?>
                                <h4><i class="icon-reorder"></i><?= $pmsg ?>Add CMS</h4>
                            <?php } ?>
                            <div class="toolbar no-padding">
                                <h4>
                                    <i class="icon-reorder"></i><a href="cmslist.php">CMS List</a>
                                </h4>
                                <div class="btn-group">
                                    <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                                </div>
                            </div>
                        </div>
                        
                <div class="widget-content">
                    <form class="form-horizontal row-border" enctype="multipart/form-data" method="post" id="frm_offer" name="frm_offer" onsubmit="return checkcmsDetails();
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
				  <div class="col-md-10"><input value="<?=$cmsName?>"  maxlength="50"   name="cmsName" class="form-control required" type="text" id="cmsName"  />
                                   <br>
                                <span>(Maximum characters allowed 100)</span>
                                  </div>
				 
				</div>
						<div class="form-group">
                            <label class="col-md-2 control-label">Contents: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><textarea name="cmsContents" id="cmsContents" class="form-control"><?=$cmsContents?></textarea>
                                   <br>
                                <span>(Maximum characters allowed 4000)</span>
                                  </div>
				 
				</div>
                                                <div class="form-group">
                            <label class="col-md-2 control-label">Is System Page: &nbsp;</label>
				  <div class="col-md-10">
                                      <input type="radio" name="cmsIsSystemPage" id="cmsIsSystemPage" value='Yes' <? if (isset($cmsIsSystemPage) && $cmsIsSystemPage == 'Yes'){ echo "checked"; } ?>>Yes
                                      <input type="radio" name="cmsIsSystemPage" id="cmsIsSystemPage" value='No' <? if (isset($cmsIsSystemPage) && $cmsIsSystemPage == 'No'){ echo "checked"; } ?>>No
                                  </div>
				 
				</div>
                                                
						<div class="form-group">
                            <label class="col-md-2 control-label">Status: &nbsp;<span class="star-color">*</span></label>
				  <div class="col-md-10"><input type="checkbox" name="cmsStatus" id="cmsStatus" value='Active' <? if (isset($cmsStatus) && $cmsStatus == 'Active'){ echo "checked"; } ?>></div>
				 
				</div>
						
			<div class="btn-toolbar">
                            <input type="submit" name="Submit" value="<?= $mode ?>" class="btn btn-warning" data-loading-text="button"  />
                            <input type="button" name="Cancel" value="Cancel" onClick="window.location.href = 'cmslist.php'" class="btn" data-loading-text="button">
                    </div>
		</form>
		 </div>
        </div>
        </div>
    </div>
</div>