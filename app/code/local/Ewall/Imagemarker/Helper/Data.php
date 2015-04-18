<?php
class Ewall_Imagemarker_Helper_Data extends Mage_Core_Helper_Abstract
{	
	public function getSchematicCategoryCollection($category_id){
		if($category_id){
			$collection = Mage::getModel('catalog/product')->getCollection()
				->addAttributeToSelect('entity_id')
				->addAttributeToSelect('price')
				->addAttributeToSelect('special_price')
				->addAttributeToSelect('sku')
				->addAttributeToSelect('has_options')
				->addAttributeToSelect('thumbnail')
				->addAttributeToSelect('url_path')
				->addAttributeToSelect('name');
            
            		$layer_collection = $this->getLayerCollection($category_id);
			$products = array();
			foreach ($layer_collection as $layer) {
				if(!empty($layer->getProductIds()))
				$products = array_merge($products, explode(',', $layer->getProductIds()));
			}

			if(count(array_unique($products)))
			{
				$productIds = array_unique($products);
			}
			else{
				$productIds = 0;
			}

			return $collection->addFieldToFilter('entity_id', array('in'=>$productIds));
		}
	}

	public function getLayerCollection($category_id)
	{
		$collection = Mage::getModel("imagemarker/schematic")->getCollection();
		$collection->addFieldToFilter('category_id', $category_id);

		$collection->setOrder('position','ASC');
		return $collection;
	}
	
    public function CategoryImageResize($fileName, $width, $height = '200'){

	    $imageURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . "imagemarker" . DS . $width ."X".$height . DS . $fileName;	 
	    $basePath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $fileName;
	    $newPath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . "imagemarker" . DS . $width ."X".$height  . DS . $fileName;
	    $create_path = $newPath;
	    $create_path = explode('/', $create_path);
	    array_pop($create_path);
	    $create_path = implode('/', $create_path);
	    $file = new Varien_Io_File();
        $customer_result = $file->mkdir($create_path);         
        if (!$customer_result) {
            //Handle error
        }

	    if ($width != '') {
	        if (file_exists($basePath) && is_file($basePath) && !file_exists($newPath)) {
	            $imageObj = new Varien_Image($basePath);
	            $imageObj->constrainOnly(TRUE);
	            $imageObj->keepAspectRatio(FALSE);
	            $imageObj->keepFrame(FALSE);
	            $imageObj->resize($width, $height);
	            $imageObj->save($newPath);
	        }
	        $resizedURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . "imagemarker" . DS . $width ."X".$height  . DS . $fileName;
	     } else {
	        $resizedURL = $imageURL;
	     }
	     return $resizedURL;
	}


	public function CategorySchematicResize($fileName, $width, $height){

	    $imageURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . "imagemarker" . DS . $width ."X".$height . DS . $fileName;	 
	    $basePath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $fileName;
	    $newPath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . "imagemarker" . DS . $width ."X".$height  . DS . $fileName;
	    $create_path = $newPath;
	    $create_path = explode('/', $create_path);
	    array_pop($create_path);
	    $create_path = implode('/', $create_path);
	    $file = new Varien_Io_File();
        $customer_result = $file->mkdir($create_path);         
        if (!$customer_result) {
            //Handle error
        }

	    if ($width != '') {
	        if (file_exists($basePath) && is_file($basePath) && !file_exists($newPath)) {
	            $imageObj = new Varien_Image($basePath);
	            $imageObj->constrainOnly(TRUE);
	            $imageObj->keepAspectRatio(TRUE);
	            $imageObj->keepFrame(FALSE);
	            $imageObj->resize($width, $height);
	            $imageObj->save($newPath);
	        }
	        $resizedURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . "imagemarker" . DS . $width ."X".$height  . DS . $fileName;
	     } else {
	        $resizedURL = $imageURL;
	     }
	     return $resizedURL;
	}


	public function getProductQty($product)
	{
	   $quote = Mage::getSingleton('checkout/cart')->getQuote();
	   $item = $quote->getItemByProduct($product);
	   if ($item !== false)
	   return $item->getQty();
	}

}	
	 