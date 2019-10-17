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
        Referral
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
                        <th>Username</th>
                        <th>Amount</th>
                      </tr>
<?php
$q = mysql_query("SELECT * FROM `btc_btcuser` WHERE refer='{$_SESSION['username']}'");

while($row=mysql_fetch_assoc($q))

{
?>
<tr>
  <td><?php echo $row['username']; ?></td>
  <td>0</td>
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