<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table btc_cmsmanager(cms_id int not null auto_increment, 
title varchar(100), 
content text, 
primary key(cms_id));
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 