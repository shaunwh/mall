<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-9-16
 * Time: 下午5:14
 */

class App_Api2_Model_Resource_Mysql4_Blogpost extends Mage_Core_Model_Mysql4_Abstract{
    protected function _construct(){
        $this->_init('api2/blogpost','blogpost_id');

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
}