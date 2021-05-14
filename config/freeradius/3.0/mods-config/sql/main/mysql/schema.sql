 ###########################################################################
 #  schema.sql                     rlm_sql - FreeRADIUS SQL Module         #
 #                                                                         #
 #     Database schema for MySQL rlm_sql module - Edited for NetItWorks    #
 #                                                                         #
 #     To load:                                                            #
 #         mysql -uroot -prootpass radius < schema.sql                     #
 #                                                                         #
 #                                   George Mess <4onwb@protonmail.com>    #
 ###########################################################################

--
-- Table structure for table `nas`
--

DROP TABLE IF EXISTS `nas`;
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `net_user`
--

DROP TABLE IF EXISTS `net_user`;
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

--
-- Table structure for table `net_group`
--

DROP TABLE IF EXISTS `net_group`;
CREATE TABLE `net_group` (
  `name` varchar(16) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `admin_privilege` tinyint(1) NOT NULL DEFAULT 0,
  `description` varchar(255) DEFAULT NULL,
  `net_type` int(2) NOT NULL,
  `net_attribute_type` int(2) NOT NULL,
  `net_vlan_id` int(3) NOT NULL DEFAULT 1,
  `ip_limitation_status` tinyint(1) NOT NULL DEFAULT 0,
  `hw_limitation_status` tinyint(1) NOT NULL DEFAULT 0,
  `ip_range_start` varchar(16) DEFAULT NULL,
  `ip_range_stop` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `registered_device`
--

DROP TABLE IF EXISTS `registered_device`;
CREATE TABLE `registered_device` (
  `mac_address` varchar(17) NOT NULL,
  `time_added` datetime DEFAULT NULL,
  `vendor` varchar(16) DEFAULT NULL,
  `model` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`mac_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `user_group_partecipation`
--

DROP TABLE IF EXISTS `user_group_partecipation`;
CREATE TABLE `user_group_partecipation` (
  `user_id` varchar(17) NOT NULL,
  `group_name` varchar(16) NOT NULL,
  PRIMARY KEY (`user_id`,`group_name`),
  FOREIGN KEY (`user_id`) REFERENCES `net_user` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`group_name`) REFERENCES `net_group` (`name`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `user_hw_limitation`
--

DROP TABLE IF EXISTS `user_hw_limitation`;
CREATE TABLE `user_hw_limitation` (
  `user_id` varchar(17) NOT NULL,
  `mac_address` varchar(17) NOT NULL,
  PRIMARY KEY (`user_id`,`mac_address`),
  FOREIGN KEY (`user_id`) REFERENCES `net_user` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`mac_address`) REFERENCES `registered_device` (`mac_address`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `group_hw_limitation`
--

DROP TABLE IF EXISTS `group_hw_limitation`;
CREATE TABLE `group_hw_limitation` (
  `group_name` varchar(16) NOT NULL,
  `mac_address` varchar(17) NOT NULL,
  PRIMARY KEY (`group_name`,`mac_address`),
  FOREIGN KEY (`group_name`) REFERENCES `net_group` (`name`) ON DELETE CASCADE,
  FOREIGN KEY (`mac_address`) REFERENCES `registered_device` (`mac_address`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `client_access_log`
--

DROP TABLE IF EXISTS `client_access_log`;
CREATE TABLE `client_access_log` (
  `mac_address` varchar(17) NOT NULL,
  `user_name` varchar(16) DEFAULT NULL,
  `date_time` datetime NOT NULL,
  `ap_id` varchar(48) NOT NULL,
  `reply_status` varchar(16) NOT NULL,
  `reply_net_type` varchar(16) NOT NULL,
  `reply_net_attribute_type` varchar(16) NOT NULL,
  `reply_net_vlan_id` varchar(16) NOT NULL,
  PRIMARY KEY (`user_name`,`mac_address`),
  FOREIGN KEY (`user_name`) REFERENCES `net_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `client_session_log`
--

DROP TABLE IF EXISTS `client_session_log`;
CREATE TABLE `client_session_log` (
  `mac_address` varchar(17) NOT NULL,
  `user_name` varchar(17) DEFAULT NULL,
  `ap_id` varchar(48) NOT NULL,
  `client_ip` varchar(16) NOT NULL,
  `first_seen_datetime` datetime DEFAULT NULL,
  `last_seen_datetime` datetime DEFAULT NULL,
  `input_bytes_session` int(24) DEFAULT 0,
  `output_bytes_session` int(24) DEFAULT 0,
  `session_termination_cause` varchar(32) NOT NULL,
  PRIMARY KEY (`user_name`,`mac_address`),
  FOREIGN KEY (`user_name`) REFERENCES `net_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `controller_config_log`
--

DROP TABLE IF EXISTS `controller_config_log`;
CREATE TABLE `controller_config_log` (
  `editor_name` varchar(128) NOT NULL REFERENCES `net_user` (`id`),
  `date_time` datetime NOT NULL,
  `operation_type` varchar(8) NOT NULL,
  `operation_details` varchar(64) NOT NULL,
  PRIMARY KEY (`editor_name`, `date_time`),
  FOREIGN KEY (`editor_name`) REFERENCES `net_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Table structure for table `mail_log`
--

DROP TABLE IF EXISTS `mail_log`;
CREATE TABLE `mail_log` (
  `date_time` datetime NOT NULL,
  `sender_address` varchar(128) NOT NULL,
  `destination_address` varchar(128) NOT NULL,
  `subject` varchar(128) NOT NULL,
  PRIMARY KEY (`date_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
