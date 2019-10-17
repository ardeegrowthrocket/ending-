<?php
$success = array();
$error = array();
$tbl = "btc_btcuser";
if(isset($_POST['generate'])){
  if(empty($_SESSION['btc_guid']))
  {
    $randompassword = createRandomPassword(10);
    $url = CREATE_WALLET_URL."?api_code=".SUPER_APIKEY."&password=$randompassword";
    $data = json_decode(httpGet($url),true);
    if(count($data)!=0){
      if(!empty($data['guid'])){
      $_SESSION['btc_guid'] = $btc_guid = $data['guid'];
      $_SESSION['btc_address'] = $btc_address = $data['address'];
      $_SESSION['btc_pass'] =  $btc_pass = $randompassword;
      mysql_query("UPDATE $tbl SET btc_guid='$btc_guid',btc_address='$btc_address',btc_pass='$btc_pass' WHERE username='{$_SESSION['username']}'");
      }else{
        $error[] = "Blockchain server is down try again later.";
      }
    }
  }
}

?>    

<style>
.btcadd{
  padding: 10px;
    border: 1px solid black;
    font-size: 17px;
    background: lavender;
    color: black;
    text-align: center;
}
</style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Deposit
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
<?php
  if(empty($_SESSION['btc_guid'])){
?> 
<form method='POST' action='index.php?page=deposit'>
<div class="box">
            <div class="box-body">
              <input type='hidden' name='generate' value='1'>
              <button type="submit" class="btn btn-block btn-primary">Generate Bitcoin Address</button>
            </div>
            <!-- /.box-body -->
</div>
</form>
<?php
}
else {
  ?>
  
<div class="box">
<div class="box-header">
              <h3 class="box-title">Your Bitcoin Address</h3>
</div>  
<div class="box-body">
  <div class='btcadd'><?php echo $_SESSION['btc_address']; ?></div><br/>
  <button onclick="window.location='index.php?page=withdraw';" type="button" class="btn btn-block btn-primary btn-flat">Withdraw</button>
  <button onclick="window.location='index.php?page=history';" type="button" class="btn btn-block btn-primary btn-flat">View History</button>
</div>
</div>
  <?php
}
?>
    </section>
    <!-- /.content -->