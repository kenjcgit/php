-- Adminer 2.2.0 dump
SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `activitytype`;
CREATE TABLE `activitytype` (
  `actTypeId` smallint(3) NOT NULL AUTO_INCREMENT,
  `actTypeName` varchar(100) NOT NULL,
  `actInfo` varchar(4000) NOT NULL,
  `actStatus` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`actTypeId`),
  UNIQUE KEY `actTypeName` (`actTypeName`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `adminusers`;
CREATE TABLE `adminusers` (
  `admId` int(2) NOT NULL AUTO_INCREMENT,
  `admName` varchar(100) NOT NULL,
  `admEmail` varchar(50) NOT NULL,
  `admPassword` varchar(255) NOT NULL,
  `admStatus` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`admId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `cms`;
CREATE TABLE `cms` (
  `cmsId` int(5) NOT NULL AUTO_INCREMENT,
  `cmsName` varchar(50) NOT NULL,
  `cmsContents` text NOT NULL,
  `cmsStatus` enum('Active','Inactive','Published') NOT NULL DEFAULT 'Active',
  `cmsIsSystemPage` enum('Yes','No') NOT NULL DEFAULT 'No',
  `cmsCreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cmsId`),
  UNIQUE KEY `cmsName` (`cmsName`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `eventattendees`;
CREATE TABLE `eventattendees` (
  `eadId` int(11) NOT NULL AUTO_INCREMENT,
  `eadEvtIdi` int(11) NOT NULL,
  `eadMemId` int(11) NOT NULL,
  `eadAttendeeStatus` enum('Yes','No','May Be') NOT NULL,
  `eadStatus` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `eadCreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`eadId`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `evtId` int(5) NOT NULL AUTO_INCREMENT,
  `evtactTypeId` smallint(3) NOT NULL,
  `evtName` varchar(100) NOT NULL,
  `evtStartDate` date NOT NULL,
  `evtEndDate` date NOT NULL,
  `evtStartTime` time NOT NULL,
  `evtEndTime` time NOT NULL,
  `evtInfo` varchar(4000) NOT NULL,
  `evtImage` varchar(100) NOT NULL,
  `evtLocatioName` varchar(100) NOT NULL,
  `evtAddress` varchar(255) NOT NULL,
  `evtLatitude` varchar(20) NOT NULL,
  `evtLongitude` varchar(20) NOT NULL,
  `evtTags` varchar(500) NOT NULL,
  `evtStatus` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `evtCreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`evtId`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `eventtags`;
CREATE TABLE `eventtags` (
  `etId` int(11) NOT NULL AUTO_INCREMENT,
  `etEvtId` int(11) NOT NULL,
  `etTagId` smallint(3) NOT NULL,
  PRIMARY KEY (`etId`),
  UNIQUE KEY `evtId` (`etEvtId`,`etTagId`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
  `memId` int(11) NOT NULL AUTO_INCREMENT,
  `memName` varchar(100) NOT NULL,
  `memMobile` varchar(20) NOT NULL,
  `memEmail` varchar(100) NOT NULL,
  `memPassword` varchar(255) NOT NULL,
  `memDob` date NOT NULL,
  `memGender` enum('Male','Female') NOT NULL DEFAULT 'Male',
  `memStatus` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `memDeviceType` enum('iphone','android') NOT NULL,
  `memUDID` varchar(255) NOT NULL,
  `memNewEventPush` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `memGeneralPush` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `memActivityReminderPush` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `memEventReminderPush` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `memPush` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `memETC` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `memAdultActivities` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `memYouthActivities` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `memMiniMaccabiActivities` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `memBogrim` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `memNonCommunityEvents` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `memBadgeCount` smallint(3) NOT NULL DEFAULT '0',
  `memUserOrigin` varchar(5) NOT NULL DEFAULT 'App',
  `memCreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`memId`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `notification`;
CREATE TABLE `notification` (
  `ntfId` int(5) NOT NULL AUTO_INCREMENT,
  `ntfRefKey` int(11) NOT NULL,
  `ntfUmId` int(11) NOT NULL,
  `ntfType` varchar(255) NOT NULL,
  `ntfUdid` varchar(255) NOT NULL,
  `ntfdeviceType` enum('iPhone','Android') NOT NULL DEFAULT 'Android',
  `ntmEvtId` int(11) NOT NULL,
  `ntfmsgText` varchar(255) NOT NULL,
  `ntfStatus` enum('Yes','No') NOT NULL DEFAULT 'No',
  `ntfCreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ntfSendDate` date NOT NULL,
  `ntfError` varchar(4000) NOT NULL,
  PRIMARY KEY (`ntfId`)
) ENGINE=InnoDB AUTO_INCREMENT=131044 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `notimessages`;
CREATE TABLE `notimessages` (
  `ntmId` int(11) NOT NULL AUTO_INCREMENT,
  `ntmType` enum('Automatic','Manual') NOT NULL DEFAULT 'Automatic',
  `ntmTitle` varchar(100) NOT NULL,
  `ntmEvtId` int(11) NOT NULL DEFAULT '0',
  `ntmDescription` varchar(255) NOT NULL,
  `ntmInterval` enum('Default','Date') NOT NULL DEFAULT 'Default',
  `ntmDate` date NOT NULL,
  `ntmTime` time NOT NULL,
  `ntmCategory` varchar(50) NOT NULL,
  `ntmStatus` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `ntmSent` enum('Yes','No') NOT NULL DEFAULT 'No',
  `ntm48hours` enum('Yes','No') NOT NULL DEFAULT 'No',
  `ntm24hours` enum('Yes','No') NOT NULL DEFAULT 'No',
  `ntmCreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ntmId`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `tagId` smallint(3) NOT NULL AUTO_INCREMENT,
  `tagName` varchar(100) NOT NULL,
  `tagInfo` varchar(4000) NOT NULL,
  `tagStatus` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`tagId`),
  UNIQUE KEY `tagName` (`tagName`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;


