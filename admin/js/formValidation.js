// JavaScript Document

function isEmail(string) 
{
	if (string.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1)
		return true;
	else
		return false;
}
function hasOnlyAlphabetsWithSlashAndSpaceDash(fieldName,fieldValue)
{
	var str=fieldValue;
	
	i=0;
	while(i<str.length)
	{
		if(!( ((str.charAt(i)>='a') && (str.charAt(i)<='z')) || ((str.charAt(i)>='A') && (str.charAt(i)<='Z')) || str.charAt(i)=='&' || str.charAt(i)=='/' || str.charAt(i)=='-' || str.charAt(i)==' '))
		{
			//alert('Enter only alphabets for '+fieldName)	;
			return false;
		}
		i++;
	}
	return true;
}
function hasOnlyAlphabets(fieldName,fieldValue)
{
	var str=fieldValue;
	
	i=0;
	while(i<str.length)
	{
		if(!( ((str.charAt(i)>='a') && (str.charAt(i)<='z')) || ((str.charAt(i)>='A') && (str.charAt(i)<='Z'))))
		{
			//alert('Enter only alphabets for '+fieldName)	;
			return false;
		}
		i++;
	}
	return true;
}

function hasOnlyNumeric(fieldName,fieldValue)
{
	var str=fieldValue;
	
	i=0;
	while(i<str.length)
	{
		if(!( ((str.charAt(i)>='0') && (str.charAt(i)<='9')) ))
		{
			//alert('Enter only numeric values for '+fieldName)	;
			return false;
		}
		i++;
	}
	return true;
}

function hasOnlyNumericWithDot(fieldName,fieldValue)
{
	var str=fieldValue;
	
	i=0;
	while(i<str.length)
	{
		if(!(((str.charAt(i)>='0') && (str.charAt(i)<='9')) || str.charAt(i)=='.' ))
		{
			//alert('Enter only 0-9 and dot for '+fieldName)	;
			return false;
		}
		i++;
	}
	return true;
}

function hasOnlyNumericWithDash(fieldName,fieldValue)
{
	var str=fieldValue;
	
	i=0;
	while(i<str.length)
	{
		if(!(((str.charAt(i)>='0') && (str.charAt(i)<='9')) || str.charAt(i)=='-'|| str.charAt(i)=='+' || str.charAt(i)==':' ))
		{
			//alert('Enter only 0-9 and dash for '+fieldName)	;
			return false;
		}
		i++;
	}
	return true;
}

function hasOnlyAlphabetsWithSpace(fieldName,fieldValue)
{
	var str=fieldValue;
	
	i=0;
	while(i<str.length)
	{
		if(!(((str.charAt(i)>='a') && (str.charAt(i)<='z')) || str.charAt(i)==' ' || ((str.charAt(i)>='A') && (str.charAt(i)<='Z')) ))
		{
			//alert('Enter only alphabets and space for '+fieldName)	;
			return false;
		}
		i++;
	}
	return true;
}


function hasOnlyAlphabetsWithUnderscore(fieldName,fieldValue)
{
	var str=fieldValue;
	
	i=0;
	while(i<str.length)
	{
		if(!(((str.charAt(i)>='a') && (str.charAt(i)<='z')) || ((str.charAt(i)>='A') && (str.charAt(i)<='Z')) || str.charAt(i)=='_'))
		{
			//alert('Enter only alphabets and underscore for '+fieldName);
			return false;
		}
		i++;
	}
	return true;
}

function hasOnlyAlphaNumeric(fieldName,fieldValue)
{
	var str=fieldValue;
	
	i=0;
	while(i<str.length)
	{
		if(!(((str.charAt(i)>='a') && (str.charAt(i)<='z')) || ((str.charAt(i)>='0') && (str.charAt(i)<='9')) || ((str.charAt(i)>='A') && (str.charAt(i)<='Z')) ))
		{
			//alert('Enter only alphabets and 0-9 for '+fieldName)	;
			return false;
		}
		i++;
	}
	return true;
}

function hasOnlyAlphaNumericWithSpace(fieldName,fieldValue)
{
	var str=fieldValue;
	
	i=0;
	while(i<str.length)
	{
		if(!(((str.charAt(i)>='a') && (str.charAt(i)<='z')) || str.charAt(i)==' ' || ((str.charAt(i)>='0') && (str.charAt(i)<='9')) || ((str.charAt(i)>='A') && (str.charAt(i)<='Z')) ))
		{
			//alert('Enter only alphabets, 0-9 and space for '+fieldName)	;
			return false;
		}
		i++;
	}
	return true;
}

