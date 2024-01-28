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
</style>

<script type="text/javascript">
	$(document).ready( function () {
		const url2 = window.location.href;
		var result= decodeURIComponent(url2);
		result = result.split('/');
		var iname = result[result.length-2];
		var lastSegment = result[result.length-1];
		if(lastSegment > 0)
		{
			$("#lblItemRowId").text(lastSegment);
			$("#txtItemName").val(iname.replace(/%20/g, " "));
			$("#btnShow").trigger('click');
		}
	});

	var controller='RptLedgerItem_Controller';
	var base_url='<?php echo site_url();?>';
	vModuleName = "Item Ledger";


	function setTable(opBal, purchase, sale, cashSale)
	{
		 // alert(JSON.stringify(records));
		 var inTot=0;
		 var outTot=0;
		 var bal = 0;
		  $("#tbl1").empty();
	      var table = document.getElementById("tbl1");

	      /////////////// Opening Balance
	      newRowIndex = table.rows.length;
          row = table.insertRow(newRowIndex);
          var cell = row.insertCell(0);
          cell.innerHTML = "";
          cell.style.display="none";
          var cell = row.insertCell(1);
          cell.innerHTML = "";
          var cell = row.insertCell(2);
          cell.innerHTML = "";
          var cell = row.insertCell(3);
          cell.innerHTML = "Op. Balance";
          cell.style.color = "red";
          var cell = row.insertCell(4);
          cell.innerHTML = opBal;
		  inTot += parseFloat(opBal);
          // cell.style.display="none";
          var cell = row.insertCell(5);
          cell.innerHTML = "";
          var cell = row.insertCell(6);
          cell.innerHTML = opBal;
          var cell = row.insertCell(7);
          cell.innerHTML = "00";
          cell.style.display="none1";
          /////////////// END - Opening Balance

          ////////////// Purchase Records in Range
	      for(i=0; i<purchase.length; i++)
	      {
	          newRowIndex = table.rows.length;
	          row = table.insertRow(newRowIndex);

	          var cell = row.insertCell(0);
	          cell.innerHTML = purchase[i].purchaseDetailRowId;
	          cell.style.display="none";

	          var cell = row.insertCell(1);
	          cell.innerHTML = dateFormat(new Date(purchase[i].purchaseDt));

	          var cell = row.insertCell(2);
	          cell.innerHTML = "PV-" + purchase[i].purchaseRowId;

	          var cell = row.insertCell(3);
	          cell.innerHTML = purchase[i].customerName + "<span style='color:green;'> [" + (purchase[i].netAmt / purchase[i].qty).toFixed(2) + "]</span>";

	          var cell = row.insertCell(4);
	          cell.innerHTML = purchase[i].qty;
		  		inTot += parseFloat(purchase[i].qty);
	          // cell.style.display="none";
	          var cell = row.insertCell(5);
	          cell.innerHTML = "";
	          var cell = row.insertCell(6);
	          cell.innerHTML = "";
	          var cell = row.insertCell(7);
          	  cell.innerHTML = purchase[i].purchaseDt;
          	  cell.style.display="none1";

	  	  }
	  	  ////////////// END - Purchase Records in Range

          ////////////// Sale Records in Range
          // alert(JSON.stringify(sale));
	      for(i=0; i<sale.length; i++)
	      {
	          newRowIndex = table.rows.length;
	          row = table.insertRow(newRowIndex);

	          var cell = row.insertCell(0);
	          cell.innerHTML = sale[i].dbdRowId;
	          cell.style.display="none";

	          var cell = row.insertCell(1);
	          cell.innerHTML = dateFormat(new Date(sale[i].dbDt));

	          var cell = row.insertCell(2);
	          cell.innerHTML = "SV-" + sale[i].dbRowId;

	          var cell = row.insertCell(3);
	          cell.innerHTML = sale[i].customerName + "<span style='color:green;'> [" + sale[i].rate + "]</span>";;

	          var cell = row.insertCell(4);
	          cell.innerHTML = "";
	          // cell.style.display="none";
	          var cell = row.insertCell(5);
	          cell.innerHTML = sale[i].qty;
		  		outTot += parseFloat(sale[i].qty);
	          var cell = row.insertCell(6);
	          cell.innerHTML = "";
	          var cell = row.insertCell(7);
          	  cell.innerHTML = sale[i].dbDt;
          	  cell.style.display="none1";

	  	  }
	  	  ////////////// END - sale Records in Range

			///////////TOTAL
			newRowIndex = table.rows.length;
	        row = table.insertRow(newRowIndex);
			row.style.color="red";
	          var cell = row.insertCell(0);
	          cell.style.display="none";
	          var cell = row.insertCell(1);
	          var cell = row.insertCell(2);
	          var cell = row.insertCell(3);
	          cell.innerHTML = "Total";
	          var cell = row.insertCell(4);
	          cell.innerHTML = inTot;
	          var cell = row.insertCell(5);
	          cell.innerHTML = outTot;
	          var cell = row.insertCell(6);
	          var cell = row.insertCell(7);
	          cell.innerHTML = "9999-12-31";
			///////////TOTAL END

		// myDataTable.destroy();
		// $(document).ready( function () {
		//     myDataTable=$('#tbl1').DataTable({
		// 	    paging: false,
		// 	    order: [[7, 'asc']],
		// 	    iDisplayLength: -1,
		// 	    aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]]
		// 	});

		// 	setRowBalance();
		// } );

		// $("#tbl1 tr").on("click", highlightRowAlag);

		myDataTable.destroy();
		$(document).ready( function () {
		    myDataTable = $('#tbl1').DataTable({
			    paging: false,
			    iDisplayLength: -1,
			    aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
			    dom: 'Bfrtip',
			    select: true,
			    order: [[7, 'asc']],
		        buttons: [
		            'copyHtml5',
		            {
		                extend: 'excel',
		                title: 'Item Ledger (' + $("#dtFrom").val() + " to " + $("#dtTo").val() + ")",
		                messageTop: $("#txtItemName").val(),
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
		setRowBalance();
		} );
			
	}

	function setRowBalance()
	{	
		vBal = 0;
		$('#tbl1 tr').each(function(row, tr)
	    {
	    	if(row==0 || row==$('#tbl1 tr').length-2)
	    	{
	    		vBal = parseFloat($(tbl1).find('tr:eq(0)').find('td:eq(4)').text());
				// alert(vBal);
	    	}
	    	else
	    	{
		    	vIn = 0; vOut = 0;
		    	if( isNaN( parseFloat($(tr).find('td:eq(4)').text()) ) == false )
		    	{
		    		vIn = parseFloat($(tr).find('td:eq(4)').text());
		    	}
		    	if( isNaN( parseFloat($(tr).find('td:eq(5)').text()) ) == false )
		    	{
		    		vOut = parseFloat($(tr).find('td:eq(5)').text());
		    	}
		    	vBal = vBal + vIn - vOut;
		    	$(tr).find('td:eq(6)').text( vBal.toFixed(2) );
		    }
		});
	}

	function loadData()
	{	
		// $("#tbl1").find("tr:gt(0)").remove(); /* empty except 1st (head) */	
		var dtFrom = $("#dtFrom").val().trim();
		dtOk = testDate("dtFrom");
		if(dtOk == false)
		{
			alertPopup("Invalid date...", 5000);
			$("#dtFrom").focus();
			return;
		}

		var dtTo = $("#dtTo").val().trim();
		dtOk = testDate("dtTo");
		if(dtOk == false)
		{
			alertPopup("Invalid date...", 5000);
			$("#dtTo").focus();
			return;
		}

		// itemRowId = $("#cboItem").val();
		itemRowId = $("#lblItemRowId").text();
		if( itemRowId <= 0 || itemRowId == "" )
		{
			alertPopup("Select item...", 8000);
			return;
		}

		$.ajax({
				'url': base_url + '/' + controller + '/showData',
				'type': 'POST',
				'dataType': 'json',
				'data': {
							'itemRowId': itemRowId
							, 'dtFrom': dtFrom
							, 'dtTo': dtTo
						},
				'success': function(data)
				{
					if(data)
					{
						// alert(JSON.stringify(data));
							setTable(data['opBal'], data['purchase'], data['sale']);
							// setTable(data['opBal'], data['purchase'], data['sale'], data['cashSale']);

							alertPopup('Records loaded...', 4000);
					}
				},
				error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
		});
		
	}





</script>
<div class="container">
	<div class="row">
		<div class="col-lg-0 col-sm-0 col-md-0 col-xs-0">
		</div>
		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
			<h3 class="text-center" style='margin-top:-20px'>Item Ledger</h3>
			<?php
				echo "<label style='color: lightgrey; font-weight: normal; margin-top:10px;' id='lblItemRowId'>-2</label>";
			?>
			<form name='frm' id='frm' method='post' enctype='multipart/form-data' action="">
				<div class="row" style="margin-top:15px;">
					<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
						<?php
							echo "<label style='color: black; font-weight: normal;'>From:</label>";
							echo form_input('dtFrom', '', "class='form-control' placeholder='' id='dtFrom' maxlength='10'");
		              	?>
		              	<script>
							$( "#dtFrom" ).datepicker({
								dateFormat: "dd-M-yy",changeMonth: true,changeYear: true,yearRange: "2010:2050"
							});
							// Set the 1st of this month
							// var date = new Date();
							// var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
							dt=new Date();
     					    dt.setDate(dt.getDate() - 3850);
     					    $("#dtFrom").val(dateFormat(dt));
							// $("#dtFrom").val(dateFormat(firstDay));
						</script>					
		          	</div>
		          	<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
						<?php
							echo "<label style='color: black; font-weight: normal;'>To:</label>";
							echo form_input('dtTo', '', "class='form-control' placeholder='' id='dtTo' maxlength='10'");
		              	?>
		              	<script>
							$( "#dtTo" ).datepicker({
								dateFormat: "dd-M-yy",changeMonth: true,changeYear: true,yearRange: "2010:2050"
							});
						    // Set the Current Date as Default
							$("#dtTo").val(dateFormat(new Date()));
						</script>					
		          	</div>
					<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
						<?php
							echo "<label style='color: black; font-weight: normal;'>Item:</label>";
							// echo form_dropdown('cboItem',$items, '-1',"class='form-control' id='cboItem'");
							echo form_input('txtItemName', '', "class='form-control' id='txtItemName' style='' maxlength=70 autocomplete='off' placeholder='Name'");

							// echo form_dropdown('cboItem',$items, '-1',"class='form-control' id='cboItem'");
		              	?>
		          	</div>
					<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
						<?php
							echo "<label style='color: black; font-weight: normal;'>&nbsp;	</label>";
							echo "<input type='button' onclick='loadData();' value='Show Data' id='btnShow' class='btn btn-primary form-control'>";
		              	?>
		          	</div>
				</div>
			</form>
		</div>
		<div class="col-lg-0 col-sm-0 col-md-0 col-xs-0">
		</div>
	</div>


	<div class="row" style="margin-top:20px;" >
		<div class="col-lg-0 col-sm-0 col-md-0 col-xs-0">
		</div>

		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
			<div id="divTable" class="divTable col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border:1px solid lightgray; padding: 10px;height:530px; overflow:auto;">
				<table class='table table-hover' id='tbl1'>
				 <thead>
					 <tr>
						<th style='display:none;'>Rowid</th>
					 	<th style='display:none1;'>Date</th>
					 	<th style='display:none1;'>V.Type</th>
					 	<th>Account</th>
					 	<th>IN</th>
					 	<th>OUT</th>
					 	<th>Bal.</th>
					 	<th style='display:none1;'>odr</th>
					 </tr>
				 </thead>
				 <tbody>

				 </tbody>
				</table>
			</div>
		</div>

		<div class="col-lg-0 col-sm-0 col-md-0 col-xs-0">
		</div>
	</div>

	
</div>





<script type="text/javascript">


		$(document).ready( function () {
		    myDataTable = $('#tbl1').DataTable({
			    paging: false,
			    iDisplayLength: -1,
			    aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],

			});
		} );



	var tblRowsCount;
	function storeTblValues()
	{
	    var TableData = new Array();
	    var i=0;
	    $('#tbl1 tr').each(function(row, tr)
	    {
	    	// if( $(tr).find('td:eq(3)').text() > 0 )
	    	// {
	        	TableData[i]=
	        	{
		             "vType" : $(tr).find('td:eq(1)').text()
		            , "Rem" :$(tr).find('td:eq(2)').text()
		            , "dt" :$(tr).find('td:eq(3)').text()
		            , "Dr" :$(tr).find('td:eq(5)').text()
		            , "Cr" :$(tr).find('td:eq(6)').text()
	        	}   
	        	i++; 
	        // }
	    }); 
	    // TableData.shift();  // NOT first row will be heading - so remove COZ its dataTable
	    tblRowsCount = i-1;
	    return TableData;
	}


	function exportData()
	{	
		// alert();
		var TableData;
		TableData = storeTblValues();
		TableData = JSON.stringify(TableData);
		// alert(JSON.stringify(TableData));
		// return;
		if(tblRowsCount == 0)
		{
			alertPopup("No product selected...", 8000);
			$("#cboProducts").focus();
			return;
		}
		var dtFrom = $("#dtFrom").val().trim();
		dtOk = testDate("dtFrom");
		if(dtOk == false)
		{
			alertPopup("Invalid date...", 5000);
			$("#dtFrom").focus();
			return;
		}

		var dtTo = $("#dtTo").val().trim();
		dtOk = testDate("dtTo");
		if(dtOk == false)
		{
			alertPopup("Invalid date...", 5000);
			$("#dtTo").focus();
			return;
		}

		partyRowId = $("#cboItem").val();
		var party = $("#cboItem option:selected").text();
		var difference = $("#txtDiff").val().trim();

		$.ajax({
				'url': base_url + '/' + controller + '/exportData',
				'type': 'POST',
				// 'dataType': 'json',
				'data': {
							'TableData': TableData
							, 'dtFrom': dtFrom
							, 'dtTo': dtTo
							, 'party': party
							, 'difference': difference
						},
				'success': function(data)
				{
					if(data)
					{
						window.location.href=data;
					}
				},
					'error': function(jqXHR, exception)
					{
						document.write(jqXHR.responseText);
					}
		});
		
	}
</script>


<script>
	var select = false;
    $( "#txtItemName" ).focus(function(){ 
	  			select = false; 
	  			// $("#txtAddress").val(select);
	  		});

	$(document).ready( function () 
	{
		select = false;
		var jSonArray = '<?php echo json_encode($items); ?>';
		// alert(jSonArray);
		var jSonArray = jSonArray.replace(/(\r\n|\n|\r)/gm,", "); ///Multilinse of Address field with comma replce
		var availableTags = $.map(JSON.parse(jSonArray), function(obj){
					return{
							label: obj.itemName,
							itemRowId: obj.itemRowId,
					}
		});

	    $( "#txtItemName" ).autocomplete({
		      // source: availableTags,
		      source: function(request, response) {
			                
			                var aryResponse = [];
			                var arySplitRequest = request.term.split(" ");
			                // alert(JSON.stringify(arySplitRequest));
			                for( i = 0; i < availableTags.length; i++ ) {
			                    var intCount = 0;
			                    for( j = 0; j < arySplitRequest.length; j++ ) {
			                        var cleanString = arySplitRequest[j].replace(/[|&;$%@"<>()+,]/g, "");
			                        regexp = new RegExp(cleanString, 'i');
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
			  open: function(event, ui) { if(select) select=false; },
			  // select: function(event, ui) { select=true; },	
		      minLength: 2,
		      select: function (event, ui) {
		      	select = true;
		      	var selectedObj = ui.item; 
			    $("#lblItemRowId").text( ui.item.itemRowId );
	        	}
		    }).blur(function() {
				  if( !select ) 
				  {
				  	$("#lblItemRowId").text('-1');
				  }
				  	
				}).focus(function(){            
			            $(this).autocomplete("search");
			        });
    } );
</script>