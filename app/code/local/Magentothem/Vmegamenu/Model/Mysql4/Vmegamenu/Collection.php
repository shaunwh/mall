<?php

class Magentothem_Vmegamenu_Model_Mysql4_Vmegamenu_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('vmegamenu/vmegamenu');
    }
}