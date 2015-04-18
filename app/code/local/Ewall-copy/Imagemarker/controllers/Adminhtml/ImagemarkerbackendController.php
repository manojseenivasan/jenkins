<?php
class Ewall_Imagemarker_Adminhtml_ImagemarkerbackendController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("Schematic"));
	   $this->renderLayout();
    }
}