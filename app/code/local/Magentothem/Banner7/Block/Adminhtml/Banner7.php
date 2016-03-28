<?php
/*------------------------------------------------------------------------
# Websites: http://www.magentothem.com/
-------------------------------------------------------------------------*/ 
class Magentothem_Banner7_Block_Adminhtml_Banner7 extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_banner7';
    $this->_blockGroup = 'banner7';
    $this->_headerText = Mage::helper('banner7')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('banner7')->__('Add Item');
    parent::__construct();
  }
}