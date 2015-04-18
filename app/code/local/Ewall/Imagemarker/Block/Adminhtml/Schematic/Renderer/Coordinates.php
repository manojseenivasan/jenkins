<?php
class Ewall_Imagemarker_Block_Adminhtml_Schematic_Renderer_Coordinates extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
 
	public function render(Varien_Object $row)
	{
		return $row->getData('height').', '.$row->getData('width').', '.$row->getData('top').', '.$row->getData('left');
	}
 
}
?>