<?php
class Magentothem_Installtemplate_Block_Installtemplate extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getInstalltemplate()     
     { 
        if (!$this->hasData('installtemplate')) {
            $this->setData('installtemplate', Mage::registry('installtemplate'));
        }
        return $this->getData('installtemplate');
        
    }
}