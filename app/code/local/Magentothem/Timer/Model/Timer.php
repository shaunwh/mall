<?php

class Magentothem_Timer_Model_Timer extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('timer/timer');
    }
}