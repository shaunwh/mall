<?php
/*------------------------------------------------------------------------
# Websites: http://www.magentothem.com/
			http://www.plazathemes.com/
-------------------------------------------------------------------------*/ 
class Magentothem_Banner7_Block_Adminhtml_Banner7_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'banner7';
        $this->_controller = 'adminhtml_banner7';
        
        $this->_updateButton('save', 'label', Mage::helper('banner7')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('banner7')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('banner7_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'banner7_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'banner7_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('banner7_data') && Mage::registry('banner7_data')->getId() ) {
            return Mage::helper('banner7')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('banner7_data')->getTitle()));
        } else {
            return Mage::helper('banner7')->__('Add Item');
        }
    }
}