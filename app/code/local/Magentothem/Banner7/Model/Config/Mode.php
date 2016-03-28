<?php
/*------------------------------------------------------------------------
# Websites: http://www.magentothem.com/
-------------------------------------------------------------------------*/ 
class Magentothem_Banner7_Model_Config_Mode
{

    public function toOptionArray()
    {
        return array(
            array('value'=>'fade', 'label'=>Mage::helper('adminhtml')->__('fade')),
            array('value'=>'slide', 'label'=>Mage::helper('adminhtml')->__('slide')),
            array('value'=>'random', 'label'=>Mage::helper('adminhtml')->__('random')),
            array('value'=>'sliceDown', 'label'=>Mage::helper('adminhtml')->__('sliceDown')),
            array('value'=>'sliceDownLeft', 'label'=>Mage::helper('adminhtml')->__('sliceDownLeft')),
            array('value'=>'sliceUp', 'label'=>Mage::helper('adminhtml')->__('sliceUp')),
            array('value'=>'sliceUpLeft', 'label'=>Mage::helper('adminhtml')->__('sliceUpLeft')),
            array('value'=>'sliceUpDown', 'label'=>Mage::helper('adminhtml')->__('sliceUpDown')),
            array('value'=>'sliceUpDownLeft', 'label'=>Mage::helper('adminhtml')->__('sliceUpDownLeft')),
            array('value'=>'fold', 'label'=>Mage::helper('adminhtml')->__('fold')),
            array('value'=>'slideInRight', 'label'=>Mage::helper('adminhtml')->__('slideInRight')),
            array('value'=>'slideInLeft', 'label'=>Mage::helper('adminhtml')->__('slideInLeft')),
            array('value'=>'boxRandom', 'label'=>Mage::helper('adminhtml')->__('boxRandom')),
            array('value'=>'boxRain', 'label'=>Mage::helper('adminhtml')->__('boxRain')),
            array('value'=>'boxRainReverse', 'label'=>Mage::helper('adminhtml')->__('boxRainReverse')),
            array('value'=>'boxRainGrow', 'label'=>Mage::helper('adminhtml')->__('boxRainGrow')),
            array('value'=>'boxRainGrowReverse', 'label'=>Mage::helper('adminhtml')->__('boxRainGrowReverse'))
        );
    }

}
