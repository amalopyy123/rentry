/*
Navicat MySQL Data Transfer

Source Server         : mysql
Source Server Version : 50736
Source Host           : localhost:3306
Source Database       : rentry

Target Server Type    : MYSQL
Target Server Version : 50736
File Encoding         : 65001

Date: 2024-11-30 13:37:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for rentry_items
-- ----------------------------
DROP TABLE IF EXISTS `rentry_items`;
CREATE TABLE `rentry_items` (
  `nId` bigint(65) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`nId`),
  UNIQUE KEY `nuique_url` (`path`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of rentry_items
-- ----------------------------
INSERT INTO `rentry_items` VALUES ('1', 'path', 'abc123', 'hello world!', '2024-11-30 12:28:53');
INSERT INTO `rentry_items` VALUES ('2', 'test', 'testpwd', 'test content', '2024-11-30 13:01:57');
INSERT INTO `rentry_items` VALUES ('3', 'test1', 'test1', 'test1 content', '2024-11-30 13:31:22');
