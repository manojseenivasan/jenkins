<?php
class Ewall_Imagemarker_Block_Adminhtml_Imagemarker_Edit_Tab_Product extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('catalog_category_products');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);

        $this->setPagerVisibility(false);
		$this->setFilterVisibility(false);
		$this->setDefaultLimit(10000);
    }

    public function getCategory()
    {
        return Mage::registry('category');
    }

    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in category flag
        if ($column->getId() == 'in_category') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$productIds));
            }
            elseif(!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$productIds));
            }
        }
        else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    protected function _prepareCollection()
    {
        if ($this->getCategory()->getId()) {
            $this->setDefaultFilter(array('in_category'=> 1));
        }
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('price')
            ->addStoreFilter($this->getRequest()->getParam('store'))
            ->joinField('position',
                'catalog/category_product',
                'position',
                'product_id=entity_id',
                'category_id='.(int) $this->getRequest()->getParam('id', 0),
                'left');
        $this->setCollection($collection);

        //if ($this->getCategory()->getProductsReadonly()) {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            
            $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$productIds));

        //}

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        if (!$this->getCategory()->getProductsReadonly()) {
            $this->addColumn('in_category', array(
                'header_css_class' => 'a-center',
                'type'      => 'checkbox',
                'name'      => 'in_category',
                'values'    => $this->_getLayeredProducts(),
                'align'     => 'center',
                'index'     => 'entity_id',
                'filter'	=> false
            ));
        }

		$this->addColumn('sku', array(
            'header'    => Mage::helper('imagemarker')->__('SKU'),
            'width'     => '80',
            'index'     => 'sku',
            'sortable'  => false,
            'renderer'  => 'imagemarker/adminhtml_imagemarker_edit_tab_renderer_sku',
            'filter'    => false
        ));

        $this->addColumn('name', array(
            'header'    => Mage::helper('imagemarker')->__('Name'),
            'index'     => 'name',
            'sortable'  => false,
            'filter'    => false
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    protected function _getSelectedProducts()
    {
        $products = $this->getRequest()->getPost('selected_products');
        if (is_null($products)) {
            $products = $this->getCategory()->getProductsPosition();
            $category_products = array_keys($products);            
            //return array_diff($category_products, $this->_getMappedProducts());
            return $category_products;
        }
        return $products;
    }

    protected function _getMappedProducts()
    {
        $id = $this->getRequest()->getParam('id');
        $layer_id = (int) $this->getRequest()->getParam('layer');
        if($id) {
            $collection = Mage::getModel("imagemarker/schematic")->getCollection()->addFieldToFilter('category_id', $id);
            $products = array();
            foreach ($collection as $layer) {
            	if($layer_id == (int) $layer->getId()): continue; endif;
            	if($layer->getProductIds()){
            		$products = array_merge($products, explode(',', $layer->getProductIds()));
            	}
            }

            return array_unique($products);
        }
    }

    protected function _getLayeredProducts()
    {
        $id = $this->getRequest()->getParam('layer');
        $category_id = $this->getRequest()->getParam('id');
        if($id) {
            $layer = Mage::getModel("imagemarker/schematic")->load($id);
            $products = array();
        	if($category_id == $layer->getCategoryId()):
        		if($layer->getProductIds()){
            		$products = explode(',', $layer->getProductIds());
            	}
        	endif;

            return array_unique($products);
        }
    }

}

