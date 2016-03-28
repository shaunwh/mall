<?php
class Magentothem_Bestsellerproductslider_Block_Bestsellerproductslider extends Mage_Catalog_Block_Product_Abstract
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
	
    protected function useFlatCatalogProduct()
    {
        return Mage::getStoreConfig('catalog/frontend/flat_catalog_product');
    }
        
    public function getBestsellerproductslider()     
    { 
        if (!$this->hasData('bestsellerproductslider')) {
            $this->setData('bestsellerproductslider', Mage::registry('bestsellerproductslider'));
        }
        return $this->getData('bestsellerproductslider');
    }
	public function getProducts()
    {
        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection->addAttributeToSelect('*')->addStoreFilter();
        $orderItems = Mage::getSingleton('core/resource')->getTableName('sales/order_item');
        $orderMain =  Mage::getSingleton('core/resource')->getTableName('sales/order');
        $collection->getSelect()
            ->join(array('items' => $orderItems), "items.product_id = e.entity_id", array('count' => 'SUM(items.qty_ordered)'))
            ->join(array('trus' => $orderMain), "items.order_id = trus.entity_id", array())
            ->group('e.entity_id')
            ->order('count DESC');

        // getNumProduct
        $collection->setPageSize($this->getConfig('qty'));	
	
        $this->setProductCollection($collection);
    }
	public function getConfig($att) 
	{
		$config = Mage::getStoreConfig('bestsellerproductslider');
		if (isset($config['bestsellerproductslider_config']) ) {
			$value = $config['bestsellerproductslider_config'][$att];
			return $value;
		} else {
			throw new Exception($att.' value not set');
		}
	}
	
	function cut_string_bestsellerproductslider($string,$number){ 
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