
<style type="text/css">
	.ui-autocomplete {
	    max-height: 200px;
	    overflow-y: auto;   /* prevent horizontal scrollbar */
	    overflow-x: hidden; /* add padding to account for vertical scrollbar */
	    z-index:1000 !important;
	}
</style>

<script type="text/javascript">
	var controller='PaymentReceipt_Controller';
	var base_url='<?php echo site_url();?>';

	vModuleName = "Payment/Receipt";


	
	function saveData()
	{	
		dt = $("#txtDate").val().trim();
		dtOk = testDate("txtDate");
		if(dtOk == false)
		{
			alertPopup("Invalid date...", 5000);
			$("#txtDate").focus();
			return;
		}

		customerRowId = $("#lblCustomerId").text();
		customerName = $("#txtCustomerName").val().trim();
		if(customerName == "" || customerRowId == ""  || customerRowId == "-2" )
		{
			alertPopup("Invalid customer...", 5000, 'red');
			$("#txtCustomerName").focus();
			return;
		}
		mobile1 = $("#txtMobile").val().trim();
		address = $("#txtAddress").val().trim();
		customerRemarks = $("#txtCustomerRemarks").val().trim();
		amt = parseFloat($("#txtAmt").val());
		remarks = $("#txtRemarks").val().trim();
		if(remarks == "" )
		{
			alertPopup("Enter remarks...", 5000, 'red');
			$("#txtRemarks").focus();
			return;
		}
		// inWords = $("#txtWords").val().trim();
		transactionMode = $("#cboMode").val();
		if(transactionMode == "-1" )
		{
			alertPopup("Select transaction mode...", 8000, 'red');
			$("#cboMode").focus();
			return;
		}

		// console.log(globalrowid);
		// return;

		///////For ledger
		var date = new Date();
		var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
		dtFrom = dateFormat(firstDay);
		$("#dtFrom").val(dateFormat(firstDay));
  		// var dtFrom = $("#dt").val();
  		// var dtTo = $("#txtDate").val();
  		// $("#dtTo").val( $("#txtDate").val() );
  		$("#dtTo").val( dateFormat(new Date()) );
  		var dtTo = $("#dtTo").val();

  		$("#cboCustomer4Ledger").val(customerRowId);
  		///////END - For ledger


		if($("#btnSave").text() == "Save")
		{
			$.ajax({
					'url': base_url + '/' + controller + '/insert',
					'type': 'POST',
					'dataType': 'json',
					'data': {
								'dt': dt
								, 'customerRowId': customerRowId
								, 'customerName': customerName
								, 'mobile1': mobile1
								, 'address': address
								, 'customerRemarks': customerRemarks
								, 'amt': amt
								, 'remarks': remarks
								, 'transactionMode': transactionMode
								, 'dtFrom': dtFrom
								, 'dtTo': dtTo
							},
					'success': function(data)
					{
						if(data == "Duplicate new customer...")
						{
							alertPopup("Duplicate new customer...Check its rowId", 8000, 'red');
							$("#txtCustomerName").focus();
						}
						else
						{
							$("#txtAmt").val("0");
							$("#txtRemarks").val("");
							$("#cboMode").val("-1");
							alertPopup("Record saved...", 3000);
							setTablePuraneReceipt(data['records']);
							// console.log(data['newBalance']);
							if( data['newBalance'] )
							{
								$("#txtBalance").val( data['newBalance'][0].balance );
							}
							if(customerRowId > 0)
							{
								// setLedgerTable(data['opBal'], data['records4Ledger']);
								// location.reload();

							}
							if(customerRowId == "-1")
							{
								location.reload();
							}
						}
					},
					'error': function(jqXHR, exception)
			          {
			            $("#paraAjaxErrorMsg").html( jqXHR.responseText );
			            $("#modalAjaxErrorMsg").modal('toggle');
			          }
			});
		}
		else if($("#btnSave").text() == "Update")
		{
			// // alert("update");
			// $.ajax({
			// 		'url': base_url + '/' + controller + '/update',
			// 		'type': 'POST',
			// 		'dataType': 'json',
			// 		'data': {'globalrowid': globalrowid
			// 					, 'dt': dt
			// 					, 'customerRowId': customerRowId
			// 					, 'customerName': customerName
			// 					, 'mobile1': mobile1
			// 					, 'address': address
			// 					, 'customerRemarks': customerRemarks
			// 					, 'amt': amt
			// 					, 'remarks': remarks
			// 					, 'transactionMode': transactionMode
			// 			},
			// 		'success': function(data)
			// 		{
			// 			blankControls();
			// 			// alert(JSON.stringify(data['records']));
			// 			setTablePuraneReceipt(data['records']);
			// 			$("#txtDate").val(dateFormat(new Date()));
			// 			alertPopup("Record updated...", 3000);
			// 			$("#txtCustomerName").prop("disabled", false);
			// 			$("#btnSave").text("Save");
			// 			$("#spanNewOrOld").text("New");
			// 	  		$("#spanNewOrOld").removeClass("label-success");
			// 	  		$("#spanNewOrOld").addClass("label-danger");
			// 	        $("#spanNewOrOld").animate({opacity: '0.2'}, 1000);
			// 	        $("#spanNewOrOld").animate({opacity: '1'}, 1000);
			// 		},
			// 		'error': function(jqXHR, exception)
			//           {
			//             $("#paraAjaxErrorMsg").html( jqXHR.responseText );
			//             $("#modalAjaxErrorMsg").modal('toggle');
			//           }
			// });
		}
	}

	function loadAllRecords()
	{
		// alert(rowId);
		$.ajax({
				'url': base_url + '/' + controller + '/loadAllRecords',
				'type': 'POST',
				'dataType': 'json',
				'success': function(data)
				{
					if(data)
					{
						setTablePuraneReceipt(data['records'])
						alertPopup('Records loaded...', 4000);
					}
				},
					error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});
	}
