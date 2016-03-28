<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('timer')};
CREATE TABLE {$this->getTable('timer')} (
  `timer_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `content` text NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");
	
$installer = new Mage_Eav_Model_Entity_Setup('core_setup');


$installer = new Mage_Eav_Model_Entity_Setup('core_setup');


$installer->addAttribute('catalog_product', 'timershow', array(
        'type'              => 'varchar',
		'input'             => 'boolean',
		'backend'           => 'eav/entity_attribute_backend_array',
		'frontend'          => '',
		'source'            => '',
		'label'             => 'Show Price Count Down ',
        'group'				=> 'Prices',
		'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
		'visible'           => true,
		'required'          => false,
		'user_defined'      => true,
		'searchable'        => false,
		'filterable'        => false,
		'comparable'        => false,
		'visible_on_front'  => false,
		'default'           => 1,
		'visible_in_advanced_search' => false,
		'apply_to'          => 'simple,configurable,bundle,virtual,downloadable',  
		'unique'            => false,
    ));
	
$installer->endSetup(); 