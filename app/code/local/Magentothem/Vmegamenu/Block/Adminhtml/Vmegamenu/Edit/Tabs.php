<?php

class Magentothem_Vmegamenu_Block_Adminhtml_Vmegamenu_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('vmegamenu_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('vmegamenu')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('vmegamenu')->__('Item Information'),
          'title'     => Mage::helper('vmegamenu')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('vmegamenu/adminhtml_vmegamenu_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}