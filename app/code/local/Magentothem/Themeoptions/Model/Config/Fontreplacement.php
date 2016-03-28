<?php
class Magentothem_Themeoptions_Model_Config_Fontreplacement
{
    public function toOptionArray()
    {
        return array(
            array('value'=>0, 'label'=>Mage::helper('themeoptions')->__('Disable')),
            array('value'=>1, 'label'=>Mage::helper('themeoptions')->__('Google Fonts'))        
        );
    }

}
