<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Billing System</title>
	<script type="text/javascript" src="<?php echo base_url(); ?>/public/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>/public/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>/public/js/jquery.blockUI.js"></script>
	<link rel='stylesheet' href='<?php  echo base_url(); ?>/public/css/bootstrap.css'>

	<!-- DataTables CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/datatable/jquery.dataTables.min.css">
	<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/jquery.dataTables.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/datatable/select.dataTables.min.css">
	<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/dataTables.select.min.js"></script>


	<!-- UI like dialog date picker (like alert) -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/ui/jquery-ui.css" />
	<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/ui/jquery-ui.js"></script>
	<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/ui/jquery-ui.min.js"></script>
	

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/css/printstyle.css" />

	<!-- My Java Script Library -->
	<script type="text/javascript">
		var global_base_url='<?php echo base_url();?>';
	</script>
	<script type="text/javascript" src="<?php echo base_url(); ?>/public/js/mylibrary.js"></script>
	
	<style>
		.environment {
			background-color: rgb(0, 0, 0);
			color: rgba(255, 255, 255, 1);
			padding: 2rem 1.75rem;
			text-align: center;
			margin-top: 10px;
		}
		/*table row selection*/
		.highlight
		{
			background-color: #337ab7 !important;
			color: white;
			/*font-weight: bold;*/
		}
		.highlightAlag
		{
			/*background-color: #337ab7 !important;*/
			/*color: white;*/
			font-weight: bold;
		}

		/*.ui-dialog .ui-dialog-titlebar .msgBoxTitleColor { background: yellow; }*/
	</style>


	<script>
		onerror = myError;
		function myError(msg, url, line)
		{
			alert(msg + "\n" + url + "\n" + line);
			return true;
		}
	</script>
</head>

<body style="padding-top:85px;">
	<script type="text/javascript">
		
  		var tm = new Date();
  		tm = '<h3>Loading... Pls. wait...<h3><span style="color:yellow;">' + tm.getHours() + ":" + tm.getMinutes()+ ":" + tm.getSeconds() + '</span>'  ;
        $.blockUI({ 
            message: tm, 
            css: { 
	            border: 'none', 
	            padding: '15px', 
	            backgroundColor: '#000', 
	            '-webkit-border-radius': '10px', 
	            '-moz-border-radius': '10px', 
	            opacity: .5, 
	            color: '#fff',
	        }
        }); 
	</script>