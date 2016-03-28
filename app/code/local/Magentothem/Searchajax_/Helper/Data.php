<?php

class Magentothem_Searchajax_Helper_Data extends Mage_Core_Helper_Abstract {
    public function getProductAttributes() {
       $entityType = Mage::getModel('catalog/product')->getResource()->getEntityType();
        $entityTypeId=$entityType->getId();
        $attributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($entityTypeId)
            ->getData();
        $result=array();
        $result[]=array('value'=>'','label'=>$this->__('Choose an attribute'));
        foreach($attributeInfo as $_key=>$_value)
        {
            if($_value['is_global'] != "1"  || $_value['is_visible']!="1"){
                // continue;
            }
            if(isset($_value['frontend_label'])&&($_value['frontend_label']!='')){
                $result[]=array('value'=>$_value['attribute_code'],'label' => $_value['frontend_label']);
            }else{
                $result[]=array('value'=>$_value['attribute_code'],'label' => $_value['attribute_code']);
            }

        }
        return $result;
    }
}