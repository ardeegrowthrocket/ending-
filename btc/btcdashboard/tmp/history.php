<?php
if(!empty($_SESSION['btc_address'])){
//$url = "https://blockchain.info/address/{$_SESSION['btc_address']}?format=json&offset=0";
//$trans = json_decode(httpGet($url),true);
?>
<style>
hr {
    color: red;
    background-color: red;
    height: 6px;
}
</style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Withdraw History
      </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

<div class="box">
            <!-- /.box-header -->
            <div class="box-body no-padding">
                    <table class="table table-striped">
                      <tbody>
                      <tr>
                        <th>Coin</th>
                        <th>Address</th>
                        <th>Amount</th>
                        <th>Status</th>
                      </tr>
<?php
$q = mysql_query("SELECT * FROM `btc_btcwithdraw` WHERE username='{$_SESSION['username']}'");

while($row=mysql_fetch_assoc($q))

{
?>
<tr>
  <td><?php echo $row['withdraw_type']; ?></td>
  <td><?php echo $row['btcaddress']; ?></td>
  <td><?php echo $row['amount']; ?></td>
  <td><?php if($row['status']==1) { echo "In Progress"; } else { echo "Done"; }; ?></td>
</tr>
<?php
}
 ?>                 
                    </tbody>
                  </table>
            </div>
            <!-- /.box-body -->
          </div>

    </section>
    <!-- /.content -->
<?php
}
else{
  echo "<p class='generatebtc'>You need to generate your BTC address to able to access this page.</p>";
}
?>