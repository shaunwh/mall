<?php
/**
 * Created by PhpStorm.
 * User: shaun
 * Date: 15-10-8
 * Time: 下午1:48
 */
class App_Api_Model_Resource_Mysql4_Api extends Mage_Core_Model_Mysql4_Abstract{
    protected function _construct(){
        $this->_init('api/api','user_id');

    }
}