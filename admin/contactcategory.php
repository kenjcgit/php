<?php
require('../includes/app_config.php');
require_once('inc/top.php');
require_once('../classes/class.contactcategory.php');
if (!isset($_SESSION['ad_userid']) && empty($_SESSION['ad_userid']))
{
    header("location:index.php");
    exit;
}

//LOOP
$conUmId = '';
$conStuId = '';
$conPrtId = '';
$conCatName = '';
$conCatInfo = '';
$conCatStatus = '';
$conCreatedDate = '';

$error = '';


$contactcategory = new contactcategory();
if (isset($_REQUEST['Submit']))
{

    if ($_POST['conCatStatus'] == '')
    {
        $_REQUEST['conCatStatus'] = 'Inactive';
    }

    /* Assign to local varriable */

    $conUmId = $_REQUEST['conUmId'];
    $conStuId = $_REQUEST['conStuId'];
    $conPrtId = $_REQUEST['conPrtId'];
    $conCatName = $_REQUEST['conCatName'];
    $conCatInfo = $_REQUEST['conCatInfo'];
    $conCatStatus = $_REQUEST['conCatStatus'];
    $conCreatedDate =date("Y-m-d H:i:s");


    //	

    /* Assing to Class */
    $contactcategory->conUmId = $conUmId;
    $contactcategory->conStuId = $conStuId;
    $contactcategory->conPrtId = $conPrtId;
    $contactcategory->conCatName = $conCatName;
    $contactcategory->conCatInfo = $conCatInfo;
    $contactcategory->conCatStatus = $conCatStatus;
    $contactcategory->conCreatedDate = $conCreatedDate;




    if ($_REQUEST['Submit'] == 'Submit')
    {
        if ($error == '')
        {
            $ret = $contactcategory->checkduplicate($_REQUEST['conCatName'], 0);
            if ($ret)
            {
                $error = "Category already exists";
            }
        }
        if ($error == '')
        {
            //Add New Page		   
            $contactcategory->insert();
            //redirect page contactcategorylist.php	
            header("location:contactcategorylist.php?msg=" . base64_encode("Category has been added successfully") . "&msgstyle=" . base64_encode("success_msg"));
            exit;
        }
    }
    else if ($_REQUEST['Submit'] == 'Update')
    {
        $conCatId = base64_decode($_REQUEST['cid']);
        $contactcategory->conCatId = $conCatId;
        if ($error == '')
        {
            //Check Page Name
            $ret = $contactcategory->checkduplicate($contactcategory->conCatName, $conCatId);
            if ($ret)
            {
                $error = "Category already exists";
            }
        }
        if ($error == '')
        {
            //Update Page Data
            $contactcategory->update($conCatId);
            header("location:contactcategorylist.php?limitstart=" . $_REQUEST['limitstart'] . "&pageno=" . $_REQUEST['pageno'] . "&msg=" . base64_encode("Category has been updated successfully") . "&msgstyle=" . base64_encode("success_msg"));
        }
    }
}

if (isset($_REQUEST['cid']) && trim($_REQUEST['cid']) != '')
{
    //Get contactcategory Data
    $contactcategory->select(base64_decode($_REQUEST['cid']));
    /* Set Local Varriables */
    $conUmId = $contactcategory->conUmId;
    $conStuId = $contactcategory->conStuId;
    $conPrtId = $contactcategory->conPrtId;
    $conCatName = $contactcategory->conCatName;
    $conCatInfo = $contactcategory->conCatInfo;
    $conCatStatus = $contactcategory->conCatStatus;
    $conCreatedDate = $contactcategory->conCreatedDate;
    
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
</script>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="widget box">

                <div class="widget-header">
                    <?php if (isset($_REQUEST['cid']))
                    { ?>
                        <h4><i class="icon-reorder"></i><?= $pmsg ?>Edit Contact Category</h4>
                    <?php }
                    else
                    { ?>
                        <h4><i class="icon-reorder"></i><?= $pmsg ?>Add Contact Category</h4>
                    <?php } ?>
                    <div class="toolbar no-padding">
                        <h4>
                            <i class="icon-reorder"></i><a href="contactcategorylist.php">Contact Category List</a>
                        </h4>
                        <div class="btn-group">
                            <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                        </div>
                    </div>
                </div>



                <div class="widget-content">
                    <form class="form-horizontal row-border" enctype="multipart/form-data" method="post" id="frm_offer" name="frm_offer" onsubmit="return checkcontactcategoryDetails();
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

                      <?php /*  <div class="form-group">
                            <label class="col-md-2 control-label">User : &nbsp;<span class="star-color">*</span></label>
                            <div class="col-md-10">
                                <select name="conUmId" id="conUmId" class="form-control required">
                                    <option value="">--User--</option>
                                    <?php
                                    echo $contactcategory->getUser($conUmId)
                                    ?>
                                </select>
                                <br>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Student : &nbsp;<span class="star-color">*</span></label>
                            <div class="col-md-10">
                                <select name="conStuId" id="conStuId" class="form-control required">
                                    <option value="">--Student--</option>
                                    <?php
                                    echo $contactcategory->getStudent($conStuId)
                                    ?>
                                </select>
                                <br>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Parent : &nbsp;<span class="star-color">*</span></label>
                            <div class="col-md-10">
                                <select name="conPrtId" id="conPrtId" class="form-control required">
                                    <option value="">--Parent--</option>
                                    <?php
                                    echo $contactcategory->getParent($conPrtId)
                                    ?>
                                </select>
                                <br>
                            </div>
                        </div> */ ?>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Category Name : &nbsp;<span class="star-color">*</span></label>
                            <div class="col-md-10">
                                <input type="text" name="conCatName" id="conCatName" value="<?=$conCatName?>" class="form-control required basicspecialchar">
                                <br>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">information : &nbsp;</label>
                            <div class="col-md-10">
                                <textarea name="conCatInfo" id="conCatInfo" class="form-control" maxlength="200"><?=$conCatInfo?></textarea>
                                <span>(Max. 200 Characters.)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Status : &nbsp;<span class="star-color">*</span></label>
                            <div class="col-md-10">
                                <select name="conCatStatus" id="conCatStatus" class="form-control">
                                    <option value="Active" <?php if($conCatStatus=='Active'){ echo "selected"; } ?>>Active</option>
                                    <option value="Inactive" <?php if($conCatStatus=='Inactive'){ echo "selected"; } ?>>Inactive</option>
                                    <option value="Approved" <?php if($conCatStatus=='Approved'){ echo "selected"; } ?>>Approved</option>
                                    <option value="Not Approved" <?php if($conCatStatus=='Not Approved'){ echo "selected"; } ?>>Not Approved</option>
                                </select>
                                <br>
                            </div>
                        </div>

                        <div class="btn-toolbar">
                            <input type="submit" name="Submit" value="<?= $mode ?>" class="btn btn-warning" data-loading-text="button"  />
                            <input type="button" name="Cancel" value="Cancel" onClick="window.location.href = 'contactcategorylist.php'" class="btn" data-loading-text="button">

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>