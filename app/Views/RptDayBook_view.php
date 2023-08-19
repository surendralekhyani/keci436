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
	
	var controller='RptDayBook_Controller';
	var base_url='<?php echo site_url();?>';

	vModuleName = "Day Book";

	vGlobalVtypeForPrint="";


	function setTable(records)
	{
          ////////////// Records in Range
          $("#tbl1").empty();
	      var table = document.getElementById("tbl1");
		  var tmp=0;
	      for(i=0; i<records.length; i++)
	      {
            if(i>0 && records[i].vType == records[i-1].vType && records[i].refRowId == records[i-1].refRowId)
            {
				if( records[i].vType == "PV" )
					$("#tbl1 tr:last").find("td:eq(5)").text(records[i].recd);
			}
			else
			{
	          newRowIndex = table.rows.length;
	          row = table.insertRow(newRowIndex);
	          var cell = row.insertCell(0);
	          cell.innerHTML = records[i].ledgerRowId;
	          cell.style.display="none";
	          var cell = row.insertCell(1);
	          if( records[i].vType == "DB" )
	          {
	          	cell.innerHTML = records[i].vType + "-" + records[i].refRowId + " <span style='color:blue; cursor: pointer;' class='glyphicon glyphicon-print' onclick='printBill(" + records[i].refRowId + ", -12);'></span>";
	          }
	          else
	          {
	          	cell.innerHTML = records[i].vType + "-" + records[i].refRowId;
	          }
	          var cell = row.insertCell(2);
	          cell.innerHTML = dateFormat(new Date(records[i].refDt));
	          var cell = row.insertCell(3);
	        //   cell.innerHTML = records[i].customerName;
			  cell.innerHTML = "<a id='contraac' target='_blank' href='<?php  echo base_url();  ?>/index.php/rptledger/yeParty/"+records[i].customerName+"/"+records[i].customerRowId+"'>" + records[i].customerName + "</a>";
	          var cell = row.insertCell(4);
	          cell.innerHTML = records[i].remarks;
	          cell.style.display="none1";
	          var cell = row.insertCell(5);
			  cell.innerHTML = records[i].amt;
	          var cell = row.insertCell(6);
	          cell.innerHTML = records[i].recd;
            }
	  	  }
	  	  ////////////// END - Records in Range

	  	  ////////////// Total
	  	    var totDr = 0;
	  	    var totCr = 0;
	  	    var rangeTotDr = 0;
	  	    var rangeTotCr = 0;
		    $('#tbl1 tr').each(function(row, tr)
		    {
                if( $(tr).find('td:eq(1)').text().substr(0,2) == "DB" ) 
		    	{
                    $(tr).find('td:eq(6)').text($(tr).find('td:eq(5)').text());
                    $(tr).find('td:eq(5)').text("0");
                }
		    	if( $(tr).find('td:eq(5)').text() > 0 ) 
		    	{
		        	totDr += parseInt( $(tr).find('td:eq(5)').text() ); 
		        }
		    	if( $(tr).find('td:eq(6)').text() > 0 ) 
		    	{
		        	totCr += parseInt( $(tr).find('td:eq(6)').text() ); 
		        }

		        rangeTotDr = totDr - $('#tbl1').find('tr:eq(0)').find('td:eq(5)').text();
		        rangeTotCr = totCr - $('#tbl1').find('tr:eq(0)').find('td:eq(6)').text();
		    }); 
              var diff = Math.abs(totDr - totCr);

		      newRowIndex = $("#tbl1 tr").length;
	          row = table.insertRow(newRowIndex);
	          // alert(newRowIndex);
	          var cell = row.insertCell(0);
	          cell.innerHTML = "9999999";
	          cell.style.display="none";

	          var cell = row.insertCell(1);
	          cell.innerHTML = "";

	          var cell = row.insertCell(2);
	          cell.innerHTML = "";

	          var cell = row.insertCell(3);
	          cell.innerHTML = "Total";
	          cell.style.color = "red";
	          

	          var cell = row.insertCell(4);
	          cell.innerHTML = "";
	          cell.style.display="none1";

	          var cell = row.insertCell(5);
	          	cell.innerHTML = totDr;
	          cell.style.color = "red";
	          
	          var cell = row.insertCell(6);
	          	cell.innerHTML = totCr + " <b>[" + diff + "]</b>";
	          cell.style.color = "red";



	          

	          

		myDataTable.destroy();
		$(document).ready( function () {
		    myDataTable = $('#tbl1').DataTable({
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


			
		} );

		$("#tbl1 tr").on("dblclick", setGlobalSaleRowId);
		$('#tbl1 tr').dblclick(function () {
		   	$('#myModalSaleDetail').modal('toggle');
		});
				
	}

	function printBill(invNo, flag=0)
	{
		if( vGlobalVtypeForPrint == "DB" || flag==-12)
		{
			$.ajax({
					'url': base_url + '/' + 'Sale_Controller' + '/printNow/L/'+invNo,
					'type': 'POST',
					'dataType': 'json',
					'data': {
								'globalrowid': invNo
							},
					'success': function(data)
					{
						$("#divPrint").html(data['html']);
						setTimeout(function() {
	                        window.print();
	                    }, 1000);
					},
				error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});
		}
		else if( vGlobalVtypeForPrint == "PV" )
		{
			$.ajax({
					'url': base_url + '/' + 'Purchase_Controller' + '/printNow/11',
					'type': 'POST',
					'dataType': 'json',
					'data': {
								'globalrowid': invNo
							},
					'success': function(data)
					{
						$("#divPrint").html(data['html']);
						window.print();
					},
				error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});
		}

	}

	function setSaleDetailTable(records)
	{
		$("#tblSaleDetail").find("tr:gt(0)").remove(); //// empty first
        var table = document.getElementById("tblSaleDetail");

        // alert(JSON.stringify(data));
        for(i=0; i<records.length; i++)
		{
	        newRowIndex = table.rows.length;
				row = table.insertRow(newRowIndex);

          var cell = row.insertCell(0);
          // cell.style.display="none";
          cell.innerHTML = records[i].dbRowId;
          var cell = row.insertCell(1);
          cell.innerHTML = records[i].itemName;
          var cell = row.insertCell(2);
          cell.innerHTML = records[i].qty;
          var cell = row.insertCell(3);
          cell.innerHTML = records[i].rate;
          // cell.style.display="none";
          var cell = row.insertCell(4);
          cell.innerHTML = records[i].amt;
        }
        /////Loading SalesReturn Data
  
	  			
	}

	function setPurchaseDetailTable(records)
	{
		$("#tblSaleDetail").find("tr:gt(0)").remove(); //// empty first
        var table = document.getElementById("tblSaleDetail");

        // alert(JSON.stringify(data));
        for(i=0; i<records.length; i++)
		{
	        newRowIndex = table.rows.length;
			row = table.insertRow(newRowIndex);

          var cell = row.insertCell(0);
          // cell.style.display="none";
          cell.innerHTML = records[i].purchaseRowId;
          var cell = row.insertCell(1);
          cell.innerHTML = records[i].itemName;
          var cell = row.insertCell(2);
          cell.innerHTML = records[i].qty;
          var cell = row.insertCell(3);
          cell.innerHTML = records[i].rate + " [" + records[i].discountAmt +", " + records[i].cgstAmt +", " + records[i].sgstAmt + "]";
          // cell.style.display="none";
          var cell = row.insertCell(4);
          cell.innerHTML = records[i].netAmt;
        }
	}

	function setGlobalSaleRowId()
	{
		globalSaleRowId = 0;
		rowIndex = $(this).parent().index();
		colIndex = $(this).index();
		globalSaleRowId = $(this).closest('tr').children('td:eq(1)').text().substr(3,$(this).closest('tr').children('td:eq(1)').text().length);
		x = $(this).closest('tr').children('td:eq(1)').text().substr(0,2);
		vGlobalVtypeForPrint = x;
		// alert(x + "    ,  " + globalSaleRowId);
		$("#tblSaleDetail").find("tr:gt(0)").remove(); //// empty first
		// $("#h4SaleDetail").text("Sale Detail - " + $( "#cboCustomer option:selected" ).text() );
		if( x == "DB" )
		{
			$("#h4SaleDetail").text("Sale Detail - " + $( "#txtCustomerName" ).val() );
			$.ajax({
				'url': base_url + '/' + controller + '/getSaleDetail',
				'type': 'POST', 
				'data':{'rowid':globalSaleRowId},
				'dataType': 'json',
				'success':function(data)
				{
					// alert(JSON.stringify(data['recordsSr']));
					// setSaleDetailTable(data['records'], data['recordsSr']);
					setSaleDetailTable(data['records']);
				},
				error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});
		}
		else if( x == "PV" )
		{
			$("#h4SaleDetail").text("Purchase Detail - " + $( "#txtCustomerName" ).val() );
			$.ajax({
				'url': base_url + '/' + controller + '/getPurchaseDetail',
				'type': 'POST', 
				'data':{'rowid':globalSaleRowId},
				'dataType': 'json',
				'success':function(data)
				{
					// alert(JSON.stringify(data['records']));
					setPurchaseDetailTable(data['records']);
				},
				error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});
		}
		else
		{
			alertPopup("Only SV and PV...",3000);
			return;
		}
		// alert(globalSaleRowId);
		
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

		

		$.ajax({
				'url': base_url + '/' + controller + '/showData',
				'type': 'POST',
				'dataType': 'json',
				'data': {
							'dtFrom': dtFrom
							, 'dtTo': dtTo
						},
				'success': function(data)
				{
					if(data)
					{
						// alert(JSON.stringify(data));
                        setTable(data['records']) 
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
			<h3 class="text-center" style='margin-top:-20px'>Day Book</h3>
			<?php
				echo "<label style='color: lightgrey; font-weight: normal; margin-top:10px;' id='lblCustomerId'>-2</label>";
				echo "<label style='color: red; font-weight: normal; margin-top:10px; margin-left:10px;' id='lblCustomerAddress'> - </label>";
				echo "<label style='color: green; font-weight: normal; margin-top:10px; margin-left:10px;' id='lblCustomerMobile'> - </label>";
				echo "<label style='color: blue; font-weight: normal; margin-top:10px; margin-left:10px;' id='lblCustomerRemarks'> - </label>";
			?>
			<form name='frm' id='frm' method='post' enctype='multipart/form-data' action="">
				<div class="row" style="margin-top:15px;">
					<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
						<?php
							// echo "<label style='color: black; font-weight: normal;'>From:</label>";
							echo form_input('dtFrom', '', "class='form-control' placeholder='' id='dtFrom' maxlength='10'");
		              	?>
		              	<script>
							$( "#dtFrom" ).datepicker({
								dateFormat: "dd-M-yy",changeMonth: true,changeYear: true,yearRange: "2010:2050"
							});
							$("#dtFrom").val(dateFormat(new Date()));
						</script>					
		          	</div>
		          	<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
						<?php
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
					
		          	
					<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
						<?php
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
			<div id="divTable" class="divTable col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border:1px solid lightgray; padding: 10px;height:470px; overflow:auto;">
				<table class='table table-hover' id='tbl1'>
				 <thead>
					 <tr>
						<th style='display:none;'>ledgerRowid</th>
					 	<th style='display:none1;'>V.Type</th>
					 	<th>Dt</th>
					 	<th style='display:none1;'>Party</th>
					 	<th style='display:none1;'>Remark</th>
					 	<th>Paid</th>
					 	<th>Recd.</th>
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

	<div class="row" style="margin-top:0px;" >
		<div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
			
		</div>
		<div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
			
		</div>
		<div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
		</div>
		<div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
			
		</div>
	</div>
</div>


		<!-- Model -->
		  <div class="modal" id="myModalSaleDetail" role="dialog">
		    <div class="modal-dialog modal-lg">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title" id="h4SaleDetail">Sale Detail</h4>
		        </div>
		        <div class="modal-body" style="overflow: auto; height: 300px;">
		          <table id='tblSaleDetail' class="table table-stripped">
		          		<th style='display:none1;'>V.Rowid</th>
					 	<th>Item</th>
					 	<th>Qty</th>
					 	<th>Rate [dis,c/sgst]</th>
					 	<th>Amt</th>
		          </table>
		        </div>
		        <div class="modal-footer">
		        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		        		<button type="button" onclick='printBill(globalSaleRowId);' class="btn btn-block btn-primary" data-dismiss="modal">Print</button>
		        	</div>
		        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		        		
		        	</div>
		        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		        	</div>
		        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		        		<button type="button" class="btn btn-block btn-default" data-dismiss="modal">Cancel</button>
		        	</div>
		        </div>
		      </div>
		    </div>
		  </div>		  



<script type="text/javascript">


		$(document).ready( function () {
		    myDataTable = $('#tbl1').DataTable({
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


			$("#btnShow").trigger('click');
		} );	


</script>