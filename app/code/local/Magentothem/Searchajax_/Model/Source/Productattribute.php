<?php
class Magentothem_Searchajax_Model_Source_Productattribute
{
    public static $_productAttributes;

    public static function getProductAttributeList()
    {
        $attributesearch = array(
            array('title'=>'Name','code'=>'name','type'=>'varchar'),
            array('title'=>'Sku','code'=>'sku','type'=>'varchar'),
            array('title'=>'Color','code'=>'color','type'=>'varchar'),
            array('title'=>'Manufacturer','code'=>'manufacturer','type'=>'varchar'),
            array('title'=>'Brand','code'=>'computer_manufacturers','type'=>'varchar'),
            array('title'=>'Shoe Type','code'=>'shoe_type','type'=>'varchar'),
        );
        return $attributesearch;
    }

    

    public static function toOptionArray()
    {
        if(is_array(self::$_productAttributes)) return self::$_productAttributes;

        self::$_productAttributes = array();

        foreach(self::getProductAttributeList() as $id => $data)
            self::$_productAttributes[] = array(
                'value' => $data['code'],
                'label' => $data['title'].' ('.$data['code'].')'
            );

        return self::$_productAttributes;
    }
}