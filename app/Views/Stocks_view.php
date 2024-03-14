<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/datatable/buttons.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/buttons.colVis.min.js"></script>
<link rel='stylesheet' href='<?php  echo base_url();  ?>/public/css/suriprint.css'>

<style type="text/css">
	.ui-autocomplete {
	    max-height: 200px;
	    overflow-y: auto;   /* prevent horizontal scrollbar */
	    overflow-x: hidden; /* add padding to account for vertical scrollbar */
	    z-index:1000 !important;
	}
    #txtDate {
            z-index:1001 !important;
        }

</style>


<script type="text/javascript">
	
	var controller='Stocks_Controller';
	var base_url='<?php echo site_url();?>';

	vModuleName = "Stocks";

	vGlobalVtypeForPrint="";


	function setTable(records, recordsCurrentStocks)
	{
        // console.log(recordsCurrentStocks);
		$("#tbl1").empty();
	      var table = document.getElementById("tbl1");
		  var tmp=0;
	      for(i=0; i<records.length; i++)
	      {
	          newRowIndex = table.rows.length;
	          row = table.insertRow(newRowIndex);
	          var cell = row.insertCell(0);
	          cell.style.display="none";
	          var cell = row.insertCell(1);
	          cell.style.display="none";
	          var cell = row.insertCell(2);
	          cell.innerHTML = records[i].stockRowId;
	          cell.style.display="none";
	          var cell = row.insertCell(3);
	          cell.innerHTML = records[i].dt.split("-").reverse().join("-"); 
	          var cell = row.insertCell(4);
	          cell.innerHTML = records[i].stockName;
			  cell.innerHTML = "<a id='contraac' href='#'>" + records[i].stockName + "</a>";
			  cell.classList.add("clsStockLedger");
	          var cell = row.insertCell(5);
	          cell.innerHTML = records[i].qty;
			  cell.style.textAlign = "right";
	          var cell = row.insertCell(6);
			  cell.innerHTML = records[i].rate;
			  cell.style.textAlign = "right";
	          var cell = row.insertCell(7);
			  cell.innerHTML = (records[i].qty * records[i].rate).toFixed(2);
			  cell.style.textAlign = "right";
	          var cell = row.insertCell(8);
	          cell.innerHTML = records[i].charges;
			  cell.style.textAlign = "right";
			  cell.classList.add("clsCharges");
			  var cell = row.insertCell(9);
			  cell.style.display = "none";
	          var cell = row.insertCell(10);
	          cell.innerHTML = records[i].buySell;
			  cell.style.textAlign = "center";
			  if(records[i].buySell == "B")
			  {
				cell.style.background = "#FF0000";
				cell.style.color = "#FFFFFF";
			  }
	  	  }
		//   $(".clsCharges").on('click', setChargesEditable);
		//   $(".clsBtnSaveCharges").on('click', saveCharges);

          $("#tblStocksCurrent").empty();
	      var table = document.getElementById("tblStocksCurrent");
		  var tmp=0;
		  let investedAmt=0;
	      for(i=0; i<recordsCurrentStocks.length; i++)
	      {
	          newRowIndex = table.rows.length;
	          row = table.insertRow(newRowIndex);
	          var cell = row.insertCell(0);
	          cell.innerHTML = recordsCurrentStocks[i].stockCurrentRowId;
	          cell.style.display="none";
	          var cell = row.insertCell(1);
			  cell.innerHTML = "<a id='contraac' href='#'>" + recordsCurrentStocks[i].stockName + "</a>";
			  cell.classList.add("clsStockLedger");
	          var cell = row.insertCell(2);
	          cell.innerHTML = recordsCurrentStocks[i].qty;
			  cell.style.textAlign = "right";
	          var cell = row.insertCell(3);
			  cell.innerHTML = recordsCurrentStocks[i].avgRate;
			  cell.style.textAlign = "right";
	          var cell = row.insertCell(4);
	          cell.innerHTML = recordsCurrentStocks[i].value;
			  cell.style.textAlign = "right";
			  investedAmt += parseFloat(recordsCurrentStocks[i].value);
	  	  }

		    $("#lblInvestedAmt").text( investedAmt.toFixed(2) );

			$(".clsStockLedger").on("click", callStockLedger);
			$('.clsStockLedger').click(function () {
				$('#myModalStockLedger').modal('toggle');
			});
	  	  
		myDataTableCurrentStock.destroy();
		$(document).ready( function () {
		    myDataTableCurrentStock = $('#tblStocksCurrent').DataTable({
			    paging: false,
			    iDisplayLength: -1,
			    aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
			    dom: 'Bfrtip',
			    select: true,
			    order: [[0, 'asc']],
		        buttons: [
		            'copyHtml5',
		            {
		                extend: 'excel',
		                title: 'Ledger (' + $("#dtFrom").val() + " to " + $("#dtTo").val() + ")",
		                messageTop: $("#txtCustomerName").val(),
		                messageBottom: 'End Of Doc'
		            },
		            {
		                extend: 'colvis',
		                collectionLayout: 'fixed two-column',
		                columnText: function ( dt, idx, title ) {
			                return (idx+1)+': '+title;
			            },
		            }
		        ]
			});

			myDataTable.destroy();
			myDataTable = $('#tbl1').DataTable({
				paging: false,
				iDisplayLength: -1,
				aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
				dom: 'Bfrtip',
				select: true,
				buttons: [	
					'copyHtml5',
					'excelHtml5',
					'csvHtml5'
				]
			});



			
		} );

	}

	function callStockLedger()
	{
		let investor = $("#cboInvestor").val();
		if(investor == "-1")
		{
			return;
		}

		stockName = "";
		stockName = $(this).text();
		$("#tblStockLedger").find("tr:gt(0)").remove(); //// empty first
		$("#h4StockLedger").html("<b><span style='color:red;'>" + stockName + "</span></b>" );
		$.ajax({
			'url': base_url + '/' + controller + '/getStockLedger',
			'type': 'POST', 
			'data':{'stockName':stockName, 'investor':investor},
			'dataType': 'json',
			'success':function(data)
			{
				setStockLedgerTable(data['records']);
			},
			error: function (jqXHR, exception) {
					ajaxCallErrorMsg(jqXHR, exception)
			}
		});
	}

	
	function setStockLedgerTable(records)
	{
		$("#tblStockLedger").find("tr:gt(0)").remove(); //// empty first
        var table = document.getElementById("tblStockLedger");
		let tot=0;

        for(i=0; i<records.length; i++)
		{
	        newRowIndex = table.rows.length;
			row = table.insertRow(newRowIndex);

          	var cell = row.insertCell(0);
          	cell.innerHTML = "<input type='checkbox' id='chk' class='chk' name='chk' style='width:20px;height:20px;vertical-align: middle;'/> " + records[i].dt.split("-").reverse().join("-");
			cell.contentEditable = true;
          	var cell = row.insertCell(1);
			if(records[i].buySell == "B")
			{
          		qty = records[i].qty * -1;
				row.style.color = "red";
			}
			else
			{
				qty = records[i].qty;
			}
			cell.innerHTML = qty;
			cell.style.textAlign = "right";
          	var cell = row.insertCell(2);
          	cell.innerHTML = records[i].rate;
			cell.style.textAlign = "right";
          	var cell = row.insertCell(3);
          	cell.innerHTML = (qty * records[i].rate).toFixed(2);
			cell.style.textAlign = "right";
          	var cell = row.insertCell(4);
          	cell.innerHTML = records[i].charges;
			cell.contentEditable = true;
			cell.style.textAlign = "right";
		  	var cell = row.insertCell(5);
			net = qty * records[i].rate - records[i].charges;
          	cell.innerHTML = (net).toFixed(2);
			tot += parseFloat(net);
			cell.style.textAlign = "right";
		  	var cell = row.insertCell(6);
          	cell.innerHTML = records[i].buySell;
			cell.style.textAlign = "center";
		  	var cell = row.insertCell(7);
          	cell.innerHTML = records[i].sattled;
			cell.contentEditable = true;
		 	cell.style.textAlign = "center";
		  	var cell = row.insertCell(8);
          	cell.innerHTML = records[i].stockRowId;
			cell.style.display = "none";
			var cell = row.insertCell(9);
			cell.innerHTML = "<button class='clsBtnSaveEditedStock btn btn-primary form-control'>Save</button>";
        }
		$(".chk").on('click', doTotalOfCheckedStocks);
		$(".clsBtnSaveEditedStock").on('click', saveEditedStock);
		$("#lblNetProfitOrLoss").text("Total of Sattled: " + tot.toFixed(2));
	}

	function doTotalOfCheckedStocks()
	{
		let total=0;
		$('#tblStockLedger tr:gt(0)').each(function(row, tr)
	    {
			if($(tr).find('td:eq(0)').find('input[type=checkbox]').is(':checked'))
			{
				amt = $(tr).find('td:eq(5)').text();
				total += parseFloat(amt);
			}
	    }); 
		
		$("#lblTotalOfCheckedStocks").text("Total of Checked: " + total.toFixed(2));
	}

	function saveEditedStock()
	{
		rowIndex = $(this).parent().parent().index();
		stockRowId = $(this).closest('tr').children('td:eq(8)').text();
		dt = $(this).closest('tr').children('td:eq(0)').text().trim().split("-").reverse().join("-");
		console.log(dt);

		// Regular expression to match the yyyy-mm-dd format
		// var regex = /^\d{4}-\d{2}-\d{2}$/;
		var regex = /^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2]\d|3[0-1])$/;
        isDateOk = regex.test(dt);
		if( !isDateOk )
		{
			alert("Invalid Date format DD-MM-YYYY...");
			return;
		}

		charges = $(this).closest('tr').children('td:eq(4)').text();
		if(charges == "" || isNaN(charges))
		{
			alert("Invalid Value to save...");
			return;
		}

		sattled = $(this).closest('tr').children('td:eq(7)').text().toUpperCase();
		if(!(sattled == "Y" || sattled == "N"))
		{
			alert("Invalid Value of SATTLED...");
			return;
		}
		$.ajax({
			'url': base_url + '/' + controller + '/saveEditedStock',
			'type': 'POST',
			'dataType': 'json',
			'data': {
						'stockRowId': stockRowId
						, 'dt': dt
						, 'charges': charges
						, 'sattled': sattled
					},
			'success': function(data)
			{
				if(data)
				{
					$("#tblStockLedger").find("tr:eq(" + rowIndex + ")").find("td:eq(9)").find('.clsBtnSaveEditedStock').text('SAVED');
				}
			},
				error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
		});	
	}


	function loadData()
	{	
		$("#btnGetProfitOfSattled").text("Get Profit Amt")
		let investor = $("#cboInvestor").val();
		if(investor == "-1")
		{
			$("#tbl1").empty();
			$("#tblStocksCurrent").empty();
			$("#lblInvestedAmt").text(0);
			return;
		}


		$.ajax({
				'url': base_url + '/' + controller + '/showData',
				'type': 'POST',
				'dataType': 'json',
				'data': {
							'investor': investor
							, 'dtTo': 'dtTo'
						},
				'success': function(data)
				{
					if(data)
					{
						// alert(JSON.stringify(data));
                        setTable(data['records'], data['recordsCurrentStocks']);
                        alertPopup('Records loaded...', 4000);
					}
				},
				error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
		});
		
	}

	
	function saveData()
	{	
		let investor = $("#cboInvestor").val();
		if(investor == "-1")
		{
			$("#tbl1").empty();
			$("#tblStocksCurrent").empty();
			$("#lblInvestedAmt").text(0);
			return;
		}

		dt = $("#txtDate").val().trim();
		dtOk = testDate("txtDate");
		if(dtOk == false)
		{
			alertPopup("Invalid date...", 5000);
			$("#txtDate").focus();
			return;
		}
		mode = $("#cboMode").val();
		if(mode == "-1" )
		{
			alertPopup("Select transaction mode...", 8000, 'red');
			$("#cboMode").focus();
			return;
		}

		stockName = $("#txtStockName").val().trim();
		if(stockName == "")
		{
			alert("Stock name can not be blank...");
			$("#txtStockName").focus();
			return;
		}

		qty = parseFloat($("#txtQty").val());
		if(qty <= 0 || isNaN(qty))
		{
			alert("Invalid Qty...");
			return;
		}
		rate = parseFloat($("#txtRate").val());
		if(rate < 0 || isNaN(rate))
		{
			alert("Invalid rate...");
			return;
		}
		charges = parseFloat($("#txtCharges").val());

		if($("#btnSave").val() == "Save")
		{
			$.ajax({
					'url': base_url + '/' + controller + '/insert',
					'type': 'POST',
					'dataType': 'json',
					'data': {
								'investor': investor
								, 'stockName': stockName
								, 'dt': dt
								, 'mode': mode
								, 'qty': qty
								, 'rate': rate
								, 'charges': charges
							},
					'success': function(data)
					{
						// console.log(data);
						if(data)
						{
							// console.log(data[0]);
							if(data[0] == "error mili...")
							{
								alert(JSON.stringify(data));
							}
							else
							{
								pDt = $("#txtDate").val();
								investor = $("#cboInvestor").val();
								setTable(data['records'], data['recordsCurrentStocks']) ///loading records in tbl1
								alertPopup('Record saved...  ', 4000);
								blankControls();
								$("#txtDate").val(pDt);
								$("#cboMode").focus();
								$("#cboInvestor").val(investor);
								// location.reload();
							}
						}
							
					},
					error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});
		}
	}
