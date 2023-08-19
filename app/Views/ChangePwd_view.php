
<script type="text/javascript">
	vModuleName = "Change Pwd";
</script>
<div class="row" style="margin: 40px 0 140px 0;">
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-0">
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style='border:1px solid gray; border-radius:10px; padding: 10px;box-shadow: 1px 1px 15px #888888;'>
		<h2 class="text-center" style='margin-top:0px'>Change Password</h2>
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

			
			echo form_open('Changepwd_Controller/checkLogin');		

			echo form_label('Old Password', '');
			echo form_password('txtOldPassword', set_value('txtOldPassword'),"placeholder='', class='form-control' autofocus");
			// echo form_error('txtOldPassword');
			echo form_label('New Password', '');
			echo form_password('txtPassword', set_value('txtPassword'),"placeholder='', class='form-control'");
			// echo form_error('txtPassword');
			echo form_label('Repeat Password', '');
			echo form_password('txtRepeatPassword', set_value('txtRepeatPassword'),"placeholder='', class='form-control'");
			// echo form_error('txtRepeatPassword');


			echo "<br>";
			// echo "<div class='row'>";
			echo "<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2'></div>";
			echo "<div class='col-lg-8 col-md-8 col-sm-8 col-xs-8'>";
			echo form_submit('btnSubmit', 'Submit',"class='btn btn-danger col-lg-12'");
			echo "</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2'></div>";
			// echo "</div>";
			echo form_close();
		?>

	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-0">
	</div>
</div>