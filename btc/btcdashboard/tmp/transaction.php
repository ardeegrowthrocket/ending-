<?php
if(!empty($_SESSION['btc_address'])){
$url = "https://blockchain.info/address/{$_SESSION['btc_address']}?format=json&offset=0";
$trans = json_decode(httpGet($url),true);
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
        Transaction (BTC)
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
                        <th>Sender</th>
                        <th>Receiver</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Hash</th>
                      </tr>
<?php

foreach ($trans['txs'] as $txs){
$sender_current = current($txs['inputs']);
$sender = $sender_current['prev_out']['addr'];
$receiver_current = $txs['out'];

// var_dump($txs['out']);
// echo "<hr>";

$amountvalue = 0;
?>
<tr>
  <td><?php echo $sender; ?></td>
  <td>
    <?php 
      foreach($receiver_current as $rcv){
        if($sender==$_SESSION['btc_address']) {
          if($sender!=$rcv['addr']){
              echo $rcv['addr'];
              $amountvalue = $rcv['value'];
          }
        }
        else{
          if($_SESSION['btc_address']==$rcv['addr']){
             echo $rcv['addr']."<br>";
             $amountvalue = $rcv['value'];
          }        
      }
    }
    ?>  
  </td>
  <td>
    <?php if($sender==$_SESSION['btc_address']) { echo "(-)"; } else { echo "(+)"; } ?>
    <?php 
      echo number_format($amountvalue / 100000000,8);
    ?>         
  </td>
  <td><?php if($sender==$_SESSION['btc_address']) { echo "Debit"; } else { echo "Credit"; } ?></td>
  <td><?php echo $txs['hash']; ?></td>
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