</script>

<div class="container-fluid">
	<div class="col-md-6 col-xs-12">
		<div class="row">
			<div>
				<h3 class="text-center" style='margin-top:-20px'>Stocks</h3>
				<form name='frm' id='frm' method='post' enctype='multipart/form-data' action="">
					<div class="row" style="margin-top:15px;">
					<div class="col-md-3 col-xs-12" style='margin-top: 0px;'>
						<?php
							$investor = array();
							$investor['-1'] = '--- Investor ---';
							$investor['SL'] = "Suri";
							$investor['DL'] = "Deepu";
							$investor['Shop'] = "Shop";
							echo form_dropdown('cboInvestor', $investor, '-1',"class='form-control' id='cboInvestor'");
						?>   
					</div>
						<div class="col-md-3 col-xs-12">
							<?php
								echo "<input type='button' onclick='loadData();' value='Show Data' id='btnShow' class='btn btn-primary form-control'>";
							?>
						</div>
						<div class="col-md-3 col-xs-12">
							<!-- <span style="font-size: 14px;" class="label label-danger">Danger</span> -->
							<label id="lblInvestedAmt" style="font-size: 24px; color: red;">dd</label>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="row" style="margin-top:20px;">
			<div id="divTable" class="divTable col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border:1px solid lightgray; padding: 10px;height:470px; overflow:auto;">
				<table class='table table-hover' id='tblStocksCurrent'>
				<thead>
					<tr>
						<th style='display:none;'>Rowid</th>
						<th style='display:none1;'>Stock</th>
						<th style='display:none1; text-align:right;'>Qty</th>
						<th style='display:none1; text-align:right;'>Avg. Price</th>
						<th style='display:none1; text-align:right;'>Value</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
				</table>
			</div>
		</div>
		<div class="row" style="margin-top:20px;">
			<div class="col-md-3 col-xs-12">
				<button id='btnEditCurrentStocks' class='btn btn-primary form-control'>Edit Current Stocks</button>;
			</div>
			<div class="col-md-3 col-xs-12">
				<button id='btnGetProfitOfSattled' class='btn btn-primary form-control'>Get Profit of Sattled</button>;
			</div>
		</div>
	</div>
	<div class="col-md-6 col-xs-12">
		<div class="col-md-6 col-xs-12" style='margin-top: 10px;'>
			<?php
				echo form_input('txtDate', '', "class='form-control' id='txtDate' style='' maxlength=11 autocomplete='off' placeholder='date'");
			?>
		</div>
		<div class="col-md-6 col-xs-12" style='margin-top: 10px;'>
			<?php
				$modes = array();
				$modes['-1'] = '--- Select ---';
				$modes['B'] = "Buy";
				$modes['S'] = "Sell";
				echo form_dropdown('cboMode', $modes, '-1',"class='form-control' id='cboMode'");
			?>   
		</div>
		<div class="col-md-12 col-xs-12" style='margin-top: 10px;'>
			<?php
				echo form_input('txtStockName', '', "class='form-control' id='txtStockName' class='clsTxtStockName' style='' maxlength=70 autocomplete='off' placeholder='Stock Name'");
			?>
		</div>
		<div class="col-md-6 col-xs-12" style='margin-top: 10px;'>
			<?php
				echo form_input('txtQty', '', "class='form-control' id='txtQty' style='' maxlength=15 autocomplete='off' placeholder='Qty'");
			?>
		</div>
		<div class="col-md-6 col-xs-12" style='margin-top: 10px;'>
			<?php
				echo form_input('txtRate', '', "class='form-control' id='txtRate' style='' maxlength=15 autocomplete='off' placeholder='Rate'");
			?>
		</div>
		<div class="col-md-6 col-xs-12" style='margin-top: 10px;'>
			<?php
				echo form_input('txtValue', '', "class='form-control' id='txtValue' style='' maxlength=15 autocomplete='off' placeholder='Value' readonly");
			?>
		</div>
		<div class="col-md-6 col-xs-12" style='margin-top: 10px;'>
			<?php
				echo form_input('txtCharges', '', "class='form-control' id='txtCharges' style='' maxlength=15 autocomplete='off' placeholder='Charges'");
			?>
		</div>

		<div class="col-md-6 col-xs-12" style='margin-top: 20px;'>
		</div>
		<div class="col-md-6 col-xs-12" style='margin-top: 20px;'>
			<?php
				echo "<input type='button' onclick='saveData();' value='Save' id='btnSave' class='btn btn-primary form-control'>";
			?>
		</div>
		<div class="col-md-12 col-xs-12" style='margin-top: 40px;'>
				<div id="tblStock" class="divTable tblScroll" style="border:1px solid lightgray; height:400px; overflow:auto;">
					<table class='table table-bordered' id='tbl1'>
						<thead>
						<tr>
							<th  width="50" class="editRecord text-center" style='display:none;'>Ed</th>
							<th  width="50" class="text-center" style='display:none;'>Del</th>
							<th style='display:none;'>Rowid</th>
							<th style='display:none1;'>Date</th>
							<th style='display:none1;'>Stock</th>
							<th style='display:none1; text-align:right;'>Qty</th>
							<th style='display:none1; text-align:right;'>Rate</th>
							<th style='display:none1; text-align:right;'>Amt</th>
							<th style='display:none1; text-align:right;'>Charges</th>
							<th style='display:none; text-align:right;'></th>
							<th style='display:none1;'>B / S</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-6 col-xs-12" style='margin-top: 20px;'>
                <?php
                    echo "<input type='button' onclick='loadAllRecords();' value='Load All Records' id='btnLoadAllRecords' class='btn btn-primary form-control'>";
                ?>
            </div>
	</div>
