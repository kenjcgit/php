<?php 
//    echo json_encode(array("test"=>"hello"));
    include("includes/app_config.php");
    if(isset($_REQUEST['type']))
    {
        $type = $_REQUEST['type'];
        $id = $_REQUEST['id'];
        $data=array();
        $sql = "SELECT * FROM usermaster WHERE umUserType='$type' AND umId=$id";
        $result = @mysql_query($sql);
        while($row = @mysql_fetch_array($result)){
            foreach($row as $field => $val)
            {
                $data[$field] = $val;
            }
        }
        echo str_replace("\/","/",json_encode($data));
    }
    if(isset($_REQUEST['showPass']))
    {
        $id=$_REQUEST['showPass'];
        $sql = "SELECT umPassword FROM usermaster WHERE umId=$id";
        $result = @mysql_query($sql);
        $row = @mysql_fetch_array($result);
        echo base64_decode($row['umPassword']);
    }
//    if(isset($_REQUEST['search']) && $_REQUEST['search']!='')
//    {
//        $search = $_REQUEST['search'];
//        $idField = $_REQUEST['idField'];
//        $nameField = $_REQUEST['nameField'];
//        $table = $_REQUEST['table'];
//        $sql = "SELECT $idField, $nameField FROM $table WHERE $nameField LIKE '%$search%'";
//        $result = @mysql_query($sql);
//        while($row = @mysql_fetch_array($result))
//        {
//            echo $row['umId'].'|'.$row['umFullName'];
//        }
//    }
    
    
?>