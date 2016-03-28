<?php
class Magentothem_Searchajax_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        
           $this->getResponse()->setBody($this->getLayout()->createBlock('searchajax/result')->toHtml());
    }
    
    public function testAction() {
//        echo Mage::getSingleton('catalog/layer')->getCurrentCategory()->getEntityId();
//        $at = $this->getCurrentlySelectedCategoryId();
//        echo "<pre>"; print_r($at);
//        die;
            $storeId = Mage::app()->getStore()->getStoreId();
           $_productCollection = Mage::getModel('catalog/product')->setStoreId($storeId)->getCollection();
           $visibility = array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH, Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
           $cateId = 28;
           $catagory_model = Mage::getModel('catalog/category')->load($cateId);
           $text = '76';
           $attribute = trim(Mage::getStoreConfig('searchajax/searchajax_config/attributeids'));
           $attribute ='computer_manufacturers';
           //Mage::log($attribute.'--'.$text, null, 'search.log');
           $_productCollection
           //->addCategoryFilter($catagory_model)
              ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
            ->addAttributeToFilter($attribute, array('like'=>'%'.$text.'%'))
            ->setVisibility($visibility)
            ->addUrlRewrite()
            ->load();
           echo "<pre>"; print_r($_productCollection->getData());
    }
    
  
}