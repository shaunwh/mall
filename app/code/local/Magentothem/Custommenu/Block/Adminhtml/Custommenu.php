<?php
class Magentothem_Custommenu_Block_Adminhtml_Custommenu extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_custommenu';
    $this->_blockGroup = 'custommenu';
    $this->_headerText = Mage::helper('custommenu')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('custommenu')->__('Add Item');
    parent::__construct();
  }
}