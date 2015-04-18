<?php
class Ewall_Imagemarker_Block_Adminhtml_Imagemarker_Grid_Renderer_Active extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
 
	public function render(Varien_Object $row)
	{	
		$options = Mage::getModel('adminhtml/system_config_source_yesno')->toArray();
		if($row->getData($this->getColumn()->getIndex())){
			return $options[$row->getData($this->getColumn()->getIndex())];
		}
		return $options[0];
	}
 
}
?>