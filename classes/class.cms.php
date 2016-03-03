
<?php
/*
*
* -------------------------------------------------------
* CLASSNAME:        cms
* CREATION DATE:    08.03.2015
* CLASS FILE:       class.cms.php
* FOR MYSQL TABLE:  cms
* FOR MYSQL DB:     pixme_kenjc
* -------------------------------------------------------
*
*/



// **********************
// CLASS DECLARATION
// **********************

class cms
{ // class : begin


// **********************
// ATTRIBUTE DECLARATION
// **********************

var $cmsId;   // KEY ATTR. WITH AUTOINCREMENT

var $cmsName;   // (normal Attribute)
var $cmsContents;   // (normal Attribute)
var $cmsStatus;   // (normal Attribute)
var $cmsIsSystemPage;   // (normal Attribute)
var $cmsCreatedDate;   // (normal Attribute)
// **********************
// CONSTRUCTOR METHOD
// **********************

function cms()
{
}


// **********************
// GETTER METHODS
// **********************


function getcmsId()
{return $this->cmsId; }
function getcmsName()
{return $this->cmsName; }
function getcmsContents()
{return $this->cmsContents; }
function getcmsStatus()
{return $this->cmsStatus; }
function getcmsIsSystemPage()
{return $this->cmsIsSystemPage; }
function getcmsCreatedDate()
{return $this->cmsCreatedDate; }
// **********************
// SETTER METHODS
// **********************


function setcmsId($val)
{
$this->cmsId =  $val;
}

function setcmsName($val)
{
$this->cmsName =  $val;
}

function setcmsContents($val)
{
$this->cmsContents =  $val;
}

function setcmsStatus($val)
{
$this->cmsStatus =  $val;
}

function setcmsIsSystemPage($val)
{
$this->cmsIsSystemPage =  $val;
}

function setcmsCreatedDate($val)
{
$this->cmsCreatedDate =  $val;
}

// **********************
// SELECT METHOD / LOAD
// **********************

function select($id)
{

$sql =  "SELECT * FROM cms WHERE cmsId = $id;";
$result =  mysql_query($sql);
$row = mysql_fetch_object($result);

$this->cmsId = $row->cmsId;$this->cmsName = $row->cmsName;$this->cmsContents = $row->cmsContents;$this->cmsStatus = $row->cmsStatus;$this->cmsIsSystemPage = $row->cmsIsSystemPage;$this->cmsCreatedDate = $row->cmsCreatedDate;}
// **********************
// DELETE
// **********************

function delete($id)
{
$sql = "DELETE FROM cms WHERE cmsId = $id;";
$result = mysql_query($sql);

}

// **********************
// INSERT
// **********************

function insert()
{
$this->cmsId = ""; // clear key for autoincrement

$sql = "INSERT INTO cms ( cmsName,cmsContents,cmsStatus,cmsIsSystemPage,cmsCreatedDate ) VALUES ( '$this->cmsName','".mysql_real_escape_string($this->cmsContents)."','$this->cmsStatus','$this->cmsIsSystemPage','$this->cmsCreatedDate' )";
$result = mysql_query($sql);
$this->cmsId = mysql_insert_id();

}

// **********************
// UPDATE
// **********************

function update($id)
{
$sql = " UPDATE cms SET  cmsName = '$this->cmsName',cmsContents = '".mysql_real_escape_string($this->cmsContents)."',cmsStatus = '$this->cmsStatus',cmsIsSystemPage = '$this->cmsIsSystemPage',cmsCreatedDate = '$this->cmsCreatedDate' WHERE cmsId = $id ";
$result = mysql_query($sql);
}

// **********************
// Check Duplicate
// **********************
function checkduplicate($name,$id)
{
$sql = "SELECT cmsId FROM cms WHERE cmsName='$name' and cmsId <> $id;";
$result = mysql_query($sql);
$row = mysql_fetch_object($result);

$this->cmsId = $row->cmsId;if($this->cmsId > 0 )
	{
		return true;
	}}
} // class : end
?>
