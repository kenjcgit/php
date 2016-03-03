<?php
@session_start();
@ob_start();

error_reporting(0);

require_once('db_config.php');

/*$url_string=explode("/",$_SERVER["PHP_SELF"]);
if($_SESSION['logged']=='' && $url_string[2]!="index.php")
{
	header("Location:index.php");
}*/
if(strstr($_SERVER['PHP_SELF'],"admin"))
{
	require_once('../classes/database.class.php');
	$dclass = new database();
	require_once('../classes/paging.class.php');
	require_once('../classes/general.class.php');
	$gnrl = new general();
}
else
{	
	require_once('classes/database.class.php');
	$dclass = new database();
	require_once('classes/paging.class.php');	
	require_once('classes/general.class.php');
	$gnrl = new general();
}
?>