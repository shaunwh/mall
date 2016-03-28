<?php
/**
 * Used in creating options for True or false config value selection
 *
 */
class Mage_Adminhtml_Model_System_Config_Source_Truefalse
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'true', 'label'=>Mage::helper('adminhtml')->__('Yes')),
            array('value' => 'false', 'label'=>Mage::helper('adminhtml')->__('No')),
        );
    }

}
