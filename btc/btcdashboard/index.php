<?php

include("inc/connection.php");
include("inc/function.php");
require_once 'googleLib/GoogleAuthenticator.php';


$qcron = mysql_query("SELECT *,(SELECT SUM(amount) FROM btc_buyhistory WHERE ico_id=a.oic_id) as total FROM `btc_oicdata` as a WHERE status='' ORDER by ico_end ASC LIMIT 1");

$qcronrow = mysql_fetch_assoc($qcron);

if($qcronrow['total']==$qcronrow['ico_qty']){
  mysql_query("UPDATE btc_oicdata SET status='1' WHERE oic_id='{$qcronrow['oic_id']}'");
}

if(!isset($_SESSION['loggedin'])){
  echo "<script> window.location ='login.php'; </script>";
  exit();
}
          $row = getuserbyEmail($_SESSION['email']);
            foreach($row as $key=>$val){
              $_SESSION[$key] = $val;
            }

            //if(!empty($_SESSION['btcvalue'])){
                 $_SESSION['btcvalue'] = getCurrentBTC();
            //}
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Elunium</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script src="fc/flipclock.js"></script> 
<link rel="stylesheet" href="fc/flipclock-invent.css">      
<link rel="stylesheet" href="plugins/bootstrap-slider/slider.css">
<script src="plugins/bootstrap-slider/bootstrap-slider.js"></script>  
<style>
p.generatebtc {
    padding-left: 10px;
    padding-top: 20px;
    font-size: 22px;
}
</style>
<style>
.faimg img {
    width: 18px;
    margin-top: -5px;
}
.faimglrg img {
    width: 80px;
    margin-top: -5px;
}
.box-body.notitxt {
    display: block;
    font-weight: bold;
    font-size: 30px;
    text-align: center;
}
</style>
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico"> 
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>E</b>LU</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Elun</b>ium</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="dist/img/user.png" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"><?php echo $_SESSION['username']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="index.php?page=account" class="btn btn-default btn-flat">Account</a>
                </div>
                <div class="pull-right">
                  <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="dist/img/user.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $_SESSION['username']; ?></p>
          <!-- Status -->
          <a href="javascript:void(0);"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <!-- Optionally, you can add icons to the links -->
        <li class="active"><a href="index.php?page=home"><i class="fa faimg"><img src='desktopicon/dashboard.png'></i> <span>Dashboard</span></a></li>
        <li class="treeview">
          <a href="#"><i class="fa faimg"><img src='desktopicon/ico.png'></i> <span>ICO</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="index.php?page=icoinfo">ICO Information</a></li>
            <li><a href="index.php?page=buyico">Buy ICO</a></li>
          </ul>
        </li>

        <li class="treeview">
          <a href="#"><i class="fa faimg"><img src='desktopicon/wallet.png'></i> <span>Wallet</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="index.php?page=deposit">Deposit</a></li>
            <li><a href="index.php?page=withdraw">Withdraw</a></li>
            <li><a href="index.php?page=history">Withdraw History</a></li>
            <li><a href="index.php?page=buyhistory">Buying History</a></li>
          </ul>
        </li>
        <li><a href="index.php?page=transaction"><i class="fa faimg"><img src='desktopicon/transaction.png'></i> <span>Transaction</span></a></li>

        <li class="treeview">
          <a href="#"><i class="fa faimg"><img src='desktopicon/settings.png'></i> <span>Settings</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="index.php?page=changepass">Change Password</a></li>
            <li><a href="index.php?page=account">Account</a></li>
            <li><a href="index.php?page=security">Security</a></li>
          </ul>
        </li>
        <li><a href="index.php?page=refer"><i class="fa faimg"><img src='desktopicon/refer.png'></i> <span>Referral</span></a></li>
        <li><a href="index.php?page=support"><i class="fa faimg"><img src='desktopicon/support.png'></i> <span>Support</span></a></li>
        <li><a href="logout.php"><i class="fa faimg"><img src='desktopicon/logout.png'></i> <span>Logout</span></a></li>


      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <?php
        if(isset($_GET['page'])){
          include("tmp/{$_GET['page']}.php");
        }else{
          include("tmp/home.php");
        }
      ?>
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
    
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2017 <a href="#">Company</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->



<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->





</body>
</html>