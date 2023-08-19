
<script>
	var controller='User_Controller';
	var base_url='<?php echo site_url();?>';

	vModuleName = "Users";


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
	          // cell.setAttribute("onclick", "editThis(" + records[i].rowid + ", " + records[i].uid + ", " + records[i].rowid + ", " + records[i].pwd + ")");
	          cell.className = "editRecord";

	          var cell = row.insertCell(1);
				  cell.innerHTML = "<span class='glyphicon glyphicon-remove'></span>";
	          cell.style.textAlign = "center";
	          cell.style.color='lightgray';
	          cell.setAttribute("onmouseover", "this.style.color='red'");
	          cell.setAttribute("onmouseout", "this.style.color='lightgray'");
	          cell.setAttribute("onclick", "delrowid(" + records[i].rowid +")");
	          // data-toggle="modal" data-target="#myModal"
	          cell.setAttribute("data-toggle", "modal");
	          cell.setAttribute("data-target", "#myModal");

	          var cell = row.insertCell(2);
	          cell.style.display="none";
	          cell.innerHTML = records[i].rowid;
	          var cell = row.insertCell(3);
	          cell.innerHTML = records[i].uid;
	          var cell = row.insertCell(4);
	          // cell.style.display="none";
	          var cell = row.insertCell(5);
	          cell.style.display="none";
	          var cell = row.insertCell(6);
	          cell.style.display="none";
	  	  }


	  	$('.editRecord').on('click', editThis);

		myDataTable.destroy();
		$(document).ready( function () {
	    myDataTable=$('#tbl1').DataTable({
		    paging: false,
		    iDisplayLength: -1,
		    aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],

		});
		} );

		// $("#tbl1 tr").on("click", highlightRow);
			
	}

	function loaddata()
	{	
		var uid = $("#txtUID").val().trim();
		if(uid == "")
		{
			alertPopup("Enter User name  ...", 8000);
			$("#txtUID").focus();
			return false;
		}

		var pwd = document.getElementById("txtPassword").value;
		var pwd = $("#txtPassword").val().trim();
		
		if(document.getElementById("btnSave").value == "Save")
		{
			if(pwd == "")
			{
				alertPopup("Enter password...", 8000);
				$("#txtPassword").focus();
				return false;
			}
			$.ajax({
					'url': base_url + '/' + controller + '/insertUser',
					'type': 'POST',
					'dataType': 'json',
					'data': {'uid':uid, 
					// 'mobile':mobile,
					'password':pwd
					},
					'success': function(data){
						setTable(data['records']);
						alertPopup('Record Saved...', 4000);
						blankControls();
					},
					error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});
		}
		else if(document.getElementById("btnSave").value == "Update")
		{
			$.ajax({
					'url': base_url + '/' + controller + '/updateUser',
					'type': 'POST',
					'dataType': 'json',
					'data': {'rowid' : globalrowid, 
					'uid':uid, 
					// 'mobile':mobile,
					'password':pwd
					 },
					'success': function(data){
						setTable(data['records']);
						alertPopup('Record Updated...', 4000);
						blankControls();
						$("#txtPassword").val('');
						$("#btnSave").val('Save');
					},
					error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});
		}	
	}
	
</script>
<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-0">
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style='border:1px solid lightgray; border-radius:10px; padding: 10px;'>
		<h1 class="text-center" style='margin-top:0px'>Users</h1>
		<?php
			echo form_open('User_Controller/insertUser', "onsubmit='return(false);'");
			echo form_input('uid', '', "placeholder='User Name' required class='form-control' maxlength='10' autofocus id='txtUID' style='margin-bottom:15px;' autocomplete='off'");
			echo "<br>";
			echo form_password('password', '', "placeholder='Password' required class='form-control' maxlength='20' autofocus id='txtPassword' autocomplete='off' style='margin-bottom:15px;'");
			echo "<br />";
			echo "<div class='col-lg-1 col-md-1 col-sm-1 col-xs-0'></div>";
			echo "<input type='button' onclick='loaddata();' value='Save' id='btnSave' class='btn btn-danger col-lg-10 col-md-10 col-sm-10 col-xs-12'>";
			echo "<div class='col-lg-1 col-md-1 col-sm-1 col-xs-0'></div>";
			echo form_close();
		?>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-0">
	</div>
