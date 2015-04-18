<?php
class Ewall_Imagemarker_IndexController extends Mage_Core_Controller_Front_Action{

    const CONTACT_FORM_EMAIL_TEMPLATE = 'imagemarker_section/imagemarker_group/email_template';
    const CONTACT_FORM_EMAIL = 'imagemarker_section/imagemarker_group/email';

    public function IndexAction() {
      $helper = Mage::helper('catalog');
      $schematic_helper = Mage::helper('imagemarker');
      $_productCollection = $schematic_helper->getSchematicCategoryCollection($this->getRequest()->getParam('category_id'));  
      $layer_collection = $schematic_helper->getLayerCollection($this->getRequest()->getParam('category_id'));
      $jsonData = array();
      $i = 0;
      foreach($layer_collection as $layer):
        $jsonData[$i]["top"] = $layer->getTop();
        $jsonData[$i]["left"] = $layer->getLeft();
        $jsonData[$i]["width"] = $layer->getWidth();
        $jsonData[$i]["height"] = $layer->getHeight();
        $jsonData[$i]["text"] = $layer->getText();
        $jsonData[$i]["id"] = $layer->getId();
        $jsonData[$i]["editable"] = true;
        //$jsonData[$i]["productcount"] = $layer->getCount();
        $layer_products = explode(',', $layer->getProductIds());
        if($layer->getProductIds()){
          $jsonData[$i]["productcount"] = count($layer_products);
        }
        else{
          $jsonData[$i]["productcount"] = (int) 0;          
        }
        foreach ($_productCollection as $product):
          if(!in_array($product->getId(), $layer_products)):  continue; endif;
            $jsonData[$i]["productdata"][$product->getId()] = $product->getData();
            $jsonData[$i]["productdata"][$product->getId()]['is_saleable'] = $product->isSaleable();
            $_product = Mage::getModel('catalog/product')->load($product->getId());
            $jsonData[$i]["productdata"][$product->getId()]['thumbnail_image'] = (string)Mage::helper('catalog/image')->init($_product, 'thumbnail')->resize(100, 100);
            $jsonData[$i]["productdata"][$product->getId()]['url_path'] = Mage::getUrl($product->getUrlPath());
            $product_block = new Mage_Catalog_Block_Product;
            $product_list_block = new Mage_Catalog_Block_Product_List;
            $jsonData[$i]["productdata"][$product->getId()]['price_html'] = $product_block->getPriceHtml($product);
            $jsonData[$i]["productdata"][$product->getId()]['addtocart_url'] = $product_list_block->getAddToCartUrl($product);
            if($product->isSaleable()):
              $jsonData[$i]["productdata"][$product->getId()]['addto_html'] = '<div class="add-to-cart">                                                                                 
                                                                                  <div class="add-to-cart-buttons">
                                                                                     <div class="qty-wrapper">                                                                                    
                                                                                      <input type="text" class="input-text qty" title="'.$helper->__('Qty:').'" value="1" maxlength="12" id="qty" name="qty" pattern="\d*">
                                                                                      <button type="submit" class="button btn-cart" title="'.$helper->__('Add to Cart').'"><span><span>'.$helper->__('Add to Cart').'</span></span></button>
                                                                                    </div>
                                                                                    
                                                                                  </div>
                                                                               </div>';
                                                                               //<label for="qty">'.$helper->__('Qty:').'</label>
            elseif (!$_product->isSaleable()):
              $jsonData[$i]["productdata"][$product->getId()]['addto_html'] = '<div class="add-to-cart"></div>';
            endif;
            unset($product_block);
            unset($product_list_block);
        endforeach;
        $i++;
      endforeach;
      
  	  $this->getResponse()->setHeader('Content-type', 'application/json');
      $this->getResponse()->setBody(json_encode($jsonData));	  
    }

    public function SendAction() {
      $to_email =  Mage::getStoreConfig(self::CONTACT_FORM_EMAIL,Mage::app()->getStore()->getId());
      $template =  Mage::getStoreConfig(self::CONTACT_FORM_EMAIL_TEMPLATE,Mage::app()->getStore()->getId());
      $jsonData = array();
      $i = 0;
      $post = $this->getRequest()->getParams();
      $sender['email'] = $post['email'];
      $sender['name'] = Mage::app()->getStore()->getId();
      $cat_name = Mage::getModel('catalog/category')->load($post['category_id']);
      $post['cat_name'] = $cat_name->getName();
        if ( $post ) {
            try {	
				$post['product'] = Mage::getModel('imagemarker/schematic')->load($post['layer_id'])->getText();
                $error = false;

                if (!Zend_Validate::is(trim($post['name']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['comment']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['phone']), 'NotEmpty')) {
                    $error = true;
                }
                if ($error) {
                    throw new Exception();
                }
              		
		$postObj = new Varien_Object();
		$postObj->setData($post);
		$translate = Mage::getSingleton('core/translate');
	        $translate->setTranslateInline(false);
       		$mailTemplate = Mage::getModel('core/email_template');
        	$mailTemplate->setDesignConfig(array('area' => 'frontend'))
        		->sendTransactional(
                        	$template,
                        	$sender, 
                        	$to_email,
                        	null,
                        	array('data' => $postObj)
                    	);
        	$translate->setTranslateInline(true);
             
                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                }


                $jsonData['message']=Mage::helper('contacts')->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.');
            } catch (Exception $e) {
                $translate->setTranslateInline(true);

                $jsonData['message']=Mage::helper('contacts')->__('Unable to submit your request. Please, try again later');
            }

        }
        $jsonData['annotation_id'] = $mailTemplate->getSentSuccess();
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($jsonData));  
    }

    public function countAction() {
      if($layer_id = $this->getRequest()->getParam('layer_id')){
        $ip = Mage::helper('core/http')->getRemoteAddr(true);
        $date = new Zend_Date(Mage::getModel('core/date')->timestamp());
        $weak_before = new Zend_Date(Mage::getModel('core/date')->timestamp());

        $count_model = Mage::getModel('imagemarker/count');
        $is_exist = $count_model->getCollection()
                      ->addFieldToFilter('layer_id', $layer_id)
                      ->addFieldToFilter('remote_addr', $ip)
                      ->addfieldtofilter('clicked_at', array(array('gteq' => $weak_before->subDay('7')->toString('Y-M-d H:m:s'))))
                      ->count();
        $jsonData['saved'] = false;
        if(!$is_exist):
          $count_model->setLayerId($layer_id);
          $count_model->setRemoteAddr($ip);
          $count_model->setClickedAt($date->toString('Y-M-d H:m:s'));
          $count_model->save();
          $count = $count_model->getCollection()
                      ->addFieldToFilter('layer_id', $layer_id)
                      ->addFieldToFilter('remote_addr', $ip)
                      ->count();
          Mage::getModel('imagemarker/schematic')->load($layer_id)->setClickedCount($count)->save();
          $jsonData['saved'] = true;
        endif;

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($jsonData));  
      }
    }
}