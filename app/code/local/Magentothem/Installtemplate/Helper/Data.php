<?php
class Magentothem_Installtemplate_Helper_Data extends Mage_Core_Helper_Abstract
{
    
   public function getContentFromXmlFile($xmlPath = NULL, $node= NULL) {
        $data = new Varien_Simplexml_Config($xmlPath);
        $statickBlockData = array();
        foreach ($data->getNode($node) as $key => $node) {
            foreach ($node as $child) {
                $array = (array) $child;
                $content = (string) $array['content'];
                $array['content'] = $content;
                $statickBlockData[] = $array;
            }
        }
        if ($statickBlockData)
            return $statickBlockData;
        return array();
    } 
    
   public function getStaticBlockData() {
        $xmlPath = Mage::getBaseDir('code') . '/local/Magentothem/Installtemplate/Block/Xml/data_static_blocks.xml';
        $statickBlockData = $this->getContentFromXmlFile($xmlPath, 'blocks');
        if ($statickBlockData)
            return $statickBlockData;
        return array();
    }
    
    public function getCmsPageData() {
        
        $xmlPath = Mage::getBaseDir('code') . '/local/Magentothem/Installtemplate/Block/Xml/data_resources.xml';
        $statickBlockData = $this->getContentFromXmlFile($xmlPath, 'resources');
        if ($statickBlockData)
            return $statickBlockData;
        return array();

    }
    
  
    public function haveBlockBefore($identifier = NULL) {
        //$stores = implode(',', $stores);
        $exist = Mage::getModel('cms/block')->getCollection()
                ->addFieldToFilter('identifier', array('eq' => $identifier))
                ->load();
        if (count($exist))
            return true;
        return false;
    }
    
    public function haveBlockPageBefore($identifier = NULL) {
        //$stores = implode(',', $stores);
        $exist = Mage::getModel('cms/page')->getCollection()
                ->addFieldToFilter('identifier', array('eq' => $identifier))
                ->load();
        if (count($exist))
            return true;
        return false;
    }
    
    
    
    public function getNodeDataFromBlock($node = 'identifier', $blocks = array()) {
        
        $array_identifier = array();
        foreach($blocks as $block) {
            $identifier = $block[$node];
            $array_identifier[] = $identifier;
        
        }
        if($array_identifier)
            return $array_identifier;
        return array();
        
    }
    
    public function getNodeDataFromStaticBlock() {
       if($this->getNodeDataFromBlock('identifier', $this->getStaticBlockData())) 
               return $this->getNodeDataFromBlock('identifier', $this->getStaticBlockData());
       return array();
    }
     
      public function getNodeDataFromCmsPageBlock() {
       if($this->getNodeDataFromBlock('identifier', $this->getCmsPageData())) 
               return $this->getNodeDataFromBlock('identifier', $this->getCmsPageData());
       return array();
    }
    
    public function getOldConfigData(){
        $oldConfig = array(
            array(
                0=> 'default',
                1=> 'home'
            )
        );
        return $oldConfig;
    }
    
     public function getBannerData() {

        $xmlPath = Mage::getBaseDir('code') . '/local/Magentothem/Installtemplate/Block/Xml/banner7.xml';
        $statickBlockData = $this->getContentFromXmlFile($xmlPath, 'records');

        if ($statickBlockData)
            return $statickBlockData;
        return array();
    }

    public function getBrandSliderData() {

        $xmlPath = Mage::getBaseDir('code') . '/local/Magentothem/Installtemplate/Block/Xml/brandslider.xml';
        $statickBlockData = $this->getContentFromXmlFile($xmlPath, 'records');

        if ($statickBlockData)
            return $statickBlockData;
        return array();
    }
	
	 public function getAllStore() {
        $stores = Mage::app()->getStores();
        $storeIds = array();
	  	$storeIds[]= 0;
        foreach ($stores as $_store) {
			
				$storeIds[] = $_store->getId();
		}
        return $storeIds;
    }

}