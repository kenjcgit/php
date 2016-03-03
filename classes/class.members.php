
<?php
/*
*
* -------------------------------------------------------
* CLASSNAME:        members
* CREATION DATE:    09.03.2015
* CLASS FILE:       class.members.php
* FOR MYSQL TABLE:  members
* FOR MYSQL DB:     pixme_kenjc
* -------------------------------------------------------
*
*/



// **********************
// CLASS DECLARATION
// **********************

class members
{ // class : begin


// **********************
// ATTRIBUTE DECLARATION
// **********************

var $memId;   // KEY ATTR. WITH AUTOINCREMENT

var $memName;   // (normal Attribute)
var $memMobile;   // (normal Attribute)
var $memEmail;   // (normal Attribute)
var $memDob;   // (normal Attribute)
var $memGender;   // (normal Attribute)
var $memStatus;   // (normal Attribute)
var $memDeviceType;   // (normal Attribute)
var $memUDID;   // (normal Attribute)
var $memNewEventPush;   // (normal Attribute)
var $memGeneralPush;   // (normal Attribute)
var $memActivityReminderPush;   // (normal Attribute)
var $memEventReminderPush;   // (normal Attribute)
var $memPush;   // (normal Attribute)
var $memCreatedDate;   // (normal Attribute)
// **********************
// CONSTRUCTOR METHOD
// **********************

function members()
{
}


// **********************
// GETTER METHODS
// **********************


function getmemId()
{return $this->memId; }
function getmemName()
{return $this->memName; }
function getmemMobile()
{return $this->memMobile; }
function getmemEmail()
{return $this->memEmail; }
function getmemDob()
{return $this->memDob; }
function getmemGender()
{return $this->memGender; }
function getmemStatus()
{return $this->memStatus; }
function getmemDeviceType()
{return $this->memDeviceType; }
function getmemUDID()
{return $this->memUDID; }
function getmemNewEventPush()
{return $this->memNewEventPush; }
function getmemGeneralPush()
{return $this->memGeneralPush; }
function getmemActivityReminderPush()
{return $this->memActivityReminderPush; }
function getmemEventReminderPush()
{return $this->memEventReminderPush; }
function getmemPush()
{return $this->memPush; }
function getmemCreatedDate()
{return $this->memCreatedDate; }
// **********************
// SETTER METHODS
// **********************


function setmemId($val)
{
$this->memId =  $val;
}

function setmemName($val)
{
$this->memName =  $val;
}

function setmemMobile($val)
{
$this->memMobile =  $val;
}

function setmemEmail($val)
{
$this->memEmail =  $val;
}

function setmemDob($val)
{
$this->memDob =  $val;
}

function setmemGender($val)
{
$this->memGender =  $val;
}

function setmemStatus($val)
{
$this->memStatus =  $val;
}

function setmemDeviceType($val)
{
$this->memDeviceType =  $val;
}

function setmemUDID($val)
{
$this->memUDID =  $val;
}

function setmemNewEventPush($val)
{
$this->memNewEventPush =  $val;
}

function setmemGeneralPush($val)
{
$this->memGeneralPush =  $val;
}

function setmemActivityReminderPush($val)
{
$this->memActivityReminderPush =  $val;
}

function setmemEventReminderPush($val)
{
$this->memEventReminderPush =  $val;
}

function setmemPush($val)
{
$this->memPush =  $val;
}

function setmemCreatedDate($val)
{
$this->memCreatedDate =  $val;
}

// **********************
// SELECT METHOD / LOAD
// **********************

function select($id)
{

$sql =  "SELECT * FROM members WHERE memId = $id;";
$result =  mysql_query($sql);
$row = mysql_fetch_object($result);

$this->memId = $row->memId;$this->memName = $row->memName;$this->memMobile = $row->memMobile;$this->memEmail = $row->memEmail;$this->memDob = $row->memDob;$this->memGender = $row->memGender;$this->memStatus = $row->memStatus;$this->memDeviceType = $row->memDeviceType;$this->memUDID = $row->memUDID;$this->memNewEventPush = $row->memNewEventPush;$this->memGeneralPush = $row->memGeneralPush;$this->memActivityReminderPush = $row->memActivityReminderPush;$this->memEventReminderPush = $row->memEventReminderPush;$this->memPush = $row->memPush;$this->memCreatedDate = $row->memCreatedDate;}
// **********************
// DELETE
// **********************

function delete($id)
{
$sql = "DELETE FROM members WHERE memId = $id;";
$result = mysql_query($sql);

}

// **********************
// INSERT
// **********************

function insert()
{
$this->memId = ""; // clear key for autoincrement

$sql = "INSERT INTO members ( memName,memMobile,memEmail,memDob,memGender,memStatus,memDeviceType,memUDID,memNewEventPush,memGeneralPush,memActivityReminderPush,memEventReminderPush,memPush,memCreatedDate ) VALUES ( '$this->memName','$this->memMobile','$this->memEmail','$this->memDob','$this->memGender','$this->memStatus','$this->memDeviceType','$this->memUDID','$this->memNewEventPush','$this->memGeneralPush','$this->memActivityReminderPush','$this->memEventReminderPush','$this->memPush','$this->memCreatedDate' )";
$result = mysql_query($sql);
$this->memId = mysql_insert_id();

}

// **********************
// UPDATE
// **********************

function update($id)
{
$sql = " UPDATE members SET  memName = '$this->memName',memMobile = '$this->memMobile',memEmail = '$this->memEmail',memDob = '$this->memDob',memGender = '$this->memGender',memStatus = '$this->memStatus',memDeviceType = '$this->memDeviceType',memUDID = '$this->memUDID',memNewEventPush = '$this->memNewEventPush',memGeneralPush = '$this->memGeneralPush',memActivityReminderPush = '$this->memActivityReminderPush',memEventReminderPush = '$this->memEventReminderPush',memPush = '$this->memPush',memCreatedDate = '$this->memCreatedDate' WHERE memId = $id ";
$result = mysql_query($sql);
}

// **********************
// Check Duplicate
// **********************
function checkduplicate($name,$id)
{
$sql = "SELECT memId FROM members WHERE memId='$name' and memId <> $id;";
$result = mysql_query($sql);
$row = mysql_fetch_object($result);

$this->memId = $row->memId;if($this->memId > 0 )
	{
		return true;
	}}
} // class : end
?>
