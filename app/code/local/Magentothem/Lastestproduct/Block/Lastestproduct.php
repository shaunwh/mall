<?php
class Magentothem_Lastestproduct_Block_Lastestproduct extends Mage_Catalog_Block_Product_Abstract
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
    public function getLastestproduct()     
    { 
        if (!$this->hasData('lastestproduct')) {
            $this->setData('lastestproduct', Mage::registry('lastestproduct'));
        }
        return $this->getData('lastestproduct');
    }
	public function getProducts($fieldorder = 'updated_at', $order = 'desc')
    {
    	$storeId    = Mage::app()->getStore()->getId();
		$products = Mage::getResourceModel('catalog/product_collection')
			->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
			->addMinimalPrice()
			->addUrlRewrite()
			->addTaxPercents()			
			->addStoreFilter()
			->setOrder ($fieldorder,$order);
		Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
		Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize($this->getConfig('qty'))->setCurPage(1);
        $this->setProductCollection($products);
    }
	public function getConfig($att) 
	{
		$config = Mage::getStoreConfig('lastestproduct');
		if (isset($config['lastestproduct_config']) ) {
			$value = $config['lastestproduct_config'][$att];
			return $value;
		} else {
			throw new Exception($att.' value not set');
		}
	}
}