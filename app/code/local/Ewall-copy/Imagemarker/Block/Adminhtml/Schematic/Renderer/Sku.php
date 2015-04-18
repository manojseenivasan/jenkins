<?php
class Ewall_Imagemarker_Block_Adminhtml_Schematic_Renderer_Sku extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
 
	public function render(Varien_Object $row)
	{
		$product_ids =  $row->getData($this->getColumn()->getIndex());
		if(!empty($product_ids)){
			$productsCollection = Mage::getModel('catalog/product')
                    ->getCollection()
                    ->addAttributeToFilter('entity_id', array('in' => explode(',', $product_ids)));
            $sku = '';
            $count = 1;
			foreach($productsCollection as $product) {
				if($productsCollection->count() == $count){
				    $sku .= $product->getSku();
				}
				else{
					$sku .= $product->getSku().', ';
				}
				$count++;
			}
			return '<span style="color:red;">'.$sku.'</span>';
		}	
		
		return;
	 
	}
 
}
?>