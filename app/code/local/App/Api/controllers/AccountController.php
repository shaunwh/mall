<?php
/**
 * Created by PhpStorm.
 * User: shaun
 * Date: 2016/3/16
 * Time: 15:37
 */
require_once 'Mage/Customer/controllers/AccountController.php';
class App_Api_AccountController extends Mage_Customer_AccountController{
    public function _newCustomer(){
        return Mage::getModel('customer/customer');
    }

    public function createPost1Action(){
        $paras = $this->getRequest()->getParams();
        //$paras = array("name" => "qwe","email" => "qwe@126.com","password" => "123456","head" => "this is head test","phone" => "1554434332333");
        if(empty($paras['email']) || empty($paras['name']) || empty($paras['password'])){
            echo Zend_Json::encode(array("success" => false, "data" => "邮箱、用户名、密码不能为空"));return;
        }
        $cus_model = $this->_newCustomer();
        $customer = $cus_model->getCollection()
            ->addAttributeToFilter('email',$paras['email'])
            ->getFirstItem();
        if($customer->getData('entity_id')){
            echo Zend_Json::encode(array("success" => false, "data" => "该邮箱已经注册"));return;
        }else{
            try{
                $customer = $this->_newCustomer();
                $customer->setGroupId(1);
                $customer->setEmail($paras['email']);
                $customer->setFirstname($paras['name']);
                $customer->setLastname($paras['name']);
                $customer->setPassword($paras['password']);
                //$customer->setHead($paras['head']);
                //$customer->setPhone($paras['phone']);
                $customer->setConfirmation(null);
                $customer->save();
                $data = $customer->getData();
                if(!empty($data)){
                    $session = $this->_getSession();
                    $session->login($paras['email'],$paras['password']);
                    //$session->setCustomerAsLoggedIn($customer);
                    //$session->renewSession();
                    echo Zend_Json::encode(array("success" => true, "data" => $data));return;
                }else{
                    echo Zend_Json::encode(array('success' => false, 'data' => '注册失败，请联系管理员'));return;
                }
            }catch (Exception $e){
                echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
            }

        }
    }

    public function createPostAction()
    {
        $paras = $this->getRequest()->getParams();
        if($paras['firstname'] == ''){
            $errors[] = Mage::helper('customer')->__('姓名不能为空.');
        }
        if($paras['email'] == ''){
            $errors[] = Mage::helper('customer')->__('邮箱不能为空.');
        }
        if($paras['phone'] == 'phone'){
            $errors[] = Mage::helper('customer')->__('电话不能为空.');
        }
        if($paras['password'] == ''){
            $errors[] = Mage::helper('customer')->__('密码不能为空');
        }
        if($paras['password'] != $paras['confirmation']){
            $errors[] = Mage::helper('customer')->__('密码不匹配');
        }

        if(!empty($errors)){
            $this->_addSessionError($errors);
        }else{
            try{
                $customer = $this->_newCustomer();
                $customer->setGroupId(1);
                $customer->setEmail($paras['email']);
                $customer->setFirstname($paras['firstname']);
                $customer->setLastname($paras['firstname']);
                $customer->setPassword($paras['password']);
                //$customer->setHead($paras['head']);
                $customer->setPhone($paras['phone']);
                $customer->setConfirmation(null);
                $customer->save();
                $session = $this->_getSession();
                $session->login($paras['email'],$paras['password']);
                $this->_redirect('*/*/');
            }catch (Exception $e){
                $errors[] = $e->getMessage();
                $this->_addSessionError($errors);
            }

        }
    }

}