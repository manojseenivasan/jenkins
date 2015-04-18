<?php
class Ewall_Imagemarker_Block_Adminhtml_Imagemarker_Grid_Renderer_Map extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
 
	public function render(Varien_Object $row)
	{	
		$collection = $this->getLayerCollection($row->getEntityId());
		$products = array();
		foreach ($collection as $layer) {
			if(!empty($layer->getProductIds()))
			$products = array_merge($products, explode(',', $layer->getProductIds()));
		}
		
		if($count = count(array_unique($products))){}else{ $count = '0';}
		return $count;
	}

	public function getLayerCollection($category_id)
	{
		$collection = Mage::getModel("imagemarker/schematic")->getCollection();
		$collection->addFieldToFilter('category_id', $category_id);

		return $collection;
	}
 
}
?>