<?php
require_once("./connect.php");
$accounts_id = $_SESSION['accounts_id'];
$q = mysql_query_cheat("SELECT * FROM tbl_accounts WHERE accounts_id='$accounts_id'");
$row = mysqli_fetch_array_cheat($q);
function trans()
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < 12; $i++) {
        $randstring .= $characters[rand(0, strlen($characters))];
    }
    return $randstring;
}

	if($_POST['submit']!='')
	{
		if($_POST['password']!=$row['password'])
		{
			$error .= "<i class=\"fa fa-warning\"></i>Password do not match.<br>";
		}
		
		if($error=='')
		{
		//$summary = nl2br($summary);
		mysql_query_cheat("INSERT INTO  tbl_transaction SET  transaction_key='{$_POST['fields']['transaction_key']}',transaction_userid='{$accounts_id}'");
		$q = mysql_query_cheat("SELECT * FROM tbl_accounts WHERE accounts_id='$accounts_id'");
		$row = mysqli_fetch_array_cheat($q);	
		$success = 1;	
		}
	}
//
?>
<h2>Transactions</h2>   
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
<div class="noti"><ul class="fa-ul"><li><i class="fa fa-check fa-li"></i>Done sending transaction id please wait for admin to review.</li></ul></div>
<?php
}
?>
<?php
$payment = array();
$fields = array();
$fields['transaction_key'] = "Transaction ID";
$payment['btc'] = $fields;


?>

<?php
	foreach($payment as $paychildkey=>$paychild){
		echo "<table id='$paychildkey' style='display:none;'>";
		foreach($paychild as $pkey=>$pval){	
				?>
				 <tr class='antibug'>
					<td style="width:180px;" class="key" valign="top"><label for="accounts_names"><?php echo $pval; ?>:</label></td>
					<td>
					   <input  autocomplete="off" style="width: 302px;" required="" type="text" name="fields[<?php echo $pkey; ?>]" id="withdraw" size="40" maxlength="255" value="">
					   <span class="validation-status"></span>												
					</td>
				 </tr>				
				<?php
		}
		echo "</table>";
	}
?>


<form method="POST" action="" autocomplete="off">
   <table width="100%">
      <tbody>

         <tr style='display:none;'>
            <td style="width:180px;" class="key" valign="top"><label for="accounts_name">Widrawal Type</label></td>
            <td>
				<select id='claimtypeid' name='claimtype' onchange="widraw(this.value)" required>
						<option value='cebuana'>Cebuana</option>
						<option value='lbc'>LBC</option>
						<option value='bank_bpi'>BPI Deposit</option>
						<option value='bank_bdo'>BDO Deposit</option>
						<option value='btc' selected='selected'>BTC</option>
				</select>											
            </td>
         </tr>
	</table>
		 <table id='optionspayment'>
		 </table>  
   <table id='defaultfield' width="100%" style='display:none;'>
      <tbody>					 
		 
         <tr class="antibug">
            <td style="width:180px;" class="key" valign="top"><label for="accounts_name">Please enter password:</label></td>
            <td>
               <input style="width: 302px;" required="" type="password" name="password" id="password" size="40" maxlength="255" value="">
               <span class="validation-status"></span>												
            </td>
         </tr>
      </tbody>
   </table>
 
   <br>
   <center><input class="btn btn-primary btn-lg" type="submit" name="submit" value="Process"></center>
</form>
<script>
	jQuery('#claimtypeid').trigger('change');
function widraw(myval)
{
	if(myval){
		jQuery('#defaultfield').show();
	}else{
		jQuery('#defaultfield').hide();
	}

	jQuery('#optionspayment').html('');
	jQuery('#optionspayment').html(jQuery('#'+myval).html());

}
</script>