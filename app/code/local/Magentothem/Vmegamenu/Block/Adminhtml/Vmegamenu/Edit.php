<?php

class Magentothem_Vmegamenu_Block_Adminhtml_Vmegamenu_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'vmegamenu';
        $this->_controller = 'adminhtml_vmegamenu';
        
        $this->_updateButton('save', 'label', Mage::helper('vmegamenu')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('vmegamenu')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('vmegamenu_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'vmegamenu_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'vmegamenu_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('vmegamenu_data') && Mage::registry('vmegamenu_data')->getId() ) {
            return Mage::helper('vmegamenu')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('vmegamenu_data')->getTitle()));
        } else {
            return Mage::helper('vmegamenu')->__('Add Item');
        }
    }
}