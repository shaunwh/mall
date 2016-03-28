<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-9-16
 * Time: 下午4:14
 */

class App_Api2_Model_Blogpost extends Mage_Core_Model_Abstract{

    protected $_customer;

    protected function _construct(){
        $this->_init('api2/blogpost');
    }

    public function isLogin(){
        return true;
    }

    public function loadByField($field, $value)
    {
        $table  = $this->getMainTable();
        $where  = $this->_getReadAdapter()->quoteInto("$field = ?", $value);
        $select = $this->_getReadAdapter()
            ->select()
            ->from($table, array('blogpost_id'))
            ->where($where);
        $item   = $this->_getReadAdapter()->fetchOne($select);
        return $item;
    }

    public function _getCustomer()
    {
        if (is_null($this->_customer)) {
            /** @var $customer Mage_Customer_Model_Customer */
            $customer = Mage::getModel('customer/customer')->load($this->getApiUser()->getUserId());
            if (!$customer->getId()) {
                $this->_critical('Customer not found.', Mage_Api2_Model_Server::HTTP_INTERNAL_ERROR);
            }
            $this->_customer = $customer;
        }
        return $this->_customer;
    }
}