<?php
error_reporting(0);
include("inc/connection.php");
include("inc/function.php");
$tbl = "btc_btcuser";
#session_destroy();
require_once 'googleLib/GoogleAuthenticator.php';
$ga = new GoogleAuthenticator();
  

$error = array();
$success = array();
$securelog = '';

if(isset($_GET['resend'])){
  if(countField($tbl,"username",$_GET['resend'])>0){
    $resendcode = getemailconfirmation($_GET['resend']);
    $resendcodeemail = getemailconfirmationemail($_GET['resend']);
    if(!empty($resendcode)){
        $linkemail = HOSTWEB."confirmaccount.php?code=$resendcode";
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
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico"> 
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="index.php"><img src="logo.png" style="height: 150px;"></a>
  </div>
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


<form action="login.php" method="post">
    
      <?php
        if(!empty($_SESSION['security_1email'])){
          $securelog = 1;
      ?>
      <p class="login-box-msg">Please put correct code sent it email.</p>
      <div class="form-group has-feedback">
        <input name="security_1email" type="text" class="form-control" placeholder="Email Code" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
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
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
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
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input name="password" type="password" class="form-control" placeholder="Password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

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

      <?php
      }
      ?>

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
    <a href="forgotpassword.php">I forgot my password</a><br>
    <a href="register.php" class="text-center">Register a new membership</a>
    <?php
    }
  ?>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>


    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
