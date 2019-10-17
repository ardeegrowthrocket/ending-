<?php
mysql_connect("localhost","root","");

// ENSURE TO CONNECT TO BUSSCO OSC DATABASE
mysql_select_db("cyrusdev_nov152017");

$ordertbl = "cyr_sales_flat_order";
$ordertbladdr = "cyr_sales_flat_order_address";
$ordertblitem = "cyr_sales_flat_order_item";

function addr($order,$type){
	$q = mysql_query("SELECT * FROM cyr_sales_flat_order_address WHERE parent_id ='$order' AND address_type='$type'");
	$row = mysql_fetch_assoc($q);
	return json_encode($row);
}

function item($order){
	$q = mysql_query("SELECT product_id,sku,qty_ordered FROM cyr_sales_flat_order_item WHERE order_id ='$order'");
	$array = array();
	while($row = mysql_fetch_assoc($q)){
		$array[] = $row;
	}
	return json_encode($array);
}
?>
<table border='1'>
<?php
/* LOOK FOR THE findermapper.csv*/
$file_csv = fopen("findermapper-orders.csv","w");
$str = "";
$strray = explode(",",$str);

$c = 0;
$q = mysql_query("SELECT * FROM $ordertbl");
while($row=mysql_fetch_assoc($q)){

	if($c==0){
		$row['billing'] = 1;
		$row['shipping'] = 1;
		$row['items'] = 1;
		fputcsv($file_csv,array_keys($row));
		$c++;
	}
		$row['billing'] = addr($row['entity_id'],'billing');
		$row['shipping'] = addr($row['entity_id'],'shipping');
		$row['items'] = item($row['entity_id']);
	fputcsv($file_csv,$row);
}
//  
fclose($file_csv);
?>
</table>