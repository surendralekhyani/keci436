<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/datatable/buttons.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/jszip.min.js"></script>

<script type="text/javascript">
	var controller='Items_Controller';
	var base_url='<?php echo site_url();?>';

	vModuleName = "Items";

	function setTable(records)
	{
		 // alert(JSON.stringify(records));
		  $("#tbl1").empty();
	      var table = document.getElementById("tbl1");
	      for(i=0; i<records.length; i++)
	      {
	          newRowIndex = table.rows.length;
	          row = table.insertRow(newRowIndex);


	          var cell = row.insertCell(0);
	          cell.innerHTML = "<span class='glyphicon glyphicon-pencil'></span>";
	          cell.style.textAlign = "center";
	          cell.style.color='lightgray';
	          cell.setAttribute("onmouseover", "this.style.color='green'");
	          cell.setAttribute("onmouseout", "this.style.color='lightgray'");
	          cell.className = "editRecord";

	          var cell = row.insertCell(1);
				  cell.innerHTML = "<span class='glyphicon glyphicon-remove'></span>";
	          cell.style.textAlign = "center";
	          cell.style.color='lightgray';
	          cell.setAttribute("onmouseover", "this.style.color='red'");
	          cell.setAttribute("onmouseout", "this.style.color='lightgray'");
	          cell.setAttribute("onclick", "delrowid(" + records[i].itemRowId +")");
	          // data-toggle="modal" data-target="#myModal"
	          cell.setAttribute("data-toggle", "modal");
	          cell.setAttribute("data-target", "#myModal");

	          var cell = row.insertCell(2);
	          // cell.style.display="none";
	          cell.innerHTML = records[i].itemRowId;
	          var cell = row.insertCell(3);
	          cell.innerHTML = records[i].itemName;
	          var cell = row.insertCell(4);
	          cell.innerHTML = records[i].sellingPrice;
			  var cell = row.insertCell(5);
	          cell.innerHTML = records[i].pp;
			  var cell = row.insertCell(6);
	          cell.innerHTML = records[i].gstRate;
			  var cell = row.insertCell(7);
	          cell.innerHTML = records[i].hsn;
	  	  }


	  	$('.editRecord').bind('click', editThis);

		myDataTable.destroy();
		$(document).ready( function () {
	    myDataTable=$('#tbl1').DataTable({
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
	function deleteRecord()
	{
		// alert(rowId);
		$.ajax({
				'url': base_url + '/' + controller + '/delete',
				'type': 'POST',
				'dataType': 'json',
				'data': {'rowId': globalRowIdForDeletion},
				'success': function(data){
					if(data)
					{
						// alert(data);
						if( data['dependent'] == "yes" )
						{
							alert("Record can not be deleted...\n\r Dependent records exist...");
						}
						else
						{
							setTable(data['records'])
							alertPopup('Record deleted...', 4000);
							blankControls();
							$("#txtItemName").focus();
						}
					}
				},
					error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});
	}
	
	function saveData()
	{	
		itemName = $("#txtItemName").val().trim();
		// alert(itemName);
		if(itemName == "")
		{
			alert("Item name can not be blank...");
			// alertPopup("Prefix type can not be blank...", 8000, 'red');
			$("#txtItemName").focus();
			return;
		}

		sellingPrice = $("#txtSellingPrice").val();
		purchasePrice = $("#txtPurchasePrice").val();
		gstRate = $("#txtGstRate").val();
		hsn = $("#txtHsn").val();

		if($("#btnSave").val() == "Save")
		{
			// alert("save");
			$.ajax({
					'url': base_url + '/' + controller + '/insert',
					'type': 'POST',
					'dataType': 'json',
					'data': {
								'itemName': itemName
								, 'sellingPrice': sellingPrice
								, 'purchasePrice': purchasePrice
								, 'gstRate': gstRate
								, 'hsn': hsn
							},
					'success': function(data)
					{
						if(data)
						{
							// alert(data);
							if(data == "Duplicate record...")
							{
								alert("Duplicate record...");
								// alertPopup("Duplicate record...", 4000, 'red');
								$("#txtItemName").focus();
							}
							else
							{
								setTable(data['records']) ///loading records in tbl1
								 alertPopup('Record saved...  ', 4000);
								blankControls();
								$("#txtItemName").focus();
								// location.reload();
							}
						}
							
					},
					error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});
		}
		else if($("#btnSave").val() == "Update")
		{
			// alert("update");
			$.ajax({
					'url': base_url + '/' + controller + '/update',
					'type': 'POST',
					'dataType': 'json',
					'data': {'globalrowid': globalrowid
								, 'itemName': itemName
								, 'sellingPrice': sellingPrice
								, 'purchasePrice': purchasePrice
								, 'gstRate': gstRate
								, 'hsn': hsn
							},
					'success': function(data)
					{
						if(data)
						{
							// alert(data);
							if(data == "Duplicate record...")
							{
								alert("Duplicate record...");
								// alertPopup("Duplicate record...", 5000, 'red');
								$("#txtItemName").focus();
							}
							else
							{
								setTable(data['records']) ///loading records in tbl1
								 alertPopup('Record updated...  ', 4000);
								blankControls();
								$("#btnSave").val("Save");
								$("#txtItemName").focus();
							}
						}
							
					},
					error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});
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
						setTable(data['records'])
						alertPopup('Records loaded...', 4000);
						blankControls();
						$("#txtItemName").focus();
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
		<div class="col-md-12">
			<h1 class="text-center" style='margin-top:-20px'>Items</h1>
			<div class="row" style="margin-top:25px;">
				<div class="col-md-12">
					<?php
						// echo $this->session->orgRowId;
						// echo    "    " . $this->session->orgName;
						echo "<label style='color: black; font-weight: normal;'>Item Name:</label>";
						echo form_input('txtItemName', '', "class='form-control' autofocus id='txtItemName' style='text-transform: capitalize;' maxlength=99 autocomplete='off'");
	              	?>
	          	</div>
			</div>

			<div class="row" style="margin-top:10px;">
				<div class="col-md-2">
					<?php
						echo "<label style='color: black; font-weight: normal;'>Selling Price</label>";
						echo "<input type='number' value='0' id='txtSellingPrice' class='form-control'>";
					?>
				</div>
				<div class="col-md-2">
					<?php
						echo "<label style='color: black; font-weight: normal;'>Purchase Price</label>";
						echo "<input type='number' value='0' id='txtPurchasePrice' class='form-control'>";
					?>
				</div>
				<div class="col-md-2">
					<?php
						echo "<label style='color: black; font-weight: normal;'>GST Rate (%):</label>";
						echo "<input type='number' value='0' id='txtGstRate' class='form-control'>";
					?>
				</div>
				<div class="col-md-2">
					<?php
						echo "<label style='color: black; font-weight: normal;'>HSN:</label>";
						echo "<input type='text' value='' id='txtHsn' class='form-control'>";
					?>
				</div>
				<div class="col-md-4">
					<?php
						echo "<label style='color: black; font-weight: normal;'>&nbsp;	</label>";
						echo "<input type='button' onclick='saveData();' value='Save' id='btnSave' class='btn btn-primary form-control'>";
	              	?>
	          	</div>
			</div>
		</div>
	</div>


	<div class="row" style="margin-top:20px;" >
		<div class="col-md-12">
			<div id="divTable" class="divTable col-md-12" style="border:1px solid lightgray; padding: 10px;height:300px; overflow:auto;">
				<table class='table table-hover' id='tbl1'>
				 <thead>
					 <tr>
					 	<th  width="50" class="editRecord text-center">Edit</th>
					 	<th  width="50" class="text-center">Delete</th>
						<th style='width:0px;display:none1;'>rowid</th>
					 	<th>Item Name</th>
					 	<th>S.Price</th>
					 	<th>P.Price</th>
					 	<th>GST Rate</th>
					 	<th>HSN</th>
					 </tr>
				 </thead>
				 <tbody>
					 <?php 
						foreach ($records as $row) 
						{
						 	$rowId = $row['itemRowId'];
							echo '<td style="color: lightgray;cursor: pointer;cursor: hand;" class="editRecord text-center" onmouseover="this.style.color=\'green\';"  onmouseout="this.style.color=\'lightgray\';"><span class="glyphicon glyphicon-pencil"></span></td>
								   <td style="color: lightgray;cursor: pointer;cursor: hand;" class="text-center" onclick="delrowid('.$rowId.');" data-toggle="modal" data-target="#myModal" onmouseover="this.style.color=\'red\';"  onmouseout="this.style.color=\'lightgray\';"><span class="glyphicon glyphicon-remove"></span></td>';
						 	echo "<td style='width:0px;display:none1;'>".$row['itemRowId']."</td>";
						 	echo "<td>".$row['itemName']."</td>";
						 	echo "<td>".$row['sellingPrice']."</td>";
						 	echo "<td>".$row['pp']."</td>";
						 	echo "<td>".$row['gstRate']."</td>";
						 	echo "<td>".$row['hsn']."</td>";
							echo "</tr>";
						}
					 ?>
				 </tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="row" style="margin-top:20px;" >
		<div class="col-md-8">
		</div>

		<div class="col-md-4">
			<?php
				echo "<input type='button' onclick='loadAllRecords();' value='Load All Records' id='btnLoadAll' class='btn form-control' style='background-color: lightgray;'>";
	      	?>
		</div>
		<div class="col-md-2">
		</div>

	</div>
</div>



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
		          <button type="button" onclick="deleteRecord();" class="btn btn-danger" data-dismiss="modal">Yes</button>
		          <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
		        </div>
		      </div>
		    </div>
		  </div>


<script type="text/javascript">
	var globalrowid;
	var globalRowIdForDeletion;
	function delrowid(rowid)
	{
		globalRowIdForDeletion = rowid;
	}

	$('.editRecord').bind('click', editThis);
	function editThis(jhanda)
	{
		rowIndex = $(this).parent().index();
		colIndex = $(this).index();
		itemName = $(this).closest('tr').children('td:eq(3)').text();
		globalrowid = $(this).closest('tr').children('td:eq(2)').text();
		sellingPrice = $(this).closest('tr').children('td:eq(4)').text();
		purchasePrice = $(this).closest('tr').children('td:eq(5)').text();

		$("#txtItemName").val(itemName);
		$("#txtSellingPrice").val(sellingPrice);
		$("#txtPurchasePrice").val(purchasePrice);
		$("#txtGstRate").val( $(this).closest('tr').children('td:eq(6)').text() );
		$("#txtHsn").val( $(this).closest('tr').children('td:eq(7)').text() );
		$("#txtItemName").focus();
		$("#btnSave").val("Update");
	}


		$(document).ready( function () {
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

</script>