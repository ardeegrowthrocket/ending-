<?php
include("inc/connection.php");
session_destroy();
include("inc/function.php");
require_once 'googleLib/GoogleAuthenticator.php';
$ga = new GoogleAuthenticator();
$tbl = "btc_btcuser";
  $error = array();
  $success = array();
  if(count($_POST)!=0){
     if(empty($_POST['country'])){
         $error[] = "Country is required.";
      }
      if(empty($_POST['username'])){
         $error[] = "Username is required.";
      }else{
         if(countField($tbl,"username",$_POST['username'])>0){
         $error[] = "Username is already in used."; 
         }
      }

      if(empty($_POST['email'])){
         $error[] = "Email is required.";
      }else{

        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)=='') {
          $error[] = "Please use correct email. Invalid email({$_POST['email']})"; 
        }
         if(countField($tbl,"email",$_POST['email'])>0){
         $error[] = "Email is already in used."; 
         }
      }

      if(!empty($_POST['refer'])){
         if(countField($tbl,"username",$_POST['refer'])==0){
          $error[] = "Referrer username cannot find in the system. Please check."; 
         }
      }

      if(empty($_POST['password'])){
         $error[] = "Password is required.";
      }
      if(empty($_POST['password2'])){
         $error[] = "Confirm Password is required.";
      }
      if(empty($_POST['g-recaptcha-response'])){
         $error[] = "Please confirm you are not robot.";
      }


      if($_POST['password']!=$_POST['password2']){
        $error[] = "Passwords do not match.";
      }

      if(count($error)==0){
        $confirmationmail = createRandomPassword();
        $eluid = createRandomPassword(34);
        #$secret = $ga->createSecret();
        $secret = '';
        mysql_query("INSERT INTO btc_btcuser SET username='{$_POST['username']}',password='{$_POST['password']}',email='{$_POST['email']}',security2_val='{$secret}',country='{$_POST['country']}',confirm='$confirmationmail',elu_address='$eluid',refer='{$_POST['refer']}'") or die(mysql_error());
        $success[] = "You are successfully registered please wait for your email confirmation.";

        $linkemail = HOSTWEB."confirm.php?code=$confirmationmail";
        sendmail($_POST['email'],"Email Account Confirmation","Go here: $linkemail to confirm your account.");
      }

  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Elunium | Registration Page</title>
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
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico"> 
</head>
<body class="hold-transition register-page">


<div class="register-box">


  <div class="register-logo">
    <a href="index.php"><img src="logo.png" style="height: 150px;"></a>
  </div>

  <div class="register-box-body">

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

    <p class="login-box-msg">Register a new membership</p>

    <form action="register.php" method="POST">
      <div class="form-group has-feedback">
        <input name="username" type="text" class="form-control" placeholder="Username" required>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input name="email" type="email" class="form-control" placeholder="Email" required>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input name="password" type="password" class="form-control" placeholder="Password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input name="password2" type="password" class="form-control" placeholder="Retype password" required>
        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <select name="country" class="form-control" required>
          <option value=''>Select Country</option>
          <?php
            foreach(getCountries() as $country){
              echo "<option value='$country'>$country</option>";
            }
          ?>
        </select>
        <span class="glyphicon glyphicon-flag form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <input name="refer" type="text" class="form-control" placeholder="Username Referral">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
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
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <br/>
          <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
        </div>
        <!-- /.col -->
      </div>
      <textarea style='display:none;' name='recap' id='recap'></textarea>
    </form>


    <a href="login.php" class="text-center">I already have a membership</a>
  </div>
  <!-- /.form-box -->
</div>
<!-- /.register-box -->

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
