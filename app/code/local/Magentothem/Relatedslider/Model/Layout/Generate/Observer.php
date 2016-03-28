<?php
class Magentothem_Relatedslider_Model_Layout_Generate_Observer {
    private function __getHeadBlock() {
        return Mage::getSingleton('core/layout')->getBlock('magentothem_relatedslider_head');
		
    }
}
