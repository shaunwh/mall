<?php
/**
 * Created by PhpStorm.
 * User: shaun
 * Date: 15-10-8
 * Time: 下午1:45
 */
class App_Api_Model_Api extends Mage_Core_Model_Abstract{
    protected function _construct(){
        $this->_init('api/api');
    }
}