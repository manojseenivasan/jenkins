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
			    $this->_title($this->__("Imagemarker"));
				$this->_title($this->__("Imagemarker"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("imagemarker/imagemarker")->load($id);
				if ($model->getId()) {
					Mage::register("imagemarker_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("imagemarker/imagemarker");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Imagemarker Manager"), Mage::helper("adminhtml")->__("Imagemarker Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Imagemarker Description"), Mage::helper("adminhtml")->__("Imagemarker Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("imagemarker/adminhtml_imagemarker_edit"))->_addLeft($this->getLayout()->createBlock("imagemarker/adminhtml_imagemarker_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("imagemarker")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("Imagemarker"));
		$this->_title($this->__("Imagemarker"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("imagemarker/imagemarker")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("imagemarker_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("imagemarker/imagemarker");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Imagemarker Manager"), Mage::helper("adminhtml")->__("Imagemarker Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Imagemarker Description"), Mage::helper("adminhtml")->__("Imagemarker Description"));


		$this->_addContent($this->getLayout()->createBlock("imagemarker/adminhtml_imagemarker_edit"))->_addLeft($this->getLayout()->createBlock("imagemarker/adminhtml_imagemarker_edit_tabs"));

		$this->renderLayout();

		}
	
	public function saveAction()
	{

		$post_data=$this->getRequest()->getPost();

		if ($post_data) {

			try {

				

				$model = Mage::getModel("imagemarker/imagemarker")
				->addData($post_data)
				->setId($this->getRequest()->getParam("id"))
				->save();

				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Imagemarker was successfully saved"));
				Mage::getSingleton("adminhtml/session")->setImagemarkerData(false);

				if ($this->getRequest()->getParam("back")) {
					$this->_redirect("*/*/edit", array("id" => $model->getId()));
					return;
				}
				$this->_redirect("*/*/");
				return;
			} 
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
				Mage::getSingleton("adminhtml/session")->setImagemarkerData($this->getRequest()->getPost());
				$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
			return;
			}

			}
			$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("imagemarker/imagemarker");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		
		public function massActivationAction()
		{
			try {
				$ids = $this->getRequest()->getPost('ids', array());
				foreach ($ids as $id) {  
					$categorySingleton = Mage::getSingleton('catalog/category')->load($id)->setIsActive(true)->save();

					$categorySingleton = Mage::getSingleton('catalog/category');
					$categorySingleton->setId($id);
					$categorySingleton->setIsActive(true);
					$categorySingleton->setStoreId(1);					 
					Mage::getModel('catalog/category')->getResource()->saveAttribute($categorySingleton, 'is_active');
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

                    $categorySingleton = Mage::getSingleton('catalog/category');
					$categorySingleton->setId($id);
					$categorySingleton->setIsActive(false);
					$categorySingleton->setStoreId(1);					 
					Mage::getModel('catalog/category')->getResource()->saveAttribute($categorySingleton, 'is_active');
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Schematic status (Dectivation) was successfully updated"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
}
