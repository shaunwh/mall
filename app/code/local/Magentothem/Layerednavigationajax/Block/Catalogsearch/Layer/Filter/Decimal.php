<?php

class Magentothem_Layerednavigationajax_Block_Catalogsearch_Layer_Filter_Decimal extends Mage_CatalogSearch_Block_Layer_Filter_Decimal {
      public function __construct()
    {
        parent::__construct();
        $enableModule = Mage::helper('layerednavigationajax/data')->getStoreConfigField('enabled');
        if($enableModule){
            $this->setTemplate('magentothem/layerednavigationajax/attribute.phtml');
        }
    }
}
