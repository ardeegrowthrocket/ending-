<?php
include("inc/connection.php");
include("inc/function.php");
$tbl = "btc_btcuser";
#session_destroy();
require_once 'googleLib/GoogleAuthenticator.php';
$ga = new GoogleAuthenticator();
  

$error = array();
$success = array();
$securelog = '';


 if(count($_POST)!=0){

      if(empty($_POST['email'])){
         $error[] = "Email is required.";
      }

      if(empty($_POST['g-recaptcha-response'])){
         $error[] = "Please confirm you are not robot.";
      
      }


      if(count($error)==0){
        $user = getuserbyEmail($_POST['email']);
        if($user){
          $success[] = "Your password is being sent to your email address.";
          sendmail($user['email'],"Forgot Password","Here is your main password: {$user['password']}.");
        }else{
           $error[] = "Email address entered not found in database.";
        }
      }

}



?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Forgot Password</title>
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


<form action="forgotpass.php" method="post">
    
      <p class="login-box-msg">Enter your email to send your password into it.</p>
      <div class="form-group has-feedback">
        <input name="email" type="enauk" class="form-control" placeholder="Email" required>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
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
    <a href="#">I forgot my password</a><br>
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