</div>


		<!-- Model Stock Ledger-->
		<div class="modal" id="myModalStockLedger" role="dialog">
		    <div class="modal-dialog modal-lg" style="width: 80%;">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title" id="h4StockLedger">Stock Ledger</h4>
		        </div>
		        <div class="modal-body" style="overflow: auto; height: 500px;">
		          <table id='tblStockLedger' class="table table-stripped">
		          		<th>[Date]</th>
					 	<th style='text-align:right;'>Qty</th>
					 	<th style='text-align:right;'>Rate</th>
					 	<th style='text-align:right;'>Value</th>
					 	<th style='text-align:right;'>[Charges]</th>
					 	<th style='text-align:right;'>Net</th>
					 	<th style='text-align:center;'>B / S</th>
					 	<th style='text-align:center;'>[Satlled]</th>
					 	<th style='display: none;'>StockRowId</th>
					 	<th style='text-align:center;'>Save</th>
		          </table>
		        </div>
		        <div class="modal-footer">
		        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
						<label id="lblTotalOfCheckedStocks" style="color:blue;"></label>
		        		<!-- <button type="button" class="btn btn-block btn-primary" onclick="loadAllOfThisStock();">Load All (This Stock)</button> -->
		        	</div>
		        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		        	</div>
		        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
						<label id="lblNetProfitOrLoss" style="color:red;"></label>
					</div>
		        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		        		<button type="button" class="btn btn-block btn-default" data-dismiss="modal">Close</button>
		        	</div>
		        </div>
		      </div>
		    </div>
		</div>		  


		<!-- Model Edit Cur Stocks-->
		<div class="modal" id="myModalEditCurrentStocks" role="dialog">
		    <div class="modal-dialog modal-lg">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title" id="h4StockLedger">Edit Current Stocks</h4>
		        </div>
		        <div class="modal-body" style="overflow: auto; height: 500px;">
		          <table id='tblEditCurrentStocks' class="table table-stripped">
		          		<th style='display:none1;'>ScRowId</th>
					 	<th style='display:none1;'>Name</th>
					 	<th style='display:none1; text-align:right;'>Qty</th>
					 	<th style='display:none1; text-align:right;'>Avg. Rate</th>
					 	<th style='display:none1; text-align:center;'></th>
		          </table>
		        </div>
		        <div class="modal-footer">
		        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		        	</div>
		        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		        		
		        	</div>
		        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
						<label id="lblNetProfitOrLoss" style="color:red;"></label>
					</div>
		        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		        		<button type="button" class="btn btn-block btn-default" data-dismiss="modal">Close</button>
		        	</div>
		        </div>
		      </div>
		    </div>
		</div>		  



