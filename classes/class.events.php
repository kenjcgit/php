
<?php
/*
*
* -------------------------------------------------------
* CLASSNAME:        events
* CREATION DATE:    19.02.2015
* CLASS FILE:       class.events.php
* FOR MYSQL TABLE:  events
* FOR MYSQL DB:     pixme_kenjc
* -------------------------------------------------------
*
*/



// **********************
// CLASS DECLARATION
// **********************

class events
{ // class : begin


// **********************
// ATTRIBUTE DECLARATION
// **********************

var $evtId;   // KEY ATTR. WITH AUTOINCREMENT

var $evtactTypeId;   // (normal Attribute)
var $evtName;   // (normal Attribute)
var $evtStartDate;   // (normal Attribute)
var $evtEndDate;   // (normal Attribute)
var $evtStartTime;   // (normal Attribute)
var $evtEndTime;   // (normal Attribute)
var $evtInfo;   // (normal Attribute)
var $evtImage;   // (normal Attribute)
var $evtLocatioName;   // (normal Attribute)
var $evtAddress;   // (normal Attribute)
var $evtLatitude;   // (normal Attribute)
var $evtLongitude;   // (normal Attribute)
var $evtTags;   // (normal Attribute)
var $evtStatus;   // (normal Attribute)
var $evtCreatedDate;   // (normal Attribute)
// **********************
// CONSTRUCTOR METHOD
// **********************

function events()
{
}


// **********************
// GETTER METHODS
// **********************


function getevtId()
{return $this->evtId; }
function getevtactTypeId()
{return $this->evtactTypeId; }
function getevtName()
{return $this->evtName; }
function getevtStartDate()
{return $this->evtStartDate; }
function getevtEndDate()
{return $this->evtEndDate; }
function getevtStartTime()
{return $this->evtStartTime; }
function getevtEndTime()
{return $this->evtEndTime; }
function getevtInfo()
{return $this->evtInfo; }
function getevtImage()
{return $this->evtImage; }
function getevtLocatioName()
{return $this->evtLocatioName; }
function getevtAddress()
{return $this->evtAddress; }
function getevtLatitude()
{return $this->evtLatitude; }
function getevtLongitude()
{return $this->evtLongitude; }
function getevtTags()
{return $this->evtTags; }
function getevtStatus()
{return $this->evtStatus; }
function getevtCreatedDate()
{return $this->evtCreatedDate; }
// **********************
// SETTER METHODS
// **********************


function setevtId($val)
{
$this->evtId =  $val;
}

function setevtactTypeId($val)
{
$this->evtactTypeId =  $val;
}

function setevtName($val)
{
$this->evtName =  $val;
}

function setevtStartDate($val)
{
$this->evtStartDate =  $val;
}

function setevtEndDate($val)
{
$this->evtEndDate =  $val;
}

function setevtStartTime($val)
{
$this->evtStartTime =  $val;
}

function setevtEndTime($val)
{
$this->evtEndTime =  $val;
}

function setevtInfo($val)
{
$this->evtInfo =  $val;
}

function setevtImage($val)
{
$this->evtImage =  $val;
}

function setevtLocatioName($val)
{
$this->evtLocatioName =  $val;
}

function setevtAddress($val)
{
$this->evtAddress =  $val;
}

function setevtLatitude($val)
{
$this->evtLatitude =  $val;
}

function setevtLongitude($val)
{
$this->evtLongitude =  $val;
}

function setevtTags($val)
{
$this->evtTags =  $val;
}

function setevtStatus($val)
{
$this->evtStatus =  $val;
}

function setevtCreatedDate($val)
{
$this->evtCreatedDate =  $val;
}

// **********************
// SELECT METHOD / LOAD
// **********************

function select($id)
{

$sql =  "SELECT * FROM events WHERE evtId = $id;";
$result =  mysql_query($sql);
$row = mysql_fetch_object($result);

$this->evtId = $row->evtId;$this->evtactTypeId = $row->evtactTypeId;$this->evtName = $row->evtName;$this->evtStartDate = $row->evtStartDate;$this->evtEndDate = $row->evtEndDate;$this->evtStartTime = $row->evtStartTime;$this->evtEndTime = $row->evtEndTime;$this->evtInfo = $row->evtInfo;$this->evtImage = $row->evtImage;$this->evtLocatioName = $row->evtLocatioName;$this->evtAddress = $row->evtAddress;$this->evtLatitude = $row->evtLatitude;$this->evtLongitude = $row->evtLongitude;$this->evtTags = $row->evtTags;$this->evtStatus = $row->evtStatus;$this->evtCreatedDate = $row->evtCreatedDate;}
// **********************
// DELETE
// **********************

function delete($id)
{
$sql = "DELETE FROM events WHERE evtId = $id;";
$result = mysql_query($sql);

}

// **********************
// INSERT
// **********************

function insert()
{
$this->evtId = ""; // clear key for autoincrement

$sql = "INSERT INTO events ( evtactTypeId,evtName,evtStartDate,evtEndDate,evtStartTime,evtEndTime,evtInfo,evtImage,evtLocatioName,evtAddress,evtLatitude,evtLongitude,evtTags,evtStatus,evtCreatedDate ) VALUES ( '$this->evtactTypeId','".mysql_real_escape_string($this->evtName)."','$this->evtStartDate','$this->evtEndDate','$this->evtStartTime','$this->evtEndTime','".mysql_real_escape_string($this->evtInfo)."','$this->evtImage','".mysql_real_escape_string($this->evtLocatioName)."','$this->evtAddress','$this->evtLatitude','$this->evtLongitude','$this->evtTags','$this->evtStatus','$this->evtCreatedDate' )";
$result = mysql_query($sql);
$this->evtId = mysql_insert_id();

}

// **********************
// UPDATE
// **********************

function update($id)
{
$sql = " UPDATE events SET  evtactTypeId = '$this->evtactTypeId',evtName = '".mysql_real_escape_string($this->evtName)."',evtStartDate = '$this->evtStartDate',evtEndDate = '$this->evtEndDate',evtStartTime = '$this->evtStartTime',evtEndTime = '$this->evtEndTime',evtInfo = '".mysql_real_escape_string($this->evtInfo)."',evtImage = '$this->evtImage',evtLocatioName = '".mysql_real_escape_string($this->evtLocatioName)."',evtAddress = '$this->evtAddress',evtLatitude = '$this->evtLatitude',evtLongitude = '$this->evtLongitude',evtTags = '$this->evtTags',evtStatus = '$this->evtStatus',evtCreatedDate = '$this->evtCreatedDate' WHERE evtId = $id ";
$result = mysql_query($sql);
}

// **********************
// Check Duplicate
// **********************
function checkduplicate($name,$id)
{
$sql = "SELECT evtId FROM events WHERE evtName='$name' and evtId <> $id;";
$result = mysql_query($sql);
$row = mysql_fetch_object($result);

$this->evtId = $row->evtId;if($this->evtId > 0 )
	{
		return true;
	}
        
        }
} // class : end
?>
