<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/datatable/buttons.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/buttons.colVis.min.js"></script>
<script type="text/javascript">
	var controller='AddressBook_Controller';
	var base_url='<?php echo site_url();?>';

	vModuleName = "Address Book";

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
	          cell.setAttribute("onclick", "delrowid(" + records[i].rowId +")");
	          // data-toggle="modal" data-target="#myModal"
	          cell.setAttribute("data-toggle", "modal");
	          cell.setAttribute("data-target", "#myModal");

	          var cell = row.insertCell(2);
	          // cell.style.display="none";
	          cell.innerHTML = records[i].rowId;
	          var cell = row.insertCell(3);
	          cell.innerHTML = records[i].name;
	          var cell = row.insertCell(4);
	          cell.innerHTML = records[i].hNo;
	          var cell = row.insertCell(5);
	          cell.innerHTML = records[i].locality;
	          var cell = row.insertCell(6);
	          cell.innerHTML = records[i].occupation;
	          var cell = row.insertCell(7);
	          cell.innerHTML = records[i].telephone;
	          var cell = row.insertCell(8);
	          cell.innerHTML = records[i].mobile;
	          var cell = row.insertCell(9);
	          cell.innerHTML = records[i].remarks;
	          var cell = row.insertCell(10);
	          cell.innerHTML = records[i].showInDirectorySearch;
	          cell.style.display="none";
	  	  }


	  	$('.editRecord').bind('click', editThis);

		myDataTable.destroy();
		$(document).ready( function () {
		    myDataTable = $('#tbl1').DataTable({
			    paging: false,
			    iDisplayLength: -1,
			    aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
			    dom: 'Bfrtip',
			    select: true,
			    ordering: false,
		        buttons: [
		            'copyHtml5',
		            {
		                extend: 'excel',
		                title: 'Address Book',
		                messageTop: "d",
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

		
	}
	function deleteRecord(rowId)
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
							alert("Record can not be deleted...<br> Dependent records exist...");
							// alertPopup('Record can not be deleted... Dependent records exist...', 8000, 'red');
						}
						else
						{
							setTable(data['records'])
							alert("Record deleted...");
							// alertPopup('Record deleted...', 4000);
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
		name = $("#txtName").val().trim();
		if(name == "")
		{
			alert("Name can not be blank...");
			$("#txtName").focus();
			return;
		}

		hNo = $("#txtHNo").val().trim();
		locality = $("#txtLocality").val().trim();
		occupation = $("#txtOccupation").val().trim();
		telephone = $("#txtTelephone").val().trim();
		mobile = $("#txtMobile").val().trim();
		if(mobile.length<10 || mobile.length>10)///mobile no. must be 10 digit
		{
			alert("Enter valid mobile no.");
			$("#txtMobile").focus();
			return;
		}
		if(isNaN(mobile)==true)///mobile no. must be numeric
		{
			alert("Enter valid mobile no.");
			$("#txtMobile").focus();
			return;
		}
		if(mobile == "")///mobile no. must 
		{
			alert("Enter mobile no.");
			$("#txtMobile").focus();
			return;
		}
		remarks = $("#txtRemarks").val().trim();

		if($("#btnSave").val() == "Save")
		{
			// alert("save");
			$.ajax({
					'url': base_url + '/' + controller + '/insert',
					'type': 'POST',
					'dataType': 'json',
					'data': {
								'name': name
								, 'hNo': hNo
								, 'locality': locality
								, 'occupation': occupation
								, 'telephone': telephone
								, 'mobile': mobile
								, 'remarks': remarks
								// , 'groupRowId': groupRowId 
								// , 'showInDirectorySearch': showInDirectorySearch 								
							},
					'success': function(data)
					{
						if(data)
						{
							// alert(data);
							if(data == "Duplicate record...")
							{
								alertPopup("Duplicate mobile no...",5000);
							}
							else
							{
								setTable(data['records']) ///loading records in tbl1

								alertPopup('Record saved...', 4000);
								// blankControls();
								$("#txtName").focus();
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
								, 'name': name
								, 'hNo': hNo
								, 'locality': locality
								, 'occupation': occupation
								, 'telephone': telephone
								, 'mobile': mobile
								, 'remarks': remarks
								// , 'groupRowId': groupRowId
								// , 'showInDirectorySearch': showInDirectorySearch
								// , 'globalGroupDetailRowId': globalGroupDetailRowId 
							},
					'success': function(data)
					{
						if(data)
						{
							// alert(data);
							if(data == "Duplicate record...")
							{
								alertPopup("Duplicate mobile no...",5000);
							}
							else
							{
								setTable(data['records']) ///loading records in tbl1
								alertPopup('Record updated...', 4000);
								// myAlert("Record updated...");
								blankControls();
								$('input:checked').each(function() {
									$(this).removeAttr('checked');
								});
								$("#btnSave").val("Save");
								$("#txtName").focus();
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
<div class="container-fluid" style="width: '90%'">
	<div class="row">
		<div class="col-md-12">
			<h3 class="text-center" style='margin-top:-20px'>Address Book</h3>
			<div class="col-md-12">
				<div class="row" style="margin-top:5px;">
					<div class="col-md-3">
						<?php
							echo "<label style='color: black; font-weight: normal;'>Name: <span style='color: red;'>*</span></label>";
							echo form_input('txtName', '', "class='form-control' autofocus id='txtName' style='' maxlength=40 autocomplete='off'");
		              	?>
		          	</div>
					<div class="col-md-3">
						<?php
							echo "<label style='color: black; font-weight: normal;'>H.No.:</label>";
							echo form_input('txtHNo', '', "class='form-control' id='txtHNo' style='' maxlength=20 autocomplete='off'");
		              	?>
					</div>
					<div class="col-md-3">
						<?php
							echo "<label style='color: black; font-weight: normal;'>Locality:</label>";
							echo form_input('txtLocality', '', "class='form-control' id='txtLocality' style='' maxlength=40 autocomplete='off'");
		              	?>
		          	</div>
					<div class="col-md-3">
						<?php
							echo "<label style='color: black; font-weight: normal;'>Occupation:</label>";
							echo form_input('txtOccupation', '', "class='form-control' id='txtOccupation' style='' maxlength=30 autocomplete='off'");
		              	?>
					</div>
					<div class="col-md-3">
						<?php
							echo "<label style='color: black; font-weight: normal;'>Telephone:</label>";
							echo form_input('txtTelephone', '', "class='form-control' id='txtTelephone' style='' maxlength=25 autocomplete='off'");
		              	?>
		          	</div>
					<div class="col-md-3">
						<?php
							echo "<label style='color: black; font-weight: normal;'>Mobile: <span style='color: red;'>*</span></label>";
							echo form_input('txtMobile', '', "class='form-control' id='txtMobile' style='' maxlength=10 autocomplete='off'");
		              	?>
					</div>
					<div class="col-md-3">
						<?php
							echo "<label style='color: black; font-weight: normal;'>Remarks:</label>";
							echo form_input('txtRemarks', '', "class='form-control' id='txtRemarks' maxlength=250 style='' autocomplete='off'");
		              	?>
					</div>
					<div class="col-md-3">
						<?php
							echo "<label style='color: black; font-weight: normal;'>&nbsp;	</label>";
							echo "<input type='button' onclick='saveData();' value='Save' id='btnSave' class='btn btn-primary form-control'>";
			          	?>
			      	</div>
				</div>				
			</div>
		</div>
	</div>



	<div class="row" style="margin-top:20px;" >
		<!-- <div class="col-lg-2 col-sm-2 col-md-2 col-xs-0">
		</div> -->

		<div class="col-md-12">
			<div id="divTable" class="col-md-12" style="border:1px solid lightgray; padding: 10px;height:300px; overflow:auto;">
				<table class='table table-hover' id='tbl1'>
				 <thead>
					 <tr>
					 	<th  width="50" class="editRecord text-center">Edit</th>
					 	<th  width="50" class="text-center">Delete</th>
						<th style='display:none1;'>rowid</th>
					 	<th>Name</th>
					 	<th>H.No.</th>
					 	<th>Locality</th>
					 	<th>Occupation</th>
					 	<th>Telephone</th>
					 	<th>Mobile</th>
					 	<th>Remarks</th>
					 	<th style='display:none;'>ShowInDirectorySearch</th>
					 </tr>
				 </thead>
				 <tbody>
					 <?php 
						foreach ($records as $row) 
						{
						 	$rowId = $row['rowId'];
						 	echo "<tr>";						//onClick="editThis(this);
							echo '<td style="color: lightgray;cursor: pointer;cursor: hand;" class="editRecord text-center" onmouseover="this.style.color=\'green\';"  onmouseout="this.style.color=\'lightgray\';"><span class="glyphicon glyphicon-pencil"></span></td>
								   <td style="color: lightgray;cursor: pointer;cursor: hand;" class="text-center" onclick="delrowid('.$rowId.');" data-toggle="modal" data-target="#myModal" onmouseover="this.style.color=\'red\';"  onmouseout="this.style.color=\'lightgray\';"><span class="glyphicon glyphicon-remove"></span></td>';
						 	echo "<td style='width:0px;display:none1;'>".$row['rowId']."</td>";
						 	echo "<td>".$row['name']."</td>";
						 	echo "<td>".$row['hNo']."</td>";
						 	echo "<td>".$row['locality']."</td>";
						 	echo "<td>".$row['occupation']."</td>";
						 	echo "<td>".$row['telephone']."</td>";
						 	echo "<td>".$row['mobile']."</td>";
						 	echo "<td>".$row['remarks']."</td>";
						 	echo "<td style='display:none;'>".$row['showInDirectorySearch']."</td>";
							echo "</tr>";
						}
					 ?>
				 </tbody>
				</table>
			</div>
		</div>

		<!-- <div class="col-lg-2 col-sm-2 col-md-2 col-xs-0">
		</div> -->
	</div>
</div>



		  <div class="modal" id="myModal" role="dialog">
		    <div class="modal-dialog modal-sm">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title">KCE</h4>
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

	$('.editRecord').on('click', editThis);
	function editThis(jhanda)
	{
		rowIndex = $(this).parent().index();
		colIndex = $(this).index();
		name = $(this).closest('tr').children('td:eq(3)').text();
		hNo = $(this).closest('tr').children('td:eq(4)').text();
		locality = $(this).closest('tr').children('td:eq(5)').text();
		occupation = $(this).closest('tr').children('td:eq(6)').text();
		telephone = $(this).closest('tr').children('td:eq(7)').text();
		mobile = $(this).closest('tr').children('td:eq(8)').text();
		showInDirectorySearch = $(this).closest('tr').children('td:eq(10)').text();
		remarks = $(this).closest('tr').children('td:eq(9)').text();
		globalrowid = $(this).closest('tr').children('td:eq(2)').text();
		// alert(rowIndex + "   " + colIndex);
		$("#txtName").val(name);
		$("#txtHNo").val(hNo);
		$("#txtLocality").val(locality);
		$("#txtOccupation").val(occupation);
		$("#txtTelephone").val(telephone);
		$("#txtMobile").val(mobile);
		// $("#cboGroup").val(groupRowId);
		$("#txtRemarks").val(remarks);
		// $("#cboShowInDirectory").val(showInDirectorySearch);


		// /////Setting Groups
		// $('input:checked').each(function() {
		// 	$(this).removeAttr('checked');
		// });
		// $.ajax({
		// 	'url': base_url + '/Records_Controller/getDataForCheckox',
		// 	'type': 'POST', 
		// 	'data':{'rowid':globalrowid},
		// 	'dataType': 'json',
		// 	'success':function(data)
		// 	{
		// 		// alert( JSON.stringify(data) );
		// 		var arr = data['groups'].split(",");
		// 		$('.mycon').each(function() {
		// 			for(var i=0; i<arr.length-1; i++)
		// 			{
		// 				if(arr[i].trim() == $(this).val())
		// 				{
		// 					$(this).prop('checked','checked');
		// 				}
		// 			}	
		// 		});
		// 	}
		// });
		/////END - Groups
		$("#btnSave").val("Update");
	}


	$(document).ready( function () {
		    myDataTable = $('#tbl1').DataTable({
			    paging: false,
			    iDisplayLength: -1,
			    aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
			    dom: 'Bfrtip',
			    select: true,
			    ordering: false,
		        buttons: [
		            'copyHtml5',
		            {
		                extend: 'excel',
		                title: 'Address Book',
		                messageTop: "d",
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


</script>