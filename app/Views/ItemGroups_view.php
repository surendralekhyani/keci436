

<script type="text/javascript">
	var controller='ItemGroups_Controller';
	var base_url='<?php echo site_url();?>';

	vModuleName = "Item Groups";

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
	          cell.setAttribute("onclick", "delrowid(" + records[i].itemGroupRowId +")");
	          // data-toggle="modal" data-target="#myModal"
	          cell.setAttribute("data-toggle", "modal");
	          cell.setAttribute("data-target", "#myModal");

	          var cell = row.insertCell(2);
	          // cell.style.display="none";
	          cell.innerHTML = records[i].itemGroupRowId;
	          var cell = row.insertCell(3);
	          cell.innerHTML = records[i].itemGroupName;
	  	  }


	  	$('.editRecord').bind('click', editThis);

		myDataTable.destroy();
		$(document).ready( function () {
	    myDataTable=$('#tbl1').DataTable({
		    paging: false,
		    iDisplayLength: -1,
		    aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
		    select: true,
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
							alert("Record can not be deleted...<br> Dependent records exist...");
							// alertPopup('Record can not be deleted... Dependent records exist...', 8000, 'red');
						}
						else
						{
							setTable(data['records'])
							alertPopup('Record deleted...', 4000);
							blankControls();
							$("#txtItemGroupName").focus();
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
		itemGroupName = $("#txtItemGroupName").val().trim();
		if(itemGroupName == "")
		{
			alert("Item name can not be blank...");
			// alertPopup("Prefix type can not be blank...", 8000, 'red');
			$("#txtItemGroupName").focus();
			return;
		}


		if($("#btnSave").val() == "Save")
		{
			// alert("save");
			$.ajax({
					'url': base_url + '/' + controller + '/insert',
					'type': 'POST',
					'dataType': 'json',
					'data': {
								'itemGroupName': itemGroupName
							},
					'success': function(data)
					{
						if(data)
						{
							// alert(data);
							if(data == "Duplicate record...")
							{
								alert("Duplicate/Empty/Too Lengthy record... " + data['affectedRows']);
								// alertPopup("Duplicate record...", 4000, 'red');
								$("#txtItemGroupName").focus();
							}
							else
							{
								setTable(data['records']) ///loading records in tbl1
								 alertPopup('Record saved... ', 4000);
								blankControls();
								$("#txtItemGroupName").focus();
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
								, 'itemGroupName': itemGroupName
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
								$("#txtItemGroupName").focus();
							}
							else
							{
								setTable(data['records']) ///loading records in tbl1
								 alertPopup('Record updated... ', 4000);
								blankControls();
								$("#btnSave").val("Save");
								$("#txtItemGroupName").focus();
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
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1 class="text-center" style='margin-top:-20px'>Item Groups</h1>
			<div class="row" style="margin-top:25px;">
				<div class="col-md-8">
					<?php
						echo "<label style='color: black; font-weight: normal;'>Item Group Name:</label>";
						echo form_input('txtItemGroupName', '', "class='form-control' autofocus id='txtItemGroupName'  style='' maxlength=20 autocomplete='off'");
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
			<div id="divTable" class="divTable col-md-12" style="border:1px solid lightgray; padding: 10px;height:500px; overflow:auto;">
				<table class='table table-hover' id='tbl1'>
				 <thead>
					 <tr>
					 	<th  width="50" class="editRecord text-center">Edit</th>
					 	<th  width="50" class="text-center">Delete</th>
						<th style='width:0px;display:none1;'>rowid</th>
					 	<th>Item Group</th>
					 </tr>
				 </thead>
				 <tbody>
					 <?php 
						foreach ($records as $row) 
						{
						 	$rowId = $row['itemGroupRowId'];
						 	echo "<tr>";						//onClick="editThis(this);
							echo '<td style="color: lightgray;cursor: pointer;cursor: hand;" class="editRecord text-center" onmouseover="this.style.color=\'green\';"  onmouseout="this.style.color=\'lightgray\';"><span class="glyphicon glyphicon-pencil"></span></td>
								   <td style="color: lightgray;cursor: pointer;cursor: hand;" class="text-center" onclick="delrowid('.$rowId.');" data-toggle="modal" data-target="#myModal" onmouseover="this.style.color=\'red\';"  onmouseout="this.style.color=\'lightgray\';"><span class="glyphicon glyphicon-remove"></span></td>';
						 	echo "<td style='width:0px;display:none1;'>".$row['itemGroupRowId']."</td>";
						 	echo "<td>".$row['itemGroupName']."</td>";
							echo "</tr>";
						}
					 ?>
				 </tbody>
				</table>
			</div>
		</div>
	</div>
</div>



		  <div class="modal" id="myModal" role="dialog">
		    <div class="modal-dialog modal-sm">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title">WSS</h4>
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
		itemGroupName = $(this).closest('tr').children('td:eq(3)').text();
		globalrowid = $(this).closest('tr').children('td:eq(2)').text();

		$("#txtItemGroupName").val(itemGroupName);
		$("#txtItemGroupName").focus();
		$("#btnSave").val("Update");
	}


		$(document).ready( function () {
		    myDataTable = $('#tbl1').DataTable({
			    paging: false,
			    iDisplayLength: -1,
			    aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
			    select: true,
			});
		} );

</script>