###########################################################################
#                                                                         #
#  schema.sql                       rlm_sql - FreeRADIUS SQL Module       #
#                                                                         #
#     Database schema for MySQL rlm_sql module - Edited for NetItWorks    #
#                                                                         #
#     To load:                                                            #
#         mysql -uroot -prootpass radius < schema.sql                     #
#                                                                         #
#                                   George Mess <4onwb@protonmail.com>    #
###########################################################################

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
-- Table structure for table `net_user`
--

DROP TABLE IF EXISTS `net_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `net_user` (
  `id` varchar(17) NOT NULL,
  `type` varchar(16) NOT NULL DEFAULT 'authenticated',
  `password` varchar(32) DEFAULT NULL,
  `status` varchar(8) NOT NULL DEFAULT 'active',
  `phone` varchar(16) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `ip_limitation_status` tinyint(1) NOT NULL DEFAULT 0,
  `hw_limitation_status` tinyint(1) NOT NULL DEFAULT 0,
  `ip_range_start` varchar(16) DEFAULT NULL,
  `ip_range_stop` varchar(16) DEFAULT NULL,
  `active_net_group` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `net_group`
--

DROP TABLE IF EXISTS `net_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `net_group` (
  `name` varchar(16) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `description` varchar(255) DEFAULT NULL,
  `net_type` int(2) NOT NULL,
  `net_attribute_type` int(2) NOT NULL,
  `net_vlan_id` int(3) NOT NULL DEFAULT 1,
  `ip_limitation_status` tinyint(1) NOT NULL DEFAULT 0,
  `hw_limitation_status` tinyint(1) NOT NULL DEFAULT 0,
  `ip_range_start` varchar(16) DEFAULT NULL,
  `ip_range_stop` varchar(16) DEFAULT NULL,
  `user_auto_registration` tinyint(1) NOT NULL DEFAULT 0,
  `user_require_admin_approval` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `user_group_partecipation`
--

DROP TABLE IF EXISTS `user_group_partecipation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_group_partecipation` (
  `user_id` varchar(17) NOT NULL REFERENCES net_user(id),
  `group_name` varchar(16) NOT NULL REFERENCES net_group(name),
  `priority` int(1) NOT NULL,
  PRIMARY KEY (`user_id`,`group_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `registered_device`
--

DROP TABLE IF EXISTS `registered_device`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registered_device` (
  `mac_address` varchar(17) NOT NULL,
  `time_added` datetime DEFAULT NULL,
  `vendor` varchar(16) DEFAULT NULL,
  `model` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`mac_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_hw_limitation`
--

DROP TABLE IF EXISTS `user_hw_limitation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_hw_limitation` (
  `user_id` varchar(17) NOT NULL REFERENCES net_user(id),
  `mac_address` varchar(17) NOT NULL REFERENCES registered_device(mac_address),
  PRIMARY KEY (`user_id`,`mac_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `group_hw_limitation`
--

DROP TABLE IF EXISTS `group_hw_limitation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_hw_limitation` (
  `group_name` varchar(16) NOT NULL REFERENCES net_group(name),
  `mac_address` varchar(17) NOT NULL REFERENCES registered_device(mac_address),
  PRIMARY KEY (`group_name`, `mac_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `network`
--

DROP TABLE IF EXISTS `network`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `network` (
  `address` varchar(16) NOT NULL,
  `radius_server_ip` varchar(16) NOT NULL,
  `radius_server_port` int(5) NOT NULL,
  `radius_server_secret` varchar(128) NOT NULL,
  PRIMARY KEY (`address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `client_session_log`
--

DROP TABLE IF EXISTS `client_session_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_session_log` (
  `mac_address` varchar(17) NOT NULL,
  `user_name` varchar(17) DEFAULT NULL REFERENCES `net_user`(`id`),
  `ap_id` varchar(48) NOT NULL,
  `client_ip` varchar(16) NOT NULL,
  `first_seen_datetime` datetime DEFAULT NULL,
  `last_seen_datetime` datetime DEFAULT NULL,
  `input_bytes_session` int(24) DEFAULT 0,
  `output_bytes_session` int(24) DEFAULT 0,
  `session_termination_cause` varchar(32) NOT NULL,
  PRIMARY KEY (`mac_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_access_log`
--

DROP TABLE IF EXISTS `client_access_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_access_log` (
  `mac_address` varchar(17) NOT NULL,
  `user_name` varchar(16) DEFAULT NULL REFERENCES `net_user`(`id`),
  `date_time` datetime NOT NULL,
  `ap_id` varchar(48) NOT NULL,
  `reply_status` varchar(16) NOT NULL,
  `reply_net_type` varchar(16) NOT NULL,
  `reply_net_attribute_type` varchar(16) NOT NULL,
  `reply_net_vlan_id` varchar(16) NOT NULL,
  PRIMARY KEY (`mac_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `controller_config_log`
--

DROP TABLE IF EXISTS `controller_config_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `controller_config_log` (
  `date_time` datetime NOT NULL,
  `editor_name` varchar(128) NOT NULL REFERENCES `net_user`(`id`),
  `operation_type` varchar(8) NOT NULL,
  `operation_details` varchar(64) NOT NULL,
  PRIMARY KEY (`date_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `mail_log`
--

DROP TABLE IF EXISTS `mail_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail_log` (
  `date_time` datetime NOT NULL,
  `sender_address` varchar(128) NOT NULL,
  `destination_address` varchar(128) NOT NULL,
  `subject` varchar(128) NOT NULL,
  PRIMARY KEY (`date_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nas`
--

DROP TABLE IF EXISTS `nas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nas` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nasname` varchar(128) NOT NULL,
  `shortname` varchar(32) DEFAULT NULL,
  `type` varchar(30) DEFAULT 'other',
  `ports` int(5) DEFAULT NULL,
  `secret` varchar(60) NOT NULL DEFAULT 'secret',
  `server` varchar(64) DEFAULT NULL,
  `community` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT 'RADIUS Client',
  PRIMARY KEY (`id`),
  KEY `nasname` (`nasname`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
