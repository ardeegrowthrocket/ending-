<?php
include("btcdashboard/inc/connection.php");
include("btcdashboard/inc/function.php");
$current = date("Y-m-d H:i:s");
$qclock1 = mysql_fetch_assoc(mysql_query("SELECT * FROM `btc_oicdata` WHERE status ='' AND ico_end >='$current' ORDER BY `ico_end` ASC LIMIT 1
"));
$clock1 = date("F d, Y H:i:s",strtotime($qclock1['ico_end']));

$qclock2 = mysql_fetch_assoc(mysql_query("SELECT * FROM `btc_oicdata` WHERE status ='' AND ico_end >='$current' ORDER BY `ico_end` DESC LIMIT 1
"));
$clock2 = date("F d, Y H:i:s",strtotime($qclock2['ico_end']));
$jsdate = date("F d, Y H:i:s");


$q = mysql_query("SELECT * FROM `btc_cmsmanager`");
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="" />
    <meta name="keywords" content="" />

    <title>Elunium</title>
    
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Plugin CSS -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/simple-line-icons.css">
    <link rel="stylesheet" href="css/jquery.lightbox.min.css">

    <!-- Theme CSS -->
    <link href="css/style.css" rel="stylesheet">
    
    <!-- Custom Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="fc/flipclock-invent.css"> 

    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Lightbox JavaScript-->
    <script src="js/jquery.lightbox.min.js"></script>
    <!-- Theme JavaScript -->
    <script src="js/script.js"></script>
    <script src="fc/flipclock.js"></script>    


<script src="https://use.fontawesome.com/426bab4379.js"></script>
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">    
</head>

<body id="page-top">

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span><i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top"><img src="logo.png" class="img-responsive" style='height: 87px;'></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a class="page-scroll" href="#page-top">Home</a>
                    </li>
                    <?php
                        while($row=mysql_fetch_assoc($q)){
                    ?>
                    <li>
                        <a class="page-scroll" href="#cms<?php echo $row['cms_id']; ?>"><?php echo $row['title']; ?></a>
                    </li>
                    <?php
                    }
                    ?>
                    <li>
                        <a class="page-scroll" href="btcdashboard/index.php">Join us</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    
     <header>
        <div class="container">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <div class="bwt-header-content">
                        <div class="bwt-header-content-inner">

<div class="row">
  <?php
    if(!empty($qclock2['ico_end']))
    {
  ?>    
        <div class="col-sm-12">
               <h1 class="bwt-section-title text-uppercase titlewhite">ICO END AT</h1>
                <div class='roger'>
                          <center>
                          <div class="clock"></div>
                          </center>
                          <script type="text/javascript">
                          var clock;

                            jQuery(document).ready(function() {

                              // Grab the current date
                              var currentDate = new Date("<?php echo $jsdate; ?>");

                              // Set some date in the future. In this case, it's always Jan 1
                              //var futureDate  = new Date(currentDate.getFullYear() + 1, 0, 1);
                              //INSERT TARGET DATE HERE
                              var futureDate  = new Date("<?php echo $clock2; ?>");

                              // Calculate the difference in seconds between the future and current date
                              var diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;

                              // Instantiate a coutdown FlipClock
                              clock = jQuery('.clock').FlipClock(diff, {
                                clockFace: 'DailyCounter',
                                countdown: true
                              });
                            });
                        </script>    
                </div>
        </div>
                    <?php
                        }
                      ?>
</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
<?php
$q = mysql_query("SELECT * FROM `btc_cmsmanager`");
    while($row2=mysql_fetch_assoc($q)){
?>
<!--about-->
<section id="cms<?php echo $row2['cms_id']; ?>" class="bwt-about text-left">
   <?php
    echo $row2['content'];
   ?> 
</section>
<!--//about-->
<?php

}

?>

<style>
.followdiv {
    width: 104px;
    float: right;    
}

ul.followus {
    list-style-type: none;
    font-size:25px;
}
ul.followus li {
    float:left;
    margin-left:10px;
    padding:5px;
}
ul.followus li a {
    color:white;
}
ul.followus li a:hover {
    color:#f39c12;
}

</style>
<div class="bwt-footer-copyright">
<div class="container">
<div class="row">
<div class="col-md-6 copyright">
<div class="left-text">Copyright &copy; Elunium 2017. All Rights Reserved</div>
</div>
<div class="col-md-6 copyright">
<div class="right-text">
            <div class='followdiv'>
            <ul class='followus'>
                <li>
                    <a href='<?php echo getmageconfig("btcsection/btcgroup/fb"); ?>'><i class="fa fa-facebook" aria-hidden="true"></i></a>
                </li>
                <li>
                    <a href='<?php echo getmageconfig("btcsection/btcgroup/twitter"); ?>'><i class="fa fa-twitter" aria-hidden="true"></i></a>
                </li>
            </ul>
        </div>
</div>
</div>
</div>
</div>
</div>

<!--//contact-->



</body>
</html>	