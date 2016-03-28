<?php

class Magentothem_Searchajax_Model_Searchajax extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('searchajax/searchajax');
    }
}