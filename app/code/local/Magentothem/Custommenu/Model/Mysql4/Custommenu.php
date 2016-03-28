<?php

class Magentothem_Custommenu_Model_Mysql4_Custommenu extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the custommenu_id refers to the key field in your database table.
        $this->_init('custommenu/custommenu', 'custommenu_id');
    }
}