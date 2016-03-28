<?php

class Magentothem_Timer_Model_Mysql4_Timer extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the timer_id refers to the key field in your database table.
        $this->_init('timer/timer', 'timer_id');
    }
}