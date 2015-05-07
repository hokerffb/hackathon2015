-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- 主机: w.rdc.sae.sina.com.cn:3307
-- 生成日期: 2015 年 04 月 17 日 17:08
-- 服务器版本: 5.5.23
-- PHP 版本: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `app_qqrecord`
--

-- --------------------------------------------------------

--
-- 表的结构 `result_man`
--
-- 保存分析结果的表，用于展现
-- filename: storage中的文件名
-- name: 分析出来的QQ人名
-- QQ: 分析出来的人的QQ号码
-- total_words: 分析统计出来的这个人说的总单词数
--

CREATE TABLE IF NOT EXISTS `result_man` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `qq` varchar(64) DEFAULT NULL,
  `total_words` int(11) DEFAULT NULL,
  `total_say` int(11) DEFAULT NULL COMMENT '总发言次数',
  PRIMARY KEY (`id`),
  KEY `filename` (`filename`),
  KEY `total_say` (`total_say`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `result_words`
--
-- filename：storage中的文件名
-- word：分析出来的所有的名词
-- count：这个名词出现的次数
--

CREATE TABLE IF NOT EXISTS `result_words` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `word` varchar(32) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `count` (`count`),
  KEY `filename` (`filename`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `result_topwords`
--
-- filename：storage中的文件名
-- word：分析出来的所有的名词
-- count：这个名词出现的次数
--

CREATE TABLE IF NOT EXISTS `result_topwords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `word` varchar(32) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `count` (`count`),
  KEY `name` (`name`),
  KEY `filename` (`filename`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;