<?php

class Magentothem_Ajaxcartsuper_Model_Ajaxcartsuper extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('ajaxcartsuper/ajaxcartsuper');
    }
}