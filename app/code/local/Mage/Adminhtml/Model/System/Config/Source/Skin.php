<?php

/**
 * Used in creating options for Yes|No config value selection
 *
 */
class Mage_Adminhtml_Model_System_Config_Source_skin
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => skin_black, 'label'=>Mage::helper('adminhtml')->__('Black')),
            array('value' => skin_Vimeo, 'label'=>Mage::helper('adminhtml')->__('Vimeo')),
            array('value' => skin_Overlay, 'label'=>Mage::helper('adminhtml')->__('Overlay')),
            array('value' => skin_sleekblack, 'label'=>Mage::helper('adminhtml')->__('Sleekblack')),
            array('value' => skin_white, 'label'=>Mage::helper('adminhtml')->__('White')),
            array('value' => fancyblack, 'label'=>Mage::helper('adminhtml')->__('FancyBlack')),
        );
    }

}
