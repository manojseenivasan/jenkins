<?php
$installer = $this;
$installer->startSetup();


$installer->addAttribute("catalog_category", "schematic",  array(
    "type"              => "varchar",
    "backend"           => "catalog/category_attribute_backend_image",
    "frontend"          => "",
    "label"             => "Schematic",
    "input"             => "image",
    "class"             => "",
    "source"            => "",
    "global"            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    "visible"           => true,
    "required"          => false,
    "user_defined"      => false,
    "default"           => "",
    "searchable"        => false,
    "filterable"        => false,
    "comparable"        => false,   
    "visible_on_front"  => false,
    "unique"            => false,
    "note"              => "",
    'sort_order'        => 6,
    'group'             => 'General Information'

    ));
$installer->endSetup();
	 