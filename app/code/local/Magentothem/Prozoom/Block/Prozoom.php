<?php
class Magentothem_Prozoom_Block_Prozoom extends Mage_Catalog_Block_Product_View_Media
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getProzoom()     
     { 
        if (!$this->hasData('prozoom')) {
            $this->setData('prozoom', Mage::registry('prozoom'));
        }
        return $this->getData('prozoom');
    }
	public function getConfig($att) 
	{
		$config = Mage::getStoreConfig('prozoom');
		if (isset($config['prozoom_config']) ) {
			$value = $config['prozoom_config'][$att];
			return $value;
		} else {
			throw new Exception($att.' value not set');
		}
	}
}