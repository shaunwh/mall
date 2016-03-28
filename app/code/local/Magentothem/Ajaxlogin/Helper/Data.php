<?php
/**
 * Created by JetBrains PhpStorm.
 * User: My PC
 * Date: 04/12/2014
 * Time: 16:26
 * To change this template use File | Settings | File Templates.
 */ 
class Magentothem_Ajaxlogin_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function ajaxLoginUrl()
    {
        return Mage::getUrl('ajaxlogin/login/index/');
    }

    public function loginPostUrl()
    {
        return Mage::getUrl('ajaxlogin/login/loginPost/');
    }

    public function registerUrl()
    {
        return Mage::getUrl('ajaxlogin/register/index');
    }

    public function logoutUrl()
    {
        return Mage::getUrl('ajaxlogin/login/logout/');
    }

}