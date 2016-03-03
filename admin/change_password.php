<?php

require('../includes/app_config.php');


require_once('inc/top.php');
if(!isset($_SESSION['ad_userid']) && empty($_SESSION['ad_userid']))
{
    header("location:index.php");
    exit;
}

?>


<?php

$error = "";


if(isset($_REQUEST['Submit']))
{


    $old_pass = base64_encode($_REQUEST['old_password']);
    $upass    = $_REQUEST['pass'];

    $ucpass = $_REQUEST['cpass'];




    if($old_pass == "")
    {


        $error = 'Please enter old password';
    }
    elseif($upass == "")
    {


        $error = 'Please enter new password';
    }
    elseif($ucpass == "")
    {


        $error = 'Please enter confirm password';
    }
    elseif($upass != $ucpass)
    {


        $error = 'Password and Confirm password not matching';
    } if($old_pass != "")
    {


        //"SELECT ad_password FROM tbladmin WHERE intid= '".$_SESSION['intid']."'";
//            
        //echo "SELECT admPassword FROM adminusers WHERE admId= '".$_SESSION['ad_userid']."'"; die;


        $sql = $dclass->query("SELECT admPassword FROM adminusers WHERE admId= '" . $_SESSION['ad_userid'] . "'");
        //$row = mysql_fetch_array($sql);


        $row = $dclass->fetchArray($sql);


        $pwd = $row['admPassword'];


        if($error == '')
        {


            if($pwd != $old_pass)
            {


                $error = 'Old password Not matching With database';
            }
            else
            {


                if($error == "")
                {


                    //---check username and password is valid or not------//
//
                    //echo "UPDATE adminusers SET password='".$upass."' WHERE userId='".$_SESSION['userId']."'"; die;


                    $sql = $dclass->query("UPDATE adminusers SET admPassword='" . base64_encode($upass) . "' WHERE admId='" . $_SESSION['ad_userid'] . "'");



                    if($sql)
                    {
                        //$msg= base64_encode("Password Changed Successfully.##Success##");
//$gnrl->redirectTo("settings.html?msg=$msg");
                        header("location:home.php?msg=" . base64_encode('Password Changed Successfully.') . "&msgstyle=" . base64_encode("success_msg"));
                    }
                }
            }
        }
    }
    //echo $error;
}

?>




<!-----------------------------------------------------content starts----------------------------------------------------------------------->    


<script type="text/javascript">

      
    jQuerX(document).ready(function() 
    {

      
        jQuerX("#frm_gallery").validate({

          
            messages: {

        
                cpass: {

            
                    equalTo: "Please enter the same password as above"

        
                }

    
            }

      
        });
	

    }); 


</script>
<style>


    .error{


        color : #F00;


    }
</style>
<script language="javascript" type="text/javascript" src="js/formValidation.js">
</script>


<div class="container">
    <!-- Breadcrumbs line -->
    <!-- /Breadcrumbs line -->	
    <!--=== Page Header ===-->	<div class="page-header">
        <!-- Page Stats -->



        <!-- /Page Stats -->

    </div>

    <!-- /Page Header -->



    <!--=== Page Content ===-->

    <!--=== Managed Tables ===-->



    <!--=== Normal ===-->

    <div class="row">

        <div class="col-md-12">

            <div class="widget box">

                <div class="widget-header">

                    <h4><i class="icon-reorder"></i>Change Password</h4>

                    <div class="toolbar no-padding">

                        <div class="btn-group">

                            <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>

                        </div>

                    </div>

                </div>

                <div class="widget-content">

                    <form class="form-horizontal row-border" enctype="multipart/form-data" method="post" id="frm_gallery" name="frm_gallery" onsubmit="return checkroomDetails(); return false;" >

                        <?php

                        if(isset($_REQUEST['act']))
                        {

                            $msgstyle = "success_msg";
                        }
                        else
                        {

                            $msgstyle = "error_msg";
                        }

                        if(isset($_REQUEST['msg']) && $_REQUEST['msg'] != '')
                        {

                            $error = base64_decode($_REQUEST['msg']);

                            $msgstyle = base64_decode($_REQUEST['msgstyle']);
                        }

                        if($error != "")
                        {

                            ?>

                            <table style="margin-left: 24px; margin-bottom: 25px;">

                                <tr>

                                    <td  align="left"  class="<?= $msgstyle ?>">

                            <?= $error; ?></td>

                                </tr>

                                <tr>

                                    <td></td>

                                </tr>

                            </table>

    <?php

}

?>





                        <div class="form-group">

                            <label class="col-md-2 control-label"></label>

                            <div align="right" class="col-md-10"><span class="star-color">*</span> Denotes required field</div>

                        </div>



                        <!--                                                      <div class="form-group">
                        
                                                            <label class="col-md-2 control-label">Hotel :</label>
                        
                                                                <div class="col-md-10"><input value="<?= $hrHotelId ?>" maxlength="50" name="hrHotelId" type="text" id="hrHotelId" class="form-control"  /></div>
                        
                                                          </div>-->





                        <div class="form-group">

                            <label class="col-md-2 control-label">Old Password : &nbsp<span class="star-color">*</span></label>

                            <div class="col-md-10"><input value="<?= htmlentities($old_password) ?>"  maxlength="10"   name="old_password"  type="password" id="old_password" class="form-control required"  />

                                <br><span id="spanhrFaciltiy" style="display:none" class="error_msg"></span>

                            </div>

                        </div>



                        <div class="form-group">

                            <label class="col-md-2 control-label">New Password : &nbsp <span class="star-color">*</span></label>

                            <div class="col-md-10"><input value="<?= htmlentities($pass) ?>"  maxlength="10"   name="pass"  type="password" id="pass" class="form-control required"  />

                                <br><span id="spanhrFaciltiy" style="display:none" class="error_msg"></span>

                            </div>

                        </div>

                        <div class="form-group">

                            <label class="col-md-2 control-label">Confirm Password :&nbsp <span class="star-color">*</span></label>

                            <div class="col-md-10"><input value="<?= htmlentities($cpass) ?>"  maxlength="10"   name="cpass"  type="password" equalTo="#pass" id="cpass" class="form-control required"  />

                                <br><span id="spanhrFaciltiy" style="display:none" class="error_msg"></span>

                            </div>

                        </div>









                        <div class="btn-toolbar">

                            <input type="submit" name="Submit" value="Submit" class="btn btn-warning" data-loading-text="button"  />

                            <input type="button" name="Cancel" value="Cancel" onClick="window.location.href='home.php'" class="btn" data-loading-text="button">



                        </div>





                    </form>



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







<!-----------------------------------------------------content ends----------------------------------------------------------------------->                            









<div class="row">

    <!--=== Calendar ===-->

    <!-- /.col-md-6 -->

    <!-- /Calendar -->



    <!--=== Feeds (with Tabs) ===-->

    <!-- /.col-md-6 -->

    <!-- /Feeds (with Tabs) -->

</div> <!-- /.row -->

<!-- /Page Content -->

</div>

<!-- /.container -->



</div>