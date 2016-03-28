<?php

class Magentothem_Themeoptions_Block_Adminhtml_System_Config_Form_Field_Customfieldcolor extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $output=parent::_getElementHtml($element);
        if (!Mage::registry('mColorPicker')) {
            $output.='
            <script type="text/javascript" src="'.$this->getJsUrl('magentothem/option/jquery-1.6.2.min.js').'"></script>
            <script type="text/javascript" src="'.$this->getJsUrl('magentothem/option/mColorPicker.js').'"></script>
            <script type="text/javascript">
                jQuery.noConflict();
                 jQuery.fn.mColorPicker.defaults.imageFolder="'.$this->getJsUrl('magentothem/option/images/').'";
            </script>
            ';
            Mage::register('mColorPicker', 1);
        }
		$output .= '
        <script type="text/javascript">
            jQuery(function(){
                 jQuery("#'.$element->getHtmlId().'").attr("data-hex", true).mColorPicker();
            })
        </script> ';
        return $output;
    }
}