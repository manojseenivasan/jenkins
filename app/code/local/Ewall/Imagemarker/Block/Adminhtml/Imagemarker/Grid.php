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
		$this->setFilterVisibility(true);
		$this->setNoFilterMassactionColumn(true);
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
			"index"   => "entity_id"
		));

		$this->addColumn('schematic', array(
			"header"  => Mage::helper('imagemarker')->__('Schematic Image'),
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
			"index"   => "name"
		));

		$this->addColumn('mapped', array(
			"header"  => Mage::helper('imagemarker')->__('Mapped'),
			'header_css_class' => 'a-center',
			"index"   => "mapped",
			"align"   => "center",
			"width"   => "100px",
			"renderer"=> "Ewall_Imagemarker_Block_Adminhtml_Imagemarker_Grid_Renderer_Map",
			"filter"  => false,
			"sortable" => false
		));

		$this->addColumn('unmapped', array(
			"header"  => Mage::helper('imagemarker')->__('Un Mapped'),
			'header_css_class' => 'a-center',
			"index"   => "unmapped",
			"align"   => "center",
			"width"   => "100px",
			"renderer"=> "Ewall_Imagemarker_Block_Adminhtml_Imagemarker_Grid_Renderer_Unmap",
			"filter"  => false,
			"sortable" => false
		));

		$this->addColumn('count', array(
			"header"  => Mage::helper('imagemarker')->__('Un Mapped Clicks'),
			'header_css_class' => 'a-center',
			"index"   => "count",
			"align"   => "center",
			"width"   => "100px",
			"renderer"=> "Ewall_Imagemarker_Block_Adminhtml_Imagemarker_Grid_Renderer_Count",
			"filter"  => false,
			"sortable" => false
		));

		$this->addColumn("is_active", array(
			"header"  => Mage::helper("imagemarker")->__("Is Active"),
			'header_css_class' => 'a-center',
			"align"   => "center",
			"width"   => "100px",
			"index"   => "is_active",
			"renderer"=> "Ewall_Imagemarker_Block_Adminhtml_Imagemarker_Grid_Renderer_Active",
			"type"    => "options",
          	"options" => Mage::getModel('adminhtml/system_config_source_yesno')->toArray(),
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
		/*$this->getMassactionBlock()->addItem('activate', array(
				 'label'=> Mage::helper('imagemarker')->__('Set Active Yes'),
				 'url'  => $this->getUrl('*adminhtml_imagemarker/massActivation'),
				 'confirm' => Mage::helper('imagemarker')->__('Are you sure?')
			));

		$this->getMassactionBlock()->addItem('deactivate', array(
				 'label'=> Mage::helper('imagemarker')->__('Set Active No'),
				 'url'  => $this->getUrl('*adminhtml_imagemarker/massDeactivation'),
				 'confirm' => Mage::helper('imagemarker')->__('Are you sure?')
			));*/
		$this->getMassactionBlock()->addItem('reset', array(
			'label'=> Mage::helper('imagemarker')->__('Reset Schematics'),
			'url'  => $this->getUrl('*/adminhtml_imagemarker/massReset'),
			'confirm' => Mage::helper('imagemarker')->__('Are you sure want to reset this schematics?')
		));
		return $this;
	}	
}