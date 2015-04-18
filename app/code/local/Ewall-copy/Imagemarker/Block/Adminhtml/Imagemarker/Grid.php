<?php

class Ewall_Imagemarker_Block_Adminhtml_Imagemarker_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

	public function __construct()
	{
		parent::__construct();
		$this->setId("imagemarkerGrid");
		$this->setDefaultSort("id");
		$this->setDefaultDir("DESC");
		$this->setSaveParametersInSession(false);
		$this->setFilterVisibility(false);
	}

	protected function _prepareCollection()
	{
		$collection = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->addFieldToFilter('schematic', array('neq'=> null)); 
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	protected function _prepareColumns()
	{
		$this->addColumn("entity_id", array(
			"header"  => Mage::helper("imagemarker")->__("Cat ID"),
			'header_css_class' => 'a-center',
			"align"   => "center",
			"width"   => "25px",
			"index"   => "entity_id",
			"filter"  => false,
      		"sortable"=> false
		));

		$this->addColumn('schematic', array(
			"header"  => Mage::helper('catalog')->__('Schematic Image'),
			"index"   => "schematic",
			"align"   => "center",
			"width"   => "130px",
			"renderer"=> "Ewall_Imagemarker_Block_Adminhtml_Imagemarker_Grid_Renderer_Image",
			"filter"  => false,
      		"sortable"=> false
		));

		$this->addColumn("name", array(
			"header"  => Mage::helper("imagemarker")->__("Category Name"),
			"align"   => "left",
			"width"   => "400px",
	    	"type"    => "varchar",
			"index"   => "name",
			"filter"  => false,
      		"sortable"=> false
		));

		$this->addColumn('mapped', array(
			"header"  => Mage::helper('catalog')->__('Mapped'),
			'header_css_class' => 'a-center',
			"index"   => "mapped",
			"align"   => "center",
			"width"   => "100px",
			"renderer"=> "Ewall_Imagemarker_Block_Adminhtml_Imagemarker_Grid_Renderer_Map",
			"filter"  => false,
      		"sortable"=> false
		));

		$this->addColumn('unmapped', array(
			"header"  => Mage::helper('catalog')->__('Un Mapped'),
			'header_css_class' => 'a-center',
			"index"   => "unmapped",
			"align"   => "center",
			"width"   => "100px",
			"renderer"=> "Ewall_Imagemarker_Block_Adminhtml_Imagemarker_Grid_Renderer_Unmap",
			"filter"  => false,
      		"sortable"=> false
		));

		$this->addColumn("is_active", array(
			"header"  => Mage::helper("imagemarker")->__("Is Active"),
			'header_css_class' => 'a-center',
			"align"   => "center",
			"width"   => "100px",
			"index"   => "is_active",
			"type"    => "options",
          	"options" => array('0' => 'No', '1' => 'Yes'),
			"filter"  => false,
      		"sortable"=> false
		));
        

		return parent::_prepareColumns();
	}

	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}


	
	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('id');
		$this->getMassactionBlock()->setFormFieldName('ids');
		$this->getMassactionBlock()->setUseSelectAll(true);
		$this->getMassactionBlock()->addItem('activate', array(
				 'label'=> Mage::helper('imagemarker')->__('Set Active Yes'),
				 'url'  => $this->getUrl('*/adminhtml_imagemarker/massActivation'),
				 'confirm' => Mage::helper('imagemarker')->__('Are you sure?')
			));
		$this->getMassactionBlock()->addItem('deactivate', array(
				 'label'=> Mage::helper('imagemarker')->__('Set Active No'),
				 'url'  => $this->getUrl('*/adminhtml_imagemarker/massDeactivation'),
				 'confirm' => Mage::helper('imagemarker')->__('Are you sure?')
			));
		return $this;
	}
}