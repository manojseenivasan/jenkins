<?php
class Ewall_Imagemarker_Helper_Data extends Mage_Core_Helper_Abstract
{
	
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
}	
	 