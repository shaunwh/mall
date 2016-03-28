<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
try {
    $installer->run("
        CREATE TABLE IF NOT EXISTS {$this->getTable('blog/blog')} (
            `post_id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT ,
            `cat_id` smallint( 11 ) NOT NULL default '0',
            `title` varchar( 255 ) NOT NULL default '',
            `post_content` text NOT NULL ,
            `status` smallint( 6 ) NOT NULL default '0',
            `created_time` datetime default NULL ,
            `update_time` datetime default NULL ,
            `identifier` varchar( 255 ) NOT NULL default '',
            `user` varchar( 255 ) NOT NULL default '',
            `update_user` varchar( 255 ) NOT NULL default '',
            `meta_keywords` text NOT NULL ,
            `meta_description` text NOT NULL ,
            `comments` TINYINT( 11 ) NOT NULL,
            PRIMARY KEY ( `post_id` ) ,
            UNIQUE KEY `identifier` ( `identifier` )
        ) ENGINE = InnoDB DEFAULT CHARSET = utf8;

        CREATE TABLE IF NOT EXISTS {$this->getTable('blog/comment')} (
            `comment_id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT ,
            `post_id` smallint( 11 ) NOT NULL default '0',
            `comment` text NOT NULL ,
            `status` smallint( 6 ) NOT NULL default '0',
            `created_time` datetime default NULL ,
            `user` varchar( 255 ) NOT NULL default '',
            `email` varchar( 255 ) NOT NULL default '',
            PRIMARY KEY ( `comment_id` )
        ) ENGINE = InnoDB DEFAULT CHARSET = utf8;

        CREATE TABLE IF NOT EXISTS {$this->getTable('blog/cat')} (
            `cat_id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT ,
            `title` varchar( 255 ) NOT NULL default '',
            `identifier` varchar( 255 ) NOT NULL default '',
            `sort_order` tinyint ( 6 ) NOT NULL ,
            `meta_keywords` text NOT NULL ,
            `meta_description` text NOT NULL ,
            PRIMARY KEY ( `cat_id` )
        ) ENGINE = InnoDB DEFAULT CHARSET = utf8;

        INSERT INTO {$this->getTable('blog/cat')} (`cat_id`, `title`, `identifier`) VALUES (NULL, 'News', 'news');

        CREATE TABLE IF NOT EXISTS {$this->getTable('blog/store')} (
            `post_id` smallint(6) unsigned,
            `store_id` smallint(6) unsigned
        ) ENGINE = InnoDB DEFAULT CHARSET = utf8;

        CREATE TABLE IF NOT EXISTS {$this->getTable('blog/cat_store')} (
            `cat_id` smallint(6) unsigned,
            `store_id` smallint(6) unsigned
        ) ENGINE = InnoDB DEFAULT CHARSET = utf8;

        CREATE TABLE IF NOT EXISTS {$this->getTable('blog/post_cat')} (
            `cat_id` smallint(6) unsigned,
            `post_id` smallint(6) unsigned
        ) ENGINE = InnoDB DEFAULT CHARSET = utf8;

        ALTER TABLE {$this->getTable('blog/blog')} ADD `tags` TEXT NOT NULL;
        ALTER TABLE {$this->getTable('blog/blog')} ADD `short_content` TEXT NOT NULL;
        ALTER TABLE {$this->getTable('blog/blog')} ADD `thumbnailimage` varchar(255) NOT NULL DEFAULT '';
    ");
} catch (Exception $e) {
    Mage::logException($e);
}

try {
    $installer->run("
        CREATE TABLE IF NOT EXISTS {$this->getTable('blog/tag')} (
            `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
            `tag` VARCHAR( 255 ) NOT NULL ,
            `tag_count` INT( 11 ) NOT NULL DEFAULT '0',
            `store_id` TINYINT( 4 ) NOT NULL ,
            INDEX (`tag`, `tag_count`, `store_id`)
        ) ENGINE = InnoDB DEFAULT CHARSET = utf8;
    ");
	
	$installer->run("
		ALTER TABLE {$this->getTable('blog/blog')} DROP `cat_id`;
	");
	
} catch (Exception $e) {
    Mage::logException($e);
}

$installer->endSetup();