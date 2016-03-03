
<?php
/*
*
* -------------------------------------------------------
* CLASSNAME:        eventattendees
* CREATION DATE:    09.03.2015
* CLASS FILE:       class.eventattendees.php
* FOR MYSQL TABLE:  eventattendees
* FOR MYSQL DB:     pixme_kenjc
* -------------------------------------------------------
*
*/



// **********************
// CLASS DECLARATION
// **********************

class eventattendees
{ // class : begin


// **********************
// ATTRIBUTE DECLARATION
// **********************

var $eadId;   // KEY ATTR. WITH AUTOINCREMENT

var $eadEvtIdi;   // (normal Attribute)
var $eadMemId;   // (normal Attribute)
var $eadStatus;   // (normal Attribute)
var $eadCreatedDate;   // (normal Attribute)
// **********************
// CONSTRUCTOR METHOD
// **********************

function eventattendees()
{
}


// **********************
// GETTER METHODS
// **********************


function geteadId()
{return $this->eadId; }
function geteadEvtIdi()
{return $this->eadEvtIdi; }
function geteadMemId()
{return $this->eadMemId; }
function geteadStatus()
{return $this->eadStatus; }
function geteadCreatedDate()
{return $this->eadCreatedDate; }
// **********************
// SETTER METHODS
// **********************


function seteadId($val)
{
$this->eadId =  $val;
}

function seteadEvtIdi($val)
{
$this->eadEvtIdi =  $val;
}

function seteadMemId($val)
{
$this->eadMemId =  $val;
}

function seteadStatus($val)
{
$this->eadStatus =  $val;
}

function seteadCreatedDate($val)
{
$this->eadCreatedDate =  $val;
}

// **********************
// SELECT METHOD / LOAD
// **********************

function select($id)
{

$sql =  "SELECT * FROM eventattendees WHERE eadId = $id;";
$result =  mysql_query($sql);
$row = mysql_fetch_object($result);

$this->eadId = $row->eadId;$this->eadEvtIdi = $row->eadEvtIdi;$this->eadMemId = $row->eadMemId;$this->eadStatus = $row->eadStatus;$this->eadCreatedDate = $row->eadCreatedDate;}
// **********************
// DELETE
// **********************

function delete($id)
{
$sql = "DELETE FROM eventattendees WHERE eadId = $id;";
$result = mysql_query($sql);

}

// **********************
// INSERT
// **********************

function insert()
{
$this->eadId = ""; // clear key for autoincrement

$sql = "INSERT INTO eventattendees ( eadEvtIdi,eadMemId,eadStatus,eadCreatedDate ) VALUES ( '$this->eadEvtIdi','$this->eadMemId','$this->eadStatus','$this->eadCreatedDate' )";
$result = mysql_query($sql);
$this->eadId = mysql_insert_id();

}

// **********************
// UPDATE
// **********************

function update($id)
{
$sql = " UPDATE eventattendees SET  eadEvtIdi = '$this->eadEvtIdi',eadMemId = '$this->eadMemId',eadStatus = '$this->eadStatus',eadCreatedDate = '$this->eadCreatedDate' WHERE eadId = $id ";
$result = mysql_query($sql);
}

// **********************
// Check Duplicate
// **********************
function checkduplicate($name,$id)
{
$sql = "SELECT eadId FROM eventattendees WHERE eadId='$name' and eadId <> $id;";
$result = mysql_query($sql);
$row = mysql_fetch_object($result);

$this->eadId = $row->eadId;if($this->eadId > 0 )
	{
		return true;
	}}
} // class : end
?>
