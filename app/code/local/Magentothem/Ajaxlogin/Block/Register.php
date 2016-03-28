<?php

class Magentothem_Ajaxlogin_Block_Register extends Mage_Customer_Block_Form_Register
{

    protected function _prepareLayout()
    {
        return $this;
    }

    public function getAjaxLoadImage()
    {
        return Mage::getBaseUrl('media').Magentothem_Ajaxlogin_Block_Login::SYSTEM_FOLDER_CONFIG_IMAGE.Mage::getStoreConfig(Magentothem_Ajaxlogin_Block_Login::SYSTEM_PATH_CONFIG_IMAGE);
    }

}