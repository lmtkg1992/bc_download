<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer->startSetup();

$installer->run("
    CREATE TABLE `{$this->getTable('extradownload_link')}` (
      `link_id` int(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT ,
      `product_id` int(10) UNSIGNED NOT NULL DEFAULT '0' ,
      `sort_order` int(10) UNSIGNED NOT NULL DEFAULT '0' ,
      `number_of_downloads` int(11) DEFAULT NULL ,
      `is_visible` smallint(5) UNSIGNED NOT NULL DEFAULT '0' ,
      `link_url` varchar(255) DEFAULT NULL ,
      `link_file` varchar(255) DEFAULT NULL ,
      `link_type` varchar(20) DEFAULT NULL
    );

    CREATE TABLE `{$this->getTable('extradownload_link_title')}` (
      `title_id` int(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
      `link_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Link ID',
      `store_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Store ID',
      `title` varchar(255) DEFAULT NULL COMMENT 'Title'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Link Title Table';


");
$installer->endSetup();

?>