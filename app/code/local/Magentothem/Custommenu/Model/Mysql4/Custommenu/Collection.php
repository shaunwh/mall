<?php

class Magentothem_Custommenu_Model_Mysql4_Custommenu_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('custommenu/custommenu');
    }
}