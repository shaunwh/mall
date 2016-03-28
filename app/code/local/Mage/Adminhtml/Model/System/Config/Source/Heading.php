<?php

class Mage_Adminhtml_Model_System_Config_Source_heading
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'showall', 'label'=>Mage::helper('adminhtml')->__('Show in catalog/products pages')),
            array('value' => 'listpage', 'label'=>Mage::helper('adminhtml')->__('Show in catalog page')),
            array('value' => 'viewpage', 'label'=>Mage::helper('adminhtml')->__('Show in product page')),
            array('value' => 'hideall', 'label'=>Mage::helper('adminhtml')->__('Hide in all pages')),
           
        );
    }

}
