<?php

class Magentothem_Timer_Block_View extends Mage_Catalog_Block_Product_View
{
    protected function _prepareLayout()
    {
        $block = $this->getLayout()->getBlock('product.info.addtocart');
        if ($block) {
            $block->setTemplate('magentothem/timer/view.phtml');
        }
    }
}
?>