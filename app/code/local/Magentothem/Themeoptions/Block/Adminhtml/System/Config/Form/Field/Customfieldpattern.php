<?php
class Magentothem_Themeoptions_Block_Adminhtml_System_Config_Form_Field_Customfieldpattern extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
	    $real_val = $element->getValue();
        $many_val = $element->getValues();
        $output = '';
        if ($many_val) {
            foreach ($many_val as $curr_val) {
                $output.= '<div  style="padding: 0 15px 15px 0; float: left; width: 130px;">';
				
                $output.='<input type="radio"'.$element->serialize(array( 'style', 'class','name'));
                if ($curr_val instanceof Varien_Object) {
                    $output.= 'id="'.$element->getHtmlId().$curr_val->getValue().'" '.$curr_val->serialize(array('title', 'label', 'style',  'class', 'value'));
                    if (in_array($curr_val->getValue(), $real_val))$output.= ' checked="checked"';
                    $output.= ' /><span>'.$curr_val->getLabel().'</span>';
                }
                elseif (is_array($curr_val)) {
                    $output.= 'id="'.$element->getHtmlId().$curr_val['value'].'" value="'.htmlspecialchars($curr_val['value'], ENT_COMPAT).'"';
                    if ($curr_val['value'] == $real_val) $output.= ' checked="checked"';
                    $output.= ' /><span>'.$curr_val['label'].'</span>';
                }
				
                if ($curr_val['value'])$output.= '<div style="height:30px;background:url('.Mage::getDesign()->getSkinUrl('magentothem/images/'.$curr_val['value'].'.png').')">&nbsp;</div>';
				
                $output.= '</div>'. "\n";
            }
			$output .= '
			<script type="text/javascript">
				jQuery(document).ready(function(){
					if(jQuery("#themeoptions_themeoptions_config_reset_css").val() == 1)
							jQuery("#row_themeoptions_themeoptions_config_bg_pattern").hide();
						else
							jQuery("#row_themeoptions_themeoptions_config_bg_pattern").show();
					
					jQuery("#themeoptions_themeoptions_config_reset_css").change(function(){
						if(jQuery("#themeoptions_themeoptions_config_reset_css").val() == 1)
							jQuery("#row_themeoptions_themeoptions_config_bg_pattern").hide();
						else
							jQuery("#row_themeoptions_themeoptions_config_bg_pattern").show();
					});
				})
			</script>';
        }
        return $output.'<div class="clear"></div>';
    }
}