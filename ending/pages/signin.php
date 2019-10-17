<?php
$tbl = "tbl_accounts";
$ga = new GoogleAuthenticator();
  

$error = array();
$success = array();
$securelog = '';

if(isset($_GET['resend'])){
  if(countField($tbl,"username",$_GET['resend'])>0){
    $resendcode = getemailconfirmation($_GET['resend']);
    $resendcodeemail = getemailconfirmationemail($_GET['resend']);
    if(!empty($resendcode)){
        $linkemail = "confirmaccount.php?code=$resendcode";
        sendmail($resendcodeemail,"Email Account Confirmation","Go here: $linkemail to confirm your account.");
        $success[] = "Email confirmation resent to your email address associated in account ({$_GET['resend']})";
    }
  }
}

 if(count($_POST)!=0){



      if(empty($_POST['username'])){
         $error[] = "Username is required.";
      }
      if(empty($_POST['password'])){
         $error[] = "Password is required.";
      }
      if(empty($_POST['g-recaptcha-response'])){
         $error[] = "Please confirm you are not robot.";
      }
        if(checklogin_confirmation($_POST['username'],$_POST['password'])==0 && checklogin($_POST['username'],$_POST['password'])>0){
          $error[] = "Account is not yet activated kindly check your email. Click <a href='?resend={$_POST['username']}'>here</a> to resend";
        }
      if(count($error)==0){
        $validate = checklogin($_POST['username'],$_POST['password']);

        if($validate==0){
          $error[] = "Can't find any user in the supplied credentials. Please check.";
        }else{
          $success[] = "Logged In Successful";
          $row = getuser($_POST['username'],$_POST['password']);
            foreach($row as $key=>$val){
              $_SESSION[$key] = $val;
            }
            $secret = $row['security2_val'];
           //echo $qrCodeUrl = $ga->getQRCodeGoogleUrl($_SESSION['email'],$secret,$_SESSION['username']);

            if(!empty($row['security_1email']) || !empty($row['security_2gauth'])){
              unset($success[0]);
              $securelog = 1;
              $pass1 = 0;
              $pass2 = 0;
              if(!empty($row['security_2gauth'])){
                if(!empty($_POST['security_2gauth']))
                {
                  $checkResult = $ga->verifyCode($row['security2_val'], $_POST['security_2gauth'],4);
                  if(empty($checkResult)){
                    $error[] = "Please enter correct code from Authenticator.";
                    $pass = 0;
                  }else{
                    $pass1 = 1;
                  }
                }
              }else{
                $pass1 = 1;
              }

              if(!empty($row['security_1email'])){
                
                if(!empty($_POST['security_1email'])){
                  $checkCode = checkemailcode($_POST['username'],$_POST['password'],$_POST['security_1email']);
                  if(empty($checkCode))
                  {
                    $error[] = "Please enter correct code sent from your E-mail.";
                    $pass = 0;
                  }
                  else{
                    $pass2 = 1;
                  }
                }else{
                  generateEmailKey($row['username'],$row['email']);
                }
              }else{
                $pass2 = 1;
              }

              if($pass1==1 && $pass2==1){
                $_SESSION['loggedin'] = $_POST['username'];
              }

            }
            else{
              $_SESSION['loggedin'] = $_POST['username'];
            }
        }
      }

  }

if(isset($_GET['logout']))
{
  session_destroy();
  unset($_SESSION['loggedin']);
}

if(isset($_SESSION['loggedin'])){
  echo "<script> window.location ='index.php'; </script>";
  exit();
}



?>
<div class="panel panel-default">

                        <div class="panel-heading" style='  background-color: #ddd'>

                        <strong>Login</strong>  

</div>
<div class="panel-body">
  <!-- /.login-logo -->
  <div class="login-box-body">


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
              foreach($success as $successv){
                echo $successv."<br>";
              }
            ?>
          </p>
</div>
<?php
  }
?>


<form action="index.php?page=signin" method="post">
    
      <?php
        if(!empty($_SESSION['security_1email'])){
          $securelog = 1;
      ?>
      <p class="login-box-msg">Please put correct code sent it email.</p>
      <div class="form-group has-feedback">
        <input name="security_1email" type="text" class="form-control" placeholder="Email Code" required>
      </div>
      <?php
      }
      ?>
    
      <?php
        if(!empty($_SESSION['security_2gauth'])){
          $securelog = 1;
      ?>
      <p class="login-box-msg">Please put correct code in authenticator.</p>
      <div class="form-group has-feedback">
        <input name="security_2gauth" type="text" class="form-control" placeholder="Auth Code" required>
      </div>
      <?php
      }
      ?>


      <?php
        if($securelog==1){
          ?>
           <input name="username" type="hidden" value="<?php echo $_SESSION['username']; ?>">
           <input name="password" type="hidden" value="<?php echo $_SESSION['password']; ?>">
           <input name="g-recaptcha-response" type="hidden" value="<?php echo md5($_SESSION['password']); ?>">
          <?php
        }
      ?>

      <?php
        if(empty($_SESSION['username'])){
      ?>
      <p class="login-box-msg">Sign in to start your session</p>
      <div class="form-group has-feedback">
        <input name="username" type="text" class="form-control" placeholder="Username" required>
      </div>
      <div class="form-group has-feedback">
        <input name="password" type="password" class="form-control" placeholder="Password" required>
      </div>

 

      <?php
      }
      ?>


      <div class='row'>
        <div class='col-xs-12'>
          <script type="text/javascript">
          var onloadCallback = function() {
          grecaptcha.render('test', {
          'sitekey' : '6LfmMTkUAAAAAI-BqOClWhvBByuZ65CZmFgvp9px',
          'callback' : verifyCallback,
          });
          };
         var verifyCallback = function(response) {
            if(response!=''){
             jQuery('#recap').val(response);
            }
          };

          </script>
          <div id='test' style='margin-left: 10px;'></div>
          <br/>
        </div>
      </div>  

    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script>

      <div class="row">
        <!-- /.col -->
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <!-- /.social-auth-links -->
      <?php
        if(empty($_SESSION['username'])){
      ?>
    <a href="index.php?page=forgotpass">I forgot my password</a><br>
    <a href="index.php?page=signin" class="text-center">Register a new membership</a>
    <?php
    }
  ?>
  </div>
  <!-- /.login-box-body -->
</div>
