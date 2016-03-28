<?php

class Magentothem_Googlerichsnippet_Block_Snippet extends Mage_Core_Block_Template
{

    const XML_PATH_ENABLE_SNIPPET = "google_snippets/general_info/enable_rs";
    const XML_PATH_ENABLE_DESCRIPTION = "google_snippets/general_info/enable_describe";
    const XML_PATH_ENABLE_RATING = "google_snippets/general_info/enable_rate";
    const XML_PATH_ENABLE_PRICE = "google_snippets/general_info/enable_price";

    private $_result;

    public function isEnableModule()
    {
        $this->_result = false;
        $config = Mage::getStoreConfig(self::XML_PATH_ENABLE_SNIPPET);
        if($config == "1") {
            $this->_result = true;
        }

        return $this->_result;
    }

    public function isEnableRating()
    {
        $this->_result = false;
        $config = Mage::getStoreConfig(self::XML_PATH_ENABLE_RATING);
        if($config == "1") {
            $this->_result = true;
        }

        return $this->_result;
    }

    public function isEnablePrice()
    {
        $this->_result = false;
        $config = Mage::getStoreConfig(self::XML_PATH_ENABLE_PRICE);
        if($config == "1") {
            $this->_result = true;
        }

        return $this->_result;
    }

    public function getReviewSummary($productId) {

        $summaryData = null;
        $storeId = Mage::app()->getStore()->getId();
        if($productId) {
            $summaryData = Mage::getModel('review/review_summary')
                ->setStoreId($storeId)
                ->load($productId);
        }

        return $summaryData;

    }

}