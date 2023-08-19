<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/datatable/buttons.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/jszip.min.js"></script>
<style type="text/css">
	.ui-autocomplete {
		max-height: 200px;
		overflow-y: auto;
		/* prevent horizontal scrollbar */
		overflow-x: hidden;
		/* add padding to account for vertical scrollbar */
		z-index: 1000 !important;
	}
</style>
<script type="text/javascript">
	var controller = 'Family_Controller';
	var base_url = '<?php echo site_url(); ?>';

	vModuleName = "Family";

	function deleteRecord() {
		// alert(rowId);
		$.ajax({
			'url': base_url + '/' + controller + '/delete',
			'type': 'POST',
			'dataType': 'json',
			'data': {
				'rowId': globalRowIdForDeletion
			},
			'success': function(data) {
				if (data) {
					// alert(data);
					if (data['dependent'] == "yes") {
						alert("Record can not be deleted...\n\r Child record exists...");
					} else {
						location.reload();
					}
				}
			},
			'error': function(jqXHR, exception) {
				$("#paraAjaxErrorMsg").html(jqXHR.responseText);
				$("#modalAjaxErrorMsg").modal('toggle');
			}
		});
	}

	function saveData() {
		name = $("#txtName").val().trim();
		if (name == "") {
			alert("Name can not be blank...");
			$("#txtName").focus();
			return;
		}
		parentRowId = $("#lblFamilyId").text();
		if (parentRowId == "" || parentRowId == "-1" || parentRowId == "d") {
			alertPopup("Parent is not valid...", 8000, 'red');
			$("#txtParentName").focus();
			return;
		}
		contactNo = $("#txtContactNo").val().trim();
		address = $("#txtAddress").val().trim();
		remarks = $("#txtRemarks").val().trim();

		if ($("#btnSave").val() == "Save") {
			$.ajax({
				'url': base_url + '/' + controller + '/insert',
				'type': 'POST',
				'dataType': 'json',
				'data': {
					'name': name,
					'parentRowId': parentRowId,
					'contactNo': contactNo,
					'address': address,
					'remarks': remarks
				},
				'success': function(data) {
					if (data) {
						if (data == "Duplicate record...") {
							alert("Duplicate record...");
							$("#txtName").focus();
						} else {
							alertPopup('Record saved...  ', 4000);
							location.reload();
						}
					}

				},
				error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});
		} else if ($("#btnSave").val() == "Update") {
			// alert("update");
			$.ajax({
				'url': base_url + '/' + controller + '/update',
				'type': 'POST',
				'dataType': 'json',
				'data': {
					'globalrowid': globalrowid,
					'name': name,
					'parentRowId': parentRowId,
					'contactNo': contactNo,
					'address': address,
					'remarks': remarks
				},
				'success': function(data) {
					if (data) {
						if (data == "Duplicate record...") {
							alert("Duplicate record...");
							$("#txtName").focus();
						} else {
							alertPopup('Record updated...  ', 4000);
							location.reload();
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
<div class="container-fluid" style="width:90%;">
	<div class="row">
		<div class="col-md-12">
			<h1 class="text-center" style='margin-top:-20px'>Family</h1>
			<div class="row" style="margin-top:25px;">
				<div class="col-md-4">
					<?php
					echo "<label style='color: black; font-weight: normal;'>Name:</label>";
					echo form_input('txtName', '', "class='form-control' autofocus id='txtName' style='' maxlength=150 autocomplete='off'");
					?>
				</div>
				<div class="col-md-4">
					<label style='color: lightgrey; font-weight: normal;' id='lblFamilyId'>d</label>
					<?php
					echo "<label style='color: black; font-weight: normal;'>Parent:</label>";
					echo form_input('txtParentName', '', "class='form-control' id='txtParentName' autocomplete='off'");
					?>
				</div>
				<div class="col-md-4">
					<?php
					echo "<label style='color: black; font-weight: normal;'>Contact No.:</label>";
					echo form_input('txtContactNo', '', "class='form-control' id='txtContactNo' autocomplete='off' maxlength=29");
					?>
				</div>
				<div class="col-md-4">
					<?php
					echo "<label style='color: black; font-weight: normal;'>Address:</label>";
					echo form_input('txtAddress', '', "class='form-control' id='txtAddress' autocomplete='off' maxlength=150");
					?>
				</div>
				<div class="col-md-4">
					<?php
					echo "<label style='color: black; font-weight: normal;'>Remarks:</label>";
					echo form_input('txtRemarks', '', "class='form-control' id='txtRemarks' autocomplete='off' maxlength=150");
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


	<div class="row" style="margin-top:30px; margin-bottom:30px;">
		<div class="col-md-12">
			<div id="divTable" class="divTable col-md-12" style="border:1px solid lightgray; padding: 10px;height:350px; overflow:auto;">
				<table class='table table-hover' id='tbl1'>
					<thead>
						<tr>
							<th width="50" class="editRecord text-center">Edit</th>
							<th width="50" class="text-center">Delete</th>
							<th style='display:none;'>rowid</th>
							<th>Name</th>
							<th style='display:none;'>Pid</th>
							<th>Parent</th>
							<th style='display:none;'>SibOdr</th>
							<th>Contact No</th>
							<th>Address</th>
							<th>Remarks</th>
							<th>Change Child Order</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($records as $row) {
							$rowId = $row['familyRowId'];
							echo '<td style="color: lightgray;cursor: pointer;cursor: hand;" class="editRecord text-center" onmouseover="this.style.color=\'green\';"  onmouseout="this.style.color=\'lightgray\';"><span class="glyphicon glyphicon-pencil"></span></td>
								   <td style="color: lightgray;cursor: pointer;cursor: hand;" class="text-center" onclick="delrowid(' . $rowId . ');" data-toggle="modal" data-target="#myModal" onmouseover="this.style.color=\'red\';"  onmouseout="this.style.color=\'lightgray\';"><span class="glyphicon glyphicon-remove"></span></td>';
							echo "<td style='display:none;'>" . $row['familyRowId'] . "</td>";
							echo "<td>" . $row['name'] . "</td>";
							echo "<td style='display:none;'>" . $row['parentRowId'] . "</td>";
							echo "<td style='color: gray;'>" . $row['parentName'] . "</td>";
							echo "<td style='display:none;'>" . $row['siblingOrder'] . "</td>";
							echo "<td>" . $row['contactNo'] . "</td>";
							echo "<td>" . $row['address'] . "</td>";
							echo "<td>" . $row['remarks'] . "</td>";
							echo "<td><button class='btnChildOrder''>Change Child Order</button></td>";
							echo "</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="row" style="margin-top:30px; margin-bottom:30px;">
		<div class="col-md-4" style="margin-bottom: 10px;">
			<?php
			echo "<input type='button' onclick='showEditTable();' value='Show Table to Bulk Edit' id='btnBulkEdit' class='btn btn-danger form-control'>";
			?>
		</div>
		<div class="col-md-12">
			<div id="divTable" class="divTable col-md-12" style="border:1px solid lightgray; padding: 10px;height:350px; overflow:auto;">
				<table class='table table-bordered' id='tblEdit'>
					<thead>
						<tr>
							<th style='display:none1;'>rowid</th>
							<th>Name</th>
							<th style='display:none1;'>Pid</th>
							<th>Parent</th>
							<th>Contact No</th>
							<th>Address</th>
							<th>Remarks</th>
							<th>Flag</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-md-4" style="margin-top: 20px;">
			<?php
			echo "<input type='button' onclick='saveBulkEdit();' value='Save Bulk Edit' id='btnBulkEditSave' class='btn btn-primary form-control'>";
			?>
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


<!-- Model -->
<div class="modal" id="modalChild" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" id="h4SaleDetail">Children Order</h4>
			</div>
			<div class="modal-body" style="overflow: auto; height: 300px;">
				<table id='tblChildOrder' class="table table-stripped">
					<th style='display:none1;'>familyRowid</th>
					<th>Name</th>
					<th>parentRowId</th>
					<th>Sib. Order</th>
				</table>
			</div>
			<div class="modal-footer">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
					<button type="button" onclick='saveChildOrder();' class="btn btn-block btn-primary" data-dismiss="modal">Save</button>
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
	var globalrowid;
	var globalRowIdForDeletion;

	function delrowid(rowid) {
		globalRowIdForDeletion = rowid;
	}

	$('.editRecord').bind('click', editThis);

	function editThis(jhanda) {
		rowIndex = $(this).parent().index();
		colIndex = $(this).index();
		name = $(this).closest('tr').children('td:eq(3)').text();
		globalrowid = $(this).closest('tr').children('td:eq(2)').text();
		parentRowId = $(this).closest('tr').children('td:eq(4)').text();
		parentName = $(this).closest('tr').children('td:eq(5)').text();
		contactNo = $(this).closest('tr').children('td:eq(7)').text();
		address = $(this).closest('tr').children('td:eq(8)').text();
		remarks = $(this).closest('tr').children('td:eq(9)').text();

		$("#txtName").val(name);
		$("#txtParentName").val(parentName);
		$("#lblFamilyId").text(parentRowId);
		$("#txtContactNo").val(contactNo);
		$("#txtAddress").val(address);
		$("#txtRemarks").val(remarks);
		$("#txtName").focus();
		$("#btnSave").val("Update");
	}

	$('.btnChildOrder').bind('click', getChildModel);

	function getChildModel() {
		rowId = $(this).closest('tr').children('td:eq(2)').text();
		$("#h4SaleDetail").text("Children Order for " + $(this).closest('tr').children('td:eq(3)').text());
		// alert( x );
		$.ajax({
			'url': base_url + '/' + controller + '/getChildren',
			'type': 'POST',
			'dataType': 'json',
			'data': {
				'rowId': rowId
			},
			'success': function(data) {
				if (data) {
					setTableChildOrder(data['records'])
				}
			},
			'error': function(jqXHR, exception) {
				$("#paraAjaxErrorMsg").html(jqXHR.responseText);
				$("#modalAjaxErrorMsg").modal('toggle');
			}
		});
		$('#modalChild').modal('toggle');
	}

	function setTableChildOrder(records) {
		$("#tblChildOrder").find("tr:gt(0)").remove(); //// empty first
		var table = document.getElementById("tblChildOrder");

		// alert(JSON.stringify(data));
		for (i = 0; i < records.length; i++) {
			newRowIndex = table.rows.length;
			row = table.insertRow(newRowIndex);
			var cell = row.insertCell(0);
			cell.innerHTML = records[i].familyRowId;
			var cell = row.insertCell(1);
			cell.innerHTML = records[i].name;
			var cell = row.insertCell(2);
			cell.innerHTML = records[i].parentRowId;
			var cell = row.insertCell(3);
			cell.innerHTML = records[i].siblingOrder;
			cell.contentEditable = "true";
			cell.style.color = "red";
		}
	}

	var tblRowsCount = 0;

	function storeTblValues() {
		var TableData = new Array();
		var i = 0;
		$('#tblChildOrder tr:gt(0)').each(function(row, tr) {
			TableData[i] = {
				"familyRowId": $(tr).find('td:eq(0)').text(),
				"name": $(tr).find('td:eq(1)').text(),
				"siblingOrder": $(tr).find('td:eq(3)').text()
			}
			i++;
		});
		// TableData.shift();  // NOT first row will be heading - so remove COZ its dataTable
		tblRowsCount = i;
		// alert(tblRowsCount);
		return TableData;
	}

	function saveChildOrder() {
		var TableData;
		TableData = storeTblValues();
		TableData = JSON.stringify(TableData);
		// alert(JSON.stringify(TableData));
		// return;
		$.ajax({
			'url': base_url + '/' + controller + '/saveChildOrder',
			'type': 'POST',
			// 'dataType': 'json',
			'data': {
				'TableData': TableData
			},
			'success': function(data) {
				alertPopup('Changes saved...', 3000);
				location.reload();
			},
			'error': function(jqXHR, exception) {
				$("#paraAjaxErrorMsg").html(jqXHR.responseText);
				$("#modalAjaxErrorMsg").modal('toggle');
			}
		});
	}


	$(document).ready(function() {
		myDataTable = $('#tbl1').DataTable({
			paging: false,
			iDisplayLength: -1,
			aLengthMenu: [
				[5, 10, 25, -1],
				[5, 10, 25, "All"]
			],
			dom: 'Bfrtip',
			select: true,
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5'
			]
		});
	});



	$(document).ready(function() {
		select = false;
		var jSonArray = '<?php echo json_encode($familyList); ?>';
		var jSonArray = jSonArray.replace(/(\r\n|\n|\r)/gm, ", "); ///Multilinse of Address field with comma replce
		var availableTags = $.map(JSON.parse(jSonArray), function(obj) {
			return {
				label: obj.name,
				familyRowId: obj.familyRowId
			}
		});
		$(function() {
			$("#txtParentName").autocomplete({
				source: function(request, response) {
					var aryResponse = [];
					var arySplitRequest = request.term.split(" ");
					// alert(JSON.stringify(arySplitRequest));
					for (i = 0; i < availableTags.length; i++) {
						var intCount = 0;
						for (j = 0; j < arySplitRequest.length; j++) {
							var cleanString = arySplitRequest[j].replace(/[|&;$%@"<>()+,]/g, "");
							regexp = new RegExp(cleanString, 'i');
							// regexp = new RegExp(arySplitRequest[j], 'i');
							var test = JSON.stringify(availableTags[i].label.toLowerCase()).match(regexp);
							if (test) {
								intCount++;
							} else if (!test) {
								intCount = arySplitRequest.length + 1;
							}
							if (intCount == arySplitRequest.length) {
								aryResponse.push(availableTags[i]);
							}
						};
					}
					response(aryResponse);
				},
				autoFocus: true,
				selectFirst: true,
				minLength: 0,
				select: function(event, ui) {
					select = true;
					var selectedObj = ui.item;
					$("#lblFamilyId").text(ui.item.familyRowId);
				}

			}).blur(function() {
				if (!select) {
					$("#lblFamilyId").text('-1');
				}
				if ($("#lblFamilyId").text() == '-1') {} else {}
			}).focus(function() {
				$(this).autocomplete("search");
			});
		}); ///AutoComplete Khatam

		///// Sabka baap if 0 records

		// alert( availableTags.length );
		if (availableTags.length == 0) {
			$("#txtParentName").val("Super Parent");
			$("#lblFamilyId").text("-2");
			$("#txtParentName").attr("disabled", true);
		}
	});
</script>

<!-- Edit Table  -->
<script type="text/javascript">
	function showEditTable() {
		$.ajax({
			'url': base_url + '/' + controller + '/showDataForBulkEdit',
			'type': 'POST',
			'dataType': 'json',
			'data': {
				'searchValue': 'searchValue',
				'dtTo': 'dtTo'
			},
			'success': function(data) {
				if (data) {
					// alert(JSON.stringify(data));
					setTable(data['records'])
					alertPopup('Records loaded...', 4000);
				}
			},
			'error': function(jqXHR, exception) {
				$("#paraAjaxErrorMsg").html(jqXHR.responseText);
				$("#modalAjaxErrorMsg").modal('toggle');
			}
		});
	}


	function setTable(records) {
		// alert(JSON.stringify(records));
		$("#tblEdit").find("tr:gt(0)").remove();
		// $("#tblEdit").empty();
		var table = document.getElementById("tblEdit");
		for (i = 0; i < records.length; i++) {
			newRowIndex = table.rows.length;
			row = table.insertRow(newRowIndex);

			var cell = row.insertCell(0);
			cell.innerHTML = records[i].familyRowId;
			// cell.style.display = "none";
			var cell = row.insertCell(1);
			cell.innerHTML = records[i].name;
			cell.setAttribute("contentEditable", true);
			var cell = row.insertCell(2);
			cell.innerHTML = records[i].parentRowId;
			// cell.style.display = "none";
			var cell = row.insertCell(3);
			cell.innerHTML = records[i].parentName;
			var cell = row.insertCell(4);
			cell.innerHTML = records[i].contactNo;
			cell.setAttribute("contentEditable", true);
			var cell = row.insertCell(5);
			cell.innerHTML = records[i].address;
			cell.setAttribute("contentEditable", true);
			var cell = row.insertCell(6);
			cell.innerHTML = records[i].remarks;
			cell.setAttribute("contentEditable", true);
			var cell = row.insertCell(7);
			cell.innerHTML = "0";
		}

		/// setting flag
		$("#tblEdit tr td").on("keyup", function(e) {
			if (e.keyCode != 9) {
				var rowIndex = $(this).parent().index();
				$("#tblEdit").find("tr:eq(" + rowIndex + ")").find("td:eq(" + 7 + ")").text(1);
				rowCount();
			}
		});
	}

	function rowCount() {
		var c = 0;
		$("#tblEdit tr").each(function(i, v) {
			if ($(this).find("td:eq(" + 7 + ")").text() == '1') {
				c++;
			}
		});
		// alert(c);
		if (c >= 200) {
			alert("Enough changes(200) done for this time...");
		}
	}

	var tblRowsCount = 0;

	function storeTblValues() {
		var TableData = new Array();
		var i = 0;
		$('#tblEdit tr').each(function(row, tr) {
			if ($(this).find("td:eq(" + 7 + ")").text() == '1') {
				TableData[i] = {
					"familyRowId": $(tr).find('td:eq(0)').text(),
					"name": $(tr).find('td:eq(1)').text(),
					"contactNo": $(tr).find('td:eq(4)').text(),
					"address": $(tr).find('td:eq(5)').text(),
					"remarks": $(tr).find('td:eq(6)').text(),
				}
				i++;
			}
		});
		tblRowsCount = i;
		return TableData;
	}

	function saveBulkEdit() {
		var TableData;
		TableData = storeTblValues();
		TableData = JSON.stringify(TableData);
		// alert(JSON.stringify(TableData));
		// return;

		$.ajax({
			'url': base_url + '/' + controller + '/saveBulkEdit',
			'type': 'POST',
			// 'dataType': 'json',
			'data': {
				'TableData': TableData,
			},
			'success': function(data) {
				alertPopup('Changes saved...', 3000);
				location.reload();
			},
			'error': function(jqXHR, exception) {
				$("#paraAjaxErrorMsg").html(jqXHR.responseText);
				$("#modalAjaxErrorMsg").modal('toggle');
			}
		});

	}
</script>