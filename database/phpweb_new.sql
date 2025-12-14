-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2025 年 12 月 14 日 18:52
-- 服务器版本: 5.6.21
-- PHP 版本: 5.4.34

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `phpweb`
--

-- --------------------------------------------------------

--
-- 表的结构 `file_mapping`
--

CREATE TABLE IF NOT EXISTS `file_mapping` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件唯一ID（业务引用）',
  `path` varchar(255) NOT NULL COMMENT '文件实际存储路径',
  `size` int(32) NOT NULL COMMENT '文件大小',
  `type` varchar(50) NOT NULL COMMENT '文件MIME类型（如image/jpeg）',
  `name` varchar(100) NOT NULL COMMENT '用户上传的原始文件名（下载时用）',
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '上传时间',
  `user_id` int(255) NOT NULL COMMENT '上传者ID',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1-有效 0-删除（软删标记）',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='文件ID-路径映射表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `title` varchar(255) NOT NULL DEFAULT '新闻标题' COMMENT '新闻标题',
  `title2` varchar(255) DEFAULT '新闻副标题' COMMENT '新闻副标题',
  `image_id` int(64) DEFAULT NULL COMMENT '新闻图片路径',
  `style` varchar(255) DEFAULT NULL COMMENT '样式',
  `content` text NOT NULL COMMENT '新闻内容',
  `user_id` varchar(255) NOT NULL COMMENT '作者ID',
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='新闻存储' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `sys_routes`
--

CREATE TABLE IF NOT EXISTS `sys_routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `url` varchar(255) NOT NULL COMMENT '路由路径(记得带斜杠)',
  `file_path` varchar(255) NOT NULL DEFAULT 'index.php' COMMENT '对应页面路径',
  `method` varchar(20) NOT NULL DEFAULT 'ALL' COMMENT '请求方式',
  `is_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='路由管理' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `sys_routes`
--

INSERT INTO `sys_routes` (`id`, `url`, `file_path`, `method`, `is_enable`) VALUES
(1, '/', 'index.php', 'ALL', 1),
(2, '/index', 'index.php', 'ALL', 1);

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','writer','admin') NOT NULL DEFAULT 'user',
  `email` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT 'default',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户信息存储' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
