<?php

class Magentothem_Vmegamenu_Model_Mysql4_Vmegamenu extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the vmegamenu_id refers to the key field in your database table.
        $this->_init('vmegamenu/vmegamenu', 'vmegamenu_id');
    }
}