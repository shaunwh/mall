<?php
class Magentothem_Upsellslider_Model_Layout_Generate_Observer {
    /*
     * Get head block
     */
    private function __getHeadBlock() {
		$enabled = Mage::getStoreConfig('upsellslider/upsellslider_config/enabled');
		return Mage::getSingleton('core/layout')->getBlock('magentothem_upsellslider_head');
    }
}