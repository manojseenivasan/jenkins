<?php
$installer = $this;
$installer->startSetup();

$installer->run("
	ALTER TABLE `{$this->getTable('schematic_layer')}` ADD `clicked_count` int(11) NOT NULL default '0';
");

$installer->run("
	-- DROP TABLE IF EXISTS {$this->getTable('layer_count')};
	CREATE TABLE {$this->getTable('layer_count')} (
	  `id` int(10) unsigned NOT NULL auto_increment,
	  `layer_id` int(11) NOT NULL default '0',
	  `remote_addr` bigint(20) NOT NULL default '0',
	  `clicked_at` datetime NULL, 
	  PRIMARY KEY  (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$installer->endSetup();