<?php
class Ewall_Imagemarker_Model_Observer
{

	public function SchematicLayoutUpdate(Varien_Event_Observer $observer)
	{			
		if($observer->getEvent()->getAction()->getFullActionName() == 'catalog_category_view'){
			$category = Mage::registry('current_category');
			if ($category->getSchematic()) {
				$layout = $observer->getEvent()->getLayout();
				$layout->getUpdate()->addUpdate('
					<reference name="head">
				  		<!--<action method="addJs"><script>schematic/jquery.min.js</script></action>
				  		<action method="addJs"><script>schematic/jquery.noconflict.js</script></action>-->
				  		<action method="addJs"><script>schematic/jquery-ui.min.js</script></action>
				  		<action method="addJs"><script>schematic/jquery.annotate.js</script></action>
				  		<action method="addCss"><stylesheet>schematic/annotation.css</stylesheet></action>
				  	</reference>
					<reference name="category.products">
						<action method="setTemplate"><template>schematic/category/view.phtml</template></action>
					</reference>
					<reference name="root">
			            <action method="setTemplate"><template>page/1column.phtml</template></action>
			        </reference>');		
				$layout->generateXml();
				//Mage::log(Mage::getSingleton('core/layout')->getUpdate()->asString());
			}
		}		
	}

	public function saveCategoryData(Varien_Event_Observer $observer)
	{
		$category = $observer->getEvent()->getCategory();
		if($category->getSchematic() != null){
			$category_products = $category->getProductCollection()->getAllIds();

			$collection = Mage::getModel("imagemarker/schematic")->getCollection();
			$collection->addFieldToFilter('category_id', $category->getId());
			$collection->addFieldToFilter('product_ids', array('neq'=> null));
			foreach ($collection as $layer) {
				$layer_products = explode(',', $layer->getProductIds());
				$unmaped_products = array_diff($layer_products, $category_products);
				if(count($unmaped_products)){
					$new_layer_products = array_diff($layer_products, $unmaped_products);
					if(count($new_layer_products)){
						$layer->setProductIds(implode(',', $new_layer_products));
					}
					else{
						$layer->setProductIds();
					}
					$layer->save();
				}
			}			
		}
	}		

	public function saveProductData(Varien_Event_Observer $observer)
	{
		$product = $observer->getEvent()->getProduct();
        if ($product->hasDataChanges()) {
        	$collection = Mage::getModel("imagemarker/schematic")->getCollection();
			$collection->addFieldToFilter('category_id', array('in', $product->getCategoryIds()));
			$collection->addFieldToFilter('product_ids', array(array('finset'=> array($product->getId()))));
			
			$all_collection = Mage::getModel("imagemarker/schematic")->getCollection();
			$all_collection->addFieldToFilter('product_ids', array(array('finset'=> array($product->getId()))));
        	if(count($all_collection->getAllIds())){
        		$layers = array_diff($all_collection->getAllIds(), $collection->getAllIds());
        		foreach ($all_collection as $layer) {
        			if(!in_array($layer->getId(), $layers)): continue; endif;
        			$layer_products = explode(',', $layer->getProductIds());
        			if(($key = array_search($product->getId(), $layer_products)) !== false) {
					    unset($layer_products[$key]);
					}
					$layer->setProductIds(implode(',', $layer_products));
					$layer->save();
        		}
        	}
        }
	}			
}
