<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table btc_btcwithdraw(btchistoryid int not null auto_increment, 
username varchar(100),
amount varchar(100),
btcaddress varchar(100),
status varchar(100),
oldbalance varchar(100),
newbalance varchar(100), 
datehistory varchar(100), 
primary key(btchistoryid));
SQLTEXT;

$installer->run($sql);

$installer->endSetup();
	 