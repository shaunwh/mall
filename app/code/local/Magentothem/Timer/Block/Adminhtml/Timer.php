<?php

class Magentothem_Timer_Block_Adminhtml_Timer extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_timer';
    $this->_blockGroup = 'timer';
    $this->_headerText = Mage::helper('timer')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('timer')->__('Add Item');
    parent::__construct();
  }
}