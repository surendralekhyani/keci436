<?=$this->extend("Layout")?>

<?=$this->section("content")?>
	<?= $this->include('menu') ?>
	<script type="text/javascript">
		var controller='EditItems_Controller';
		var base_url='<?php echo site_url();?>';

		vModuleName = "Edit Items";


		function setTable(records)
		{
			// alert(JSON.stringify(records));
			
			// setHeadings();
			// $("#tblItems").find("tr:gt(0)").remove();
			$("#tblItems").empty();
			var table = document.getElementById("tblItems");
			// alert(noOfDays);
			for(i=0; i<records.length; i++)
			{
				newRowIndex = table.rows.length;
				row = table.insertRow(newRowIndex);


				var cell = row.insertCell(0);
				cell.innerHTML = i+1;
				cell.style.backgroundColor="#F0F0F0";
				var cell = row.insertCell(1);
				cell.innerHTML = records[i].itemRowId;
				cell.style.backgroundColor="#F0F0F0";
				// cell.style.display="none";
				var cell = row.insertCell(2);
				cell.innerHTML = records[i].itemName;
				cell.setAttribute("contentEditable", true);
				// cell.style.backgroundColor="#F0F0F0";
				var cell = row.insertCell(3);
				cell.innerHTML = records[i].sellingPrice;
				cell.setAttribute("contentEditable", true);
				cell.style.textAlign = 'right';
				var cell = row.insertCell(4);
				cell.innerHTML = "0";
				cell.style.display="none";
				var cell = row.insertCell(5);
				cell.innerHTML = records[i].pp;
				cell.setAttribute("contentEditable", true);
				cell.style.textAlign = 'right';
				cell.className = "clsPp";

				var cell = row.insertCell(6);
				cell.innerHTML = "<input type='checkbox' class='chk' style='width:20px;height:20px;' name='chkDelete'/>";
				cell.style.textAlign = 'center';

				dates = "";
				var cell = row.insertCell(7);
				try{
					if( records[i].pvDt.length > 0 )
					{
						dates += "PV: " + dateFormat(new Date(records[i].pvDt)) + ", ";
					}
					else{
						dates += "";
					}
					if( records[i].svDt.length > 0 )
					{
						dates += "SV: " + dateFormat(new Date(records[i].svDt));
					}
					else{
						dates += "";
					}
					cell.innerHTML = dates;
				}
				catch(e)
				{}

				var cell = row.insertCell(8);
				cell.innerHTML = records[i].gstRate;
				cell.setAttribute("contentEditable", true);
				cell.style.textAlign = 'right';
				var cell = row.insertCell(9);
				cell.innerHTML = records[i].hsn;
				cell.setAttribute("contentEditable", true);
				cell.style.textAlign = 'right';
				var cell = row.insertCell(10);
				cell.innerHTML = records[i].openingBalance;
				cell.setAttribute("contentEditable", true);
				cell.style.textAlign = 'right';
				var cell = row.insertCell(11);
				cell.style.textAlign = 'right';
				var cell = row.insertCell(12);
				cell.innerHTML = records[i].itemGroupName;
				
			}
			$(".clsBtnDelete").on('click', deleteKaro);

				myDataTable.destroy();
				$(document).ready( function () {
				myDataTable=$('#tblItems').DataTable({
					paging: false,
					iDisplayLength: -1,
					aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],

				});
				} );


			///////Following function to add select TD text on FOCUS
					$("#tblItems tr td").on("focus", function(){
						// alert($(this).text());
						var range, selection;
						if (document.body.createTextRange) {
							range = document.body.createTextRange();
							range.moveToElementText(this);
							range.select();
						} else if (window.getSelection) {
							selection = window.getSelection();
							range = document.createRange();
							range.selectNodeContents(this);
							selection.removeAllRanges();
							selection.addRange(range);
						}
					}); 


			$("#tblItems tr td").on("keyup", function(e){
				// if ( (e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 97 && e.keyCode <= 122) ) 
				if(e.keyCode != 9)
				{
					var rowIndex = $(this).parent().index();
					$("#tblItems").find("tr:eq("+ rowIndex + ")").find("td:eq("+ 4 +")").text(1);
					rowCount();
				}
			});
		}


		var checkedRows=0;
		function storeTblValuesChecked()
		{
			var TableData = new Array();
			var i=0, j=0;
			$('#tblItems tr').each(function(row, tr)
			{
				if(j>=0)
				{
					if($(tr).find('td:eq(6)').find('input[type=checkbox]').is(':checked'))
					{
						TableData[i]=
						{
							"itemRowId" : $(tr).find('td:eq(1)').text()
						}   
						i++; 
					}
				}	 
				j++;   	
			}); 
			checkedRows = i;
			// TableData.shift();  // first row will be heading - so remove
			return TableData;
		}

		function deleteKaro()
		{
			var TableData;
			TableData = storeTblValuesChecked();
			TableData = JSON.stringify(TableData);
			// alert(JSON.stringify(TableData));
			// return;


			$.ajax({
				'url': base_url + '/' + controller + '/delete',
				'type': 'POST',
				'dataType': 'json',
				'data': {
							'TableData': TableData
						},
				'success': function(data)
				{
					if(data)
					{
					// alert(JSON.stringify(data));
						setTable(data['records']) 
						// alertPopup('Done...', 4000);
					}
				},
						error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});	
		}

		function loadData()
		{	
			var searchValue = $("#txtSearch").val().trim();

			$.ajax({
					'url': base_url + '/' + controller + '/showData',
					'type': 'POST',
					'dataType': 'json',
					'data': {
								'searchValue': searchValue
								, 'dtTo': 'dtTo'
							},
					'success': function(data)
					{
						if(data)
						{
							// alert(JSON.stringify(data));
								setTable(data['records']);
								setPpHidden();
								alertPopup('Records loaded... ' + data.timeTook, 4000);
						}
					},
						error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});
			
		}

		function loadDataWithDt()
		{	
			var searchValue = $("#txtSearch").val().trim();

			$.ajax({
					'url': base_url + '/' + controller + '/showDataWithDt',
					'type': 'POST',
					'dataType': 'json',
					'data': {
								'searchValue': searchValue
								, 'dtTo': 'dtTo'
							},
					'success': function(data)
					{
						if(data)
						{
							// console.log(JSON.stringify(data));
								setTable(data['records']);
								setPpHidden();
								alertPopup('Records loaded... ' + data.timeTook, 4000);
						}
					},
						error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});
			
		}
		var tblRowsCount=0;
		function storeTblValues()
		{
			var TableData = new Array();
			var i=0;
			$('#tblItems tr').each(function(row, tr)
			{
				if($(this).find("td:eq("+ 4 +")").text() == '1' )
				{
					TableData[i]=
					{
						"itemRowId" : $(tr).find('td:eq(1)').text()
						, "itemName" :$(tr).find('td:eq(2)').text()
						, "sellingPrice" :$(tr).find('td:eq(3)').text()
						, "pp" :$(tr).find('td:eq(5)').text()
						, "gstRate" :$(tr).find('td:eq(8)').text()
						, "hsn" :$(tr).find('td:eq(9)').text()
						, "openingBalance" :$(tr).find('td:eq(10)').text()
					}   
					i++; 
				}
			}); 
			// TableData.shift();  // NOT first row will be heading - so remove COZ its dataTable
			tblRowsCount = i;
			// alert(tblRowsCount);
			return TableData;
		}

		function saveData()
		{	
			var TableData;
			TableData = storeTblValues();
			TableData = JSON.stringify(TableData);
			// alert(JSON.stringify(TableData));
			// return;

			// alert(tblRowsCount);
			// return;
			/// check boxes for deletion
			var TableDataDelete;
			TableDataDelete = storeTblValuesChecked();
			TableDataDelete = JSON.stringify(TableDataDelete);
			// alert(JSON.stringify(TableDataDelete));
			// return;
			$.ajax({
					'url': base_url + '/' + controller + '/saveData',
					'type': 'POST',
					// 'dataType': 'json',
					'data': {
								'TableData': TableData
								, 'TableDataDelete': TableDataDelete
							},
					'success': function(data)
					{
						alertPopup('Changes saved...', 3000);
						location.reload();
					},
						error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});
			
		}
		
		function setPpHidden()
		{
			if( $(".clsPp").css("display") != 'none' ) 
				$(".clsPp").css("display", 'none');
			else
				$(".clsPp").css("display", 'block');
		}

		function setZeroBalRowsHide()
		{
			$('#tblItems tr').each(function(row, tr)
			{
				// console.log($(tr).find('td:eq(11)').text());
				if($(this).find("td:eq("+ 11 +")").text() == '0' )
				{
					$(this).hide();
					// console.log($(tr).find('td:eq(11)').text());
				}
			}); 
		}

	</script>
	<div class="container-fluid">
			<div class="col-md-12">
				<h3 class="text-center" style='margin-top:-20px'>Edit Items</h3>
				<h6 class="text-center" style='color:red;'>Edit Max. 200 records at a time</h6>
				<form name='frm1' id='frm1' method='post' enctype='multipart/form-data' action="">
					<div class="row" style="margin-top:-30px;">
						<div class="col-md-2">
							<?php
								echo "<label style='color: black; font-weight: normal;'>&nbsp;</label>";
								echo "<input type='text' id='txtSearch' class='form-control' maxlength=20 placeholder='Search' value='' autocomplete='off'>";
							?>
						</div>
						<div class="col-md-2">
							<?php
								echo "<label style='color: black; font-weight: normal;'>&nbsp;	</label>";
								echo "<input type='button' onclick='loadData();' value='Show Data' id='btnShow' class='btn form-control' style='background-color: lightgray;'>";
							?>
						</div>
						<div class="col-md-2">
						<?php
								echo "<label style='color: black; font-weight: normal;'>&nbsp;	</label>";
								echo "<input type='button' onclick='loadDataWithDt();' value='Show Data With Dt.' id='btnShowWIthDt' class='btn form-control' style='background-color: lightgray;'>";
							?>
						</div>
						<div class="col-md-3">
						</div>
						<div class="col-md-3">
							<?php
								echo "<label style='color: black; font-weight: normal;'>&nbsp;	</label>";
								echo "<input type='button' onclick='saveData();' value='Save Changes' id='btnSave' class='btn btn-primary form-control'>";
							?>
						</div>
					</div>

					<div class="row" style="margin-top:20px;" >
						<style>
							table, th, td{border:1px solid gray; padding: 7px;}
						</style>
						<div id="divTable" class="divTable col-md-12" style="border:0 solid lightgray; padding: 10px;height:450px; overflow:auto;">
							<table class='table table-bordered' id='tblItems'>
								<thead>
								<tr style="background-color: #F0F0F0;">
									<th style='display:none1;'>S.N.</th>
									<th style='display:none1;'>Id</th>
									<th>Item Name</th>
									<th style='text-align: right;'>S.Price</th>
									<th style='display:none;'>Flag</th>
									<th style='text-align: right;' class='clsPp'>P.Price</th>
									<th style='text-align: center;'>Delete</th>
									<th style='text-align: center;'>Last Used Dt.</th>
									<th style='text-align: right;'>GST Rate</th>
									<th style='text-align: right;'>HSN</th>
									<th style='text-align: right;'>Op. Bal.</th>
									<th style='text-align: right;'>Cl. Bal.</th>
									<th>Group</th>
								</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>

					<div class="col-md-3">
						<?php
							echo "<label style='color: black; font-weight: normal;'>&nbsp;	</label>";
							echo "<input type='button' onclick='setZeroBalRowsHide();' value='Zero Bal Rows Hide' id='btnSetZeroBalRowsHide' class='btn btn-danger form-control'>";
						?>
					</div>
					<div class="col-md-3">
					<?php
						echo "<label style='color: black; font-weight: normal;'>&nbsp;	</label>";
						echo "<input type='button' onclick='getClosingBalanceInBackground();' value='Get Closing Bal. (In Background)' id='btnGetClosingBalance' class='btn btn-danger form-control'>";
					?>
					</div>
					<div class="col-md-3">
						<?php
							echo "<label style='color: black; font-weight: normal;'>&nbsp;	</label>";
							echo "<input type='button' onclick='getClosingBalance();' value='Get Closing Bal.' id='btnGetClosingBalance' class='btn btn-danger form-control'>";
						?>
					</div>
					<div class="col-md-3">
						<?php
							echo "<label style='color: black; font-weight: normal;'>&nbsp;	</label>";
							echo "<input type='button' onclick='setPpHidden();' value='PP' id='btnSetPpHidden' class='btn btn-primary form-control'>";
						?>
					</div>
				</form>
			</div>
		</div>
	</div>

	<hr />
	<div class="container-fluid" style="background-color: lightgrey;margin-bottom: 20px;">
			<div class="col-md-12">
				<h3 class="text-center" style='margin-top:20px'>Deleted Items</h3>
				<form name='frm' id='frm' method='post' enctype='multipart/form-data' action="">
					<div class="row" style="margin-top:-30px;">
						<div class="col-md-3">
							<?php
								echo "<label style='color: black; font-weight: normal;'>&nbsp;	</label>";
								echo "<input type='button' onclick='loadDataDeleted();' value='Show Deleted Items' id='btnShowDeleted' class='btn btn-block btn-danger'>";
							?>
						</div>
						<div class="col-md-3">
						</div>
						<div class="col-md-3">
						</div>
						<div class="col-md-3">
							<?php
								echo "<label style='color: black; font-weight: normal;'>&nbsp;	</label>";
								echo "<input type='button' onclick='undeleteKaro();' value='Undelete Checked' id='btnUndelete' class='btn btn-primary form-control'>";
							?>
						</div>
					</div>

					<div class="row" style="margin-top:20px;" >
						<style>
							table, th, td{border:1px solid gray; padding: 7px;}
						</style>
						<div id="divTableDeleted" class="divTable col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border:0 solid lightgray; padding: 10px;height:450px; overflow:auto;">
							<table class='table table-bordered' id='tblItemsDeleted'>
								<thead>
								<tr style="background-color: #F0F0F0;">
									<th style='display:none1;'>S.N.</th>
									<th style='display:none1;'>itemRowId</th>
									<th>Item Name</th>
									<th>S.Price</th>
									<th style='display:none;'>Flag</th>
									<th>P.Price</th>
									<th>Delete</th>
								</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		function loadDataDeleted()
		{	
			$.ajax({
					'url': base_url + '/' + controller + '/showDataDeleted',
					'type': 'POST',
					'dataType': 'json',
					'data': {
								'productCategoryRowId': 'productCategoryRowId'
								, 'dtTo': 'dtTo'
							},
					'success': function(data)
					{
						if(data)
						{
							// alert(JSON.stringify(data));
								setTableDeleted(data['records']) 
								alertPopup('Records loaded...', 4000);
						}
					},
						'error': function(jqXHR, exception)
						{
							document.write(jqXHR.responseText);
						}
			});	
		}


		function setTableDeleted(records)
		{
			$("#tblItemsDeleted").empty();
			var table = document.getElementById("tblItemsDeleted");
			for(i=0; i<records.length; i++)
			{
				newRowIndex = table.rows.length;
				row = table.insertRow(newRowIndex);


				var cell = row.insertCell(0);
				cell.innerHTML = i+1;
				cell.style.backgroundColor="#F0F0F0";
				var cell = row.insertCell(1);
				cell.innerHTML = records[i].itemRowId;
				cell.style.backgroundColor="#F0F0F0";
				// cell.style.display="none";
				var cell = row.insertCell(2);
				cell.innerHTML = records[i].itemName;
				// cell.setAttribute("contentEditable", true);
				// cell.style.backgroundColor="#F0F0F0";
				var cell = row.insertCell(3);
				cell.innerHTML = records[i].sellingPrice;
				// cell.setAttribute("contentEditable", true);
				var cell = row.insertCell(4);
				cell.innerHTML = "0";
				cell.style.display="none";
				var cell = row.insertCell(5);
				cell.innerHTML = records[i].pp;
				// cell.setAttribute("contentEditable", true);

				var cell = row.insertCell(6);
				cell.innerHTML = "<input type='checkbox' class='chk' style='width:20px;height:20px;' name='chkDelete'/>";
				
			}
			$(".chkUnDelete").on('click', undeleteKaro);

				myDataTableDeleted.destroy();
				$(document).ready( function () {
				myDataTableDeleted=$('#tblItemsDeleted').DataTable({
					paging: false,
					iDisplayLength: -1,
					aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
				});
				} );
		}

		var checkedRowsDeleted=0;
		function storeTblValuesCheckedDeleted()
		{
			var TableData = new Array();
			var i=0, j=0;
			$('#tblItemsDeleted tr').each(function(row, tr)
			{
				if(j>=0)
				{
					if($(tr).find('td:eq(6)').find('input[type=checkbox]').is(':checked'))
					{
						TableData[i]=
						{
							"itemRowId" : $(tr).find('td:eq(1)').text()
						}   
						i++; 
					}
				}	 
				j++;   	
			}); 
			checkedRowsDeleted = i;
			// TableData.shift();  // first row will be heading - so remove
			return TableData;
		}

		function undeleteKaro()
		{
			var TableData;
			TableData = storeTblValuesCheckedDeleted();
			TableData = JSON.stringify(TableData);
			// alert(JSON.stringify(TableData));
			// return;


			$.ajax({
				'url': base_url + '/' + controller + '/undelete',
				'type': 'POST',
				// 'dataType': 'json',
				'data': {
							'TableData': TableData
						},
				'success': function(data)
				{
					// if(data)
					// {
						location.reload();
					// }
				},
						'error': function(jqXHR, exception)
						{
							document.write(jqXHR.responseText);
						}
			});	
		}
	</script>


	<script type="text/javascript">
			// var myDataTable;
			$(document).ready( function () {
				myDataTable = $('#tblItems').DataTable({
					paging: false,
					iDisplayLength: -1,
					aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
				});

				myDataTableDeleted = $('#tblItemsDeleted').DataTable({
					paging: false,
					iDisplayLength: -1,
					aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
				});

				// $("#btnShow").trigger("click");
			} );

			function rowCount()
		{
			var c=0;
			$("#tblItems tr").each(function(i, v){
				if($(this).find("td:eq("+ 4 +")").text() == '1' )
				{
					c++;
				}	
			});
			// alert(c);
			if(c>=200)
			{
				alert("Enough changes done for this time...");
			}
		}

	</script>

	<script type="text/javascript">
		var tblRowsCount=0;
		function storeTblValuesOnlyRowId()
		{
			var idString = "";
			$('#tblItems tr').each(function(row, tr)
			{
				idString += $(tr).find('td:eq(1)').text() + ", "
				
			}); 
			
			idString = idString.substring(0, idString.length-4);
			return idString;
		}

		function getClosingBalance()
		{	
			var TableData;
			TableData = storeTblValuesOnlyRowId();
			

			$.ajax({
					'global': false,
					'url': base_url + '/' + controller + '/getClosingBalance',
					'type': 'POST',
					'dataType': 'json',
					'data': {
								'TableData': TableData
							},
					'success': function(data)
					{
						// console.log(data['records']);
						i=0;
						$('#tblItems tr:lt(' + data['records'].length +')').each(function(row, tr)
						{
							var index = data['records'].findIndex(obj => obj.itemRowId === $(tr).find('td:eq(1)').text());
							closingBal = data['records'][index]['closingQty'];
							$(tr).find('td:eq(11)').text(closingBal);
							$(tr).find('td:eq(11)').css('color', 'red');
							i++;
							myDataTable.rows(tr).invalidate().draw();
						});
						
						alertPopup('Records loaded... ' + data.timeTook, 4000);
					},
						error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});
			
		}
		function getClosingBalanceInBackground()
		{	
			var TableData;
			TableData = storeTblValuesOnlyRowId();
			$.ajax({
					'global': false,
					'url': base_url + '/' + controller + '/getClosingBalance',
					'type': 'POST',
					'dataType': 'json',
					'data': {
								'TableData': TableData
							},
					'success': function(data)
					{
						// console.log(data['records']);
						x=1
						tr=0;
						i=0;
						rowsInTable = $('#tblItems tr').length;
						var myInterval = setInterval(function(){
								if(rowsInTable-1 > tr)
								{
									var index = data['records'].findIndex(obj => obj.itemRowId == $('#tblItems tr:eq(' + tr +')').find('td:eq(1)').text());
									// console.log(index + "---" + $('#tblItems tr:eq(' + tr +')').find('td:eq(1)').text());
									try
									{
										closingBal = data['records'][index]['closingQty'];
										$('#tblItems tr:eq(' + tr +')').find('td:eq(11)').text(closingBal);
										$('#tblItems tr:eq(' + tr +')').find('td:eq(11)').css('color', 'red');
										tr++;
									}
									catch(e)
									{

									}
								}
								else{
									clearInterval(myInterval);
								}
							// console.log(data['records']);
						}, 500);
						alertPopup('Records loaded... ' + data.timeTook, 4000);
					},
						error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});
			
		}
	</script>
<?=$this->endSection()?>	