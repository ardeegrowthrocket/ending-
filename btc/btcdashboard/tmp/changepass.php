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
        Change Password
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
<form method='POST' action='index.php?page=changepass'>
<div class="box">
            <div class="box-body">
              <input name='current' class="form-control" type="password" placeholder="Current Password" required>
              <br>
              <input name='password' class="form-control" type="password" placeholder="New Password" required>
              <br>
              <input name='password2' class="form-control" type="password" placeholder="Confirm Password" required>
              <br>
              <button type="submit" class="btn btn-block btn-primary btn-lg">Submit</button>
            </div>
            <!-- /.box-body -->
</div>
</form>
    </section>
    <!-- /.content -->