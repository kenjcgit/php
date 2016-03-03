<?php

   require('includes/app_config.php');
        
        $table=$_REQUEST['table'];
        $where = $_REQUEST['where'];
        $status=$_REQUEST['status'];
        $usrId=$_REQUEST['Id'];
        $field = $_REQUEST['field'];
        
        
        
        if(isset($table))
        {
            $sql="UPDATE $table SET $field='".$status."' WHERE $where=".$usrId;
            $rs=@mysql_query($sql);
            if($rs)
            {
                 echo "Record has been successfully updates";
            }
        } 
?>			
	 

	
		
		