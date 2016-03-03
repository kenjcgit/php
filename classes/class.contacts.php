
<?php
/*
*
* -------------------------------------------------------
* CLASSNAME:        contacts
* CREATION DATE:    20.03.2015
* CLASS FILE:       class.contacts.php
* FOR MYSQL TABLE:  contacts
* FOR MYSQL DB:     pixme_kenjc
* -------------------------------------------------------
*
*/



// **********************
// CLASS DECLARATION
// **********************

class contacts
{ // class : begin


// **********************
// ATTRIBUTE DECLARATION
// **********************

var $cnId;   // KEY ATTR. WITH AUTOINCREMENT

var $cnName;   // (normal Attribute)
var $cnEmail;   // (normal Attribute)
var $cnPhone;   // (normal Attribute)
var $cnAddress;   // (normal Attribute)
var $cnLatitude;   // (normal Attribute)
var $cnLongitude;   // (normal Attribute)
var $cnStatus;   // (normal Attribute)
var $cnCreatedDate;   // (normal Attribute)
// **********************
// CONSTRUCTOR METHOD
// **********************

function contacts()
{
}


// **********************
// GETTER METHODS
// **********************


function getcnId()
{return $this->cnId; }
function getcnName()
{return $this->cnName; }
function getcnEmail()
{return $this->cnEmail; }
function getcnPhone()
{return $this->cnPhone; }
function getcnAddress()
{return $this->cnAddress; }
function getcnLatitude()
{return $this->cnLatitude; }
function getcnLongitude()
{return $this->cnLongitude; }
function getcnStatus()
{return $this->cnStatus; }
function getcnCreatedDate()
{return $this->cnCreatedDate; }
// **********************
// SETTER METHODS
// **********************


function setcnId($val)
{
$this->cnId =  $val;
}

function setcnName($val)
{
$this->cnName =  $val;
}

function setcnEmail($val)
{
$this->cnEmail =  $val;
}

function setcnPhone($val)
{
$this->cnPhone =  $val;
}

function setcnAddress($val)
{
$this->cnAddress =  $val;
}

function setcnLatitude($val)
{
$this->cnLatitude =  $val;
}

function setcnLongitude($val)
{
$this->cnLongitude =  $val;
}

function setcnStatus($val)
{
$this->cnStatus =  $val;
}

function setcnCreatedDate($val)
{
$this->cnCreatedDate =  $val;
}

// **********************
// SELECT METHOD / LOAD
// **********************

function select($id)
{

$sql =  "SELECT * FROM contacts WHERE cnId = $id;";
$result =  mysql_query($sql);
$row = mysql_fetch_object($result);

$this->cnId = $row->cnId;$this->cnName = $row->cnName;$this->cnEmail = $row->cnEmail;$this->cnPhone = $row->cnPhone;$this->cnAddress = $row->cnAddress;$this->cnLatitude = $row->cnLatitude;$this->cnLongitude = $row->cnLongitude;$this->cnStatus = $row->cnStatus;$this->cnCreatedDate = $row->cnCreatedDate;}
// **********************
// DELETE
// **********************

function delete($id)
{
$sql = "DELETE FROM contacts WHERE cnId = $id;";
$result = mysql_query($sql);

}

// **********************
// INSERT
// **********************

function insert()
{
$this->cnId = ""; // clear key for autoincrement

$sql = "INSERT INTO contacts ( cnName,cnEmail,cnPhone,cnAddress,cnLatitude,cnLongitude,cnStatus,cnCreatedDate ) VALUES ( '$this->cnName','$this->cnEmail','$this->cnPhone','$this->cnAddress','$this->cnLatitude','$this->cnLongitude','$this->cnStatus','$this->cnCreatedDate' )";
$result = mysql_query($sql);
$this->cnId = mysql_insert_id();

}

// **********************
// UPDATE
// **********************

function update($id)
{
$sql = " UPDATE contacts SET  cnName = '$this->cnName',cnEmail = '$this->cnEmail',cnPhone = '$this->cnPhone',cnAddress = '$this->cnAddress',cnLatitude = '$this->cnLatitude',cnLongitude = '$this->cnLongitude',cnStatus = '$this->cnStatus',cnCreatedDate = '$this->cnCreatedDate' WHERE cnId = $id ";
$result = mysql_query($sql);
}

// **********************
// Check Duplicate
// **********************
function checkduplicate($name,$id)
{
$sql = "SELECT cnId FROM contacts WHERE cnEmail='$name' and cnId <> $id;";
$result = mysql_query($sql);
$row = mysql_fetch_object($result);

$this->cnId = $row->cnId;if($this->cnId > 0 )
	{
		return true;
	}}
} // class : end
?>
