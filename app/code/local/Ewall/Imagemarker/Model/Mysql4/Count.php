<?php
class Ewall_Imagemarker_Model_Mysql4_Count extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("imagemarker/count", "id");
    }
}