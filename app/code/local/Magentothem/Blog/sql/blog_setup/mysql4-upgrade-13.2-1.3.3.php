<?php

$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('magentothem_blog')} ADD `thumbnailimage` varchar(255) NOT NULL DEFAULT '' AFTER `short_content`;
");

$installer->endSetup();
