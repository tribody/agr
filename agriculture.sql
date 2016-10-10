-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2014 年 11 月 15 日 10:56
-- 服务器版本: 5.6.12-log
-- PHP 版本: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `agriculture`
--
CREATE DATABASE IF NOT EXISTS `agriculture` DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci;
USE `agriculture`;

-- --------------------------------------------------------

--
-- 表的结构 `alarm_message`
--
-- 报警信息表，用于处理超过data超过上下限的数据
CREATE TABLE IF NOT EXISTS `alarm_message` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '序号',
  `zone` int(10) NOT NULL COMMENT '大棚区号',
  `greenhouse_id` int(11) NOT NULL COMMENT '大棚编号',
  `sensor_id` int(10) NOT NULL COMMENT '设备编号',
  `add_mac` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'MAC地址',
  `crops` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '作物种类',
  `sensor_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '传感器类型',
  `data` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '报警数据',
  `time` datetime NOT NULL DEFAULT NOW() COMMENT '报警时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='报警信息表';

--
-- 转存表中的数据 `alarm_message`
--

INSERT INTO `alarm_message` (`zone`, `greenhouse_id`, `sensor_id`,`add_mac`,`crops`, `sensor_type`, `data`) VALUES
(1, 1, 1,'12.23.45', '小麦','空气温度', '8.7');

-- --------------------------------------------------------

--
-- 表的结构 `alarm_setting`
--

CREATE TABLE IF NOT EXISTS `alarm_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '序列',
  `zone` int(10) NOT NULL COMMENT '大棚区号',
  `crops` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sensor_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '传感器类型',
  `max_value` float DEFAULT NULL COMMENT '最大值',
  `min_value` float DEFAULT NULL COMMENT '最小值',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`zone`,`crops`,`sensor_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='报警设置表';

--
-- 转存表中的数据 `alarm_setting`
--

INSERT INTO `alarm_setting` (`zone`,`crops`,`sensor_type`, `max_value`, `min_value`) VALUES
(1,'大豆','二氧化碳（%）', 0.7, 0.02), 
(2,'玉米','光照强度', 60, 6),
(2,'红豆','土壤温度（C）', 38, 3),
(3,'田鼠','土壤湿度（%）', 50, 10),
(1,'何川','空气温度（C）', 32, 0),
(2,'土豆','空气湿度（%）', 20, 1);

-- --------------------------------------------------------

--
-- 表的结构 `area_message`
--

CREATE TABLE IF NOT EXISTS `area_message` (
  `country` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '国家',
  `area` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '实验区',
  `district` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '地区',
  `number` int(10) DEFAULT NULL COMMENT '大棚数量'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='大棚区域信息表';

-- --------------------------------------------------------

--
-- 表的结构 `crops_message`
--

CREATE TABLE IF NOT EXISTS `crops_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zone` int(11) NOT NULL DEFAULT '1' COMMENT '大棚区号',
  `greenhouse_id` int(11) NOT NULL DEFAULT '1' COMMENT '大棚编号',
  `crops` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `crops_message`
--

INSERT INTO `crops_message` (`zone`, `greenhouse_id`,`crops`) VALUES
(1,1,'水稻'),
(1,1,'小麦'),
(1,2,'大豆');

-- --------------------------------------------------------



-- 大棚信息表
-- greenhouse_message  id country district area zone greenhoust_id cod_x cod_y
CREATE TABLE IF NOT EXISTS `greenhouse_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '国家',
  `district` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '城市',
  `area` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '实验区',
  `zone` int(10) NOT NULL COMMENT '大棚区号',
  `greenhouse_id` int(11) NOT NULL COMMENT '大棚编号',
  `cod_x` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cod_y` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
INSERT INTO  `greenhouse_message` (`country`,`district`,`area`,`zone`,`greenhouse_id`,`cod_x`,`cod_y`) VALUES 
('中国','上海','孙桥','3','3','121.640452','31.214892'),('中国','上海','徐汇','2','2','121.441532','31.206985'),('中国','上海','延长','1','1','121.464528','31.282564');

--
-- 表的结构 `device_message`
-- device_message id zone greenhouse_id sensor_id add_mac sensor_type cod_x cod_y time

CREATE TABLE IF NOT EXISTS `device_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zone` int(11) NOT NULL DEFAULT '1' COMMENT '大棚区号',
  `greenhouse_id` int(11) NOT NULL DEFAULT '1' COMMENT '大棚编号',
  `sensor_id` int(11) NOT NULL DEFAULT '1' COMMENT '设备编号',
  `add_mac` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  -- `crops` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sensor_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '传感器类型',
  `cod_x` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cod_y` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `time` datetime NOT NULL DEFAULT NOW() COMMENT '投用时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `device_message`



