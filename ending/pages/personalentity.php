<?php
require_once("./connect.php");
$accounts_id = $_SESSION['accounts_id'];
$accounts_id = $_SESSION['accounts_id'];
$query = "SELECT * FROM tbl_buycode_history as a 
JOIN tbl_accounts as b 
WHERE a.accounts_id=b.accounts_id  
AND a.accounts_id='$accounts_id'
";
$q = mysql_query_cheat($query);


function dateDiff($d1){
    $date1=strtotime($d1);
    $date2=strtotime(date("Y-m-d h:i:sa"));
    $seconds = $date1 - $date2;
    $weeks = floor($seconds/604800);
    $seconds -= $weeks * 604800;
    $days = floor($seconds/86400);
    $seconds -= $days * 86400;
    $hours = floor($seconds/3600);
    $seconds -= $hours * 3600;
    $minutes = floor($seconds/60);
    $seconds -= $minutes * 60;
    $months=round(($date1-$date2) / 60 / 60 / 24 / 30);
    $years=round(($date1-$date2) /(60*60*24*365));
    $diffArr=array("Seconds"=>$seconds,
                  "minutes"=>$minutes,
                  "Hours"=>$hours,
                  "Days"=>$days,
                  "Weeks"=>$weeks,
                  "Months"=>$months,
                  "Years"=>$years
                 ) ;
   return $diffArr;
}


$monthly = "funding_provided,funding_date,monthy_profit,capital_release";
$accumulative = "funding_provided,funding_date,accumulated_profit,accumulated_profit_release,percentage_growth,total_clients";
?>


<h2>Funding Accounts</h2>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
			     <th>Fund Data</th>
           <th>Other Status</th>
        </tr>
    </thead>
    <tbody>
		<?php
			while($row=mysqli_fetch_array_cheat($q)){		
				//var_dump(dateDiff($row['history']));
		?>
        <tr>
			<td><?php echo $row['package_summary']; ?></td>

      <td>
          <?php
              if($row['package_id']==6){
                  $textdata = $monthly;
              }else{
                 $textdata = $accumulative;
              }

              foreach(explode(",",$textdata) as $text){

                  if(empty($row[$text])){
                    $row[$text] = "N/A";
                  }

                  echo ucwords($text)." :{$row[$text]}<br>";
              }
          ?>
      </td>
        </tr>
		<?php
			}
		?>
    </tbody>
</table>