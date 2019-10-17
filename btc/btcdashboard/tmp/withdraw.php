<?php
if(!empty($_SESSION['btc_address'])){
$success = array();
$error = array();
$bitcoin_url = APIURL."/merchant/{$_SESSION['btc_guid']}/balance?password={$_SESSION['btc_pass']}";
$bitcoin_balance = json_decode(httpGet($bitcoin_url),true);
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


if(isset($_POST['withdraw_type'])){


if($_POST['withdraw_type']=='btc'){

  if(empty($_POST['address']))
  {
    $error[] = "Address is required.";
  }  
  if(empty($_POST['amount']))
  {
    $error[] = "Please insert amount.";
  }  
  if(empty($_POST['amount'])){
    $error[] = "Please insert amount.";
  }  
  if(!is_numeric($_POST['amount'])){
    $error[] = "Please correct your amount (Dont put letter or words)";
  }else{
    if($_POST['amount']<0){
      $error[] = "Hmmm.. it looks like your adding negative value. Please correct amount.";
    }
    if($_POST['amount']>$btcamountsum){
      $error[] = "Insufficient funds in your BTC wallet.";
    }    

  }
  if($_POST['password']!=$_SESSION['password']){
    $error[] = "Confirm Password is wrong. Try again.";
  }

  if(count($error)==0){
    $satoshivalue = $_POST['amount'] * 100000000;
    $paymenturl = APIURL."/merchant/{$_SESSION['btc_guid']}/payment?password={$_SESSION['btc_pass']}&to=".$_POST['address']."&amount={$satoshivalue}&fee=1000";
    $payment_resp = json_decode(httpGet($paymenturl),true);
    if(!empty($payment_resp['error'])){
        $error[] = $payment_resp['error']." This may cause of downtime of blockchain, Or your account balance is not yet approved.";
    }else{
        if(!empty($payment_resp['txid']))
        {
          $success[] = "Your BTC withdrawal is approved kindly wait in your wallet. Hash:{$payment_resp['txid']}";
          mysql_query("INSERT INTO btc_btcwithdraw SET 
            username='{$_SESSION['username']}',
            amount='{$_POST['amount']}',
            btcaddress='{$_POST['address']}',
            withdraw_type='btc',
            status='0'
          ") or die(mysql_error());
          echo "<script> window.location='index.php?page=withdraw&success=btc&hash={$payment_resp['txid']}'; </script>";
          exit();
        }
    }


  }

}




if($_POST['withdraw_type']=='elu'){

  if(empty($_POST['address']))
  {
    $error[] = "Address is required.";
  }  
  if(empty($_POST['amount']))
  {
    $error[] = "Please insert amount.";
  }  
  if(empty($_POST['amount'])){
    $error[] = "Please insert amount.";
  }  
  if(!is_numeric($_POST['amount'])){
    $error[] = "Please correct your amount (Dont put letter or words)";
  }else{
    if($_POST['amount']<0){
      $error[] = "Hmmm.. it looks like your adding negative value. Please correct amount.";
    }
    if($_POST['amount']>getUserBalance($_SESSION['username'])){
      $error[] = "Insufficient funds in your ELU wallet.";
    }   

    if($_POST['amount']<MININUMWITHDRAW){
      $error[] = "Minimum amount of withdraw is ".MININUMWITHDRAW;
    }    
  }
  if($_POST['password']!=$_SESSION['password']){
    $error[] = "Confirm Password is wrong. Try again.";
  }
  #$error[] = $_POST['amount']."//".MININUMWITHDRAW;
  $checkuseraddr = checkuserbyelu($_POST['address']);
  if(empty($checkuseraddr)){
      $error[] = "ELU address({$_POST['address']}) is invalid kindly check ensure to put correct address.";
  }

  if(count($error)==0){
    $success[] = "Your ELU withdrawal is approved kindly wait in your wallet.";
    $newbalance =  getUserBalance($_SESSION['username']) - $_POST['amount'];
    $newbalance2 =  getUserBalance($checkuseraddr) + $_POST['amount'];

    $checkuseraddremail = checkuserbyeluemail($_POST['address']);
    $checkuseraddrsender = $_SESSION['elu_address'];
    sendmail($checkuseraddremail,"ELU UPDATE - DEPOSIT ","Address: {$_SESSION['elu_address']} Credited your account({$_POST['address']}) by {$_POST['amount']}");


    mysql_query("UPDATE btc_btcuser SET balance = '$newbalance' WHERE username ='{$_SESSION['username']}'");
    mysql_query("UPDATE btc_btcuser SET balance = '$newbalance2' WHERE username ='{$checkuseraddr}'");
    mysql_query("INSERT INTO btc_btcwithdraw SET 
      username='{$_SESSION['username']}',
      amount='{$_POST['amount']}',
      btcaddress='{$_POST['address']}',
      withdraw_type='elu',
      status='0'
    ") or die(mysql_error());
    echo "<script> window.location='index.php?page=withdraw&success=elu'; </script>";
    exit();

  }

}






}


?> 

<div class="row">
        <div class="col-md-6">
          <div class="info-box">
                      <span class="info-box-icon bg-aqua"><i class="fa fa-bitcoin"></i></span>

                      <div class="info-box-content">
                        <span class="info-box-text">Bitcoin Balance</span>
                        <span class="info-box-number"><?php echo $bitcoin_balance_value; ?></span>
                      </div>
                      <!-- /.info-box-content -->
          </div>
        </div>
        <div class="col-md-6">
          <div class="info-box">
                      <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>

                      <div class="info-box-content">
                        <span class="info-box-text">Elunium Balance</span>
                        <span class="info-box-number"><?php echo $elu_balance_value; ?></span>
                      </div>
                      <!-- /.info-box-content -->
          </div>          
        </div>
</div>   


    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Withdraw
      </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

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
  if(isset($_GET['success']))
  {
?>

<div class="callout callout-success">
          <p>
              Your <?php echo strtoupper($_GET['success']); ?> withdrawal is approved kindly wait in your wallet
              <?php
                if(isset($_GET['hash']))
                {
                  echo " Hash: {$_GET['hash']}";
                }
              ?>
          </p>
</div>
<?php
  }
?>  
<?php
  if(count($success)!=0){
?>

<div class="callout callout-success">
          <p>
            <?php
              foreach($success as $successval){
                echo $successval."<br>";
              }
            ?>
          </p>
</div>
<?php
  }
?>      
<form method='POST' action='index.php?page=withdraw' autocomplete="off">
  <input type='hidden' name='withdraw_type' value='btc'/>
<div class="box">
        <div class="box-header">
                      <h3 class="box-title">Bitcoin</h3>
        </div>  
            <div class="box-body">
              Amount: <input name="amount" class="form-control" type="text" value="" placeholder="Amount" autocomplete="off" required><br/>
              Address: <input name="address" class="form-control" type="text" value="" placeholder="Address(BTC)" autocomplete="off" required><br/>
              Confirm Password: <input name="password" class="form-control" type="password" value="" placeholder="Password" autocomplete="off" required><br/>
              <button type="submit" class="btn btn-block btn-primary btn-lg">Submit</button>
            </div>
            <!-- /.box-body -->
</div>
</form>


<form method='POST' action='index.php?page=withdraw' autocomplete="off">
  <input type='hidden' name='withdraw_type' value='elu'/>
<div class="box">
        <div class="box-header">
                      <h3 class="box-title">Elunium</h3>
        </div>  
            <div class="box-body">
              Amount: <input name="amount" class="form-control" type="text" value="" placeholder="Amount" autocomplete="off" required><br/>
              Address: <input name="address" class="form-control" type="text" value="" placeholder="Address(ELU)" autocomplete="off" required><br/>
              Confirm Password: <input name="password" class="form-control" type="password" value="" placeholder="Password" autocomplete="off" required><br/>
              <button type="submit" class="btn btn-block btn-primary btn-lg">Submit</button>
            </div>
            <!-- /.box-body -->
</div>
</form>
    </section>
    <!-- /.content -->
<?php
}
else{
  echo "<p class='generatebtc'>You need to generate your BTC address to able to access this page.</p>";
}
?>    