-- --------------------------------------------------------

--
-- 表的结构 `history_message`
--

CREATE TABLE IF NOT EXISTS `history_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zone` int(10) NOT NULL COMMENT '大棚区号',
  `greenhouse_id` int(11) NOT NULL COMMENT '大棚编号',
  `sensor_id` int(10) NOT NULL COMMENT '设备编号',
  `add_mac` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'MAC地址',
  -- `crops` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sensor_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '设备类型',
  `cod_x` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cod_y` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `data` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '数据值',
  `time` datetime NOT NULL DEFAULT NOW() COMMENT '时间',
  `alarmed` int(1) NOT NULL DEFAULT 0 COMMENT '解除警报',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='传感器数据表';

--
-- 转存表中的数据 `history_message`
--

INSERT INTO `history_message` (`zone`, `greenhouse_id`, `sensor_id`,`add_mac`, `sensor_type`, `cod_x`, `cod_y`, `data`) VALUES
(1, 1, 1, '', '光照强度', '121.466474', '31.27999', '12'),
(1, 1, 1, '', '光照强度', '121.466474', '31.27999', '30'),
(1, 1, 1, '', '光照强度', '121.466474', '31.27999', '61'),
(1, 1, 1, '', '二氧化碳（%）', '121.466654', '31.281827', '0.01'),
(1, 2, 1, '', '二氧化碳（%）', '121.463357', '31.279682', '0.03'),
(1, 2, 1, '', '二氧化碳（%）', '121.463950', '31.281200', '0.6'),
(1, 1, 1, '', '二氧化碳（%）', '121.466654', '31.279682', '0.5'),
(1, 1, 1, '', '二氧化碳（%）', '121.462899', '31.282205', '0.2'),
(1, 1, 1, '', '二氧化碳（%）', '121.462899', '31.282205', '0.3'),
(1, 1, 1, '', '二氧化碳（%）', '121.462899', '31.282205', '0.15');

-- --------------------------------------------------------

--
-- 表的结构 `new_message`
--
-- 删除new_message表，new_message应该从history_message中获取


-- --------------------------------------------------------

--
-- 表的结构 `video_message`
--

CREATE TABLE IF NOT EXISTS `video_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `cod_x` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `cod_y` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

--
-- 转存表中的数据 `video_message`
--

INSERT INTO `video_message` (`id`, `name`, `type`, `cod_x`, `cod_y`) VALUES
(3, '校园', 'cgi', '100.456734', '38.926852'),
(4, '海滩', 'cgi', '100.452674', '38.926852'),
(16, '珠海航展', 'youku', '109', '34');

-- --------------------------------------------------------

--
-- 表的结构 `user_message`
--

CREATE TABLE IF NOT EXISTS `user_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `country` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '中国' COMMENT '国家',
  `district` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '上海' COMMENT '地区',
  `area` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '实验区',
  `zone` int(11) NOT NULL COMMENT '区号',
  `user` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `role` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用户角色',
  `checked` int(11) NOT NULL DEFAULT '0' COMMENT '是否经过验证',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户信息表';

--
-- 转存表中的数据 `user_message`
--

INSERT INTO `user_message` (`country`, `district`, `area`, `zone`,`user`, `password`, `role`,`checked` ) VALUES
('中国', '上海', '孙桥', '1', 'admin','21232f297a57a5a743894a0e4a801fc3','超级管理员', 1);



-- 创建最新数据表 new message
CREATE view new_message as (SELECT * FROM history_message ORDER BY id DESC LIMIT 100);


-- 创建告警视图 分为历史告警和最新告警视图
CREATE view Alarm_old as (SELECT new.id,new.zone,greenhouse_id,crops,sensor_id,add_mac,new.sensor_type,data,time 
  FROM history_message new, alarm_setting old WHERE new.alarmed=1 AND new.sensor_type=old.sensor_type 
      and (new.data>old.max_value or new.data < old.min_value) and new.zone=old.zone);

CREATE view Alarm_new as (SELECT new.id,new.zone,greenhouse_id,crops,sensor_id,add_mac,new.sensor_type,data,time 
  FROM history_message new, alarm_setting old where new.alarmed=0 and new.sensor_type=old.sensor_type 
      and (new.data>old.max_value or new.data < old.min_value) and new.zone=old.zone);
