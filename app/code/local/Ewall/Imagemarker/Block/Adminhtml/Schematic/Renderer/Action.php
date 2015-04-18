<?php
class Ewall_Imagemarker_Block_Adminhtml_Schematic_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
 
	public function render(Varien_Object $row)
	{	
		$id = $this->getRequest()->getParam('id');
		$html = '';
		$html .= '<a id="edit_'.$row->getId().'" href="'.$this->getUrl('*/*/edit', array('id' => $id, 'layer' => $row->getId())).'">'.$this->helper('imagemarker')->__('Edit').'</a>';
		$html .= '<a style="margin-left:10px;" onclick="return delete_confirmation(this);" id="delete_'.$row->getId().'" href="'.$this->getUrl('*/*/delete', array('id' => $id, 'layer' => $row->getId())).'">'.$this->helper('imagemarker')->__('Delete').'</a>';
		return $html;
	}
 
}
?>