<?php
class Ewall_Imagemarker_Block_Adminhtml_Imagemarker_Grid_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
 
	public function render(Varien_Object $row)
	{	
		if($row->getSchematic()){
			$file_path = 'catalog' . DS . 'category' . DS . $row->getSchematic();
			return '<img src="'.Mage::helper('imagemarker')->CategoryImageResize($file_path, 200, 200).'" alt="'.$row->getName().'" />';
		}
	}
 
}
?>