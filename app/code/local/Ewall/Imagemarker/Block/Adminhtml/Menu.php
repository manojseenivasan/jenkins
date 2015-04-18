<?php
class Ewall_Imagemarker_Block_Adminhtml_Menu extends Mage_Adminhtml_Block_Page_Menu
{
	public function getMenuArray()
	{
		//Load standard menu
		$parentArr = parent::getMenuArray();
		unset($parentArr['imagemarker']['children']['imagemarker']['children']['settings']['last']);
		$parentArr['imagemarker']['children']['imagemarker']['children'][]= array(
																			'label'      => 'Help',
																			'active'     => false ,
																			'click'      => "window.open(this.href, 'Website - ' + this.href); return false;",
																			'sort_order' => 3,
																			'level'      => 2,
																			'url'        => 'http://magento.vividinnovations.com.au/',
																			'last'       => 1,
																		 );
		return $parentArr;
    }
}
