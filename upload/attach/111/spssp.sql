-- phpMyAdmin SQL Dump
-- version 2.11.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 25, 2011 at 03:49 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `spssp`
--

-- --------------------------------------------------------

--
-- Table structure for table `spssp_guest_type`
--

CREATE TABLE `spssp_guest_type` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `spssp_guest_type`
--

INSERT INTO `spssp_guest_type` (`id`, `name`) VALUES
(10, '主賓'),
(11, '職場'),
(12, '友人'),
(13, '親戚'),
(15, 'なし');

-- --------------------------------------------------------

--
-- Table structure for table `spssp_respect`
--

CREATE TABLE `spssp_respect` (
  `id` int(5) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `spssp_respect`
--

INSERT INTO `spssp_respect` (`id`, `title`) VALUES
(11, 'くん'),
(12, '様'),
(13, 'ちゃん'),
(15, '殿');

-- --------------------------------------------------------

--
-- Table structure for table `super_admin_message`
--

CREATE TABLE `super_admin_message` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `show_it` int(11) NOT NULL default '1',
  `display_order` int(11) NOT NULL,
  `attach_file` varchar(255) NOT NULL,
  `user_show` int(11) NOT NULL default '0',
  `creation_date` date NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `super_admin_message`
--

INSERT INTO `super_admin_message` (`id`, `title`, `description`, `show_it`, `display_order`, `attach_file`, `user_show`, `creation_date`) VALUES
(2, '2', 'テストテストテストテスト<br />\r\nテストテストテストテストテストテストテスト', 0, 1309257623, '', 0, '0000-00-00'),
(3, 'メイリオのフォント確認', 'メイリオのフォント確認は成功です', 0, 1309872551, '', 0, '0000-00-00'),
(4, 'テスト7/12', 'テスト7/12　テスト7/12　テスト7/12　テスト7/12　テスト7/12　テスト7/12　テスト7/12　テスト7/12　テスト7/12　', 0, 1310455424, '', 0, '0000-00-00'),
(5, 'テスト7/12　2　', 'テスト7/12　2　テスト7/12　2　テスト7/12　2　テスト7/12　2　テスト7/12　2　テスト7/12　2　テスト7/12　2　', 0, 1310455524, '', 0, '0000-00-00'),
(7, 'テスト　7/15　6　', 'テスト　7/15　6　テスト　7/15　6　テスト　7/15　6　テスト　7/15　6　テスト　7/15　6　テスト　7/15　6　', 1, 1310698422, '', 0, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `super_spssp_admin`
--

CREATE TABLE `super_spssp_admin` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `super_spssp_admin`
--

INSERT INTO `super_spssp_admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `super_spssp_hotel`
--

CREATE TABLE `super_spssp_hotel` (
  `id` int(11) NOT NULL auto_increment,
  `hotel_code` varchar(255) NOT NULL,
  `hotel_name` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `address1` text NOT NULL,
  `address2` text NOT NULL,
  `phone` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `adminstrator` varchar(255) NOT NULL,
  `adminid` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `delete_guest` varchar(255) NOT NULL,
  `delete_weeding` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `super_spssp_hotel`
--

INSERT INTO `super_spssp_hotel` (`id`, `hotel_code`, `hotel_name`, `zip`, `address1`, `address2`, `phone`, `contact`, `email`, `adminstrator`, `adminid`, `password`, `date`, `delete_guest`, `delete_weeding`) VALUES
(1, '0001', 'Kumar International', '123456', 'Yokohama', 'Japan', '111111111', 'Kumar', 'kumar@re-inc.jp', 'Kumar', 'AA00000001', 'XHhyMc', '0000-00-00 00:00:00', '6', '12'),
(2, '0010', 'Hotel California', '111000', 'fdafasfasfasdfadfa', 'afadsfasdfasdfa', '0000000000', '鷲頭', 'sekiduka@re-inc.jp', '鷹', 'AA00000010', '79vhtT', '0000-00-00 00:00:00', '20', '100');
