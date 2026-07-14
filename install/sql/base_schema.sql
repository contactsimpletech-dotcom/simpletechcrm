-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: bhpsa_blackhawkmsp
-- ------------------------------------------------------
-- Server version	8.0.45-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bh_agent_runs`
--

DROP TABLE IF EXISTS `bh_agent_runs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_agent_runs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `agent_id` int NOT NULL,
  `ran_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('success','error','running') DEFAULT 'running',
  `ai_output` text,
  `action_result` text,
  `error_msg` text,
  `duration_ms` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agent_id` (`agent_id`),
  KEY `ran_at` (`ran_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_agents`
--

DROP TABLE IF EXISTS `bh_agents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_agents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `agent_type` varchar(50) DEFAULT 'custom',
  `schedule` varchar(100) DEFAULT '0 7 * * *',
  `prompt` text,
  `action_type` enum('email','create_ticket','both') DEFAULT 'email',
  `email_to` varchar(500) DEFAULT '',
  `ticket_subject` varchar(255) DEFAULT '',
  `ticket_priority` enum('low','medium','high','critical') DEFAULT 'medium',
  `ticket_department_id` int DEFAULT NULL,
  `ticket_assignee_id` int DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `last_run` datetime DEFAULT NULL,
  `next_run` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_agg_clicks`
--

DROP TABLE IF EXISTS `bh_agg_clicks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_agg_clicks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email_id` bigint unsigned NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `clicked_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `email_id` (`email_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_agg_contacts`
--

DROP TABLE IF EXISTS `bh_agg_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_agg_contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `domain` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `market` longtext COLLATE utf8mb4_unicode_ci,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_agg_emails`
--

DROP TABLE IF EXISTS `bh_agg_emails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_agg_emails` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contact_id` bigint unsigned NOT NULL,
  `subject` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `scheduled_at` datetime DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `opens` int NOT NULL DEFAULT '0',
  `clicks` int NOT NULL DEFAULT '0',
  `tracking_token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `status` (`status`),
  KEY `tracking_token` (`tracking_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_agg_queue`
--

DROP TABLE IF EXISTS `bh_agg_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_agg_queue` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email_id` bigint unsigned NOT NULL,
  `scheduled_at` datetime NOT NULL,
  `attempts` int NOT NULL DEFAULT '0',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `last_error` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `email_id` (`email_id`),
  KEY `status_scheduled` (`status`,`scheduled_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_agg_unsubscribes`
--

DROP TABLE IF EXISTS `bh_agg_unsubscribes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_agg_unsubscribes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unsubscribed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_ai_log`
--

DROP TABLE IF EXISTS `bh_ai_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_ai_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `provider` varchar(50) NOT NULL,
  `model` varchar(100) NOT NULL DEFAULT '',
  `action` varchar(100) NOT NULL DEFAULT '' COMMENT 'rewrite, summarise, generate, etc.',
  `source_module` varchar(50) NOT NULL DEFAULT '' COMMENT 'kb, tickets, invoices, etc.',
  `prompt_tokens` int NOT NULL DEFAULT '0',
  `output_tokens` int NOT NULL DEFAULT '0',
  `success` tinyint(1) NOT NULL DEFAULT '1',
  `error` text,
  `user_id` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `provider` (`provider`),
  KEY `created_at` (`created_at`),
  KEY `source_module` (`source_module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_ai_providers`
--

DROP TABLE IF EXISTS `bh_ai_providers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_ai_providers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `provider` varchar(50) NOT NULL,
  `label` varchar(100) NOT NULL DEFAULT '',
  `api_key` text NOT NULL,
  `model` varchar(100) NOT NULL DEFAULT '',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `extra` text COMMENT 'JSON: org_id, base_url, etc.',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `provider` (`provider`),
  KEY `is_default` (`is_default`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_assessment_leads`
--

DROP TABLE IF EXISTS `bh_assessment_leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_assessment_leads` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `assessment_id` int unsigned NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `company` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `answers` longtext,
  `overall_pct` decimal(5,2) NOT NULL DEFAULT '0.00',
  `grade` varchar(5) NOT NULL DEFAULT '',
  `risk_level` varchar(50) NOT NULL DEFAULT '',
  `lead_id` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `assessment_id` (`assessment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_assessment_questions`
--

DROP TABLE IF EXISTS `bh_assessment_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_assessment_questions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `section_id` int unsigned NOT NULL,
  `question` text NOT NULL,
  `weight` int NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_assessment_responses`
--

DROP TABLE IF EXISTS `bh_assessment_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_assessment_responses` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `assessment_id` int unsigned NOT NULL,
  `client_id` int unsigned NOT NULL,
  `business_name` varchar(255) NOT NULL DEFAULT '',
  `answers` longtext,
  `score_data` longtext,
  `overall_pct` decimal(5,2) NOT NULL DEFAULT '0.00',
  `grade` varchar(5) NOT NULL DEFAULT '',
  `risk_level` varchar(50) NOT NULL DEFAULT '',
  `taken_by` varchar(255) NOT NULL DEFAULT '',
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `assessment_id` (`assessment_id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_assessment_sections`
--

DROP TABLE IF EXISTS `bh_assessment_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_assessment_sections` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `assessment_id` int unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `sort_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `assessment_id` (`assessment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_assessment_settings`
--

DROP TABLE IF EXISTS `bh_assessment_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_assessment_settings` (
  `k` varchar(100) NOT NULL,
  `v` longtext,
  PRIMARY KEY (`k`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_assessments`
--

DROP TABLE IF EXISTS `bh_assessments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_assessments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `status` varchar(20) NOT NULL DEFAULT 'published',
  `allow_customer` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `allow_external` tinyint(1) NOT NULL DEFAULT '0',
  `external_pin` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_asset_types`
--

DROP TABLE IF EXISTS `bh_asset_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_asset_types` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_asset_warranty_notified`
--

DROP TABLE IF EXISTS `bh_asset_warranty_notified`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_asset_warranty_notified` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int unsigned NOT NULL,
  `notice_type` varchar(30) NOT NULL COMMENT 'expiring_30d | expired',
  `sent_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `asset_notice` (`asset_id`,`notice_type`),
  KEY `asset_id` (`asset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_assets`
--

DROP TABLE IF EXISTS `bh_assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_assets` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned DEFAULT NULL,
  `asset_type_id` int unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `model` varchar(255) DEFAULT NULL,
  `serial` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `purchase_price` decimal(10,2) DEFAULT NULL,
  `warranty_start` date DEFAULT NULL,
  `warranty_end` date DEFAULT NULL,
  `description` text,
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  KEY `asset_type_id` (`asset_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_audit_log`
--

DROP TABLE IF EXISTS `bh_audit_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_audit_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `action` varchar(128) NOT NULL,
  `target` varchar(128) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `meta` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`),
  KEY `idx_action` (`action`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_bills`
--

DROP TABLE IF EXISTS `bh_bills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_bills` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` int unsigned NOT NULL,
  `invoice_number` varchar(80) NOT NULL DEFAULT '',
  `description` text,
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `due_date` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `recurring` tinyint NOT NULL DEFAULT '0',
  `recur_freq` varchar(20) NOT NULL DEFAULT '',
  `recur_end` date DEFAULT NULL,
  `recur_next` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `vendor_id` (`vendor_id`),
  KEY `status` (`status`),
  KEY `due_date` (`due_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_bookkeeping_intakes`
--

DROP TABLE IF EXISTS `bh_bookkeeping_intakes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_bookkeeping_intakes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned DEFAULT NULL,
  `lead_id` int unsigned DEFAULT NULL,
  `company_name` varchar(255) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_phone` varchar(50) DEFAULT NULL,
  `business_structure` varchar(50) DEFAULT NULL,
  `tax_filings_current` varchar(20) DEFAULT NULL,
  `employees` int unsigned DEFAULT '0',
  `contractors` int unsigned DEFAULT '0',
  `customers` int unsigned DEFAULT '0',
  `vendors_monthly` int unsigned DEFAULT '0',
  `bills_monthly` int unsigned DEFAULT '0',
  `invoices_monthly` int unsigned DEFAULT '0',
  `sales_tax` tinyint(1) DEFAULT '0',
  `sales_tax_states` int unsigned DEFAULT '0',
  `sales_tax_freq` varchar(20) DEFAULT NULL,
  `env_fees` tinyint(1) DEFAULT '0',
  `env_fees_charge` tinyint(1) DEFAULT '0',
  `env_fees_pay` tinyint(1) DEFAULT '0',
  `annual_tools` int unsigned DEFAULT '0',
  `annual_customers` int unsigned DEFAULT '0',
  `resell_hardware` tinyint(1) DEFAULT '0',
  `hardware_volume` decimal(12,2) DEFAULT '0.00',
  `resell_cloud` tinyint(1) DEFAULT '0',
  `cloud_volume` decimal(12,2) DEFAULT '0.00',
  `multiyear_contracts` tinyint(1) DEFAULT '0',
  `project_billing` tinyint(1) DEFAULT '0',
  `payroll_employees` int unsigned DEFAULT '0',
  `payroll_freq` varchar(20) DEFAULT NULL,
  `tool_systems` int unsigned DEFAULT '0',
  `tech_split_time` tinyint(1) DEFAULT '0',
  `tech_roles` int unsigned DEFAULT '0',
  `outsource_noc` tinyint(1) DEFAULT '0',
  `multi_location` tinyint(1) DEFAULT '0',
  `location_count` int unsigned DEFAULT '1',
  `delivery_speed` varchar(20) DEFAULT 'standard',
  `months_behind` int unsigned DEFAULT '0',
  `notes` text,
  `monthly_quote` decimal(10,2) DEFAULT '0.00',
  `setup_quote` decimal(10,2) DEFAULT '0.00',
  `status` varchar(20) DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_client` (`client_id`),
  KEY `idx_status` (`status`),
  KEY `idx_email` (`contact_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_bookkeeping_snapshots`
--

DROP TABLE IF EXISTS `bh_bookkeeping_snapshots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_bookkeeping_snapshots` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `period_year` smallint unsigned NOT NULL,
  `period_month` tinyint unsigned NOT NULL,
  `total_revenue` decimal(14,2) DEFAULT '0.00',
  `cogs` decimal(14,2) DEFAULT '0.00',
  `payroll` decimal(14,2) DEFAULT '0.00',
  `overhead` decimal(14,2) DEFAULT '0.00',
  `ebitda` decimal(14,2) DEFAULT '0.00',
  `mrr` decimal(14,2) DEFAULT '0.00',
  `arr_run_rate` decimal(14,2) DEFAULT '0.00',
  `gross_margin` decimal(5,2) DEFAULT '0.00',
  `churn_rate` decimal(5,2) DEFAULT '0.00',
  `cash_on_hand` decimal(14,2) DEFAULT '0.00',
  `outstanding_ar` decimal(14,2) DEFAULT '0.00',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_period` (`client_id`,`period_year`,`period_month`),
  KEY `idx_client` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_books_snapshots`
--

DROP TABLE IF EXISTS `bh_books_snapshots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_books_snapshots` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `period_year` smallint unsigned NOT NULL,
  `period_month` tinyint unsigned NOT NULL,
  `total_revenue` decimal(14,2) DEFAULT '0.00',
  `cogs` decimal(14,2) DEFAULT '0.00',
  `payroll` decimal(14,2) DEFAULT '0.00',
  `overhead` decimal(14,2) DEFAULT '0.00',
  `ebitda` decimal(14,2) DEFAULT '0.00',
  `mrr` decimal(14,2) DEFAULT '0.00',
  `arr_run_rate` decimal(14,2) DEFAULT '0.00',
  `gross_margin` decimal(5,2) DEFAULT '0.00',
  `outstanding_ar` decimal(14,2) DEFAULT '0.00',
  `cash_on_hand` decimal(14,2) DEFAULT '0.00',
  `client_count` int unsigned DEFAULT '0',
  `closed_by` int unsigned DEFAULT NULL,
  `closed_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_period` (`period_year`,`period_month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_budget_categories`
--

DROP TABLE IF EXISTS `bh_budget_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_budget_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` enum('income','expense') NOT NULL DEFAULT 'expense',
  `color` varchar(7) NOT NULL DEFAULT '#6c757d',
  `sort_order` smallint NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_name_type` (`name`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_budget_entries`
--

DROP TABLE IF EXISTS `bh_budget_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_budget_entries` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int unsigned NOT NULL,
  `year` smallint NOT NULL,
  `month` tinyint NOT NULL,
  `planned_amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `notes` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cat_ym` (`category_id`,`year`,`month`),
  KEY `ym` (`year`,`month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_client_attachments`
--

DROP TABLE IF EXISTS `bh_client_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_client_attachments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `size` int unsigned NOT NULL DEFAULT '0',
  `uploaded_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_client_contacts`
--

DROP TABLE IF EXISTS `bh_client_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_client_contacts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `portal_access` tinyint(1) NOT NULL DEFAULT '0',
  `portal_password` varchar(255) DEFAULT NULL,
  `perm_invoices` tinyint(1) NOT NULL DEFAULT '0',
  `perm_estimates` tinyint(1) NOT NULL DEFAULT '0',
  `perm_contracts` tinyint(1) NOT NULL DEFAULT '0',
  `perm_proposals` tinyint(1) NOT NULL DEFAULT '0',
  `perm_support` tinyint(1) NOT NULL DEFAULT '1',
  `perm_projects` tinyint(1) NOT NULL DEFAULT '0',
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `perm_glue` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_client_group_map`
--

DROP TABLE IF EXISTS `bh_client_group_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_client_group_map` (
  `client_id` int unsigned NOT NULL,
  `group_id` int unsigned NOT NULL,
  PRIMARY KEY (`client_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_client_groups`
--

DROP TABLE IF EXISTS `bh_client_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_client_groups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_client_notes`
--

DROP TABLE IF EXISTS `bh_client_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_client_notes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `content` text NOT NULL,
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_clients`
--

DROP TABLE IF EXISTS `bh_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_clients` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `vat` varchar(100) DEFAULT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'USD',
  `contact_name` varchar(255) DEFAULT NULL,
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `glue_portal` varchar(10) NOT NULL DEFAULT 'none',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_coa_accounts`
--

DROP TABLE IF EXISTS `bh_coa_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_coa_accounts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'Expense',
  `subtype` varchar(100) NOT NULL DEFAULT '',
  `description` text,
  `balance` decimal(14,2) NOT NULL DEFAULT '0.00',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `tax_line` varchar(100) NOT NULL DEFAULT '',
  `expense_cats` varchar(500) NOT NULL DEFAULT '',
  `psa_linked` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_number` (`number`),
  KEY `type` (`type`),
  KEY `active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_coa_transactions`
--

DROP TABLE IF EXISTS `bh_coa_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_coa_transactions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int unsigned NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `debit` decimal(14,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(14,2) NOT NULL DEFAULT '0.00',
  `ref_type` varchar(50) NOT NULL DEFAULT '',
  `ref_id` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_contract_attachments`
--

DROP TABLE IF EXISTS `bh_contract_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_contract_attachments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `contract_id` int NOT NULL,
  `filename` varchar(255) NOT NULL DEFAULT '',
  `original_name` varchar(255) NOT NULL DEFAULT '',
  `mime_type` varchar(100) NOT NULL DEFAULT '',
  `file_size` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `contract_id` (`contract_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_contract_comments`
--

DROP TABLE IF EXISTS `bh_contract_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_contract_comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `contract_id` int NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `author` varchar(100) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `contract_id` (`contract_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_contract_groups`
--

DROP TABLE IF EXISTS `bh_contract_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_contract_groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_contract_history`
--

DROP TABLE IF EXISTS `bh_contract_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_contract_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `contract_id` int NOT NULL,
  `action` varchar(100) NOT NULL DEFAULT '',
  `by` varchar(100) NOT NULL DEFAULT '',
  `note` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `contract_id` (`contract_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_contract_notes`
--

DROP TABLE IF EXISTS `bh_contract_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_contract_notes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `contract_id` int NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `author` varchar(100) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `contract_id` (`contract_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_contract_templates`
--

DROP TABLE IF EXISTS `bh_contract_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_contract_templates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `group_name` varchar(100) NOT NULL DEFAULT '',
  `content` longtext NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `bh_contract_builders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `bh_contract_sections` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `builder_id` int unsigned NOT NULL DEFAULT 0,
  `name` varchar(200) NOT NULL,
  `title` varchar(200) DEFAULT '',
  `category` varchar(100) DEFAULT 'General',
  `content` longtext,
  `header_style` varchar(20) DEFAULT 'numbered',
  `header_color` varchar(20) DEFAULT '',
  `show_number` tinyint(1) NOT NULL DEFAULT 1,
  `require_ack` tinyint(1) NOT NULL DEFAULT 0,
  `shared` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_contracts`
--

DROP TABLE IF EXISTS `bh_contracts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_contracts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL DEFAULT '',
  `content` longtext NOT NULL,
  `type` varchar(100) NOT NULL DEFAULT '',
  `group_id` int NOT NULL DEFAULT '0',
  `value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `client_id` int NOT NULL DEFAULT '0',
  `hidden_from_customer` tinyint(1) NOT NULL DEFAULT '0',
  `signed` tinyint(1) NOT NULL DEFAULT '0',
  `signer_name` varchar(255) NOT NULL DEFAULT '',
  `signer_email` varchar(255) NOT NULL DEFAULT '',
  `signer_ip` varchar(45) NOT NULL DEFAULT '',
  `signed_date` datetime DEFAULT NULL,
  `signature` longtext NOT NULL,
  `sign_token` varchar(64) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  KEY `sign_token` (`sign_token`),
  KEY `signed` (`signed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_cron_log`
--

DROP TABLE IF EXISTS `bh_cron_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_cron_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `run_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `duration_ms` int unsigned NOT NULL DEFAULT '0',
  `tasks_run` smallint NOT NULL DEFAULT '0',
  `log` longtext,
  PRIMARY KEY (`id`),
  KEY `run_at` (`run_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_docs`
--

DROP TABLE IF EXISTS `bh_docs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_docs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `folder_id` int unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL DEFAULT 'Untitled Document',
  `content` longtext,
  `created_by` int unsigned DEFAULT NULL,
  `updated_by` int unsigned DEFAULT NULL,
  `visibility` enum('private','team','shared') NOT NULL DEFAULT 'team',
  `shared_token` varchar(64) DEFAULT NULL,
  `pinned` tinyint(1) NOT NULL DEFAULT '0',
  `word_count` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `folder_id` (`folder_id`),
  KEY `created_by` (`created_by`),
  KEY `visibility` (`visibility`),
  FULLTEXT KEY `ft_docs` (`title`,`content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_docs_folders`
--

DROP TABLE IF EXISTS `bh_docs_folders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_docs_folders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `parent_id` int unsigned DEFAULT NULL,
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sort_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_docs_shares`
--

DROP TABLE IF EXISTS `bh_docs_shares`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_docs_shares` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `doc_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `can_edit` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_share` (`doc_id`,`user_id`),
  KEY `doc_id` (`doc_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_download_categories`
--

DROP TABLE IF EXISTS `bh_download_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_download_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `color` varchar(20) NOT NULL DEFAULT '#6b7280',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_downloads`
--

DROP TABLE IF EXISTS `bh_downloads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_downloads` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `version` varchar(50) NOT NULL DEFAULT '',
  `file_url` varchar(1000) NOT NULL DEFAULT '',
  `gdrive_id` varchar(255) NOT NULL DEFAULT '',
  `category_id` int unsigned DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL DEFAULT '',
  `access` tinyint NOT NULL DEFAULT '0' COMMENT '0=staff only, 1=all portal clients, 2=specific clients',
  `allowed_clients` json DEFAULT NULL COMMENT 'array of client IDs when access=2',
  `download_count` int unsigned NOT NULL DEFAULT '0',
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `category_id` (`category_id`),
  KEY `access` (`access`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_dropsuite_cache`
--

DROP TABLE IF EXISTS `bh_dropsuite_cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_dropsuite_cache` (
  `cache_key` varchar(200) NOT NULL,
  `data` longtext,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`cache_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_dropsuite_org_map`
--

DROP TABLE IF EXISTS `bh_dropsuite_org_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_dropsuite_org_map` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `ds_org_id` varchar(100) NOT NULL,
  `ds_org_name` varchar(255) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_id` (`client_id`),
  KEY `ds_org_id` (`ds_org_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_dropsuite_settings`
--

DROP TABLE IF EXISTS `bh_dropsuite_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_dropsuite_settings` (
  `key` varchar(100) NOT NULL,
  `value` text,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_email_log`
--

DROP TABLE IF EXISTS `bh_email_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_email_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `message_id` varchar(255) DEFAULT NULL,
  `to` varchar(500) NOT NULL DEFAULT '',
  `from` varchar(255) NOT NULL DEFAULT '',
  `subject` varchar(500) NOT NULL DEFAULT '',
  `body` longtext,
  `headers` text,
  `attachments` text,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `response` text,
  `provider` varchar(50) NOT NULL DEFAULT '',
  `source` varchar(255) NOT NULL DEFAULT '',
  `resent_count` int unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_employees`
--

DROP TABLE IF EXISTS `bh_employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_employees` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `emp_status` enum('active','inactive','terminated') NOT NULL DEFAULT 'active',
  `employment_type` enum('full_time','part_time','contractor','intern') NOT NULL DEFAULT 'full_time',
  `department` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `manager_user_id` int unsigned DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `emergency_name` varchar(200) DEFAULT NULL,
  `emergency_phone` varchar(50) DEFAULT NULL,
  `emergency_rel` varchar(100) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `marital_status` varchar(20) DEFAULT NULL,
  `ssn_last4` varchar(4) DEFAULT NULL,
  `emerg_name` varchar(200) DEFAULT NULL,
  `emerg_phone` varchar(50) DEFAULT NULL,
  `emerg_relation` varchar(100) DEFAULT NULL,
  `filing_status` varchar(20) DEFAULT NULL,
  `pay_method` varchar(20) DEFAULT NULL,
  `account_last4` varchar(4) DEFAULT NULL,
  `pay_type` enum('hourly','salary') NOT NULL DEFAULT 'hourly',
  `hourly_rate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `salary` decimal(12,2) NOT NULL DEFAULT '0.00',
  `federal_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `state_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `allowances` int NOT NULL DEFAULT '0',
  `ss_exempt` tinyint(1) NOT NULL DEFAULT '0',
  `medicare_exempt` tinyint(1) NOT NULL DEFAULT '0',
  `bank_name` varchar(100) DEFAULT NULL,
  `routing_number` varchar(20) DEFAULT NULL,
  `account_number` varchar(30) DEFAULT NULL,
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_expense_categories`
--

DROP TABLE IF EXISTS `bh_expense_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_expense_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `parent_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_parent` (`name`,`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_expenses`
--

DROP TABLE IF EXISTS `bh_expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_expenses` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `category` varchar(100) NOT NULL DEFAULT '',
  `subcategory` varchar(100) NOT NULL DEFAULT '',
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(5,2) NOT NULL DEFAULT '0.00',
  `expense_date` date NOT NULL,
  `billable` tinyint(1) NOT NULL DEFAULT '0',
  `invoiced` tinyint(1) NOT NULL DEFAULT '0',
  `client_id` int unsigned DEFAULT NULL,
  `reference` varchar(255) NOT NULL DEFAULT '',
  `note` text,
  `recurring` varchar(20) NOT NULL DEFAULT '',
  `recurring_last` date DEFAULT NULL,
  `recurring_parent_id` int unsigned DEFAULT NULL,
  `vendor_id` int unsigned DEFAULT NULL,
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `project_id` int unsigned DEFAULT NULL,
  `ai_starred` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `expense_date` (`expense_date`),
  KEY `category` (`category`),
  KEY `client_id` (`client_id`),
  KEY `vendor_id` (`vendor_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_fitness_assignments`
--

DROP TABLE IF EXISTS `bh_fitness_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_fitness_assignments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `workout_id` int unsigned NOT NULL,
  `scheduled_date` date DEFAULT NULL,
  `completed_date` datetime DEFAULT NULL,
  `status` enum('assigned','in_progress','completed','skipped') COLLATE utf8mb4_unicode_ci DEFAULT 'assigned',
  `notes_trainer` text COLLATE utf8mb4_unicode_ci,
  `notes_client` text COLLATE utf8mb4_unicode_ci,
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  KEY `scheduled_date` (`scheduled_date`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_fitness_exercises`
--

DROP TABLE IF EXISTS `bh_fitness_exercises`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_fitness_exercises` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `muscle_group` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'full_body',
  `equipment` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bodyweight',
  `difficulty` enum('beginner','intermediate','advanced') COLLATE utf8mb4_unicode_ci DEFAULT 'beginner',
  `instructions` text COLLATE utf8mb4_unicode_ci,
  `video_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_custom` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `muscle_group` (`muscle_group`),
  KEY `equipment` (`equipment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_fitness_log`
--

DROP TABLE IF EXISTS `bh_fitness_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_fitness_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `assignment_id` int unsigned NOT NULL,
  `exercise_id` int unsigned NOT NULL,
  `set_number` int NOT NULL DEFAULT '1',
  `reps_done` int DEFAULT NULL,
  `weight_done_lb` decimal(6,2) DEFAULT NULL,
  `rpe` tinyint DEFAULT NULL,
  `logged_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `assignment_id` (`assignment_id`),
  KEY `exercise_id` (`exercise_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_fitness_metrics`
--

DROP TABLE IF EXISTS `bh_fitness_metrics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_fitness_metrics` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `contact_id` int unsigned DEFAULT NULL,
  `recorded_at` date NOT NULL,
  `weight_lb` decimal(6,2) DEFAULT NULL,
  `body_fat_pct` decimal(4,1) DEFAULT NULL,
  `chest_in` decimal(5,2) DEFAULT NULL,
  `waist_in` decimal(5,2) DEFAULT NULL,
  `hips_in` decimal(5,2) DEFAULT NULL,
  `arms_in` decimal(5,2) DEFAULT NULL,
  `thighs_in` decimal(5,2) DEFAULT NULL,
  `neck_in` decimal(5,2) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `client_recorded` (`client_id`,`recorded_at`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_fitness_packages`
--

DROP TABLE IF EXISTS `bh_fitness_packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_fitness_packages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `contact_id` int unsigned DEFAULT NULL,
  `package_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sessions_total` int NOT NULL DEFAULT '0',
  `sessions_used` int NOT NULL DEFAULT '0',
  `price_per_session` decimal(8,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `purchased_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` date DEFAULT NULL,
  `status` enum('active','expired','depleted','refunded') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `invoice_id` int unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `client_status` (`client_id`,`status`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_fitness_profiles`
--

DROP TABLE IF EXISTS `bh_fitness_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_fitness_profiles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `contact_id` int unsigned DEFAULT NULL,
  `height_in` decimal(5,2) DEFAULT NULL,
  `current_weight_lb` decimal(6,2) DEFAULT NULL,
  `goal_weight_lb` decimal(6,2) DEFAULT NULL,
  `body_fat_pct` decimal(4,1) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `sex` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity_level` enum('sedentary','light','moderate','active','very_active') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fitness_level` enum('beginner','intermediate','advanced') COLLATE utf8mb4_unicode_ci DEFAULT 'beginner',
  `goals` text COLLATE utf8mb4_unicode_ci,
  `conditions` text COLLATE utf8mb4_unicode_ci,
  `injuries` text COLLATE utf8mb4_unicode_ci,
  `medications` text COLLATE utf8mb4_unicode_ci,
  `parq_passed` tinyint(1) NOT NULL DEFAULT '0',
  `parq_signed_at` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contact_id_unique` (`contact_id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_fitness_sessions`
--

DROP TABLE IF EXISTS `bh_fitness_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_fitness_sessions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `contact_id` int unsigned DEFAULT NULL,
  `trainer_id` int unsigned DEFAULT NULL,
  `package_id` int unsigned DEFAULT NULL,
  `assignment_id` int unsigned DEFAULT NULL,
  `workout_id` int unsigned DEFAULT NULL,
  `scheduled_at` datetime NOT NULL,
  `duration_minutes` int NOT NULL DEFAULT '60',
  `status` enum('booked','completed','no_show','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'booked',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_scheduled` (`client_id`,`scheduled_at`),
  KEY `trainer_scheduled` (`trainer_id`,`scheduled_at`),
  KEY `status` (`status`),
  KEY `contact_id` (`contact_id`),
  KEY `workout_id` (`workout_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_fitness_workout_exercises`
--

DROP TABLE IF EXISTS `bh_fitness_workout_exercises`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_fitness_workout_exercises` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `workout_id` int unsigned NOT NULL,
  `exercise_id` int unsigned NOT NULL,
  `sort_order` smallint NOT NULL DEFAULT '0',
  `sets` int NOT NULL DEFAULT '3',
  `reps` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '10',
  `weight_lb` decimal(6,2) DEFAULT NULL,
  `rest_seconds` int NOT NULL DEFAULT '60',
  `duration_seconds` int DEFAULT NULL,
  `notes` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workout_id` (`workout_id`),
  KEY `exercise_id` (`exercise_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_fitness_workouts`
--

DROP TABLE IF EXISTS `bh_fitness_workouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_fitness_workouts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('strength','cardio','hiit','yoga','circuit','mobility','custom') COLLATE utf8mb4_unicode_ci DEFAULT 'strength',
  `estimated_minutes` int DEFAULT NULL,
  `difficulty` enum('beginner','intermediate','advanced') COLLATE utf8mb4_unicode_ci DEFAULT 'beginner',
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_attachments`
--

DROP TABLE IF EXISTS `bh_glue_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_attachments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(500) NOT NULL,
  `filesize` int unsigned DEFAULT '0',
  `mime_type` varchar(100) DEFAULT NULL,
  `uploaded_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_client` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_backups`
--

DROP TABLE IF EXISTS `bh_glue_backups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_backups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `software` varchar(100) NOT NULL DEFAULT '',
  `target` varchar(255) NOT NULL DEFAULT '',
  `schedule` varchar(100) NOT NULL DEFAULT '',
  `retention` varchar(100) NOT NULL DEFAULT '',
  `last_verified` date DEFAULT NULL,
  `restore_procedure` text,
  `alert_email` varchar(255) NOT NULL DEFAULT '',
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_circuits`
--

DROP TABLE IF EXISTS `bh_glue_circuits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_circuits` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `provider` varchar(100) NOT NULL DEFAULT '',
  `circuit_type` varchar(50) NOT NULL DEFAULT '',
  `circuit_id` varchar(100) NOT NULL DEFAULT '',
  `account_number` varchar(100) NOT NULL DEFAULT '',
  `bandwidth_down` varchar(50) NOT NULL DEFAULT '',
  `bandwidth_up` varchar(50) NOT NULL DEFAULT '',
  `static_ips` text,
  `support_phone` varchar(50) NOT NULL DEFAULT '',
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_contacts`
--

DROP TABLE IF EXISTS `bh_glue_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_contacts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `role` varchar(100) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `phone_alt` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `escalation_order` tinyint NOT NULL DEFAULT '0',
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_device_passwords`
--

DROP TABLE IF EXISTS `bh_glue_device_passwords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_device_passwords` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned DEFAULT NULL,
  `device_name` varchar(255) NOT NULL DEFAULT '',
  `device_type` varchar(100) NOT NULL DEFAULT 'other',
  `username` varchar(255) NOT NULL DEFAULT '',
  `password_enc` text NOT NULL,
  `url` varchar(500) NOT NULL DEFAULT '',
  `notes` text,
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_domains`
--

DROP TABLE IF EXISTS `bh_glue_domains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_domains` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `client_id` int unsigned DEFAULT NULL,
  `registrar` varchar(100) NOT NULL DEFAULT '',
  `expiry` date DEFAULT NULL,
  `nameservers` text,
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hosting_url` varchar(500) DEFAULT NULL,
  `hosting_username` varchar(255) DEFAULT NULL,
  `hosting_password_enc` text,
  `ping_enabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_favorites`
--

DROP TABLE IF EXISTS `bh_glue_favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_favorites` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `client_id` int unsigned NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_client` (`user_id`,`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_flex`
--

DROP TABLE IF EXISTS `bh_glue_flex`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_flex` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `client_id` int unsigned DEFAULT NULL,
  `type` varchar(100) NOT NULL DEFAULT '',
  `type_id` int unsigned DEFAULT NULL,
  `fields_json` longtext,
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_flex_types`
--

DROP TABLE IF EXISTS `bh_glue_flex_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_flex_types` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon` varchar(60) NOT NULL DEFAULT 'fa-table-list',
  `color` varchar(20) NOT NULL DEFAULT '#6366f1',
  `fields_json` longtext NOT NULL COMMENT 'JSON array of {key,label,type,required,options}',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_hardware`
--

DROP TABLE IF EXISTS `bh_glue_hardware`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_hardware` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  `make` varchar(100) NOT NULL DEFAULT '',
  `model` varchar(100) NOT NULL DEFAULT '',
  `serial` varchar(100) NOT NULL DEFAULT '',
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `location` varchar(255) NOT NULL DEFAULT '',
  `warranty_exp` date DEFAULT NULL,
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_license_files`
--

DROP TABLE IF EXISTS `bh_glue_license_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_license_files` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `license_id` int unsigned NOT NULL,
  `client_id` int unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(500) NOT NULL,
  `filesize` int unsigned DEFAULT '0',
  `mime_type` varchar(100) DEFAULT NULL,
  `notes` text,
  `uploaded_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_license` (`license_id`),
  KEY `idx_client` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_licenses`
--

DROP TABLE IF EXISTS `bh_glue_licenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_licenses` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `vendor` varchar(100) NOT NULL DEFAULT '',
  `product` varchar(255) NOT NULL DEFAULT '',
  `license_key` text,
  `seat_count` int NOT NULL DEFAULT '0',
  `renewal_date` date DEFAULT NULL,
  `cost_monthly` decimal(10,2) NOT NULL DEFAULT '0.00',
  `portal_url` varchar(500) NOT NULL DEFAULT '',
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_locations`
--

DROP TABLE IF EXISTS `bh_glue_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_locations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `client_id` int unsigned DEFAULT NULL,
  `address` varchar(500) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `state` varchar(100) NOT NULL DEFAULT '',
  `zip` varchar(20) NOT NULL DEFAULT '',
  `country` varchar(100) NOT NULL DEFAULT '',
  `primary_loc` tinyint NOT NULL DEFAULT '0',
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_maintenance`
--

DROP TABLE IF EXISTS `bh_glue_maintenance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_maintenance` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `window_name` varchar(255) NOT NULL DEFAULT '',
  `schedule` varchar(255) NOT NULL DEFAULT '',
  `blackout_start` varchar(50) NOT NULL DEFAULT '',
  `blackout_end` varchar(50) NOT NULL DEFAULT '',
  `contact_name` varchar(255) NOT NULL DEFAULT '',
  `contact_phone` varchar(50) NOT NULL DEFAULT '',
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_networks`
--

DROP TABLE IF EXISTS `bh_glue_networks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_networks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `client_id` int unsigned DEFAULT NULL,
  `subnet` varchar(50) NOT NULL DEFAULT '',
  `gateway` varchar(50) NOT NULL DEFAULT '',
  `vlan_id` varchar(20) NOT NULL DEFAULT '',
  `dns` varchar(200) NOT NULL DEFAULT '',
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_notes`
--

DROP TABLE IF EXISTS `bh_glue_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_notes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `is_encrypted` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_printers`
--

DROP TABLE IF EXISTS `bh_glue_printers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_printers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `make` varchar(100) NOT NULL DEFAULT '',
  `model` varchar(100) NOT NULL DEFAULT '',
  `serial` varchar(100) NOT NULL DEFAULT '',
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `location` varchar(255) NOT NULL DEFAULT '',
  `toner_bk` varchar(50) NOT NULL DEFAULT '',
  `toner_cy` varchar(50) NOT NULL DEFAULT '',
  `toner_mg` varchar(50) NOT NULL DEFAULT '',
  `toner_yw` varchar(50) NOT NULL DEFAULT '',
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_procedures`
--

DROP TABLE IF EXISTS `bh_glue_procedures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_procedures` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `scenario` varchar(255) NOT NULL DEFAULT '',
  `steps` longtext,
  `priority` tinyint NOT NULL DEFAULT '5',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_relations`
--

DROP TABLE IF EXISTS `bh_glue_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_relations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `src_type` varchar(60) NOT NULL,
  `src_id` int unsigned NOT NULL,
  `dst_type` varchar(60) NOT NULL,
  `dst_id` int unsigned NOT NULL,
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pair` (`src_type`,`src_id`,`dst_type`,`dst_id`),
  KEY `src` (`src_type`,`src_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_rmm`
--

DROP TABLE IF EXISTS `bh_glue_rmm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_rmm` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `platform` varchar(100) NOT NULL DEFAULT '',
  `site_name` varchar(255) NOT NULL DEFAULT '',
  `site_id` varchar(100) NOT NULL DEFAULT '',
  `agent_count` int NOT NULL DEFAULT '0',
  `console_url` varchar(500) NOT NULL DEFAULT '',
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_ssl`
--

DROP TABLE IF EXISTS `bh_glue_ssl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_ssl` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `client_id` int unsigned DEFAULT NULL,
  `issuer` varchar(100) NOT NULL DEFAULT '',
  `expiry` date DEFAULT NULL,
  `wildcard` tinyint NOT NULL DEFAULT '0',
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_vendor_accounts`
--

DROP TABLE IF EXISTS `bh_glue_vendor_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_vendor_accounts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `vendor_name` varchar(255) NOT NULL DEFAULT '',
  `service` varchar(255) NOT NULL DEFAULT '',
  `account_num` varchar(100) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '',
  `portal_url` varchar(500) NOT NULL DEFAULT '',
  `support_phone` varchar(50) NOT NULL DEFAULT '',
  `renewal_date` date DEFAULT NULL,
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_glue_voip`
--

DROP TABLE IF EXISTS `bh_glue_voip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_glue_voip` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `provider` varchar(100) NOT NULL DEFAULT '',
  `pbx_type` varchar(100) NOT NULL DEFAULT '',
  `pbx_ip` varchar(50) NOT NULL DEFAULT '',
  `sip_trunk` varchar(255) NOT NULL DEFAULT '',
  `did_numbers` text,
  `voicemail_pin` varchar(20) NOT NULL DEFAULT '',
  `admin_url` varchar(500) NOT NULL DEFAULT '',
  `support_phone` varchar(50) NOT NULL DEFAULT '',
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_help_messages`
--

DROP TABLE IF EXISTS `bh_help_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_help_messages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `user_name` varchar(120) NOT NULL DEFAULT '',
  `user_email` varchar(200) NOT NULL DEFAULT '',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  `status` enum('new','read','replied') NOT NULL DEFAULT 'new',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_huntress_agents`
--

DROP TABLE IF EXISTS `bh_huntress_agents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_huntress_agents` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `huntress_id` int unsigned NOT NULL DEFAULT '0',
  `org_id` int unsigned NOT NULL DEFAULT '0',
  `org_name` varchar(255) NOT NULL DEFAULT '',
  `hostname` varchar(255) NOT NULL DEFAULT '',
  `platform` varchar(50) NOT NULL DEFAULT '',
  `os_version` varchar(255) NOT NULL DEFAULT '',
  `agent_version` varchar(50) NOT NULL DEFAULT '',
  `status` varchar(50) NOT NULL DEFAULT '',
  `last_seen_at` datetime DEFAULT NULL,
  `raw_json` longtext,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `huntress_id` (`huntress_id`),
  KEY `org_id` (`org_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_huntress_incidents`
--

DROP TABLE IF EXISTS `bh_huntress_incidents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_huntress_incidents` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `huntress_id` int unsigned NOT NULL DEFAULT '0',
  `org_id` int unsigned NOT NULL DEFAULT '0',
  `org_name` varchar(255) NOT NULL DEFAULT '',
  `platform` varchar(50) NOT NULL DEFAULT '',
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `severity` varchar(50) NOT NULL DEFAULT '',
  `summary` text,
  `remediation` text,
  `sent_at` datetime DEFAULT NULL,
  `closed_at` datetime DEFAULT NULL,
  `raw_json` longtext,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `huntress_id` (`huntress_id`),
  KEY `status` (`status`),
  KEY `org_id` (`org_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_inbound_log`
--

DROP TABLE IF EXISTS `bh_inbound_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_inbound_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `date_received` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `recipient` varchar(255) DEFAULT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `subject` varchar(500) DEFAULT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'received',
  `ticket_id` int unsigned DEFAULT NULL,
  `reply_id` int unsigned DEFAULT NULL,
  `error_message` text,
  `message_id` varchar(500) DEFAULT NULL,
  `raw_size` int unsigned DEFAULT '0',
  `attachments` int unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `date_received` (`date_received`),
  KEY `status` (`status`),
  KEY `ticket_id` (`ticket_id`),
  KEY `message_id` (`message_id`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `bh_inventory_movements`
--

DROP TABLE IF EXISTS `bh_inventory_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_inventory_movements` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL,
  `warehouse_id` int unsigned NOT NULL DEFAULT '1',
  `qty` decimal(12,3) NOT NULL DEFAULT '0.000',
  `type` enum('po_receive','invoice_sale','adjustment','return','transfer_in','transfer_out') NOT NULL,
  `ref_type` varchar(40) DEFAULT NULL,
  `ref_id` int unsigned DEFAULT NULL,
  `cost_at_time` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `note` varchar(255) NOT NULL DEFAULT '',
  `user_id` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_product` (`product_id`),
  KEY `idx_type` (`type`),
  KEY `idx_ref` (`ref_type`,`ref_id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_inventory_stock`
--

DROP TABLE IF EXISTS `bh_inventory_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_inventory_stock` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL,
  `warehouse_id` int unsigned NOT NULL,
  `qty` decimal(12,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_product_warehouse` (`product_id`,`warehouse_id`),
  KEY `idx_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `bh_invoice_views`
--

DROP TABLE IF EXISTS `bh_invoice_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_invoice_views` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` int unsigned NOT NULL,
  `viewed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_invoices`
--

DROP TABLE IF EXISTS `bh_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_invoices` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned DEFAULT NULL,
  `invoice_number` varchar(20) NOT NULL DEFAULT '',
  `status` varchar(20) NOT NULL DEFAULT 'Draft',
  `due_date` date DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `paid` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(10) NOT NULL DEFAULT 'USD',
  `notes` text,
  `terms` text,
  `items` longtext,
  `payment_log` longtext,
  `pdf_path` varchar(500) DEFAULT NULL,
  `last_sent` datetime DEFAULT NULL,
  `created_by` int unsigned DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `recurring` tinyint(1) NOT NULL DEFAULT '0',
  `recurring_interval` varchar(20) DEFAULT NULL,
  `recurring_next_date` date DEFAULT NULL,
  `recurring_end_date` date DEFAULT NULL,
  `warn_5d_sent` date DEFAULT NULL,
  `warn_1d_sent` date DEFAULT NULL,
  `warn_7d_sent` date DEFAULT NULL,
  `warn_3d_sent` date DEFAULT NULL,
  `pay_token` varchar(64) DEFAULT NULL,
  `overdue_r1_sent` date DEFAULT NULL,
  `overdue_r2_sent` date DEFAULT NULL,
  `overdue_r3_sent` date DEFAULT NULL,
  `project_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_pay_token` (`pay_token`),
  KEY `client_id` (`client_id`),
  KEY `status` (`status`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_item_groups`
--

DROP TABLE IF EXISTS `bh_item_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_item_groups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_kb_article_clients`
--

DROP TABLE IF EXISTS `bh_kb_article_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_kb_article_clients` (
  `article_id` int unsigned NOT NULL,
  `client_id` int unsigned NOT NULL,
  PRIMARY KEY (`article_id`,`client_id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_kb_articles`
--

DROP TABLE IF EXISTS `bh_kb_articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_kb_articles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL DEFAULT '',
  `content` longtext,
  `excerpt` text,
  `status` varchar(20) NOT NULL DEFAULT 'published',
  `visibility` varchar(30) NOT NULL DEFAULT 'public',
  `group_id` int unsigned DEFAULT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  `show_customer` tinyint(1) NOT NULL DEFAULT '1',
  `views` int unsigned NOT NULL DEFAULT '0',
  `helpful` int unsigned NOT NULL DEFAULT '0',
  `not_helpful` int unsigned NOT NULL DEFAULT '0',
  `tags` varchar(500) NOT NULL DEFAULT '',
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shared_token` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `visibility` (`visibility`),
  KEY `group_id` (`group_id`),
  FULLTEXT KEY `ft_search` (`title`,`content`,`tags`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_kb_groups`
--

DROP TABLE IF EXISTS `bh_kb_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_kb_groups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` varchar(500) NOT NULL DEFAULT '',
  `display_order` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_lead_activity`
--

DROP TABLE IF EXISTS `bh_lead_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_lead_activity` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint unsigned NOT NULL,
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `type` varchar(50) NOT NULL DEFAULT 'note',
  `description` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lead_id` (`lead_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_lead_custom_fields`
--

DROP TABLE IF EXISTS `bh_lead_custom_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_lead_custom_fields` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `field_key` varchar(100) NOT NULL,
  `label` varchar(255) NOT NULL,
  `field_type` varchar(50) NOT NULL DEFAULT 'text',
  `options` text,
  `required` tinyint NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_lead_custom_values`
--

DROP TABLE IF EXISTS `bh_lead_custom_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_lead_custom_values` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint unsigned NOT NULL,
  `field_id` int unsigned NOT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lead_field` (`lead_id`,`field_id`),
  KEY `lead_id` (`lead_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_lead_notes`
--

DROP TABLE IF EXISTS `bh_lead_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_lead_notes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint unsigned NOT NULL,
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `note` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lead_id` (`lead_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_lead_sources`
--

DROP TABLE IF EXISTS `bh_lead_sources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_lead_sources` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_lead_statuses`
--

DROP TABLE IF EXISTS `bh_lead_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_lead_statuses` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `color` varchar(20) NOT NULL DEFAULT '#6366f1',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_default` tinyint NOT NULL DEFAULT '0',
  `is_won` tinyint NOT NULL DEFAULT '0',
  `is_lost` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_leads`
--

DROP TABLE IF EXISTS `bh_leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_leads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `company` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(100) NOT NULL DEFAULT '',
  `position` varchar(255) NOT NULL DEFAULT '',
  `website` varchar(500) NOT NULL DEFAULT '',
  `address` text,
  `city` varchar(100) NOT NULL DEFAULT '',
  `state` varchar(100) NOT NULL DEFAULT '',
  `country` varchar(100) NOT NULL DEFAULT '',
  `zip` varchar(20) NOT NULL DEFAULT '',
  `description` text,
  `lead_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status_id` int unsigned NOT NULL DEFAULT '1',
  `source_id` int unsigned NOT NULL DEFAULT '0',
  `assigned_to` int unsigned NOT NULL DEFAULT '0',
  `tags` varchar(500) NOT NULL DEFAULT '',
  `last_contact` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status_id` (`status_id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_meraki_cache`
--

DROP TABLE IF EXISTS `bh_meraki_cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_meraki_cache` (
  `cache_key` varchar(255) NOT NULL,
  `data` longtext NOT NULL,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`cache_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_mileage`
--

DROP TABLE IF EXISTS `bh_mileage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_mileage` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `trip_date` date NOT NULL,
  `start_location` varchar(255) NOT NULL DEFAULT '',
  `end_location` varchar(255) NOT NULL DEFAULT '',
  `miles` decimal(8,2) NOT NULL DEFAULT '0.00',
  `purpose` varchar(255) NOT NULL DEFAULT '',
  `vehicle` varchar(100) NOT NULL DEFAULT '',
  `rate` decimal(6,4) NOT NULL DEFAULT '0.6700',
  `reimbursement` decimal(10,2) NOT NULL DEFAULT '0.00',
  `notes` text,
  `client_id` int unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `trip_date` (`trip_date`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_moderation_log`
--

DROP TABLE IF EXISTS `bh_moderation_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_moderation_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` int unsigned DEFAULT NULL,
  `mime` varchar(100) DEFAULT NULL,
  `sha256` varchar(64) DEFAULT NULL,
  `blocked` tinyint(1) NOT NULL DEFAULT '0',
  `categories` text,
  `reasons` text,
  `context` varchar(100) DEFAULT NULL,
  `related_id` int unsigned DEFAULT NULL,
  `api_response_code` int DEFAULT NULL,
  `api_error` text,
  `checked_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `blocked_idx` (`blocked`,`checked_at`),
  KEY `context_idx` (`context`,`related_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_modules`
--

DROP TABLE IF EXISTS `bh_modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_modules` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `module_id` varchar(80) NOT NULL,
  `name` varchar(200) NOT NULL DEFAULT '',
  `description` text,
  `version` varchar(20) NOT NULL DEFAULT '1.0.0',
  `author` varchar(200) NOT NULL DEFAULT '',
  `active` tinyint NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `installed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `module_id` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_nl_bounces`
--

DROP TABLE IF EXISTS `bh_nl_bounces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_nl_bounces` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int unsigned DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `bounce_type` enum('hard','soft','complaint','unknown') NOT NULL DEFAULT 'unknown',
  `message` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_nl_campaigns`
--

DROP TABLE IF EXISTS `bh_nl_campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_nl_campaigns` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `preview_text` varchar(255) NOT NULL DEFAULT '',
  `from_name` varchar(150) NOT NULL DEFAULT '',
  `from_email` varchar(150) NOT NULL DEFAULT '',
  `reply_to` varchar(150) NOT NULL DEFAULT '',
  `body_html` longtext,
  `audience_type` enum('all','customers','employees','group','list') NOT NULL DEFAULT 'all',
  `audience_group` varchar(100) NOT NULL DEFAULT '',
  `status` enum('draft','scheduled','sending','sent','cancelled') NOT NULL DEFAULT 'draft',
  `is_template` tinyint(1) NOT NULL DEFAULT '0',
  `track_opens` tinyint(1) NOT NULL DEFAULT '1',
  `track_clicks` tinyint(1) NOT NULL DEFAULT '1',
  `scheduled_at` datetime DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `audience_list_id` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `is_template` (`is_template`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_nl_clicks`
--

DROP TABLE IF EXISTS `bh_nl_clicks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_nl_clicks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int unsigned NOT NULL,
  `recipient_id` bigint unsigned NOT NULL,
  `url` text NOT NULL,
  `clicked_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `campaign_id` (`campaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_nl_list_members`
--

DROP TABLE IF EXISTS `bh_nl_list_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_nl_list_members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `list_id` int unsigned NOT NULL,
  `email` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL DEFAULT '',
  `company` varchar(200) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_list_email` (`list_id`,`email`),
  KEY `list_id` (`list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_nl_lists`
--

DROP TABLE IF EXISTS `bh_nl_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_nl_lists` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(190) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `member_count` int unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_nl_recipients`
--

DROP TABLE IF EXISTS `bh_nl_recipients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_nl_recipients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int unsigned NOT NULL,
  `email` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL DEFAULT '',
  `recipient_type` enum('customer','employee','list') NOT NULL DEFAULT 'customer',
  `status` enum('pending','sent','failed','bounced','unsubscribed') NOT NULL DEFAULT 'pending',
  `sent_at` datetime DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `open_count` smallint unsigned NOT NULL DEFAULT '0',
  `clicked_at` datetime DEFAULT NULL,
  `click_count` smallint unsigned NOT NULL DEFAULT '0',
  `token` varchar(64) NOT NULL DEFAULT '',
  `company` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `campaign_id` (`campaign_id`),
  KEY `email` (`email`),
  KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_nl_settings`
--

DROP TABLE IF EXISTS `bh_nl_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_nl_settings` (
  `key` varchar(100) NOT NULL,
  `value` text,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_nl_suppressed`
--

DROP TABLE IF EXISTS `bh_nl_suppressed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_nl_suppressed` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL,
  `reason` enum('bounce','unsubscribe','manual','complaint') NOT NULL DEFAULT 'unsubscribe',
  `campaign_id` int unsigned DEFAULT NULL,
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_noc_integrations`
--

DROP TABLE IF EXISTS `bh_noc_integrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_noc_integrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `module_id` varchar(64) NOT NULL,
  `label` varchar(100) NOT NULL,
  `icon` varchar(100) NOT NULL DEFAULT 'fa-solid fa-plug',
  `color` varchar(20) NOT NULL DEFAULT '#6b7280',
  `url` varchar(255) NOT NULL DEFAULT '',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '50',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `module_id` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_password_audit`
--

DROP TABLE IF EXISTS `bh_password_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_password_audit` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `pwd_id` int unsigned NOT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `user_type` varchar(10) NOT NULL DEFAULT 'staff',
  `action` varchar(50) NOT NULL,
  `ip` varchar(45) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pwd_id` (`pwd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_password_categories`
--

DROP TABLE IF EXISTS `bh_password_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_password_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `color` varchar(20) NOT NULL DEFAULT '#6b7280',
  `icon` varchar(60) NOT NULL DEFAULT 'fa-folder',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_password_resets`
--

DROP TABLE IF EXISTS `bh_password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_password_resets` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `token` varchar(128) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_passwords`
--

DROP TABLE IF EXISTS `bh_passwords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_passwords` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '',
  `password_enc` text NOT NULL,
  `url` varchar(500) NOT NULL DEFAULT '',
  `notes` text,
  `client_id` int unsigned DEFAULT NULL,
  `category` varchar(100) NOT NULL DEFAULT '',
  `client_can_view` tinyint NOT NULL DEFAULT '0',
  `client_can_edit` tinyint NOT NULL DEFAULT '0',
  `hidden_from_client` tinyint NOT NULL DEFAULT '0',
  `created_by` int unsigned DEFAULT NULL,
  `created_by_type` varchar(10) NOT NULL DEFAULT 'staff' COMMENT 'staff or client',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  KEY `hidden_from_client` (`hidden_from_client`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_pay_items`
--

DROP TABLE IF EXISTS `bh_pay_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_pay_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `run_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `period_id` int unsigned NOT NULL,
  `item_type` varchar(20) NOT NULL DEFAULT '',
  `label` varchar(100) NOT NULL DEFAULT '',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_recurring` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `run_id` (`run_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_pay_periods`
--

DROP TABLE IF EXISTS `bh_pay_periods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_pay_periods` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `pay_date` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_pay_runs`
--

DROP TABLE IF EXISTS `bh_pay_runs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_pay_runs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `period_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `pay_type` varchar(20) NOT NULL DEFAULT 'hourly',
  `hours_worked` decimal(8,2) NOT NULL DEFAULT '0.00',
  `hourly_rate` decimal(8,2) NOT NULL DEFAULT '0.00',
  `salary_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `gross_pay` decimal(10,2) NOT NULL DEFAULT '0.00',
  `federal_tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `state_tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `social_security` decimal(10,2) NOT NULL DEFAULT '0.00',
  `medicare` decimal(10,2) NOT NULL DEFAULT '0.00',
  `other_deductions` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bonuses` decimal(10,2) NOT NULL DEFAULT '0.00',
  `reimbursements` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_deductions` decimal(10,2) NOT NULL DEFAULT '0.00',
  `net_pay` decimal(10,2) NOT NULL DEFAULT '0.00',
  `notes` text,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `period_user` (`period_id`,`user_id`),
  KEY `period_id` (`period_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_payments`
--

DROP TABLE IF EXISTS `bh_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `invoice_id` int NOT NULL,
  `amount` int NOT NULL COMMENT 'Amount in cents',
  `fee_amount` int NOT NULL DEFAULT '0' COMMENT 'Platform fee in cents',
  `stripe_checkout_session_id` varchar(255) DEFAULT NULL,
  `stripe_payment_intent_id` varchar(255) DEFAULT NULL,
  `stripe_account_id` varchar(255) DEFAULT NULL,
  `payer_email` varchar(255) DEFAULT NULL,
  `status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `paid_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_invoice` (`invoice_id`),
  KEY `idx_session` (`stripe_checkout_session_id`),
  KEY `idx_intent` (`stripe_payment_intent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_portal_resets`
--

DROP TABLE IF EXISTS `bh_portal_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_portal_resets` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `contact_id` int unsigned NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_portal_sessions`
--

DROP TABLE IF EXISTS `bh_portal_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_portal_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contact_id` int unsigned NOT NULL,
  `token` varchar(64) NOT NULL,
  `ip` varchar(45) NOT NULL DEFAULT '',
  `user_agent` varchar(255) NOT NULL DEFAULT '',
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_portal_solitaire_scores`
--

DROP TABLE IF EXISTS `bh_portal_solitaire_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_portal_solitaire_scores` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `contact_id` int unsigned NOT NULL DEFAULT '0',
  `player_name` varchar(120) NOT NULL DEFAULT '',
  `time_secs` int unsigned NOT NULL DEFAULT '0',
  `moves` int unsigned NOT NULL DEFAULT '0',
  `played_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_time` (`time_secs`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_portal_tetris_scores`
--

DROP TABLE IF EXISTS `bh_portal_tetris_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_portal_tetris_scores` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `contact_id` int unsigned NOT NULL DEFAULT '0',
  `player_name` varchar(120) NOT NULL DEFAULT '',
  `score` int unsigned NOT NULL DEFAULT '0',
  `level` tinyint unsigned NOT NULL DEFAULT '1',
  `played_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_score` (`score` DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_predefined_replies`
--

DROP TABLE IF EXISTS `bh_predefined_replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_predefined_replies` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_privacy_imported`
--

DROP TABLE IF EXISTS `bh_privacy_imported`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_privacy_imported` (
  `token` varchar(100) NOT NULL,
  `expense_id` int unsigned NOT NULL,
  `imported_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_privacy_settings`
--

DROP TABLE IF EXISTS `bh_privacy_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_privacy_settings` (
  `key` varchar(100) NOT NULL,
  `value` text,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_product_warehouses`
--

DROP TABLE IF EXISTS `bh_product_warehouses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_product_warehouses` (
  `product_id` int unsigned NOT NULL,
  `warehouse_id` int unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`warehouse_id`),
  KEY `idx_warehouse` (`warehouse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_products`
--

DROP TABLE IF EXISTS `bh_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(50) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `tax` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tax2` decimal(5,2) NOT NULL DEFAULT '0.00',
  `group_id` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cost` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `stock_qty` decimal(12,3) NOT NULL DEFAULT '0.000',
  `reorder_at` decimal(12,3) NOT NULL DEFAULT '0.000',
  `is_inventory` tinyint(1) NOT NULL DEFAULT '0',
  `vendor_id` int unsigned DEFAULT NULL,
  `income_account_id` int unsigned DEFAULT NULL,
  `cogs_account_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `idx_sku` (`sku`),
  KEY `idx_is_inventory` (`is_inventory`),
  KEY `idx_income_account` (`income_account_id`),
  KEY `idx_cogs_account` (`cogs_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_project_changes`
--

DROP TABLE IF EXISTS `bh_project_changes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_project_changes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `requested_by` varchar(255) DEFAULT NULL,
  `cost_impact` decimal(12,2) NOT NULL DEFAULT '0.00',
  `hours_impact` decimal(10,2) NOT NULL DEFAULT '0.00',
  `schedule_impact_days` smallint NOT NULL DEFAULT '0',
  `status` varchar(20) NOT NULL DEFAULT 'proposed',
  `approved_by` varchar(255) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_project_deliverables`
--

DROP TABLE IF EXISTS `bh_project_deliverables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_project_deliverables` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int unsigned NOT NULL,
  `milestone_id` int unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `due_date` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `approved_by` varchar(255) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  `sort_order` smallint NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_project_email_log`
--

DROP TABLE IF EXISTS `bh_project_email_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_project_email_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int unsigned DEFAULT NULL,
  `message_id` varchar(255) DEFAULT NULL,
  `direction` enum('in','out') NOT NULL DEFAULT 'in',
  `from_email` varchar(255) DEFAULT NULL,
  `to_email` varchar(255) DEFAULT NULL,
  `subject` varchar(500) DEFAULT NULL,
  `body` mediumtext,
  `status` varchar(50) DEFAULT 'processed',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_msgid` (`message_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_project_files`
--

DROP TABLE IF EXISTS `bh_project_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_project_files` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int unsigned NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `stored_name` varchar(255) NOT NULL,
  `mime` varchar(100) DEFAULT NULL,
  `size` int unsigned NOT NULL DEFAULT '0',
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `uploaded_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_project_members`
--

DROP TABLE IF EXISTS `bh_project_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_project_members` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `role` varchar(40) NOT NULL DEFAULT 'engineer',
  `hourly_rate` decimal(8,2) NOT NULL DEFAULT '0.00',
  `allocated_hours` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_proj_user` (`project_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_project_milestones`
--

DROP TABLE IF EXISTS `bh_project_milestones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_project_milestones` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `due_date` date DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `completed_by` int unsigned DEFAULT NULL,
  `sort_order` smallint NOT NULL DEFAULT '0',
  `billing_trigger` tinyint(1) NOT NULL DEFAULT '0',
  `billing_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `billing_invoice_id` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `due_date` (`due_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_project_notes`
--

DROP TABLE IF EXISTS `bh_project_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_project_notes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int unsigned NOT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `author` varchar(150) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT 'note',
  `note` text NOT NULL,
  `is_internal` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_project_risks`
--

DROP TABLE IF EXISTS `bh_project_risks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_project_risks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `impact` varchar(20) NOT NULL DEFAULT 'medium',
  `probability` varchar(20) NOT NULL DEFAULT 'medium',
  `mitigation` text,
  `owner_id` int unsigned DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'open',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_project_tasks`
--

DROP TABLE IF EXISTS `bh_project_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_project_tasks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int unsigned NOT NULL,
  `milestone_id` int unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `status` varchar(20) NOT NULL DEFAULT 'open',
  `priority` varchar(20) NOT NULL DEFAULT 'normal',
  `assigned_to` int unsigned DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `est_hours` decimal(8,2) NOT NULL DEFAULT '0.00',
  `sort_order` smallint NOT NULL DEFAULT '0',
  `task_link_id` int unsigned DEFAULT NULL,
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `milestone_id` (`milestone_id`),
  KEY `status` (`status`),
  KEY `assigned_to` (`assigned_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_project_template_items`
--

DROP TABLE IF EXISTS `bh_project_template_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_project_template_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `template_id` int unsigned NOT NULL,
  `parent_id` int unsigned DEFAULT NULL,
  `kind` varchar(20) NOT NULL DEFAULT 'task',
  `title` varchar(255) NOT NULL,
  `description` text,
  `offset_days` smallint NOT NULL DEFAULT '0',
  `est_hours` decimal(8,2) NOT NULL DEFAULT '0.00',
  `default_role` varchar(40) DEFAULT NULL,
  `billing_trigger` tinyint(1) NOT NULL DEFAULT '0',
  `billing_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `sort_order` smallint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `template_id` (`template_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_project_templates`
--

DROP TABLE IF EXISTS `bh_project_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_project_templates` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `type` varchar(40) NOT NULL DEFAULT 'implementation',
  `default_duration_days` smallint NOT NULL DEFAULT '30',
  `default_budget_hours` decimal(10,2) NOT NULL DEFAULT '0.00',
  `default_budget_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `default_hourly_rate` decimal(8,2) NOT NULL DEFAULT '150.00',
  `billing_type` varchar(20) NOT NULL DEFAULT 'fixed',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_project_time`
--

DROP TABLE IF EXISTS `bh_project_time`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_project_time` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int unsigned NOT NULL,
  `task_id` int unsigned DEFAULT NULL,
  `milestone_id` int unsigned DEFAULT NULL,
  `user_id` int unsigned NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `hours` decimal(8,2) NOT NULL DEFAULT '0.00',
  `note` text,
  `billable` tinyint(1) NOT NULL DEFAULT '1',
  `rate` decimal(8,2) NOT NULL DEFAULT '0.00',
  `invoice_id` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `user_id` (`user_id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_project_watchers`
--

DROP TABLE IF EXISTS `bh_project_watchers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_project_watchers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int unsigned NOT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `contact_id` int unsigned DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_projects`
--

DROP TABLE IF EXISTS `bh_projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_projects` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL,
  `description` text,
  `client_id` int unsigned DEFAULT NULL,
  `pm_id` int unsigned DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'planning',
  `priority` varchar(20) NOT NULL DEFAULT 'normal',
  `health` varchar(10) NOT NULL DEFAULT 'green',
  `type` varchar(40) NOT NULL DEFAULT 'implementation',
  `billing_type` varchar(20) NOT NULL DEFAULT 'fixed',
  `budget_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `budget_hours` decimal(10,2) NOT NULL DEFAULT '0.00',
  `hourly_rate` decimal(8,2) NOT NULL DEFAULT '0.00',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `actual_start` date DEFAULT NULL,
  `actual_end` date DEFAULT NULL,
  `progress` tinyint unsigned NOT NULL DEFAULT '0',
  `tags` varchar(500) DEFAULT NULL,
  `view_hash` varchar(32) DEFAULT NULL,
  `email_key` varchar(32) DEFAULT NULL,
  `portal_visible` tinyint(1) NOT NULL DEFAULT '1',
  `weekly_report` tinyint(1) NOT NULL DEFAULT '0',
  `report_day` tinyint NOT NULL DEFAULT '1',
  `last_report_sent` date DEFAULT NULL,
  `notes` text,
  `department_id` int unsigned DEFAULT NULL,
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `closed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_code` (`code`),
  UNIQUE KEY `uq_view_hash` (`view_hash`),
  UNIQUE KEY `uq_email_key` (`email_key`),
  KEY `client_id` (`client_id`),
  KEY `pm_id` (`pm_id`),
  KEY `status` (`status`),
  KEY `end_date` (`end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_proposal_settings`
--

DROP TABLE IF EXISTS `bh_proposal_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_proposal_settings` (
  `k` varchar(100) NOT NULL,
  `v` text,
  PRIMARY KEY (`k`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_proposals`
--

DROP TABLE IF EXISTS `bh_proposals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_proposals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL DEFAULT 'proposal',
  `title` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(30) NOT NULL DEFAULT 'Draft',
  `client_id` int unsigned NOT NULL DEFAULT '0',
  `client_name` varchar(255) NOT NULL DEFAULT '',
  `client_email` varchar(255) NOT NULL DEFAULT '',
  `currency` varchar(10) NOT NULL DEFAULT 'USD',
  `expiry_date` date DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `intro` text,
  `notes` text,
  `terms` text,
  `items` longtext,
  `last_sent` varchar(100) NOT NULL DEFAULT '',
  `converted_to` int unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `type_status` (`type`,`status`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_purchase_order_items`
--

DROP TABLE IF EXISTS `bh_purchase_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_purchase_order_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `po_id` int unsigned NOT NULL,
  `product_id` int unsigned DEFAULT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `qty_ordered` decimal(12,3) NOT NULL DEFAULT '0.000',
  `qty_received` decimal(12,3) NOT NULL DEFAULT '0.000',
  `cost` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_po` (`po_id`),
  KEY `idx_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_purchase_orders`
--

DROP TABLE IF EXISTS `bh_purchase_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_purchase_orders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `po_number` varchar(40) NOT NULL DEFAULT '',
  `vendor_id` int unsigned DEFAULT NULL,
  `warehouse_id` int unsigned NOT NULL DEFAULT '1',
  `status` enum('draft','sent','partial','received','cancelled') NOT NULL DEFAULT 'draft',
  `order_date` date DEFAULT NULL,
  `expected_date` date DEFAULT NULL,
  `received_date` date DEFAULT NULL,
  `subtotal` decimal(14,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(14,2) NOT NULL DEFAULT '0.00',
  `shipping` decimal(14,2) NOT NULL DEFAULT '0.00',
  `total` decimal(14,2) NOT NULL DEFAULT '0.00',
  `notes` text,
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_vendor` (`vendor_id`),
  KEY `idx_status` (`status`),
  KEY `idx_dates` (`order_date`,`received_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_s1_incidents`
--

DROP TABLE IF EXISTS `bh_s1_incidents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_s1_incidents` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `s1_threat_id` varchar(100) NOT NULL DEFAULT '',
  `threat_name` varchar(255) NOT NULL DEFAULT '',
  `endpoint_name` varchar(255) NOT NULL DEFAULT '',
  `endpoint_ip` varchar(64) NOT NULL DEFAULT '',
  `site_name` varchar(255) NOT NULL DEFAULT '',
  `os_name` varchar(100) NOT NULL DEFAULT '',
  `incident_status` varchar(50) NOT NULL DEFAULT 'UNRESOLVED',
  `classification` varchar(100) NOT NULL DEFAULT '',
  `file_path` text,
  `sha1` varchar(64) NOT NULL DEFAULT '',
  `detected_at` datetime DEFAULT NULL,
  `resolved_at` datetime DEFAULT NULL,
  `raw_json` longtext,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `s1_threat_id` (`s1_threat_id`),
  KEY `incident_status` (`incident_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_settings`
--

DROP TABLE IF EXISTS `bh_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_settings` (
  `key` varchar(100) NOT NULL,
  `value` text,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_site_help_log`
--

DROP TABLE IF EXISTS `bh_site_help_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_site_help_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `content` mediumtext,
  `session_id` varchar(64) NOT NULL DEFAULT '',
  `tokens_in` int unsigned NOT NULL DEFAULT '0',
  `tokens_out` int unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_site_help_proposals`
--

DROP TABLE IF EXISTS `bh_site_help_proposals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_site_help_proposals` (
  `id` varchar(32) NOT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `session_id` varchar(64) NOT NULL DEFAULT '',
  `tool` varchar(100) NOT NULL DEFAULT '',
  `args_json` mediumtext,
  `preview` mediumtext,
  `batch_key` varchar(64) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `batch_key` (`batch_key`),
  KEY `expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_site_help_tool_log`
--

DROP TABLE IF EXISTS `bh_site_help_tool_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_site_help_tool_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `session_id` varchar(64) NOT NULL DEFAULT '',
  `tool` varchar(100) NOT NULL DEFAULT '',
  `args_json` text,
  `result_json` mediumtext,
  `status` varchar(20) NOT NULL DEFAULT 'ok',
  `error` text,
  `ms` int unsigned NOT NULL DEFAULT '0',
  `ip` varchar(45) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `tool` (`tool`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_solitaire_scores`
--

DROP TABLE IF EXISTS `bh_solitaire_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_solitaire_scores` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `user_name` varchar(100) NOT NULL DEFAULT '',
  `time_secs` smallint NOT NULL DEFAULT '0',
  `moves` smallint NOT NULL DEFAULT '0',
  `played_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `time_secs` (`time_secs`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_tacticalrmm_map`
--

DROP TABLE IF EXISTS `bh_tacticalrmm_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_tacticalrmm_map` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `trmm_site_id` int NOT NULL,
  `trmm_site_name` varchar(255) NOT NULL DEFAULT '',
  `trmm_client_name` varchar(255) NOT NULL DEFAULT '',
  `bh_client_id` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_site` (`trmm_site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_task_assignees`
--

DROP TABLE IF EXISTS `bh_task_assignees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_task_assignees` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_user` (`task_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_task_checklist`
--

DROP TABLE IF EXISTS `bh_task_checklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_task_checklist` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int unsigned NOT NULL,
  `description` text NOT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT '0',
  `assigned_to` int unsigned DEFAULT NULL,
  `sort_order` smallint NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_task_notes`
--

DROP TABLE IF EXISTS `bh_task_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_task_notes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int unsigned NOT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `author` varchar(100) NOT NULL DEFAULT '',
  `note` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_tasks`
--

DROP TABLE IF EXISTS `bh_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_tasks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `status` varchar(20) NOT NULL DEFAULT 'open',
  `priority` varchar(20) NOT NULL DEFAULT 'normal',
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `client_id` int unsigned DEFAULT NULL,
  `parent_id` int unsigned DEFAULT NULL,
  `recurring` varchar(20) NOT NULL DEFAULT 'none',
  `next_due` date DEFAULT NULL,
  `nag_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `nag_interval_min` smallint NOT NULL DEFAULT '30',
  `nag_start_at` datetime DEFAULT NULL,
  `last_nag_sent` datetime DEFAULT NULL,
  `billable` tinyint(1) NOT NULL DEFAULT '0',
  `hourly_rate` decimal(8,2) NOT NULL DEFAULT '0.00',
  `is_private` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `closed_at` datetime DEFAULT NULL,
  `project_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `due_date` (`due_date`),
  KEY `project_id` (`project_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_tax_1099s`
--

DROP TABLE IF EXISTS `bh_tax_1099s`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_tax_1099s` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `direction` varchar(20) NOT NULL DEFAULT 'received',
  `payer_name` varchar(255) NOT NULL DEFAULT '',
  `payer_ein` varchar(20) NOT NULL DEFAULT '',
  `recipient_name` varchar(255) NOT NULL DEFAULT '',
  `recipient_ein` varchar(20) NOT NULL DEFAULT '',
  `form_type` varchar(20) NOT NULL DEFAULT '1099-NEC',
  `amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `tax_withheld` decimal(12,2) NOT NULL DEFAULT '0.00',
  `received_date` date DEFAULT NULL,
  `year` smallint NOT NULL DEFAULT '0',
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `yr` (`year`),
  KEY `direction` (`direction`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_tax_manual`
--

DROP TABLE IF EXISTS `bh_tax_manual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_tax_manual` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `entry_type` varchar(20) NOT NULL DEFAULT 'income',
  `amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `description` varchar(255) NOT NULL DEFAULT '',
  `entry_date` date DEFAULT NULL,
  `category` varchar(100) NOT NULL DEFAULT '',
  `year` smallint NOT NULL DEFAULT '0',
  `month` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `yr_type` (`year`,`entry_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_tax_payments`
--

DROP TABLE IF EXISTS `bh_tax_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_tax_payments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `tax_type` varchar(30) NOT NULL DEFAULT 'federal',
  `period_type` varchar(20) NOT NULL DEFAULT 'quarterly',
  `period_label` varchar(50) NOT NULL DEFAULT '',
  `year` smallint NOT NULL DEFAULT '0',
  `quarter` tinyint NOT NULL DEFAULT '0',
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `paid_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'due',
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tax_type` (`tax_type`),
  KEY `yr` (`year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_tax_sales`
--

DROP TABLE IF EXISTS `bh_tax_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_tax_sales` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `period_label` varchar(50) NOT NULL DEFAULT '',
  `year` smallint NOT NULL DEFAULT '0',
  `month` tinyint NOT NULL DEFAULT '0',
  `quarter` tinyint NOT NULL DEFAULT '0',
  `taxable_sales` decimal(14,2) NOT NULL DEFAULT '0.00',
  `exempt_sales` decimal(14,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(7,4) NOT NULL DEFAULT '0.0000',
  `tax_collected` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax_remitted` decimal(12,2) NOT NULL DEFAULT '0.00',
  `source` varchar(30) NOT NULL DEFAULT 'manual',
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `yr_mo` (`year`,`month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_tetris_scores`
--

DROP TABLE IF EXISTS `bh_tetris_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_tetris_scores` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `user_name` varchar(100) NOT NULL DEFAULT '',
  `score` int NOT NULL DEFAULT '0',
  `level` tinyint NOT NULL DEFAULT '1',
  `lines` smallint NOT NULL DEFAULT '0',
  `played_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `score` (`score`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_ticket_attachments`
--

DROP TABLE IF EXISTS `bh_ticket_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_ticket_attachments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` int unsigned NOT NULL,
  `reply_id` int unsigned DEFAULT NULL,
  `original_name` varchar(255) NOT NULL,
  `stored_name` varchar(255) NOT NULL,
  `mime` varchar(100) DEFAULT NULL,
  `size` int unsigned NOT NULL DEFAULT '0',
  `uploaded_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `reply_id` (`reply_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_ticket_decrypt_sessions`
--

DROP TABLE IF EXISTS `bh_ticket_decrypt_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_ticket_decrypt_sessions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(64) NOT NULL,
  `ticket_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `method` varchar(20) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `ticket_user` (`ticket_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_ticket_departments`
--

DROP TABLE IF EXISTS `bh_ticket_departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_ticket_departments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `reply_to_email` varchar(255) DEFAULT NULL,
  `slug` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `imap_host` varchar(255) DEFAULT NULL,
  `imap_port` smallint DEFAULT '993',
  `imap_enc` varchar(10) DEFAULT 'ssl',
  `imap_user` varchar(255) DEFAULT NULL,
  `imap_pass` varchar(500) DEFAULT NULL,
  `imap_folder` varchar(100) DEFAULT 'INBOX',
  `last_fetched_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reject_unknown` tinyint(1) NOT NULL DEFAULT '0',
  `reject_message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_ticket_email_log`
--

DROP TABLE IF EXISTS `bh_ticket_email_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_ticket_email_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `message_id` varchar(255) DEFAULT NULL,
  `ticket_id` int unsigned DEFAULT NULL,
  `direction` enum('in','out') NOT NULL DEFAULT 'in',
  `from_email` varchar(255) DEFAULT NULL,
  `subject` varchar(500) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'processed',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `message_id` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_ticket_replies`
--

DROP TABLE IF EXISTS `bh_ticket_replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_ticket_replies` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` int unsigned NOT NULL,
  `body` text NOT NULL,
  `type` enum('reply','note') NOT NULL DEFAULT 'reply',
  `author_id` int unsigned DEFAULT NULL,
  `author_name` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_tickets`
--

DROP TABLE IF EXISTS `bh_tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_tickets` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Open',
  `priority` varchar(50) NOT NULL DEFAULT 'Medium',
  `department_id` int unsigned DEFAULT NULL,
  `client_id` int unsigned DEFAULT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `assigned_to` int unsigned DEFAULT NULL,
  `cc` text,
  `tags` varchar(500) DEFAULT NULL,
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_reply_at` datetime DEFAULT NULL,
  `is_encrypted` tinyint(1) NOT NULL DEFAULT '0',
  `encrypt_method` varchar(20) DEFAULT NULL,
  `view_hash` varchar(32) DEFAULT NULL,
  `project_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_view_hash` (`view_hash`),
  KEY `status` (`status`),
  KEY `client_id` (`client_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_timesheets`
--

DROP TABLE IF EXISTS `bh_timesheets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_timesheets` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `client_id` int unsigned DEFAULT NULL,
  `billable` tinyint(1) NOT NULL DEFAULT '0',
  `product_id` int unsigned DEFAULT NULL,
  `invoice_id` int unsigned DEFAULT NULL,
  `related_type` varchar(20) NOT NULL DEFAULT 'general',
  `related_id` int unsigned NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `hours` decimal(6,2) NOT NULL DEFAULT '0.00',
  `note` text,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `related_id` (`related_id`),
  KEY `start_time` (`start_time`),
  KEY `billable_client` (`billable`,`client_id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_tuesday_activity`
--

DROP TABLE IF EXISTS `bh_tuesday_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_tuesday_activity` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `action` varchar(200) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_task` (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_tuesday_boards`
--

DROP TABLE IF EXISTS `bh_tuesday_boards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_tuesday_boards` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `color` varchar(20) DEFAULT '#6366f1',
  `client_id` int unsigned DEFAULT NULL,
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_tuesday_cell_values`
--

DROP TABLE IF EXISTS `bh_tuesday_cell_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_tuesday_cell_values` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int unsigned NOT NULL,
  `column_id` int unsigned NOT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_task_col` (`task_id`,`column_id`),
  KEY `idx_task` (`task_id`),
  KEY `idx_col` (`column_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_tuesday_columns`
--

DROP TABLE IF EXISTS `bh_tuesday_columns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_tuesday_columns` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `board_id` int unsigned NOT NULL,
  `type` varchar(30) NOT NULL,
  `label` varchar(100) NOT NULL,
  `position` int DEFAULT '0',
  `config` text,
  PRIMARY KEY (`id`),
  KEY `idx_board` (`board_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_tuesday_comments`
--

DROP TABLE IF EXISTS `bh_tuesday_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_tuesday_comments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `body` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_task` (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_tuesday_groups`
--

DROP TABLE IF EXISTS `bh_tuesday_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_tuesday_groups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `board_id` int unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `color` varchar(20) DEFAULT '#6366f1',
  `position` int DEFAULT '0',
  `collapsed` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_board` (`board_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_tuesday_notifications`
--

DROP TABLE IF EXISTS `bh_tuesday_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_tuesday_notifications` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `task_id` int unsigned NOT NULL,
  `board_id` int unsigned NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'assigned',
  `message` varchar(500) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_task` (`task_id`),
  KEY `idx_unread` (`user_id`,`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_tuesday_statuses`
--

DROP TABLE IF EXISTS `bh_tuesday_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_tuesday_statuses` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `board_id` int unsigned NOT NULL,
  `label` varchar(100) NOT NULL,
  `color` varchar(20) NOT NULL DEFAULT '#9ca3af',
  `position` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_board` (`board_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_tuesday_tasks`
--

DROP TABLE IF EXISTS `bh_tuesday_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_tuesday_tasks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `board_id` int unsigned NOT NULL,
  `group_id` int unsigned NOT NULL,
  `parent_id` int unsigned DEFAULT NULL,
  `name` varchar(500) NOT NULL,
  `owner_id` int unsigned DEFAULT NULL,
  `status_id` int unsigned DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `client_id` int unsigned DEFAULT NULL,
  `notes` text,
  `position` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_group` (`group_id`),
  KEY `idx_board` (`board_id`),
  KEY `idx_parent` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_tw_events`
--

DROP TABLE IF EXISTS `bh_tw_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_tw_events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_tw_players`
--

DROP TABLE IF EXISTS `bh_tw_players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_tw_players` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `pilot_name` varchar(64) NOT NULL,
  `sector` int NOT NULL DEFAULT '1',
  `credits` int NOT NULL DEFAULT '1000',
  `turns` int NOT NULL DEFAULT '20',
  `max_turns` int NOT NULL DEFAULT '20',
  `cargo_ore` int NOT NULL DEFAULT '0',
  `cargo_org` int NOT NULL DEFAULT '0',
  `cargo_eq` int NOT NULL DEFAULT '0',
  `holds` int NOT NULL DEFAULT '5',
  `fighters` int NOT NULL DEFAULT '5',
  `shields` int NOT NULL DEFAULT '10',
  `score` int NOT NULL DEFAULT '0',
  `last_seen` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `turns_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_user_meta`
--

DROP TABLE IF EXISTS `bh_user_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_user_meta` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `meta_key` varchar(100) NOT NULL,
  `meta_value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_meta` (`user_id`,`meta_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_user_permissions`
--

DROP TABLE IF EXISTS `bh_user_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_user_permissions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `module_id` varchar(100) NOT NULL,
  `granted` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_module` (`user_id`,`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_users`
--

DROP TABLE IF EXISTS `bh_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','contractor','client') NOT NULL DEFAULT 'staff',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `avatar` varchar(500) NOT NULL DEFAULT '',
  `mfa_secret` varchar(64) DEFAULT NULL,
  `client_id` int unsigned DEFAULT NULL,
  `active` tinyint NOT NULL DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `totp_secret` varchar(64) DEFAULT NULL,
  `totp_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `totp_verified_at` datetime DEFAULT NULL,
  `login_fails` tinyint unsigned NOT NULL DEFAULT '0',
  `locked_until` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_vendors`
--

DROP TABLE IF EXISTS `bh_vendors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_vendors` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `category` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(80) NOT NULL DEFAULT '',
  `website` varchar(255) NOT NULL DEFAULT '',
  `address` text,
  `city` varchar(100) NOT NULL DEFAULT '',
  `state` varchar(50) NOT NULL DEFAULT '',
  `zip` varchar(20) NOT NULL DEFAULT '',
  `country` varchar(100) NOT NULL DEFAULT 'United States',
  `payment_terms` varchar(50) NOT NULL DEFAULT '',
  `billing_contact` varchar(255) NOT NULL DEFAULT '',
  `billing_email` varchar(255) NOT NULL DEFAULT '',
  `tax_id` varchar(50) NOT NULL DEFAULT '',
  `notes` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_warehouses`
--

DROP TABLE IF EXISTS `bh_warehouses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_warehouses` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL DEFAULT '',
  `address` text,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_zelda_scores`
--

DROP TABLE IF EXISTS `bh_zelda_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_zelda_scores` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `user_name` varchar(100) NOT NULL DEFAULT '',
  `rupees` smallint NOT NULL DEFAULT '0',
  `rooms` tinyint NOT NULL DEFAULT '0',
  `kills` smallint NOT NULL DEFAULT '0',
  `played_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `rupees` (`rupees`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_zoom_calls`
--

DROP TABLE IF EXISTS `bh_zoom_calls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_zoom_calls` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `zoom_id` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT 'meeting',
  `topic` varchar(500) NOT NULL DEFAULT '',
  `host_name` varchar(255) NOT NULL DEFAULT '',
  `host_email` varchar(255) NOT NULL DEFAULT '',
  `start_time` datetime DEFAULT NULL,
  `duration` int unsigned NOT NULL DEFAULT '0',
  `recording_url` varchar(1000) NOT NULL DEFAULT '',
  `transcript` longtext NOT NULL,
  `ai_summary` text NOT NULL,
  `action_items` text NOT NULL,
  `next_steps` text NOT NULL,
  `raw_data` longtext NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ticket_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_zoom_id` (`zoom_id`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_zoom_pending_ai`
--

DROP TABLE IF EXISTS `bh_zoom_pending_ai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_zoom_pending_ai` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `call_log_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ai_summary_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bh_zoom_settings`
--

DROP TABLE IF EXISTS `bh_zoom_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bh_zoom_settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'bhpsa_blackhawkmsp'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-31 10:55:26

CREATE TABLE IF NOT EXISTS `bh_kb_attachments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int unsigned NOT NULL DEFAULT 0,
  `original_name` varchar(400) NOT NULL DEFAULT '',
  `stored_name` varchar(200) NOT NULL DEFAULT '',
  `mime` varchar(120) DEFAULT '',
  `size` int unsigned NOT NULL DEFAULT 0,
  `uploaded_by` int unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
