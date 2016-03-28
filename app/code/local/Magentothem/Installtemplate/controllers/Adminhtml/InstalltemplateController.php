<?php
class Magentothem_Installtemplate_Adminhtml_InstalltemplateController extends Mage_Adminhtml_Controller_action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('installtemplate/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

        return $this;
    }

    public function indexAction() {
        $this->newAction();
    }

    public function editAction() {


        $this->loadLayout();
        $this->_setActiveMenu('installtemplate/items');

        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addContent($this->getLayout()->createBlock('installtemplate/adminhtml_installtemplate_edit'))
                ->_addLeft($this->getLayout()->createBlock('installtemplate/adminhtml_installtemplate_edit_tabs'));

        $this->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            $action = trim($data['action']);
			$stores = array();
            $stores = $data['store_ids'];
			if(!$stores ) { $stores = array(0=>0); }
			try {

                if ($action == 'install') {
                    //install configuration 
					if($stores[0]==0)  {
						$storeConfigs = Mage::helper('installtemplate/data')->getAllStore();
					} else {
						$storeConfigs = $stores; 
					}
                    foreach ($storeConfigs as $store_id) {
                        Mage::getModel('installtemplate/installtemplate')->saveTemplateConfig($store_id);
                    }
                    //install static block 
                    Mage::getModel('installtemplate/installtemplate/')->saveStaticBlock($stores);
                    //install cms page
                    Mage::getModel('installtemplate/installtemplate/')->saveCmsPage($stores);
                    //install banner7 data
                    Mage::getModel('installtemplate/installtemplate')->saveBanner7();
                    //install brandslider data 
                    Mage::getModel('installtemplate/installtemplate')->saveBrandSlider();

                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('installtemplate')->__('Template was successfully saved'));
                } else if ($action == 'uninstall') {
                    //uninstall configuration 
					if($stores[0]==0)  {
						$storeConfigs = Mage::helper('installtemplate/data')->getAllStore();
					} else {
						$storeConfigs = $stores; 
					}
					
                    foreach ($storeConfigs as $store_id) {
                        Mage::getModel('installtemplate/installtemplate')-> backupTemplateConfig($store_id);
                    }
                    //uninstall static block
                    $identityFromStatic = Mage::helper('installtemplate')->getNodeDataFromStaticBlock();
                    foreach ($identityFromStatic as $keyStatic) {
                        Mage::getModel('installtemplate/installtemplate/')->deleteStaticBlock($keyStatic, $stores);
                    }

                    //uninstall cms page block
                    $identityFromCmsPage = Mage::helper('installtemplate')->getNodeDataFromCmsPageBlock();
                    foreach ($identityFromCmsPage as $keyPage) {
                        Mage::getModel('installtemplate/installtemplate/')->deleteCmsPageBlock($keyPage,$stores);
                    }
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('installtemplate')->__('Template was succesfully uninstalled'));
                }

                $this->_redirect('*/*/edit');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('installtemplate')->__('Unable to find item to save'));
                $this->_redirect('*/*/edit');
            }
        }
    }

}