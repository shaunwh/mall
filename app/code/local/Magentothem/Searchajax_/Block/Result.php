<?php

class Magentothem_Searchajax_Block_Result extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getSearchajax() {
        if (!$this->hasData('searchajax')) {
            $this->setData('searchajax', Mage::registry('searchajax'));
        }
        return $this->getData('searchajax');
    }

    public function getProductIds($storeId = 1) {
        $_productCollection = Mage::getModel('catalog/product')->setStoreId($storeId)->getCollection();
        $visibility = array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH, Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
        $text = trim($this->getRequest()->getParam('q'));
        $attribute = trim(Mage::getStoreConfig('searchajax/searchajax_config/attributeids'));
        $cateId = $this->getRequest()->getParam('cate_id');
        $catagory_model = Mage::getModel('catalog/category')->load($cateId);
        if ($this->attributeHaveOption($attribute)) {
            $text = $this->getAttributeByLabel($attribute, $text);
            if (!$text)
                return array();
        }
        Mage::log($text.'---'.$attribute, null,'search.log');
        $_productCollection
                ->addCategoryFilter($catagory_model)
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                ->addAttributeToFilter($attribute, array('like' => '%' . $text . '%'))
                ->setVisibility($visibility)
                ->addUrlRewrite()
                ->load();
        $productIds = array();
        foreach ($_productCollection as $product) {
            $productIds[] = $product->getId();
        }
        //Mage::log($productIds, null,'search.log');
        if ($productIds)
            return $productIds;
        return array();
    }

    public function getProductInfo($storeId = 1) {
        
        $productIds = $this->getProductIds(1);
        $productIds = implode(',', $productIds);
      
        if (!$productIds)
            return false;
        $_productCollection = Mage::getModel('catalog/product')->setStoreId($storeId)->getCollection();
        $_productCollection->getSelect()->where('`e`.`entity_id` IN (' . $productIds . ')');
        $visibility = array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH, Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
        $_productCollection
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                ->setVisibility($visibility)
                ->addUrlRewrite()
                ->load();

        $productInfo = array();
        if ($_productCollection) {
            foreach ($_productCollection as $_product) {
                $productInfo[$_product->getId()] = $_product;
            }
        }
        if ($productInfo)
            return $productInfo;
        return array();
    }

    function getAttributeByLabel($attribute = NULL, $value = NULL) {
        $product = Mage::getModel('catalog/product');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($product->getResource()->getTypeId())
                ->addFieldToFilter('attribute_code', $attribute);
        $attribute = $attributes->getFirstItem()->setEntity($product->getResource());
        $collection = $attribute->getSource()->getAllOptions(false);
        foreach ($collection as $brandItem) {
            if (preg_match("/" . $value . "/i", $brandItem['label'])) {
                return $brandItem['value'];
                break;
            }
        }
    }
    
     function getAttributeByValue($attribute = NULL, $value = NULL) {
        $product = Mage::getModel('catalog/product');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($product->getResource()->getTypeId())
                ->addFieldToFilter('attribute_code', $attribute);
        $attribute = $attributes->getFirstItem()->setEntity($product->getResource());
        $collection = $attribute->getSource()->getAllOptions(false);
        foreach ($collection as $brandItem) {
            if ($brandItem['value']== trim($value)) {
                return $brandItem['label'];
                break;
            }
        }
    }

    function attributeHaveOption($attribute = NULL) {
        $product = Mage::getModel('catalog/product');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($product->getResource()->getTypeId())
                ->addFieldToFilter('attribute_code', $attribute);
        $attribute = $attributes->getFirstItem()->setEntity($product->getResource());
        $collection = $attribute->getSource()->getAllOptions(false);
        if ($collection)
            return true;
        return false;
    }

    public function _toHtml() {
        $params = $this->getRequest()->getParams();
        $search = strtolower(trim($params['q']));
        $result = "<ul id ='search_complete' style ='border:1px; background:white'>";
        $currencies = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();
        $products = $this->getProductInfo(Mage::app()->getStore()->getStoreId());
        $attribute = trim(Mage::getStoreConfig('searchajax/searchajax_config/attributeids'));
        if ($products) {
            foreach ($products as $product) {
                $pimage = Mage::helper('catalog/image')->init($product, 'small_image')->resize(55);
                $result .='<li>';
                $result .='<div style ="float:left;" ><img src="' . $pimage . '"/></div>';
                $result .='<div class ="product_info">';
                $product_name = str_replace($search, '<span class="highlight_search">' . $search . '</span>', strtolower($product->getName()));
                $product_sku = str_replace($search, '<span class="highlight_search">' . $search . '</span>', strtolower($product->getSku()));
                $product_price = str_replace($search, '<span class="highlight_search">' . $search . '</span>', strtolower($product->getFinalPrice()));
                $product_color = str_replace($search, '<span class="highlight_search">' . $search . '</span>', strtolower($this->getAttributeByValue($attribute, $product->getColor())));
               // $product_manufacturer = str_replace($search, '<span class="highlight_search">'.$search.'</span>', strtolower($this->getAttributeByValue($attribute, $product->getManufacturer())));
                //Mage::log($search, null, 'search.log');
                $result .= "<div class ='product_name'>Sku:" . $product_sku . "</div>";
                $result .= "<div class ='product_name'>Color:" . $product_color . "</div>";
               // $result .= "<div class ='product_name'>Manufacturer:".$product_manufacturer."</div>";
                $result .= "<div class ='product_name'>Name: <a href ='" . $product->getProductUrl() . "'> " . $product_name . "</a></div>";
                $result .= "<div class ='product_price'>Price: " . $product_price . ' ' . $currencies . '</div>';
                $result .='</div>';
                $result .='</li>';
            }
        } else {
            $result .='<div class ="error">' . $this->__('No  product was found') . '</div>';
        }
        $result .='</ul>';
        return $result;
        parent::_toHtml();
    }

}