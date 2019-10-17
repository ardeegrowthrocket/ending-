<?php
$success = array();
$error = array();
$ga = new GoogleAuthenticator();
$poperror = array();
if(isset($_POST['verificationcode2fa'])){
    $checkResult = $ga->verifyCode($_SESSION['security2_val'], $_POST['verificationcode2fa'],4);
    if(empty($checkResult)){
      $poperror[] = "Please enter correct code from Authenticator.";
    }else{
      mysql_query("UPDATE btc_btcuser SET security_2gauth='yes' WHERE username='{$_SESSION['username']}'");
      $success[] = "Two Factor Authentication is now enabled.";
      $_SESSION['security_2gauth'] = 'yes';
    }  
}


if(isset($_POST['security_type'])){

  if($_POST['security_type']==2){
    if(empty($_POST['security_2gauth'])){
      $secret = $ga->createSecret();
      $qrurl = $ga->getQRCodeGoogleUrl($_SESSION['email'],$secret,$_SESSION['username']);
      //security_2gauth  security2_val
      $_SESSION['security_2gauth'] = '';
      $_SESSION['security2_val'] = $secret;
      mysql_query("UPDATE btc_btcuser SET security_2gauth='',security2_val='$secret' WHERE username='{$_SESSION['username']}'");
    }else{

      $checkResult = $ga->verifyCode($_SESSION['security2_val'], $_POST['qrcide'],4);
        if(empty($checkResult)){
          $error[] = "Unable to disable 2FA. Code entered is invalid.";
          ?>
          <script> window.location = 'index.php?page=security&error2fa=1'; </script>
          <?php
          exit();
        }else{      
          mysql_query("UPDATE btc_btcuser SET security_2gauth='',security2_val='' WHERE username='{$_SESSION['username']}'");
          $_SESSION['security_2gauth'] = '';
          $_SESSION['security2_val'] = '';
        } 
    }
    ?>
    <script> window.location = 'index.php?page=security'; </script>
    <?php
    exit();
  }


  if($_POST['security_type']==1){
    if(empty($_POST['security_1email'])){
      //security_2gauth  security2_val
      $_SESSION['security_1email'] = 'yes';
      mysql_query("UPDATE btc_btcuser SET security_1email='yes' WHERE username='{$_SESSION['username']}'");
    }else{
      mysql_query("UPDATE btc_btcuser SET security_1email='' WHERE username='{$_SESSION['username']}'");
      $_SESSION['security_1email'] = '';
    }
    ?>
    <script> window.location = 'index.php?page=security'; </script>
    <?php
    exit();
  }

}

?>    


    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Security
      </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
<?php
  if(isset($_GET['error2fa'])){
?>

<div class="callout callout-danger">
          <p>Unable to disable 2FA. Code entered is invalid.</p>
</div>
<?php
  }
?>
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

<form method='POST' action='index.php?page=security'>
<div class="box">
            <div class="box-header">
              <h3 class="box-title">Verification login by email</h3>
            </div>
            <div class="box-body">
              <?php
                if(empty($_SESSION['security_1email'])){
                  echo "Your account is not protected by email verification! Enable an email verification now.";
                }else{
                  echo "Your account is protected by email verification. ";
                }
              ?>
                
            </div>
            <div class='box-footer'>
              <input type="hidden" name="security_type" value='1'>
              <input type="hidden" name="security_1email" value='<?php echo $_SESSION['security_1email']; ?>'>
              <button type="submit" value="2" class="btn btn-primary">
                <?php
                  if(empty($_SESSION['security_1email'])){
                    echo "Enable";
                  }
                  else{
                    echo "Disable";
                  }
                ?>
              </button>
            </div>
            <!-- /.box-body -->
</div>
</form>


<form method='POST' action='index.php?page=security'>
<div class="box">
            <div class="box-header">
              <h3 class="box-title">Two Factor Authentication</h3>
            </div>
            <div class="box-body">
              <?php
                if(empty($_SESSION['security_2gauth'])){
                  echo "Your account is not protected by two-step verification! Enable an authenticator now.";
                }else{
                  echo "Your account is protected by two-step verification. ";
                }
              ?>
                
            </div>
            <div class='box-footer'>
              <input type="hidden" name="security_type" value='2'>
              <input type="hidden" name="security_2gauth" value='<?php echo $_SESSION['security_2gauth']; ?>'>
             
                <?php
                  if(empty($_SESSION['security_2gauth']) && empty($_SESSION['security2_val'])){
                    echo '<button type="submit" value="2" class="btn btn-primary">Enable</button>';
                  }
                  else if($_SESSION['security2_val']!='' && $_SESSION['security_2gauth']!='')
                  {
                    echo '<input name="qrcide" type="text" value="" placeholder="Enter 2FA Code" style="margin-right:10px;"><button type="submit" value="2" class="btn btn-primary">Disable</button>';
                  }
                ?>
              </button>
<?php
  if($_SESSION['security2_val']!='' && $_SESSION['security_2gauth']=='')
  {
?>              
              <button id='qrcode' type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Click to Verify</button>
<?php
}
?>
            </div>
            <!-- /.box-body -->
</div>
</form>







<?php
  if($_SESSION['security2_val']!='' && $_SESSION['security_2gauth']=='')
  {
    $qrurl = $ga->getQRCodeGoogleUrl($_SESSION['email'],$_SESSION['security2_val'],$_SESSION['username']);
?>
<script>
$( document ).ready(function() {
  //$('#qrcode').trigger('click');
});
</script>
<div class="modal fade" id="modal-default" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Authenticator Code / QR</h4>
              </div>
              <div class="modal-body">
<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                Please note that we are not able to show you this again ensure to copy this code or scan the qrcode.
</div>
<?php
  if(count($poperror)!=0){
?>

<div class="callout callout-danger">
          <p>
            <?php
              foreach($poperror as $errorval){
                echo $errorval."<br>";
              }
            ?>
          </p>
</div>
<?php
  }
?>
Code: <strong><?php echo $_SESSION['security2_val']; ?></strong><br>
<center>
  <img src='<?php echo $qrurl; ?>'/>
</center>
<p>
- Install an authenticator app on your mobile device if you don't already have one. <br>
- Scan QR code with the authenticator (or tap it in mobile browser) <br>
- Please write down or print a copy of the 16-digit secret code and put it in a safe place <br>
- If your phone gets lost, stolen or erased, you will need this code to link UcoinCash to a new authenticator app install once again <br>
- Do not share it with anyone. Be aware of phishing scams. We will never ask you for this key <br>
</p>
Insert your code below after you scan above:
<form method='POST' action='index.php?page=security'>
<input name="verificationcode2fa" class="form-control" type="text" placeholder="Your code here." required=""></br>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary pull-right">Verify Now!</button>
              </div>
            </div>
</form>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
</div>
<?php
}
?>
    </section>
    <!-- /.content -->