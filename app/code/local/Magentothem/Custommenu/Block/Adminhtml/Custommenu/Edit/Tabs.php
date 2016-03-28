<?php

class Magentothem_Custommenu_Block_Adminhtml_Custommenu_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('custommenu_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('custommenu')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('custommenu')->__('Item Information'),
          'title'     => Mage::helper('custommenu')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('custommenu/adminhtml_custommenu_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}