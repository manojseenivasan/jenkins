<?php
$installer = $this;
$installer->startSetup();

$installer->run("
	-- DROP TABLE IF EXISTS {$this->getTable('schematic_layer')};
	CREATE TABLE {$this->getTable('schematic_layer')} (
	  `id` int(10) unsigned NOT NULL auto_increment,
	  `category_id` int(11) NOT NULL default '0',
	  `text` text NOT NULL,
	  `product_ids` text NOT NULL,   
	  `height` int(11) NOT NULL default '0',
	  `width` int(11) NOT NULL default '0',
	  `left` int(11) NOT NULL default '0',
	  `top` int(11) NOT NULL default '0', 
	  `count` int(11) NOT NULL default '0',
	  `created_at` datetime NULL, 
	  PRIMARY KEY  (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$installer->endSetup();
	 