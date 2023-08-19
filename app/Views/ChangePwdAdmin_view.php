
<script type="text/javascript">
	vModuleName = "Change Pwd Admin";
</script>
<div class="row" style="margin: 40px 0;">
	<div class="col-md-4">
	</div>
	<div class="col-sm-4" style='border:1px solid gray; border-radius:10px; padding: 10px;box-shadow: 1px 1px 15px #888888;'>
		<h3 class="text-center" style='margin-top:0px'>Reset Password<br>(By Admin)</h3>
		<p style="color: red;"><?php echo $errMsg ?></p>
	    <?php if (isset($validation)) : ?>
            <div class="alert alert-danger" role="alert" alert-dismissabl fade in>
		        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
					&times;
				</button>
                <?= $validation->listErrors() ?>
            </div>
    	<?php endif; ?>
		<?php
			echo form_open('Changepwdadmin_Controller/checkLogin');		// checklogin will be called on submit button.

			echo form_label('User ID', '');
			echo form_dropdown('txtUID',$users,"0","class='form-control' id='txtUID'");
			echo "<br>";
			echo form_label('New Password', '');
			echo form_password('txtPassword', set_value('txtPassword'),"placeholder='New Password', class='form-control'");
			echo "<br>";
			echo form_label('Repeat Password', '');
			echo form_password('txtRepeatPassword', set_value('txtRepeatPassword'),"placeholder='Repeat New Password', class='form-control'");


			echo "<br>";
			echo "<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2'></div>";
			echo form_submit('btnSubmit', 'Submit',"class='btn btn-danger btn-block'");
			echo form_close();
		?>

	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-0">
	</div>
</div>