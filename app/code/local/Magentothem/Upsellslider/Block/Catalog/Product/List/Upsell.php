<?php
class Magentothem_Upsellslider_Block_Catalog_Product_List_Upsell extends Mage_Catalog_Block_Product_List_Upsell
{
    const XML_PATH_ITEM_LIMIT   = 'upsellslider/upsellslider_config/qty';
    public function getItemLimit($type = '')
    {
        return Mage::getStoreConfig(self::XML_PATH_ITEM_LIMIT);
    }
    /**
     * Get relevant path to template
     *
     * @return string
     */
    public function getTemplate()
    {
        return 'magentothem/upsellslider/upsell.phtml';
    }
	
	public function getConfig($att) 
	{
		$config = Mage::getStoreConfig('upsellslider');
		if (isset($config['upsellslider_config']) ) {
			$value = $config['upsellslider_config'][$att];
			return $value;
		} else {
			throw new Exception($att.' value not set');
		}
	}
}