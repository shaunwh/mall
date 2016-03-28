<?php
class Magentothem_Installtemplate_Model_Installtemplate extends Mage_Core_Model_Abstract {

    public function _construct() {
	
        parent::_construct();
        $this->_init('installtemplate/installtemplate');
    }

    public function saveStaticBlock($store = NULL) {
        $staticData = Mage::helper('installtemplate/data')->getStaticBlockData();
        foreach ($staticData as $block) {
            $block['stores'] = $store;
            if (!Mage::helper('installtemplate/data')->haveBlockBefore($block['identifier'])) {
                Mage::getModel('cms/block')->setData($block)->save();
            } else {
                Mage::getModel('cms/block')->load($block['identifier'])->setStores($store)->save();
            }
        }
    }

    public function saveCmsPage($store = NULL) {
        $cmsPageData = Mage::helper('installtemplate/data')->getCmsPageData();
        foreach ($cmsPageData as $block) {
            $block['stores'] = $store;
            if (!Mage::helper('installtemplate/data')->haveBlockPageBefore($block['identifier'])) {
                Mage::getModel('cms/page')->setData($block)->save();
            } else {
                Mage::getModel('cms/page')->load($block['identifier'])->setStores($store)->save();
            }
        }
    }

    public function saveTemplateConfig(int $store) {
        $scope = ($store ? 'stores' : 'default');
		Mage::getConfig()->saveConfig('design/package/name', 'ma_sahara', $scope, $store);
        Mage::getConfig()->saveConfig('design/theme/default', 'ma_sahara_food4', $scope, $store);
        Mage::getConfig()->saveConfig('web/default/cms_home_page', 'ma_sahara_food4_home', $scope, $store);
		Mage::getConfig()->saveConfig('design/theme/default', 'ma_sahara_food4', 'default', 0);
        Mage::getConfig()->saveConfig('web/default/cms_home_page', 'ma_sahara_food4_home', 'default', 0);
		Mage::getConfig()->saveConfig('web/default/cms_no_route', 'no-route-custom', $scope, $store);
        Mage::getConfig()->saveConfig('web/default/cms_no_route', 'no-route-custom', 'default', 0);
    }
    
       
    public function saveBanner7() {
        $baner7Data = Mage::helper('installtemplate/data')->getBannerData(); 
        foreach($baner7Data as $banner) {
            if($banner){
                unset($banner['content']);
                $existed = $this->existBanner7($banner['banner7_id'],'banner7');
                if(!$existed) {
                    //insert
                    $this->_insert('banner7', $banner);
                } 
            }
        }
    }
    
     public function saveBrandSlider() {
        $brandsliderData = Mage::helper('installtemplate/data')->getBrandSliderData();
       
        foreach ($brandsliderData as $banner) {
            if ($banner) {
                unset($banner['content']);
                $existed = $this->existBrandSlider($banner['brandslider_id'], 'brandslider');
                if (!$existed) {
                    //insert
                    $this->_insert('brandslider', $banner);
                }
            }
        }
    }
    
     public function backupTemplateConfig(int $store) {
        $oldConfigData = Mage::helper('installtemplate/data')->getOldConfigData(); 
        $scope = ($store ? 'stores' : 'default');
        Mage::getConfig()->saveConfig('design/theme/default', $oldConfigData[0][0], $scope, $store);
        Mage::getConfig()->saveConfig('web/default/cms_home_page', $oldConfigData[0][1], $scope, $store);
		Mage::getConfig()->saveConfig('design/theme/default', $oldConfigData[0][0], $scope, 0);
        Mage::getConfig()->saveConfig('web/default/cms_home_page', $oldConfigData[0][1], $scope, 0);
    }

    public function deleteCmsPageBlock($key = NULL, $stores = NULL) {
        $model = Mage::getModel('cms/page');
        $model->load($key);
        $storesOld = $model->getStoreId();
        $storeNew = array();
        foreach ($storesOld as $storeId) {
            if (!in_array($storeId, $stores)) {
                $storeNew[] = $storeId;
            }
        }

        if (in_array(0, $stores)) {
            $model->delete();
        } else {
            $model->setStores($storeNew)->save();
        }
    }

    public function deleteStaticBlock($key = NULL, $stores = NULL) {
        $model = Mage::getModel('cms/block');
        $model->load($key);
        $storesOld = $model->getStoreId();
        $storeNew = array();
        foreach ($storesOld as $storeId) {
            if (!in_array($storeId, $stores)) {
                $storeNew[] = $storeId;
            }
        }

        if (in_array(0, $stores)) {
            $model->delete();
        } else {
            $model->setStores($storeNew)->save();
        }
    }
    
     public function _insert($table = NULL, $fields = NULL) {
        
        try {
            $connection = Mage::getSingleton('core/resource')
                    ->getConnection('core_write');
            $connection->beginTransaction();
            $connection->insert($table, $fields);
            $connection->commit();
            
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    public function existBanner7($id, $table) {
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $query = "SELECT * FROM $table where banner7_id=".$id;
        $results = $read->fetchAll($query);
        if($results) 
            return $results;
        return array();
    }
    
    public function existBrandSlider($id, $table) {
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $query = "SELECT * FROM $table where brandslider_id=".$id;
        $results = $read->fetchAll($query);
        if($results) 
            return $results;
        return array();
    }

}