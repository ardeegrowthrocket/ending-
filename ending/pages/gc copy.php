<?php
require_once("./connect.php");
$accounts_id = $_SESSION['accounts_id'];
$q = mysql_query_cheat("SELECT * FROM tbl_accounts WHERE accounts_id='$accounts_id'");
$row = mysqli_fetch_array_cheat($q);
function pin()
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < 7; $i++) {
        $randstring .= $characters[rand(0, strlen($characters))];
    }
    return $randstring;
}

	if($_POST['submit']!='')
	{
	$check_rate = mysql_query_cheat("SELECT * FROM tbl_rate WHERE rate_id='".$_POST['rate']."'");
	$check_row  = mysqli_fetch_array_cheat($check_rate);
		if($_POST['password']!=$row['password'])
		{
						$error .= "<i class=\"fa fa-warning\"></i>Password incorrect!.<br>";
		}
		
		if($check_row['rate_start']>$row['balance'])
		{
			$error .= "<i class=\"fa fa-warning\"></i>Insufficient Funds!.<br>";
		}
		if($error=='')
		{
		$current_balance  = $row['balance'] - $check_row['rate_start'];
		$rebates = $check_row['rate_start'] * 0.10;
		$code_pin = pin();
		$code_value = pin();
		$code_package = $_POST['rate'];
		$code_referrer = $accounts_id;
		mysql_query_cheat("INSERT INTO tbl_code SET code_value='$code_value',code_package='$code_package',code_pin='$code_pin',code_referrer='$code_referrer',activated='1'");
		mysql_query_cheat("UPDATE tbl_accounts SET balance='$current_balance' WHERE accounts_id='$accounts_id'");
		
		//rebates
		mysql_query_cheat("UPDATE tbl_accounts SET balance=balance + $rebates WHERE accounts_id='$accounts_id'");
		mysql_query_cheat("INSERT INTO tbl_bonus SET amount='$rebates',accounts_id='$accounts_id',bonus_type='rb'");
		//history
		$package_id = $check_row['rate_id'];
		$package_summary = $check_row['rate_name']. " - ".$check_row['rate_start'];
		mysql_query_cheat("INSERT INTO tbl_buycode_history SET package_id='$package_id',package_summary='$package_summary',accounts_id='$accounts_id',code_pin='$code_pin',code_value='$code_value',rebates='$rebates'");
		$q = mysql_query_cheat("SELECT * FROM tbl_accounts WHERE accounts_id='$accounts_id'");
		$row = mysqli_fetch_array_cheat($q);	
		$success = "Here is the pin $code_value - $code_pin";
		}
	}



$package_query = mysql_query_cheat("SELECT * FROM tbl_rate WHERE activated='1' AND rate_bet!=2");	
$arr = array();
while($row_package = mysqli_fetch_array_cheat($package_query))
{
	$arr[$row_package	['rate_id']] = $row_package['rate_name']. " - ".$row_package['rate_start'];
}
	
$field[] = array("type"=>"select","value"=>"rate","label"=>"Choose Your Package","option"=>$arr);
$field[] = array("type"=>"password","value"=>"password","label"=>"Enter Password");
//
?>
<h2>Buy Code - Balance(<?php echo $row['balance'];?>)</h2>   
<?php
if($error!='')
{
?>
<div class="warning"><ul class="fa-ul"><li><?php echo $error;?></li></ul></div>
<?php
}
?>


<?php
if($success!='')
{
?>
<div class="noti"><ul class="fa-ul"><li><i class="fa fa-check fa-li"></i>
<?php echo $success; ?>
</li></ul></div>
<?php
}
?>



<form method='POST' action=''>
<table width="100%">
						<?php
						$is_editable_field = 1;
						foreach($field as $inputs)
						{
												if($inputs['label']!='')
												{
												$label = $inputs['label'];
												}
												else
												{
												$label = ucwords($inputs['value']);
												}
						?>
									<!---weee--->
										<tr>
											<td style="width:180px;" class="key" valign="top" ><label for="accounts_name"><?php echo $label; ?><?php echo $req_fld?>:</label></td>
											<?php if ( $is_editable_field ) { ?>
											<td>
											<?php
											if ($inputs['type']=='select')
											{
												if($$inputs['value']!='' && $inputs['value']=='code_id')
												{ 
												 $code = $$inputs['value'];
												 $codeqq = mysql_query_cheat("SELECT * FROM tbl_code as a JOIN tbl_rate as b JOIN tbl_accounts as c WHERE c.code_id=a.code_value AND a.code_value='$code' AND a.code_package=b.rate_id");
												 $coderow = mysqli_fetch_array_cheat($codeqq);
												 
												//asds
												
												echo "Package Name"." : ".$coderow['rate_name'];
												echo "<br>";
												echo "Registration FEE"." : ".$coderow['rate_start'];
												echo "<br>";
												echo "Salary"." : ".$coderow['rate_end'];
												echo "<br>";
												echo "Code Value - Code Pin"." : ".$coderow['code_value']." - ".$coderow['code_pin'];
												echo "<br><br>";
												echo "Total Earnings"." : ".$coderow['total_earnings'];
												echo "<br>";
												echo "Current Balance"." : ".$coderow['balance']; 
												//
												}
												else
												{
													
												
											
												?>
												<select name="<?php echo $inputs['value']; ?>" id="<?php echo $inputs['value']; ?>" required <?php echo $inputs['attr']; ?>>
												<?php
												foreach($inputs['option'] as $key=>$val)
												{
													?>
													<option <?php if($$inputs['value']==$val){echo"selected='selected'";} ?> value='<?php echo $key;?>'><?php echo $val;?></option>
													<?php
												}
												?>
												</select>
												<span class="validation-status"></span>
												<?php
												}
											}
											else
											{
												?>
												<input required <?php echo $inputs['attr']; ?> type="<?php echo $inputs['type']; ?>" name="<?php echo $inputs['value']; ?>" id="<?php echo $inputs['value']; ?>" size="40" maxlength="255" value="<?php echo $$inputs['value']; ?>" />
												<span class="validation-status"></span>												
												<?php
											}
											?>

											</td>
											<?php } else { ?>
											<td><?php echo $$inputs['value']; ?></td>
											<?php } ?>                                                                                                    
										</tr>
						<?php
						}
						?>
</table>
<br/>
<center><input class='btn btn-primary btn-lg' type='submit' name='submit' value='Process'></center>
</form>