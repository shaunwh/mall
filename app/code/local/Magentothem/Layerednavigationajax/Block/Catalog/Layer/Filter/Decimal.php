<?php

class Magentothem_Layerednavigationajax_Block_Catalog_Layer_Filter_Decimal extends Mage_Catalog_Block_Layer_Filter_Decimal {
      public function __construct()
    {
        parent::__construct();
        if(Mage::getStoreConfig('layerednavigationajax/layerfiler_config/enabled')){
            $this->setTemplate('magentothem/layerednavigationajax/attribute.phtml');
        }
    }
}
