<?php

class Magentothem_Vmegamenu_Model_Vmegamenu extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('vmegamenu/vmegamenu');
    }
}