<?php
if(isset($_REQUEST['q']) && $_REQUEST['q']!='')
    {
        
        $data=array();
        $sql = "SELECT umId, umFullName FROM usermaster WHERE umFullName LIKE '%".$_REQUEST['q']."%'";
        $result = @mysql_query($sql);
        echo $row = @mysql_fetch_object($result);
//        foreach($row as $field=>$val)
        echo $row->umFullName;
        
    }
    
    ?>