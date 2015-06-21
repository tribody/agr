-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 01 月 06 日 08:41
-- 服务器版本: 5.5.16
-- PHP 版本: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `smart_agriculture`
--

-- --------------------------------------------------------

--
-- 表的结构 `alarm_message`
--

CREATE TABLE IF NOT EXISTS `alarm_message` (
  `add_mac` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'MAC地址',
  `sensor_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '传感器类型',
  `data` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '报警数据',
  `time` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '报警时间',
  PRIMARY KEY (`add_mac`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='报警信息表';

-- --------------------------------------------------------

--
-- 表的结构 `alarm_setting`
--

CREATE TABLE IF NOT EXISTS `alarm_setting` (
  `add_mac` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'MAC地址',
  `sensor_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '传感器类型',
  `max_value` int(20) DEFAULT NULL COMMENT '最大值',
  `min_value` int(20) DEFAULT NULL COMMENT '最小值',
  PRIMARY KEY (`add_mac`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='报警设置表';

-- --------------------------------------------------------

--
-- 表的结构 `area_message`
--

CREATE TABLE IF NOT EXISTS `area_message` (
  `area` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '实验区',
  `district` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '地区',
  `country` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '国家',
  `number` int(10) DEFAULT NULL COMMENT '大鹏数量',
  KEY `area` (`area`,`district`,`country`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `history_message`
--

CREATE TABLE IF NOT EXISTS `history_message` (
  `add_mac` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'MAC地址',
  `sensor_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '设备类型',
  `data` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '数据值',
  `time` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '时间',
  KEY `add_mac` (`add_mac`),
  KEY `sensor_type` (`sensor_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='传感器数据表';

-- --------------------------------------------------------

--
-- 表的结构 `new_message`
--

CREATE TABLE IF NOT EXISTS `new_message` (
  `add_mac` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'MAC地址',
  `sensor_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '类型',
  `data` int(20) NOT NULL COMMENT '数据',
  `time` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '时间',
  UNIQUE KEY `add_mac` (`add_mac`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `node_message`
--

CREATE TABLE IF NOT EXISTS `node_message` (
  `add_net` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '网络地址',
  `add_mac` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'MAC地址',
  `county` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '国家',
  `district` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '地区',
  `area` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '实验区',
  `zone` int(10) DEFAULT NULL COMMENT '大棚区号',
  `id` int(10) DEFAULT NULL COMMENT '大棚编号',
  `cod_x` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'X坐标',
  `cod_y` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Y坐标',
  `type` int(10) DEFAULT NULL COMMENT '类型编号',
  `crops` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '作物',
  PRIMARY KEY (`add_mac`),
  KEY `add_net` (`add_net`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `node_message`
--

INSERT INTO `node_message` (`add_net`, `add_mac`, `county`, `district`, `area`, `zone`, `id`, `cod_x`, `cod_y`, `type`, `crops`) VALUES
(NULL, '1', NULL, NULL, NULL, 1, 2, '15', '315', 0, NULL),
(NULL, '2', NULL, NULL, NULL, 1, 5, '15', '315', 0, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `role_message`
--

CREATE TABLE IF NOT EXISTS `role_message` (
  `role` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '角色',
  `country` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '国家',
  `district` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '区域',
  `area` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '实验区',
  `show_country` int(10) DEFAULT NULL COMMENT '国家显示',
  `show_district` int(10) DEFAULT NULL COMMENT '区域显示',
  `show_area` int(10) DEFAULT NULL COMMENT '实验区显示',
  `show_dapeng` int(10) DEFAULT NULL COMMENT '大鹏显示',
  `realtime_data` int(10) DEFAULT NULL COMMENT '实时数据',
  `hostory_data` int(10) DEFAULT NULL COMMENT '历史数据',
  `crop_alarm` int(10) DEFAULT NULL COMMENT '作物告警',
  `equipment_alarm` int(10) DEFAULT NULL COMMENT '设备告警',
  `alarm_setting` int(10) DEFAULT NULL COMMENT '告警设置',
  `add_role` int(10) DEFAULT NULL COMMENT '添加角色',
  `alter_role` int(10) DEFAULT NULL COMMENT '修改角色',
  `delete_role` int(10) DEFAULT NULL COMMENT '删除角色',
  `check_role` int(10) DEFAULT NULL COMMENT '查看角色',
  `check_camera` int(10) DEFAULT NULL COMMENT '查看视频',
  `system_manage` int(10) DEFAULT NULL COMMENT '系统管理',
  `video_manage` int(10) DEFAULT NULL COMMENT '录像管理',
  `playback_download` int(10) DEFAULT NULL COMMENT '回看下载',
  `reverse_control` int(10) DEFAULT NULL COMMENT '反向控制',
  `take_picture` int(10) DEFAULT NULL COMMENT '图片拍摄',
  `system_expert` int(10) DEFAULT NULL COMMENT '专家系统',
  KEY `role` (`role`,`country`,`district`,`area`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `role_message`
--

INSERT INTO `role_message` (`role`, `country`, `district`, `area`, `show_country`, `show_district`, `show_area`, `show_dapeng`, `realtime_data`, `hostory_data`, `crop_alarm`, `equipment_alarm`, `alarm_setting`, `add_role`, `alter_role`, `delete_role`, `check_role`, `check_camera`, `system_manage`, `video_manage`, `playback_download`, `reverse_control`, `take_picture`, `system_expert`) VALUES
('领导', '中国', '上海', '孙桥', 1, 1, 100, 100, 100, 100, 100, 100, 0, 0, 100, 0, 0, 100, 0, 0, 0, NULL, NULL, NULL),
('超级管理员', NULL, NULL, NULL, 1, 1, 111, 111, 111, 111, 111, 111, 111, 111, 111, 111, 111, 111, 111, 111, 111, NULL, NULL, NULL),
('管理员', '中国', '上海', '孙桥', 1, 1, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, NULL, NULL, NULL),
('管理员', '中国', '上海', '延长', 1, 1, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, NULL, NULL, NULL),
('操作员', '中国', '上海', '孙桥', 1, 1, 100, 100, 100, 100, 100, 100, 0, 0, 100, 0, 0, 100, 0, 0, 0, NULL, NULL, NULL),
('操作员', '中国', '上海', '延长', 1, 1, 10, 10, 10, 10, 10, 10, 0, 0, 10, 0, 0, 10, 0, 0, 0, NULL, NULL, NULL),
('研究人员', '中国', '上海', '孙桥', 1, 1, 100, 100, 100, 100, 100, 100, 0, 0, 100, 0, 0, 100, 0, 0, 0, NULL, NULL, NULL),
('研究人员', '中国', '上海', '延长', 1, 1, 10, 10, 10, 10, 10, 10, 0, 0, 10, 0, 0, 10, 0, 0, 0, NULL, NULL, NULL),
('领导', '中国', '上海', '延长', 1, 1, 10, 10, 10, 10, 10, 10, 0, 0, 10, 0, 0, 10, 0, 0, 0, NULL, NULL, NULL),
('领导', '中国', '上海', '徐汇区', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 0, 0, 1, 0, 0, 0, NULL, NULL, NULL),
('管理员', '中国', '上海', '徐汇区', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, NULL, NULL, NULL),
('操作员', '中国', '上海', '徐汇区', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 0, 0, 1, 0, 0, 0, NULL, NULL, NULL),
('研究人员', '中国', '上海', '徐汇区', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 0, 0, 1, 0, 0, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `user_message`
--

CREATE TABLE IF NOT EXISTS `user_message` (
  `user` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `password` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `role` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用户角色',
  `country` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '国家',
  `district` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '地区',
  `area` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '实验区',
  PRIMARY KEY (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='用户信息表';

--
-- 转存表中的数据 `user_message`
--

INSERT INTO `user_message` (`user`, `password`, `role`, `country`, `district`, `area`) VALUES
('1', '1', '超级管理员', '中国', '上海', '孙桥'),
('admin', 'admin', '超级管理员', '中国', '上海', '延长');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
