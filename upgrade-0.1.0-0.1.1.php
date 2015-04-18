<?php
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$entityTypeCode = 'catalog_category';
$attributeCode    = 'schematic';

$setup->removeAttribute($entityTypeCode , $attributeCode);

?>