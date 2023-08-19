<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>BS: <?= $title ?></title>
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

<style type="text/css">
  .menu1{
    cursor: pointer;
  }
</style>
    


    <?= $this->renderSection("content"); ?>

    <div class="environment">
        <p>Page rendered in {elapsed_time} seconds.</p>
        <p>Environment: <?= ENVIRONMENT ?></p>
    </div>

    <div id="dialog" style="display: none;">
        Something is wrong... pls check...
    </div>

    <div class="modal" id="modalAjaxErrorMsg" role="dialog" data-backdrop="static">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header" style="background: #D9534f; color: #fff;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">ERROR</h4>
          </div>
          <div class="modal-body">
            <p id="paraAjaxErrorMsg"></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"> OK </button>
          </div>
        </div>
      </div>
    </div>
</body>



    <script type="text/javascript">
        // $(document).prop('title', "BS: " + vModuleName);
    </script>
</html>

<div id="divPrint" style="color: white;">
    
</div>

<div id="divHeader" class="header">.</div>
<div id="divFooter" class="footer">.</div>

<script type="text/javascript">
    setInterval(loadIntervalJobs, 400000 );
    function loadIntervalJobs() 
    {
        var controller='DailyCash_Controller';
        var base_url='<?php echo site_url();?>';
        $.ajax({
                'url': base_url + '/' + controller + '/loadIntervalJobs',
                'type': 'POST',
                 'global': false, /// not calling hourGlass function
                'dataType': 'json',
                'data': {
                            'dtFrom': 'ff'
                            , 'dtTo': 'gg'
                            , 'userRowId': 'tt'
                        },
                'success': function(data)
                {
                    if(data)
                    {
                        if( data['dailyCashInEntry'] == "notEntered" )
                        {
                            alert ("Daily Cash Entry IN not entered");
                        }
                        
                    }
                }
        });

      
      
      // notificationCount++;
    }
</script>