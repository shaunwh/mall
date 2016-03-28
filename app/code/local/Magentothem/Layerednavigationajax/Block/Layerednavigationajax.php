<?php
class Magentothem_Layerednavigationajax_Block_Layerednavigationajax extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getLayerednavigationajax()     
     { 
        if (!$this->hasData('layerednavigationajax')) {
            $this->setData('layerednavigationajax', Mage::registry('layerednavigationajax'));
        }
        return $this->getData('layerednavigationajax');
        
    }
}