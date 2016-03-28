<?php
class Magentothem_Themeoptions_Block_Adminhtml_System_Config_Form_Field_Customfieldfont extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $output = parent::_getElementHtml($element);

        $output .= '<span id="themeoptions_font_view" style="font-size:28px;line-height: 28px; display:block; padding:6px 0 0 0">Preview Font</span>
        <script type="text/javascript" src="'.$this->getJsUrl('magentothem/option/jquery-1.6.2.min.js').'"></script>
		<script type="text/javascript">
            jQuery.noConflict();
            jQuery(function(){
                fontSelect=jQuery("#themeoptions_themeoptions_config_font");
                fontUpdate=function(){
                    curFont=jQuery("#themeoptions_themeoptions_config_font").val();
					curFont_text=curFont.replace(/\+/g, " ");
                    jQuery("#themeoptions_font_view").css({ fontFamily: curFont_text });
                    jQuery("<link />",{href:"http://fonts.googleapis.com/css?family="+curFont,rel:"stylesheet",type:"text/css"}).appendTo("head");
                }
                fontSelect.change(function(){
                    fontUpdate();
                }).keyup(function(){
                    fontUpdate();
                }).keydown(function(){
                    fontUpdate();
                });
                jQuery("#themeoptions_themeoptions_config_font").trigger("change");
            })
		</script>
        ';
        return $output;
    }
}