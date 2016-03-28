<?php

class Magentothem_Ajaxlogin_Block_Login extends Mage_Customer_Block_Form_Login
{

    const SYSTEM_FOLDER_CONFIG_IMAGE        = "magentothem/";
    const SYSTEM_PATH_CONFIG_IMAGE          = "ajax_login/general_info/image_upload";
    const SYSTEM_PATH_CONFIG_USE_REDIRECT   = "ajax_login/general_info/enable_rd";

    protected function _prepareLayout()
    {
        return $this;
    }

    /**
     * Retrieve form posting url
     *
     * @return string
     */
    public function getPostActionUrl()
    {
        return $this->helper('magentothem_ajaxlogin')->loginPostUrl();
    }

    public function getRegisterUrl()
    {
        return $this->helper('magentothem_ajaxlogin')->registerUrl();
    }

    public function getLogoutActionUrl()
    {
        return $this->helper('magentothem_ajaxlogin')->logoutUrl();
    }

    public function getAjaxLoadImage()
    {
        return Mage::getBaseUrl('media').self::SYSTEM_FOLDER_CONFIG_IMAGE.Mage::getStoreConfig(self::SYSTEM_PATH_CONFIG_IMAGE);
    }

    public function isUseRedirect()
    {
        $config = Mage::getStoreConfig(self::SYSTEM_PATH_CONFIG_USE_REDIRECT);
        if($config == "1") $check = true;
        else $check = false;
        return $check;
    }

}