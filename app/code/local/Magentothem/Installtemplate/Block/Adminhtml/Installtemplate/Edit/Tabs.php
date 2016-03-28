<?php

class Magentothem_Installtemplate_Block_Adminhtml_Installtemplate_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('installtemplate_tabs');
      $this->setDestElementId('edit_form');
      //$this->setTitle(Mage::helper('installtemplate')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('installtemplate')->__('Setting Template'),
          'title'     => Mage::helper('installtemplate')->__('Setting Template'),
          'content'   => $this->getLayout()->createBlock('installtemplate/adminhtml_installtemplate_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}