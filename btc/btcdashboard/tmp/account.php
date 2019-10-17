<?php
$success = array();
$error = array();
if(isset($_POST['current'])){
      if(empty($_POST['password'])){
         $error[] = "Password is required.";
      }
      if(empty($_POST['password2'])){
         $error[] = "Confirm Password is required.";
      }
      if($_POST['current']!=$_SESSION['password']){
        $error[] = "Current Password is incorrect.";
      }
      if(count($error)==0){
        $success[] = "Your password is now updated.";
        #echo "UPDATE btc_btcuser SET passowrd='{$_POST['password']}' WHERE username='{$_SESSION['username']}'";
        mysql_query("UPDATE btc_btcuser SET password='{$_POST['password']}' WHERE username='{$_SESSION['username']}'");
      }
}
?>    


    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        My Account
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
<div class="box">
            <div class="box-body">
              Username:<input name='1' class="form-control" type="text" value="<?php echo $_SESSION['username']; ?>" xplaceholder="Current Password" readonly="readonly">
              <br>
              Email:<input name='2' class="form-control" type="text" value="<?php echo $_SESSION['email']; ?>" xplaceholder="New Password" readonly="readonly">
              <br>
              Country:<input name='3' class="form-control" type="text" value="<?php echo $_SESSION['country']; ?>"  xplaceholder="Confirm Password" readonly="readonly">
              <br>
              Elunium Address:<input name='2' class="form-control" type="text" value="<?php echo $_SESSION['elu_address']; ?>" xplaceholder="New Password" readonly="readonly">
              <br>
               Bitcoin Address:<input name='2' class="form-control" type="text" value="<?php if(!empty($_SESSION['btc_address'])) { echo $_SESSION['btc_address']; } else { echo "Please generate BTC in Wallet > Deposit"; } ?>" xplaceholder="New Password" readonly="readonly">
              <br>                           
              <br>
            <button onclick="window.location='index.php?page=changepass';" type="button" class="btn btn-block btn-primary btn-flat">Change Password</button>
            <button onclick="window.location='index.php?page=security';" type="button" class="btn btn-block btn-primary btn-flat">Change Security</button>
            </div>
            <!-- /.box-body -->
</div>

    </section>
    <!-- /.content -->