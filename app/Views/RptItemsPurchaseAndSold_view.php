<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/datatable/buttons.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/datatable/jszip.min.js"></script>
<link rel='stylesheet' href='<?php  echo base_url();  ?>/public/css/suriprint.css'>

<script type="text/javascript">
	var controller='RptItemsPurchaseAndSold_Controller';
	var base_url='<?php echo site_url();?>';

     vModuleName = "Items Purchased and Sold";

  var globalRecords;
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

          vType = $("#cboVoucherType").val();
          searchWhat = $("#txtSearch").val().trim();
          // alert(searchWhat);
		$.ajax({
				'url': base_url + '/' + controller + '/showData',
				'type': 'POST',
				'dataType': 'json',
				'data': {
							'dtFrom': dtFrom
							, 'dtTo': dtTo
							, 'vType': vType
              , 'searchWhat': searchWhat
						},
				'success': function(data)
				{
					if(data)
					{
						// alert(JSON.stringify(data));
            // console.log( JSON.stringify( data['timeTook'] ) );
            // totalRecords = parseInt(data['records'].length) + parseInt(data['recordsPurchase'].length) + parseInt(data['recordsQuotation'].length) + parseInt(data['recordsCashSale'].length); 
            // totalRecords = parseInt(data['records'].length) + parseInt(data['recordsPurchase'].length); 
            totalRecords = parseInt(data['records'].length); 
            globalRecords = data['records'];
            // console.log( (data['recordsPurchase']) );
            console.log( Math.ceil(totalRecords) );
            $("#divPages").empty();
            $("#divPages").append('<span>Total Records: '+ totalRecords +' </span>');
            for(i=1; i<= Math.ceil(totalRecords / 500); i++)
            {
              $("#divPages").append('<button class="btn btn-danger pageBtn" style="margin:5px;">' + (i) + '</button>');
            }
		        $(".pageBtn").on("click", showData);
            setTablePage(1) // display 1st page by default
            alertPopup("Records Loaded... in Server Time: " + JSON.stringify( data['timeTook'] ), 6000);
					}
				},
        'error': function(jqXHR, exception)
              {
                $("#paraAjaxErrorMsg").html( jqXHR.responseText );
                $("#modalAjaxErrorMsg").modal('toggle');
              }
		});
	}

  function showData()
  {
    // alert($(this).text());
    setTablePage($(this).text())
  }
  function setTablePage(st)
	{
    start = 500 * (st-1);
    loopEnd = 0;
    // r = (globalRecords.length - start) % 500;
    if( (globalRecords.length - start) > 500 )
    {
      loopEnd = 500;
    }
    else
    {
      loopEnd = (globalRecords.length - start);
    }
    
    // console.log(start);
    // return;
		 // alert(JSON.stringify(records));
		  $("#tbl1").empty();
        var table = document.getElementById("tbl1");
        // for(i=0; i<records.length; i++)
        for(i=start; i<(start+loopEnd); i++)
        {
          var newRowIndex = table.rows.length;
          var row = table.insertRow(newRowIndex);
          // row.style.color = "green";

          var cell = row.insertCell(0);
          cell.style.display="none1";
          cell.innerHTML = globalRecords[i].rowId;
          var cell = row.insertCell(1);
          cell.innerHTML = globalRecords[i].detailRowId;
          cell.style.display="none1";
          var cell = row.insertCell(2);
          cell.innerHTML = globalRecords[i].itemRowId;
          // cell.style.display="none";
          var cell = row.insertCell(3);
          cell.innerHTML = dateFormat(new Date(globalRecords[i].dt));
          var cell = row.insertCell(4);
          cell.innerHTML = globalRecords[i].customerName + " [<span style='color: blue;'>" + globalRecords[i].remarks + "</span>]";
          var cell = row.insertCell(5);
          cell.innerHTML = globalRecords[i].itemName + " [<span style='color: blue;'>" + globalRecords[i].itemRemarks + "</span>]";
          var cell = row.insertCell(6);
          cell.innerHTML = globalRecords[i].qty;
          var cell = row.insertCell(7);
          cell.innerHTML = globalRecords[i].rate;
          cell.style.color="red";
          var cell = row.insertCell(8);
          cell.innerHTML = globalRecords[i].amt;
          var cell = row.insertCell(9);
          cell.innerHTML = globalRecords[i].discountPer;
          var cell = row.insertCell(10);
          cell.innerHTML = globalRecords[i].discountAmt;
          // totalNetAmt += parseFloat(globalRecords[i].netAmt);
          var cell = row.insertCell(11);
          cell.innerHTML = globalRecords[i].pretaxAmt;
          cell.style.display="none";
          var cell = row.insertCell(12);
          cell.innerHTML = globalRecords[i].igst;
          cell.style.display="none";
          var cell = row.insertCell(13);
          cell.style.display="none";
          cell.innerHTML = globalRecords[i].igstAmt;
          var cell = row.insertCell(14);
          cell.innerHTML = globalRecords[i].cgst;
          var cell = row.insertCell(15);
          // cell.innerHTML = globalRecords[i].cgstAmt;
          cell.style.display="none";
          var cell = row.insertCell(16);
          cell.innerHTML = globalRecords[i].sgst;
          var cell = row.insertCell(17);
          // cell.innerHTML = globalRecords[i].sgstAmt;
          cell.style.display="none";
          var cell = row.insertCell(18);
          cell.innerHTML = globalRecords[i].netAmt;
          var cell = row.insertCell(19);
          cell.innerHTML = parseFloat(globalRecords[i].netAmt/globalRecords[i].qty).toFixed(2);
          cell.style.color="red";
          var cell = row.insertCell(20);
          cell.innerHTML = globalRecords[i].sp;

	    }



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
		                title: 'Purchase Sale (' + $("#dtFrom").val() + " to " + $("#dtTo").val() + ")",
		                messageTop: $("#txtCustomerName").val(),
		                // messageBottom: 'End Of Doc'
		            }
		        ]
			});
		} );

	}

