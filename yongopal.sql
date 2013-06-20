-- MySQL dump 10.13  Distrib 5.5.29, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: yongopal
-- ------------------------------------------------------
-- Server version	5.5.29-0ubuntu0.12.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcements` (
  `announcementNo` int(11) NOT NULL AUTO_INCREMENT,
  `content` mediumtext,
  `regDatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`announcementNo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `apnBadgeCount`
--

DROP TABLE IF EXISTS `apnBadgeCount`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `apnBadgeCount` (
  `deviceNo` bigint(20) NOT NULL,
  `badgeCount` smallint(255) NOT NULL DEFAULT '0',
  `newMatchAlert` smallint(255) NOT NULL DEFAULT '0',
  `matchSuccessfulAlert` smallint(255) NOT NULL DEFAULT '0',
  `newMessageAlert` smallint(255) NOT NULL DEFAULT '0',
  `newMissionAlert` smallint(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`deviceNo`),
  CONSTRAINT `FK_apnBadgeCount_deviceNo` FOREIGN KEY (`deviceNo`) REFERENCES `apnDevices` (`deviceNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `apnDeviceHistory`
--

DROP TABLE IF EXISTS `apnDeviceHistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `apnDeviceHistory` (
  `deviceNo` bigint(20) NOT NULL,
  `appName` varchar(255) NOT NULL,
  `appVersion` varchar(25) DEFAULT NULL,
  `deviceUdid` char(40) NOT NULL,
  `deviceToken` char(64) NOT NULL,
  `deviceName` varchar(255) NOT NULL,
  `deviceModel` varchar(100) NOT NULL,
  `deviceVersion` varchar(25) NOT NULL,
  `pushBadge` enum('disabled','enabled') DEFAULT 'disabled',
  `pushAlert` enum('disabled','enabled') DEFAULT 'disabled',
  `pushSound` enum('disabled','enabled') DEFAULT 'disabled',
  `development` enum('production','sandbox') CHARACTER SET latin1 NOT NULL DEFAULT 'production',
  `status` enum('active','uninstalled') NOT NULL DEFAULT 'active',
  `archived` datetime NOT NULL,
  KEY `devicetoken` (`deviceToken`),
  KEY `devicename` (`deviceName`),
  KEY `devicemodel` (`deviceModel`),
  KEY `deviceversion` (`deviceVersion`),
  KEY `pushbadge` (`pushBadge`),
  KEY `pushalert` (`pushAlert`),
  KEY `pushsound` (`pushSound`),
  KEY `development` (`development`),
  KEY `status` (`status`),
  KEY `appname` (`appName`),
  KEY `appversion` (`appVersion`),
  KEY `deviceuid` (`deviceUdid`),
  KEY `archived` (`archived`),
  KEY `deviceNo` (`deviceNo`),
  CONSTRAINT `FK_apnDeviceHistory_deviceNo` FOREIGN KEY (`deviceNo`) REFERENCES `apnDevices` (`deviceNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Store unique device history';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `apnDevices`
--

