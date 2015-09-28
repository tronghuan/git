<?php

$installer = $this;

$installer->startSetup();

$installer->run ( "
		DROP TABLE IF EXISTS {$this->getTable('zohointegration/field')};

		CREATE TABLE IF NOT EXISTS {$this->getTable('zohointegration/field')} (
		`id` int(10) unsigned NOT NULL auto_increment COMMENT 'Id',
		`type` varchar(255) DEFAULT NULL ,
		`zoho` mediumtext DEFAULT NULL COMMENT 'Zoho Field',
		`magento` mediumtext DEFAULT NULL COMMENT 'Magento Field',
		`status` int(2) DEFAULT NULL COMMENT 'status',		
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

		DROP TABLE IF EXISTS {$this->getTable('zohointegration/report')};

		CREATE TABLE {$this->getTable('zohointegration/report')} (
		`id` int(12) unsigned NOT NULL auto_increment COMMENT 'Id',
		`record_id` varchar(20) DEFAULT NULL ,
		`action` varchar(50) DEFAULT NULL ,
		`table` mediumtext DEFAULT NULL COMMENT 'Zoho Field',
		`username` varchar(255) DEFAULT NULL COMMENT 'Username',
		`email` varchar(50) DEFAULT NULL COMMENT 'Email',
		`datetime` datetime DEFAULT NULL COMMENT 'Datetime',
		`status` int(2) DEFAULT NULL COMMENT 'status',		
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
		");
$installer->endSetup ();