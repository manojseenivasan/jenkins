<?php   
class Ewall_Imagemarker_Block_Product_Schematics extends Mage_Core_Block_Template{   


	public function getProductSchematics()
	{
		$product = Mage::registry('current_product');
		$collection = Mage::getModel("imagemarker/schematic")->getCollection();
		$collection->addFieldToSelect('id');
		$collection->addFieldToSelect('category_id');
		$collection->addFieldToSelect('product_ids');
		$collection->addFieldToFilter('product_ids', array(array('finset'=> array($product->getId()))));
		$collection->getSelect()->group('category_id');


		/*$collection->getSelect()->reset(Zend_Db_Select::COLUMNS)
     		->columns('category_id')
     		->group(array('category_id')); */
     	$schematic_ids = array_column($collection->getData(), 'category_id');
     	
    	if(count($schematic_ids)){
    		return $collection = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->addFieldToFilter('entity_id', array('in'=> $schematic_ids)); 
    	}

    	return false;
	}

}