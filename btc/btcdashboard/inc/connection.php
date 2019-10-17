<?php
error_reporting(0);
session_start();
mysql_connect("localhost","root","");
mysql_select_db("btc");


define("SUPER_APIKEY","6796f6b7-9fa2-4734-9aac-19462d4e74d5");
define("WEBSITENAME","BITCOINPRO");
define("BTC_GUID","55e1936e-3900-4a3e-b802-636c0d0f4d21");
define("BTC_PASS","reuusx6gnnlj");
define("BTC_XPUB", "xpub6Bp4491TTdTdKXaVKw3hEBXWGQMzaFfycauKNYq1AZvkTJYsGbZ1SKc2vccSHhRiPrnsdB1KKo9wRwNf9FkzjjLLSjp3HxJhKsWLxQ7SFWL");
define("SENDERNOREPLY","test@noreply.com");
define("HOSTWEB","http://test.local/btcdashboard/");
define("ADMINEMAILNOREPLY","ardee@test.com");
define("ADMINEMAIL","noreply@test.com");
define("APIURL","http://localhost:3000");
define("CREATE_WALLET_URL",APIURL."/api/v2/create");
define("BTCVALUE","https://blockchain.info/ticker");
define("BTCADDRESSPAYMENT","1Hdm174KHdqD4ChbtKyKSDfzetJnwr2mGQ");
define("MININUMWITHDRAW",50);
?>