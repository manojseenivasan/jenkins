<?php
class Ewall_Imagemarker_Model_Mysql4_Schematic extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("imagemarker/schematic", "id");
    }
}