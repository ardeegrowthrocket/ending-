<?php
//SELECT *,(SELECT SUM(amount) FROM btc_buyhistory WHERE ico_id=a.oic_id) as total FROM `btc_oicdata` as a WHERE total=a.ico_qty
$datelimit = date("Y-m-d");
if(!empty($_SESSION['btc_address'])){
$success = array();
$error = array();
$paymentDate = date("Y-m-d H:i:s");

$q = "SELECT * FROM `btc_oicdata` WHERE ico_end <= '$paymentDate' AND status = '' ORDER by ico_end ASC LIMIT 1";
$query = mysql_query($q);

$rowdata = mysql_fetch_assoc($query);

///
$bitcoin_url = APIURL."/merchant/{$_SESSION['btc_guid']}/balance?password={$_SESSION['btc_pass']}";
$bitcoin_balance = json_decode(httpGet($bitcoin_url),true);
if(empty($bitcoin_balance['balance'])){
    $bitcoin_balance['balance'] = 0;
}
if(isset($bitcoin_balance['balance'])){
$btcbalanceraw = $bitcoin_balance['balance'] / 100000000;
$bitcoin_balance_value = number_format(($bitcoin_balance['balance'] / 100000000) - btcamountsum($_SESSION['username']),8);
}
else{
$btcbalanceraw = 0 / 100000000;
$bitcoin_balance_value = number_format(0 - btcamountsum($_SESSION['username']),5);
}
$elu_balance_value = number_format(getUserBalance($_SESSION['username']),5);
$btcamountsum = $btcbalanceraw - btcamountsum($_SESSION['username']);
///

//
$q2 = "SELECT SUM(amount) as total FROM `btc_buyhistory` WHERE ico_id='{$rowdata['oic_id']}'";
$row2 = mysql_fetch_assoc(mysql_query($q2));
$totalcoin2 = $row2['total'];
if($totalcoin2==''){
    $totalcoin2 = 0;
}


$q23 = "SELECT SUM(amount) as total FROM `btc_buyhistory` WHERE username='{$_SESSION['username']}' AND dateofbuy LIKE '%$datelimit%'";
$row23 = mysql_fetch_assoc(mysql_query($q23));
$totalcoin23 = $row23['total'];
if($totalcoin23==''){
    $totalcoin23 = 0;
}

//
?>   
<?php
$availtobuy = $rowdata['ico_qty'] - $totalcoin2;


if(isset($_POST['coins'])){
    
      if(!is_numeric($_POST['coins'])){
        $error[] = "Please correct your amount (Dont put letter or words)";
      }else{
        $grandtotal1 = $_POST['coins'] * $rowdata['ico_price'];
        $grandtotal2 = $grandtotal1 / $_SESSION['btcvalue'];
        $grandtotal = round($grandtotal2 * 100000000); 
        $paymenturl = APIURL."/merchant/{$_SESSION['btc_guid']}/payment?password={$_SESSION['btc_pass']}&to=".BTCADDRESSPAYMENT."&amount=$grandtotal&fee=1000";
        //$error[] = "{$bitcoin_balance['balance']} ++ $availtobuy ++ $grandtotal ++";
        if($_POST['coins']<0){
          $error[] = "Hmmm.. it looks like your adding negative value. Please correct amount.";
        }
        if($_POST['coins']>$availtobuy){
          $error[] = "Your amount is greater than available coins. Available($availtobuy) your request is ({$_POST['coins']})";
        }    
        if($bitcoin_balance['balance']<=$grandtotal){
            $error[] = "Your bitcoin has insufficient amount kindly deposit to purchase a coin.";
        }

      }    
      if($_POST['current']!=$_SESSION['password']){
        $error[] = "Confirm Password is wrong. Try again.";
      }
      $islimittotal = $totalcoin23 + $_POST['coins'];
      if($islimittotal>$rowdata['ico_limit']){
        $error[] = "You exceed of limit({$rowdata['ico_limit']}) of buying coins per day. You already bought($totalcoin23).";
      }

      if(count($error)==0){
        $payment_resp = json_decode(httpGet($paymenturl),true);
        //unset($payment_resp['error']);
        //$payment_resp['txid'] = "hilot-".rand();
        if(!empty($payment_resp['error'])){
            $error[] = $payment_resp['error']." This may cause of downtime of blockchain, Or your account balance is not yet approved.";
        }else{
            if(!empty($payment_resp['txid']))
            {
            $newbalance = getUserBalance($_SESSION['username']) + $_POST['coins'];
            $_SESSION['successbuy'] = "Thank you for purchasing coins. Hash: {$payment_resp['txid']}";
            mysql_query("INSERT INTO btc_buyhistory SET username='{$_SESSION['username']}',ico_id='{$rowdata['oic_id']}',amount='{$_POST['coins']}',hash='{$payment_resp['txid']}'")  or  die("Could not connect: " . mysql_error());
            mysql_query("UPDATE btc_btcuser SET balance = '{$newbalance}' WHERE username = '{$_SESSION['username']}'") or die("Could not connect: " . mysql_error());
            ?>
             <script>
                setTimeout(function () {
                  window.location = 'index.php?page=buyico'; 
              }, 1000);
            </script>
            <?php
            exit("  Processing... Please wait..");
            }
        }
      }

}
?>


    <!-- Content Header (Page header) -->
    <section data-date='<?php echo date("F d, Y H:i:s"); ?>' class="content-header">
      <h1>
        Buy ICO now!
      </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">




<div class="row">
        <div class="col-md-6">
          <div class="info-box">
                      <span class="info-box-icon bg-aqua"><i class="fa faimglrg"><img src='desktopicon/btc.png'></i></span>

                      <div class="info-box-content">
                        <span class="info-box-text">Bitcoin Balance</span>
                        <span class="info-box-number"><?php echo $bitcoin_balance_value; ?></span>
                        <span>Current Exchange (1 BTC to USD): $<?php echo number_format($_SESSION['btcvalue'],2); ?></span>
                      </div>
                      <!-- /.info-box-content -->
          </div>
        </div>
        <div class="col-md-6">
          <div class="info-box">
                      <span class="info-box-icon bg-aqua"><i class="fa faimglrg"><img src='desktopicon/user.png'></i></span>

                      <div class="info-box-content">
                        <span class="info-box-text">Elunium Balance</span>
                        <span class="info-box-number"><?php echo $elu_balance_value; ?></span>
                        <span>Current Exchange (1 ELU to USD): $<?php echo number_format($rowdata['ico_price'],2); ?></span>
                      </div>
                      <!-- /.info-box-content -->
          </div>          
        </div>
</div> 




<?php
  if(count($error)!=0){
?>

<div class="callout callout-danger">
          <p>
            <?php
              foreach($error as $errorval){
                echo $errorval."<br>";
              }
            ?>
          </p>
</div>
<?php
  }
?>
<?php
  if(isset($_SESSION['successbuy']))
  {
?>

<div class="callout callout-success">
          <p>
                <?php echo $_SESSION['successbuy']; ?>
          </p>
</div>
<?php
    unset($_SESSION['successbuy']);
  }
?>  



    <?php
       if(!empty($rowdata['ico_price']))
        $row = $rowdata;
    {
    ?>
    <div class="box">
            <div id='notification' class="box-body"> 
                    Total Selling Coins: <?php echo number_format($row['ico_qty'],2); ?><br>
                    Total Available Coins: <?php echo number_format($row['ico_qty'] - $totalcoin2,2); ?><br>
                     <div id="coinspage">
                    </div>                   
            </div>

    </div>    
    <form method="POST" action="index.php?page=buyico">
        <div class="box">
                <div class="box-body">         
                    <br style='clear:both;'/>
                    <input name="coins" onkeyup="countercoin(this.value)" class="form-control" type="number" placeholder="Number of coins" required=""><br/>
                    <input name="current" class="form-control" type="password" placeholder="Current Password" required=""></br>
                    <button type="submit" class="btn btn-block btn-primary btn-lg">Submit</button>
                </div>
                </div>
                <!-- /.box-body -->
        </div>
    </form>
    <script>
        function countercoin(coin){
            if(!coin){
                return;
            }
            var coins = parseFloat(coin);
            var conversion = <?php echo $rowdata['ico_price'] / $_SESSION['btcvalue']; ?>;
            var final = coins * conversion;
            jQuery("#coinspage").html("Total Price for "+coins+" coins is "+final.toFixed(8)+" (BTC) and additional 0.00010000(BTC) for transfer fee.");
        }
    </script>   
    <?php
    }
    ?>
     </section>
    <!-- /.content -->
<?php
}
else{
  echo "<p class='generatebtc'>You need to generate your BTC address to able to access this page.</p>";
}
?>