</script>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
			<h3 class="text-center" style='margin-top:-20px'>Items Purchase And Sold</h3>
			<form name='frm' id='frm' method='post' enctype='multipart/form-data' action="">
				<div class="row" style="margin-top:5px;">
					<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
						<?php
							echo "<label style='color: black; font-weight: normal;'>From:</label>";
							echo form_input('dtFrom', '', "class='form-control' placeholder='' id='dtFrom' maxlength='10'");
		              	?>
		              	<script>
							$( "#dtFrom" ).datepicker({
								dateFormat: "dd-M-yy",changeMonth: true,changeYear: true,yearRange: "2010:2050"
							});

						    // Set the Current Date-50 as Default
						    dt=new Date();
     					    dt.setDate(dt.getDate() - 365);
   		 					$("#dtFrom").val(dateFormat(dt));
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
					<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
              <?php
                    $vType = array();
                  //  $vType['ALL'] = 'ALL';
                    $vType['Sale'] = "Sale";
                    $vType['Purchase'] = "Purchase";
                  //  $vType['Quotation'] = "Quotation";
                  //  $vType['Cash Sale'] = "Cash Sale";
                    echo "<label style='color: black; font-weight: normal;'>Voucher Type</label>";
                    echo form_dropdown('cboVoucherType', $vType, 'Sale',"class='form-control' id='cboVoucherType'");
              ?>
          </div>
            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                <?php
                      echo "<label style='color: black; font-weight: normal;'>Keyword</label>";
                      echo '<input type="text" value="aaa" placeholder="Part of Party Name or Product" class="form-control" maxlength="15" id="txtSearch" />';
                ?>
            </div>
          <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
            <?php
              echo "<label style='color: black; font-weight: normal;'>&nbsp;	</label>";
              echo "<input type='button' onclick='loadData();' value='Show Data' id='btnShow' class='btn btn-primary form-control'>";
            ?>
          </div>
          <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
            <?php
              echo "<label style='color: black; font-weight: normal;'>&nbsp;	</label>";
              echo "<input type='button' onclick='loadDataExcel();' value='Excel' id='btnShowExcel' class='btn btn-primary form-control'>";
            ?>
          </div>
				</div>
         
			</form>
		</div>
	</div>
  <div class="row" style="margin:15px 0; overflow:auto;min-height:50px;">
		<div id="divPages" class="col-lg-12 col-sm-12 col-md-12 col-xs-12 text-center overflow-auto"  style="max-height:50px;">
        <button class="btn btn-danger pageBtn">1</button>
    </div>
  </div>

	<div class="row" style="margin-top:10px;" >
		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
			<div id="divTable" class="divTable col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border:1px solid lightgray; padding: 10px;height:400px; overflow:auto;">
				<table class='table table-hover' id='tbl1'>
				 <thead>
					 <tr>
					 	<th style="display: none1;">RowId</th>
					 	<th style="display: none1;">detailRowId</th>
					 	<th style="display: none1;">itemRowId</th>
					 	<th>Date</th>
					 	<th>Party</th>
					 	<th>Item</th>
					 	<th>Qty</th>
					 	<th>Rate</th>
					 	<th>Amt</th>
					 	<th>D%</th>
					 	<th>D</th>
					 	<th style="display: none;">PreTax</th>
					 	<th style="display: none;">igst%</th>
					 	<th style="display: none;">igst</th>
					 	<th>cgst%</th>
					 	<th style="display: none;">cgst</th>
					 	<th>sgst%</th>
					 	<th style="display: none;">sgst</th>
					 	<th>Net</th>
            <th>perPc</th>
					 	<th>SP</th>
					</tr>
				 </thead>
				 <tbody>

				 </tbody>
         
				</table>
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
			    ordering: false,
		        buttons: [
		            'copyHtml5',
		            {
		                extend: 'excel',
		                title: 'p s (' + $("#dtFrom").val() + " to " + $("#dtTo").val() + ")",
		                messageTop: $("#txtCustomerName").val(),
		            }
		        ]
			});
		} );


  function loadDataExcel()
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

          vType = $("#cboVoucherType").val();
          searchWhat = $("#txtSearch").val().trim();
          // alert(searchWhat);
		$.ajax({
				'url': base_url + '/' + controller + '/showDataExcel',
				'type': 'POST',
				// 'dataType': 'json',
				'data': {
							'dtFrom': dtFrom
							, 'dtTo': dtTo
							, 'vType': vType
              , 'searchWhat': searchWhat
						},
				'success': function(data)
				{
					// alert("done...");
          window.location.href=data;
				},
        error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
		});
	}
</script>