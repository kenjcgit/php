<?php



include_once '../includes/app_config.php';
error_reporting(0);
//ini_set("display_errors","on");


date_default_timezone_set('America/Los_Angeles');

		
		
$selectNoti = "SELECT noti.ntmId, noti.ntmType, noti.ntmTitle, noti.ntmEvtId, noti.ntmDescription, noti.ntmInterval, noti.ntmDate, noti.ntmTime, noti.ntmCategory, noti.ntmStatus,noti.ntm48hours,noti.ntm24hours, noti.ntmCreatedDate FROM notimessages AS noti WHERE 1 =1 AND noti.ntmStatus='Active' AND (noti.ntmDate='".date('Y-m-d')."' OR noti.ntmType='Automatic') AND  (noti.ntmSent='No' OR noti.ntm48hours='No' OR noti.ntm24hours='No') AND noti.ntmTime<='" .date('H:i:s'). "'";
$result = @mysql_query($selectNoti);
while($row = @mysql_fetch_array($result))
{
    extract($row);
    switch ($ntmType)
    {
        case 'Manual':
        {
            if($ntmInterval=='Default')
            {
                $whereDate = " FROM members INNER JOIN eventattendees ON eventattendees.eadMemId = members.memId INNER JOIN events as evt ON evt.evtId = eventattendees.eadEvtIdi WHERE evtStartDate = '" .date("Y-m-d"). "'";
            }
			else
            {
                $whereDate = " FROM members WHERE 1=1 ";
            }
            break;
        }
        case "Automatic":
        {
            $date=date("Y-m-d");
            $dateBrfore48Hours = date("Y-m-d", strtotime('-36 hours', strtotime($ntmDate)));
			if(($dateBrfore48Hours==$date) && ($ntm48hours=='No'))
			{
				$sql48hours = "UPDATE notimessages SET ntm48hours='Yes' WHERE notimessages.ntmId='" .$ntmId. "'";
				@mysql_query($sql48hours);
				
				$whereDate = " FROM members WHERE 1=1 " ;
			}
			$dateBrfore24Hours = date("Y-m-d", strtotime('-24 hours', strtotime($ntmDate)));
			if($dateBrfore24Hours==$date &&  $ntm24hours=='No')
			{
				$sql24hours = "UPDATE notimessages SET ntm24hours='Yes' WHERE notimessages.ntmId='" .$ntmId. "'";
				@mysql_query($sql24hours);
				
				$whereDate = " FROM members WHERE 1=1 " ;
			}
            
            
			
			//" INNER JOIN eventattendees ON eventattendees.eadMemId = members.memId INNER JOIN events as evt ON evt.evtId = eventattendees.eadEvtIdi WHERE (evtStartDate = '$dateBrfore72Hours' OR evtStartDate = '$dateBrfore24Hours') AND evtId = $ntmEvtId";
            break;
        }
        default :
        {
            break;
        }
    }
/*
    <option value="ETC"//<?php if($ntmCategory=='ETC'){ echo "selected"; }?>>ETC</option>
    <option value="Adult Activities"//<?php if($ntmCategory=='Adult Activities'){ echo "selected"; }?>>Adult Activities</option>
    <option value="Youth Activities"//<?php if($ntmCategory=='Youth Activities'){ echo "selected"; }?>>Youth Activities</option>
    <option value="Mini Maccabi Activities"//<?php if($ntmCategory=='Mini Maccabi Activities'){ echo "selected"; }?>>Mini Maccabi Activities</option>
    <option value="Bogrim"//<?php if($ntmCategory=='Bogrim'){ echo "selected"; }?>>Bogrim</option>
    <option value="Non Community Events" */
    switch ($ntmCategory)
    {
        case 'New Event':
            
            $WhereAlert = "memNewEventPush='Yes'";
            break;
        
        case 'General':
            
            $WhereAlert="memGeneralPush='Yes'";
            break;
        
        case 'Activity Remainder':
            
            $WhereAlert="memActivityReminderPush='Yes'";
            break;
        
        case 'Event Reminder':
            
            $WhereAlert="memEventReminderPush='Yes'";
            break;
        
        case 'Push':
            
            $WhereAlert="memPush='Yes'";
            break;
        
        case 'ETC':
            
            $WhereAlert="memETC='Yes'";
            break;
        
        case 'Adult Activities':
            
            $WhereAlert="memAdultActivities='Yes'";
            break;
            
        case 'Youth Activities':
            
            $WhereAlert="memYouthActivities='Yes'";
            break;
            
        case 'Mini Maccabi Activities':
            
            $WhereAlert="memMiniMaccabiActivities='Yes'";
            break;
            
        case 'Bogrim':
            
            $WhereAlert="memBogrim='Yes'";
            break;
            
        case 'Non Community Events':
            
            $WhereAlert="memNonCommunityEvents='Yes'";
            break;
            
            
            
    }
    
        $sql = "INSERT INTO notification (ntfRefKey, ntfUmId, ntfType, ntfUdid, ntfdeviceType, ntmEvtId, ntfmsgText, ntfStatus, ntfCreatedDate, ntfSendDate, ntfError)
        SELECT '" .$ntmId. "', memId, '" .$ntmCategory. "', memUDID, memDeviceType, '" .$ntmEvtId. "', '". $ntmDescription. "', 'No', NOW(), '" .$ntmDate. "', 'test' $whereDate AND $WhereAlert";
        $result123 = @mysql_query($sql);
		
		$resultUpdateNotimessages = "UPDATE notimessages SET ntmSent='Yes' WHERE ntmId='$ntmId'";
		$resultQuery = @mysql_query($resultUpdateNotimessages);
		
		if(!$result123)
		{
			die("Some error in inserting records".@mysql_error());
		}
}

    
    
    
    
?>