DROP TABLE IF EXISTS `apnDevices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `apnDevices` (
  `deviceNo` bigint(20) NOT NULL AUTO_INCREMENT,
  `appName` varchar(255) NOT NULL,
  `appVersion` float DEFAULT NULL,
  `deviceUdid` char(40) NOT NULL,
  `deviceToken` char(64) NOT NULL,
  `deviceName` varchar(255) NOT NULL,
  `deviceModel` varchar(100) NOT NULL,
  `deviceVersion` varchar(25) NOT NULL,
  `pushBadge` enum('disabled','enabled') DEFAULT 'disabled',
  `pushAlert` enum('disabled','enabled') DEFAULT 'disabled',
  `pushSound` enum('disabled','enabled') DEFAULT 'disabled',
  `development` enum('release','releaseDebug','beta','betaDebug','debug') NOT NULL DEFAULT 'release',
  `status` enum('active','uninstalled') NOT NULL DEFAULT 'active',
  `debug` char(1) NOT NULL DEFAULT 'N',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`deviceNo`),
  UNIQUE KEY `appname` (`appName`,`appVersion`,`deviceUdid`),
  KEY `devicetoken` (`deviceToken`),
  KEY `devicename` (`deviceName`),
  KEY `devicemodel` (`deviceModel`),
  KEY `deviceversion` (`deviceVersion`),
  KEY `pushbadge` (`pushBadge`),
  KEY `pushalert` (`pushAlert`),
  KEY `pushsound` (`pushSound`),
  KEY `development` (`development`),
  KEY `status` (`status`),
  KEY `created` (`created`),
  KEY `modified` (`modified`),
  KEY `deviceUdid` (`deviceUdid`),
  KEY `appVersion` (`appVersion`)
) ENGINE=InnoDB AUTO_INCREMENT=14126820 DEFAULT CHARSET=utf8 COMMENT='Store unique devices';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `afterInsertForApnDevices` AFTER INSERT ON `apnDevices` 
    FOR EACH ROW BEGIN
	insert into apnBadgeCount values (new.deviceNo, 0, 0, 0, 0, 0);
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `beforeUpdateForApnDevices` BEFORE UPDATE ON `apnDevices` 
    FOR EACH ROW BEGIN
	IF OLD.`pushBadge` != NEW.`pushBadge`
	OR OLD.`pushAlert` != NEW.`pushAlert`
	OR OLD.`pushSound` != NEW.`pushSound`
	OR OLD.`appName` != NEW.`appName`
	OR OLD.`appVersion` != NEW.`appVersion`
	OR OLD.`deviceName` != NEW.`deviceName`
	OR OLD.`deviceModel` != NEW.`deviceModel`
	OR OLD.`deviceVersion` != NEW.`deviceVersion`
	OR OLD.`status` != NEW.`status`
	THEN
		INSERT INTO `apnDeviceHistory`
		VALUES
		(
			OLD.`deviceNo`,
			OLD.`appName`,
			OLD.`appVersion`,
			OLD.`deviceUdid`,
			OLD.`deviceToken`,
			OLD.`deviceName`,
			OLD.`deviceModel`,
			OLD.`deviceVersion`,
			OLD.`pushBadge`,
			OLD.`pushAlert`,
			OLD.`pushSound`,
			OLD.`development`,
			OLD.`status`,
			UTC_TIMESTAMP()
		);
	END IF;
	END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `apnQueue`
--

DROP TABLE IF EXISTS `apnQueue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `apnQueue` (
  `queueNo` bigint(20) NOT NULL AUTO_INCREMENT,
  `sender` int(11) DEFAULT NULL,
  `receiver` int(11) NOT NULL DEFAULT '0',
  `pushType` smallint(6) NOT NULL,
  `badge` smallint(6) NOT NULL DEFAULT '0',
  `message` varchar(255) DEFAULT NULL,
  `sound` varchar(40) DEFAULT NULL,
  `extraParams` varchar(255) DEFAULT NULL,
  `status` char(1) DEFAULT 'Q' COMMENT 'Q=queued, F=failed, S=success',
  `didConfirm` char(1) NOT NULL DEFAULT 'N',
  `confirmDatetime` datetime DEFAULT NULL,
  `queueDatetime` datetime NOT NULL,
  `pushDatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`queueNo`),
  KEY `sender` (`sender`),
  KEY `receiver` (`receiver`),
  KEY `pushType` (`pushType`),
  KEY `didConfirm` (`didConfirm`),
  KEY `pushDatetime` (`pushDatetime`),
  KEY `queueDatetime` (`queueDatetime`),
  KEY `badge` (`badge`),
  KEY `status` (`status`),
  CONSTRAINT `FK_apnQueue_receiver` FOREIGN KEY (`receiver`) REFERENCES `members` (`memberNo`),
  CONSTRAINT `FK_apnQueue_sender` FOREIGN KEY (`sender`) REFERENCES `members` (`memberNo`)
) ENGINE=InnoDB AUTO_INCREMENT=15518830 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `apnQueueCache`
--

DROP TABLE IF EXISTS `apnQueueCache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `apnQueueCache` (
  `memberNo` int(11) NOT NULL,
  `queDate` date NOT NULL,
  `newGuideAlert` char(1) NOT NULL DEFAULT 'N',
  `newMissionAlert` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`memberNo`,`queDate`),
  KEY `newGuideAlert` (`newGuideAlert`),
  KEY `newMissionAlert` (`newMissionAlert`),
  CONSTRAINT `FK_apnQueueCache_memberNo` FOREIGN KEY (`memberNo`) REFERENCES `members` (`memberNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `blacklist`
--

DROP TABLE IF EXISTS `blacklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blacklist` (
  `deviceUdid` char(40) NOT NULL,
  `regDatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`deviceUdid`),
  CONSTRAINT `FK_blacklist_deviceUdid` FOREIGN KEY (`deviceUdid`) REFERENCES `apnDevices` (`deviceUdid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cacheRecentMessage`
--

DROP TABLE IF EXISTS `cacheRecentMessage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cacheRecentMessage` (
  `matchNo` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `recentMessage` varchar(80) NOT NULL,
  PRIMARY KEY (`matchNo`,`receiver`),
  KEY `FK_cacheRecentMessage_receiver` (`receiver`),
  CONSTRAINT `FK_cacheRecentMessage_matchNo` FOREIGN KEY (`matchNo`) REFERENCES `matchSessions` (`matchNo`),
  CONSTRAINT `FK_cacheRecentMessage_receiver` FOREIGN KEY (`receiver`) REFERENCES `members` (`memberNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `chatData`
--

DROP TABLE IF EXISTS `chatData`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chatData` (
  `messageNo` int(11) NOT NULL AUTO_INCREMENT,
  `matchNo` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `message` text,
  `detectedLanguage` char(2) DEFAULT NULL,
  `imageFileNo` int(11) DEFAULT NULL,
  `sendDate` datetime DEFAULT NULL,
  `receiveDate` datetime DEFAULT NULL,
  PRIMARY KEY (`messageNo`),
  UNIQUE KEY `key` (`key`),
  UNIQUE KEY `imageFileNo` (`imageFileNo`),
  KEY `matchNo` (`matchNo`),
  KEY `sender` (`sender`),
  KEY `sendDate` (`sendDate`),
  KEY `receiveDate` (`receiveDate`),
  KEY `FK_chatData_receiver` (`receiver`),
  KEY `receiver` (`receiver`),
  CONSTRAINT `FK_chatData_fileNo` FOREIGN KEY (`imageFileNo`) REFERENCES `files` (`fileNo`),
  CONSTRAINT `FK_chatData_matchNo` FOREIGN KEY (`matchNo`) REFERENCES `matchSessions` (`matchNo`),
  CONSTRAINT `FK_chatData_receiver` FOREIGN KEY (`receiver`) REFERENCES `members` (`memberNo`),
  CONSTRAINT `FK_chatData_sender` FOREIGN KEY (`sender`) REFERENCES `members` (`memberNo`)
) ENGINE=InnoDB AUTO_INCREMENT=6052504 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `beforeInsertForChatData` BEFORE INSERT ON `chatData` 
    FOR EACH ROW BEGIN
	declare message varchar(255) CHARSET utf8;
	declare firstName varchar(80) CHARSET utf8;
	
	if new.message is not null then
		SET message = SUBSTRING(NEW.message, 1, 50);
	else
		
		SELECT m.firstName INTO firstName
		FROM members m
		WHERE m.memberNo = NEW.sender;
	
		SET message = CONCAT(firstName,' shared a photo!');
	end if;
	
	
	insert into cacheRecentMessage values (new.matchNo, new.receiver, message)
	ON DUPLICATE KEY UPDATE recentMessage = message;
	
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `crossPostLog`
--

DROP TABLE IF EXISTS `crossPostLog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crossPostLog` (
  `logNo` int(11) NOT NULL AUTO_INCREMENT,
  `memberNo` int(11) NOT NULL,
  `fileNo` int(11) NOT NULL,
  `type` char(4) NOT NULL COMMENT 'FB=facebook,TWT=twitter,FSQ=Foursquare',
  `regDatetime` datetime NOT NULL,
  PRIMARY KEY (`logNo`),
  UNIQUE KEY `memberNo_fileNo_type` (`memberNo`,`fileNo`,`type`),
  KEY `type` (`type`),
  KEY `FK_crossPostLog_fileNo` (`fileNo`),
  CONSTRAINT `FK_crossPostLog_fileNo` FOREIGN KEY (`fileNo`) REFERENCES `files` (`fileNo`),
  CONSTRAINT `FK_crossPostLog_memberNo` FOREIGN KEY (`memberNo`) REFERENCES `members` (`memberNo`)
) ENGINE=InnoDB AUTO_INCREMENT=66450 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback` (
  `feedbackNo` int(11) NOT NULL AUTO_INCREMENT,
  `matchNo` int(11) NOT NULL,
  `memberNo` int(11) NOT NULL,
  `answerNo` int(11) NOT NULL,
  `regDatetime` datetime NOT NULL,
  PRIMARY KEY (`feedbackNo`),
  UNIQUE KEY `matchNo` (`matchNo`,`memberNo`),
  KEY `answerNo` (`answerNo`),
  KEY `FK_feedback_memberNo` (`memberNo`),
  CONSTRAINT `FK_feedback_answerNo` FOREIGN KEY (`answerNo`) REFERENCES `feedbackAnswers` (`answerNo`),
  CONSTRAINT `FK_feedback_matchNo` FOREIGN KEY (`matchNo`) REFERENCES `matchSessions` (`matchNo`),
  CONSTRAINT `FK_feedback_memberNo` FOREIGN KEY (`memberNo`) REFERENCES `members` (`memberNo`)
) ENGINE=InnoDB AUTO_INCREMENT=172659 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `feedbackAnswers`
--

DROP TABLE IF EXISTS `feedbackAnswers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedbackAnswers` (
  `answerNo` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `active` char(1) NOT NULL DEFAULT 'N',
  `listOrder` smallint(4) NOT NULL,
  `pointsForUser` smallint(6) NOT NULL DEFAULT '0',
  `pointsForPartner` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`answerNo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `feedbackOther`
--

DROP TABLE IF EXISTS `feedbackOther`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedbackOther` (
  `feedbackNo` int(11) NOT NULL,
  `otherAnswer` mediumtext,
  PRIMARY KEY (`feedbackNo`),
  CONSTRAINT `FK_feedbackOther_feedbackNo` FOREIGN KEY (`feedbackNo`) REFERENCES `feedback` (`feedbackNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fileMetaData`
--

DROP TABLE IF EXISTS `fileMetaData`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fileMetaData` (
  `fileNo` int(11) NOT NULL,
  `missionNo` int(11) DEFAULT NULL,
  `caption` varchar(140) CHARACTER SET utf8 DEFAULT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `cityName` varchar(160) CHARACTER SET utf8 DEFAULT NULL,
  `provinceName` varchar(160) CHARACTER SET utf8 DEFAULT NULL,
  `provinceCode` varchar(4) CHARACTER SET utf8 DEFAULT NULL,
  `countryName` varchar(160) CHARACTER SET utf8 DEFAULT NULL,
  `countryCode` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `locationName` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `locationId` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`fileNo`),
  KEY `missionNo` (`missionNo`),
  CONSTRAINT `FK_fileLocationData_fileNo` FOREIGN KEY (`fileNo`) REFERENCES `files` (`fileNo`),
  CONSTRAINT `FK_fileMetaData_missionNo` FOREIGN KEY (`missionNo`) REFERENCES `missionPool` (`missionNo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `filePathInfo`
--

DROP TABLE IF EXISTS `filePathInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filePathInfo` (
  `fileNo` int(11) NOT NULL,
  `filePath` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`fileNo`),
  CONSTRAINT `FK_filePathInfo_fileNo` FOREIGN KEY (`fileNo`) REFERENCES `files` (`fileNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fileUrls`
--

DROP TABLE IF EXISTS `fileUrls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fileUrls` (
  `fileNo` int(11) NOT NULL,
  `key` varchar(255) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `twitter` char(1) NOT NULL DEFAULT 'N',
  `facebook` char(1) NOT NULL DEFAULT 'N',
  `foursquare` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`fileNo`),
  UNIQUE KEY `key` (`key`),
  KEY `facebook` (`facebook`),
  KEY `foursquare` (`foursquare`),
  KEY `twitter` (`twitter`),
  CONSTRAINT `FK_fileUrls_fileNo` FOREIGN KEY (`fileNo`) REFERENCES `files` (`fileNo`) ON DELETE CASCADE,
  CONSTRAINT `FK_fileUrls_key` FOREIGN KEY (`key`) REFERENCES `chatData` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files` (
  `fileNo` int(11) NOT NULL AUTO_INCREMENT,
  `fileSize` int(11) NOT NULL DEFAULT '0',
  `fileType` varchar(80) NOT NULL,
  `regDatetime` datetime NOT NULL,
  `updateDatetime` datetime NOT NULL,
  PRIMARY KEY (`fileNo`)
) ENGINE=InnoDB AUTO_INCREMENT=1410206 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `instances`
--

DROP TABLE IF EXISTS `instances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `instances` (
  `instanceNo` int(11) NOT NULL AUTO_INCREMENT,
  `udid` char(40) NOT NULL,
  `regDatetime` datetime NOT NULL,
  PRIMARY KEY (`instanceNo`),
  KEY `udid` (`udid`)
) ENGINE=InnoDB AUTO_INCREMENT=337684 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `matchGroups`
--

DROP TABLE IF EXISTS `matchGroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matchGroups` (
  `matchGroupNo` int(11) NOT NULL AUTO_INCREMENT,
  `minAge` smallint(4) NOT NULL DEFAULT '0',
  `maxAge` smallint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`matchGroupNo`),
  UNIQUE KEY `minAge` (`minAge`),
  UNIQUE KEY `maxAge` (`maxAge`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `matchMemberLog`
--

DROP TABLE IF EXISTS `matchMemberLog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matchMemberLog` (
  `matchNo` int(11) NOT NULL,
  `memberNo` int(11) NOT NULL,
  `matchedMemberNo` int(11) NOT NULL,
  `responseToMatch` char(1) DEFAULT NULL,
  `respondDatetime` datetime DEFAULT NULL,
  `didDump` char(1) NOT NULL DEFAULT 'N',
  `dumpDatetime` datetime DEFAULT NULL,
  `didDelete` char(1) NOT NULL DEFAULT 'N',
  `deleteDatetime` datetime DEFAULT NULL,
  `regDatetime` datetime DEFAULT NULL,
  `activeDate` date DEFAULT NULL,
  `expireDate` date DEFAULT NULL,
  PRIMARY KEY (`matchNo`,`memberNo`,`matchedMemberNo`),
  KEY `FK_matchMemberLog_memberNo` (`memberNo`),
  KEY `matchNo_memberNo` (`matchNo`,`memberNo`),
  KEY `FK_matchMemberLog_matchedMemberNo` (`matchedMemberNo`),
  CONSTRAINT `FK_matchMemberLog_matchedMemberNo` FOREIGN KEY (`matchedMemberNo`) REFERENCES `members` (`memberNo`),
  CONSTRAINT `FK_matchMemberLog_matchNo` FOREIGN KEY (`matchNo`) REFERENCES `matchSessions` (`matchNo`),
  CONSTRAINT `FK_matchMemberLog_memberNo` FOREIGN KEY (`memberNo`) REFERENCES `members` (`memberNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `matchMissionLog`
--

DROP TABLE IF EXISTS `matchMissionLog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matchMissionLog` (
  `matchNo` int(11) NOT NULL,
  `memberNo` int(11) NOT NULL,
  `missionNo` int(11) NOT NULL,
  `fileNo` int(11) DEFAULT NULL,
  `checked` char(1) NOT NULL DEFAULT 'N',
  `updateDatetime` datetime NOT NULL,
  PRIMARY KEY (`matchNo`,`memberNo`,`missionNo`),
  KEY `FK_matchMissionLog` (`memberNo`),
  KEY `FK_matchMissionLog_missionNo` (`missionNo`),
  KEY `fileNo` (`fileNo`),
  CONSTRAINT `FK_matchMissionLog` FOREIGN KEY (`fileNo`) REFERENCES `files` (`fileNo`),
  CONSTRAINT `FK_matchMissionLog_missionNo` FOREIGN KEY (`missionNo`) REFERENCES `matchMissions` (`missionNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `matchMissions`
--

DROP TABLE IF EXISTS `matchMissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matchMissions` (
  `matchNo` int(11) NOT NULL,
  `missionNo` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`matchNo`,`missionNo`),
  KEY `FK_matchMissions_missionNo` (`missionNo`),
  CONSTRAINT `FK_matchMissions_matchNo` FOREIGN KEY (`matchNo`) REFERENCES `matchSessions` (`matchNo`),
  CONSTRAINT `FK_matchMissions_missionNo` FOREIGN KEY (`missionNo`) REFERENCES `missionPool` (`missionNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `matchPool`
--

DROP TABLE IF EXISTS `matchPool`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matchPool` (
  `memberNo` int(20) NOT NULL,
  `matchGroupNo` int(11) NOT NULL DEFAULT '0',
  `status` char(1) NOT NULL DEFAULT 'P' COMMENT 'P=pending, L=locked, M=matched, N=declined todays match, S=suspended, U=uninstalled, F=fake match',
  `pendingSessions` int(11) NOT NULL DEFAULT '0',
  `activeSessions` int(11) NOT NULL DEFAULT '0',
  `queuedMatchSessions` int(11) NOT NULL,
  `queuedQuickMatchSessions` int(11) NOT NULL DEFAULT '0',
  `regDatetime` datetime DEFAULT NULL,
  `updateDatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`memberNo`),
  UNIQUE KEY `memberNo_status` (`memberNo`,`status`),
  KEY `status` (`status`),
  KEY `activeSessions` (`activeSessions`),
  KEY `pendingSessions` (`pendingSessions`),
  KEY `queuedMatchSessions` (`queuedMatchSessions`),
  KEY `queuedQuickMatchSessions` (`queuedQuickMatchSessions`),
  CONSTRAINT `FK_matchPool_memberNo` FOREIGN KEY (`memberNo`) REFERENCES `members` (`memberNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `beforeInsertForMatchPool` BEFORE INSERT ON `matchPool` 
    FOR EACH ROW BEGIN
	DECLARE age, matchGroupNo INT;
	DECLARE birthday DATE;
	
	SELECT m.birthday INTO birthday
	FROM members m
	WHERE m.memberNo = NEW.memberNo;
	
	SET age = age(birthday);
	
	select getMemberMatchGroupNo(NEW.memberNo) INTO matchGroupNo;
	if matchGroupNo is not null then
		SET NEW.matchGroupNo = matchGroupNo;
	end if;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `beforeUpdateForMatchPool` BEFORE UPDATE ON `matchPool` 
    FOR EACH ROW BEGIN
	declare multipleMatchLimit int;
 
	set new.updateDatetime = UTC_TIMESTAMP();
	if new.status = 'S' then
		UPDATE memberPrivileges mpr
		SET mpr.matchPriority = 5
		where mpr.memberNo = old.memberNo;
	end if;
	
	if new.status not in ('S', 'U', 'L') then
		
		SELECT mpv.multipleMatchLimit INTO multipleMatchLimit FROM memberPrivileges mpv WHERE mpv.memberNo = OLD.memberNo;
		IF NEW.queuedQuickMatchSessions > 0 
		THEN set NEW.status = 'A';
		ELSEIF NEW.pendingSessions = 0 AND multipleMatchLimit > NEW.activeSessions+NEW.queuedMatchSessions+NEW.queuedQuickMatchSessions
		THEN set NEW.status = 'P';
		ELSEIF NEW.pendingSessions > 0 OR multipleMatchLimit <= NEW.activeSessions+NEW.queuedMatchSessions+NEW.queuedQuickMatchSessions
		THEN set NEW.status = 'M';
		END IF;
	end if;
	
	set new.updateDatetime = UTC_TIMESTAMP();
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `matchRules`
--

DROP TABLE IF EXISTS `matchRules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matchRules` (
  `ruleNo` int(11) NOT NULL AUTO_INCREMENT,
  `memberNo` int(11) NOT NULL,
  `blockCountry` char(2) DEFAULT NULL,
  `blockMember` int(11) DEFAULT NULL,
  `regDatetime` datetime NOT NULL,
  `expireDate` date NOT NULL,
  PRIMARY KEY (`ruleNo`),
  UNIQUE KEY `ruleIndex` (`memberNo`,`blockCountry`,`expireDate`),
  UNIQUE KEY `blockMemberRuleIndex` (`memberNo`,`blockMember`),
  KEY `FK_matchRules_blockMember` (`blockMember`),
  CONSTRAINT `FK_matchRules_blockMember` FOREIGN KEY (`blockMember`) REFERENCES `members` (`memberNo`),
  CONSTRAINT `FK_matchRules_memberNo` FOREIGN KEY (`memberNo`) REFERENCES `members` (`memberNo`)
) ENGINE=InnoDB AUTO_INCREMENT=47733 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `matchSessionMembers`
--

DROP TABLE IF EXISTS `matchSessionMembers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matchSessionMembers` (
  `matchNo` int(11) NOT NULL,
  `memberNo` int(11) NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'M' COMMENT 'M=match, P=pending, Y=saidYes, N=saidNo, D=deleted',
  `muted` char(1) NOT NULL DEFAULT 'N',
  `deleted` char(1) NOT NULL DEFAULT 'N',
  `regDatetime` datetime DEFAULT NULL,
  `exitDatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`matchNo`,`memberNo`),
  KEY `status` (`status`),
  KEY `FK_matchSessionMembers_memberNo` (`memberNo`),
  KEY `regDatetime` (`regDatetime`),
  KEY `FK_matchSessionMembers_matchNo` (`matchNo`),
  CONSTRAINT `FK_matchSessionMembers_matchNo` FOREIGN KEY (`matchNo`) REFERENCES `matchSessions` (`matchNo`),
  CONSTRAINT `FK_matchSessionMembers_memberNo` FOREIGN KEY (`memberNo`) REFERENCES `members` (`memberNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `afterInsertForMatchSessionMembers` AFTER INSERT ON `matchSessionMembers` 
    FOR EACH ROW BEGIN
	CALL refreshMatchPoolForMember(new.memberNo);
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `afterUpdateForMatchSessionMembers` AFTER UPDATE ON `matchSessionMembers` 
    FOR EACH ROW BEGIN
	CALL refreshMatchPoolForMember(OLD.memberNo);
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `matchSessions`
--

DROP TABLE IF EXISTS `matchSessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matchSessions` (
  `matchNo` int(11) NOT NULL AUTO_INCREMENT,
  `memberCount` int(11) NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'P' COMMENT 'P=pending, Y=successful, N=unsuccessful, X=expired',
  `isQuickMatch` char(1) NOT NULL DEFAULT 'N',
  `open` char(1) NOT NULL DEFAULT 'N',
  `regDatetime` datetime DEFAULT NULL,
  `matchDatetime` datetime DEFAULT NULL,
  `activeDate` date DEFAULT NULL,
  `expireDate` date DEFAULT NULL,
  PRIMARY KEY (`matchNo`),
  KEY `status` (`status`),
  KEY `activeDate` (`activeDate`),
  KEY `expireDate` (`expireDate`),
  KEY `open` (`open`),
  KEY `isQuickMatch` (`isQuickMatch`)
) ENGINE=InnoDB AUTO_INCREMENT=729700 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `beforeUpdateForMatchSessions` BEFORE UPDATE ON `matchSessions` 
    FOR EACH ROW BEGIN
	if old.status != new.status and new.status = 'Y' then
		set new.matchDatetime = UTC_TIMESTAMP();
	end if;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `afterUpdateForMatchSessions` AFTER UPDATE ON `matchSessions` 
    FOR EACH ROW BEGIN
	
	if old.status != new.status then
		
		UPDATE matchPool mp
		join matchSessionMembers msm
		on mp.memberNo = msm.memberNo
		and msm.matchNo = old.matchNo
		left JOIN 
		(
			SELECT
				msm.memberNo,
				COUNT(ms2.matchNo) AS pendingMatches
			FROM matchSessions ms
			JOIN matchSessionMembers msm
			ON msm.matchNo = ms.matchNo
			LEFT JOIN matchSessionMembers msm2
			ON msm.memberNo = msm2.memberNo
			AND msm2.STATUS NOT IN ('Y', 'N')
			LEFT JOIN matchSessions ms2
			ON msm2.matchNo = ms2.matchNo
			AND ms2.activeDate = UTC_DATE()
			AND ms2.STATUS IN ('P', 'Q')
			WHERE ms.matchNo = OLD.matchNo
			GROUP BY msm.memberNo
		) A
		ON mp.memberNo = A.memberNo
		LEFT JOIN 
		(
			SELECT
				msm.memberNo,
				COUNT(ms2.matchNo) AS activeMatches
			FROM matchSessions ms
			JOIN matchSessionMembers msm
			ON msm.matchNo = ms.matchNo
			LEFT JOIN matchSessionMembers msm2
			ON msm.memberNo = msm2.memberNo
			LEFT JOIN matchSessions ms2
			ON msm2.matchNo = ms2.matchNo
			AND ms2.expireDate >= UTC_DATE()
			AND ms2.STATUS = 'Y'
			WHERE ms.matchNo = OLD.matchNo
			GROUP BY msm.memberNo
		) B
		ON mp.memberNo = B.memberNo
		LEFT JOIN 
		(
			SELECT
				msm.memberNo,
				COUNT(ms2.matchNo) AS queuedMatches
			FROM matchSessions ms
			JOIN matchSessionMembers msm
			ON msm.matchNo = ms.matchNo
			LEFT JOIN matchSessionMembers msm2
			ON msm.memberNo = msm2.memberNo
			LEFT JOIN matchSessions ms2
			ON msm2.matchNo = ms2.matchNo
			AND ms2.activeDate = UTC_DATE()
			AND ms2.STATUS = 'Y'
			AND ((ms2.STATUS = 'P' AND msm2.STATUS IN ('Y', 'N')) OR (ms2.STATUS = 'N' AND msm2.STATUS in ('Y', 'N') AND ms2.isQuickMatch = 'N'))
			WHERE ms.matchNo = OLD.matchNo
			GROUP BY msm.memberNo
		) C
		ON mp.memberNo = C.memberNo
		LEFT JOIN 
		(
			SELECT
				msm.memberNo,
				COUNT(ms2.matchNo) AS queuedQuickMatches
			FROM matchSessions ms
			JOIN matchSessionMembers msm
			ON msm.matchNo = ms.matchNo
			LEFT JOIN matchSessionMembers msm2
			ON msm.memberNo = msm2.memberNo
			LEFT JOIN matchSessions ms2
			ON msm2.matchNo = ms2.matchNo
			AND ms2.STATUS = 'A'
			AND msm2.STATUS = 'Y'
			WHERE ms.matchNo = OLD.matchNo
			GROUP BY msm.memberNo
		) D
		ON mp.memberNo = D.memberNo
		SET mp.pendingSessions = case when A.pendingMatches is null then 0 else A.pendingMatches end,
		mp.activeSessions = CASE WHEN B.activeMatches IS NULL THEN 0 ELSE B.activeMatches END,
		mp.queuedMatchSessions = CASE WHEN C.queuedMatches IS NULL THEN 0 ELSE C.queuedMatches END,
		mp.queuedQuickMatchSessions = CASE WHEN D.queuedQuickMatches IS NULL THEN 0 ELSE D.queuedQuickMatches END;
	end if;
	
	
	if old.expireDate != NEW.expireDate then
		update matchMemberLog mml
		set mml.expireDate = new.expireDate
		where mml.matchNo = old.matchNo;
	end if;
	
	
	if NEW.status = 'Y' then
		
		delete from apnQueue
		USING apnQueue
		join matchSessionMembers msm
		on apnQueue.receiver = msm.memberNo
		and msm.matchNo = OLD.matchNo
		where apnQueue.sender is null
		and apnQueue.pushType = 1
		AND apnQueue.queueDatetime > UTC_TIMESTAMP();
	
		
		CALL queNewMissionNotification(old.matchNo);
	
	
	elseIF NEW.STATUS in ('N', 'X') THEN
		
		DELETE FROM apnQueue
		USING apnQueue
		JOIN matchSessionMembers msm
		ON apnQueue.receiver = msm.memberNo
		AND msm.matchNo = OLD.matchNo
		WHERE apnQueue.sender is null
		AND apnQueue.pushType = 5
		AND apnQueue.queueDatetime > UTC_TIMESTAMP();
		
		
		CALL queNewGuideNotification(OLD.matchNo);
	end if;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `memberAccessCodes`
--

DROP TABLE IF EXISTS `memberAccessCodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `memberAccessCodes` (
  `memberNo` int(11) NOT NULL,
  `accessCode` char(8) NOT NULL,
  PRIMARY KEY (`memberNo`),
  CONSTRAINT `FK_memberAccessCodes_memberNo` FOREIGN KEY (`memberNo`) REFERENCES `members` (`memberNo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `memberPrivileges`
--

DROP TABLE IF EXISTS `memberPrivileges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `memberPrivileges` (
  `memberNo` int(11) NOT NULL,
  `matchPriority` int(11) NOT NULL DEFAULT '5',
  `quickMatch` char(1) NOT NULL DEFAULT 'Y',
  `multipleMatchLimit` int(11) NOT NULL DEFAULT '1',
  `maxMatchLimit` int(11) NOT NULL DEFAULT '5',
  PRIMARY KEY (`memberNo`),
  KEY `quickMatch` (`quickMatch`),
  KEY `multipleMatchLimit` (`multipleMatchLimit`),
  KEY `matchPriority` (`matchPriority`),
  CONSTRAINT `FK_memberPrivileges_memberNo` FOREIGN KEY (`memberNo`) REFERENCES `members` (`memberNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `afterUpdateForMemberPrivileges` AFTER UPDATE ON `memberPrivileges` 
    FOR EACH ROW BEGIN
	if old.multipleMatchLimit != new.multipleMatchLimit and NEW.multipleMatchLimit <= old.maxMatchLimit then
		
		UPDATE matchPool mp
		JOIN matchSessionMembers msm
		ON mp.memberNo = msm.memberNo
		JOIN memberPrivileges mpv
		ON mp.memberNo = mpv.memberNo
		JOIN members m
		ON mp.memberNo = m.memberNo
		JOIN apnDevices ad
		ON m.deviceNo = ad.deviceNo
		AND ad.STATUS = 'active'
		SET mp.STATUS = CASE WHEN mp.pendingSessions = 0 AND mpv.multipleMatchLimit > mp.activeSessions+mp.queuedMatchSessions+mp.queuedQuickMatchSessions
		THEN 'P'
		WHEN mp.pendingSessions > 0 OR mpv.multipleMatchLimit <= mp.activeSessions+mp.queuedMatchSessions+mp.queuedQuickMatchSessions
		THEN 'M'
		END,
		mp.updateDatetime = UTC_TIMESTAMP()
		WHERE mp.memberNo = old.memberNo
		AND mp.STATUS NOT IN ('S', 'U', 'L');
	end if;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `members` (
  `memberNo` int(11) NOT NULL AUTO_INCREMENT,
  `currentInstance` int(11) DEFAULT NULL,
  `deviceNo` bigint(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` char(40) DEFAULT NULL,
  `firstName` varchar(20) DEFAULT NULL,
  `lastName` varchar(20) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `city` varchar(160) NOT NULL,
  `provinceCode` varchar(4) NOT NULL,
  `country` varchar(160) NOT NULL,
  `countryCode` char(2) NOT NULL,
  `latitude` float NOT NULL DEFAULT '0',
  `longitude` float NOT NULL DEFAULT '0',
  `intro` varchar(120) DEFAULT NULL,
  `facebookID` bigint(20) DEFAULT NULL,
  `imageIsSet` char(1) NOT NULL DEFAULT 'N',
  `profileImage` int(11) DEFAULT NULL,
  `active` char(1) NOT NULL DEFAULT 'N',
  `locale` char(5) DEFAULT NULL,
  `timezone` varchar(32) NOT NULL,
  `timezoneOffset` int(11) NOT NULL COMMENT 'seconds from GMT',
  `newMatchAlert` char(1) NOT NULL DEFAULT 'Y',
  `newMissionAlert` char(1) NOT NULL DEFAULT 'Y',
  `newMessageAlert` char(1) NOT NULL DEFAULT 'Y',
  `regDatetime` datetime DEFAULT NULL,
  `updateDatetime` datetime DEFAULT NULL,
  `lastSessionDatetime` datetime DEFAULT NULL,
  `lastMatchDatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`memberNo`),
  UNIQUE KEY `memberNo_active_countryCode` (`memberNo`,`countryCode`,`active`),
  UNIQUE KEY `memberNo_active_matchPriority` (`memberNo`,`active`),
  UNIQUE KEY `currentInstance` (`currentInstance`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `facebookID` (`facebookID`),
  UNIQUE KEY `deviceNo` (`deviceNo`),
  KEY `countryCode` (`countryCode`),
  KEY `newMatchAlert` (`newMatchAlert`),
  KEY `newMissionAlert` (`newMissionAlert`),
  KEY `newMessageAlert` (`newMessageAlert`),
  KEY `active` (`active`),
  KEY `profileImage` (`profileImage`),
  KEY `birthday` (`birthday`),
  KEY `lastSessionDatetime` (`lastSessionDatetime`),
  CONSTRAINT `FK_members_currentInstance` FOREIGN KEY (`currentInstance`) REFERENCES `instances` (`instanceNo`),
  CONSTRAINT `FK_members_deviceNo` FOREIGN KEY (`deviceNo`) REFERENCES `apnDevices` (`deviceNo`)
) ENGINE=InnoDB AUTO_INCREMENT=86846 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `beforeUpdateForMembers` BEFORE UPDATE ON `members` 
    FOR EACH ROW BEGIN
	DECLARE age INT;
	DECLARE matchGroupNo INT;
	declare appVersion FLOAT;
	
	
	if (old.birthday != NEW.birthday) or (OLD.birthday is null and NEW.birthday is not null) then
		SET age = age(new.birthday);
		SELECT mg.matchGroupNo INTO matchGroupNo
		FROM matchGroups mg
		WHERE mg.minAge <= age
		AND mg.maxAge >= age;
	
		update matchPool mp set mp.matchGroupNo = matchGroupNo
		where mp.memberNo = old.memberNo;
	end if;
	
	
	if old.lastSessionDatetime != new.lastSessionDatetime then
		insert into sessionLogs (memberNo, regDatetime) values (old.memberNo, UTC_TIMESTAMP());
	end if;
	
	
	if old.newMatchAlert = 'Y' and new.newMatchAlert = 'N' then
		
		DELETE FROM apnQueue
		USING apnQueue
		JOIN members m
		ON apnQueue.receiver = m.memberNo
		AND m.memberNo = OLD.memberNo
		WHERE apnQueue.sender IS NULL
		AND apnQueue.pushType = 1
		AND apnQueue.queueDatetime > UTC_TIMESTAMP();
	ELSEIF OLD.newMissionAlert = 'Y' AND NEW.newMissionAlert = 'N' THEN
		
		DELETE FROM apnQueue
		USING apnQueue
		JOIN members m
		ON apnQueue.receiver = m.memberNo
		AND m.memberNo = OLD.memberNo
		WHERE apnQueue.sender IS NULL
		AND apnQueue.pushType = 5
		AND apnQueue.queueDatetime > UTC_TIMESTAMP();
	end if;
	
	
	if old.deviceNo != new.deviceNo or old.countryCode != new.countryCode then
		SELECT ad.appVersion into appVersion 
		FROM apnDevices ad 
		WHERE ad.deviceNo = NEW.deviceNo;
	
		if appVersion > 216 and NEW.countryCode IN ('US', 'KR', 'JP') then
			update memberPrivileges mpv
			set mpv.maxMatchLimit = 2
			where mpv.memberNo = OLD.memberNo
			and mpv.maxMatchLimit != 2;
		else
			UPDATE memberPrivileges mpv
			SET mpv.maxMatchLimit = 5
			WHERE mpv.memberNo = OLD.memberNo
			AND mpv.maxMatchLimit != 5;
		end if;
	end if;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `missionPool`
--

DROP TABLE IF EXISTS `missionPool`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `missionPool` (
  `missionNo` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) DEFAULT NULL,
  `day` smallint(2) NOT NULL DEFAULT '0',
  `enabled` char(1) NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`missionNo`)
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `quickMatchLog`
--

DROP TABLE IF EXISTS `quickMatchLog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quickMatchLog` (
  `quickMatchNo` int(11) NOT NULL AUTO_INCREMENT,
  `matchNo` int(11) DEFAULT NULL,
  `memberNo` int(11) NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'P' COMMENT 'P=pending, Y=confirm/success, N=decline, A=confirm/waiting',
  `regDatetime` datetime DEFAULT NULL,
  `updatedatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`quickMatchNo`),
  KEY `FK_quickMatchLog_memberNo` (`memberNo`),
  KEY `regDatetime` (`regDatetime`),
  KEY `status` (`status`),
  KEY `FK_quickMatchLog_matchNo` (`matchNo`),
  CONSTRAINT `FK_quickMatchLog_matchNo` FOREIGN KEY (`matchNo`) REFERENCES `matchSessions` (`matchNo`),
  CONSTRAINT `FK_quickMatchLog_memberNo` FOREIGN KEY (`memberNo`) REFERENCES `members` (`memberNo`)
) ENGINE=InnoDB AUTO_INCREMENT=521318 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sessionLogs`
--

DROP TABLE IF EXISTS `sessionLogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessionLogs` (
  `sessionNo` bigint(20) NOT NULL AUTO_INCREMENT,
  `memberNo` int(20) NOT NULL,
  `regDatetime` datetime NOT NULL,
  PRIMARY KEY (`sessionNo`),
  KEY `FK_sessionLogs_memberNo` (`memberNo`),
  CONSTRAINT `FK_sessionLogs_memberNo` FOREIGN KEY (`memberNo`) REFERENCES `members` (`memberNo`)
) ENGINE=InnoDB AUTO_INCREMENT=13294233 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'yongopal'
--
/*!50003 DROP FUNCTION IF EXISTS `age` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `age`(dob date) RETURNS int(11)
    DETERMINISTIC
BEGIN
	declare age int;
	set age = ((DATE_FORMAT(NOW(),'%Y') - DATE_FORMAT(dob,'%Y')) - (DATE_FORMAT(NOW(),'00-%m-%d') < DATE_FORMAT(dob,'00-%m-%d')));
	return age;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `getMemberMatchGroupNo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `getMemberMatchGroupNo`(memberNo int) RETURNS smallint(6)
    DETERMINISTIC
BEGIN
	DECLARE memberMatchGroup SMALLINT;
	SELECT mg.matchGroupNo INTO memberMatchGroup
	FROM members m
	JOIN matchGroups mg
	ON mg.minAge <= age(m.birthday)
	AND mg.maxAge >= age(m.birthday)
	where m.memberNo = memberNo;
	
	return memberMatchGroup;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `getPushTime` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `getPushTime`(timezone INT) RETURNS time
    DETERMINISTIC
BEGIN
	DECLARE hourOffset CHAR(6);
	DECLARE pushHour TINYINT;
	DECLARE pushTime TIME;
	SET hourOffset = hourFromSec(timezone);
	
	IF hourOffset = '-04:00' THEN SET pushHour = 0;
	ELSEIF hourOffset = '-05:00' THEN SET pushHour = 0;
	ELSEIF hourOffset = '-06:00' THEN SET pushHour = 0;
	ELSEIF hourOffset = '-07:00' THEN SET pushHour = 0;
	ELSEIF hourOffset = '-08:00' THEN SET pushHour = 0;
	ELSEIF hourOffset = '-09:00' THEN SET pushHour = 0;
	ELSEIF hourOffset = '-10:00' THEN SET pushHour = 0;
	ELSEIF hourOffset = '-11:00' THEN SET pushHour = 0;
	ELSEIF hourOffset = '-12:00' THEN SET pushHour = 0;
	ELSEIF hourOffset = '+11:00' THEN SET pushHour = 0;
	ELSEIF hourOffset = '+12:00' THEN SET pushHour = 0;
	ELSEIF hourOffset = '+13:00' THEN SET pushHour = 0;
	ELSEIF hourOffset = '+10:00' THEN SET pushHour = 1;
	ELSEIF hourOffset = '+09:30' THEN SET pushHour = 1;
	ELSEIF hourOffset = '+09:00' THEN SET pushHour = 1;
	ELSEIF hourOffset = '+08:00' THEN SET pushHour = 1;
	ELSEIF hourOffset = '+07:00' THEN SET pushHour = 2;
	ELSEIF hourOffset = '+06:00' THEN SET pushHour = 3;
	ELSEIF hourOffset = '+05:30' THEN SET pushHour = 4;
	ELSEIF hourOffset = '+05:00' THEN SET pushHour = 4;
	ELSEIF hourOffset = '+04:00' THEN SET pushHour = 5;
	ELSEIF hourOffset = '+03:30' THEN SET pushHour = 6;
	ELSEIF hourOffset = '+03:00' THEN SET pushHour = 6;
	ELSEIF hourOffset = '+02:00' THEN SET pushHour = 6;
	ELSEIF hourOffset = '+01:00' THEN SET pushHour = 7;
	ELSEIF hourOffset = '+00:00' THEN SET pushHour = 8;
	ELSEIF hourOffset = '-01:00' THEN SET pushHour = 9;
	ELSEIF hourOffset = '-03:00' THEN SET pushHour = 11;
	ELSEIF hourOffset = '-03:30' THEN SET pushHour = 11;
	ELSE SET pushHour = 0;
	END IF;
	
	SET pushTime = MAKETIME(pushHour, 0, 0);
	
	RETURN pushTime;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `getTimezoneOffset` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `getTimezoneOffset`(hourOffset int) RETURNS int(11)
    DETERMINISTIC
BEGIN
	DECLARE secondOffset INT;
	DECLARE gmt_1100, gmt_1000, gmt_0900, gmt_0800, gmt_0700, gmt_0600, gmt_0500, gmt_0400, gmt_0330, gmt_0300, gmt_0200, gmt_0100 INT;
	DECLARE gmt0, gmt0100, gmt0200, gmt0300, gmt0330, gmt0400, gmt0500, gmt0530, gmt0600, gmt0700, gmt0800, gmt0900, gmt0930, gmt1000, gmt1100, gmt1200, gmt1300 INT;
	
	SET gmt_1100 = -39600;
	SET gmt_1000 = -36000;
	SET gmt_0900 = -32400;
	SET gmt_0800 = -28800;
	SET gmt_0700 = -25200;
	SET gmt_0600 = -21600;
	SET gmt_0500 = -18000;
	SET gmt_0400 = -14400;
	SET gmt_0330 = -12600;
	SET gmt_0300 = -10800;
	SET gmt_0100 = -3600;
	SET gmt0 = 0;
	
	SET gmt0100 = 3600;
	SET gmt0200 = 7200;
	SET gmt0300 = 10800;
	SET gmt0330 = 12600;
	SET gmt0400 = 14400;
	SET gmt0500 = 18000;
	SET gmt0530 = 19800;
	SET gmt0600 = 21600;
	SET gmt0700 = 25200;
	SET gmt0800 = 28800;
	SET gmt0900 = 32400;
	SET gmt0930 = 34200;
	SET gmt1000 = 36000;
	SET gmt1100 = 39600;
	SET gmt1200 = 43200;
	SET gmt1300 = 46800;
	if hourOffset = -1100 then SET secondOffset = gmt_1100;
	elseif hourOffset = -1000 then SET secondOffset = gmt_1000;
	ELSEIF hourOffset = -0900 then SET secondOffset = gmt_0900;
	ELSEIF hourOffset = -0800 then SET secondOffset = gmt_0800;
	ELSEIF hourOffset = -0700 then SET secondOffset = gmt_0700;
	ELSEIF hourOffset = -0600 then SET secondOffset = gmt_0600;
	ELSEIF hourOffset = -0500 then SET secondOffset = gmt_0500;
	ELSEIF hourOffset = -0400 then SET secondOffset = gmt_0400;
	ELSEIF hourOffset = -0330 then SET secondOffset = gmt_0330;
	ELSEIF hourOffset = -0300 then SET secondOffset = gmt_0300;
	ELSEIF hourOffset = -0100 then SET secondOffset = gmt_0100;
	ELSEIF hourOffset = 0 then SET secondOffset = 0;
	ELSEIF hourOffset = 0100 then SET secondOffset = gmt0100;
	ELSEIF hourOffset = 0200 then SET secondOffset = gmt0200;
	ELSEIF hourOffset = 0300 then SET secondOffset = gmt0300;
	ELSEIF hourOffset = 0330 then SET secondOffset = gmt0330;
	ELSEIF hourOffset = 0400 then SET secondOffset = gmt0400;
	ELSEIF hourOffset = 0500 then SET secondOffset = gmt0500;
	ELSEIF hourOffset = 0530 then SET secondOffset = gmt0530;
	ELSEIF hourOffset = 0600 then SET secondOffset = gmt0600;
	ELSEIF hourOffset = 0700 then SET secondOffset = gmt0700;
	ELSEIF hourOffset = 0800 then SET secondOffset = gmt0800;
	ELSEIF hourOffset = 0900 then SET secondOffset = gmt0900;
	ELSEIF hourOffset = 0930 then SET secondOffset = gmt0930;
	ELSEIF hourOffset = 1000 then SET secondOffset = gmt1000;
	ELSEIF hourOffset = 1100 then SET secondOffset = gmt1100;
	ELSEIF hourOffset = 1200 then SET secondOffset = gmt1200;
	ELSEIF hourOffset = 1300 then SET secondOffset = gmt1300;
	end if;
	RETURN secondOffset;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `hourFromSec` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `hourFromSec`(sec INT) RETURNS char(6) CHARSET latin1
    DETERMINISTIC
BEGIN
	declare hours time;
	declare timezone char(6);
	
	if sec != 0 then
		set hours = SEC_TO_TIME(sec);
	
		IF hours >= '00:00:00' THEN
			SET timezone = CONCAT('+', CAST(TIME_FORMAT(hours, '%h:%i') AS CHAR(6)));
		ELSE
			SET timezone = CAST(TIME_FORMAT(hours, '%h:%i') AS CHAR(6));
		END IF;
	else
		set timezone = '+00:00';
	end if;
	RETURN timezone;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `addApnQueue` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `addApnQueue`(IN sender INT, IN receiver INT, IN pushType INT, IN badge INT, IN sound VARCHAR(40), IN message VARCHAR(255) CHARSET utf8, IN extraParams VARCHAR(255) CHARSET utf8)
    DETERMINISTIC
BEGIN
	INSERT ignore INTO apnQueue (sender, receiver, pushType, badge, message, sound, extraParams, queueDatetime)
	VALUES (sender, receiver, pushType, badge, message, sound, extraParams, UTC_TIMESTAMP());
	CALL updateBadgeCount(receiver);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `banMember` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `banMember`(in memberNo int)
    DETERMINISTIC
BEGIN
	declare deviceUdid char(40);
	
	select ad.deviceUdid into deviceUdid from members m join apnDevices ad on m.deviceNo = ad.deviceNo where m.memberNo = memberNo;
	
	INSERT INTO blacklist VALUES (deviceUdid, UTC_TIMESTAMP());
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `broadcastPushNotification` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `broadcastPushNotification`(in message varchar(255), in targetVersion int)
    DETERMINISTIC
BEGIN
	insert into apnQueue
	SELECT
		NULL,
		NULL,
		m.memberNo,
		0,
		0,
		message,
		'default',
		'',
		'Q',
		'N',
		null,
		CASE WHEN DATE_FORMAT(CONVERT_TZ(UTC_TIMESTAMP(), hourFromSec(m.timezoneOffset), '+00:00'),'%H:%i:%s') > '12:00:00' 
		and DATE_FORMAT(CONVERT_TZ(UTC_TIMESTAMP(), hourFromSec(m.timezoneOffset), '+00:00'),'%H:%i:%s') < '18:00:00' then
			UTC_TIMESTAMP()
		when DATE_FORMAT(CONVERT_TZ(UTC_TIMESTAMP(), hourFromSec(m.timezoneOffset), '+00:00'),'%H:%i:%s') < '12:00:00' THEN
			CONVERT_TZ(CONCAT(UTC_DATE(), ' 12:00:00'), hourFromSec(m.timezoneOffset), '+00:00')
		ELSE
			CONVERT_TZ(CONCAT(DATE_ADD(UTC_DATE(), INTERVAL 1 DAY), ' 12:00:00'), hourFromSec(m.timezoneOffset), '+00:00')
		END AS pushTime,
		NULL
	FROM members m
	JOIN apnDevices ad
	ON m.deviceNo = ad.deviceNo
	WHERE CAST(ad.appVersion AS DECIMAL) < targetVersion
	AND ad.STATUS = 'active'
	AND pushAlert = 'enabled';
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `cancelQuickMatch` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `cancelQuickMatch`(IN memberNumber INT)
    DETERMINISTIC
BEGIN
	DECLARE validMember, matchMemberNo, numberOfAttempts, quickMatchNo INT;
	
	
	UPDATE matchSessionMembers msm
	JOIN matchSessions ms
	ON msm.matchNo = ms.matchNo
	JOIN quickMatchLog qml
	ON ms.matchNo = qml.matchNo
	AND msm.memberNo = qml.memberNo
	SET qml.STATUS = 'N', qml.updatedatetime = UTC_TIMESTAMP(), ms.STATUS = 'N', msm.STATUS = 'N'
	WHERE ms.STATUS = 'A'
	AND qml.STATUS = 'A'
	AND qml.memberNo = memberNumber;
	
	
	UPDATE matchPool SET matchPool.STATUS = 'L'
	WHERE matchPool.memberNo = memberNumber
	AND matchPool.STATUS in ('P', 'M')
	AND matchPool.matchGroupNo IN (1, 2, 3);
	
	SET validMember = ROW_COUNT();
	
	IF validMember != 0 THEN
		
		CALL findMatch(memberNumber, 0, 0, @matchMemberNo, @quickMatchNo);
		SELECT @matchMemberNo, @quickMatchNo INTO matchMemberNo, quickMatchNo;
		
		IF quickMatchNo IS NOT NULL THEN
			
			CALL joinSession(memberNumber, matchMemberNo, quickMatchNo, 0);
	
			
			UPDATE quickMatchLog
			SET quickMatchLog.STATUS = 'M', quickMatchLog.updatedatetime = UTC_TIMESTAMP()
			WHERE quickMatchLog.matchNo = quickMatchNo;
			
			CALL getPendingMatchList(memberNumber, 1);
		ELSEIF matchMemberNo IS NOT NULL THEN
			
			UPDATE matchPool SET matchPool.STATUS = 'L'
			WHERE matchPool.memberNo = matchMemberNo
			AND matchPool.STATUS = 'P';
			
			
			CALL setMatch(memberNumber, matchMemberNo, UTC_DATE());
			
			
			CALL getPendingMatchList(memberNumber, 1);
		ELSE
			CALL setFakeMatch(memberNumber);
		END IF;
	ELSE
		CALL getPendingMatchList(memberNumber, 1);
	END IF;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `confirmMatch` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `confirmMatch`(in matchNo int, in memberNo int, out success int)
    DETERMINISTIC
BEGIN
	declare matchStatus, quickMatchStatus char(1);
	DECLARE partnerNo, matchPriority INT;
	
	
	UPDATE matchMemberLog mml
	SET mml.responseToMatch = 'Y', mml.respondDatetime = UTC_TIMESTAMP()
	WHERE mml.matchNo = matchNo
	AND mml.memberNo = memberNo;
	
	
	UPDATE matchSessions ms
	JOIN matchSessionMembers msm
	ON msm.matchNo = ms.matchNo
	SET msm.STATUS = 'Y' 
	WHERE ms.matchNo = matchNo
	
	AND ms.STATUS IN ('P', 'N')
	AND msm.memberNo = memberNo;
	
	
	SELECT 
		distinct(msm.memberNo),
		msm.STATUS,
		qml.status
		INTO
		partnerNo,
		matchStatus,
		quickMatchStatus
	FROM matchSessions ms
	join matchSessionMembers msm
	on msm.matchNo = ms.matchNo
	AND msm.memberNo != memberNo
	left join quickMatchLog qml
	on qml.memberNo = msm.memberNo
	and qml.matchNo = ms.matchNo
	and qml.status = 'A'
	WHERE ms.matchNo = matchNo
	AND ms.STATUS IN ('P', 'N')
	limit 1;
	
	
	SET matchPriority = 1;
	
	IF matchStatus = 'Y' or quickMatchStatus = 'A' THEN
		
		UPDATE matchSessions ms 
		SET ms.STATUS = 'Y', ms.OPEN = 'Y', ms.expireDate = DATE_ADD(UTC_DATE(), INTERVAL 6 DAY)
		WHERE ms.matchNo = matchNo
		and ms.status = 'P';
	
		
		IF quickMatchStatus = 'A' THEN
			
			UPDATE matchSessions ms
			JOIN matchSessionMembers msm
			ON msm.matchNo = ms.matchNo
			join matchMemberLog mml
			on mml.matchNo = msm.matchNo
			and mml.memberNo = msm.memberNo
			join quickMatchLog qml
			on qml.memberNo = msm.memberNo
			and qml.matchNo = ms.matchNo
			and qml.STATUS = 'M'
			SET msm.STATUS = 'Y', mml.responseToMatch = 'Y', mml.respondDatetime = UTC_TIMESTAMP(), qml.STATUS = 'Y', qml.updatedatetime = UTC_TIMESTAMP()
			WHERE ms.matchNo = matchNo
			AND msm.memberNo = partnerNo;
		END IF;
	
		
		update members m set m.lastMatchDatetime = UTC_TIMESTAMP()
		where m.memberNo in (memberNo, partnerNo);
		
		
		update memberPrivileges mpr set mpr.matchPriority = 5
		WHERE mpr.memberNo IN (memberNo, partnerNo);
		
		SET success = 1;
		SET matchPriority = 0;
	elseif matchStatus IN ('M', 'P') then
		SET success = 0;
	else
		
		SET matchPriority = 2;
		SET success = -1;
	END IF;
	
	if matchPriority != 0 then
		UPDATE memberPrivileges mpr SET mpr.matchPriority = mpr.matchPriority + matchPriority WHERE mpr.memberNo = memberNo;
	end if;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `confirmNotification` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `confirmNotification`(in memberNo int, in pushType int)
    DETERMINISTIC
BEGIN
	update apnQueue que 
	set que.didConfirm = 'Y', que.confirmDatetime = UTC_TIMESTAMP()
	where que.receiver = memberNo
	and que.pushType = pushType
	and que.didConfirm = 'N'
	and pushDatetime is not null;
	
	if pushType in (1, 5) then
		update apnQueue que set que.badge = 1
		where que.receiver = memberNo
		and que.sender = 0
		and que.pushType = pushType
		and que.queueDatetime > UTC_TIMESTAMP();
	end if;
	call updateBadgeCount(memberNo);
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `confirmQuickMatch` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `confirmQuickMatch`(IN memberNumber INT, IN allowLocalMatch INT, IN activeDate DATE)
    DETERMINISTIC
BEGIN
	DECLARE matchMemberNo, quickMatchNo INT;
	
	IF activeDate IS NULL THEN
		SET activeDate = UTC_DATE();
	END IF;
	
	
	CALL findMatch(memberNumber, allowLocalMatch, 1, @matchMemberNo, @quickMatchNo);
	SELECT @matchMemberNo, @quickMatchNo INTO matchMemberNo, quickMatchNo;
	IF quickMatchNo IS NOT NULL THEN
		
		UPDATE quickMatchLog
		SET quickMatchLog.STATUS = 'Y', quickMatchLog.updatedatetime = UTC_TIMESTAMP()
		WHERE quickMatchLog.matchNo = quickMatchNo;
	
		
		CALL joinSession(memberNumber, matchMemberNo, quickMatchNo, 1);
		
		
		CALL getMatch(quickMatchNo, memberNumber);
	ELSE
		
		UPDATE matchSessionMembers msm
		JOIN matchSessions ms
		ON msm.matchNo = ms.matchNo
		JOIN quickMatchLog qml
		ON ms.matchNo = qml.matchNo
		AND msm.memberNo = qml.memberNo
		SET qml.STATUS = 'A', qml.updatedatetime = UTC_TIMESTAMP(), ms.STATUS = 'A', msm.STATUS = 'Y'
		WHERE qml.memberNo = memberNumber
		AND qml.STATUS = 'P'
		and ms.STATUS = 'Q'
		AND ms.activeDate = UTC_DATE();
	
		SELECT * FROM members WHERE NULL IS NOT NULL;
	END IF;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `declineMatch` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `declineMatch`(in matchNo int, in memberNo int, in wasDeclined char(1), out token char(64))
    DETERMINISTIC
BEGIN
	declare partnerNo, matchCount, matchLimit int;
	declare didPush, isQuickMatch char(1);
	
	
	SELECT m.deviceNo, m.memberNo, ms.isQuickMatch INTO token, partnerNo, isQuickMatch
	FROM matchSessionMembers msm
	JOIN members m
	ON msm.memberNo = m.memberNo
	join matchSessions ms
	on msm.matchNo = ms.matchNo
	WHERE msm.matchNo = matchNo
	AND msm.memberNo != memberNo
	group by m.deviceNo, m.memberNo
	limit 1;
	
	
	if isQuickMatch = 'Y' then
		delete from matchSessionMembers where matchSessionMembers.matchNo = matchNo and matchSessionMembers.memberNo = memberNo;
		delete from matchMemberLog where matchMemberLog.matchNo = matchNo;
	
		
		insert ignore into matchRules (memberNo, blockMember, regDatetime) values 
		(memberNo, partnerNo, UTC_TIMESTAMP()),	
		(partnerNo, memberNo, UTC_TIMESTAMP());
	
		update matchSessions ms
		join quickMatchLog qml
		on ms.matchNo = qml.matchNo
		and qml.status = 'M'
		AND qml.memberNo = partnerNo
		set ms.status = 'A', ms.memberCount = 1, qml.status = 'A', qml.updatedatetime = UTC_TIMESTAMP()
		where ms.matchNo = matchNo;
	else
		UPDATE matchSessions ms
		JOIN matchSessionMembers msm
		ON msm.matchNo = ms.matchNo
		SET ms.STATUS = 'N', ms.expireDate = '0000-00-00', msm.STATUS = 'N', msm.exitDatetime = UTC_TIMESTAMP()
		WHERE ms.matchNo = matchNo
		AND msm.memberNo = memberNo
		
		AND ms.STATUS = 'P'
		AND msm.STATUS = 'P';
	
		
		UPDATE matchMemberLog mml
		SET mml.responseToMatch = 'N', mml.respondDatetime = UTC_TIMESTAMP()
		WHERE mml.matchNo = matchNo
		AND mml.memberNo = memberNo;
	end if;
	
	
	UPDATE memberPrivileges mpr SET mpr.matchPriority = mpr.matchPriority + 1 WHERE mpr.memberNo = partnerNo;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `declineQuickMatch` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `declineQuickMatch`(IN memberNumber INT)
    DETERMINISTIC
BEGIN
	DECLARE validMember, matchMemberNo, numberOfAttempts, quickMatchNo INT;
	
	
	UPDATE matchPool SET matchPool.STATUS = 'L'
	WHERE matchPool.memberNo = memberNumber
	AND matchPool.STATUS = 'M'
	AND matchPool.matchGroupNo IN (1, 2, 3);
	
	SET validMember = ROW_COUNT();
	
	IF validMember != 0 THEN
		
		UPDATE matchSessions ms
		JOIN matchSessionMembers msm
		ON msm.matchNo = ms.matchNo
		JOIN quickMatchLog qml
		ON ms.matchNo = qml.matchNo
		AND msm.memberNo = qml.memberNo
		SET ms.STATUS = 'N', msm.STATUS = 'N', qml.STATUS = 'N', qml.updatedatetime = UTC_TIMESTAMP()
		WHERE ms.STATUS = 'Q'
		AND qml.STATUS = 'P'
		AND ms.activeDate = UTC_DATE()
		AND qml.memberNo = memberNumber;
	
		
		CALL findMatch(memberNumber, 0, 0, @matchMemberNo, @quickMatchNo);
		SELECT @matchMemberNo, @quickMatchNo INTO matchMemberNo, quickMatchNo;
		
		IF matchMemberNo IS NOT NULL THEN
			
			UPDATE matchPool SET matchPool.STATUS = 'L'
			WHERE matchPool.memberNo = matchMemberNo
			AND matchPool.STATUS = 'P';
	
			
			CALL setMatch(memberNumber, matchMemberNo, UTC_DATE());
			
			
			CALL getPendingMatchList(memberNumber, 1);
		ELSE
			
			UPDATE matchPool SET matchPool.STATUS = 'P'
			WHERE matchPool.memberNo = memberNumber
			AND matchPool.STATUS = 'L';
			
			SELECT 0 AS matchNo;
		END IF;
	else
		SELECT 0 AS matchNo;
	END IF;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `deleteMember` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `deleteMember`(IN memberNo INT)
    DETERMINISTIC
BEGIN
	UPDATE members m
	SET m.deviceNo = NULL, 
	m.email = NULL, 
	m.PASSWORD = NULL, 
	m.firstName = NULL,
	m.lastName = NULL,
	m.gender = NULL,
	m.birthday = NULL,
	m.city = '',
	m.provinceCode = '',
	m.country = '',
	m.countryCode = '',
	m.intro = NULL,
	m.facebookID = NULL,
	m.imageIsSet = 'N',
	m.profileImage = 0,
	m.active = 'N',
	m.locale = NULL,
	m.timezone = '',
	m.timezoneOffset = 0,
	m.newMatchAlert = 'N',
	m.newMissionAlert = 'N',
	m.newMessageAlert = 'N'
	WHERE m.memberNo = memberNo;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `exitAllMatches` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `exitAllMatches`(in memberNo int)
    DETERMINISTIC
BEGIN
	
	select msm.memberNo 
	from matchSessionMembers msm 
	where msm.matchNo in (SELECT ms.matchNo FROM matchSessions ms JOIN matchSessionMembers msm ON msm.matchNo = ms.matchNo WHERE msm.memberNo = memberNo AND ms.STATUS = 'Y')
	and msm.memberNo != memberNo;
	
	
	UPDATE matchSessionMembers msm 
	JOIN matchSessions ms
	ON msm.matchNo = ms.matchNo
	JOIN matchMemberLog mml
	ON msm.matchNo = mml.matchNo
	and msm.memberNo = mml.memberNo
	SET msm.STATUS = 'X', msm.exitDatetime = UTC_TIMESTAMP(), mml.didDump = 'Y', mml.dumpDatetime = UTC_TIMESTAMP()
	WHERE msm.memberNo = memberNo
	
	AND ms.STATUS = 'Y';
	
	
	UPDATE matchSessions ms 
	join matchSessionMembers msm
	on ms.matchNo = msm.matchNo
	and msm.memberNo = memberNo
	SET ms.STATUS = 'X', ms.OPEN = 'N', ms.expireDate = UTC_DATE()
	WHERE ms.STATUS = 'Y';
	
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `exitMatch` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `exitMatch`(IN matchNo INT, in memberNo int, IN partnerNo INt)
    DETERMINISTIC
BEGIN
	
	UPDATE matchSessions ms 
	SET ms.STATUS = 'X', ms.OPEN= 'N', ms.expireDate = UTC_DATE()
	 
	WHERE ms.matchNo = matchNo
	AND ms.STATUS = 'Y';
	
	
	UPDATE matchSessions ms
	JOIN matchSessionMembers msm 
	ON msm.matchNo = ms.matchNo
	JOIN matchMemberLog mml
	ON msm.matchNo = mml.matchNo
	SET msm.STATUS = 'X', msm.exitDatetime = UTC_TIMESTAMP()
	WHERE ms.matchNo = matchNo
	
	AND ms.STATUS = 'Y';
	
	
	update matchMemberLog mml
	set mml.didDump = 'Y', mml.dumpDatetime = UTC_TIMESTAMP()
	where mml.memberNo = memberNo and mml.matchNo = matchNo;
	
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `findMatch` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`yongopal`@`localhost`*/ /*!50003 PROCEDURE `findMatch`(in memberNo INT, in allowLocalMatch INT, in matchType INT, out matchMemberNo int, out quickMatchNo int)
    DETERMINISTIC
BEGIN
	DECLARE matchPriority, lastMatchCount INT;
	DECLARE countryCode, countryFilter, lastMatchCountry CHAR(2);
	DECLARE gender, lastMatchGender, genderFilter, lastMatchStatus, matchStatusPriority CHAR(1);
	
	
	SELECT m.countryCode, mpr.matchPriority, m.gender INTO countryCode, matchPriority, gender
	FROM members m 
	JOIN memberPrivileges mpr
	ON m.memberNo = mpr.memberNo
	WHERE m.memberNo = memberNo;
	
	
	SELECT A.STATUS, A.countryCode, A.gender, COUNT(*)
	INTO lastMatchStatus, lastMatchCountry, lastMatchGender, lastMatchCount
	FROM 
	(
		SELECT
			CASE WHEN msm.STATUS IN ('P', 'N') THEN 'N'
			ELSE msm.STATUS
			END AS STATUS,
			m2.countryCode,
			m2.gender,
			mml.regDatetime
		FROM members m
		JOIN matchMemberLog mml
		ON mml.memberNo = m.memberNo
		JOIN members m2
		ON mml.matchedMemberNo = m2.memberNo
		JOIN matchSessionMembers msm
		ON mml.matchNo = msm.matchNo
		AND mml.memberNo = msm.memberNo
		JOIN matchSessions ms
		ON mml.matchNo = ms.matchNo
		WHERE m.memberNo = memberNo
		AND ms.STATUS NOT IN ('Y', 'X')
		ORDER BY mml.regDatetime DESC
		LIMIT 3
	) A
	GROUP BY A.countryCode, A.STATUS
	ORDER BY A.regDatetime DESC
	LIMIT 1;
	
	IF lastMatchStatus = 'N' AND lastMatchCount = 2 THEN
		INSERT INTO matchRules (memberNo, blockCountry, regDatetime, expireDate) VALUES
		(memberNo, lastMatchCountry, UTC_TIMESTAMP(), DATE_ADD(UTC_DATE(), INTERVAL 3 DAY))
		on duplicate key update expireDate = DATE_ADD(UTC_DATE(), INTERVAL 3 DAY);
	END IF;
	
	IF gender = lastMatchGender THEN
		SET genderFilter = gender;
	ELSE
		SET genderFilter = '';
	END IF;
	
	
	SET matchPriority = matchPriority + 2;
	
	
	IF allowLocalMatch = 1 THEN
		SET countryFilter = '';
	ELSE
		SET countryFilter = countryCode;
	END IF;
	
	
	DROP TEMPORARY TABLE IF EXISTS findMatchTable;
	CREATE TEMPORARY TABLE findMatchTable
	(
		memberNo INT(11),
		pushAlert ENUM('disabled', 'enabled'),
		newMatchAlert CHAR(1),
		lastSessionDatetime DATETIME,
		matchPriority INT(11),
		quickMatchNo INT(11),
		countryPriority TINYINT,
		genderPriority TINYINT,
		matchStatusPriority CHAR(1),
		currentPendingSessions int,
		currentActiveSessions int,
		currentQueuedSessions int,
		KEY `index` (`pushAlert`, `newMatchAlert`, `lastSessionDatetime`, `matchPriority`, `quickMatchNo`, `countryPriority`, `genderPriority`, `matchStatusPriority`)
	) ENGINE memory;
	
	IF matchType = 1 THEN
	
		INSERT INTO findMatchTable
		SELECT
			m.memberNo,
			ad.pushAlert,
			m.newMatchAlert,
			m.lastSessionDatetime,
			mpr.matchPriority,
			qml.matchNo,
			CASE WHEN m.countryCode != countryCode AND m.countryCode != lastMatchCountry THEN 2 
			WHEN m.countryCode != countryCode THEN 1 
			ELSE 0 
			END AS _countryPriority,
			CASE WHEN m.gender != genderFilter THEN 1
			ELSE 0
			END AS _genderPriority,
			qml.STATUS as _matchStatusPriority,
			mp.pendingSessions,
			mp.activeSessions,
			mp.queuedMatchSessions
		FROM matchPool mp
		JOIN members m
		ON mp.memberNo = m.memberNo
		JOIN apnDevices ad
		ON m.deviceNo = ad.deviceNo
		JOIN memberPrivileges mpr
		ON m.memberNo = mpr.memberNo
		JOIN quickMatchLog qml
		ON mp.memberNo = qml.memberNo
		AND qml.STATUS = 'A'
		and qml.matchNo is not null
		JOIN matchSessions ms
		ON qml.matchNo = ms.matchNo
		LEFT JOIN matchMemberLog mml
		ON mml.matchedMemberNo = m.memberNo
		AND mml.memberNo = memberNo
		LEFT JOIN matchRules mr
		ON (mr.blockCountry = m.countryCode
		AND mr.expireDate > UTC_DATE()
		AND mr.memberNo = memberNo)
		or (mr.memberNo = memberNo
		AND mr.blockMember = m.memberNo)
		WHERE m.memberNo != memberNo
		AND m.countryCode != countryFilter
		AND m.active = 'Y'
		and mp.status not in ('S', 'U', 'L')
		AND mp.matchGroupNo = getMemberMatchGroupNo(memberNo)
		AND mpr.matchPriority <= matchPriority
		AND mml.matchNo IS NULL
		AND mr.ruleNo IS NULL;
	ELSE
		INSERT INTO findMatchTable
		SELECT
			m.memberNo,
			ad.pushAlert,
			m.newMatchAlert,
			m.lastSessionDatetime,
			mpr.matchPriority,
			qml.matchNo,
			CASE WHEN m.countryCode != countryCode AND m.countryCode != lastMatchCountry THEN 2 
			WHEN m.countryCode != countryCode THEN 1 
			ELSE 0 
			END AS _countryPriority,
			CASE WHEN m.gender != genderFilter THEN 1
			ELSE 0
			END AS _genderPriority,
			case when qml.status is null then 'Z'
			else qml.status
			end as _matchStatusPriority,
			mp.pendingSessions,
			mp.activeSessions,
			mp.queuedMatchSessions
		FROM matchPool mp
		JOIN members m
		ON mp.memberNo = m.memberNo
		JOIN apnDevices ad
		ON m.deviceNo = ad.deviceNo
		JOIN memberPrivileges mpr
		ON m.memberNo = mpr.memberNo
		LEFT JOIN matchSessionMembers msm
		ON mp.memberNo = msm.memberNo
		AND msm.STATUS = 'Y'
		AND msm.exitDatetime IS NOT NULL
		left join matchSessions ms
		on ms.matchNo = msm.matchNo
		and ms.status = 'A'
		and ms.isQuickMatch = 'Y'
		LEFT JOIN quickMatchLog qml
		ON msm.matchNo = qml.matchNo
		AND qml.STATUS = 'A'
		left join matchMemberLog mml
		on mml.matchedMemberNo = m.memberNo
		and mml.memberNo = memberNo
		LEFT JOIN matchRules mr
		ON (mr.blockCountry = m.countryCode
		AND mr.expireDate > UTC_DATE()
		and mr.memberNo = memberNo)
		OR (mr.memberNo = memberNo
		and mr.blockMember = m.memberNo)
		WHERE m.memberNo != memberNo
		AND m.active = 'Y'
		AND m.countryCode != countryFilter
		AND mp.STATUS NOT IN ('S', 'U', 'L')
		AND (mp.STATUS = 'P' OR qml.matchNo IS NOT NULL)
		AND mp.matchGroupNo = getMemberMatchGroupNo(memberNo)
		AND mpr.matchPriority <= matchPriority
		and mml.matchNo is null
		and mr.ruleNo is null;
	END IF;
	
	SELECT fmt.memberNo, fmt.quickMatchNo
	into matchMemberNo, quickMatchNo
	FROM findMatchTable fmt
	ORDER BY fmt.pushAlert DESC,
	fmt.newMatchAlert DESC,
	fmt.countryPriority DESC,
	fmt.genderPriority DESC,
	fmt.matchStatusPriority ASC,
	DATE_FORMAT(fmt.lastSessionDatetime, '%Y-%m-%d %H:00:00') DESC,
	fmt.matchPriority DESC,
	fmt.currentPendingSessions asc,
	fmt.currentQueuedSessions ASC,
	fmt.currentActiveSessions ASC
	limit 1;
	
	DROP TEMPORARY TABLE IF EXISTS findMatchTable;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generateMatch` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `generateMatch`(IN memberNumber INT, IN allowLocalMatch INT, IN willPush INT, IN activeDate DATE)
    DETERMINISTIC
BEGIN
	DECLARE validMember, matchMemberNo, quickMatchNo INT;
	Declare quickMatchPrivilege char(1);
	
	IF activeDate IS NULL THEN
		SET activeDate = UTC_DATE();
	END IF;
	
	
	update matchPool set matchPool.status = 'P'
	WHERE matchPool.memberNo = memberNumber
	AND matchPool.STATUS = 'L'
	and TIMEDIFF(UTC_TIMESTAMP(), matchPool.updateDatetime) > '00:01:00';
	
	
	CALL refreshMatchPoolForMember(memberNumber);
	
	
	UPDATE matchPool SET matchPool.STATUS = 'L'
	WHERE matchPool.memberNo = memberNumber
	AND matchPool.STATUS = 'P'
	AND matchPool.matchGroupNo IN (1, 2, 3);
	
	SET validMember = ROW_COUNT();
	
	IF validMember != 0 THEN
		select mpr.quickMatch into quickMatchPrivilege
		from memberPrivileges mpr
		where mpr.memberNo = memberNumber;
		
		
		IF quickMatchPrivilege = 'Y' THEN
			
			CALL setQuickMatch(memberNumber, UTC_DATE(), @matchNo);
	
			
			SELECT @matchNo INTO quickMatchNo;
			INSERT INTO quickMatchLog (matchNo, memberNo, regDatetime) VALUES (quickMatchNo, memberNumber, UTC_TIMESTAMP());
	
			CALL getPendingMatchList(memberNumber, willPush);
		else
			
			call findMatch(memberNumber, allowLocalMatch, 0, @matchMemberNo, @quickMatchNo);
			select @matchMemberNo, @quickMatchNo INTO matchMemberNo, quickMatchNo;
	
			
			if quickMatchNo is not null then
				
				CALL joinSession(memberNumber, matchMemberNo, quickMatchNo, 0);
		
				
				UPDATE quickMatchLog
				SET quickMatchLog.STATUS = 'M', quickMatchLog.updatedatetime = UTC_TIMESTAMP()
				WHERE quickMatchLog.matchNo = quickMatchNo;
	
				
				CALL getPendingMatchList(memberNumber, willPush);
			ELSEIF matchMemberNo IS NOT NULL THEN
				
				UPDATE matchPool SET matchPool.STATUS = 'L'
				WHERE matchPool.memberNo = matchMemberNo
				AND matchPool.STATUS = 'P';
				
				
				CALL setMatch(memberNumber, matchMemberNo, activeDate);
				
				
				CALL getPendingMatchList(memberNumber, willPush);
			ELSEif allowLocalMatch = 0 then
				
				call setQuickMatch(memberNumber, UTC_DATE(), @matchNo);
	
				
				select @matchNo INTO quickMatchNo;
				INSERT INTO quickMatchLog (matchNo, memberNo, regDatetime) VALUES (quickMatchNo, memberNumber, UTC_TIMESTAMP());
	
				CALL getPendingMatchList(memberNumber, willPush);
			else
				UPDATE matchPool SET matchPool.STATUS = 'P' 
				WHERE matchPool.memberNo = memberNumber
				and matchPool.status = 'L';
	
				SELECT * FROM members WHERE NULL IS NOT NULL;
			END IF;
		end if;
	else
		SELECT * FROM members WHERE NULL IS NOT NULL;
	END IF;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getMatch` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `getMatch`(IN matchNo int, in memberNo int)
    DETERMINISTIC
BEGIN
	
	SELECT
		distinct(ms.matchNo),
		ms.regDatetime,
		ms.matchDatetime,
		ms.activeDate,
		ms.expireDate,
		ms.isQuickMatch,
		m.memberNo,
		m.deviceNo,
		m.email,
		m.firstName,
		m.lastName,
		m.gender,
		m.birthday,
		m.city,
		m.provinceCode,
		m.country,
		m.countryCode,
		m.timezone,
		m.timezoneOffset,
		m.latitude,
		m.longitude,
		m.intro,
		m.facebookID,
		m.profileImage AS profileImageNo,
		m.active,
		m.lastMatchDatetime,
		age(m.birthday) AS age,
		m2.firstName as userFirstName
	FROM matchSessions ms
	join matchSessionMembers msm
	ON msm.matchNo = ms.matchNo
	JOIN members m
	ON msm.memberNo = m.memberNo
	join matchMemberLog mml
	on mml.matchNo = ms.matchNo
	and msm.memberNo = mml.matchedMemberNo
	and mml.memberNo = memberNo
	join members m2
	on mml.memberNo = m2.memberNo
	where ms.matchNo = matchNo;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getMatchList` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `getMatchList`(in memberNo int, in willPush int)
    DETERMINISTIC
BEGIN
	
	if willPush = 1 then
		UPDATE matchSessionMembers msm
		join matchSessions ms
		on msm.matchNo = ms.matchNo
		AND ms.STATUS != 'N'
		SET msm.STATUS = 'P'
		WHERE msm.memberNo = memberNo
		and msm.status = 'M';
	end if;
	
	SELECT
		msm.matchNo,
		case when m.memberNo is null then 0
		else m.memberNo
		end as memberNo,
		m.email,
		case when m.firstName is null then ''
		else m.firstName
		end as firstName,
		m.lastName,
		m.gender,
		m.birthday,
		m.city,
		m.provinceCode,
		m.country,
		m.countryCode,
		m.timezone,
		m.timezoneOffset,
		case when m.latitude is null then 0
		else m.latitude
		end as latitude,
		case when m.longitude is null then 0
		else m.longitude
		end as longitude,
		m.intro,
		m.profileImage AS profileImageNo,
		case when ms.matchDatetime is null then ms.regDatetime
		else ms.matchDatetime
		end as matchDatetime,
		ms.regDatetime,
		ms.open,
		ms.activeDate,
		ms.expireDate,
		ms.memberCount,
		
		CASE WHEN msm.deleted = 'Y' THEN 'D'
		
		WHEN ms.STATUS IN ('P', 'N', 'Q') AND msm.STATUS = 'P' THEN 'M'
		
		WHEN ms.STATUS = 'Y' AND msm.STATUS = 'Y' THEN 'Y'
		
		WHEN ms.STATUS IN ('P', 'N', 'A') AND msm.STATUS = 'Y' THEN 'P'
		
		WHEN ms.STATUS = 'X' AND msm.STATUS IN ('Y', 'X') THEN 'N'
		END AS matchStatus,
		msm.status,
		msm.muted,
		msm.deleted,
		
		CASE WHEN ms.STATUS IN ('P', 'N', 'Q') AND msm.STATUS = 'P' THEN 1
		
		WHEN ms.STATUS = 'Y' AND msm.STATUS = 'Y' THEN 2
		
		WHEN ms.STATUS IN ('P', 'N', 'A') AND msm.STATUS = 'Y' THEN 3
		
		WHEN ms.STATUS = 'X' AND msm.STATUS IN ('Y', 'X') THEN 4
		END AS `order`,
		CASE ms.activeDate
		WHEN UTC_DATE() THEN 'Y'
		ELSE 'N'
		END AS active,
		crm.recentMessage,
		ms.isQuickMatch
	FROM members MK
	join matchSessionMembers msm
	on MK.memberNo = msm.memberNo
	and MK.memberNo = memberNo
	join matchSessions ms
	ON msm.matchNo = ms.matchNo
	left JOIN matchMemberLog mml
	ON msm.matchNo = mml.matchNo
	AND MK.memberNo = mml.matchedMemberNo
	left JOIN members = m
	ON mml.memberNo = m.memberNo
	left join cacheRecentMessage crm
	on MK.memberNo = crm.receiver
	and crm.matchNo = msm.matchNo
	WHERE ((ms.STATUS IN ('P', 'Y', 'X', 'Q', 'A'))
	OR (ms.STATUS = 'N' AND msm.STATUS IN ('P', 'Y') AND ms.activeDate = UTC_DATE()))
	order by ms.regDatetime desc;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getNewMissions` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `getNewMissions`(IN matchNo INT, IN memberNo INT, IN missionLimit INT)
    DETERMINISTIC
BEGIN
	DECLARE matchDay INT;
	DECLARE currentMissions INT;
	DECLARE missionDiff INT;
	
	
	SELECT COUNT(*) INTO currentMissions FROM matchMissions mm WHERE mm.matchNo = matchNo AND mm.DATE = UTC_DATE();
	
	SET missionDiff = missionLimit - currentMissions;
	
	
	IF missionDiff > 0 THEN
		
		SELECT DATEDIFF(UTC_DATE(), ms.regDatetime) + 1 INTO matchDay FROM matchSessions ms WHERE ms.matchNo = matchNo;
		
		INSERT ignore INTO matchMissions
			(matchNo, missionNo, DATE)
			(
				SELECT
					matchNo,
					mp.missionNo,
					UTC_DATE()
				FROM missionPool mp
				WHERE mp.DAY IN (0, matchDay)
				AND mp.missionNo NOT IN (SELECT missionNo FROM matchMissions ms WHERE ms.matchNo = matchNo)
				and mp.enabled = 'Y'
				ORDER BY mp.DAY DESC, RAND() LIMIT missionDiff
			);
	END IF;
	
	SELECT
		mp.*,
		mm.date,
		mml.checked,
		mml.updateDatetime,
		null as checkDatetime 
	from matchMissions mm
	JOIN missionPool mp
	on mm.missionNo = mp.missionNo
	LEFT JOIN matchMissionLog mml
	on mm.missionNo = mml.missionNo
	AND mml.memberNo = memberNo
	WHERE mm.matchNo = matchNo
	AND date = UTC_DATE();	
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getPendingMatchList` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `getPendingMatchList`(IN memberNo int, in willPush int)
    DETERMINISTIC
BEGIN	
	
	if willPush = 1 then
		UPDATE matchSessionMembers msm
		JOIN matchSessions ms
		ON msm.matchNo = ms.matchNo
		SET msm.STATUS = 'P'
		WHERE msm.STATUS = 'M'
		AND ms.STATUS != 'N'
		AND msm.memberNo = memberNo;
	end if;
	
	
	SELECT
		ms.matchNo,
		ms.regDatetime,
		ms.STATUS,
		ms.activeDate,
		ms.expireDate,
		ms.memberCount,
		m.memberNo,
		m.deviceNo,
		m.email,
		m.firstName,
		m.lastName,
		m.gender,
		m.birthday,
		m.city,
		m.provinceCode,
		m.country,
		m.countryCode,
		m.timezone,
		m.timezoneOffset,
		m.latitude,
		m.longitude,
		m.intro,
		m.facebookID,
		m.profileImage as profileImageNo,
		m.active,
		m.lastMatchDatetime,
		age(m.birthday) AS age,
		ms.isQuickMatch
	FROM members MK
	join matchMemberLog mml
	on MK.memberNo = mml.memberNo
	JOIN members m
	ON mml.matchedMemberNo = m.memberNo
	JOIN matchSessions ms
	ON mml.matchNo = ms.matchNo
	WHERE MK.memberNo = memberNo
	and ms.STATUS IN ('M', 'P')
	
	UNION
	
	select
		ms.matchNo,
		ms.regDatetime,
		ms.status,
		ms.activeDate,
		ms.expireDate,
		ms.memberCount,
		0 AS memberNo,
		null as deviceNo,
		NULL AS email,
		'' as firstName,
		NULL AS lastName,
		NULL AS gender,
		NULL AS birthday,
		NULL AS city,
		NULL AS provinceCode,
		NULL AS country,
		NULL AS countryCode,
		NULL AS timezone,
		NULL AS timezoneOffset,
		0 AS latitude,
		0 AS longitude,
		NULL AS intro,
		NULL AS facebookID,
		NULL AS profileImageNo,
		NULL AS active,
		NULL AS lastMatchDatetime,
		Null AS age,
		ms.isQuickMatch
	FROM matchSessions ms
	join matchSessionMembers msm
	on ms.matchNo = msm.matchNo
	and msm.memberNo = memberNo
	WHERE ms.STATUS = 'Q'
	and ms.activeDate = UTC_DATE();
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getQueuedNotifications` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `getQueuedNotifications`(in memberNo int)
    DETERMINISTIC
BEGIN
	if memberNo != 0
	then
		SELECT
			que.queueNo,
			que.sender,
			que.receiver,
			que.pushType,
			que.message,
			que.sound,
			que.extraParams,
			que.didConfirm,
			que.queueDatetime,
			que.pushDatetime,
			ad.pushBadge,
			ad.pushAlert,
			ad.pushSound,
			ad.deviceNo,
			ad.deviceToken,
			(abc.badgeCount + que.badge) as badge,
			ad.development,
			m.newMatchAlert,
			m.newMissionAlert,
			m.newMessageAlert
		FROM apnQueue que
		JOIN members m
		ON que.receiver = m.memberNo
		AND m.deviceNo IS NOT NULL
		JOIN apnDevices ad
		on m.deviceNo = ad.deviceNo
		AND ad.STATUS = 'active'
		join apnBadgeCount abc
		on abc.deviceNo = ad.deviceNo
		WHERE que.sender = memberNo
		and que.STATUS = 'Q'
		AND que.didConfirm = 'N'
		AND que.pushDatetime IS NULL
		AND que.queueDatetime <= UTC_TIMESTAMP();
	else
		
		update apnQueue que
		set que.status = 'F'
		WHERE que.STATUS = 'Q'
		AND que.didConfirm = 'N'
		AND ADDTIME(que.queueDatetime, '4:0:0') < UTC_TIMESTAMP()
		AND que.pushDatetime IS NULL;
	
		SELECT
			que.queueNo,
			que.sender,
			que.receiver,
			que.pushType,
			que.message,
			que.sound,
			que.extraParams,
			que.didConfirm,
			que.queueDatetime,
			que.pushDatetime,
			ad.pushBadge,
			ad.pushAlert,
			ad.pushSound,
			ad.deviceNo,
			ad.deviceToken,
			(abc.badgeCount + que.badge) AS badge,
			ad.development,
			m.newMatchAlert,
			m.newMissionAlert,
			m.newMessageAlert
		FROM apnQueue que
		JOIN members m
		ON que.receiver = m.memberNo
		AND m.deviceNo IS NOT NULL
		JOIN apnDevices ad
		ON m.deviceNo = ad.deviceNo
		AND ad.STATUS = 'active'
		JOIN apnBadgeCount abc
		ON abc.deviceNo = ad.deviceNo
		WHERE que.status = 'Q'
		AND que.didConfirm = 'N'
		and que.queueDatetime <= UTC_TIMESTAMP()
		AND que.pushDatetime IS NULL;
	end if;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getRecentMessage` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `getRecentMessage`(in matchNo int, in memberNo int)
    DETERMINISTIC
BEGIN
	SELECT
		CASE WHEN imageFileNo IS NULL THEN cd.message
		ELSE CONCAT(m.firstName, ' shared a photo')
		END AS recentMessage
	FROM chatData cd
	JOIN members m
	ON cd.sender = m.memberNo
	WHERE cd.matchNo = matchNo
	AND cd.receiver = memberNo
	ORDER BY sendDate DESC
	LIMIT 1;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `joinSession` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`yongopal`@`localhost`*/ /*!50003 PROCEDURE `joinSession`(in memberNo int, in matchMemberNo int, in matchNo int, in autoConfirm int)
    DETERMINISTIC
BEGIN
	declare matchCount, matchLimit, quickMatchCount int;
	declare matchStatus, matchMemberStatus, isOpen char(1); 
	
	if autoConfirm = 1 then
		set matchStatus = 'Y';
		set matchMemberStatus = 'Y';
		set isOpen = 'Y';
	else
		SET matchStatus = 'P';
		set matchMemberStatus = 'P';
		set isOpen = 'N';
	end if;
	
	
	UPDATE matchSessions ms 
	SET ms.memberCount = ms.memberCount + 1, ms.STATUS = matchStatus, ms.expireDate = DATE_ADD(UTC_DATE(), INTERVAL 6 DAY), ms.OPEN = isOpen
	WHERE ms.matchNo = matchNo;
	
	
	INSERT INTO matchSessionMembers (matchNo, memberNo, STATUS, regDatetime)
	VALUES
	(matchNo, memberNo, matchMemberStatus, UTC_TIMESTAMP());
	
	
	if autoConfirm = 1 then
		
		update quickMatchLog qml
		join matchSessions ms
		on qml.matchNo = ms.matchNo
		and ms.status = 'Q'
		join matchSessionMembers msm
		on ms.matchNo = msm.matchNo
		set ms.status = 'N', ms.expireDate = UTC_DATE(), msm.STATUS = 'N', msm.exitDatetime = UTC_TIMESTAMP()
		where qml.memberNo = memberNo
		and qml.status = 'P';
	
		
		INSERT INTO matchMemberLog (matchNo, memberNo, matchedMemberNo, responseToMatch, respondDatetime, regDatetime, activeDate) 
		VALUES
		(matchNo, matchMemberNo, memberNo, 'Y', UTC_TIMESTAMP(), UTC_TIMESTAMP(), UTC_DATE()),
		(matchNo, memberNo, matchMemberNo, 'Y', UTC_TIMESTAMP(), UTC_TIMESTAMP(), UTC_DATE())
		ON DUPLICATE KEY UPDATE matchMemberLog.responseToMatch = 'Y', matchMemberLog.respondDatetime = UTC_TIMESTAMP();
	
		
		update members m set m.lastMatchDatetime = UTC_TIMESTAMP() where m.memberNo in (memberNo, matchMemberNo);
	else
		INSERT INTO matchMemberLog (matchNo, memberNo, matchedMemberNo, regDatetime, activeDate) 
		VALUES
		(matchNo, matchMemberNo, memberNo, UTC_TIMESTAMP(), UTC_DATE()),
		(matchNo, memberNo, matchMemberNo, UTC_TIMESTAMP(), UTC_DATE());
	
		
		UPDATE matchPool SET matchPool.STATUS = 'M'
		WHERE matchPool.memberNo = memberNo
		and matchPool.status = 'L';
	end if;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `queNewGuideNotification` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `queNewGuideNotification`(IN matchNo INT)
    DETERMINISTIC
BEGIN
	IF matchNo IS NOT NULL THEN
		INSERT INTO apnQueue
		(receiver, pushType, badge, message, sound, extraParams, queueDatetime)
		SELECT
			DISTINCT(m.memberNo),
			1,
			CASE WHEN abc.newMatchAlert = 1 THEN 0
			ELSE 1 
			END AS badge,
			'New guide available!',
			'default',
			'{"type":1}',
			CONCAT(UTC_DATE(), ' ', getPushTime(m.timezoneOffset))
		FROM members m
		LEFT JOIN apnQueueCache aqc
		ON aqc.memberNo = m.memberNo
		AND aqc.queDate = UTC_DATE()
		AND aqc.newGuideAlert = 'Y'
		JOIN matchPool mp
		ON m.memberNo = mp.memberNo
		AND mp.activeSessions = 0
		AND mp.STATUS NOT IN ('A', 'U')
		JOIN matchSessionMembers msm
		ON msm.memberNo = m.memberNo
		AND msm.matchNo = matchNo
		LEFT JOIN apnBadgeCount abc
		ON abc.deviceNo = m.deviceNo
		join apnDevices ad
		on m.deviceNo = ad.deviceNo
		AND (ad.pushBadge = 'enabled' or ad.pushAlert = 'enabled' or ad.pushSound = 'enabled')
		WHERE m.active = 'Y'
		AND m.newMatchAlert = 'Y'
		AND aqc.memberNo IS NULL;
	
		INSERT INTO apnQueueCache (memberNo, queDate, newGuideAlert)
		SELECT
			DISTINCT(m.memberNo),
			UTC_DATE(),
			'Y'
		FROM members m
		LEFT JOIN apnQueueCache aqc
		ON aqc.memberNo = m.memberNo
		AND aqc.queDate = UTC_DATE()
		AND aqc.newGuideAlert = 'Y'
		JOIN matchPool mp
		ON m.memberNo = mp.memberNo
		AND mp.activeSessions = 0
		AND mp.STATUS NOT IN ('A', 'U')
		JOIN matchSessionMembers msm
		ON msm.memberNo = m.memberNo
		AND msm.matchNo = matchNo
		WHERE m.active = 'Y'
		AND m.newMatchAlert = 'Y'
		AND aqc.memberNo IS NULL
		ON DUPLICATE KEY UPDATE newGuideAlert = 'Y';
	ELSE
		INSERT INTO apnQueue
		(receiver, pushType, badge, message, sound, extraParams, queueDatetime)
		SELECT
			DISTINCT(m.memberNo),
			1,
			CASE WHEN abc.newMatchAlert = 1 THEN 0
			ELSE 1 
			END AS badge,
			'New guide available!',
			'default',
			'{"type":1}',
			CONCAT(UTC_DATE(), ' ', getPushTime(m.timezoneOffset))
		FROM members m
		LEFT JOIN apnQueueCache aqc
		ON aqc.memberNo = m.memberNo
		AND aqc.queDate = UTC_DATE()
		AND aqc.newGuideAlert = 'Y'
		JOIN matchPool mp
		ON m.memberNo = mp.memberNo
		AND mp.activeSessions = 0
		AND mp.STATUS not in ('A', 'U')
		LEFT JOIN apnBadgeCount abc
		ON abc.deviceNo = m.deviceNo
		JOIN apnDevices ad
		ON m.deviceNo = ad.deviceNo
		AND (ad.pushBadge = 'enabled' OR ad.pushAlert = 'enabled' OR ad.pushSound = 'enabled')
		WHERE m.active = 'Y'
		AND m.newMatchAlert = 'Y'
		AND aqc.memberNo IS NULL;
	
		INSERT INTO apnQueueCache (memberNo, queDate, newGuideAlert)
		SELECT
			DISTINCT(m.memberNo),
			UTC_DATE(),
			'Y'
		FROM members m
		LEFT JOIN apnQueueCache aqc
		ON aqc.memberNo = m.memberNo
		AND aqc.queDate = UTC_DATE()
		AND aqc.newGuideAlert = 'Y'
		JOIN matchPool mp
		ON m.memberNo = mp.memberNo
		AND mp.activeSessions = 0
		AND mp.STATUS NOT IN ('A', 'U')
		WHERE m.active = 'Y'
		AND m.newMatchAlert = 'Y'
		AND aqc.memberNo IS NULL
		ON DUPLICATE KEY UPDATE newGuideAlert = 'Y';
	END IF;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `queNewMissionNotification` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `queNewMissionNotification`(in matchNo int)
    DETERMINISTIC
BEGIN
	
	
	if matchNo is not null then
		INSERT INTO apnQueue
		(receiver, pushType, badge, message, sound, extraParams, queueDatetime)
		SELECT
			DISTINCT(m.memberNo),
			5,
			CASE WHEN abc.newMissionAlert = 1 THEN 0
			ELSE 1 
			END AS badge,
			'You have new missions!',
			'default',
			'{"type":5}' AS extraParams,
			CONCAT(UTC_DATE(), ' ', getPushTime(m.timezoneOffset))
		FROM members m
		JOIN matchPool mp
		ON m.memberNo = mp.memberNo
		AND mp.activeSessions > 0
		JOIN matchSessionMembers msm
		ON msm.memberNo = m.memberNo
		AND msm.matchNo = matchNo
		LEFT JOIN apnQueueCache aqc
		ON aqc.memberNo = m.memberNo
		AND aqc.queDate = UTC_DATE()
		AND aqc.newMissionAlert = 'Y'
		LEFT JOIN apnBadgeCount abc
		ON abc.deviceNo = m.deviceNo
		JOIN apnDevices ad
		ON m.deviceNo = ad.deviceNo
		AND (ad.pushBadge = 'enabled' OR ad.pushAlert = 'enabled' OR ad.pushSound = 'enabled')
		WHERE m.active = 'Y'
		AND m.newMissionAlert = 'Y'
		AND aqc.memberNo IS NULL;
	
		INSERT INTO apnQueueCache (memberNo, queDate, newMissionAlert)
		SELECT
			DISTINCT(m.memberNo),
			UTC_DATE(),
			'Y'
		FROM members m
		JOIN matchPool mp
		ON m.memberNo = mp.memberNo
		AND mp.activeSessions > 0
		JOIN matchSessionMembers msm
		ON msm.memberNo = m.memberNo
		AND msm.matchNo = matchNo
		LEFT JOIN apnQueueCache aqc
		ON aqc.memberNo = m.memberNo
		AND aqc.queDate = UTC_DATE()
		AND aqc.newMissionAlert = 'Y'
		WHERE m.active = 'Y'
		AND m.newMissionAlert = 'Y'
		AND aqc.memberNo IS NULL
		ON DUPLICATE KEY UPDATE newMissionAlert = 'Y';
	else
		INSERT INTO apnQueue
		(receiver, pushType, badge, message, sound, extraParams, queueDatetime)
		SELECT
			DISTINCT(m.memberNo),
			5,
			CASE WHEN abc.newMissionAlert = 1 THEN 0
			ELSE 1 
			END AS badge,
			'You have new missions!',
			'default',
			'{"type":5}' AS extraParams,
			CONCAT(UTC_DATE(), ' ', getPushTime(m.timezoneOffset))
		FROM members m
		JOIN matchPool mp
		ON m.memberNo = mp.memberNo
		AND mp.activeSessions > 0
		LEFT JOIN apnQueueCache aqc
		ON aqc.memberNo = m.memberNo
		AND aqc.queDate = UTC_DATE()
		AND aqc.newMissionAlert = 'Y'
		LEFT JOIN apnBadgeCount abc
		ON abc.deviceNo = m.deviceNo
		JOIN apnDevices ad
		ON m.deviceNo = ad.deviceNo
		AND (ad.pushBadge = 'enabled' OR ad.pushAlert = 'enabled' OR ad.pushSound = 'enabled')
		WHERE m.active = 'Y'
		AND m.newMissionAlert = 'Y'
		AND aqc.memberNo IS NULL;
	
		INSERT INTO apnQueueCache (memberNo, queDate, newMissionAlert)
		SELECT
			DISTINCT(m.memberNo),
			UTC_DATE(),
			'Y'
		FROM members m
		JOIN matchPool mp
		ON m.memberNo = mp.memberNo
		AND mp.activeSessions > 0
		LEFT JOIN apnQueueCache aqc
		ON aqc.memberNo = m.memberNo
		AND aqc.queDate = UTC_DATE()
		AND aqc.newMissionAlert = 'Y'
		LEFT JOIN apnBadgeCount abc
		ON abc.deviceNo = m.deviceNo
		WHERE m.active = 'Y'
		AND m.newMissionAlert = 'Y'
		AND aqc.memberNo IS NULL
		ON DUPLICATE KEY UPDATE newMissionAlert = 'Y';
	end if;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `refreshApnQueue` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `refreshApnQueue`()
    DETERMINISTIC
BEGIN
	
	call queNewMissionNotification(null);
	
	
	call queNewGuideNotification(null);
	
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `refreshMatchPool` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `refreshMatchPool`()
    DETERMINISTIC
BEGIN
	
	UPDATE matchSessions ms
	SET ms.STATUS = 'X'
	WHERE ms.STATUS = 'Y' AND ms.expireDate < UTC_DATE();
	
	
	UPDATE matchSessions ms
	SET ms.STATUS = 'T'
	WHERE ms.STATUS in ('P', 'Q') AND ms.activeDate < UTC_DATE();
	
	
	UPDATE matchSessions ms
	set ms.status = 'D'
	where ms.status = 'N' AND ms.activeDate < UTC_DATE();
	
	
	UPDATE matchPool mp
	JOIN members m
	ON mp.memberNo = m.memberNo
	JOIN apnDevices ad
	ON m.deviceNo = ad.deviceNo
	and ad.STATUS = 'uninstalled'
	SET mp.STATUS = 'U'
	WHERE mp.STATUS != 'U';
	
	
	UPDATE matchPool mp
	JOIN members m
	ON mp.memberNo = m.memberNo
	SET mp.STATUS = 'S'
	WHERE mp.STATUS = 'P'
	AND (DATEDIFF(m.lastSessionDatetime, UTC_TIMESTAMP()) < -3 OR m.lastSessionDatetime IS NULL);
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `refreshMatchPoolForMember` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `refreshMatchPoolForMember`(in memberNo int)
    DETERMINISTIC
BEGIN
	
	UPDATE matchPool mp
	JOIN 
	(
		SELECT
			msm.memberNo,
			COUNT(ms.matchNo) AS pendingMatches
		FROM matchSessionMembers msm
		LEFT JOIN matchSessions ms
		ON msm.matchNo = ms.matchNo
		AND ms.activeDate = UTC_DATE()
		AND ms.STATUS IN ('P', 'Q')
		AND msm.STATUS NOT IN ('Y', 'N')
		WHERE msm.memberNo = memberNo
		GROUP BY msm.memberNo
	) A
	ON mp.memberNo = A.memberNo
	JOIN 
	(
		SELECT
			msm.memberNo,
			COUNT(ms.matchNo) AS activeMatches
		FROM matchSessionMembers msm
		LEFT JOIN matchSessions ms
		ON msm.matchNo = ms.matchNo
		AND ms.expireDate >= UTC_DATE()
		AND ms.STATUS = 'Y'
		WHERE msm.memberNo = memberNo
		GROUP BY msm.memberNo
	) B
	ON mp.memberNo = B.memberNo
	JOIN
	(
		SELECT
			msm.memberNo,
			COUNT(ms.matchNo) AS queuedMatches
		FROM matchSessionMembers msm
		LEFT JOIN matchSessions ms
		ON msm.matchNo = ms.matchNo
		AND ms.activeDate = UTC_DATE()
		AND ((ms.STATUS = 'P' AND msm.STATUS IN ('Y', 'N')) OR (ms.STATUS = 'N' AND msm.STATUS IN ('Y', 'N') AND ms.isQuickMatch = 'N'))
		WHERE msm.memberNo = memberNo
		GROUP BY msm.memberNo
	) C
	ON mp.memberNo = C.memberNo
	JOIN
	(
		SELECT
			msm.memberNo,
			COUNT(ms.matchNo) AS queuedQuickMatches
		FROM matchSessionMembers msm
		LEFT JOIN matchSessions ms
		ON msm.matchNo = ms.matchNo
		AND ms.STATUS = 'A'
		AND msm.STATUS = 'Y'
		WHERE msm.memberNo = memberNo
		GROUP BY msm.memberNo
	) D
	ON mp.memberNo = D.memberNo
	SET mp.pendingSessions = A.pendingMatches,
	mp.activeSessions = B.activeMatches,
	mp.queuedMatchSessions = C.queuedMatches,
	mp.queuedQuickMatchSessions = D.queuedQuickMatches;
	
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `removeApnQueue` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `removeApnQueue`(in memberNo int, in pushType int)
    DETERMINISTIC
BEGIN
	DELETE FROM apnQueue WHERE apnQueue.receiver = memberNo AND apnQueue.pushType = pushType;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `setFakeMatch` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `setFakeMatch`(IN memberNo INT)
    DETERMINISTIC
BEGIN
	DECLARE matchMemberNo INT;
	DECLARE countryCode CHAR(2);
	
	
	SELECT m.countryCode INTO countryCode
	FROM members m WHERE m.memberNo = memberNo;
	
	SELECT
	m.memberNo INTO matchMemberNo
	FROM matchPool mp
	join members m
	on mp.memberNo = m.memberNo
	and mp.matchGroupNo = getMemberMatchGroupNo(memberNo)
	LEFT JOIN matchSessionMembers msm
	ON m.memberNo = msm.memberNo
	join memberPrivileges mpr
	on m.memberNo = mpr.memberNo
	WHERE m.memberNo != memberNo
	AND m.active = 'Y'
	AND m.memberNo NOT IN (SELECT mml.matchedMemberNo FROM matchMemberLog mml WHERE mml.memberNo = memberNo)
	AND m.countryCode != countryCode
	and m.countryCode NOT IN (SELECT blockCountry FROM matchRules WHERE matchRules.memberNo = memberNo AND UTC_DATE() <= expireDate)
	ORDER BY mpr.matchPriority DESC, SUBSTRING(m.lastSessionDatetime, 1, 10) DESC, m.lastMatchDatetime ASC LIMIT 1;
	
	
	if matchMemberNo is not null then
		UPDATE matchPool SET matchPool.STATUS = 'F' 
		WHERE matchPool.memberNo = memberNo;
		
		
		UPDATE memberPrivileges mpr SET mpr.matchPriority = mpr.matchPriority + 1 WHERE mpr.memberNo = memberNo;
	else
		UPDATE matchPool SET matchPool.STATUS = 'P' 
		WHERE matchPool.memberNo = memberNo;
	end if;
	
	
	SELECT
		-1 as matchNo,
		UTC_TIMESTAMP() as regDatetime,
		UTC_DATE() as activeDate,
		NULL as expireDate,
		m.memberNo,
		m.deviceNo,
		m.email,
		m.firstName,
		m.lastName,
		m.gender,
		m.birthday,
		m.city,
		m.provinceCode,
		m.country,
		m.countryCode,
		m.timezoneOffset,
		m.latitude,
		m.longitude,
		m.intro,
		m.facebookID,
		m.profileImage AS profileImageNo,
		m.active,
		m.lastMatchDatetime,
		age(m.birthday) AS age
	FROM members m
	WHERE m.memberNo = matchMemberNo;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `setMatch` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `setMatch`(IN memberNo INT, IN matchMemberNo INT, IN activeDate DATE)
    DETERMINISTIC
BEGIN
	DECLARE lastMatchNo INT;
	declare matchStatus, matchMemberStatus, quickMatchValue, isOpen char(1);
	declare expireDateValue date;
	
	IF activeDate is null THEN
		SET activeDate = UTC_DATE();
	END IF;
	
	SET matchStatus = 'P';
	SET matchMemberStatus = 'M';
	SET quickMatchValue = 'N';
	SET isOpen = 'N';
	SET expireDateValue = NULL;
	
	
	INSERT INTO matchSessions (memberCount, STATUS, isQuickMatch, open, regDatetime, activeDate, expireDate) VALUES (2, matchStatus, quickMatchValue, isOpen, UTC_TIMESTAMP(), activeDate, expireDateValue);
	
	SET lastMatchNo = LAST_INSERT_ID();
	
	
	INSERT INTO matchSessionMembers (matchNo, memberNo, status, regDatetime)
	VALUES
	(lastMatchNo, memberNo, matchMemberStatus, UTC_TIMESTAMP()),
	(lastMatchNo, matchMemberNo, matchMemberStatus, UTC_TIMESTAMP());
	
	
	INSERT INTO matchMemberLog (matchNo, memberNo, matchedMemberNo, regDatetime, activeDate) 
	VALUES
	(lastMatchNo, memberNo, matchMemberNo, UTC_TIMESTAMP(), UTC_DATE()),
	(lastMatchNo, matchMemberNo, memberNo, UTC_TIMESTAMP(), UTC_DATE());
	
	
	UPDATE matchPool SET matchPool.STATUS = 'M'
	WHERE matchPool.memberNo in (memberNo, matchMemberNo)
	and matchPool.status = 'L';
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `setPushSuccess` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `setPushSuccess`(in queueNo int)
    DETERMINISTIC
BEGIN
	declare receiverNo int;
	UPDATE apnQueue SET apnQueue.pushDatetime = UTC_TIMESTAMP(), apnQueue.status = 'S' 
	WHERE apnQueue.queueNo = queueNo;
	
	select receiver into receiverNo from apnQueue where apnQueue.queueNo = queueNo;
	
	CALL updateBadgeCount(receiverNo);
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `setQuickMatch` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `setQuickMatch`(IN memberNo INT, activeDate DATE, OUT matchNo INT)
BEGIN
	DECLARE matchCount, quickMatchCount, matchLimit, memberNumber INT;
	DECLARE matchStatus, matchMemberStatus, quickMatchValue, isOpen CHAR(1);
	DECLARE expireDateValue DATE;
	
	IF activeDate IS NULL THEN
		SET activeDate = UTC_DATE();
	END IF;
	
	SET matchStatus = 'Q';
	SET matchMemberStatus = 'M';
	SET quickMatchValue = 'Y';
	SET isOpen = 'N';
	SET expireDateValue = DATE_ADD(UTC_DATE(), INTERVAL 6 DAY);
	SET memberNumber = 1;
	
	INSERT INTO matchSessions (memberCount, STATUS, isQuickMatch, OPEN, regDatetime, activeDate, expireDate) VALUES (memberNumber, matchStatus, quickMatchValue, isOpen, UTC_TIMESTAMP(), activeDate, expireDateValue);
	SET matchNo = LAST_INSERT_ID();
	
	
	INSERT INTO matchSessionMembers (matchNo, memberNo, STATUS, regDatetime)
	VALUES
	(matchNo, memberNo, matchMemberStatus, UTC_TIMESTAMP());
	
	
	UPDATE matchPool SET matchPool.STATUS = 'M'
	WHERE matchPool.memberNo = memberNo;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `updateBadgeCount` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `updateBadgeCount`(in memberNo int)
    DETERMINISTIC
BEGIN
	DECLARE done INT DEFAULT 0; 
	DECLARE currentBadgeCount, totalBadgeCount SMALLINT DEFAULT 0;
	DECLARE deviceNo, pushType INT;
	
	DECLARE badgeCursor CURSOR FOR
		SELECT 
			ad.deviceNo, que.pushType, COUNT(que.queueNo)
		FROM members m
		JOIN apnDevices ad
		ON m.deviceNo = ad.deviceNo
		LEFT JOIN apnQueue que
		ON que.receiver = m.memberNo
		AND que.didConfirm = 'N'
		AND que.badge != 0
		AND que.pushDatetime is not null
		WHERE m.memberNo = memberNo
		GROUP BY que.receiver, que.pushType;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	
	OPEN badgeCursor;
	queData: LOOP
		FETCH badgeCursor INTO deviceNo, pushType, currentBadgeCount;
	
		IF done THEN 
			LEAVE queData; 
		END IF;
		INSERT INTO apnBadgeCount VALUES (deviceNo, 0, 0, 0, 0, 0)
		on duplicate key update badgeCount = 0, newMatchAlert = 0, matchSuccessfulAlert = 0, newMessageAlert = 0, newMissionAlert = 0;
		IF pushType = 1 THEN
			if currentBadgeCount > 0 then 
				set currentBadgeCount = 1;
			else
				set currentBadgeCount = 0;
			end if;
	
			update apnBadgeCount set apnBadgeCount.newMatchALert = currentBadgeCount 
			where apnBadgeCount.deviceNo = deviceNo;
			
			SET totalBadgeCount = totalBadgeCount + currentBadgeCount;
		ELSEIF pushType = 2 THEN
			IF currentBadgeCount > 0 THEN 
				SET currentBadgeCount = 1;
			ELSE
				SET currentBadgeCount = 0;
			END IF;
	
			UPDATE apnBadgeCount SET apnBadgeCount.matchSuccessfulAlert = currentBadgeCount 
			WHERE apnBadgeCount.deviceNo = deviceNo;
			
			SET totalBadgeCount = totalBadgeCount + currentBadgeCount;
		ELSEIF pushType = 3 THEN
			UPDATE apnBadgeCount SET apnBadgeCount.newMessageAlert = currentBadgeCount 
			WHERE apnBadgeCount.deviceNo = deviceNo;
			
			SET totalBadgeCount = totalBadgeCount + currentBadgeCount;
		ELSEIF pushType = 5 THEN
			IF currentBadgeCount > 0 THEN 
				SET currentBadgeCount = 1;
			ELSE
				SET currentBadgeCount = 0;
			END IF;
	
			UPDATE apnBadgeCount SET apnBadgeCount.newMissionAlert = currentBadgeCount 
			WHERE apnBadgeCount.deviceNo = deviceNo;
			
			SET totalBadgeCount = totalBadgeCount + currentBadgeCount;
		END IF;
	END LOOP queData;
	CLOSE badgeCursor;
	
	if deviceNo is null then
		set deviceNo = 0;
	end if;
	
	if currentBadgeCount is null then
		set currentBadgeCount = 0;
	end if;
	
	if deviceNo != 0 then
		UPDATE apnBadgeCount SET apnBadgeCount.badgeCount = totalBadgeCount 
		WHERE apnBadgeCount.deviceNo = deviceNo;
	end if;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `updateDeviceNo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `updateDeviceNo`(in memberNo int, in deviceNo int)
    DETERMINISTIC
BEGIN
	UPDATE members SET members.deviceNo = NULL WHERE members.deviceNo = deviceNo and members.memberNo != memberNo;
	UPDATE members SET members.deviceNo = deviceNo WHERE members.memberNo = memberNo;
	select debug from apnDevices where apnDevices.deviceNo = deviceNo;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-06-10 11:59:19
