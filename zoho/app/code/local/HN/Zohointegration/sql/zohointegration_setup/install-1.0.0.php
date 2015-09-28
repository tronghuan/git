<?php
/**
* HungnamEcommerce Co.
 *
 * @category   HN
 * @version    2.0.2
 * @copyright  Copyright (c) 2012-2013 HungnamEcommerce Co. (http://hungnamecommerce.com)
 * @license    http://hungnamecommerce.com/HN-LICENSE-COMMUNITY.txt
 */
	$installer = $this;

	$installer->startSetup();

	$installer->run ( "
	CREATE TABLE IF NOT EXISTS {$this->getTable('zohointegration/map')} (
	  `id` int(10) unsigned NOT NULL auto_increment COMMENT 'Id',
	  `zoho` varchar(255) DEFAULT NULL COMMENT 'event',
	  `magento` varchar(255) DEFAULT NULL COMMENT 'event name',
	  `status` varchar(255) DEFAULT NULL COMMENT 'Name',
	  `type` varchar(255) DEFAULT NULL ,
	  `name` text COMMENT 'Description',
	   PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
	" );
	$installer->endSetup ();
