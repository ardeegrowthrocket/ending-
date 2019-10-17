<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table btcuser(btcid int not null auto_increment, username varchar(100), 
password varchar(100),
btc_address varchar(100),
email varchar(100),
balance varchar(100),
primary key(btcid));
		
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 