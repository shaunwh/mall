<?php

class Magentothem_Timer_Model_Mysql4_Timer_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('timer/timer');
    }
}