<script type="text/javascript">
	$(document).ready( function () {

        $( "#txtDate" ).datepicker({
			dateFormat: "dd-M-yy",changeMonth: true,changeYear: true,yearRange: "2010:2050"
		});
	    // Set the Current Date as Default
		$("#txtDate").val(dateFormat(new Date()));

		$("#txtQty").on('keyup', setValue);
		$("#txtRate").on('keyup', setValue);


		myDataTableCurrentStock = $('#tblStocksCurrent').DataTable({
			paging: false,
			iDisplayLength: -1,
			aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
			dom: 'Bfrtip',
			select: true,
			buttons: [
				'copyHtml5',
				{
					extend: 'excel',
					title: 'Ledger',
					messageTop: '..',
					messageBottom: 'End Of Doc'
				},
				{
					extend: 'colvis',
					collectionLayout: 'fixed two-column',
					columnText: function ( dt, idx, title ) {
						return (idx+1)+': '+title;
					},
				}
			]
		});

		myDataTable = $('#tbl1').DataTable({
			paging: false,
			iDisplayLength: -1,
			aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
			dom: 'Bfrtip',
			select: true,
			buttons: [	
				'copyHtml5',
				'excelHtml5',
				'csvHtml5'
			]
		});

		$("#btnShow").trigger('click');
		bindStocks();
		$("#cboInvestor").on('change', loadData);

		$('#btnEditCurrentStocks').on('click', editCurrentStocks);
		$('#btnGetProfitOfSattled').on('click', getProfitOfSattled);
	} );	

	function getProfitOfSattled()
	{
		let investor = $("#cboInvestor").val();
		if(investor == "-1")
		{
			return;
		}
		$.ajax({
				'url': base_url + '/' + controller + '/getProfitOfSattled',
				'type': 'POST',
				'dataType': 'json',
				'data':{'stockName':'stockName', 'investor':investor},
				'success': function(data)
				{
					if(data)
					{
						alertPopup('Records loaded...', 4000);
						console.log(data['profit']);
						$("#btnGetProfitOfSattled").text(data['profit']);
					}
				},
				error: function (jqXHR, exception) {
					ajaxCallErrorMsg(jqXHR, exception)
				}
			});
	}

	function setValue()
	{
		let q = $("#txtQty").val();
		let r = $("#txtRate").val();
		let v = q * r;
		$("#txtValue").val(v.toFixed(2));
	}


	function bindStocks()
	{
		select = false;
		var jSonArray = '<?php echo json_encode($stockList); ?>';
		var jSonArray = jSonArray.replace(/(\r\n|\n|\r)/gm,", "); ///Multilinse of Address field with comma replce
				var availableTags = $.map(JSON.parse(jSonArray), function(obj){
							return{
									label: obj.stockName
							}
					});

				    $(function() {
			        $( "#txtStockName" ).autocomplete({
			            source: function(request, response) {
			                var aryResponse = [];
			                var arySplitRequest = request.term.split(" ");
			                // alert(JSON.stringify(arySplitRequest));
			                for( i = 0; i < availableTags.length; i++ ) {
			                    var intCount = 0;
			                    for( j = 0; j < arySplitRequest.length; j++ ) {
									var cleanString = arySplitRequest[j].replace(/[|&;$%@"<>()+,]/g, "");
			                        regexp = new RegExp(cleanString, 'i');
			                        // regexp = new RegExp(arySplitRequest[j], 'i');
			                        var test = JSON.stringify(availableTags[i].label.toLowerCase()).match(regexp);
			                        if( test ) {
			                            intCount++;
			                        } else if( !test ) {
			                        intCount = arySplitRequest.length + 1;
			                        }
			                        if ( intCount == arySplitRequest.length ) {
			                            aryResponse.push( availableTags[i] );
			                        }
			                    };
			                }
			                response(aryResponse);
			            },
			            autoFocus: true,
			 			selectFirst: true,
			 			minLength: 1,
			            select: function (event, ui) {
				      	select = true;
					    var selectedObj = ui.item; 
			    		// $("#lblItemId").text( ui.item.itemRowId );
			        	}

			        }).blur(function() {
						  if( !select ) 
						  {
						  	// $("#lblItemId").text('-1');
						  	// $("#tbl1").find("tr:eq(" + rowIndex + ")").find("td:eq(1)").css("color", "red");
						  }
						  	else
						  	{
						  	}
						}).focus(function(){            
					            $(this).autocomplete("search");
					        });
			    });
	}

	
	function loadAllRecords()
	{
		let investor = $("#cboInvestor").val();
		if(investor == "-1")
		{
			return;
		}
		$.ajax({
				'url': base_url + '/' + controller + '/loadAllRecords',
				'type': 'POST',
				'dataType': 'json',
				'data':{'stockName':'stockName', 'investor':investor},
				'success': function(data)
				{
					if(data)
					{
						setTable(data['records'], data['recordsCurrentStocks']);
						alertPopup('Records loaded...', 4000);
					}
				},
				error: function (jqXHR, exception) {
					ajaxCallErrorMsg(jqXHR, exception)
				}
			});
	}

	
	function loadAllOfThisStock()
	{
		let investor = $("#cboInvestor").val();
		if(investor == "-1")
		{
			return;
		}
		stockName = "";
		rowIndex = $(this).parent().index();
		colIndex = $(this).index();
		stockName = $("#h4StockLedger").text();
		// alert(stockName);
		// return;
		$("#tblStockLedger").find("tr:gt(0)").remove(); //// empty first
		$("#h4StockLedger").html("<b><span style='color:red;'>" + stockName + "</span></b>" );
		$.ajax({
			'url': base_url + '/' + controller + '/loadAllOfThisStock',
			'type': 'POST', 
			'data':{'stockName':stockName, 'investor':investor},
			'dataType': 'json',
			'success':function(data)
			{
				console.log(data['records']);
				setStockLedgerTable(data['records']);
			},
			error: function (jqXHR, exception) {
					ajaxCallErrorMsg(jqXHR, exception)
			}
		});
	}	
	
	
	function editCurrentStocks()
	{
		let investor = $("#cboInvestor").val();
		if(investor == "-1")
		{
			return;
		}
		$.ajax({
			'url': base_url + '/' + controller + '/editCurrentStocks',
			'type': 'POST',
			'dataType': 'json',
			'data':{'stockName':'stockName', 'investor':investor},
			'success': function(data)
			{
				if(data)
				{
					setTableEditCurrentStocks(data['records']);
					alertPopup('Records loaded...', 4000);
				}
			},
			error: function (jqXHR, exception) {
				ajaxCallErrorMsg(jqXHR, exception)
			}
		});

		$('#myModalEditCurrentStocks').modal('toggle');
	}
	
	function setTableEditCurrentStocks(records)
	{
		$("#tblEditCurrentStocks").find("tr:gt(0)").remove(); //// empty first
        var table = document.getElementById("tblEditCurrentStocks");
        for(i=0; i<records.length; i++)
		{
	        newRowIndex = table.rows.length;
			row = table.insertRow(newRowIndex);

          	var cell = row.insertCell(0);
          	cell.innerHTML = records[i].stockCurrentRowId;
          	var cell = row.insertCell(1);
			cell.innerHTML = records[i].stockName;
          	var cell = row.insertCell(2);
          	cell.innerHTML = records[i].qty;
			cell.contentEditable="true"
			cell.style.textAlign = "right";
          	var cell = row.insertCell(3);
          	cell.innerHTML = records[i].avgRate;
			cell.contentEditable="true"
			cell.style.textAlign = "right";
			var cell = row.insertCell(4);
	        cell.innerHTML = "<button class='clsBtnSaveEditedCurrentStock btn btn-success form-control'>Save</button>";
			cell.style.textAlign = "center";
        }
		$(".clsBtnSaveEditedCurrentStock").on('click', saveEditedCurrentStock);
		// $("#lblNetProfitOrLoss").text(tot.toFixed(2));
	}
	function saveEditedCurrentStock()
	{
		rowIndex = $(this).parent().parent().index();
		stockCurrentRowId = $(this).closest('tr').children('td:eq(0)').text();
		qty = $(this).closest('tr').children('td:eq(2)').text();
		avgRate = $(this).closest('tr').children('td:eq(3)').text();
		let investor = $("#cboInvestor").val();
		if(investor == "-1")
		{
			return;
		}
		$.ajax({
			'url': base_url + '/' + controller + '/saveEditedCurrentStock',
			'type': 'POST',
			'dataType': 'json',
			'data': {
					'stockCurrentRowId': stockCurrentRowId, 'qty': qty, 'avgRate': avgRate
				},
			'success': function(data)
			{
				if(data)
				{
					$("#tblEditCurrentStocks").find("tr:eq(" + rowIndex + ")").find("td:eq(4)").find('.clsBtnSaveEditedCurrentStock').text('SAVED');
					loadData();
				}
			},
				error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
		});	
	}
</script>