-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 03, 2018 at 09:20 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `financial`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `GET_ACC_NAME` (`v_acc_no` VARCHAR(8), `v_comp_code` INT(6) UNSIGNED) RETURNS VARCHAR(200) CHARSET utf8 COLLATE utf8_unicode_ci BEGIN

      DECLARE v_acc_name VARCHAR(200);
      
      SELECT accName INTO v_acc_name FROM accounts WHERE accNo =  RTRIM(LTRIM(v_acc_no)) and compCode = v_comp_code;
  
 RETURN v_acc_name;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `GET_GL_DATE_BAL` (`v_glHead` VARCHAR(9), `v_comp_code` INT(6) UNSIGNED, `transToDate` DATE) RETURNS DECIMAL(10,0) NO SQL
BEGIN
      DECLARE v_gl_opening_bal decimal(12,2);
      
      select (b.startDr + IFNULL(a.dr_amt,0))- (b.startCr + IFNULL(a.cr_amt,0)) into v_gl_opening_bal
      from accounts b,
      (select sum(IF(accCr = v_glHead, transAmt,0)) cr_amt,
              sum(IF(accDr = v_glHead, transAmt,0)) dr_amt
      from transactions
      where compCode = v_comp_code and postFlag = true
      and (accDr = v_glHead or accCr = v_glHead)
      and transDate < transToDate) a
      where b.compCode = v_comp_code and b.accNo = v_glHead;

 RETURN v_gl_opening_bal;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `compCode` int(11) NOT NULL,
  `ldgrCode` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accNo` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `accName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `accType` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `typeCode` int(11) NOT NULL,
  `accrNo` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userCreated` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `isGroup` tinyint(1) DEFAULT NULL,
  `opnDr` decimal(15,2) DEFAULT '0.00',
  `opnCr` decimal(15,2) DEFAULT '0.00',
  `startDr` decimal(15,2) DEFAULT '0.00',
  `startCr` decimal(15,2) DEFAULT '0.00',
  `currBal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `cyrDr` decimal(15,2) DEFAULT '0.00',
  `cyrCr` decimal(15,2) DEFAULT '0.00',
  `dr00` decimal(15,2) DEFAULT '0.00',
  `cr00` decimal(15,2) DEFAULT '0.00',
  `pyrdr` decimal(15,2) DEFAULT '0.00',
  `pyrcr` decimal(15,2) DEFAULT '0.00',
  `dr01` decimal(15,2) DEFAULT '0.00',
  `cr01` decimal(15,2) DEFAULT '0.00',
  `dr02` decimal(15,2) DEFAULT '0.00',
  `cr02` decimal(15,2) DEFAULT '0.00',
  `dr03` decimal(15,2) DEFAULT '0.00',
  `cr03` decimal(15,2) DEFAULT '0.00',
  `dr04` decimal(15,2) DEFAULT '0.00',
  `cr04` decimal(15,2) DEFAULT '0.00',
  `dr05` decimal(15,2) DEFAULT '0.00',
  `cr05` decimal(15,2) DEFAULT '0.00',
  `dr06` decimal(15,2) DEFAULT '0.00',
  `cr06` decimal(15,2) DEFAULT '0.00',
  `dr07` decimal(15,2) DEFAULT '0.00',
  `cr07` decimal(15,2) DEFAULT '0.00',
  `dr08` decimal(15,2) DEFAULT '0.00',
  `cr08` decimal(15,2) DEFAULT '0.00',
  `dr09` decimal(15,2) DEFAULT '0.00',
  `cr09` decimal(15,2) DEFAULT '0.00',
  `dr10` decimal(15,2) DEFAULT '0.00',
  `cr10` decimal(15,2) DEFAULT '0.00',
  `dr11` decimal(15,2) DEFAULT '0.00',
  `cr11` decimal(15,2) DEFAULT '0.00',
  `dr12` decimal(15,2) DEFAULT '0.00',
  `cr12` decimal(15,2) DEFAULT '0.00',
  `cyrBgtp` decimal(15,2) DEFAULT '0.00',
  `cyrBbgtr` decimal(15,2) DEFAULT '0.00',
  `cyrBbgta` decimal(15,2) DEFAULT '0.00',
  `lyrBbgt` decimal(15,2) DEFAULT '0.00',
  `pyrBbgt` decimal(15,2) DEFAULT '0.00',
  `tmpBdr` decimal(15,2) DEFAULT '0.00',
  `tmpBcr` decimal(15,2) DEFAULT '0.00',
  `bdgt01` decimal(15,2) DEFAULT '0.00',
  `bdgt02` decimal(15,2) DEFAULT '0.00',
  `bdgt03` decimal(15,2) DEFAULT '0.00',
  `bdgt04` decimal(15,2) DEFAULT '0.00',
  `bdgt05` decimal(15,2) DEFAULT '0.00',
  `bdgt06` decimal(15,2) DEFAULT '0.00',
  `bdgt07` decimal(15,2) DEFAULT '0.00',
  `bdgt08` decimal(15,2) DEFAULT '0.00',
  `bdgt09` decimal(15,2) DEFAULT '0.00',
  `bdgt10` decimal(15,2) DEFAULT '0.00',
  `bdgt11` decimal(15,2) DEFAULT '0.00',
  `bdgt12` decimal(15,2) DEFAULT '0.00',
  `fcBdgt` decimal(15,2) DEFAULT '0.00',
  `fcBalDr` decimal(15,2) DEFAULT '0.00',
  `fcBalCr` decimal(15,2) DEFAULT '0.00',
  `opnPost` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `compCode`, `ldgrCode`, `accNo`, `accName`, `accType`, `typeCode`, `accrNo`, `userCreated`, `isGroup`, `opnDr`, `opnCr`, `startDr`, `startCr`, `currBal`, `cyrDr`, `cyrCr`, `dr00`, `cr00`, `pyrdr`, `pyrcr`, `dr01`, `cr01`, `dr02`, `cr02`, `dr03`, `cr03`, `dr04`, `cr04`, `dr05`, `cr05`, `dr06`, `cr06`, `dr07`, `cr07`, `dr08`, `cr08`, `dr09`, `cr09`, `dr10`, `cr10`, `dr11`, `cr11`, `dr12`, `cr12`, `cyrBgtp`, `cyrBbgtr`, `cyrBbgta`, `lyrBbgt`, `pyrBbgt`, `tmpBdr`, `tmpBcr`, `bdgt01`, `bdgt02`, `bdgt03`, `bdgt04`, `bdgt05`, `bdgt06`, `bdgt07`, `bdgt08`, `bdgt09`, `bdgt10`, `bdgt11`, `bdgt12`, `fcBdgt`, `fcBalDr`, `fcBalCr`, `opnPost`, `created_at`, `updated_at`) VALUES
