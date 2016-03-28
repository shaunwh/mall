<?php

class Magentothem_Custommenu_Model_Custommenu extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('custommenu/custommenu');
    }
}