</div>
	<hr>
	<div id="containerTT"></div>


	<div class="row" id="container">
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-0">
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style='border:1px solid lightgray; padding: 10px; overflow:scroll;'>
			<table class='table table-hover' id='tbl1'>
			 <!-- <caption></caption> -->
			 <thead>
			 <tr>
				<th  width="50" class="text-center">Edit</th>
				<th  width="50" class="text-center">Delete</th>
				<th style='display:none;'>rowid</th>
				<th>User Name</th>
				<th style='display:none;'>Mobile</th>
				<th style='display:none;'>Password</th>
				<th style='display:none;'>AB Access</th>
			 </tr>
			 </thead>
			 <tbody>
			 <?php 
			 foreach ($records as $row) 
			 {
			 	$rw = $row['rowid'];
			 	$uid = $row['uid'];
			 	$mobile = $row['mobile'];
			 	$pwd = $row['pwd'];
			 	echo "<tr>";
				echo '<td style="color: lightgray;cursor: pointer;cursor: hand;" class="editRecord text-center" onClick="editThis('.$rw.', \''.$uid.'\', \''.$mobile.'\',\''.$pwd.'\');" onmouseover="this.style.color=\'green\';"  onmouseout="this.style.color=\'lightgray\';"><span class="glyphicon glyphicon-pencil"></span></td>
					   <td style="color: lightgray;cursor: pointer;cursor: hand;" class="text-center" onclick="delrowid('.$rw.');" data-toggle="modal" data-target="#myModal"  onmouseover="this.style.color=\'red\';"  onmouseout="this.style.color=\'lightgray\';"><span class="glyphicon glyphicon-remove"></span></td>';
			 	echo "<td style='width:0px;display:none;'>".$row['rowid']."</td>";
			 	echo "<td>".$row['uid']."</td>";
			 	echo "<td style='display:none;'>".$row['mobile']."</td>";
			 	echo "<td style='display:none;'>".$row['pwd']."</td>";
			 	echo "<td style='display:none;'>".$row['abAccess']."</td>";
				echo "</tr>";
			 }
			 ?>
			 </tbody>
			</table>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-0">
		</div>

		<!-- Modal -->
		  <div class="modal" id="myModal" role="dialog">
		    <div class="modal-dialog modal-sm">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title">KE</h4>
		        </div>
		        <div class="modal-body">
		          <p>Are you sure <br /> Delete this record..?</p>
		        </div>
		        <div class="modal-footer">
		          <button type="button" onclick="delrow();" class="btn btn-danger" data-dismiss="modal">Yes</button>
		          <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
		        </div>
		      </div>
		    </div>
		  </div>

	</div>


	<script type="text/javascript">
		var globalrowid;
		var globalRowIdForDeletion;
		function editThis()
		{
			rowIndex = $(this).parent().index();
			colIndex = $(this).index();
			globalrowid = $(this).closest('tr').children('td:eq(2)').text();
			$("#txtUID").val( $(this).closest('tr').children('td:eq(3)').text() );

			// var base_url='<?php echo site_url();?>';
			// globalrowid = rowid;
			// document.getElementById("txtUID").value=uid;
			// document.getElementById("txtPassword").value=pwd;
			document.getElementById("btnSave").value="Update";		
		}

		function delrowid(rowid)
		{
			globalRowIdForDeletion = rowid;
		}
		function delrow()
		{
			var controller='User_Controller';
			var base_url='<?php echo site_url();?>';
			// var rowid = document.getElementById("txtPrefix").value;
			$.ajax({
					'url': base_url + '/' + controller + '/deleteUser',
					'type': 'POST',
					'dataType': 'json',
					'data': {'rowid' : globalRowIdForDeletion},
					'success': function(data){
						setTable(data['records']);
						alertPopup('Record Deleted...', 4000);
						blankControls();
						$("#txtPassword").val('');
						$("#btnSave").val('Save');
						
					},
					error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			});
		}
	</script>

	 <!-- JQuery -->
	<script>
		$(document).ready( function () {
	    myDataTable=$('#tbl1').DataTable({
		    paging: false,
		    iDisplayLength: -1,
		    aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],

		});

	  	$('.editRecord').on('click', editThis);

		} );
	</script>	