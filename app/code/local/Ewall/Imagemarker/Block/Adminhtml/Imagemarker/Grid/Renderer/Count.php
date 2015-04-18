<?php
class Ewall_Imagemarker_Block_Adminhtml_Imagemarker_Grid_Renderer_Count extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
 
	public function render(Varien_Object $row)
	{	
		$collection = $this->getLayerCollection($row->getEntityId());
		$count = null;
		foreach ($collection as $layer) {
			$count += $layer->getClickedCount();
		}
		if($count){}else{ $count = '0';}

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