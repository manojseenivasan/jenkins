<?php
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE `{$this->getTable('schematic_layer')}` ADD `position`  int(11) NOT NULL default '0';
");

$installer->endSetup();