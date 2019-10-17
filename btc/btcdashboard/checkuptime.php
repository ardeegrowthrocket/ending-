<?php
include("inc/connection.php");
include("inc/function.php");

$data = json_decode(httpGet(APIURL),true);
if(count($data)==0){
  mail("ardeenathanraranga@gmail.com","DOWNTIME",date("Y-m-d h:i:sa"));
}
else
{
  //mail("ardeenathanraranga@gmail.com","UPTIME",date("Y-m-d h:i:sa"));
}
?>