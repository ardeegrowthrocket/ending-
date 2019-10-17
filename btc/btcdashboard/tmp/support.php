    <!-- Content Header (Page header) -->




    <section class="content-header">
        <?php
        if(isset($_POST['ticketnumber'])){
        ?>

<?php
$to = ADMINEMAIL;
$noreply = ADMINEMAILNOREPLY;
$subject = "Support - Ticket# {$_POST['ticketnumber']}";

$message = "
Username: {$_SESSION['username']} <br>
Query: {$_POST['query']}
<br>
"; 
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
// More headers
$headers .= 'From: <'.$noreply.'>' . "\r\n";
$headers .= 'Cc: ' .$_SESSION['email']. "\r\n";  
sendmail($to,$subject,$message,$_SESSION['email']);
?>

                  <div class="callout callout-success">
                            <p>
                              Email sent to the support. Ticket #: <?php echo $_POST['ticketnumber']; ?> <br>
                            </p>
                  </div>
        <?php
        }
        ?>

      <h1>
        Support
        <small>Please note that administrator/support will reply on your email.</small>
      </h1>
    </section>
    <?php $ticketnumber = createRandomPassword(12); ?>
    <!-- Main content -->
    <section class="content container-fluid">
<form action='index.php?page=support' method="POST">
<textarea name='query' class="textarea" placeholder="Place question/problem here." style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" required></textarea>
<input type='hidden' name='ticketnumber' value='<?php echo $ticketnumber; ?>'>
<button type="submit" class="btn btn-block btn-primary btn-lg">Primary</button>
</form>
    </section>

    <!-- /.content -->