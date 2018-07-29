# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.22)
# Database: phalconcmf
# Generation Time: 2018-07-29 16:01:25 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table admin_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_role`;

CREATE TABLE `admin_role` (
  `admin_role_id` smallint(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` char(64) NOT NULL DEFAULT '' COMMENT '角色名',
  `description` text NOT NULL COMMENT '角色描述',
  PRIMARY KEY (`admin_role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `admin_role` WRITE;
/*!40000 ALTER TABLE `admin_role` DISABLE KEYS */;

INSERT INTO `admin_role` (`admin_role_id`, `role_name`, `description`)
VALUES
	(1,'超级管理员','拥有所有权限并且不可被删除'),
	(2,'普通管理员','普通管理员');

/*!40000 ALTER TABLE `admin_role` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_role_auth
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_role_auth`;

CREATE TABLE `admin_role_auth` (
  `admin_role_auth_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_role_id` smallint(5) NOT NULL,
  `menu_id` smallint(5) NOT NULL,
  PRIMARY KEY (`admin_role_auth_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色权限表';

LOCK TABLES `admin_role_auth` WRITE;
/*!40000 ALTER TABLE `admin_role_auth` DISABLE KEYS */;

INSERT INTO `admin_role_auth` (`admin_role_auth_id`, `admin_role_id`, `menu_id`)
VALUES
	(24,2,2),
	(25,2,4),
	(29,2,5),
	(30,2,6);

/*!40000 ALTER TABLE `admin_role_auth` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_user`;

CREATE TABLE `admin_user` (
  `admin_user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(64) NOT NULL DEFAULT '',
  `password` char(32) DEFAULT '',
  `nickname` char(32) NOT NULL DEFAULT '',
  `salt` char(10) DEFAULT '',
  PRIMARY KEY (`admin_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `admin_user` WRITE;
/*!40000 ALTER TABLE `admin_user` DISABLE KEYS */;

INSERT INTO `admin_user` (`admin_user_id`, `username`, `password`, `nickname`, `salt`)
VALUES
	(1,'admin','940eace6ed64673b5de2ea7976a57685','老子是超管','abcd'),
	(4,'test','da6d40dc4d65dd263e57088042084354','测试账号','HRL3FmpZKa');

/*!40000 ALTER TABLE `admin_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_user_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_user_role`;

CREATE TABLE `admin_user_role` (
  `admin_user_role_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_user_id` int(10) unsigned NOT NULL,
  `admin_role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`admin_user_role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `admin_user_role` WRITE;
/*!40000 ALTER TABLE `admin_user_role` DISABLE KEYS */;

INSERT INTO `admin_user_role` (`admin_user_role_id`, `admin_user_id`, `admin_role_id`)
VALUES
	(1,1,1);

/*!40000 ALTER TABLE `admin_user_role` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `menu`;

CREATE TABLE `menu` (
  `menu_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(32) NOT NULL,
  `controller` char(32) NOT NULL DEFAULT '',
  `action` char(32) NOT NULL DEFAULT '',
  `pid` smallint(5) unsigned DEFAULT '0',
  `is_show` tinyint(3) DEFAULT '0',
  `description` text,
  `order` int(10) unsigned DEFAULT '0',
  `icon` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`menu_id`),
  UNIQUE KEY `unique_action` (`controller`,`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='操作节点表';

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;

INSERT INTO `menu` (`menu_id`, `name`, `controller`, `action`, `pid`, `is_show`, `description`, `order`, `icon`)
VALUES
	(1,'角色列表','role','list',11,1,'',NULL,'glyphicon glyphicon-eye-open'),
	(2,'编辑角色','role','edit',1,0,'',0,''),
	(3,'删除角色','role','delete',0,0,'',NULL,''),
	(4,'角色授权','role','auth',1,0,'',0,''),
	(5,'菜单列表','menu','list',10,1,'',NULL,'glyphicon glyphicon-menu-hamburger'),
	(6,'编辑菜单','menu','edit',5,0,'',0,''),
	(10,'系统设置','system','setting',0,1,'',NULL,'glyphicon glyphicon-cog'),
	(11,'用户管理','user','default',0,1,'',NULL,'glyphicon glyphicon-user'),
	(12,'账号列表','adminuser','list',11,1,'',NULL,'glyphicon glyphicon-tasks');

/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
