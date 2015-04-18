<?php
class Ewall_Imagemarker_Block_Adminhtml_Imagemarker_Edit_Tab_Renderer_Sku extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
 
	public function render(Varien_Object $row)
	{
		return '<a href="'.Mage::helper('adminhtml')->getUrl('adminhtml/catalog_product/edit', array('id' => $row->getId())).'">'.$row->getData($this->getColumn()->getIndex()).'</a>';
	 
	}
 
}
?>