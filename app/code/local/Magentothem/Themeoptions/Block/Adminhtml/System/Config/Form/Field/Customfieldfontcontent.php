<?php
class Magentothem_Themeoptions_Block_Adminhtml_System_Config_Form_Field_Customfieldfontcontent extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $output = parent::_getElementHtml($element);

        $output .= '<span id="themeoptions_font_content_view" style="font-size:28px;line-height: 28px; display:block; padding:6px 0 0 0">Preview Font</span>
		<script type="text/javascript">
            jQuery(function(){
                fontSelect=jQuery("#themeoptions_themeoptions_config_font_content");
                fontcontentUpdate=function(){
                    curFontcontent=jQuery("#themeoptions_themeoptions_config_font_content").val();
					curFontcontent_text=curFontcontent.replace(/\+/g, " ");
                    jQuery("#themeoptions_font_content_view").css({ fontFamily: curFontcontent_text });
                    jQuery("<link />",{href:"http://fonts.googleapis.com/css?family="+curFontcontent,rel:"stylesheet",type:"text/css"}).appendTo("head");
                }
                fontSelect.change(function(){
                    fontcontentUpdate();
                }).keyup(function(){
                    fontcontentUpdate();
                }).keydown(function(){
                    fontcontentUpdate();
                });
                jQuery("#themeoptions_themeoptions_config_font_content").trigger("change");
            })
		</script>
        ';
        return $output;
    }
}