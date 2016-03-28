<?php
class Magentothem_Vmegamenu_Block_Adminhtml_Vmegamenu extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_vmegamenu';
    $this->_blockGroup = 'vmegamenu';
    $this->_headerText = Mage::helper('vmegamenu')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('vmegamenu')->__('Add Item');
    parent::__construct();
  }
}