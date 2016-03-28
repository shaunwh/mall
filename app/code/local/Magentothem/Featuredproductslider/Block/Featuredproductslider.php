<?php
class Magentothem_Featuredproductslider_Block_Featuredproductslider extends Mage_Catalog_Block_Product_Abstract
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
    public function getFeaturedproductslider()     
    { 
        if (!$this->hasData('featuredproductslider')) {
            $this->setData('featuredproductslider', Mage::registry('featuredproductslider'));
        }
        return $this->getData('featuredproductslider');
    }
	public function getProducts()
    {
    	$storeId    = Mage::app()->getStore()->getId();
		$products = Mage::getResourceModel('catalog/product_collection')
			->addAttributeToSelect('*')
			->addStoreFilter()
			->addMinimalPrice()
			->addUrlRewrite()
			->addTaxPercents()		
			//->addAttributeToFilter("featured", 1);		
			->addFieldToFilter(array(
			array('attribute'=>'featured','eq'=>'1'),
			));

        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize($this->getConfig('qty'))->setCurPage(1);
        $this->setProductCollection($products);
    }
	public function getConfig($att) 
	{
		$config = Mage::getStoreConfig('featuredproductslider');
		if (isset($config['featuredproductslider_config']) ) {
			$value = $config['featuredproductslider_config'][$att];
			return $value;
		} else {
			throw new Exception($att.' value not set');
		}
	}
	
	function cut_string_featuredproductslider($string,$number){
		if(strlen($string) <= $number) {
			return $string;
		}
		else {	
			if(strpos($string," ",$number) > $number){
				$new_space = strpos($string," ",$number);
				$new_string = substr($string,0,$new_space)."..";
				return $new_string;
			}
			$new_string = substr($string,0,$number)."..";
			return $new_string;
		}
	}
}