</script>

<script type="text/javascript">
	function setDilip()
	{
		$("#txtCustomerName").val("Dilip Lekhyani");
		$("#txtCustomerName").autocomplete('search', 'Dilip Lekhyani');
		var list = $(txtCustomerName).autocomplete("widget");
        $(list[0].children[0]).click();
        $("#txtCustomerName").blur();
	}
	function setSuri()
	{
		$("#txtCustomerName").val("Surendra Lekhyani");
		$("#txtCustomerName").autocomplete('search', 'Surendra Lekhyani');
		var list = $(txtCustomerName).autocomplete("widget");
        $(list[0].children[0]).click();
        $("#txtCustomerName").blur();
	}

</script>
<div class="container-fluid" style="width: 97%;">
	<div class="row" style='margin-top:-10px;'>
		<div class="col-md-4 col-xs-2">
			<?php
				echo "<label style='color: lightgrey; font-weight: normal; margin-top:10px;' id='lblCustomerId'>-2</label>";
			?>
			<span style="padding: 5px 10px;" class="label label-success" onclick="setDilip();">Deepu</span>
			<span style="padding: 5px 10px;" class="label label-danger" onclick="setSuri();">Suri</span>
		</div>
		<div class="col-md-4 col-xs-6">
			<h4 class="text-center">Payment / Receipt</h4>
		</div>
		<div class="col-md-4 col-xs-4 text-right">
			<h4 style='font-size: 14pt;' id='lblNewOrOld'><span id='spanNewOrOld' class='label label-danger'>New</span></h4>

		</div> 
	</div>
	 
	<div class="row" style="background-color: #F0F0F0; padding-top: 1px; padding-bottom: 10px;" >
		<div class="col-md-3 col-xs-5" style='margin-top: 10px;'>
			<?php
				echo form_input('txtDate', '', "class='form-control' id='txtDate' style='' maxlength=10 autocomplete='off' placeholder='date'");
          	?>
      	</div>
		<div class="col-md-6 col-xs-7" style='margin-top: 10px;'>
			<?php
				echo form_input('txtCustomerName', '', "class='form-control' id='txtCustomerName' style='' maxlength=70 autocomplete='off' placeholder='Name'");
          	?>
      	</div>
      	<div class="col-md-3 col-xs-5" style='margin-top: 10px;'>
			<?php
				echo form_input('txtBalance', '', "class='form-control' id='txtBalance' style='' placeholder='Balance' disabled='yes'");
          	?>
      	</div>
		<div class="col-md-3 col-xs-7" style='margin-top: 10px;'>
			<?php
				echo form_input('txtMobile', '', "class='form-control' id='txtMobile' style='' maxlength=10 autocomplete='off'  placeholder='Mobile No.'");
          	?>
      	</div>
		<div class="col-md-6 col-xs-5" style='margin-top: 10px;'>
			<?php
				echo form_input('txtAddress', '', "class='form-control' id='txtAddress' style='' maxlength=100 placeholder='Address'");
          	?>
      	</div>
		<div class="col-md-3 col-xs-7" style='margin-top: 10px;'>
			<?php
				// echo "<label style='colr: black; font-weight: normal;'>Remarks:</label>";
				echo form_input('txtCustomerRemarks', '', "class='form-control' id='txtCustomerRemarks' style='' maxlength=100 autocomplete='off' placeholder='About Customer'");
          	?>
      	</div>
	</div>

	
	<div class="row" style="margin-top: 10px;background-color: #F0F0F0; padding-top:1px;padding-bottom:10px;">
		<div class="col-md-3 col-xs-5" style='margin-top: 10px;'>
			<?php
				// echo "<label style='color: black; font-weight: normal;'>Amt.:</label>";
				echo '<input type="number"  step="1" name="txtAmt" value="0" class="form-control" maxlength="15" id="txtAmt"  placeholder="Amt."/>';
          	?>
      	</div>
      	<div class="col-md-2 col-xs-7" style='margin-top: 10px;'>
      		<?php
				$modes = array();
				$modes['-1'] = '--- Select ---';
				$modes['Payment'] = "Paid";
				$modes['Received'] = "Received";
				$modes['UPI'] = "UPI Payment";
				// echo "<label style='color: black; font-weight: normal;'>Mode:</label>";
				echo form_dropdown('cboMode', $modes, '-1',"class='form-control' id='cboMode'");
          	?>      	</div>
		<div class="col-md-4 col-xs-12" style='margin-top: 10px;'>
			<?php
				// echo "<label style='color: black; font-weight: normal;'>Remarks:</label>";
				echo form_input('txtRemarks', '', "class='form-control' id='txtRemarks' style='' maxlength=100 autocomplete='on' placeholder='Remarks'");
          	?>
      	</div>
		
		<div class="col-md-3 col-xs-12" style='margin-top: 10px;'>
			<?php
				// echo "<label style='color: black; font-weight: normal;'>&nbsp;</label>";
          	?>
          	<button id="btnSave" class="btn btn-primary btn-block" onclick="saveData();">Save</button>
      	</div>

		<div class="col-md-12" style='display: none;'>
		<?php
			echo "<label style='color: black; font-weight: normal;'>In Words:</label>";
			echo '<input type="text" disabled name="txtWords" value="" placeholder="" class="form-control" id="txtWords" />';
      	?>
      	</div>
	</div>

	<div class="row" style="display: none1;">
  	</div>

	<div class="row"  style="margin-top: 25px;">
		<div class="col-md-12 col-xs-12" style="margin-top: 10px;">
			<div id="divTableOldDb" class="divTable tblScroll" style="border:1px solid lightgray; height:400px; overflow:auto;">
				<table class='table table-hover' id='tblOldReceipts'>
				 <thead>
					 <tr>
					 	<th  width="50" class="editRecord text-center" style='display:none;'>Ed</th>
					 	<th  width="50" class="text-center">Del</th>
						<th>VT</th>
						<th>V#</th>
					 	<th>Date</th>
					 	<th style='display:none;'>customerRowId</th>
					 	<th>Customer Name</th>
					 	<th>Amt</th>
					 	<th>Remarks</th>
					 </tr>
				 </thead>
				 <tbody>
					 <?php 
						foreach ($records as $row) 
						{
						 	$rowId = $row['refRowId'];
						 	$customerRowId=$row['customerRowId'];
							if( $row['vType'] == "P")
						 	{
						 		echo "<tr>";	
							}
							else{
								echo "<tr style='background:lightgrey;'>";	
							}					//onClick="editThis(this);
						 	if($row['deleted'] == "N")
						 	{
							echo '<td style="display:none; color: lightgray;cursor: pointer;cursor: hand;" class="editRecord text-center" onmouseover="this.style.color=\'green\';"  onmouseout="this.style.color=\'lightgray\';"><span class="glyphicon glyphicon-pencil"></span></td>
								   <td style="color: lightgray;cursor: pointer;cursor: hand;" class="deleteRecord text-center" onclick="delrowid('.$rowId.');" data-toggle="modal" data-target="#myModal" onmouseover="this.style.color=\'red\';"  onmouseout="this.style.color=\'lightgray\';"><span class="glyphicon glyphicon-remove"></span></td>';
							}
							else
							{
								echo '<td style="display:none; color: red;" class="text-center"><span class="">Deleted</span></td>
								   <td style="color: red;" class="text-center"><span class="">Deleted</span></td>';		
							}
						 	echo "<td style='width:0px;'>".$row['vType']."</td>";
						 	echo "<td style='width:0px;'>".$row['refRowId']."</td>";
						 	$vdt = strtotime($row['refDt']);
							$vdt = date('d-M-Y', $vdt);
						 	echo "<td>".$vdt."</td>";
						 	echo "<td style='display:none;'>".$row['customerRowId']."</td>";
						 	// echo "<td><a id='contraac' target='_blank' href='" . base_url() . "'/index.php/rptledger/yeParty/".$row['customerName']."/".$row['customerRowId']."'>" . $row['customerName'] . "</a></td>";
						 	echo "<td><a id='contraac' target='_blank' href=" . base_url() . "/index.php/rptledger/yeParty/".urlencode($row['customerName'])."/".$row['customerRowId'].">" . $row['customerName'] . "</a></td>";

						 	if( $row['vType'] == "P")
						 	{
						 		echo "<td>".$row['amt']."</td>";
						 	}
						 	else if( $row['vType'] == "R" )
						 	{
						 		echo "<td>".$row['recd']."</td>";
						 	}
							echo "<td>".$row['remarks']."</td>";
							echo "</tr>";
						}
					 ?>
				 </tbody>
				</table>
			</div>
		</div>
		
	</div>

	<div class="row" style="margin-top:20px;margin-bottom:20px;" >
		<div class="col-md-4 col-xs-12">
			<?php
				echo "<input type='button' onclick='loadAllRecords();' value='Load All Records' id='btnLoadAll' class='btn form-control' style='background-color: lightgray;'>";
	      	?>
		</div>
		<div class="col-md-8 col-xs-0">
		</div>
	</div>
