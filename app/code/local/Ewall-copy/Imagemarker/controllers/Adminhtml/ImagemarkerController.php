<?php

class Ewall_Imagemarker_Adminhtml_ImagemarkerController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
	{
		$this->loadLayout()->_setActiveMenu("imagemarker/imagemarker")->_addBreadcrumb(Mage::helper("adminhtml")->__("Imagemarker  Manager"),Mage::helper("adminhtml")->__("Imagemarker Manager"));
		return $this;
	}

	public function indexAction() 
	{
	    $this->_title($this->__("Imagemarker"));
	    $this->_title($this->__("Manager Imagemarker"));
		$this->_initAction();
		$this->renderLayout();
	}

	public function editAction()
	{			    
	    $id = $this->getRequest()->getParam("id");
		$model = Mage::getModel("catalog/category")->load($id);
		if ($model->getId()) {
			Mage::register("category", $model);
		}
		$this->loadLayout();
		$this->_setActiveMenu("imagemarker/imagemarker");
	    $this->_title($this->__("%s Schematic", $model->getName()));
	    $this->_addContent($this->getLayout()->createBlock("imagemarker/adminhtml_imagemarker_edit_tab_product"));
	    $this->_addContent($this->getLayout()->createBlock("imagemarker/adminhtml_schematic_grid"));
	    $this->renderLayout();
	}

	
    public function gridAction()
    {
        if (!$category = $this->_initCategory(true)) {
            return;
        }
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('imagemarker/adminhtml_imagemarker_edit_tab_product', 'category.product.grid')
                ->toHtml()
        );
    }

   
    protected function _initCategory($getRootInstead = false)
    {
        
        $categoryId = (int) $this->getRequest()->getParam('id',false);
        $category = Mage::getModel('catalog/category');
        if ($categoryId) {
            $category->load($categoryId);            
        }
        Mage::register('category', $category);
        Mage::register('current_category', $category);
        return $category;
    }
	
	public function saveAction()
	{
		$post_data=$this->getRequest()->getPost();		
		if ($post_data) {
			try 
			{				
				$model = Mage::getModel("imagemarker/schematic");
				if($post_data['id'] == 'new'):
					unset($post_data['id']);
					$post_data['created_at'] = date("Y/m/d h:i:s", Mage::getModel('core/date')->timestamp(time()));
				else:
					$model->setId($post_data['id']);
				endif;
				$model->addData($post_data);
				$model->save();

				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Layer was successfully saved"));
				Mage::getSingleton("adminhtml/session")->setImagemarkerData(false);

				if ($this->getRequest()->getParam("back")) {
					$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
				}
				$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
				return;
			} 
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
				Mage::getSingleton("adminhtml/session")->setImagemarkerData($this->getRequest()->getPost());
				$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
				return;
			}

		}
		$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
	}



	public function deleteAction()
	{
		if( $this->getRequest()->getParam("id") > 0 ) {
			try {
				$model = Mage::getModel("imagemarker/schematic");
				$model->setId($this->getRequest()->getParam("layer"))->delete();
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Layer was successfully deleted"));
				$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
			} 
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
				$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
			}
		}
		$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
	}

		
	public function massActivationAction()
	{
		try {
			$ids = $this->getRequest()->getPost('ids', array());
			foreach ($ids as $id) {  
				$categorySingleton = Mage::getSingleton('catalog/category')->load($id)->setIsActive(true)->save();

				/*$categorySingleton = Mage::getSingleton('catalog/category');
				$categorySingleton->setId($id);
				$categorySingleton->setIsActive(true);
				$categorySingleton->setStoreId(1);					 
				Mage::getModel('catalog/category')->getResource()->saveAttribute($categorySingleton, 'is_active'); */
			}
			Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Schematic status (Activation) was successfully updated"));
		}
		catch (Exception $e) {
			Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
		}
		$this->_redirect('*/*/');
	}

	public function massDeactivationAction()
	{
		try {
			$ids = $this->getRequest()->getPost('ids', array());
			foreach ($ids as $id) {
                $categorySingleton = Mage::getSingleton('catalog/category')->load($id)->setIsActive(false)->save();

                /*$categorySingleton = Mage::getSingleton('catalog/category');
				$categorySingleton->setId($id);
				$categorySingleton->setIsActive(false);
				$categorySingleton->setStoreId(1);					 
				Mage::getModel('catalog/category')->getResource()->saveAttribute($categorySingleton, 'is_active'); */
			}
			Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Schematic status (Dectivation) was successfully updated"));
		}
		catch (Exception $e) {
			Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
		}
		$this->_redirect('*/*/');
	}		
}
