/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 80012
 Source Host           : localhost:3306
 Source Schema         : nuxt_bt

 Target Server Type    : MySQL
 Target Server Version : 80012
 File Encoding         : 65001

 Date: 13/07/2020 18:40:16
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for nt_admin
-- ----------------------------
DROP TABLE IF EXISTS `nt_admin`;
CREATE TABLE `nt_admin`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户令牌',
  `token_timeout` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '令牌过期时间',
  `is_lock` tinyint(4) NOT NULL DEFAULT 0 COMMENT '用户是否锁定',
  `registered_time` datetime(0) NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of nt_admin
-- ----------------------------
INSERT INTO `nt_admin` VALUES (1, 'BLADE', '5a0add85cc963048bdd84cfd99f2d637', 'f0d2d42c1a33a14480a2ef481d00d03494c948ab', '1595240982', 0, '2020-05-21 13:43:38');
INSERT INTO `nt_admin` VALUES (2, 'BLADE2', '5a0add85cc963048bdd84cfd99f2d637', 'f55d08e630c603a9e3ffdbe9abba8525e304aaee', '1591099707', 5, '2020-05-21 13:43:38');
INSERT INTO `nt_admin` VALUES (3, 'BLADE3', '5a0add85cc963048bdd84cfd99f2d637', 'f55d08e630c603a9e3ffdbe9abba8525e304aaee', '1591099707', 5, '2020-05-21 13:43:38');
INSERT INTO `nt_admin` VALUES (4, 'BLADE4', '5a0add85cc963048bdd84cfd99f2d637', 'f55d08e630c603a9e3ffdbe9abba8525e304aaee', '1591099707', 5, '2020-05-21 13:43:38');
INSERT INTO `nt_admin` VALUES (5, 'BLADE5', '5a0add85cc963048bdd84cfd99f2d637', 'f55d08e630c603a9e3ffdbe9abba8525e304aaee', '1591099707', 5, '2020-05-21 13:43:38');
INSERT INTO `nt_admin` VALUES (6, 'BLADE6', '5a0add85cc963048bdd84cfd99f2d637', 'f55d08e630c603a9e3ffdbe9abba8525e304aaee', '1591099707', 5, '2020-05-21 13:43:38');
INSERT INTO `nt_admin` VALUES (7, 'BLADE7', '5a0add85cc963048bdd84cfd99f2d637', 'f55d08e630c603a9e3ffdbe9abba8525e304aaee', '1591099707', 5, '2020-05-21 13:43:38');
INSERT INTO `nt_admin` VALUES (8, 'BLADE8', '5a0add85cc963048bdd84cfd99f2d637', 'f55d08e630c603a9e3ffdbe9abba8525e304aaee', '1591099707', 5, '2020-05-21 13:43:38');
INSERT INTO `nt_admin` VALUES (9, 'BLADE9', '5a0add85cc963048bdd84cfd99f2d637', 'f55d08e630c603a9e3ffdbe9abba8525e304aaee', '1591099707', 5, '2020-05-21 13:43:38');
INSERT INTO `nt_admin` VALUES (10, 'BLADE10', '5a0add85cc963048bdd84cfd99f2d637', 'f55d08e630c603a9e3ffdbe9abba8525e304aaee', '1591099707', 5, '2020-05-21 13:43:38');
INSERT INTO `nt_admin` VALUES (11, 'BLADE11', '5a0add85cc963048bdd84cfd99f2d637', 'f55d08e630c603a9e3ffdbe9abba8525e304aaee', '1591099707', 5, '2020-05-21 13:43:38');
INSERT INTO `nt_admin` VALUES (12, 'BLADE12', '5a0add85cc963048bdd84cfd99f2d637', 'f55d08e630c603a9e3ffdbe9abba8525e304aaee', '1591099707', 5, '2020-05-21 13:43:38');
INSERT INTO `nt_admin` VALUES (13, 'BLADE13', '5a0add85cc963048bdd84cfd99f2d637', 'f55d08e630c603a9e3ffdbe9abba8525e304aaee', '1591099707', 5, '2020-05-21 13:43:38');

-- ----------------------------
-- Table structure for nt_admin_to_role
-- ----------------------------
DROP TABLE IF EXISTS `nt_admin_to_role`;
CREATE TABLE `nt_admin_to_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `adminid` int(11) NOT NULL COMMENT '用户ID',
  `roleid` int(11) NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of nt_admin_to_role
-- ----------------------------
INSERT INTO `nt_admin_to_role` VALUES (1, 1, 1);
INSERT INTO `nt_admin_to_role` VALUES (2, 2, 1);

-- ----------------------------
-- Table structure for nt_permission
-- ----------------------------
DROP TABLE IF EXISTS `nt_permission`;
CREATE TABLE `nt_permission`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `permission_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '权限名',
  `permission_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '权限介绍',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of nt_permission
-- ----------------------------
INSERT INTO `nt_permission` VALUES (1, 'admin', '用户表的管理权限');
INSERT INTO `nt_permission` VALUES (2, 'relation', '订单表的管理权限');

-- ----------------------------
-- Table structure for nt_relation
-- ----------------------------
DROP TABLE IF EXISTS `nt_relation`;
CREATE TABLE `nt_relation`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order` int(64) NOT NULL COMMENT '订单号',
  `article` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文件位置',
  `is_select` tinyint(4) NOT NULL COMMENT '是否完成',
  `return` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '返回报告的位置',
  `purchase_time` datetime(0) NOT NULL COMMENT '提交时间',
  `result_time` datetime(0) NULL DEFAULT NULL COMMENT '返回时间',
  `money` double NOT NULL COMMENT '交易金额',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of nt_relation
-- ----------------------------
INSERT INTO `nt_relation` VALUES (1, 3333333, '33333333', 0, NULL, '2020-05-22 12:12:31', NULL, 0);
INSERT INTO `nt_relation` VALUES (2, 33333332, '333333332', 0, NULL, '2020-05-22 12:14:54', NULL, 0);

-- ----------------------------
-- Table structure for nt_role
-- ----------------------------
DROP TABLE IF EXISTS `nt_role`;
CREATE TABLE `nt_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '角色名',
  `role_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '角色说明',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of nt_role
-- ----------------------------
INSERT INTO `nt_role` VALUES (1, '管理员', '后台权限最高角色，拥有所有权限');
INSERT INTO `nt_role` VALUES (2, '订单处理员', '只可以查看订单表，获取论文查重后返回结果');

-- ----------------------------
-- Table structure for nt_role_to_permission
-- ----------------------------
DROP TABLE IF EXISTS `nt_role_to_permission`;
CREATE TABLE `nt_role_to_permission`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `roleid` int(11) NOT NULL COMMENT '角色ID',
  `permissionid` int(11) NOT NULL COMMENT '权限ID',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of nt_role_to_permission
-- ----------------------------
INSERT INTO `nt_role_to_permission` VALUES (1, 1, 1);
INSERT INTO `nt_role_to_permission` VALUES (2, 1, 2);
INSERT INTO `nt_role_to_permission` VALUES (3, 2, 2);

SET FOREIGN_KEY_CHECKS = 1;
