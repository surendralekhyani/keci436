
<link rel='stylesheet' href='<?php  echo base_url();  ?>/public/css/suriprint.css'>

<script type="text/javascript">
	var controller='RptDues_Controller';
	var base_url='<?php echo site_url();?>';

	vModuleName = "Dues";


	function setTable(records, recordsNegative)
	{
		 // alert(JSON.stringify(records));
		 	var msgWp = "";
			msgWp += "Respected Customer,";
			msgWp += "%0aPls clear your dues at *KAMAL COMPUTERS*. This is auto generated msg, ignore if already cleared.";
			msgWp += "%0a-Regards,";
			// msgWp += "%0aDilip Lekhyani";
			// msgWp += "%0a9461070900";


		  $("#tbl1").empty();
	      var table = document.getElementById("tbl1");
	      var tot=0;
	      for(i=0; i<records.length; i++)
	      {
			amtWp = "%0aDue Amt Rs. *" + records[i].balance + "* only.";

	          newRowIndex = table.rows.length;
	          row = table.insertRow(newRowIndex);


	          var cell = row.insertCell(0);
	          cell.innerHTML = records[i].customerRowId;
	          cell.style.display="none";

	          var cell = row.insertCell(1);
	        //   cell.innerHTML = "<input type='checkbox' id='chk' class='chk' name='chk' style='width:14px;height:14px;'/> <a id='contraac' href='#' onClick='loadLedger("+records[i].customerRowId+");'>" + records[i].customerName + "</a><br>[" + records[i].mobile1 + "] <kbd><a style='color:#fff;' target='_blank' href='https://web.whatsapp.com/send?phone=91"+records[i].mobile1+"&text="+msgWp+amtWp+"&source=&data'>WhatsApp</a></kbd>"
	          cell.innerHTML = "<input type='checkbox' id='chk' class='chk' name='chk' style='width:14px;height:14px;'/> <a id='contraac' target='_blank' href='<?php  echo base_url();  ?>/index.php/rptledger/yeParty/"+records[i].customerName+"/"+records[i].customerRowId+"'>" + records[i].customerName + "</a><br>[" + records[i].mobile1 + "] <kbd><a style='color:#fff;' target='_blank' href='https://web.whatsapp.com/send?phone=91"+records[i].mobile1+"&text="+msgWp+amtWp+"&source=&data'>WhatsApp</a></kbd>"
	          // cell.innerHTML = records[i].customerName;

	          var cell = row.insertCell(2);
	          cell.innerHTML = records[i].balance;
	          cell.style.textAlign="right";
	          cell.style.border="thin solid lightgray";

	          tot += parseFloat(records[i].balance);


	          var cell = row.insertCell(3);
	          cell.innerHTML = "";
	          cell.style.color="red";
	          cell.contentEditable="true";
	          cell.style.border="thin solid lightgray";

	          var cell = row.insertCell(4);
	          cell.innerHTML = "";
	          cell.style.color="blue";
	          cell.contentEditable="true";
	          cell.style.border="thin solid lightgray";

	          var cell = row.insertCell(5);
	          cell.innerHTML = "<button class='clsBtnReceive btn btn-success form-control'>Receive</button>";

	          var cell = row.insertCell(6);
	          cell.innerHTML = "<a target='_blank' href='https://web.whatsapp.com/send?phone=91"+records[i].mobile1+"&text="+msgWp+amtWp+"&source=&data'>" + records[i].mobile1 + "</a>";
	          cell.style.color="red";
	          cell.style.display="none";

	          var cell = row.insertCell(7);
	          cell.innerHTML = "<button class='clsBtnDoobat btn btn-warning form-control'>" + records[i].doobat + "</button>";
	          cell.style.border="thin solid lightgray";
	  	  }
	  	  $("#txtTotalDues").val(tot);
	  	  /////////// -ve dues
	  	  for(i=0; i<recordsNegative.length; i++)
	      {
	          newRowIndex = table.rows.length;
	          row = table.insertRow(newRowIndex);
	          row.style.color="red";

	          var cell = row.insertCell(0);
	          cell.innerHTML = recordsNegative[i].customerRowId;
	          cell.style.display="none";
	          var cell = row.insertCell(1);
	         // cell.innerHTML = "<a id='contraac' href='#' onClick='loadLedger("+recordsNegative[i].customerRowId+");'>" + recordsNegative[i].customerName + "</a>"
	          cell.innerHTML = "<input type='checkbox' id='chk' class='chk' name='chk' style='width:14px;height:14px;'/> <a id='contraac' target='_blank' href='<?php  echo base_url();  ?>/index.php/rptledger/yeParty/"+recordsNegative[i].customerName+"/"+recordsNegative[i].customerRowId+"'>" + recordsNegative[i].customerName + "</a>";
	          var cell = row.insertCell(2);
	          cell.innerHTML = recordsNegative[i].balance;
	          cell.style.border="thin solid lightgray";
	          // tot += parseFloat(records[i].balance);
	          var cell = row.insertCell(3);
	          cell.innerHTML = "";
	          cell.style.color="red";
	          cell.contentEditable="true";
	          cell.style.border="thin solid lightgray";
	          var cell = row.insertCell(4);
	          cell.innerHTML = "";
	          cell.style.color="blue";
	          cell.contentEditable="true";
	          cell.style.border="thin solid lightgray";
	          var cell = row.insertCell(5);
	          cell.innerHTML = "<button class='clsBtnPaid btn btn-primary form-control'>Paid</button>";
	          var cell = row.insertCell(6);
	          cell.innerHTML = "";
	          cell.style.color="red";
	          cell.style.display="none";
	          var cell = row.insertCell(7);
	          cell.innerHTML = "";
	          cell.style.border="thin solid lightgray";

	  	  }


			// $("#txtSms").on("change, keyup", countCharactersReady);

	  	    var msg = "";
			msg += "Respected Customer,";
			msg += "\nPls clear your dues at KAMAL COMPUTERS. This is auto generated msg, ignore if already cleared.";
			msg += "\n-Regards,";
			// msg += "\nDilip Lekhyani";
			// msg += "\n9461070900";
			$("#txtSms").val(msg);
			// countCharactersReady();

		myDataTable.destroy();
		$(document).ready( function () {
	    myDataTable=$('#tbl1').DataTable({
		    paging: false,
		    iDisplayLength: -1,
		    aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
		    "ordering": false
		});
		} );

		// $("#tbl1 tr").on("click", highlightRowAlag);
		$(".clsBtnReceive").on('click', jamaKaro);
		$(".clsBtnPaid").on('click', payKaro);
		$(".clsBtnDoobat").on('click', markDoobat);
			
	}
	// function countCharactersReady()
	// 		{
	// 			$("#lblChars").text( $("#txtSms").val().length );
	// 		}

	function jamaKaro()
	{
		rowIndex = $(this).parent().parent().index();
		customerRowId = $(this).closest('tr').children('td:eq(0)').text();
		rAmt = $(this).closest('tr').children('td:eq(3)').text();
		remarks = $(this).closest('tr').children('td:eq(4)').text();
		if(rAmt == "" || isNaN(rAmt))
		{
			alert("Invalid Amt...");
			return;
		}
		$.ajax({
			'url': base_url + '/' + controller + '/receiveAmt',
			'type': 'POST',
			'dataType': 'json',
			'data': {
						'customerRowId': customerRowId, 'rAmt': rAmt, 'remarks': remarks
					},
			'success': function(data)
			{
				if(data)
				{
					if(data == "This amt for this Party on this Date already saved... Try diff. amt...")
					{
						alert("This amt for this Party on this Date already saved... Try diff. amt...");
					}
					else
					{
					// alert(JSON.stringify(data));
						setTable(data['records'], data['recordsNegative']) 
						alertPopup('Done...', 4000);
					}
				}
			},
				error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
		});	
	}

	function payKaro()
	{
		rowIndex = $(this).parent().parent().index();
		customerRowId = $(this).closest('tr').children('td:eq(0)').text();
		rAmt = $(this).closest('tr').children('td:eq(3)').text();
		remarks = $(this).closest('tr').children('td:eq(4)').text();
		if(rAmt == "" || isNaN(rAmt))
		{
			alert("Invalid Amt...");
			return;
		}
		$.ajax({
			'url': base_url + '/' + controller + '/payAmt',
			'type': 'POST',
			'dataType': 'json',
			'data': {
						'customerRowId': customerRowId, 'rAmt': rAmt, 'remarks': remarks
					},
			'success': function(data)
			{
				if(data)
				{
				// alert(JSON.stringify(data));
					setTable(data['records'], data['recordsNegative']) 
					alertPopup('Done...', 4000);
				}
			},
				error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
		});	
	}

	function markDoobat()
	{
		rowIndex = $(this).parent().parent().index();
		customerRowId = $(this).closest('tr').children('td:eq(0)').text();
		abhiDoobatHaiKya = $(this).text();
		// alert(abhiDoobatHaiKya);
		$.ajax({
			'url': base_url + '/' + controller + '/markDoobat',
			'type': 'POST',
			'dataType': 'json',
			'data': {
						'customerRowId': customerRowId, 'abhiDoobatHaiKya': abhiDoobatHaiKya
					},
			'success': function(data)
			{
				if(data)
				{
				// alert(JSON.stringify(data));
					setTable(data['records'], data['recordsNegative']) 
					alertPopup('Done...', 3000);
				}
			},
				error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
		});	
	}

	function loadData()
	{	
		$.ajax({
				'url': base_url + '/' + controller + '/showData',
				'type': 'POST',
				'dataType': 'json',
				'data': {
							'customerRowId': 'customerRowId'
						},
				'success': function(data)
				{
					if(data)
					{
						// alert(JSON.stringify(data));
							setTable(data['records'], data['recordsNegative']) 
							alertPopup('Records loaded...', 4000);
					}
				},
				error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
		});
		
	}

	
	var gCustomerRowId = -1;


	var gLedgerRowId, gCeRowId, gReminder;
	

