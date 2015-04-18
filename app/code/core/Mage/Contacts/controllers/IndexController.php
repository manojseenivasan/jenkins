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
        $jsonData[$i]["productcount"] = $layer->getCount();
        $layer_products = explode(',', $layer->getProductIds());
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
                                                                                  <div class="qty-wrapper">
                                                                                    <label for="qty">'.$helper->__('Qty:').'</label>
                                                                                    <input type="text" class="input-text qty" title="'.$helper->__('Qty:').'" value="1" maxlength="12" id="qty" name="qty" pattern="\d*">
                                                                                  </div>
                                                                                  <div class="add-to-cart-buttons">
                                                                                    <button type="submit" class="button btn-cart" title="'.$helper->__('Add to Cart').'"><span><span>'.$helper->__('Add to Cart').'</span></span></button>
                                                                                  </div>
                                                                               </div>';
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
      $sender['email'] = Mage::getStoreConfig(self::CONTACT_FORM_EMAIL,Mage::app()->getStore()->getId()); 
      $sender['name'] = Mage::app()->getStore()->getId();
      $template =  Mage::getStoreConfig(self::CONTACT_FORM_EMAIL_TEMPLATE,Mage::app()->getStore()->getId());
      $jsonData = array();
      $i = 0;
      $post = $this->getRequest()->getParams();
        if ( $post ) {
            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);
            try {
                $post['product'] = Mage::getModel('imagemarker/schematic')->load($post['layer_id'])->getText();
                $postObject = new Varien_Object();
                $postObject->setData($post);

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
                $mailTemplate = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                $mailTemplate->sendTransactional(
                        $template,
                        $sender, 
                        $this->getRequest()->getParam('email'),
                        null,
                        array('data' => $postObject)
                    );
             
                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                }

                $translate->setTranslateInline(true);

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
}