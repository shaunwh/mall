<?php
/**
 * Used in creating options for player Scale config value selection
 *
 */
class Mage_Adminhtml_Model_System_Config_Source_Scale
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' =>0, 'label'=>Mage::helper('adminhtml')->__('Aspect Ratio')),
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Keep Orginal Size')),
            array('value' => 2, 'label'=>Mage::helper('adminhtml')->__('Fit to Screen')),

        );
    }

}
