<?php
class Magentothem_Timer_Block_Timer extends Mage_Catalog_Block_Product_Abstract
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getOnsaleslider()     
     { 
        if (!$this->hasData('onsaleslider')) {
            $this->setData('onsaleslider', Mage::registry('onsaleslider'));
        }
        return $this->getData('onsaleslider');
        
    }
	public function getProducts()
    {
		$_productCollection = Mage::getResourceModel('catalog/product_collection')
		->addAttributeToSelect('*')->addStoreFilter()
		->addMinimalPrice()
		->addStoreFilter();

		Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($_productCollection);
		Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($_productCollection);

		$todayDate = date('m/d/y');
		$tomorrow = mktime(0, 0, 0, date('m'), date('d'), date('y'));
		$tomorrowDate = date('m/d/y', $tomorrow);

		$_productCollection->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate))
		->addAttributeToFilter('special_to_date', array('or'=> array(
		0 => array('date' => true, 'from' => $tomorrowDate),
		1 => array('is' => new Zend_Db_Expr('null')))
		), 'left')->addAttributeToFilter('special_price', array('gt' => 0));
		$_productCollection->setPageSize($this->getConfig('qty'))->setCurPage(1);
		$this->setProductCollection($_productCollection);
    }
	public function getConfig($att) 
	{
		$config = Mage::getStoreConfig('timer');
		if (isset($config['timer_config']) ) {
			$value = $config['timer_config'][$att];
			return $value;
		} else {
			throw new Exception($att.' value not set');
		}
	}
	
	function cut_string_timer($string,$number){
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