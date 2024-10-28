-- -------------------------------------------------------------
-- TablePlus 5.6.2(516)
--
-- https://tableplus.com/
--
-- Database: vertuoza_dvp
-- Generation Time: 2024-02-14 12:19:25.3870
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP TABLE IF EXISTS `collaborator`;
CREATE TABLE `collaborator` (
    `id` CHAR(36) NOT NULL DEFAULT (UUID()),
    `lastname` varchar(255) NOT NULL,
    `firstname` varchar(255) NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `password` TEXT NOT NULL,
    `admin` BOOLEAN NOT NULL DEFAULT FALSE,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE(username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS `tenant`;
CREATE TABLE `tenant` (
  `id` CHAR(36) NOT NULL DEFAULT (UUID()),
  `owner_id` CHAR(36) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tenant_owner_idx` (`owner_id`),
  CONSTRAINT `fk_tenant_owner` FOREIGN KEY (`owner_id`) REFERENCES `collaborator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  UNIQUE(name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS `unit_type`;
CREATE TABLE `unit_type` (
  `id` CHAR(36) NOT NULL DEFAULT (UUID()),
  `tenant_id` CHAR(36) DEFAULT NULL,  -- Changed to CHAR(36) to match the data type of `tenant.id`
  `label` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_unit_tenant_idx` (`tenant_id`),
  CONSTRAINT `fk_unit_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `collaborator_tenant`
(
    `collaborator_id` CHAR(36) NOT NULL,
    `tenant_id` CHAR(36) NOT NULL,
    KEY `fk_collaborator_tenant_tenant_id_idx` (`tenant_id`),
    KEY `fk_collaborator_tenant_collaborator_id_idx` (`collaborator_id`),
    CONSTRAINT `fk_collaborator_tenant_tenant_id` FOREIGN KEY (`tenant_id`) REFERENCES `tenant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_collaborator_tenant_collaborator_id` FOREIGN KEY (`collaborator_id`) REFERENCES `collaborator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