(1, 11001, '101', '10112100', '*CASH IN HAND', 'A', 12, '10112999', 'ABC Company', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2018-02-04 15:59:25', '2018-02-04 15:59:25'),
(2, 11001, '102', '10212100', '*CASH AT BANK', 'A', 12, '10212999', 'ABC Company', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2018-02-04 15:59:25', '2018-02-04 15:59:25'),
(3, 11001, '301', '30112100', '*SALES ACCOUNT', 'I', 31, '30112999', 'ABC Company', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2018-02-04 15:59:25', '2018-02-04 15:59:25'),
(4, 11001, '401', '40112100', '*PURCHASE ACCOUNT', 'E', 41, '40112999', 'ABC Company', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2018-02-04 15:59:25', '2018-02-04 15:59:25'),
(5, 11001, '101', '10112102', 'Bis', 'A', 12, '10112102', 'Admin User', 0, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2018-11-04 00:14:27', '2018-11-04 00:14:27'),
(6, 11001, '101', '10112104', 'Lol', 'A', 12, '10112104', 'Admin User', 0, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '2018-11-04 00:14:45', '2018-11-04 00:14:45');

-- --------------------------------------------------------

--
-- Table structure for table `acc_types`
--

CREATE TABLE `acc_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `parrentCode` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `typeCode` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `apps_countries`
--

CREATE TABLE `apps_countries` (
  `id` int(10) UNSIGNED NOT NULL,
  `countryCodeA` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `countryCodeN` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `countryName` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `nickName` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currencyCodeN` char(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currencyCodeA` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currencySymble` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phoneCode` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `apps_countries`
--

INSERT INTO `apps_countries` (`id`, `countryCodeA`, `countryCodeN`, `countryName`, `nickName`, `currencyCodeN`, `currencyCodeA`, `currency`, `currencySymble`, `phoneCode`, `created_at`, `updated_at`) VALUES
(1, 'AD', '020', 'Andorra', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(2, 'AE', '784', 'United Arab Emirates', NULL, NULL, 'AED', 'United Arab Emirates Dirham', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(3, 'AF', '004', 'Afghanistan', NULL, NULL, 'AFN', 'Afghanistan Afghani', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(4, 'AG', '028', 'Antigua and Barbuda', NULL, NULL, 'XCD', 'East Caribbean Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(5, 'AI', '660', 'Anguilla', NULL, NULL, 'XCD', 'East Caribbean Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(6, 'AL', '008', 'Albania', NULL, NULL, 'ALL', 'Albanian Lek', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(7, 'AM', '051', 'Armenia', NULL, NULL, 'AMD', 'Armenian Dram', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(8, 'AO', '024', 'Angola', NULL, NULL, 'AOA', 'Angolan Kwanza', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(9, 'AQ', '010', 'Antarctica', NULL, NULL, '', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(10, 'AR', '032', 'Argentina', NULL, NULL, 'ARS', 'Argentine Peso', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(11, 'AS', '016', 'American Samoa', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(12, 'AT', '040', 'Austria', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(13, 'AU', '036', 'Australia', NULL, NULL, 'AUD', 'Australian Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(14, 'AW', '533', 'Aruba', NULL, NULL, 'AWG', 'Aruban Florin', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(15, 'AX', '248', 'Åland', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(16, 'AZ', '031', 'Azerbaijan', NULL, NULL, 'AZN', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(17, 'BA', '070', 'Bosnia and Herzegovina', NULL, NULL, 'BAM', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(18, 'BB', '052', 'Barbados', NULL, NULL, 'BBD', 'Barbados Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(19, 'BD', '050', 'Bangladesh', NULL, NULL, 'BDT', 'Bangladeshi Taka', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(20, 'BE', '056', 'Belgium', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(21, 'BF', '854', 'Burkina Faso', NULL, NULL, 'XOF', 'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(22, 'BG', '100', 'Bulgaria', NULL, NULL, 'BGN', 'Bulgarian Lev', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(23, 'BH', '048', 'Bahrain', NULL, NULL, 'BHD', 'Bahraini Dinar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(24, 'BI', '108', 'Burundi', NULL, NULL, 'BIF', 'Burundi Franc', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(25, 'BJ', '204', 'Benin', NULL, NULL, 'XOF', 'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(26, 'BL', '652', 'Saint Barthélemy', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(27, 'BM', '060', 'Bermuda', NULL, NULL, 'BMD', 'Bermudian Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(28, 'BN', '096', 'Brunei', NULL, NULL, 'BND', 'Brunei Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(29, 'BO', '068', 'Bolivia', NULL, NULL, 'BOB', 'Bolivian Boliviano', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(30, 'BQ', '535', 'Bonaire', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(31, 'BR', '076', 'Brazil', NULL, NULL, 'BRL', 'Brazilian Real', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(32, 'BS', '044', 'Bahamas', NULL, NULL, 'BSD', 'Bahamian Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(33, 'BT', '064', 'Bhutan', NULL, NULL, 'BTN', 'Bhutan Ngultrum', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(34, 'BV', '074', 'Bouvet Island', NULL, NULL, 'NOK', 'Norwegian Kroner', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(35, 'BW', '072', 'Botswana', NULL, NULL, 'BWP', 'Botswanian Pula', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(36, 'BY', '112', 'Belarus', NULL, NULL, 'BYR', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(37, 'BZ', '084', 'Belize', NULL, NULL, 'BZD', 'Belize Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(38, 'CA', '124', 'Canada', NULL, NULL, 'CAD', 'Canadian Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(39, 'CC', '166', 'Cocos [Keeling] Islands', NULL, NULL, 'AUD', 'Australian Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(40, 'CD', '180', 'Democratic Republic of the Congo', NULL, NULL, 'CDF', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(41, 'CF', '140', 'Central African Republic', NULL, NULL, 'XAF', 'CommunautÃ© FinanciÃ¨re Africaine BEAC, Francs', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(42, 'CG', '178', 'Republic of the Congo', NULL, NULL, 'XAF', 'CommunautÃ© FinanciÃ¨re Africaine BEAC, Francs', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(43, 'CH', '756', 'Switzerland', NULL, NULL, 'CHF', 'Swiss Franc', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(44, 'CI', '384', 'Ivory Coast', NULL, NULL, 'XOF', 'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(45, 'CK', '184', 'Cook Islands', NULL, NULL, 'NZD', 'New Zealand Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(46, 'CL', '152', 'Chile', NULL, NULL, 'CLP', 'Chilean Peso', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(47, 'CM', '120', 'Cameroon', NULL, NULL, 'XAF', 'CommunautÃ© FinanciÃ¨re Africaine BEAC, Francs', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(48, 'CN', '156', 'China', NULL, NULL, 'CNY', 'Yuan (Chinese) Renminbi', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(49, 'CO', '170', 'Colombia', NULL, NULL, 'COP', 'Colombian Peso', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(50, 'CR', '188', 'Costa Rica', NULL, NULL, 'CRC', 'Costa Rican Colon', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(51, 'CU', '192', 'Cuba', NULL, NULL, 'CUP', 'Cuban Peso', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(52, 'CV', '132', 'Cape Verde', NULL, NULL, 'CVE', 'Cape Verde Escudo', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(53, 'CW', '531', 'Curacao', NULL, NULL, 'ANG', 'Netherlands Antillian Guilder', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(54, 'CX', '162', 'Christmas Island', NULL, NULL, 'AUD', 'Australian Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(55, 'CY', '196', 'Cyprus', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(56, 'CZ', '203', 'Czechia', NULL, NULL, 'CZK', 'Czech Republic Koruna', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(57, 'DE', '276', 'Germany', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(58, 'DJ', '262', 'Djibouti', NULL, NULL, 'DJF', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(59, 'DK', '208', 'Denmark', NULL, NULL, 'DKK', 'Danish Krone', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(60, 'DM', '212', 'Dominica', NULL, NULL, 'XCD', 'East Caribbean Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(61, 'DO', '214', 'Dominican Republic', NULL, NULL, 'DOP', 'Dominican Peso', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(62, 'DZ', '012', 'Algeria', NULL, NULL, 'DZD', 'Algerian Dinar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(63, 'EC', '218', 'Ecuador', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(64, 'EE', '233', 'Estonia', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(65, 'EG', '818', 'Egypt', NULL, NULL, 'EGP', 'Egyptian Pound', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(66, 'EH', '732', 'Western Sahara', NULL, NULL, 'MAD', 'Moroccan Dirham', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(67, 'ER', '232', 'Eritrea', NULL, NULL, 'ERN', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(68, 'ES', '724', 'Spain', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(69, 'ET', '231', 'Ethiopia', NULL, NULL, 'ETB', 'Ethiopian Birr', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(70, 'FI', '246', 'Finland', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(71, 'FJ', '242', 'Fiji', NULL, NULL, 'FJD', 'Fiji Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(72, 'FK', '238', 'Falkland Islands', NULL, NULL, 'FKP', 'Falkland Islands Pound', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(73, 'FM', '583', 'Micronesia', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(74, 'FO', '234', 'Faroe Islands', NULL, NULL, 'DKK', 'Danish Krone', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(75, 'FR', '250', 'France', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(76, 'GA', '266', 'Gabon', NULL, NULL, 'XAF', 'CommunautÃ© FinanciÃ¨re Africaine BEAC, Francs', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(77, 'GB', '826', 'United Kingdom', NULL, NULL, 'GBP', 'British Pound', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(78, 'GD', '308', 'Grenada', NULL, NULL, 'XCD', 'East Caribbean Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(79, 'GE', '268', 'Georgia', NULL, NULL, 'GEL', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(80, 'GF', '254', 'French Guiana', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(81, 'GG', '831', 'Guernsey', NULL, NULL, 'GBP', 'British Pound', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(82, 'GH', '288', 'Ghana', NULL, NULL, 'GHS', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(83, 'GI', '292', 'Gibraltar', NULL, NULL, 'GIP', 'Gibraltar Pound', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(84, 'GL', '304', 'Greenland', NULL, NULL, 'DKK', 'Danish Krone', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(85, 'GM', '270', 'Gambia', NULL, NULL, 'GMD', 'Gambian Dalasi', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(86, 'GN', '324', 'Guinea', NULL, NULL, 'GNF', 'Guinea Franc', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(87, 'GP', '312', 'Guadeloupe', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(88, 'GQ', '226', 'Equatorial Guinea', NULL, NULL, 'XAF', 'CommunautÃ© FinanciÃ¨re Africaine BEAC, Francs', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(89, 'GR', '300', 'Greece', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(90, 'GS', '239', 'South Georgia and the South Sandwich Islands', NULL, NULL, 'GBP', 'British Pound', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(91, 'GT', '320', 'Guatemala', NULL, NULL, 'GTQ', 'Guatemalan Quetzal', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(92, 'GU', '316', 'Guam', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(93, 'GW', '624', 'Guinea-Bissau', NULL, NULL, 'XOF', 'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(94, 'GY', '328', 'Guyana', NULL, NULL, 'GYD', 'Guyanan Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(95, 'HK', '344', 'Hong Kong', NULL, NULL, 'HKD', 'Hong Kong Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(96, 'HM', '334', 'Heard Island and McDonald Islands', NULL, NULL, 'AUD', 'Australian Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(97, 'HN', '340', 'Honduras', NULL, NULL, 'HNL', 'Honduran Lempira', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(98, 'HR', '191', 'Croatia', NULL, NULL, 'HRK', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(99, 'HT', '332', 'Haiti', NULL, NULL, 'HTG', 'Haitian Gourde', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(100, 'HU', '348', 'Hungary', NULL, NULL, 'HUF', 'Hungarian Forint', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(101, 'ID', '360', 'Indonesia', NULL, NULL, 'IDR', 'Indonesian Rupiah', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(102, 'IE', '372', 'Ireland', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(103, 'IL', '376', 'Israel', NULL, NULL, 'ILS', 'Israeli Shekel', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(104, 'IM', '833', 'Isle of Man', NULL, NULL, 'GBP', 'British Pound', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(105, 'IN', '356', 'India', NULL, NULL, 'INR', 'Indian Rupee', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(106, 'IO', '086', 'British Indian Ocean Territory', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(107, 'IQ', '368', 'Iraq', NULL, NULL, 'IQD', 'Iraqi Dinar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(108, 'IR', '364', 'Iran', NULL, NULL, 'IRR', 'Iranian Rial', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(109, 'IS', '352', 'Iceland', NULL, NULL, 'ISK', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(110, 'IT', '380', 'Italy', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(111, 'JE', '832', 'Jersey', NULL, NULL, 'GBP', 'British Pound', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(112, 'JM', '388', 'Jamaica', NULL, NULL, 'JMD', 'Jamaican Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(113, 'JO', '400', 'Jordan', NULL, NULL, 'JOD', 'Jordanian Dinar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(114, 'JP', '392', 'Japan', NULL, NULL, 'JPY', 'Japanese Yen', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(115, 'KE', '404', 'Kenya', NULL, NULL, 'KES', 'Kenyan Schilling', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(116, 'KG', '417', 'Kyrgyzstan', NULL, NULL, 'KGS', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(117, 'KH', '116', 'Cambodia', NULL, NULL, 'KHR', 'Kampuchean (Cambodian) Riel', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(118, 'KI', '296', 'Kiribati', NULL, NULL, 'AUD', 'Australian Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(119, 'KM', '174', 'Comoros', NULL, NULL, 'KMF', 'Comoros Franc', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(120, 'KN', '659', 'Saint Kitts and Nevis', NULL, NULL, 'XCD', 'East Caribbean Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(121, 'KP', '408', 'North Korea', NULL, NULL, 'KPW', 'North Korean Won', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(122, 'KR', '410', 'South Korea', NULL, NULL, 'KRW', '(South) Korean Won', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(123, 'KW', '414', 'Kuwait', NULL, NULL, 'KWD', 'Kuwaiti Dinar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(124, 'KY', '136', 'Cayman Islands', NULL, NULL, 'KYD', 'Cayman Islands Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(125, 'KZ', '398', 'Kazakhstan', NULL, NULL, 'KZT', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(126, 'LA', '418', 'Laos', NULL, NULL, 'LAK', 'Lao Kip', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(127, 'LB', '422', 'Lebanon', NULL, NULL, 'LBP', 'Lebanese Pound', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(128, 'LC', '662', 'Saint Lucia', NULL, NULL, 'XCD', 'East Caribbean Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(129, 'LI', '438', 'Liechtenstein', NULL, NULL, 'CHF', 'Swiss Franc', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(130, 'LK', '144', 'Sri Lanka', NULL, NULL, 'LKR', 'Sri Lanka Rupee', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(131, 'LR', '430', 'Liberia', NULL, NULL, 'LRD', 'Liberian Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(132, 'LS', '426', 'Lesotho', NULL, NULL, 'LSL', 'Lesotho Loti', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(133, 'LT', '440', 'Lithuania', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(134, 'LU', '442', 'Luxembourg', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(135, 'LV', '428', 'Latvia', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(136, 'LY', '434', 'Libya', NULL, NULL, 'LYD', 'Libyan Dinar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(137, 'MA', '504', 'Morocco', NULL, NULL, 'MAD', 'Moroccan Dirham', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(138, 'MC', '492', 'Monaco', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(139, 'MD', '498', 'Moldova', NULL, NULL, 'MDL', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(140, 'ME', '499', 'Montenegro', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(141, 'MF', '663', 'Saint Martin', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(142, 'MG', '450', 'Madagascar', NULL, NULL, 'MGA', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(143, 'MH', '584', 'Marshall Islands', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(144, 'MK', '807', 'Macedonia', NULL, NULL, 'MKD', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(145, 'ML', '466', 'Mali', NULL, NULL, 'XOF', 'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(146, 'MM', '104', 'Myanmar [Burma]', NULL, NULL, 'MMK', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(147, 'MN', '496', 'Mongolia', NULL, NULL, 'MNT', 'Mongolian Tugrik', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(148, 'MO', '446', 'Macao', NULL, NULL, 'MOP', 'Macau Pataca', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(149, 'MP', '580', 'Northern Mariana Islands', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(150, 'MQ', '474', 'Martinique', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(151, 'MR', '478', 'Mauritania', NULL, NULL, 'MRO', 'Mauritanian Ouguiya', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(152, 'MS', '500', 'Montserrat', NULL, NULL, 'XCD', 'East Caribbean Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(153, 'MT', '470', 'Malta', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(154, 'MU', '480', 'Mauritius', NULL, NULL, 'MUR', 'Mauritius Rupee', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(155, 'MV', '462', 'Maldives', NULL, NULL, 'MVR', 'Maldive Rufiyaa', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(156, 'MW', '454', 'Malawi', NULL, NULL, 'MWK', 'Malawi Kwacha', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(157, 'MX', '484', 'Mexico', NULL, NULL, 'MXN', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(158, 'MY', '458', 'Malaysia', NULL, NULL, 'MYR', 'Malaysian Ringgit', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(159, 'MZ', '508', 'Mozambique', NULL, NULL, 'MZN', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(160, 'NA', '516', 'Namibia', NULL, NULL, 'NAD', 'Namibian Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(161, 'NC', '540', 'New Caledonia', NULL, NULL, 'XPF', 'Comptoirs FranÃ§ais du Pacifique Francs', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(162, 'NE', '562', 'Niger', NULL, NULL, 'XOF', 'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(163, 'NF', '574', 'Norfolk Island', NULL, NULL, 'AUD', 'Australian Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(164, 'NG', '566', 'Nigeria', NULL, NULL, 'NGN', 'Nigerian Naira', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(165, 'NI', '558', 'Nicaragua', NULL, NULL, 'NIO', 'Nicaraguan Cordoba', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(166, 'NL', '528', 'Netherlands', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(167, 'NO', '578', 'Norway', NULL, NULL, 'NOK', 'Norwegian Kroner', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(168, 'NP', '524', 'Nepal', NULL, NULL, 'NPR', 'Nepalese Rupee', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(169, 'NR', '520', 'Nauru', NULL, NULL, 'AUD', 'Australian Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(170, 'NU', '570', 'Niue', NULL, NULL, 'NZD', 'New Zealand Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(171, 'NZ', '554', 'New Zealand', NULL, NULL, 'NZD', 'New Zealand Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(172, 'OM', '512', 'Oman', NULL, NULL, 'OMR', 'Omani Rial', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(173, 'PA', '591', 'Panama', NULL, NULL, 'PAB', 'Panamanian Balboa', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(174, 'PE', '604', 'Peru', NULL, NULL, 'PEN', 'Peruvian Nuevo Sol', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(175, 'PF', '258', 'French Polynesia', NULL, NULL, 'XPF', 'Comptoirs FranÃ§ais du Pacifique Francs', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(176, 'PG', '598', 'Papua New Guinea', NULL, NULL, 'PGK', 'Papua New Guinea Kina', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(177, 'PH', '608', 'Philippines', NULL, NULL, 'PHP', 'Philippine Peso', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(178, 'PK', '586', 'Pakistan', NULL, NULL, 'PKR', 'Pakistan Rupee', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(179, 'PL', '616', 'Poland', NULL, NULL, 'PLN', 'Polish Zloty', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(180, 'PM', '666', 'Saint Pierre and Miquelon', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(181, 'PN', '612', 'Pitcairn Islands', NULL, NULL, 'NZD', 'New Zealand Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(182, 'PR', '630', 'Puerto Rico', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(183, 'PS', '275', 'Palestine', NULL, NULL, 'ILS', 'Israeli Shekel', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(184, 'PT', '620', 'Portugal', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(185, 'PW', '585', 'Palau', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(186, 'PY', '600', 'Paraguay', NULL, NULL, 'PYG', 'Paraguay Guarani', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(187, 'QA', '634', 'Qatar', NULL, NULL, 'QAR', 'Qatari Rial', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(188, 'RE', '638', 'Réunion', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(189, 'RO', '642', 'Romania', NULL, NULL, 'RON', 'Romanian Leu', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(190, 'RS', '688', 'Serbia', NULL, NULL, 'RSD', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(191, 'RU', '643', 'Russia', NULL, NULL, 'RUB', 'Russian Ruble', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(192, 'RW', '646', 'Rwanda', NULL, NULL, 'RWF', 'Rwanda Franc', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(193, 'SA', '682', 'Saudi Arabia', NULL, NULL, 'SAR', 'Saudi Arabian Riyal', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(194, 'SB', '090', 'Solomon Islands', NULL, NULL, 'SBD', 'Solomon Islands Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(195, 'SC', '690', 'Seychelles', NULL, NULL, 'SCR', 'Seychelles Rupee', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(196, 'SD', '729', 'Sudan', NULL, NULL, 'SDG', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(197, 'SE', '752', 'Sweden', NULL, NULL, 'SEK', 'Swedish Krona', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(198, 'SG', '702', 'Singapore', NULL, NULL, 'SGD', 'Singapore Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(199, 'SH', '654', 'Saint Helena', NULL, NULL, 'SHP', 'St. Helena Pound', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(200, 'SI', '705', 'Slovenia', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(201, 'SJ', '744', 'Svalbard and Jan Mayen', NULL, NULL, 'NOK', 'Norwegian Kroner', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(202, 'SK', '703', 'Slovakia', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(203, 'SL', '694', 'Sierra Leone', NULL, NULL, 'SLL', 'Sierra Leone Leone', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(204, 'SM', '674', 'San Marino', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(205, 'SN', '686', 'Senegal', NULL, NULL, 'XOF', 'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(206, 'SO', '706', 'Somalia', NULL, NULL, 'SOS', 'Somali Schilling', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(207, 'SR', '740', 'Suriname', NULL, NULL, 'SRD', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(208, 'SS', '728', 'South Sudan', NULL, NULL, 'SSP', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(209, 'ST', '678', 'São Tomé and Príncipe', NULL, NULL, 'STD', 'Sao Tome and Principe Dobra', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(210, 'SV', '222', 'El Salvador', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(211, 'SX', '534', 'Sint Maarten', NULL, NULL, 'ANG', 'Netherlands Antillian Guilder', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(212, 'SY', '760', 'Syria', NULL, NULL, 'SYP', 'Syrian Potmd', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(213, 'SZ', '748', 'Swaziland', NULL, NULL, 'SZL', 'Swaziland Lilangeni', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(214, 'TC', '796', 'Turks and Caicos Islands', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(215, 'TD', '148', 'Chad', NULL, NULL, 'XAF', 'CommunautÃ© FinanciÃ¨re Africaine BEAC, Francs', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(216, 'TF', '260', 'French Southern Territories', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(217, 'TG', '768', 'Togo', NULL, NULL, 'XOF', 'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(218, 'TH', '764', 'Thailand', NULL, NULL, 'THB', 'Thai Baht', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(219, 'TJ', '762', 'Tajikistan', NULL, NULL, 'TJS', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(220, 'TK', '772', 'Tokelau', NULL, NULL, 'NZD', 'New Zealand Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(221, 'TL', '626', 'East Timor', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(222, 'TM', '795', 'Turkmenistan', NULL, NULL, 'TMT', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(223, 'TN', '788', 'Tunisia', NULL, NULL, 'TND', 'Tunisian Dinar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(224, 'TO', '776', 'Tonga', NULL, NULL, 'TOP', 'Tongan Paanga', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(225, 'TR', '792', 'Turkey', NULL, NULL, 'TRY', 'Turkish Lira', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(226, 'TT', '780', 'Trinidad and Tobago', NULL, NULL, 'TTD', 'Trinidad and Tobago Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(227, 'TV', '798', 'Tuvalu', NULL, NULL, 'AUD', 'Australian Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(228, 'TW', '158', 'Taiwan', NULL, NULL, 'TWD', 'Taiwan Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(229, 'TZ', '834', 'Tanzania', NULL, NULL, 'TZS', 'Tanzanian Schilling', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(230, 'UA', '804', 'Ukraine', NULL, NULL, 'UAH', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(231, 'UG', '800', 'Uganda', NULL, NULL, 'UGX', 'Uganda Shilling', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(232, 'UM', '581', 'U.S. Minor Outlying Islands', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(233, 'US', '840', 'United States', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(234, 'UY', '858', 'Uruguay', NULL, NULL, 'UYU', 'Uruguayan Peso', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(235, 'UZ', '860', 'Uzbekistan', NULL, NULL, 'UZS', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(236, 'VA', '336', 'Vatican City', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(237, 'VC', '670', 'Saint Vincent and the Grenadines', NULL, NULL, 'XCD', 'East Caribbean Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(238, 'VE', '862', 'Venezuela', NULL, NULL, 'VEF', 'Venezualan Bolivar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(239, 'VG', '092', 'British Virgin Islands', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(240, 'VI', '850', 'U.S. Virgin Islands', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(241, 'VN', '704', 'Vietnam', NULL, NULL, 'VND', 'Vietnamese Dong', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(242, 'VU', '548', 'Vanuatu', NULL, NULL, 'VUV', 'Vanuatu Vatu', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(243, 'WF', '876', 'Wallis and Futuna', NULL, NULL, 'XPF', 'Comptoirs FranÃ§ais du Pacifique Francs', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(244, 'WS', '882', 'Samoa', NULL, NULL, 'WST', 'Samoan Tala', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(245, 'XK', '0', 'Kosovo', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(246, 'YE', '887', 'Yemen', NULL, NULL, 'YER', 'Yemeni Rial', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(247, 'YT', '175', 'Mayotte', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(248, 'ZA', '710', 'South Africa', NULL, NULL, 'ZAR', 'South African Rand', NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(249, 'ZM', '894', 'Zambia', NULL, NULL, 'ZMW', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42'),
(250, 'ZW', '716', 'Zimbabwe', NULL, NULL, 'ZWL', NULL, NULL, NULL, '2018-01-30 00:38:42', '2018-01-30 00:38:42');

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `bank_type` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `bank_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `branch_name` varchar(190) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(190) COLLATE utf8_unicode_ci NOT NULL,
  `swift_code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acc_name` varchar(190) COLLATE utf8_unicode_ci NOT NULL,
  `acc_no` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `basic_properties`
--

CREATE TABLE `basic_properties` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `project` tinyint(4) NOT NULL DEFAULT '0',
  `inventory` tinyint(4) NOT NULL DEFAULT '0',
  `cash` int(11) NOT NULL DEFAULT '101',
  `bank` int(11) NOT NULL DEFAULT '102',
  `sales` int(11) NOT NULL DEFAULT '301',
  `purchase` int(11) NOT NULL DEFAULT '401',
  `currency` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `posted` tinyint(1) NOT NULL DEFAULT '0',
  `fpStart` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `basic_properties`
--

INSERT INTO `basic_properties` (`id`, `compCode`, `project`, `inventory`, `cash`, `bank`, `sales`, `purchase`, `currency`, `posted`, `fpStart`, `created_at`, `updated_at`) VALUES
(1, 11001, 0, 0, 101, 102, 301, 401, 'BDT', 1, '2018-01-01', '2018-02-04 15:58:25', '2018-02-04 15:59:25');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `manufacturer` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `imagePath` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accNo` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inventoryAmt` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Current balance * avg unit price',
  `accBalance` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'General Ledger Balance',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Triggers `categories`
--
DELIMITER $$
CREATE TRIGGER `copy_alias` BEFORE INSERT ON `categories` FOR EACH ROW IF NEW.alias IS NULL OR LENGTH(NEW.alias) < 1 THEN
                SET NEW.alias := NEW.name;
                END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `compName` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `postCode` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `phoneNo` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `currency` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `compCode`, `compName`, `city`, `state`, `postCode`, `country`, `phoneNo`, `email`, `currency`, `created_at`, `updated_at`) VALUES
(1, 11001, 'Adenta Municipal Assembly', 'Adenta', 'Greater Accra', '0233', 'Ghana', '0240229678', 'admin@gmail.com', 'GHC', NULL, NULL);

--
-- Triggers `companies`
--
DELIMITER $$
CREATE TRIGGER `new_comp_properties` AFTER INSERT ON `companies` FOR EACH ROW BEGIN
                   
                    INSERT INTO trans_codes(compCode,transCode, transName, lastTransNo) VALUES(NEW.compCode, 'CP','Cash Payment','100000');

                    INSERT INTO trans_codes(compCode,transCode, transName, lastTransNo) VALUES(NEW.compCode, 'CR','Cash Receipt','200000');
                    
                    INSERT INTO trans_codes(compCode,transCode, transName, lastTransNo) VALUES(NEW.compCode, 'BP','Bank Payment','300000');
                    
                    INSERT INTO trans_codes(compCode,transCode, transName, lastTransNo) VALUES(NEW.compCode, 'BR','Bank Receipt','400000');
                    
                    INSERT INTO trans_codes(compCode,transCode, transName, lastTransNo) VALUES(NEW.compCode, 'JV','Journal','500000');
                    
                    INSERT INTO trans_codes(compCode,transCode, transName, lastTransNo) VALUES(NEW.compCode, 'SI','Sales Invoice','600000');
                    
                    INSERT INTO trans_codes(compCode,transCode, transName, lastTransNo) VALUES(NEW.compCode, 'PR','Purchase','700000');
                    
                    INSERT INTO trans_codes(compCode,transCode, transName, lastTransNo) VALUES(NEW.compCode, 'DC','Delivery Challan','800000');
                    
                    INSERT INTO trans_codes(compCode,transCode, transName, lastTransNo) VALUES(NEW.compCode, 'RQ','Requisition','900000');
                                                                                
                    INSERT INTO users(compCode,name,email,password,role) VALUES(NEW.compCode,NEW.compName,NEW.email, ENCRYPT('pass123'),'Admin');
                    
                    
                    INSERT INTO `use_cases` (`id`, `menuId`, `useCaseId`, `useCaseName`, `created_at`, `updated_at`) VALUES
                            (1, 'A', 'A01', 'User Profile', '2017-01-07 11:46:13', '2017-01-07 11:46:13'),
                            (2, 'A', 'A02', 'User Privilege', '2017-01-07 11:46:13', '2017-01-07 11:46:13'),
                            (3, 'A', 'A03', 'Change Password', '2017-01-07 11:46:13', '2017-01-07 11:46:13'),
                            (4, 'A', 'A04', 'Reset Password', '2017-01-07 11:46:13', '2017-01-07 11:46:13'),
                            (5, 'B', 'B01', 'Company Profile', '2017-01-07 11:46:13', '2017-01-07 11:46:13'),
                            (6, 'B', 'B02', 'Project Profile', '2017-01-07 11:46:13', '2017-01-07 11:46:13'),
                            (7, 'B', 'B03', 'Fiscal Period', '2017-01-07 11:46:13', '2017-01-07 11:46:13'),
                            (8, 'B', 'B04', 'Account Group', '2017-01-07 11:46:13', '2017-01-07 11:46:13'),
                            (9, 'B', 'B05', 'Account Head', '2017-01-07 11:46:13', '2017-01-07 11:46:13'),
                            (10, 'B', 'B06', 'Opening Balance', '2017-01-07 11:46:13', '2017-01-07 11:46:13'),
                            (11, 'B', 'B07', 'Fixed Asset Depreciation', '2017-01-07 11:46:13', '2017-01-07 11:46:13'),
                            (12, 'B', 'B08', 'Accounts Budget', '2017-01-07 11:46:13', '2017-01-07 11:46:13'),
                            (13, 'C', 'C01', 'Cash Payment', '2017-01-07 11:46:13', '2017-01-07 11:46:13'),
                            (14, 'C', 'C02', 'Bank Payment', '2017-01-07 11:46:13', '2017-01-07 11:46:13'),
                            (15, 'C', 'C03', 'Cash Receive', '2017-01-07 11:46:13', '2017-01-07 11:46:13'),
                            (16, 'C', 'C04', 'Bank Receive', '2017-01-07 11:46:13', '2017-01-07 11:46:13'),
                            (17, 'C', 'C05', 'Journal', '2017-01-07 11:46:13', '2017-01-07 11:46:13'),
                            (18, 'C', 'C20', 'Edit Voucher', '2017-01-07 11:46:13', '2017-01-07 11:46:13'),
                            (19, 'C', 'C21', 'Post Voucher', '2017-01-07 11:46:13', '2017-01-07 11:46:13'),
                            (20, 'R', 'R01', 'Daily Transaction List', '2017-08-23 12:00:00', '2017-08-23 12:00:00'),
                            (21, 'R', 'R02', 'View Print Voucher', '2018-01-30 06:20:48', '2018-01-30 06:20:48'),
                            (22, 'R', 'R03', 'Trial Balance', '2018-01-30 06:20:48', '2018-01-30 06:20:48'),
                            (23, 'R', 'R04', 'Trial Balance Group Head', '2018-01-30 06:20:48', '2018-01-30 06:20:48'),
                            (24, 'R', 'R05', 'View General Ledger', '2018-01-30 06:20:48', '2018-01-30 06:20:48'),
                            (25, 'S', 'S01', 'Add Financial Statement', '2018-01-30 06:20:48', '2018-01-30 06:20:48'),
                            (26, 'S', 'S02', 'Create Financial Statement', '2018-01-30 06:20:48', '2018-01-30 06:20:48'),
                            (27, 'S', 'S03', 'Print Financial Statement', '2018-01-30 06:20:48', '2018-01-30 06:20:48'),
                            (28, 'P', 'P01', 'Product Category', '2017-08-23 12:00:00', '2017-08-23 12:00:00'),
                            (29, 'P', 'P02', 'Product Brands', '2017-08-24 09:02:01', '2017-08-24 09:02:01');
                            
                            
                        INSERT INTO `apps_countries` (`id`, `countryCodeA`, `countryCodeN`, `countryName`, `nickName`, `currencyCodeN`, `currencyCodeA`, `currency`, `currencySymble`, `phoneCode`, `created_at`, `updated_at`) VALUES
                            (1, 'AD', '020', 'Andorra', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (2, 'AE', '784', 'United Arab Emirates', NULL, NULL, 'AED', 'United Arab Emirates Dirham', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (3, 'AF', '004', 'Afghanistan', NULL, NULL, 'AFN', 'Afghanistan Afghani', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (4, 'AG', '028', 'Antigua and Barbuda', NULL, NULL, 'XCD', 'East Caribbean Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (5, 'AI', '660', 'Anguilla', NULL, NULL, 'XCD', 'East Caribbean Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (6, 'AL', '008', 'Albania', NULL, NULL, 'ALL', 'Albanian Lek', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (7, 'AM', '051', 'Armenia', NULL, NULL, 'AMD', 'Armenian Dram', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (8, 'AO', '024', 'Angola', NULL, NULL, 'AOA', 'Angolan Kwanza', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (9, 'AQ', '010', 'Antarctica', NULL, NULL, '', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (10, 'AR', '032', 'Argentina', NULL, NULL, 'ARS', 'Argentine Peso', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (11, 'AS', '016', 'American Samoa', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (12, 'AT', '040', 'Austria', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (13, 'AU', '036', 'Australia', NULL, NULL, 'AUD', 'Australian Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (14, 'AW', '533', 'Aruba', NULL, NULL, 'AWG', 'Aruban Florin', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (15, 'AX', '248', 'Åland', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (16, 'AZ', '031', 'Azerbaijan', NULL, NULL, 'AZN', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (17, 'BA', '070', 'Bosnia and Herzegovina', NULL, NULL, 'BAM', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (18, 'BB', '052', 'Barbados', NULL, NULL, 'BBD', 'Barbados Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (19, 'BD', '050', 'Bangladesh', NULL, NULL, 'BDT', 'Bangladeshi Taka', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (20, 'BE', '056', 'Belgium', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (21, 'BF', '854', 'Burkina Faso', NULL, NULL, 'XOF', 'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (22, 'BG', '100', 'Bulgaria', NULL, NULL, 'BGN', 'Bulgarian Lev', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (23, 'BH', '048', 'Bahrain', NULL, NULL, 'BHD', 'Bahraini Dinar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (24, 'BI', '108', 'Burundi', NULL, NULL, 'BIF', 'Burundi Franc', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (25, 'BJ', '204', 'Benin', NULL, NULL, 'XOF', 'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (26, 'BL', '652', 'Saint Barthélemy', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (27, 'BM', '060', 'Bermuda', NULL, NULL, 'BMD', 'Bermudian Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (28, 'BN', '096', 'Brunei', NULL, NULL, 'BND', 'Brunei Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (29, 'BO', '068', 'Bolivia', NULL, NULL, 'BOB', 'Bolivian Boliviano', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (30, 'BQ', '535', 'Bonaire', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (31, 'BR', '076', 'Brazil', NULL, NULL, 'BRL', 'Brazilian Real', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (32, 'BS', '044', 'Bahamas', NULL, NULL, 'BSD', 'Bahamian Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (33, 'BT', '064', 'Bhutan', NULL, NULL, 'BTN', 'Bhutan Ngultrum', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (34, 'BV', '074', 'Bouvet Island', NULL, NULL, 'NOK', 'Norwegian Kroner', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (35, 'BW', '072', 'Botswana', NULL, NULL, 'BWP', 'Botswanian Pula', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (36, 'BY', '112', 'Belarus', NULL, NULL, 'BYR', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (37, 'BZ', '084', 'Belize', NULL, NULL, 'BZD', 'Belize Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (38, 'CA', '124', 'Canada', NULL, NULL, 'CAD', 'Canadian Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (39, 'CC', '166', 'Cocos [Keeling] Islands', NULL, NULL, 'AUD', 'Australian Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (40, 'CD', '180', 'Democratic Republic of the Congo', NULL, NULL, 'CDF', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (41, 'CF', '140', 'Central African Republic', NULL, NULL, 'XAF', 'CommunautÃ© FinanciÃ¨re Africaine BEAC, Francs', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (42, 'CG', '178', 'Republic of the Congo', NULL, NULL, 'XAF', 'CommunautÃ© FinanciÃ¨re Africaine BEAC, Francs', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (43, 'CH', '756', 'Switzerland', NULL, NULL, 'CHF', 'Swiss Franc', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (44, 'CI', '384', 'Ivory Coast', NULL, NULL, 'XOF', 'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (45, 'CK', '184', 'Cook Islands', NULL, NULL, 'NZD', 'New Zealand Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (46, 'CL', '152', 'Chile', NULL, NULL, 'CLP', 'Chilean Peso', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (47, 'CM', '120', 'Cameroon', NULL, NULL, 'XAF', 'CommunautÃ© FinanciÃ¨re Africaine BEAC, Francs', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (48, 'CN', '156', 'China', NULL, NULL, 'CNY', 'Yuan (Chinese) Renminbi', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (49, 'CO', '170', 'Colombia', NULL, NULL, 'COP', 'Colombian Peso', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (50, 'CR', '188', 'Costa Rica', NULL, NULL, 'CRC', 'Costa Rican Colon', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (51, 'CU', '192', 'Cuba', NULL, NULL, 'CUP', 'Cuban Peso', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (52, 'CV', '132', 'Cape Verde', NULL, NULL, 'CVE', 'Cape Verde Escudo', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (53, 'CW', '531', 'Curacao', NULL, NULL, 'ANG', 'Netherlands Antillian Guilder', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (54, 'CX', '162', 'Christmas Island', NULL, NULL, 'AUD', 'Australian Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (55, 'CY', '196', 'Cyprus', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (56, 'CZ', '203', 'Czechia', NULL, NULL, 'CZK', 'Czech Republic Koruna', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (57, 'DE', '276', 'Germany', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (58, 'DJ', '262', 'Djibouti', NULL, NULL, 'DJF', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (59, 'DK', '208', 'Denmark', NULL, NULL, 'DKK', 'Danish Krone', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (60, 'DM', '212', 'Dominica', NULL, NULL, 'XCD', 'East Caribbean Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (61, 'DO', '214', 'Dominican Republic', NULL, NULL, 'DOP', 'Dominican Peso', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (62, 'DZ', '012', 'Algeria', NULL, NULL, 'DZD', 'Algerian Dinar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (63, 'EC', '218', 'Ecuador', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (64, 'EE', '233', 'Estonia', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (65, 'EG', '818', 'Egypt', NULL, NULL, 'EGP', 'Egyptian Pound', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (66, 'EH', '732', 'Western Sahara', NULL, NULL, 'MAD', 'Moroccan Dirham', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (67, 'ER', '232', 'Eritrea', NULL, NULL, 'ERN', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (68, 'ES', '724', 'Spain', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (69, 'ET', '231', 'Ethiopia', NULL, NULL, 'ETB', 'Ethiopian Birr', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (70, 'FI', '246', 'Finland', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (71, 'FJ', '242', 'Fiji', NULL, NULL, 'FJD', 'Fiji Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (72, 'FK', '238', 'Falkland Islands', NULL, NULL, 'FKP', 'Falkland Islands Pound', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (73, 'FM', '583', 'Micronesia', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (74, 'FO', '234', 'Faroe Islands', NULL, NULL, 'DKK', 'Danish Krone', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (75, 'FR', '250', 'France', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (76, 'GA', '266', 'Gabon', NULL, NULL, 'XAF', 'CommunautÃ© FinanciÃ¨re Africaine BEAC, Francs', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (77, 'GB', '826', 'United Kingdom', NULL, NULL, 'GBP', 'British Pound', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (78, 'GD', '308', 'Grenada', NULL, NULL, 'XCD', 'East Caribbean Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (79, 'GE', '268', 'Georgia', NULL, NULL, 'GEL', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (80, 'GF', '254', 'French Guiana', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (81, 'GG', '831', 'Guernsey', NULL, NULL, 'GBP', 'British Pound', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (82, 'GH', '288', 'Ghana', NULL, NULL, 'GHS', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (83, 'GI', '292', 'Gibraltar', NULL, NULL, 'GIP', 'Gibraltar Pound', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (84, 'GL', '304', 'Greenland', NULL, NULL, 'DKK', 'Danish Krone', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (85, 'GM', '270', 'Gambia', NULL, NULL, 'GMD', 'Gambian Dalasi', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (86, 'GN', '324', 'Guinea', NULL, NULL, 'GNF', 'Guinea Franc', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (87, 'GP', '312', 'Guadeloupe', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (88, 'GQ', '226', 'Equatorial Guinea', NULL, NULL, 'XAF', 'CommunautÃ© FinanciÃ¨re Africaine BEAC, Francs', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (89, 'GR', '300', 'Greece', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (90, 'GS', '239', 'South Georgia and the South Sandwich Islands', NULL, NULL, 'GBP', 'British Pound', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (91, 'GT', '320', 'Guatemala', NULL, NULL, 'GTQ', 'Guatemalan Quetzal', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (92, 'GU', '316', 'Guam', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (93, 'GW', '624', 'Guinea-Bissau', NULL, NULL, 'XOF', 'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (94, 'GY', '328', 'Guyana', NULL, NULL, 'GYD', 'Guyanan Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (95, 'HK', '344', 'Hong Kong', NULL, NULL, 'HKD', 'Hong Kong Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (96, 'HM', '334', 'Heard Island and McDonald Islands', NULL, NULL, 'AUD', 'Australian Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (97, 'HN', '340', 'Honduras', NULL, NULL, 'HNL', 'Honduran Lempira', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (98, 'HR', '191', 'Croatia', NULL, NULL, 'HRK', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (99, 'HT', '332', 'Haiti', NULL, NULL, 'HTG', 'Haitian Gourde', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (100, 'HU', '348', 'Hungary', NULL, NULL, 'HUF', 'Hungarian Forint', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (101, 'ID', '360', 'Indonesia', NULL, NULL, 'IDR', 'Indonesian Rupiah', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (102, 'IE', '372', 'Ireland', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (103, 'IL', '376', 'Israel', NULL, NULL, 'ILS', 'Israeli Shekel', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (104, 'IM', '833', 'Isle of Man', NULL, NULL, 'GBP', 'British Pound', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (105, 'IN', '356', 'India', NULL, NULL, 'INR', 'Indian Rupee', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (106, 'IO', '086', 'British Indian Ocean Territory', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (107, 'IQ', '368', 'Iraq', NULL, NULL, 'IQD', 'Iraqi Dinar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (108, 'IR', '364', 'Iran', NULL, NULL, 'IRR', 'Iranian Rial', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (109, 'IS', '352', 'Iceland', NULL, NULL, 'ISK', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (110, 'IT', '380', 'Italy', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (111, 'JE', '832', 'Jersey', NULL, NULL, 'GBP', 'British Pound', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (112, 'JM', '388', 'Jamaica', NULL, NULL, 'JMD', 'Jamaican Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (113, 'JO', '400', 'Jordan', NULL, NULL, 'JOD', 'Jordanian Dinar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (114, 'JP', '392', 'Japan', NULL, NULL, 'JPY', 'Japanese Yen', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (115, 'KE', '404', 'Kenya', NULL, NULL, 'KES', 'Kenyan Schilling', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (116, 'KG', '417', 'Kyrgyzstan', NULL, NULL, 'KGS', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (117, 'KH', '116', 'Cambodia', NULL, NULL, 'KHR', 'Kampuchean (Cambodian) Riel', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (118, 'KI', '296', 'Kiribati', NULL, NULL, 'AUD', 'Australian Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (119, 'KM', '174', 'Comoros', NULL, NULL, 'KMF', 'Comoros Franc', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (120, 'KN', '659', 'Saint Kitts and Nevis', NULL, NULL, 'XCD', 'East Caribbean Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (121, 'KP', '408', 'North Korea', NULL, NULL, 'KPW', 'North Korean Won', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (122, 'KR', '410', 'South Korea', NULL, NULL, 'KRW', '(South) Korean Won', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (123, 'KW', '414', 'Kuwait', NULL, NULL, 'KWD', 'Kuwaiti Dinar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (124, 'KY', '136', 'Cayman Islands', NULL, NULL, 'KYD', 'Cayman Islands Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (125, 'KZ', '398', 'Kazakhstan', NULL, NULL, 'KZT', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (126, 'LA', '418', 'Laos', NULL, NULL, 'LAK', 'Lao Kip', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (127, 'LB', '422', 'Lebanon', NULL, NULL, 'LBP', 'Lebanese Pound', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (128, 'LC', '662', 'Saint Lucia', NULL, NULL, 'XCD', 'East Caribbean Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (129, 'LI', '438', 'Liechtenstein', NULL, NULL, 'CHF', 'Swiss Franc', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (130, 'LK', '144', 'Sri Lanka', NULL, NULL, 'LKR', 'Sri Lanka Rupee', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (131, 'LR', '430', 'Liberia', NULL, NULL, 'LRD', 'Liberian Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (132, 'LS', '426', 'Lesotho', NULL, NULL, 'LSL', 'Lesotho Loti', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (133, 'LT', '440', 'Lithuania', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (134, 'LU', '442', 'Luxembourg', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (135, 'LV', '428', 'Latvia', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (136, 'LY', '434', 'Libya', NULL, NULL, 'LYD', 'Libyan Dinar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (137, 'MA', '504', 'Morocco', NULL, NULL, 'MAD', 'Moroccan Dirham', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (138, 'MC', '492', 'Monaco', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (139, 'MD', '498', 'Moldova', NULL, NULL, 'MDL', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (140, 'ME', '499', 'Montenegro', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (141, 'MF', '663', 'Saint Martin', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (142, 'MG', '450', 'Madagascar', NULL, NULL, 'MGA', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (143, 'MH', '584', 'Marshall Islands', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (144, 'MK', '807', 'Macedonia', NULL, NULL, 'MKD', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (145, 'ML', '466', 'Mali', NULL, NULL, 'XOF', 'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (146, 'MM', '104', 'Myanmar [Burma]', NULL, NULL, 'MMK', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (147, 'MN', '496', 'Mongolia', NULL, NULL, 'MNT', 'Mongolian Tugrik', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (148, 'MO', '446', 'Macao', NULL, NULL, 'MOP', 'Macau Pataca', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (149, 'MP', '580', 'Northern Mariana Islands', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (150, 'MQ', '474', 'Martinique', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (151, 'MR', '478', 'Mauritania', NULL, NULL, 'MRO', 'Mauritanian Ouguiya', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (152, 'MS', '500', 'Montserrat', NULL, NULL, 'XCD', 'East Caribbean Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (153, 'MT', '470', 'Malta', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (154, 'MU', '480', 'Mauritius', NULL, NULL, 'MUR', 'Mauritius Rupee', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (155, 'MV', '462', 'Maldives', NULL, NULL, 'MVR', 'Maldive Rufiyaa', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (156, 'MW', '454', 'Malawi', NULL, NULL, 'MWK', 'Malawi Kwacha', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (157, 'MX', '484', 'Mexico', NULL, NULL, 'MXN', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (158, 'MY', '458', 'Malaysia', NULL, NULL, 'MYR', 'Malaysian Ringgit', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (159, 'MZ', '508', 'Mozambique', NULL, NULL, 'MZN', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (160, 'NA', '516', 'Namibia', NULL, NULL, 'NAD', 'Namibian Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (161, 'NC', '540', 'New Caledonia', NULL, NULL, 'XPF', 'Comptoirs FranÃ§ais du Pacifique Francs', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (162, 'NE', '562', 'Niger', NULL, NULL, 'XOF', 'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (163, 'NF', '574', 'Norfolk Island', NULL, NULL, 'AUD', 'Australian Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (164, 'NG', '566', 'Nigeria', NULL, NULL, 'NGN', 'Nigerian Naira', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (165, 'NI', '558', 'Nicaragua', NULL, NULL, 'NIO', 'Nicaraguan Cordoba', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (166, 'NL', '528', 'Netherlands', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (167, 'NO', '578', 'Norway', NULL, NULL, 'NOK', 'Norwegian Kroner', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (168, 'NP', '524', 'Nepal', NULL, NULL, 'NPR', 'Nepalese Rupee', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (169, 'NR', '520', 'Nauru', NULL, NULL, 'AUD', 'Australian Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (170, 'NU', '570', 'Niue', NULL, NULL, 'NZD', 'New Zealand Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (171, 'NZ', '554', 'New Zealand', NULL, NULL, 'NZD', 'New Zealand Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (172, 'OM', '512', 'Oman', NULL, NULL, 'OMR', 'Omani Rial', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (173, 'PA', '591', 'Panama', NULL, NULL, 'PAB', 'Panamanian Balboa', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (174, 'PE', '604', 'Peru', NULL, NULL, 'PEN', 'Peruvian Nuevo Sol', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (175, 'PF', '258', 'French Polynesia', NULL, NULL, 'XPF', 'Comptoirs FranÃ§ais du Pacifique Francs', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (176, 'PG', '598', 'Papua New Guinea', NULL, NULL, 'PGK', 'Papua New Guinea Kina', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (177, 'PH', '608', 'Philippines', NULL, NULL, 'PHP', 'Philippine Peso', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (178, 'PK', '586', 'Pakistan', NULL, NULL, 'PKR', 'Pakistan Rupee', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (179, 'PL', '616', 'Poland', NULL, NULL, 'PLN', 'Polish Zloty', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (180, 'PM', '666', 'Saint Pierre and Miquelon', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (181, 'PN', '612', 'Pitcairn Islands', NULL, NULL, 'NZD', 'New Zealand Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (182, 'PR', '630', 'Puerto Rico', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (183, 'PS', '275', 'Palestine', NULL, NULL, 'ILS', 'Israeli Shekel', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (184, 'PT', '620', 'Portugal', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (185, 'PW', '585', 'Palau', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (186, 'PY', '600', 'Paraguay', NULL, NULL, 'PYG', 'Paraguay Guarani', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (187, 'QA', '634', 'Qatar', NULL, NULL, 'QAR', 'Qatari Rial', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (188, 'RE', '638', 'Réunion', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (189, 'RO', '642', 'Romania', NULL, NULL, 'RON', 'Romanian Leu', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (190, 'RS', '688', 'Serbia', NULL, NULL, 'RSD', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (191, 'RU', '643', 'Russia', NULL, NULL, 'RUB', 'Russian Ruble', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (192, 'RW', '646', 'Rwanda', NULL, NULL, 'RWF', 'Rwanda Franc', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (193, 'SA', '682', 'Saudi Arabia', NULL, NULL, 'SAR', 'Saudi Arabian Riyal', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (194, 'SB', '090', 'Solomon Islands', NULL, NULL, 'SBD', 'Solomon Islands Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (195, 'SC', '690', 'Seychelles', NULL, NULL, 'SCR', 'Seychelles Rupee', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (196, 'SD', '729', 'Sudan', NULL, NULL, 'SDG', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (197, 'SE', '752', 'Sweden', NULL, NULL, 'SEK', 'Swedish Krona', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (198, 'SG', '702', 'Singapore', NULL, NULL, 'SGD', 'Singapore Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (199, 'SH', '654', 'Saint Helena', NULL, NULL, 'SHP', 'St. Helena Pound', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (200, 'SI', '705', 'Slovenia', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (201, 'SJ', '744', 'Svalbard and Jan Mayen', NULL, NULL, 'NOK', 'Norwegian Kroner', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (202, 'SK', '703', 'Slovakia', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (203, 'SL', '694', 'Sierra Leone', NULL, NULL, 'SLL', 'Sierra Leone Leone', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (204, 'SM', '674', 'San Marino', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (205, 'SN', '686', 'Senegal', NULL, NULL, 'XOF', 'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (206, 'SO', '706', 'Somalia', NULL, NULL, 'SOS', 'Somali Schilling', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (207, 'SR', '740', 'Suriname', NULL, NULL, 'SRD', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (208, 'SS', '728', 'South Sudan', NULL, NULL, 'SSP', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (209, 'ST', '678', 'São Tomé and Príncipe', NULL, NULL, 'STD', 'Sao Tome and Principe Dobra', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (210, 'SV', '222', 'El Salvador', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (211, 'SX', '534', 'Sint Maarten', NULL, NULL, 'ANG', 'Netherlands Antillian Guilder', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (212, 'SY', '760', 'Syria', NULL, NULL, 'SYP', 'Syrian Potmd', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (213, 'SZ', '748', 'Swaziland', NULL, NULL, 'SZL', 'Swaziland Lilangeni', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (214, 'TC', '796', 'Turks and Caicos Islands', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (215, 'TD', '148', 'Chad', NULL, NULL, 'XAF', 'CommunautÃ© FinanciÃ¨re Africaine BEAC, Francs', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (216, 'TF', '260', 'French Southern Territories', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (217, 'TG', '768', 'Togo', NULL, NULL, 'XOF', 'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (218, 'TH', '764', 'Thailand', NULL, NULL, 'THB', 'Thai Baht', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (219, 'TJ', '762', 'Tajikistan', NULL, NULL, 'TJS', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (220, 'TK', '772', 'Tokelau', NULL, NULL, 'NZD', 'New Zealand Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (221, 'TL', '626', 'East Timor', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (222, 'TM', '795', 'Turkmenistan', NULL, NULL, 'TMT', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (223, 'TN', '788', 'Tunisia', NULL, NULL, 'TND', 'Tunisian Dinar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (224, 'TO', '776', 'Tonga', NULL, NULL, 'TOP', 'Tongan Paanga', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (225, 'TR', '792', 'Turkey', NULL, NULL, 'TRY', 'Turkish Lira', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (226, 'TT', '780', 'Trinidad and Tobago', NULL, NULL, 'TTD', 'Trinidad and Tobago Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (227, 'TV', '798', 'Tuvalu', NULL, NULL, 'AUD', 'Australian Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (228, 'TW', '158', 'Taiwan', NULL, NULL, 'TWD', 'Taiwan Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (229, 'TZ', '834', 'Tanzania', NULL, NULL, 'TZS', 'Tanzanian Schilling', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (230, 'UA', '804', 'Ukraine', NULL, NULL, 'UAH', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (231, 'UG', '800', 'Uganda', NULL, NULL, 'UGX', 'Uganda Shilling', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (232, 'UM', '581', 'U.S. Minor Outlying Islands', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (233, 'US', '840', 'United States', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (234, 'UY', '858', 'Uruguay', NULL, NULL, 'UYU', 'Uruguayan Peso', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (235, 'UZ', '860', 'Uzbekistan', NULL, NULL, 'UZS', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (236, 'VA', '336', 'Vatican City', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (237, 'VC', '670', 'Saint Vincent and the Grenadines', NULL, NULL, 'XCD', 'East Caribbean Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (238, 'VE', '862', 'Venezuela', NULL, NULL, 'VEF', 'Venezualan Bolivar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (239, 'VG', '092', 'British Virgin Islands', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (240, 'VI', '850', 'U.S. Virgin Islands', NULL, NULL, 'USD', 'US Dollar', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (241, 'VN', '704', 'Vietnam', NULL, NULL, 'VND', 'Vietnamese Dong', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (242, 'VU', '548', 'Vanuatu', NULL, NULL, 'VUV', 'Vanuatu Vatu', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (243, 'WF', '876', 'Wallis and Futuna', NULL, NULL, 'XPF', 'Comptoirs FranÃ§ais du Pacifique Francs', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (244, 'WS', '882', 'Samoa', NULL, NULL, 'WST', 'Samoan Tala', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (245, 'XK', '0', 'Kosovo', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (246, 'YE', '887', 'Yemen', NULL, NULL, 'YER', 'Yemeni Rial', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (247, 'YT', '175', 'Mayotte', NULL, NULL, 'EUR', 'Euro', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (248, 'ZA', '710', 'South Africa', NULL, NULL, 'ZAR', 'South African Rand', NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (249, 'ZM', '894', 'Zambia', NULL, NULL, 'ZMW', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42'),
                            (250, 'ZW', '716', 'Zimbabwe', NULL, NULL, 'ZWL', NULL, NULL, NULL, '2018-01-30 06:38:42', '2018-01-30 06:38:42');

                END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `challanno` int(10) UNSIGNED NOT NULL,
  `invoiceno` int(10) UNSIGNED NOT NULL,
  `challandate` date NOT NULL,
  `relationship_id` int(10) UNSIGNED DEFAULT NULL,
  `approver` int(10) UNSIGNED DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `depreciation`
--

CREATE TABLE `depreciation` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `accNo` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `fpNo` int(11) NOT NULL,
  `opnDate` date NOT NULL,
  `endDate` date NOT NULL,
  `openBall` decimal(15,2) NOT NULL,
  `Addition` decimal(15,2) NOT NULL,
  `totalVal` decimal(15,2) NOT NULL,
  `depRate` decimal(5,2) NOT NULL,
  `deprAmt` decimal(15,2) NOT NULL,
  `finalval` decimal(15,2) NOT NULL,
  `postingStatus` tinyint(1) NOT NULL,
  `postDate` date DEFAULT NULL,
  `contraAcc` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `userCreated` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fiscal_period`
--

CREATE TABLE `fiscal_period` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `FiscalYear` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `year` int(11) NOT NULL,
  `fpNo` int(11) NOT NULL,
  `monthSl` int(11) NOT NULL,
  `monthName` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `status` tinyint(1) NOT NULL,
  `depriciation` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fiscal_period`
--

INSERT INTO `fiscal_period` (`id`, `compCode`, `FiscalYear`, `year`, `fpNo`, `monthSl`, `monthName`, `startDate`, `endDate`, `status`, `depriciation`, `created_at`, `updated_at`) VALUES
(1, 11001, '2018-2018', 2018, 1, 1, 'January', '2018-01-01', '2018-01-31', 1, 0, '2018-02-04 15:59:25', '2018-02-04 15:59:25'),
(2, 11001, '2018-2018', 2018, 2, 2, 'February', '2018-02-01', '2018-02-28', 1, 0, '2018-02-04 15:59:25', '2018-02-04 15:59:25'),
(3, 11001, '2018-2018', 2018, 3, 3, 'March', '2018-03-01', '2018-03-31', 1, 0, '2018-02-04 15:59:25', '2018-02-04 15:59:25'),
(4, 11001, '2018-2018', 2018, 4, 4, 'April', '2018-04-01', '2018-04-30', 1, 0, '2018-02-04 15:59:25', '2018-02-04 15:59:25'),
(5, 11001, '2018-2018', 2018, 5, 5, 'May', '2018-05-01', '2018-05-31', 1, 0, '2018-02-04 15:59:25', '2018-02-04 15:59:25'),
(6, 11001, '2018-2018', 2018, 6, 6, 'June', '2018-06-01', '2018-06-30', 1, 0, '2018-02-04 15:59:25', '2018-02-04 15:59:25'),
(7, 11001, '2018-2018', 2018, 7, 7, 'July', '2018-07-01', '2018-07-31', 1, 0, '2018-02-04 15:59:25', '2018-02-04 15:59:25'),
(8, 11001, '2018-2018', 2018, 8, 8, 'August', '2018-08-01', '2018-08-31', 1, 0, '2018-02-04 15:59:25', '2018-02-04 15:59:25'),
(9, 11001, '2018-2018', 2018, 9, 9, 'September', '2018-09-01', '2018-09-30', 1, 0, '2018-02-04 15:59:25', '2018-02-04 15:59:25'),
(10, 11001, '2018-2018', 2018, 10, 10, 'October', '2018-10-01', '2018-10-31', 1, 0, '2018-02-04 15:59:25', '2018-02-04 15:59:25'),
(11, 11001, '2018-2018', 2018, 11, 11, 'November', '2018-11-01', '2018-11-30', 1, 0, '2018-02-04 15:59:25', '2018-02-04 15:59:25'),
(12, 11001, '2018-2018', 2018, 12, 12, 'December', '2018-12-01', '2018-12-31', 1, 0, '2018-02-04 15:59:25', '2018-02-04 15:59:25');

-- --------------------------------------------------------

--
-- Table structure for table `godowns`
--

CREATE TABLE `godowns` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `godownName` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_movements`
--

CREATE TABLE `item_movements` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `refno` int(10) UNSIGNED NOT NULL,
  `barcode` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `contra` int(10) UNSIGNED NOT NULL,
  `reftype` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` decimal(15,2) NOT NULL DEFAULT '0.00',
  `received` decimal(15,2) NOT NULL DEFAULT '0.00',
  `returned` decimal(15,2) NOT NULL DEFAULT '0.00',
  `delevered` decimal(15,2) NOT NULL DEFAULT '0.00',
  `remarks` varchar(190) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `models`
--

CREATE TABLE `models` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `modelNo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `name` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `productCode` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `relationship_id` int(10) UNSIGNED DEFAULT NULL,
  `brand_id` int(10) UNSIGNED DEFAULT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `subcategory_id` int(10) UNSIGNED DEFAULT NULL,
  `unit_name` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `varient` tinyint(1) NOT NULL DEFAULT '0',
  `size` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `color` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sku` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `model_id` int(10) UNSIGNED DEFAULT NULL,
  `taxgrp_id` int(10) UNSIGNED DEFAULT NULL,
  `tax_id` int(10) UNSIGNED DEFAULT NULL,
  `godown_id` int(10) UNSIGNED DEFAULT NULL,
  `rack_id` int(10) UNSIGNED DEFAULT NULL,
  `initialPrice` decimal(15,2) NOT NULL DEFAULT '0.00',
  `buyPrice` decimal(15,2) NOT NULL DEFAULT '0.00',
  `wholesalePrice` decimal(15,2) NOT NULL DEFAULT '0.00',
  `retailPrice` decimal(15,2) NOT NULL DEFAULT '0.00',
  `unitPrice` decimal(15,2) NOT NULL DEFAULT '0.00',
  `reorderpoint` decimal(15,2) NOT NULL DEFAULT '0.00',
  `openingQty` decimal(15,2) NOT NULL DEFAULT '0.00',
  `openingValue` decimal(15,2) NOT NULL DEFAULT '0.00',
  `onhand` decimal(15,2) NOT NULL DEFAULT '0.00',
  `committed` decimal(15,2) NOT NULL DEFAULT '0.00',
  `incomming` decimal(15,2) NOT NULL DEFAULT '0.00',
  `maxonlinestock` decimal(15,2) NOT NULL DEFAULT '0.00',
  `minonlineorder` decimal(15,2) NOT NULL DEFAULT '0.00',
  `purchaseQty` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sellQty` decimal(15,2) NOT NULL DEFAULT '0.00',
  `salvageQty` decimal(15,2) NOT NULL DEFAULT '0.00',
  `saleable` tinyint(1) NOT NULL DEFAULT '0',
  `receivedQty` decimal(15,2) NOT NULL DEFAULT '0.00',
  `returnQty` decimal(15,2) NOT NULL DEFAULT '0.00',
  `shipping` int(10) UNSIGNED DEFAULT '0',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `description_short` text COLLATE utf8_unicode_ci,
  `description_long` text COLLATE utf8_unicode_ci,
  `stuff_included` text COLLATE utf8_unicode_ci,
  `warranty_period` double UNSIGNED DEFAULT NULL,
  `image` varchar(195) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_large` varchar(195) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sellable` tinyint(1) NOT NULL DEFAULT '1',
  `purchasable` tinyint(1) NOT NULL DEFAULT '1',
  `b2bpublish` tinyint(1) NOT NULL DEFAULT '0',
  `free` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `taxable` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `projCode` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `projType` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `projName` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `projDesc` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `projRef` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `projBudget` decimal(15,2) DEFAULT '0.00',
  `expense` decimal(15,2) DEFAULT '0.00',
  `income` decimal(15,2) DEFAULT '0.00',
  `userCreated` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `refno` int(10) UNSIGNED NOT NULL,
  `type` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `pdate` date NOT NULL,
  `relationship_id` int(10) UNSIGNED DEFAULT NULL,
  `invoice_amt` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid_amt` decimal(15,2) NOT NULL DEFAULT '0.00',
  `due_amt` decimal(15,2) NOT NULL DEFAULT '0.00',
  `approver` int(10) UNSIGNED DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `racks`
--

CREATE TABLE `racks` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `godown_id` int(10) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receives`
--

CREATE TABLE `receives` (
  `compCode` int(11) NOT NULL,
  `refno` int(10) UNSIGNED NOT NULL,
  `challanno` int(10) UNSIGNED NOT NULL,
  `purchase_id` int(10) UNSIGNED DEFAULT NULL,
  `challandate` date NOT NULL,
  `relationship_id` int(10) UNSIGNED DEFAULT NULL,
  `approver` int(10) UNSIGNED DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `relationships`
--

CREATE TABLE `relationships` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `type` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `company_code` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `tax_number` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `glcode` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `street` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipcode` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax_number` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `asigned` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `default_price` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Whole Sale / Retail',
  `default_discount` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `default_payment_term` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `default_payment_method` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `min_order_value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requisitions`
--

CREATE TABLE `requisitions` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `refNo` int(10) UNSIGNED NOT NULL,
  `reqType` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `reqDate` date NOT NULL,
  `product_id` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` decimal(15,2) NOT NULL DEFAULT '0.00',
  `approver` int(10) UNSIGNED DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `invoiceno` int(10) UNSIGNED NOT NULL,
  `type` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `invoicedate` date NOT NULL,
  `relationship_id` int(10) UNSIGNED DEFAULT NULL,
  `invoice_amt` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid_amt` decimal(15,2) NOT NULL DEFAULT '0.00',
  `due_amt` decimal(15,2) NOT NULL DEFAULT '0.00',
  `approver` int(10) UNSIGNED DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `size` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stmt_data`
--

CREATE TABLE `stmt_data` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `fileNo` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `lineNo` int(11) NOT NULL DEFAULT '0',
  `textPosition` int(11) NOT NULL DEFAULT '0',
  `font` int(11) NOT NULL DEFAULT '10',
  `texts` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `accType` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `balType` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ac11` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ac12` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ac21` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ac22` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `figrPosition` int(11) DEFAULT '0',
  `subTotal` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pFormula` varchar(51) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rangeVal1` decimal(15,2) DEFAULT NULL,
  `prntVal1` decimal(15,2) DEFAULT '0.00',
  `rangeVal2` decimal(15,2) DEFAULT '0.00',
  `prntVal2` decimal(15,2) DEFAULT '0.00',
  `rangeVal3` decimal(15,2) DEFAULT '0.00',
  `prntVal3` decimal(15,2) DEFAULT '0.00',
  `prntVal` decimal(15,2) DEFAULT '0.00',
  `pcnt` decimal(15,2) DEFAULT '0.00',
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fp00` decimal(15,2) DEFAULT '0.00',
  `fp01` decimal(15,2) DEFAULT '0.00',
  `fp02` decimal(15,2) DEFAULT '0.00',
  `fp03` decimal(15,2) DEFAULT '0.00',
  `fp04` decimal(15,2) DEFAULT '0.00',
  `fp05` decimal(15,2) DEFAULT '0.00',
  `fp06` decimal(15,2) DEFAULT '0.00',
  `fp07` decimal(15,2) DEFAULT '0.00',
  `fp08` decimal(15,2) DEFAULT '0.00',
  `fp09` decimal(15,2) DEFAULT '0.00',
  `fp10` decimal(15,2) DEFAULT '0.00',
  `fp11` decimal(15,2) DEFAULT '0.00',
  `fp12` decimal(15,2) DEFAULT '0.00',
  `pfp01` decimal(15,2) DEFAULT '0.00',
  `pfp02` decimal(15,2) DEFAULT '0.00',
  `pfp03` decimal(15,2) DEFAULT '0.00',
  `pfp04` decimal(15,2) DEFAULT '0.00',
  `pfp05` decimal(15,2) DEFAULT '0.00',
  `pfp06` decimal(15,2) DEFAULT '0.00',
  `pfp07` decimal(15,2) DEFAULT '0.00',
  `pfp08` decimal(15,2) DEFAULT '0.00',
  `pfp09` decimal(15,2) DEFAULT '0.00',
  `pfp10` decimal(15,2) DEFAULT '0.00',
  `pfp11` decimal(15,2) DEFAULT '0.00',
  `pfp12` decimal(15,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stmt_lists`
--

CREATE TABLE `stmt_lists` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `fileNo` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `fileDesc` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `importFile` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `importLine` int(11) DEFAULT '0',
  `intoLine` int(11) DEFAULT '0',
  `baseFormula` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `importValue` decimal(15,2) DEFAULT '0.00',
  `odby` int(11) DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `valueDate` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stmt_lists`
--

INSERT INTO `stmt_lists` (`id`, `compCode`, `fileNo`, `fileDesc`, `importFile`, `importLine`, `intoLine`, `baseFormula`, `importValue`, `odby`, `user_id`, `valueDate`, `created_at`, `updated_at`) VALUES
(1, 11001, 'C001', 'Road', '15', 12, 20, NULL, '0.00', NULL, 1, NULL, '2018-10-29 03:23:44', '2018-10-29 03:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `alias` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Triggers `sub_categories`
--
DELIMITER $$
CREATE TRIGGER `copy_alias_sub` BEFORE INSERT ON `sub_categories` FOR EACH ROW IF NEW.alias IS NULL OR LENGTH(NEW.alias) < 1 THEN
                SET NEW.alias := NEW.name;
                END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `tax_id` int(10) UNSIGNED NOT NULL,
  `taxName` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `applicableOn` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'S' COMMENT 'P=Purchase ; S= Sales ;',
  `rate` decimal(15,2) NOT NULL DEFAULT '0.00',
  `calculatingMode` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'P' COMMENT 'P=Percentage ; F= Fixed Amount ;',
  `description` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax_groups`
--

CREATE TABLE `tax_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `taxgrp_code` int(11) NOT NULL,
  `taxGroupName` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `tax_id` int(10) UNSIGNED NOT NULL,
  `tax1_id` int(10) UNSIGNED DEFAULT NULL,
  `tax2_id` int(10) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temp_gl`
--

CREATE TABLE `temp_gl` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `jCode` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `projCode` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `refNo` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `chequeNo` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transDate` date NOT NULL,
  `voucherNo` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `accNo` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contraNo` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `drAmt` decimal(15,2) NOT NULL DEFAULT '0.00',
  `crAmt` decimal(15,2) NOT NULL DEFAULT '0.00',
  `transAmt` decimal(15,2) NOT NULL DEFAULT '0.00',
  `currCode` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transDesc1` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transDesc2` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `period` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `jCode` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `projCode` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fpNo` int(11) NOT NULL DEFAULT '0',
  `refNo` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `chequeNo` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `costCenter` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transId` varchar(18) COLLATE utf8_unicode_ci NOT NULL,
  `transGrpId` varchar(18) COLLATE utf8_unicode_ci NOT NULL,
  `transDate` date NOT NULL,
  `voucherNo` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `accDr` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accCr` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `drAmt` decimal(15,2) NOT NULL DEFAULT '0.00',
  `crAmt` decimal(15,2) NOT NULL DEFAULT '0.00',
  `transAmt` decimal(15,2) NOT NULL DEFAULT '0.00',
  `currCode` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `exchRate` decimal(8,2) DEFAULT NULL,
  `fiscalYear` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transDesc1` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transDesc2` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postFlag` tinyint(1) DEFAULT NULL,
  `postDate` date DEFAULT NULL,
  `postedBy` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `jvFlag` tinyint(1) DEFAULT NULL,
  `exportFlag` tinyint(1) DEFAULT NULL,
  `userCreated` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trans_codes`
--

CREATE TABLE `trans_codes` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `transCode` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `transName` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `lastTransNo` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trans_codes`
--

INSERT INTO `trans_codes` (`id`, `compCode`, `transCode`, `transName`, `lastTransNo`, `created_at`, `updated_at`) VALUES
(1, 11001, 'CP', 'Cash Payment', 100002, '2018-02-04 15:58:25', '2018-10-19 12:41:45'),
(2, 11001, 'CR', 'Cash Receipt', 200000, '2018-02-04 15:58:25', '2018-02-04 15:58:25'),
(3, 11001, 'BP', 'Bank Payment', 300001, '2018-02-04 15:58:25', '2018-10-19 12:17:58'),
(4, 11001, 'BR', 'Bank Receipt', 400000, '2018-02-04 15:58:25', '2018-02-04 15:58:25'),
(5, 11001, 'JV', 'Journal', 500006, '2018-02-04 15:58:25', '2018-11-04 01:04:16'),
(6, 11001, 'SI', 'Sales Invoice', 600000, '2018-02-04 15:58:25', '2018-02-04 15:58:25'),
(7, 11001, 'PR', 'Purchase', 700000, '2018-02-04 15:58:25', '2018-02-04 15:58:25'),
(8, 11001, 'DC', 'Delivery Challan', 800000, '2018-02-04 15:58:25', '2018-02-04 15:58:25'),
(9, 11001, 'RQ', 'Requisition', 900000, '2018-02-04 15:58:25', '2018-02-04 15:58:25');

-- --------------------------------------------------------

--
-- Table structure for table `trans_products`
--

CREATE TABLE `trans_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `refno` int(10) UNSIGNED NOT NULL,
  `contra` int(10) UNSIGNED NOT NULL,
  `reftype` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `toWhome` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` decimal(15,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_id` int(10) UNSIGNED DEFAULT NULL,
  `tax_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `approved` decimal(15,2) NOT NULL DEFAULT '0.00',
  `purchased` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sold` decimal(15,2) NOT NULL DEFAULT '0.00',
  `received` decimal(15,2) NOT NULL DEFAULT '0.00',
  `returned` decimal(15,2) NOT NULL DEFAULT '0.00',
  `delevered` decimal(15,2) NOT NULL DEFAULT '0.00',
  `remarks` varchar(190) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `formalName` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `noOfDecimalplaces` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL DEFAULT '0',
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `lastLogin` timestamp NULL DEFAULT NULL,
  `visitor` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wrongPassCount` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `compCode`, `name`, `email`, `password`, `role`, `lastLogin`, `visitor`, `wrongPassCount`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 11001, 'Admin User', 'admin@gmail.com', 'VhFvcs5NLr6UM', 'Admin', NULL, NULL, 0, 1, 'p8ftbZnFlAV07byBJi4e6b0qjScdEo9eyX8jbYnnxqslLNpdns5089nOGbLL', NULL, NULL),
(2, 11001, 'Albert Fordjour Antwi', 'aantwi1@st.vvu.edu.gh', '$2y$10$QgQJqTO8vJPq.fVPglROpe6AdTwT08zF85pq1jGunsutu4wWXNIEO', 'Admin', NULL, NULL, 0, 1, 'WyZ1WlDkICtDeB4yLDOywyBdeCT1TdHYyOopo0BdDug53LxhUodUVucx8s2a', '2018-10-18 15:23:08', '2018-10-18 15:23:08'),
(3, 11001, 'Engineer', 'engineer@gmail.com', '$2y$10$IieglHZTjUWp.NBKqiWvZu9zHgaLU1VgaEk3vwMyFwqdSc2WvBLtu', 'User', NULL, NULL, 0, 1, 'B2siZnFvEiZhqfpiUjm1k3r0HwHmH5YIRBXLsBQaudvxck6PuY8zLfEgnlWK', '2018-10-25 17:34:27', '2018-10-25 17:34:27'),
(4, 11001, 'bismark', 'info@llc.com', '$2y$10$v10YkBVmRSe2DIbQNN4kGemlzt2AaLL2EIyvOtDCvHBz5iVL7vtyO', 'User', NULL, NULL, 0, 1, '41FpXHRtZVesxXnVwSQ48LOfc3ApkSRoZQEjqNMdQ3TIt7Om0UY1bPv78uVg', '2018-11-02 06:27:12', '2018-11-02 06:27:12');

-- --------------------------------------------------------

--
-- Table structure for table `user_actions`
--

CREATE TABLE `user_actions` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `action_model` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_actions`
--

INSERT INTO `user_actions` (`id`, `compCode`, `user_id`, `action`, `action_model`, `action_id`, `created_at`, `updated_at`) VALUES
(1, 11001, 1, 'created', 'users', 2, '2018-10-18 15:23:08', '2018-10-18 15:23:08'),
(2, 11001, 1, 'updated', 'users', 1, '2018-10-18 15:27:15', '2018-10-18 15:27:15'),
(3, 11001, 2, 'updated', 'users', 2, '2018-10-18 15:55:03', '2018-10-18 15:55:03'),
(4, 11001, 1, 'updated', 'users', 1, '2018-10-19 12:29:06', '2018-10-19 12:29:06'),
(5, 11001, 1, 'updated', 'users', 1, '2018-10-19 12:47:02', '2018-10-19 12:47:02'),
(6, 11001, 1, 'updated', 'users', 1, '2018-10-19 13:09:16', '2018-10-19 13:09:16'),
(7, 11001, 1, 'created', 'users', 3, '2018-10-25 17:34:27', '2018-10-25 17:34:27'),
(8, 11001, 1, 'updated', 'users', 1, '2018-10-25 17:34:38', '2018-10-25 17:34:38'),
(9, 11001, 3, 'updated', 'users', 3, '2018-10-25 17:41:30', '2018-10-25 17:41:30'),
(10, 11001, 3, 'updated', 'users', 3, '2018-10-25 17:46:22', '2018-10-25 17:46:22'),
(11, 11001, 1, 'updated', 'users', 1, '2018-10-29 03:14:05', '2018-10-29 03:14:05'),
(12, 11001, 1, 'updated', 'users', 1, '2018-10-29 03:15:53', '2018-10-29 03:15:53'),
(13, 11001, 1, 'updated', 'users', 1, '2018-10-29 03:20:22', '2018-10-29 03:20:22'),
(14, 11001, 1, 'updated', 'users', 1, '2018-10-29 03:22:45', '2018-10-29 03:22:45'),
(15, 11001, 3, 'updated', 'users', 3, '2018-10-30 21:02:24', '2018-10-30 21:02:24'),
(16, 11001, 1, 'updated', 'users', 1, '2018-10-30 21:21:40', '2018-10-30 21:21:40'),
(17, 11001, 3, 'updated', 'users', 3, '2018-10-30 21:25:09', '2018-10-30 21:25:09'),
(18, 11001, 1, 'updated', 'users', 1, '2018-10-31 00:19:50', '2018-10-31 00:19:50'),
(19, 11001, 1, 'updated', 'users', 1, '2018-10-31 17:01:38', '2018-10-31 17:01:38'),
(20, 11001, 1, 'created', 'users', 4, '2018-11-02 06:27:12', '2018-11-02 06:27:12'),
(21, 11001, 1, 'updated', 'users', 1, '2018-11-02 19:29:45', '2018-11-02 19:29:45'),
(22, 11001, 4, 'updated', 'users', 4, '2018-11-03 02:19:45', '2018-11-03 02:19:45'),
(23, 11001, 1, 'updated', 'users', 1, '2018-11-03 02:27:00', '2018-11-03 02:27:00'),
(24, 11001, 4, 'updated', 'users', 4, '2018-11-04 00:07:17', '2018-11-04 00:07:17'),
(25, 11001, 1, 'updated', 'users', 1, '2018-11-04 00:18:44', '2018-11-04 00:18:44'),
(26, 11001, 4, 'updated', 'users', 4, '2018-11-04 00:26:40', '2018-11-04 00:26:40'),
(27, 11001, 1, 'updated', 'users', 1, '2018-11-04 00:42:08', '2018-11-04 00:42:08'),
(28, 11001, 1, 'updated', 'users', 1, '2018-11-04 00:42:28', '2018-11-04 00:42:28'),
(29, 11001, 4, 'updated', 'users', 4, '2018-11-04 00:58:40', '2018-11-04 00:58:40'),
(30, 11001, 1, 'updated', 'users', 1, '2018-11-04 01:04:29', '2018-11-04 01:04:29'),
(31, 11001, 4, 'updated', 'users', 4, '2018-11-04 01:23:12', '2018-11-04 01:23:12'),
(32, 11001, 4, 'updated', 'users', 4, '2018-11-04 01:48:00', '2018-11-04 01:48:00'),
(33, 11001, 1, 'updated', 'users', 1, '2018-11-04 01:49:58', '2018-11-04 01:49:58'),
(34, 11001, 1, 'updated', 'users', 1, '2018-11-04 01:56:14', '2018-11-04 01:56:14'),
(35, 11001, 1, 'updated', 'users', 1, '2018-11-04 02:14:24', '2018-11-04 02:14:24'),
(36, 11001, 1, 'updated', 'users', 1, '2018-11-04 02:16:33', '2018-11-04 02:16:33'),
(37, 11001, 1, 'updated', 'users', 1, '2018-11-04 02:18:31', '2018-11-04 02:18:31');

-- --------------------------------------------------------

--
-- Table structure for table `user_privileges`
--

CREATE TABLE `user_privileges` (
  `id` int(10) UNSIGNED NOT NULL,
  `compCode` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `menuId` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `useCaseId` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `view` tinyint(1) NOT NULL DEFAULT '0',
  `add` tinyint(1) NOT NULL DEFAULT '0',
  `edit` tinyint(1) NOT NULL DEFAULT '0',
  `delete` tinyint(1) NOT NULL DEFAULT '0',
  `privilege` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_privileges`
--

INSERT INTO `user_privileges` (`id`, `compCode`, `email`, `menuId`, `useCaseId`, `view`, `add`, `edit`, `delete`, `privilege`, `created_at`, `updated_at`) VALUES
(1, 11001, 'admin@gmail.com', 'A', 'A01', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(2, 11001, 'admin@gmail.com', 'A', 'A02', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(3, 11001, 'admin@gmail.com', 'A', 'A03', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(4, 11001, 'admin@gmail.com', 'A', 'A04', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(5, 11001, 'admin@gmail.com', 'B', 'B01', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(6, 11001, 'admin@gmail.com', 'B', 'B02', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(7, 11001, 'admin@gmail.com', 'B', 'B03', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(8, 11001, 'admin@gmail.com', 'B', 'B04', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(9, 11001, 'admin@gmail.com', 'B', 'B05', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(10, 11001, 'admin@gmail.com', 'B', 'B06', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(11, 11001, 'admin@gmail.com', 'B', 'B07', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(12, 11001, 'admin@gmail.com', 'B', 'B08', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(13, 11001, 'admin@gmail.com', 'C', 'C01', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(14, 11001, 'admin@gmail.com', 'C', 'C02', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(15, 11001, 'admin@gmail.com', 'C', 'C03', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(16, 11001, 'admin@gmail.com', 'C', 'C04', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(17, 11001, 'admin@gmail.com', 'C', 'C05', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(18, 11001, 'admin@gmail.com', 'C', 'C20', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(19, 11001, 'admin@gmail.com', 'C', 'C21', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(20, 11001, 'admin@gmail.com', 'R', 'R01', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(21, 11001, 'admin@gmail.com', 'R', 'R02', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(22, 11001, 'admin@gmail.com', 'R', 'R03', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(23, 11001, 'admin@gmail.com', 'R', 'R04', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:41'),
(24, 11001, 'admin@gmail.com', 'R', 'R05', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:42'),
(25, 11001, 'admin@gmail.com', 'S', 'S01', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:42'),
(26, 11001, 'admin@gmail.com', 'S', 'S02', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:42'),
(27, 11001, 'admin@gmail.com', 'S', 'S03', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:42'),
(28, 11001, 'admin@gmail.com', 'P', 'P01', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:42'),
(29, 11001, 'admin@gmail.com', 'P', 'P02', 1, 1, 1, 1, NULL, '2018-02-04 15:58:44', '2018-10-18 09:57:42');

-- --------------------------------------------------------

--
-- Table structure for table `use_cases`
--

CREATE TABLE `use_cases` (
  `id` int(10) UNSIGNED NOT NULL,
  `menuId` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `useCaseId` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `useCaseName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `use_cases`
--

INSERT INTO `use_cases` (`id`, `menuId`, `useCaseId`, `useCaseName`, `created_at`, `updated_at`) VALUES
(1, 'A', 'A01', 'User Profile', '2017-01-07 05:46:13', '2017-01-07 05:46:13'),
(2, 'A', 'A02', 'User Privilege', '2017-01-07 05:46:13', '2017-01-07 05:46:13'),
(3, 'A', 'A03', 'Change Password', '2017-01-07 05:46:13', '2017-01-07 05:46:13'),
(4, 'A', 'A04', 'Reset Password', '2017-01-07 05:46:13', '2017-01-07 05:46:13'),
(5, 'B', 'B01', 'Company Profile', '2017-01-07 05:46:13', '2017-01-07 05:46:13'),
(6, 'B', 'B02', 'Project Profile', '2017-01-07 05:46:13', '2017-01-07 05:46:13'),
(7, 'B', 'B03', 'Fiscal Period', '2017-01-07 05:46:13', '2017-01-07 05:46:13'),
(8, 'B', 'B04', 'Account Group', '2017-01-07 05:46:13', '2017-01-07 05:46:13'),
(9, 'B', 'B05', 'Account Head', '2017-01-07 05:46:13', '2017-01-07 05:46:13'),
(10, 'B', 'B06', 'Opening Balance', '2017-01-07 05:46:13', '2017-01-07 05:46:13'),
(11, 'B', 'B07', 'Fixed Asset Depreciation', '2017-01-07 05:46:13', '2017-01-07 05:46:13'),
(12, 'B', 'B08', 'Accounts Budget', '2017-01-07 05:46:13', '2017-01-07 05:46:13'),
(13, 'C', 'C01', 'Cash Payment', '2017-01-07 05:46:13', '2017-01-07 05:46:13'),
(14, 'C', 'C02', 'Bank Payment', '2017-01-07 05:46:13', '2017-01-07 05:46:13'),
(15, 'C', 'C03', 'Cash Receive', '2017-01-07 05:46:13', '2017-01-07 05:46:13'),
(16, 'C', 'C04', 'Bank Receive', '2017-01-07 05:46:13', '2017-01-07 05:46:13'),
(17, 'C', 'C05', 'Journal', '2017-01-07 05:46:13', '2017-01-07 05:46:13'),
(18, 'C', 'C20', 'Edit Voucher', '2017-01-07 05:46:13', '2017-01-07 05:46:13'),
(19, 'C', 'C21', 'Post Voucher', '2017-01-07 05:46:13', '2017-01-07 05:46:13'),
(20, 'R', 'R01', 'Daily Transaction List', '2017-08-23 06:00:00', '2017-08-23 06:00:00'),
(21, 'R', 'R02', 'View Print Voucher', '2018-01-30 00:20:48', '2018-01-30 00:20:48'),
(22, 'R', 'R03', 'Trial Balance', '2018-01-30 00:20:48', '2018-01-30 00:20:48'),
(23, 'R', 'R04', 'Trial Balance Group Head', '2018-01-30 00:20:48', '2018-01-30 00:20:48'),
(24, 'R', 'R05', 'View General Ledger', '2018-01-30 00:20:48', '2018-01-30 00:20:48'),
(25, 'S', 'S01', 'Add Financial Statement', '2018-01-30 00:20:48', '2018-01-30 00:20:48'),
(26, 'S', 'S02', 'Create Financial Statement', '2018-01-30 00:20:48', '2018-01-30 00:20:48'),
(27, 'S', 'S03', 'Print Financial Statement', '2018-01-30 00:20:48', '2018-01-30 00:20:48'),
(28, 'P', 'P01', 'Product Category', '2017-08-23 06:00:00', '2017-08-23 06:00:00'),
(29, 'P', 'P02', 'Product Brands', '2017-08-24 03:02:01', '2017-08-24 03:02:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `brands_compcode_foreign` (`compCode`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_compcode_name_unique` (`compCode`,`name`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `companies_compcode_unique` (`compCode`),
  ADD UNIQUE KEY `companies_email_unique` (`email`);

--
-- Indexes for table `godowns`
--
ALTER TABLE `godowns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `godowns_compcode_foreign` (`compCode`);

--
-- Indexes for table `models`
--
ALTER TABLE `models`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `models_compcode_modelno_unique` (`compCode`,`modelNo`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_compcode_productcode_unique` (`compCode`,`productCode`),
  ADD UNIQUE KEY `products_compcode_sku_unique` (`compCode`,`sku`),
  ADD KEY `products_user_id_foreign` (`user_id`),
  ADD KEY `FK_products_relationships` (`relationship_id`),
  ADD KEY `FK_products_brands` (`brand_id`),
  ADD KEY `FK_products_categories` (`category_id`),
  ADD KEY `FK_products_sub_categories` (`subcategory_id`),
  ADD KEY `FK_products_units` (`unit_name`),
  ADD KEY `FK_products_models` (`model_id`),
  ADD KEY `FK_products_taxgroup` (`taxgrp_id`),
  ADD KEY `FK_products_tax` (`tax_id`),
  ADD KEY `FK_products_godowns` (`godown_id`),
  ADD KEY `FK_products_racks` (`rack_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projects_compcode_projcode_unique` (`compCode`,`projCode`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_compcode_foreign` (`compCode`),
  ADD KEY `purchases_approver_foreign` (`approver`),
  ADD KEY `purchases_user_id_foreign` (`user_id`),
  ADD KEY `FK_purchase_relationships` (`relationship_id`);

--
-- Indexes for table `racks`
--
ALTER TABLE `racks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `racks_godown_id_foreign` (`godown_id`),
  ADD KEY `racks_compcode_foreign` (`compCode`);

--
-- Indexes for table `receives`
--
ALTER TABLE `receives`
  ADD KEY `receives_compcode_foreign` (`compCode`),
  ADD KEY `receives_approver_foreign` (`approver`),
  ADD KEY `receives_user_id_foreign` (`user_id`),
  ADD KEY `FK_purchase_no` (`purchase_id`),
  ADD KEY `FK_purchase_relationships` (`relationship_id`);

--
-- Indexes for table `relationships`
--
ALTER TABLE `relationships`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `relationships_compcode_name_unique` (`compCode`,`name`),
  ADD UNIQUE KEY `relationships_compcode_company_code_unique` (`compCode`,`company_code`),
  ADD KEY `relationships_user_id_foreign` (`user_id`);

--
-- Indexes for table `requisitions`
--
ALTER TABLE `requisitions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requisitions_compcode_foreign` (`compCode`),
  ADD KEY `requisitions_approver_foreign` (`approver`),
  ADD KEY `requisitions_user_id_foreign` (`user_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_compcode_foreign` (`compCode`),
  ADD KEY `sales_approver_foreign` (`approver`),
  ADD KEY `sales_user_id_foreign` (`user_id`),
  ADD KEY `FK_purchase_relationships` (`relationship_id`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sizes_compcode_size_unique` (`compCode`,`size`);

--
-- Indexes for table `stmt_data`
--
ALTER TABLE `stmt_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stmt_data_compcode_foreign` (`compCode`),
  ADD KEY `stmt_data_user_id_foreign` (`user_id`);

--
-- Indexes for table `stmt_lists`
--
ALTER TABLE `stmt_lists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stmt_lists_compcode_fileno_unique` (`compCode`,`fileNo`),
  ADD KEY `stmt_lists_user_id_foreign` (`user_id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sub_categories_compcode_name_unique` (`compCode`,`name`),
  ADD KEY `FK_sub_categories_categories` (`category_id`),
  ADD KEY `sub_categories_user_id_foreign` (`user_id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `taxes_compcode_tax_id_unique` (`compCode`,`tax_id`);

--
-- Indexes for table `tax_groups`
--
ALTER TABLE `tax_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tax_groups_compcode_taxgrp_code_unique` (`compCode`,`taxgrp_code`),
  ADD KEY `tax_groups_user_id_foreign` (`user_id`),
  ADD KEY `tax_groups_tax_id_foreign` (`tax_id`);

--
-- Indexes for table `temp_gl`
--
ALTER TABLE `temp_gl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `temp_gl_compcode_foreign` (`compCode`),
  ADD KEY `temp_gl_user_id_foreign` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_compcode_foreign` (`compCode`);

--
-- Indexes for table `trans_codes`
--
ALTER TABLE `trans_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trans_codes_compcode_foreign` (`compCode`);

--
-- Indexes for table `trans_products`
--
ALTER TABLE `trans_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trans_products_compcode_foreign` (`compCode`),
  ADD KEY `trans_products_product_id_foreign` (`product_id`),
  ADD KEY `FK_products_tax` (`tax_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `units_name_unique` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_compcode_foreign` (`compCode`);

--
-- Indexes for table `user_actions`
--
ALTER TABLE `user_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_actions_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_privileges`
--
ALTER TABLE `user_privileges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_privileges_compcode_foreign` (`compCode`),
  ADD KEY `user_privileges_email_foreign` (`email`);

--
-- Indexes for table `use_cases`
--
ALTER TABLE `use_cases`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `godowns`
--
ALTER TABLE `godowns`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `models`
--
ALTER TABLE `models`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `racks`
--
ALTER TABLE `racks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `relationships`
--
ALTER TABLE `relationships`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requisitions`
--
ALTER TABLE `requisitions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stmt_data`
--
ALTER TABLE `stmt_data`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stmt_lists`
--
ALTER TABLE `stmt_lists`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tax_groups`
--
ALTER TABLE `tax_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temp_gl`
--
ALTER TABLE `temp_gl`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trans_codes`
--
ALTER TABLE `trans_codes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `trans_products`
--
ALTER TABLE `trans_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_actions`
--
ALTER TABLE `user_actions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `user_privileges`
--
ALTER TABLE `user_privileges`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `use_cases`
--
ALTER TABLE `use_cases`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `brands`
--
ALTER TABLE `brands`
  ADD CONSTRAINT `brands_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`) ON DELETE CASCADE;

--
-- Constraints for table `godowns`
--
ALTER TABLE `godowns`
  ADD CONSTRAINT `godowns_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`) ON DELETE CASCADE;

--
-- Constraints for table `models`
--
ALTER TABLE `models`
  ADD CONSTRAINT `models_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_godown_id_foreign` FOREIGN KEY (`godown_id`) REFERENCES `godowns` (`id`),
  ADD CONSTRAINT `products_model_id_foreign` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`),
  ADD CONSTRAINT `products_rack_id_foreign` FOREIGN KEY (`rack_id`) REFERENCES `racks` (`id`),
  ADD CONSTRAINT `products_relationship_id_foreign` FOREIGN KEY (`relationship_id`) REFERENCES `relationships` (`id`),
  ADD CONSTRAINT `products_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `sub_categories` (`id`),
  ADD CONSTRAINT `products_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `taxes` (`id`),
  ADD CONSTRAINT `products_taxgrp_id_foreign` FOREIGN KEY (`taxgrp_id`) REFERENCES `tax_groups` (`id`),
  ADD CONSTRAINT `products_unit_name_foreign` FOREIGN KEY (`unit_name`) REFERENCES `units` (`name`) ON UPDATE CASCADE,
  ADD CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`) ON DELETE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_approver_foreign` FOREIGN KEY (`approver`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchases_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`),
  ADD CONSTRAINT `purchases_relationship_id_foreign` FOREIGN KEY (`relationship_id`) REFERENCES `relationships` (`id`),
  ADD CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `racks`
--
ALTER TABLE `racks`
  ADD CONSTRAINT `racks_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`) ON DELETE CASCADE,
  ADD CONSTRAINT `racks_godown_id_foreign` FOREIGN KEY (`godown_id`) REFERENCES `godowns` (`id`);

--
-- Constraints for table `receives`
--
ALTER TABLE `receives`
  ADD CONSTRAINT `receives_approver_foreign` FOREIGN KEY (`approver`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `receives_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`),
  ADD CONSTRAINT `receives_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`),
  ADD CONSTRAINT `receives_relationship_id_foreign` FOREIGN KEY (`relationship_id`) REFERENCES `relationships` (`id`),
  ADD CONSTRAINT `receives_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `relationships`
--
ALTER TABLE `relationships`
  ADD CONSTRAINT `relationships_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`) ON DELETE CASCADE,
  ADD CONSTRAINT `relationships_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `requisitions`
--
ALTER TABLE `requisitions`
  ADD CONSTRAINT `requisitions_approver_foreign` FOREIGN KEY (`approver`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `requisitions_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`),
  ADD CONSTRAINT `requisitions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_approver_foreign` FOREIGN KEY (`approver`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sales_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`),
  ADD CONSTRAINT `sales_relationship_id_foreign` FOREIGN KEY (`relationship_id`) REFERENCES `relationships` (`id`),
  ADD CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sizes`
--
ALTER TABLE `sizes`
  ADD CONSTRAINT `sizes_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`) ON DELETE CASCADE;

--
-- Constraints for table `stmt_data`
--
ALTER TABLE `stmt_data`
  ADD CONSTRAINT `stmt_data_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`),
  ADD CONSTRAINT `stmt_data_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `stmt_lists`
--
ALTER TABLE `stmt_lists`
  ADD CONSTRAINT `stmt_lists_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`),
  ADD CONSTRAINT `stmt_lists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD CONSTRAINT `FK_sub_categories_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sub_categories_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`) ON DELETE CASCADE,
  ADD CONSTRAINT `sub_categories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `taxes`
--
ALTER TABLE `taxes`
  ADD CONSTRAINT `taxes_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`) ON DELETE CASCADE;

--
-- Constraints for table `tax_groups`
--
ALTER TABLE `tax_groups`
  ADD CONSTRAINT `tax_groups_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`) ON DELETE CASCADE,
  ADD CONSTRAINT `tax_groups_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `taxes` (`id`),
  ADD CONSTRAINT `tax_groups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `temp_gl`
--
ALTER TABLE `temp_gl`
  ADD CONSTRAINT `temp_gl_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`) ON DELETE CASCADE,
  ADD CONSTRAINT `temp_gl_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`) ON DELETE CASCADE;

--
-- Constraints for table `trans_codes`
--
ALTER TABLE `trans_codes`
  ADD CONSTRAINT `trans_codes_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`) ON DELETE CASCADE;

--
-- Constraints for table `trans_products`
--
ALTER TABLE `trans_products`
  ADD CONSTRAINT `trans_products_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`),
  ADD CONSTRAINT `trans_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `trans_products_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `taxes` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`) ON DELETE CASCADE;

--
-- Constraints for table `user_actions`
--
ALTER TABLE `user_actions`
  ADD CONSTRAINT `user_actions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_privileges`
--
ALTER TABLE `user_privileges`
  ADD CONSTRAINT `user_privileges_compcode_foreign` FOREIGN KEY (`compCode`) REFERENCES `companies` (`compCode`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_privileges_email_foreign` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
