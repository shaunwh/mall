<?php

class Magentothem_Quickview_IndexController extends Mage_Core_Controller_Front_Action
{

    protected function initProduct($productId) {

        $product = null;

        if($productId) {
            $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($productId);

            Mage::register('current_product', $product);
            Mage::register('product', $product);

        }

        return $product;
    }

    public function viewAction() {

        $path = $this->getRequest()->getParam('path');

        //Get object by url rewrite
        if($path) {
            $oRewrite = Mage::getModel('core/url_rewrite')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->loadByRequestPath($path);

            $productId = $oRewrite->getProductId();

            $product = $this->initProduct($productId);

            if($product) {

                Mage::dispatchEvent('catalog_controller_product_view', array('product'=>$product));

                if ($this->getRequest()->getParam('options')) {
                    $notice = $product->getTypeInstance(true)->getSpecifyOptionMessage();
                    Mage::getSingleton('catalog/session')->addNotice($notice);
                }

                Mage::getSingleton('catalog/session')->setLastViewedProductId($product->getId());

                $this->getLayout()->getUpdate()->addHandle(array(
                    'default',
                    'catalog_product_view',
                    'PRODUCT_TYPE_' . $product->getTypeId(),
                    'PRODUCT_' . $product->getId()
                ));

                $this->loadLayout();
                $this->getLayout()->removeOutputBlock('root')->addOutputBlock('content');
                $this->renderLayout();
            } else {
                $this->_forward('noRoute');
                return;
            }

        } else {
            $this->_forward('noRoute');
            return;
        }

    }
}