<?php
class Magentothem_Catlist_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getListCfg($cfg)
	{
        $config = Mage::getStoreConfig('catlist/list_config');
        if(isset($config[$cfg])) return $config[$cfg];
	}

}