</script>
<div class="container" style="width: 95%">
	<div class="row" id="divDues">
		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
			<div class="row">
				<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
					<h3 class="text-center" style='margin-top:-20px'>Dues</h3>
					<form name='frm1' id='frm1' method='post' enctype='multipart/form-data' action="">
						<div class="row" style="margin-top:-15px;">
							<div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
								<?php
									echo "<input type='button' onclick='loadData();' value='Show Data' id='btnShow1' class='btn btn-primary form-control'>";
				              	?>
				          	</div>
				          	
							<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
				          	</div>
							<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
				          	</div>
						</div>
					</form>
				</div>
			</div>


			<div class="row" style="margin-top:20px;" >
				<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
					<div id="divTable" class="divTable col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border:1px solid lightgray; padding: 10px;height:500px; overflow:auto;">
						<table class='table table-hover' id='tbl1'>
						 <thead>
							 <tr>
								<th style='display:none;'>customerRowid</th>
							 	<th style='display:none1;'>Name</th>
							 	<th style='display:none1;'>Dues</th>
							 	<th style='display:none1;'>Receive</th>
							 	<th style='display:none1;'>Remarks</th>
							 	<th style='display:none1;'></th>
							 	<th style='display:none;'>Mobile</th>
							 	<th style='display:none1;'>Doobat</th>
							 </tr>
						 </thead>
						 <tbody>

						 </tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="row" style="margin-top:5px;" >
				<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
					
				</div>
				
				<div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
					
				</div>
				<div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
					<?php
						echo "<label style='color: black; font-weight: normal;'>Total Dues:</label>";
						echo form_input('txtTotalDues', '', "class='form-control' placeholder='' id='txtTotalDues' maxlength='10' disabled='yes'");
		          	?>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
					
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
					
				</div>
			</div>
		</div>


	</div>
	
	<div class="row" style="margin-top: 20px;">
			<div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
				<?php
				echo "<label style='color: black; font-weight: normal;'>&nbsp;</label>";
					echo form_input('txtDateDelete', '01-Apr-2018', "class='form-control' id='txtDateDelete' style='' maxlength=11 autocomplete='off' placeholder='date'");
	          	?>
	          	<script>
					$( "#txtDateDelete" ).datepicker({
						dateFormat: "dd-M-yy",changeMonth: true,changeYear: true,yearRange: "2010:2050"
					});
					// Set the 1st of this month
					// $("#txtDateDelete").val(dateFormat(new Date()));
				</script>	
	      	</div>
			<div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
				<?php
					echo "<label style='color: black; font-weight: normal;'>&nbsp;</label>";
					echo "<input type='button' onclick='deleteOldRecs();' value='Del. Old Rec., Sale, Purchase < dt' id='btnDelOldRec' class='btn btn-danger btn-block'>";
		      	?>
			</div>
			<label>IN ABOVE DEL -> del from ledger before defined dt and carry OB, del BKP table all, del Solved Complaints, del Notifications (deleted), reminders(deleted), del replacement(sent n recd), SendSms table all, Sale before defined dt, Purchase before defined dt</label>
		</div>
