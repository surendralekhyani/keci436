<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Billing System</title>
	<script type="text/javascript" src="<?php echo base_url(); ?>/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>/js/bootstrap.min.js"></script>
	<link rel='stylesheet' href='<?php echo base_url();  ?>/css/bootstrap.css'>
	<link href="<?php echo base_url(); ?>/images/diamond.png" rel="shortcut icon" type="image/x-icon" />
	<link rel='stylesheet' href='<?php echo base_url();  ?>/css/loginstyle.css'>
	<link href='//fonts.googleapis.com/css?family=Satisfy|Dosis' rel='stylesheet'>


	<script type="text/javascript">
		vModuleName = "Login";

		function validate_form() {
			var userName = $("#txtUID").val();
			if (userName == "") {
				alert("Username required...");
				$("#txtUID").focus();
				return false;
			}
			var password = $("#txtPassword").val();
			if (password == "") {
				alert("Password required...");
				$("#txtPassword").focus();
				return false;
			}
		}
	</script>
</head>

<body>
	<div class="pen-title">
		<h4 style="font-family: 'Dosis';font-size: 24px; color: #1bcaff; ">
			<?php
			if (count($orgInfo) > 0) {
				echo $orgInfo[0]['orgName'];
			}
			?>
		</h4>
		<h4 style="font-family: 'Satisfy';font-size: 18px; color: #666666;">Billing System</h4>
	</div>
	<?php if(session()->getFlashdata('msg')):?>
		<div class="col-md-4 alert alert-danger" role="alert" alert-dismissabl fade in>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
				&times;
			</button>
			<?= session()->getFlashdata('msg') ?>
		</div>
	<?php endif;?>
	
	<div class="module form-module">
		<div class="toggle">
		</div>
		<div class="form">
			<h2>Login to your account</h2>
			
			<p style="color: red;"><?php echo $errMsg ?></p>

			<?php
				$attributes = array('onsubmit' => 'return validate_form(this)'); //
				echo form_open('Login_controller/checkLogin', $attributes);
			?>
			<input type="text" id="txtUID" value="admin" name="txtUID" value="<?= old('txtUID');?>" maxlength="20" placeholder="Username" autofocus autocomplete="off" />
			<input type="password" id="txtPassword" value="modicommbill" name="txtPassword" value="<?= old('txtPassword');?>" maxlength="20" placeholder="Password" autocomplete="off" />
			
			<!-- CSRF token -->
			<input type="hidden" class="clfName" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
			<input type="submit" value="Login" required style="margin-top: 30px;margin-bottom: -10px; background-color: #666666; color: #ffffff; font-size: 16px;" />

			</form>
		</div>
		<div class="cta"><a href="#" style="color:grey;">Developed by <span style="color:black;">Imperial Technologies</span> 9929598700</a></div>
	</div>
</body>

</html>
<script type="text/javascript">
	$('#errView').fadeTo(5000, 500).slideUp(1000, function() {
		// $('.alert').alert('close');
	});
</script>