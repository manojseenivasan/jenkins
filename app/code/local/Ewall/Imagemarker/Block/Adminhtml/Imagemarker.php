<?php
class Ewall_Imagemarker_Block_Adminhtml_Imagemarker extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{
		$this->_controller = "adminhtml_imagemarker";
		$this->_blockGroup = "imagemarker";
		$this->_headerText = Mage::helper("imagemarker")->__("Manage Schematics");
		$this->_addButtonLabel = Mage::helper("imagemarker")->__("Add New Item");
		parent::__construct();
		$this->_removeButton('add');
	}

	public function getSchematicImageUrl()
	{
		$_category = $this->getCategory();
		return Mage::getBaseUrl('media').'catalog/category/'.$_category->getSchematic(); 
	}

	public function getSaveAction()
	{
		$_category = $this->getCategory();
		return $this->getUrl('*/*/save', array('id' => $_category->getId())); 
	}

	public function getCategory()
	{
		return Mage::registry("category");
	}

	public function getLayerCollection()
	{
		$collection = Mage::getModel("imagemarker/schematic")->getCollection();
		$collection->addFieldToFilter('category_id', $this->getRequest()->getParam('id'));

		return $collection;
	}
}