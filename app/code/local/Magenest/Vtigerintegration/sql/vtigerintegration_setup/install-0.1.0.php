<?php

$installer = $this;

$installer->startSetup();

$installer->run("
		DROP TABLE IF EXISTS {$this->getTable('vtigerintegration/map')};

		CREATE TABLE IF NOT EXISTS {$this->getTable('vtigerintegration/map')} (
		`id` int(10) unsigned NOT NULL auto_increment COMMENT 'Id',
		`vtigerintegration` varchar(255) DEFAULT NULL COMMENT 'event',
		`magento` varchar(255) DEFAULT NULL COMMENT 'event name',
		`status` varchar(255) DEFAULT 'Enable' COMMENT 'status',
		`type` varchar(255) DEFAULT NULL ,
		`name` text COMMENT 'Description',
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
                
                DROP TABLE IF EXISTS {$this->getTable('vtigerintegration/field')};

		CREATE TABLE IF NOT EXISTS {$this->getTable('vtigerintegration/field')} (
		`id` int(10) unsigned NOT NULL auto_increment COMMENT 'Id',
		`type` varchar(255) DEFAULT NULL ,
		`vtigerintegration` mediumtext DEFAULT NULL COMMENT 'Vtiger Field',
		`magento` mediumtext DEFAULT NULL COMMENT 'Magento Field',
		`status` int(2) DEFAULT 1 COMMENT 'status',		
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
                
                DROP TABLE IF EXISTS {$this->getTable('vtigerintegration/report')};

		CREATE TABLE IF NOT EXISTS {$this->getTable('vtigerintegration/report')} (
		`id` int(10) unsigned NOT NULL auto_increment COMMENT 'Id',
		`vtiger_id` varchar(255) DEFAULT NULL ,
		`vtiger_table` varchar(255) DEFAULT NULL,
		`date` datetime DEFAULT NULL,
		`status` int(2) DEFAULT NULL,		
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
		");
$installer->endSetup();
