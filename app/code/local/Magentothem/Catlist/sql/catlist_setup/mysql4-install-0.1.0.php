<?php
$this->startSetup();

$this->addAttribute('catalog_category', 'catlist', array(
    'group'         => 'General Information',
    'input'         => 'select',
    'type'          => 'varchar',
    'label'         => 'Categories List',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined'  => 1,
    'source'   => 'eav/entity_attribute_source_boolean',
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$this->addAttribute('catalog_category', 'catlist_thumbnail', array(
    'group'                    => 'General Information',
    'label'                    => 'Category List Thumbnail',
    'input'                    => 'image',
    'type'                     => 'varchar',
    'backend'                  => 'catalog/category_attribute_backend_image',
    'global'                   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'                  => true,
    'required'                 => false,
    'user_defined'             => true,
    'order'                    => 21
));

$this->endSetup();