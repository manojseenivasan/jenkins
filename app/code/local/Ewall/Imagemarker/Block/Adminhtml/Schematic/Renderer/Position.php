<?php
class Ewall_Imagemarker_Block_Adminhtml_Schematic_Renderer_Position extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
 
	public function render(Varien_Object $row)
	{
		return '<input type="text" class="input-text" style="width:60%;" name="position['.$row->getId().']" value="'.$row->getPosition().'" />';
	}
 
}
?>