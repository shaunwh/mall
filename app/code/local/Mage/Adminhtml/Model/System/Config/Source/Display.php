<?php

class Mage_Adminhtml_Model_System_Config_Source_display
{

    public function toOptionArray()
    {
        return array(
            array('value' => standard, 'label'=>Mage::helper('adminhtml')->__('Standard')),
            array('value' => bottom, 'label'=>Mage::helper('adminhtml')->__('Window right bottom corner')),
           
        );
    }

}
