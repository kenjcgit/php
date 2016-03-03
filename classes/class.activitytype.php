
<?php
/*
*
* -------------------------------------------------------
* CLASSNAME:        activitytype
* CREATION DATE:    08.03.2015
* CLASS FILE:       class.activitytype.php
* FOR MYSQL TABLE:  activitytype
* FOR MYSQL DB:     pixme_kenjc
* -------------------------------------------------------
*
*/



// **********************
// CLASS DECLARATION
// **********************

class activitytype
{ // class : begin


// **********************
// ATTRIBUTE DECLARATION
// **********************

var $actTypeId;   // KEY ATTR. WITH AUTOINCREMENT

var $actTypeName;   // (normal Attribute)
var $actInfo;   // (normal Attribute)
var $actStatus;   // (normal Attribute)
// **********************
// CONSTRUCTOR METHOD
// **********************

function activitytype()
{
}


// **********************
// GETTER METHODS
// **********************


function getactTypeId()
{return $this->actTypeId; }
function getactTypeName()
{return $this->actTypeName; }
function getactInfo()
{return $this->actInfo; }
function getactStatus()
{return $this->actStatus; }
// **********************
// SETTER METHODS
// **********************


function setactTypeId($val)
{
$this->actTypeId =  $val;
}

function setactTypeName($val)
{
$this->actTypeName =  $val;
}

function setactInfo($val)
{
$this->actInfo =  $val;
}

function setactStatus($val)
{
$this->actStatus =  $val;
}

// **********************
// SELECT METHOD / LOAD
// **********************

function select($id)
{

$sql =  "SELECT * FROM activitytype WHERE actTypeId = $id;";
$result =  mysql_query($sql);
$row = mysql_fetch_object($result);

$this->actTypeId = $row->actTypeId;$this->actTypeName = $row->actTypeName;$this->actInfo = $row->actInfo;$this->actStatus = $row->actStatus;}
// **********************
// DELETE
// **********************

function delete($id)
{
$sql = "DELETE FROM activitytype WHERE actTypeId = $id;";
$result = mysql_query($sql);

}

// **********************
// INSERT
// **********************

function insert()
{
$this->actTypeId = ""; // clear key for autoincrement

$sql = "INSERT INTO activitytype ( actTypeName,actInfo,actStatus ) VALUES ( '$this->actTypeName','".mysql_real_escape_string($this->actInfo)."','$this->actStatus' )";
$result = mysql_query($sql);
$this->actTypeId = mysql_insert_id();

}

// **********************
// UPDATE
// **********************

function update($id)
{
$sql = " UPDATE activitytype SET  actTypeName = '$this->actTypeName',actInfo = '".mysql_real_escape_string($this->actInfo)."',actStatus = '$this->actStatus' WHERE actTypeId = $id ";
$result = mysql_query($sql);
}

// **********************
// Check Duplicate
// **********************
function checkduplicate($name,$id)
{
$sql = "SELECT actTypeId FROM activitytype WHERE actTypeName='$name' and actTypeId <> $id;";
$result = mysql_query($sql);
$row = mysql_fetch_object($result);

$this->actTypeId = $row->actTypeId;if($this->actTypeId > 0 )
	{
		return true;
	}}
} // class : end
?>
