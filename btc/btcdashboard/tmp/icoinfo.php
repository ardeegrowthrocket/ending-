<?php
$success = array();
$error = array();

$q = "SELECT SUM(ico_qty) as total FROM `btc_oicdata`";
$row = mysql_fetch_assoc(mysql_query($q));
$totalcoin = $row['total'];


$q2 = "SELECT SUM(amount) as total FROM `btc_buyhistory`";
$row2 = mysql_fetch_assoc(mysql_query($q2));
$totalcoin2 = $row2['total'];


$row2q = mysql_query("SELECT * FROM btc_oicdata ORDER by ico_end ASC");

$current = date("Y-m-d H:i:s");

$qclock1 = mysql_fetch_assoc(mysql_query("SELECT * FROM `btc_oicdata` WHERE status ='' AND ico_end >='$current' ORDER BY `ico_end` ASC LIMIT 1
"));
$clock1 = date("F d, Y H:i:s",strtotime($qclock1['ico_end']));

$qclock2 = mysql_fetch_assoc(mysql_query("SELECT * FROM `btc_oicdata` WHERE status ='' AND ico_end >='$current' ORDER BY `ico_end` DESC LIMIT 1
"));
$clock2 = date("F d, Y H:i:s",strtotime($qclock2['ico_end']));


$paymentDate = strtotime(date("Y-m-d H:i:s"));
$jsdate = date("F d, Y H:i:s");
?>    


    <!-- Content Header (Page header) -->
    <section data-date='<?php echo date("F d, Y H:i:s"); ?>' class="content-header">
      <h1>
        ICO Information
      </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">


<div class="row">
  <div class='col-md-6'>
      <div class="box">
                  <div class="box-header with-border">
                      <h3 class="box-title">Total Available ELU</h3>
                  </div>                  
                  <div class="box-body notitxt">
                    <?php echo number_format($totalcoin); ?>
                  </div>
                  <!-- /.box-body -->
      </div>
  </div>
  <div class='col-md-6'>
      <div class="box">
                  <div class="box-header with-border">
                      <h3 class="box-title">Total ELU Sold</h3>
                  </div>                  
                  <div class="box-body notitxt">
                    <?php echo number_format($totalcoin2); ?>
                  </div>
                  <!-- /.box-body -->
      </div>
  </div>  
 </div>

<div class="row">
      <?php
        if(!empty($qclock1['ico_end']))
        {
      ?>
        <div class="col-md-6">
            <div class="box box-default">

                      <div class="box-header with-border">
                        <h3 class="box-title">ICO BUY TIME (<?php echo $clock1; ?>)</h3>
                      </div>
                      <div class="box-body">


                        <div class="clock2" style="margin:2em;"></div>
                       <script type="text/javascript">
                          var clock2;

                            $(document).ready(function() {

                              // Grab the current date
                              var currentDate2 = new Date("<?php echo $jsdate; ?>");

                              // Set some date in the future. In this case, it's always Jan 1
                              //var futureDate  = new Date(currentDate.getFullYear() + 1, 0, 1);
                              //INSERT TARGET DATE HERE
                              var futureDate2  = new Date("<?php echo $clock1; ?>");
                              console.log(futureDate2);
                              console.log(currentDate2);
                              // Calculate the difference in seconds between the future and current date
                              var diff2 = futureDate2.getTime() / 1000 - currentDate2.getTime() / 1000;

                              // Instantiate a coutdown FlipClock
                              clock2 = $('.clock2').FlipClock(diff2, {
                                clockFace: 'DailyCounter',
                                countdown: true
                              });
                            });
                        </script>

                      </div>
                      <!-- /.box-body -->
            </div>   

        </div>  
   <?php
    }
  ?>
  <?php
    if(!empty($qclock2['ico_end']))
    {
  ?>    
        <div class="col-md-6">
            <div class="box box-default">
                      <div class="box-header with-border">
                        <h3 class="box-title">ICO END AT (<?php echo $clock2; ?>)</h3>
                      </div>
                      <div class="box-body">

                          <div class="clock" style="margin:2em;"></div>
                          
                          <script type="text/javascript">
                          var clock;

                            $(document).ready(function() {

                              // Grab the current date
                              var currentDate = new Date("<?php echo $jsdate; ?>");

                              // Set some date in the future. In this case, it's always Jan 1
                              //var futureDate  = new Date(currentDate.getFullYear() + 1, 0, 1);
                              //INSERT TARGET DATE HERE
                              var futureDate  = new Date("<?php echo $clock2; ?>");

                              // Calculate the difference in seconds between the future and current date
                              var diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;

                              // Instantiate a coutdown FlipClock
                              clock = $('.clock').FlipClock(diff, {
                                clockFace: 'DailyCounter',
                                countdown: true
                              });
                            });
                        </script>
  
                      </div>
                      <!-- /.box-body -->
            </div>          
        </div>
                    <?php
                        }
                      ?>
</div>




<div class="box">
            <div class="box-header">
              <h3 class="box-title">Calendar ICO</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <table class="table table-striped">
                <tbody><tr>
                  <th>Date</th>
                  <th>ELU Token</th>
                  <th>Price (USD)</th>
                  <th>Price (BTC)</th>
                  <th>Status</th>
                </tr>
                <?php
                  while($row2=mysql_fetch_assoc($row2q)){
                ?>

                <tr>
                  <td><?php echo date("M-d-Y",strtotime($row2['ico_end'])); ?></td>
                  <td><?php echo $row2['ico_qty']; ?></td>
                  <td>
                        <?php echo $row2['ico_price']; ?>
                  </td>
                  <td>
                        <?php echo number_format($row2['ico_price'] / $_SESSION['btcvalue'],8); ?>
                  </td>
                  <?php                  
                    $contractDateBegin = strtotime($row2['ico_start']);
                    $contractDateEnd = strtotime($row2['ico_end']);
                    $status = '';
                    $colortrack = '';
                    if($paymentDate < $contractDateEnd) {
                       $status = "Waiting";
                       $colortrack = 'blue';
                    }
                    else if($paymentDate > $contractDateEnd && $row2['status']==1){
                      $status = "Done";
                      $colortrack = 'green';
                    } 
                    else if($paymentDate >= $contractDateEnd && $row2['status']==''){
                      $status = "Ongoing";
                      $colortrack = 'red';
                    }
                    else {
                       $status = "Waiting"; 
                       $colortrack = 'orange';
                    } 

                  ?>
                  <td><span class="badge bg-<?php echo $colortrack; ?>"><?php echo $status; ?></span></td>
                </tr>
                <?php    
                  }                
                ?> 
              </tbody></table>
            </div>
            <!-- /.box-body -->
          </div>

    </section>
    <!-- /.content -->