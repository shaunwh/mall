<?php

if (!class_exists('Magentothem_Blog_Block_Product_ToolbarCommon')) {
    if (Mage::helper('blog')->isMobileInstalled()) {
        class Magentothem_Blog_Block_Product_ToolbarCommon extends Magentothem_Mobile_Block_Catalog_Product_List_Toolbar
        {
        }
    } else {
        class Magentothem_Blog_Block_Product_ToolbarCommon extends Mage_Catalog_Block_Product_List_Toolbar
        {
        }
    }
}