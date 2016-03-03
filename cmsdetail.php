<?php 
require_once('includes/app_config.php');

$pageName=$_REQUEST['pagename'];
mb_internal_encoding('UTF-8'); 
			mysql_query("SET NAMES utf8");	
$sql="SELECT  * FROM cms  WHERE  cmsName='".$pageName."' AND cmsStatus<>'Inactive' ";
$rs=mysql_query($sql) or die(mysql_error());
$rw=mysql_fetch_array($rs);
@header("Accept-Encoding:*");
header('Content-Type: text/html;charset=utf-8');
@header("Content-Transfer-Encoding: binary");

/*Set Local Varriables*/
$arr=array();
$i=0;
$txtcontents=$rw['cmsContents'];
$arr[$i]['txtcontents']    = $txtcontents;
$arr[$i]['cmPageName']    =    $rw['cmsName'];
//$arr[$i]['cmPageHeading']    =    $rw['cmsH1Tag'];

//print_r($arr);
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=0.6, maximum-scale=1.0, user-scalable=yes"/>

<style>
.data
	{
	color:#000066;
	vertical-align:top;
	border-right:thin #000066 solid;
	}
.data2
	{
	color:#000066;
	vertical-align:top;
/*	border-right:thin #000066 solid;*/
	}
body{ padding:0px; margin:0px; font-family:Arial, Helvetica, sans-serif;font-size:12px;}

}
</style>
</head>
<body>
<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" >
<!--  <tr>
    <td style="padding:5px"><?=$arr[$i]['cmPageHeading']?></td>
  </tr> -->
    <tr>
    <td style="padding:5px"><?=$arr[$i]['txtcontents']?></td>
  </tr> 	  
</table>
</body>
</html>