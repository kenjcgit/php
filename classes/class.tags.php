
<?php
/*
*
* -------------------------------------------------------
* CLASSNAME:        tags
* CREATION DATE:    09.03.2015
* CLASS FILE:       class.tags.php
* FOR MYSQL TABLE:  tags
* FOR MYSQL DB:     pixme_kenjc
* -------------------------------------------------------
*
*/



// **********************
// CLASS DECLARATION
// **********************

class tags
{ // class : begin


// **********************
// ATTRIBUTE DECLARATION
// **********************

var $tagId;   // KEY ATTR. WITH AUTOINCREMENT

var $tagName;   // (normal Attribute)
var $tagInfo;   // (normal Attribute)
var $tagStatus;   // (normal Attribute)
// **********************
// CONSTRUCTOR METHOD
// **********************

function tags()
{
}


// **********************
// GETTER METHODS
// **********************


function gettagId()
{return $this->tagId; }
function gettagName()
{return $this->tagName; }
function gettagInfo()
{return $this->tagInfo; }
function gettagStatus()
{return $this->tagStatus; }
// **********************
// SETTER METHODS
// **********************


function settagId($val)
{
$this->tagId =  $val;
}

function settagName($val)
{
$this->tagName =  $val;
}

function settagInfo($val)
{
$this->tagInfo =  $val;
}

function settagStatus($val)
{
$this->tagStatus =  $val;
}

// **********************
// SELECT METHOD / LOAD
// **********************

function select($id)
{

$sql =  "SELECT * FROM tags WHERE tagId = $id;";
$result =  mysql_query($sql);
$row = mysql_fetch_object($result);

$this->tagId = $row->tagId;$this->tagName = $row->tagName;$this->tagInfo = $row->tagInfo;$this->tagStatus = $row->tagStatus;}
// **********************
// DELETE
// **********************

function delete($id)
{
$sql = "DELETE FROM tags WHERE tagId = $id;";
$result = mysql_query($sql);

}

// **********************
// INSERT
// **********************

function insert()
{
$this->tagId = ""; // clear key for autoincrement

$sql = "INSERT INTO tags ( tagName,tagInfo,tagStatus ) VALUES ( '$this->tagName','".mysql_real_escape_string($this->tagInfo)."','$this->tagStatus' )";
$result = mysql_query($sql);
$this->tagId = mysql_insert_id();

}

// **********************
// UPDATE
// **********************

function update($id)
{
$sql = " UPDATE tags SET  tagName = '$this->tagName',tagInfo = '".mysql_real_escape_string($this->tagInfo)."',tagStatus = '$this->tagStatus' WHERE tagId = $id ";
$result = mysql_query($sql);
}

// **********************
// Check Duplicate
// **********************
function checkduplicate($name,$id)
{
$sql = "SELECT tagId FROM tags WHERE tagName='$name' and tagId <> $id;";
$result = mysql_query($sql);
$row = mysql_fetch_object($result);

$this->tagId = $row->tagId;if($this->tagId > 0 )
	{
		return true;
	}}
} // class : end
?>
