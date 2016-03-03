
<?php
/*
*
* -------------------------------------------------------
* CLASSNAME:        notimessages
* CREATION DATE:    12.03.2015
* CLASS FILE:       class.notimessages.php
* FOR MYSQL TABLE:  notimessages
* FOR MYSQL DB:     pixme_kenjc
* -------------------------------------------------------
*
*/



// **********************
// CLASS DECLARATION
// **********************

class notimessages
{ // class : begin


// **********************
// ATTRIBUTE DECLARATION
// **********************

var $ntmId;   // KEY ATTR. WITH AUTOINCREMENT

var $ntmType;   // (normal Attribute)
var $ntmTitle;   // (normal Attribute)
var $ntmEvtId;   // (normal Attribute)
var $ntmDescription;   // (normal Attribute)
var $ntmInterval;   // (normal Attribute)
var $ntmDate;   // (normal Attribute)
var $ntmTime;   // (normal Attribute)
var $ntmCategory;   // (normal Attribute)
var $ntmStatus;   // (normal Attribute)
var $ntmCreatedDate;   // (normal Attribute)
// **********************
// CONSTRUCTOR METHOD
// **********************

function notimessages()
{
}


// **********************
// GETTER METHODS
// **********************


function getntmId()
{return $this->ntmId; }
function getntmType()
{return $this->ntmType; }
function getntmTitle()
{return $this->ntmTitle; }
function getntmEvtId()
{return $this->ntmEvtId; }
function getntmDescription()
{return $this->ntmDescription; }
function getntmInterval()
{return $this->ntmInterval; }
function getntmDate()
{return $this->ntmDate; }
function getntmTime()
{return $this->ntmTime; }
function getntmCategory()
{return $this->ntmCategory; }
function getntmStatus()
{return $this->ntmStatus; }
function getntmCreatedDate()
{return $this->ntmCreatedDate; }
// **********************
// SETTER METHODS
// **********************


function setntmId($val)
{
$this->ntmId =  $val;
}

function setntmType($val)
{
$this->ntmType =  $val;
}

function setntmTitle($val)
{
$this->ntmTitle =  $val;
}

function setntmEvtId($val)
{
$this->ntmEvtId =  $val;
}

function setntmDescription($val)
{
$this->ntmDescription =  $val;
}

function setntmInterval($val)
{
$this->ntmInterval =  $val;
}

function setntmDate($val)
{
$this->ntmDate =  $val;
}

function setntmTime($val)
{
$this->ntmTime =  $val;
}

function setntmCategory($val)
{
$this->ntmCategory =  $val;
}

function setntmStatus($val)
{
$this->ntmStatus =  $val;
}

function setntmCreatedDate($val)
{
$this->ntmCreatedDate =  $val;
}

// **********************
// SELECT METHOD / LOAD
// **********************

function select($id)
{

$sql =  "SELECT * FROM notimessages WHERE ntmId = $id;";
$result =  mysql_query($sql);
$row = mysql_fetch_object($result);

$this->ntmId = $row->ntmId;$this->ntmType = $row->ntmType;$this->ntmTitle = $row->ntmTitle;$this->ntmEvtId = $row->ntmEvtId;$this->ntmDescription = $row->ntmDescription;$this->ntmInterval = $row->ntmInterval;$this->ntmDate = $row->ntmDate;$this->ntmTime = $row->ntmTime;$this->ntmCategory = $row->ntmCategory;$this->ntmStatus = $row->ntmStatus;$this->ntmCreatedDate = $row->ntmCreatedDate;}
// **********************
// DELETE
// **********************

function delete($id)
{
$sql = "DELETE FROM notimessages WHERE ntmId = $id;";
$result = mysql_query($sql);

}

// **********************
// INSERT
// **********************

function insert()
{
$this->ntmId = ""; // clear key for autoincrement

$sql = "INSERT INTO notimessages ( ntmType,ntmTitle,ntmEvtId,ntmDescription,ntmInterval,ntmDate,ntmTime,ntmCategory,ntmStatus,ntmCreatedDate ) VALUES ( '$this->ntmType','$this->ntmTitle','$this->ntmEvtId','$this->ntmDescription','$this->ntmInterval','$this->ntmDate','$this->ntmTime','$this->ntmCategory','$this->ntmStatus','$this->ntmCreatedDate' )";
$result = mysql_query($sql);
$this->ntmId = mysql_insert_id();

}

// **********************
// UPDATE
// **********************

function update($id)
{
$sql = " UPDATE notimessages SET  ntmType = '$this->ntmType',ntmTitle = '$this->ntmTitle',ntmEvtId = '$this->ntmEvtId',ntmDescription = '$this->ntmDescription',ntmInterval = '$this->ntmInterval',ntmDate = '$this->ntmDate',ntmTime = '$this->ntmTime',ntmCategory = '$this->ntmCategory',ntmStatus = '$this->ntmStatus',ntmCreatedDate = '$this->ntmCreatedDate' WHERE ntmId = $id ";
$result = mysql_query($sql);
}

// **********************
// Check Duplicate
// **********************
function checkduplicate($name,$id)
{
$sql = "SELECT ntmId FROM notimessages WHERE ntmTitle='$name' and ntmId <> $id;";
$result = mysql_query($sql);
$row = mysql_fetch_object($result);

$this->ntmId = $row->ntmId;if($this->ntmId > 0 )
	{
		return true;
	}}
} // class : end
?>