function hasOnlyAlphaNumericWithUnderscore(fieldName,fieldValue)
{
	var str=fieldValue;
	
	i=0;
	while(i<str.length)
	{
		if(!(((str.charAt(i)>='a') && (str.charAt(i)<='z')) || str.charAt(i)=='_' || ((str.charAt(i)>='0') && (str.charAt(i)<='9')) || ((str.charAt(i)>='A') && (str.charAt(i)<='Z')) ))
		{
			//alert('Enter only alphabets, 0-9 and underscore for '+fieldName)	;
			return false;
		}
		i++;
	}
	return true;
}
function hasOnlyNumericWithDashBracket(fieldName,fieldValue)
{
	var str=fieldValue;
	
	i=0;
	while(i<str.length)
	{
		if(!(((str.charAt(i)>='0') && (str.charAt(i)<='9')) || str.charAt(i)=='-' || str.charAt(i)=='(' || str.charAt(i)==')'))
		{
			//alert('Enter only 0-9 and dash for '+fieldName)	;
			return false;
		}
		i++;
	}
	return true;
}


function hasAnyAlphaSpecialChar(fieldName,fieldValue)
{
	var str=fieldValue;
	
	i=0;
	while(i<str.length)
	{
		if( str.charAt(i)=='<' || str.charAt(i)=='>' || str.charAt(i)=='^' || ((str.charAt(i)>='0') && (str.charAt(i)<='9')))
		{
			//alert('Enter any character except <,>,^ for '+fieldName)	;
			return false;
		}
		i++;
	}
	return true;
}

function hasAnySpecialChar(fieldName,fieldValue)
{
	var str=fieldValue;
	
	i=0;
	while(i<str.length)
	{
		if( str.charAt(i)=='<' || str.charAt(i)=='>' || str.charAt(i)=='^'|| str.charAt(i)=='@'|| str.charAt(i)=='#'|| str.charAt(i)=='$'|| str.charAt(i)=='%'|| str.charAt(i)=='*'|| str.charAt(i)=='('|| str.charAt(i)==')'|| str.charAt(i)=='|'|| str.charAt(i)=='{'
																																																																|| str.charAt(i)=='}'|| str.charAt(i)=='['|| str.charAt(i)==']'|| str.charAt(i)=='/'|| str.charAt(i)=='?'|| str.charAt(i)=='+'|| str.charAt(i)=='&'|| str.charAt(i)=='"'|| str.charAt(i)=='.'|| str.charAt(i)==',')
		{
			//alert('Enter any character except <,>,^ for '+fieldName)	;
			return false;
		}
		i++;
	}
	return true;
}
function hasAnySpecialCharWithoutSpace(fieldName,fieldValue)
{
	var str=fieldValue;
	
	i=0;
	while(i<str.length)
	{
		if( str.charAt(i)=='<' || str.charAt(i)=='>' || str.charAt(i)=='^' || str.charAt(i)==' ')
		{
			//alert('Enter any character except <,>,^ for '+fieldName)	;
			return false;
		}
		i++;
	}
	return true;
}

function hasOnlyAlphaNumericWithSpaceUnderscore(fieldName,fieldValue)
{
	var str=fieldValue;
	
	i=0;
	while(i<str.length)
	{
		if(!(((str.charAt(i)>='a') && (str.charAt(i)<='z')) || str.charAt(i)==' ' || str.charAt(i)=='_' || ((str.charAt(i)>='0') && (str.charAt(i)<='9')) || ((str.charAt(i)>='A') && (str.charAt(i)<='Z')) ))
		{
			//alert('Enter only alphabets, 0-9 and space for '+fieldName)	;
			return false;
		}
		i++;
	}
	return true;
}
function hasOnlyNumericWithSpacialchar(fieldName,fieldValue)
{
	var str=fieldValue;
	
	i=0;
	while(i<str.length)
	{
		if(!(((str.charAt(i)>='0') && (str.charAt(i)<='9')) || (str.charAt(i)=='+') || (str.charAt(i)=='-') || str.charAt(i)=='(' || str.charAt(i)==')' ))
		{
			//alert('Enter only alphabets, 0-9 and space for '+fieldName)	;
			return false;
		}
		i++;
	}
	return true;
}

function hasMinLen(fieldName, fieldValue, strMaxLen)
{
    var str=fieldValue;
    var ret = true;
    if (eval(str.length) < eval(strMaxLen))
    {
      return false;
    } 
    return true;
}
function hasMaxLen(fieldName, fieldValue, strMaxLen)
{
    var str=fieldValue;
    var ret = true;

    if (eval(str.length) > eval(strMaxLen))
    {
      return false;
    } 
    return true;
}

function CheckValidUrl(strUrl)
{

        var RegexUrl = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/

        return RegexUrl.test(strUrl);
}
function ValidateBirthDate(chkdate)
{
	var chdate = chkdate;
	i=0;
	if(chdate.value != "")
	{
		if(!chdate.match(/^[0-9]{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])/))
		{
		  return false;
		}
	}
	return true;
}