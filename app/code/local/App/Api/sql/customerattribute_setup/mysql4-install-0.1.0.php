<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-9-25
 * Time: 上午9:55
 */

$installer = $this;
$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$entityTypeId     = $setup->getEntityTypeId('customer');
$attributeSetId   = $setup->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$setup->addAttribute('customer', 'phone', array(
    'input'         => 'text',
//    'type'          => 'int',
    'type'          => 'varchar',
    'label'         => 'Some textual description',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
));

$setup->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'phone',
    '999'  //sort_order
);

$oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'phone');
$oAttribute->setData('used_in_forms', array('adminhtml_customer'));
$oAttribute->save();

$installer->endSetup();