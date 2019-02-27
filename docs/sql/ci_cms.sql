/*
Navicat MySQL Data Transfer

Source Server         : 192.168.100.40
Source Server Version : 50722
Source Host           : 192.168.100.40:3306
Source Database       : ci_cms

Target Server Type    : MYSQL
Target Server Version : 50722
File Encoding         : 65001

Date: 2019-02-11 11:53:57
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('1', '2014_10_12_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('2', '2014_10_12_100000_create_password_resets_table', '1');

-- ----------------------------
-- Table structure for nodes
-- ----------------------------
DROP TABLE IF EXISTS `nodes`;
CREATE TABLE `nodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'URL',
  `icon` varchar(255) NOT NULL DEFAULT '' COMMENT '图标',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `comment` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0禁用，1启用）',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COMMENT='节点表';

-- ----------------------------
-- Records of nodes
-- ----------------------------
INSERT INTO `nodes` VALUES ('1', '0', '根节点', '/', '', '0', '', '1', '2018-11-03 22:39:04', '2018-11-03 22:39:04');
INSERT INTO `nodes` VALUES ('2', '1', '首页', '/admin/index', 'fa fa-dashboard', '0', '', '1', null, '2018-11-04 09:39:31');
INSERT INTO `nodes` VALUES ('3', '1', '系统', '/node/aaa', 'fa fa-desktop', '0', '', '1', null, null);
INSERT INTO `nodes` VALUES ('4', '3', '菜单管理', '/node/index', 'fa fa-bars', '1', 'c1', '1', null, null);
INSERT INTO `nodes` VALUES ('5', '4', '子菜单管理2', '/menu/submenu2', '', '2', 'c2', '1', null, null);
INSERT INTO `nodes` VALUES ('6', '4', '子3', '/menu/submenu3', 'fa fa-link', '10', 'c3', '1', null, null);
INSERT INTO `nodes` VALUES ('7', '6', '子31', '/menu/submenu3/ss1?aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbcccccccccccccccccccccccccccccccccccccccccddddddddddddddddddddddddddddddddeeeeeeeeeeeeeeeeeeeeeeeeeeeeeffffffffffffffffffffffffffffffffffffffgggggggggggggggg', 'fa fa-link', '0', '', '1', null, null);
INSERT INTO `nodes` VALUES ('8', '3', '系统管理', '/system/index', '', '0', '', '1', null, null);
INSERT INTO `nodes` VALUES ('9', '8', '服务器状态', '/system/status', '', '2', '', '1', null, null);
INSERT INTO `nodes` VALUES ('10', '1', '营销', '/marketing', '', '0', '', '1', null, null);
INSERT INTO `nodes` VALUES ('11', '1', '运营', '/operation', '', '0', '', '1', null, null);

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for role_node
-- ----------------------------
DROP TABLE IF EXISTS `role_node`;
CREATE TABLE `role_node` (
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `node_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '节点ID',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色节点表';

-- ----------------------------
-- Records of role_node
-- ----------------------------

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `comment` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0禁用，1启用）',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色表';

-- ----------------------------
-- Records of roles
-- ----------------------------

-- ----------------------------
-- Table structure for systems
-- ----------------------------
DROP TABLE IF EXISTS `systems`;
CREATE TABLE `systems` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '系统名称',
  `domain` varchar(255) NOT NULL DEFAULT '' COMMENT '系统域名（不含协议）',
  `slots` varchar(255) NOT NULL DEFAULT '' COMMENT '槽点（用|分割，如s1|s2|s3）',
  `app_id` char(32) NOT NULL DEFAULT '' COMMENT '业务系统唯一凭证',
  `app_secret` char(32) NOT NULL DEFAULT '' COMMENT '业务系统唯一凭证密码',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='业务系统表';

-- ----------------------------
-- Records of systems
-- ----------------------------
INSERT INTO `systems` VALUES ('1', '媒体编辑系统', 'cutwing', 's1|s2|s3', 'ecd13523a06c8d15cfb2be584eaa5528', '86a3ac64244ff3fce9f329d5da2a4a5d', '2018-08-25 22:00:35', '2018-08-25 22:00:35');

-- ----------------------------
-- Table structure for user_role
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户角色表';

-- ----------------------------
-- Records of user_role
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------

-- ----------------------------
-- Table structure for users_bak
-- ----------------------------
DROP TABLE IF EXISTS `users_bak`;
CREATE TABLE `users_bak` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '邮箱',
  `password` char(40) NOT NULL DEFAULT '' COMMENT '密码',
  `register_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后一次登录时间',
  `last_login_ip` char(15) NOT NULL DEFAULT '' COMMENT '最后一次登录IP',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态（0禁用，1启动）',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

-- ----------------------------
-- Records of users_bak
-- ----------------------------