</div>

  


<script type="text/javascript">
	

	$(document).ready( function () {
	    myDataTable = $('#tbl1').DataTable({
		    paging: false,
		    iDisplayLength: -1,
		    aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]]

		});
		$("#btnShow1").trigger("click");

	} );


</script>


<!-- Delete zero bal from ledger -->
<script type="text/javascript">
	function deleteOldRecs()
	{
		dt = $("#txtDateDelete").val().trim();
		dtOk = testDate("txtDateDelete");
		if(dtOk == false)
		{
			alertPopup("Invalid date...", 5000);
			$("#txtDateDelete").focus();
			return;
		}

		var p = prompt("Enter Password...", "M...B...9...0");
		if (p === null) {
	        return; //break out of the function early
	    }
		
		$.ajax({
			'url': base_url + '/' + controller + '/deleteOldRecs',
			'type': 'POST',
			'dataType': 'json',
			'data': {'p': p, 'dt': dt},
			'success': function(data){
				if(data == "Invalid...")
				{
	                alertPopup("Invalid pwd... ", 6000, 'red', 'white');
				}
				else
				{
	                alertPopup("Updated... ", 6000);
	            }
			},
	          'error': function(jqXHR, exception)
	          {
	            $("#paraAjaxErrorMsg").html( jqXHR.responseText );
	            $("#modalAjaxErrorMsg").modal('toggle');
	          }
		});

	}
</script>