</div> <!-- CONTAINER CLOSE -->


		<div class="modal" id="myModal" role="dialog">
		    <div class="modal-dialog modal-sm">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title">BS</h4>
		        </div>
		        <div class="modal-body">
		          <p>Are you sure <br /> Delete this record..?</p>
		        </div>
		        <div class="modal-footer">
		          <button type="button" onclick="deleteRecord(globalrowid);" class="btn btn-danger" data-dismiss="modal">Yes</button>
		          <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
		        </div>
		      </div>
		    </div>
		</div>
		  

<script type="text/javascript">
	


    $(document).ready(function()
    {
    	$( "#txtDate" ).datepicker({
			dateFormat: "dd-M-yy",changeMonth: true,changeYear: true,yearRange: "2010:2050"
		});
	    // Set the Current Date as Default
		$("#txtDate").val(dateFormat(new Date()));
      	$("#cboMode").on('change', setModeInRemarks);
  	});

    function setModeInRemarks()
	{
		var md = $('#cboMode').val();
		if( md == "UPI" )
		{
			$("#txtRemarks").val(md);
			$("#txtRemarks").prop("disabled", true);
		}
		else
		{
			$("#txtRemarks").val("");
			$("#txtRemarks").prop("disabled", false);
		}
	}

	// function funInWords()
	// {
	// 	// var netInWords = number2text( parseFloat( $("#txtAmt").val() ) ) ;
	//  //  	$("#txtWords").val( netInWords );
	// }

	

	var select = false;
    $( "#txtCustomerName" ).focus(function(){ 
	  			select = false; 
	  			// $("#txtAddress").val(select);
	  		});

    function bindCustomers()
    {
		select = false;
		var jSonArray = '<?php echo json_encode($customers); ?>';
		// alert(jSonArray);
		var jSonArray = jSonArray.replace(/(\r\n|\n|\r)/gm,", "); ///Multilinse of Address field with comma replce
		var availableTags = $.map(JSON.parse(jSonArray), function(obj){
					return{
							label: obj.customerName,
							customerRowId: obj.customerRowId,
							address: obj.address,
							mobile1: obj.mobile1,
							remarks: obj.remarks,
							balance: obj.balance
					}
		});

	    $( "#txtCustomerName" ).autocomplete({
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
			    $("#lblCustomerId").text( ui.item.customerRowId );
			    $("#txtMobile").val( ui.item.mobile1 );
			    $("#txtAddress").val( ui.item.address );
			    $("#txtCustomerRemarks").val( ui.item.remarks );
			    $("#txtBalance").val( ui.item.balance );
	        	}
		    }).blur(function() {
				  if( !select ) 
				  {
				  	$("#lblCustomerId").text('-1');
				  	// $("#tbl1").find("tr:eq(" + rowIndex + ")").find("td:eq(1)").css("color", "red");
				  }
				  	if( $("#lblCustomerId").text() == '-1' )
				  	{
				  		$("#spanNewOrOld").text("New");
				  		$("#spanNewOrOld").removeClass("label-success");
				  		$("#spanNewOrOld").addClass("label-danger");
				        $("#spanNewOrOld").animate({opacity: '0.2'}, 1000);
				        $("#spanNewOrOld").animate({opacity: '1'}, 1000);
				        $("#spanNewOrOld").animate({opacity: '1'}, 1000);
				        $("#txtMobile").prop('disabled', false);
					    $("#txtAddress").prop('disabled', false);
					    $("#txtCustomerRemarks").prop('disabled', false);
					    $("#txtMobile").val( '' );
					    $("#txtAddress").val( '' );
					    $("#txtCustomerRemarks").val( '' );
					    $("#txtBalance").val( '' );
					    $("#txtMobile").focus();
				  	}
				  	else
				  	{
				  		$("#spanNewOrOld").text("Old");
				  		$("#spanNewOrOld").removeClass("label-danger");
				  		$("#spanNewOrOld").addClass("label-success");
				        $("#spanNewOrOld").animate({opacity: '0.2'}, 1000);
				        $("#spanNewOrOld").animate({opacity: '1'}, 1000);
				        $("#spanNewOrOld").animate({opacity: '1'}, 1000);
				        $("#txtMobile").prop('disabled', true);
					    $("#txtAddress").prop('disabled', true);
					    $("#txtCustomerRemarks").prop('disabled', true);
					    $("#txtCustomerBalance").prop('disabled', true);
					    $("#txtAmt").focus();
				  	}
				}).focus(function(){            
			            $(this).autocomplete("search");
					});    	
    }
	$(document).ready( function () 
	{
		bindCustomers();
    } );
  </script>


  <script type="text/javascript">
	var globalrowid;
	var globalVtype;
	var editFlag = 0;
	$('.deleteRecord').bind('click', delrowid);
	function delrowid(rowid)
	{
		// globalrowid = rowid;
		rowIndex = $(this).parent().index();
		colIndex = $(this).index();
		globalrowid = $(this).closest('tr').children('td:eq(3)').text();
		globalVtype = $(this).closest('tr').children('td:eq(2)').text();
		// alert(vType);
	}
	function deleteRecord(rowId)
	{
		// alert(globalrowid);
		// return;
		$.ajax({
				'url': base_url + '/' + controller + '/delete',
				'type': 'POST',
				'dataType': 'json',
				'data': {'globalrowid': globalrowid, 'globalVtype': globalVtype
						},
				'success': function(data){
					// alert(data);
					if(data)
					{
						if( data == "yes" )
						{
							alertPopup('Record can not be deleted... Dependent records exist...', 8000);
						}
						else
						{
							setTablePuraneReceipt(data['records'])
							alertPopup('Record deleted...', 8000);
						}
					}
				},
					error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});
	}

	function setTablePuraneReceipt(records)
	{
		$("#tblOldReceipts").empty();
        var table = document.getElementById("tblOldReceipts");
        for(i=0; i<records.length; i++)
        {
          var newRowIndex = table.rows.length;
          var row = table.insertRow(newRowIndex);

          if(records[i].deleted == "N")
          {
	          var cell = row.insertCell(0);
	          cell.innerHTML = "<span class='glyphicon glyphicon-pencil'></span>";
	          cell.style.textAlign = "center";
	          cell.style.color='lightgray';
	          cell.setAttribute("onmouseover", "this.style.color='green', this.style.cursor='pointer'");
	          cell.setAttribute("onmouseout", "this.style.color='lightgray'");
	          cell.className = "editRecord";
	          cell.style.display="none";

	          var cell = row.insertCell(1);
				  cell.innerHTML = "<span class='glyphicon glyphicon-remove'></span>";
	          cell.style.textAlign = "center";
	          cell.style.color='lightgray';
	          cell.setAttribute("onmouseover", "this.style.color='red', this.style.cursor='pointer'");
	          cell.setAttribute("onmouseout", "this.style.color='lightgray'");
	          cell.setAttribute("onclick", "delrowid(" + records[i].refRowId +")");
	          // data-toggle="modal" data-target="#myModal"
	          cell.setAttribute("data-toggle", "modal");
	          cell.setAttribute("data-target", "#myModal");
	          cell.className = "deleteRecord";
	       }
      	  else
      	  {
	          var cell = row.insertCell(0);
	          cell.innerHTML = "<span class=''>Deleted</span>";
	          cell.style.textAlign = "center";
	          cell.style.color='red';
	          cell.style.display="none";

	          var cell = row.insertCell(1);
				  cell.innerHTML = "<span class=''>Deleted</span>";
	          cell.style.textAlign = "center";
	          cell.style.color='red';
      	  }
          var cell = row.insertCell(2);
          cell.innerHTML = records[i].vType;
          var cell = row.insertCell(3);
          cell.innerHTML = records[i].refRowId;
          var cell = row.insertCell(4);
          cell.innerHTML = dateFormat(new Date(records[i].refDt));
          // cell.style.display="none";
          var cell = row.insertCell(5);
          cell.innerHTML = records[i].customerRowId;
          cell.style.display="none";
          var cell = row.insertCell(6);
        //   cell.innerHTML = "<a id='contraac' href='#' onClick='loadLedger("+records[i].customerRowId+");'>" + records[i].customerName + "</a>";
          cell.innerHTML = "<a id='contraac' target='_blank' href='<?php  echo base_url();  ?>/index.php/rptledger/yeParty/"+records[i].customerName+"/"+records[i].customerRowId+"'>" + records[i].customerName + "</a>";
          
		  var cell = row.insertCell(7);
          if( records[i].vType == "P")
		  {
          	cell.innerHTML = records[i].amt;
          }
          else
          {
          	cell.innerHTML = records[i].recd;
			row.style.background="lightgrey";
          }
          var cell = row.insertCell(8);
          cell.innerHTML = records[i].remarks;
	    }

	    // $('.editRecord').bind('click', editThis);
	    $('.deleteRecord').bind('click', delrowid);

		myDataTable.destroy();
	    myDataTable=$('#tblOldReceipts').DataTable({
		    paging: false,
		    ordering: false,
		    iDisplayLength: -1,
		    aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
			select: true,
		});
	}

	

</script>

  <script type="text/javascript">
		$(document).ready( function () {
		    myDataTable = $('#tblOldReceipts').DataTable({
			    paging: false,
		    	ordering: false,
			    iDisplayLength: -1,
			    aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
				select: true,
			});
		} );

	
  </script>

  <script type="text/javascript">
	$(document).ready( function () {
	    myDataTableLedger = $('#tbl1').DataTable({
		    paging: false,
		    iDisplayLength: -1,
		    aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
			select: true,
		});
	} );

  </script>