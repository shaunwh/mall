<?php
class Magentothem_Searchajax_Block_Searchajax extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getSearchajax()     
     { 
        if (!$this->hasData('searchajax')) {
            $this->setData('searchajax', Mage::registry('searchajax'));
        }
        return $this->getData('searchajax');
        
    }
  
}