<?php

class Ewall_Imagemarker_Block_Adminhtml_Schematic_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId("schematicGrid");
		$this->setDefaultSort("id");
		$this->setDefaultDir("ASC");
		$this->setSaveParametersInSession(true);

		$this->setPagerVisibility(false);
		$this->setFilterVisibility(false);
		$this->setDefaultLimit(10000);
	}

	protected function _prepareCollection()
	{
		$collection = Mage::getModel("imagemarker/schematic")->getCollection();
		$collection->addFieldToFilter('category_id', $this->getRequest()->getParam('id'));
		if($layer_id = $this->getRequest()->getParam('layer')){
			$collection->addFieldToFilter('id', array('nin' => array($layer_id)));
		}
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{
		$this->addColumn("text", array(
			"header" => Mage::helper("imagemarker")->__("Layer Label"),
			"align" =>"left",
			"width" => "40%",
			"index" => "text",
			'sortable'  => false
		));

		$this->addColumn('sku', array(
            'header'    => Mage::helper('imagemarker')->__('Associated SKU'),
            'width'     => '40%',
            'index'     => 'product_ids',
            'sortable'  => false,
            'renderer'  => 'imagemarker/adminhtml_schematic_renderer_sku'
        ));

        $this->addColumn('co_ordinates', array(
            'header'    => Mage::helper('imagemarker')->__('Co-ordinates'),
            'width'     => '10%',
            'index'     => 'co_ordinates',
            'sortable'  => false,
            'renderer'  => 'imagemarker/adminhtml_schematic_renderer_coordinates'
        ));

        $this->addColumn('action_edit', array(
	        'header'   => $this->helper('imagemarker')->__('Action'),
	        'width'    => '10%',
	        'sortable' => false,
	        'filter'   => false,
	        'renderer'  => 'imagemarker/adminhtml_schematic_renderer_action'	        
   		));

		return parent::_prepareColumns();
	}

	public function getRowUrl($row)
	{	
		return '#';
	}

	public function getRowClass($row)
	{	
		return 'layer-row row-'.$row->getId();
	}
}