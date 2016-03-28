<?php

class Magentothem_Installtemplate_Block_Adminhtml_Installtemplate_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->removeButton('back');
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'installtemplate';
        $this->_controller = 'adminhtml_installtemplate';
        
        $this->_updateButton('save', 'label', Mage::helper('installtemplate')->__('Submit'));
        $this->_updateButton('delete', 'label', Mage::helper('installtemplate')->__('Delete Item'));
		
    
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('installtemplate_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'installtemplate_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'installtemplate_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('installtemplate_data') && Mage::registry('installtemplate_data')->getId() ) {
            return Mage::helper('installtemplate')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('installtemplate_data')->getTitle()));
        } else {
            return Mage::helper('installtemplate')->__('');
        }
    }
}