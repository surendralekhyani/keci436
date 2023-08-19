
	<script type="text/javascript" src="<?php echo base_url(); ?>/public/js/jquery-barcode.js"></script>
<style type="text/css">
	* {
    color:black;
    font-family:Arial, sans-serif;
    font-size:12px;
    font-weight:normal;
	}
	#config {
	    margin: 10px 0 10px 0px;
	}
	.config {
	    float: left;
	    width: 200px;
	    height: 250px;
	    border: 1px solid #000;
	    margin-left: 10px;
	}
	.config .title {
	    font-weight: bold;
	    text-align: center;
	}
	.config .barcode2D, #miscCanvas {
	    display: none;
	}
	#submit {
	    /*clear: both;*/
	}

	input[type="button"] {
	    /*margin: 10px 0 10px 0px;*/
	}

	#barcodeTarget, #canvasTarget {
	    margin-top: 20px;
	}
</style>

<script type="text/javascript">
	var controller='Backupdata_Controller';
	var base_url='<?php echo site_url();?>';

	vModuleName = "Backup";

	function importData()
	{	
		// alert("DDD");
		$.ajax({
				'url': base_url + '/' + controller + '/frmExcel',
				'type': 'POST',
				'data': {'bankaccount' : 1},
				'success': function(data){
					if(data){
						alert(data);
					}
				}
		});
	}


	function backupData()
	{	
		// alert("FFF");
		$.ajax({
				'url': base_url + '/' + controller + '/dbbackup',
				'type': 'POST',
				'data': {'bankaccount' : 1},
				'success': function(data){
					if(data){
						alert(data);
					}
				}
		});
	}
</script>

	<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-0">
		<?php
			echo '<br />Current PHP version: ' . phpversion();
		?>
		<p>Environment: <?= ENVIRONMENT ?></p>
		<p>Codeigniter: <?= CodeIgniter\CodeIgniter::CI_VERSION ?></p>
	<div id="divVersion"></div>
	
		<script type="text/javascript">
			if (typeof jQuery != 'undefined') {  
			    // jQuery is loaded => print the version
			    // alert(jQuery.fn.jquery);
			    $("#divVersion").text("jQuery Version: " + jQuery.fn.jquery);
			    $(function () {
				    $.get("<?php echo base_url(); ?>/public/css/bootstrap.css", function (data) {
				        var version = data.match(/v[.\d]+[.\d]/);
				        // alert(version);
				        $("#divVersion").append("<br/>Bootstrap Version: " + version);
				    });
				});
			}
		</script>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style='border:1px solid lightgray; border-radius:10px; padding: 10px;'>
		<h1 class="text-center" style='margin-top:0px'>Backup Data</h1>
		<?php

			echo form_open('Backupdata_Controller/dbbackup');
			
			echo "<input type='submit' value='Click Here For Data Backup' id='btnBackupData' class='btn btn-danger col-lg-12 col-md-12 col-sm-12 col-xs-12'>";
			echo "<br />";
			echo "<br />";
			// echo "<input type='button' onclick='importData();' value='Excel' id='btnImport' class='btn btn-danger form-control'>";
			echo form_close();
		?>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-0" style="display: none1;">
		<button onclick="createDummyData();">Dummy Data in SV, PV, ledger</button>
		<button onclick="qrCode();">QR Code</button>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-0" style="display: none;">
		<button onclick="createTable();">Session</button>
		<button onclick="doEmail();">Demo Email</button>
		<button onclick="alterDb();">Alter DB</button>
		<button onclick="deleteOldPdfs();">Delete Old PDFs (Before 90 days)</button>
		<button onclick="copyItems();">Copy items (for too any data test)</button>
	</div>
	</div>
	<hr>

	<div class="row"  style="margin:10px; padding: 10px; display: none;">
		<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12" style='margin-top: 10px; padding-right: 1px; padding-left: 1px;'>
			<label class='jktLabel'>EAN 12 Digits</label>
			<input type='text' class='form-control' id='txtEanCodeP2' maxlength='12' autocomplete='off'>
		</div>
		<div class="col-lg-1 col-sm-1 col-md-1 col-xs-12" style='margin-top: 10px; padding-left: 1px;'>
			<label class='jktLabel'>13th Digit</label>
			<input type='text' class='form-control' id='txtEanCodeP3' maxlength='10' disabled="yes">
		</div>
		<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12" style='margin-top: 10px; padding-left: 1px;'>
			<label class='jktLabel'>&nbsp;</label>
			<button class="btn btn-block btn-primary" onclick="generateBarCode();">Generate BarCode</button>
		</div>
	</div>

		<br />
	  <div class="table-responsive"  style="background: lightpink; margin:10px; padding: 10px;">
	   <table class="table table-bordered table-striped">
	    <tr>
	     <td><b>IP Address</b></td>
	     <td><?php echo $ip_address; ?></td>
	    </tr>
	    <tr>
	     <td><b>Operating System</b></td>
	     <td><?php echo $os; ?></td>
	    </tr>
	    <tr>
	     <td><b>Browser Details</b></td>
	     <td><?php echo $browser . ' - ' . $browser_version; ?></td>
	    </tr>
		<tr>
	     <td><b>Browser Details</b></td>
	    </tr>
	   </table>
	  </div>


	  <div class="container" style="background: lightgrey; display: none;">  
           <div class="container" style="width:700px;">
			   <h4 align="center">Upload File without using Form Submit in Ajax (See SbErp Products for this)</h4>
		  </div>
      </div>  
      <div class="container" style="background: lightgrey;display: none;">  
           <div class="container" style="width:700px;">
           		<input type="file" id="avatar">
			   <button onclick="uploadImage();">Upload File without using Form Submit in Ajax(not working)</button>
		  </div>
      </div>  
      <div class="container text-center" style="background: lightyellow;display: none;" >  
		<h4 align="center">MySql Table to 2D array(PHP,JS)</h4>
		<button id='btnTableToArray' onclick='tableToArray();'>Bring and Show</button>
		<div class="text-left" style="border:1px solid lightgray; padding: 10px;height:300px; overflow:auto;">
			<table id="tblSql" class="table table-bordered">
				<tr>
					<th>SN</th>
					<th>purchaseDetailRowId</th>
					<th>itemRowId</th>
					<th>itemName</th>
					<th>customerRowId</th>
					<th>customerName</th>
				</tr>
			</table>
		</div>
      </div>  

      <!-- barcode -->
      <div class="container" style="background: #fff; display: none1;">  
           <div id="generator">Please fill in the code :
			    <input type="text" id="barcodeValue" value="12345670">
			    <div id="config">
			        <div class="config" style="overflow: auto;">
			            <div class="title">Type</div>
			            <input type="radio" name="btype" id="ean8" value="ean8" checked="checked">
			            <label for="ean8">EAN 8</label>
			            <br />
			            <input type="radio" name="btype" id="ean13" value="ean13">
			            <label for="ean13">EAN 13</label>
			            <br />
			            <input type="radio" name="btype" id="upc" value="upc">
			            <label for="upc">UPC</label>
			            <br />
			            <input type="radio" name="btype" id="std25" value="std25">
			            <label for="std25">standard 2 of 5 (industrial)</label>
			            <br />
			            <input type="radio" name="btype" id="int25" value="int25">
			            <label for="int25">interleaved 2 of 5</label>
			            <br />
			            <input type="radio" name="btype" id="code11" value="code11">
			            <label for="code11">code 11</label>
			            <br />
			            <input type="radio" name="btype" id="code39" value="code39">
			            <label for="code39">code 39</label>
			            <br />
			            <input type="radio" name="btype" id="code93" value="code93">
			            <label for="code93">code 93</label>
			            <br />
			            <input type="radio" name="btype" id="code128" value="code128">
			            <label for="code128">code 128</label>
			            <br />
			            <input type="radio" name="btype" id="codabar" value="codabar">
			            <label for="codabar">codabar</label>
			            <br />
			            <input type="radio" name="btype" id="msi" value="msi">
			            <label for="msi">MSI</label>
			            <br />
			            <input type="radio" name="btype" id="datamatrix" value="datamatrix">
			            <label for="datamatrix">Data Matrix</label>
			            <br />
			            <br />
			        </div>
			        <div class="config"  style="overflow: auto;">
			            <div class="title">Misc</div>Background :
			            <input type="text" id="bgColor" value="#FFFFFF" size="7">
			            <br />"1" Bars :
			            <input type="text" id="color" value="#000000" size="7">
			            <br />
			            <div class="barcode1D">bar width:
			                <input type="text" id="barWidth" value="1" size="3">
			                <br />bar height:
			                <input type="text" id="barHeight" value="50" size="3">
			                <br />
			            </div>
			            <div class="barcode2D">Module Size:
			                <input type="text" id="moduleSize" value="5" size="3">
			                <br />Quiet Zone Modules:
			                <input type="text" id="quietZoneSize" value="1" size="3">
			                <br />Form:
			                <input type="checkbox" name="rectangular" id="rectangular">
			                <label for="rectangular">Rectangular</label>
			                <br />
			            </div>
			            <div id="miscCanvas">x :
			                <input type="text" id="posX" value="10" size="3">
			                <br />y :
			                <input type="text" id="posY" value="20" size="3">
			                <br />
			            </div>
			        </div>
			        <div class="config" style="overflow: auto;">
			            <div class="title">Format</div>
			            <input type="radio" id="css" name="renderer" value="css" checked="checked">
			            <label for="css">CSS</label>
			            <br />
			            <input type="radio" id="bmp" name="renderer" value="bmp">
			            <label for="bmp">BMP (not usable in IE)</label>
			            <br />
			            <input type="radio" id="svg" name="renderer" value="svg">
			            <label for="svg">SVG (not usable in IE)</label>
			            <br />
			            <input type="radio" id="canvas" name="renderer" value="canvas">
			            <label for="canvas">Canvas (not usable in IE)</label>
			            <br />
			        </div>
			    </div>
			    <div id="submit">
			        <input type="button" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Generate the barcode&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;">
			    </div>
			</div>
			<div id="barcodeTarget" class="barcodeTarget"></div>
			<canvas id="canvasTarget" width="150" height="150" style=""></canvas>
      </div>
      <!-- END barcode -->


      <!-- Synchronized Block Test -->
      <div class="container" style="background: #fff; display: none1; margin-top:50px;">  
			    <div id="one">
			        <button class="btn-primary" onclick="setZero();">Set Zero</button>
			        <button class="btn-primary" onclick="plusTen();">Set +10</button>
			        <button class="btn-primary" onclick="plusTwenty();">Set +20</button>
			        <button class="btn-primary" onclick="showRechargeLimit();">Show</button>
			    </div>
      </div>
	<script type="text/javascript">
	function setZero()
	{
		console.log("setZero");
		var dataJson = { id: "hello", link: "link" };
		$.ajax({
			'global': false,
			'url': base_url + '/' + controller + '/setZero',
			'type': 'POST',
			'data': dataJson,
			'dataType': 'json',
			'success': function(data){
				if(data){
					console.log(data);
				}
			},
			error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
		});
	}	

	function showRechargeLimit()
	{
		console.log("showRechargeLimit");
		var dataJson = { id: "hello", link: "link" };
		$.ajax({
			'global': false,
			'url': base_url + '/' + controller + '/showRechargeLimit',
			'type': 'POST',
			'data': dataJson,
			'dataType': 'json',
			'success': function(data){
				if(data){
					console.log(data['records'][0].rechargeLimit);

				}
			},
			error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
		});
	}				

		
	function plusTen()
	{
		console.log("plusTen");
		var dataJson = { id: "hello", link: "link" };
		$.ajax({
			'global': false,
			'url': base_url + '/' + controller + '/plusTen',
			'type': 'POST',
			'data': dataJson,
			'dataType': 'json',
			'success': function(data){
				if(data){
					//console.log(data['records'][0].rechargeLimit);
					console.log("Complete Plus Ten");
				}
			},
			error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
		});
	}				

		
	function plusTwenty()
	{
		console.log("plusTwenty");
		var dataJson = { id: "hello", link: "link" };
		$.ajax({
			'global': false,
			'url': base_url + '/' + controller + '/plusTwenty',
			'type': 'POST',
			'data': dataJson,
			'dataType': 'json',
			'success': function(data){
				if(data){
					//console.log(data['records'][0].rechargeLimit);
					console.log("Complete Plus Twenty");
				}
			},
			error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
		});
	}
	
	</script>
      <!-- END Synchronized Block Test -->


<script type="text/javascript">
	// function uploadImage()
	// {
	// 	// // alert();
	// 	// var link = $("#avatar").val();
	// 	// console.log(link);
	// 	// var dataJson = { id: "hello", link: link };
	// 	// $.ajax({
	// 	// 	'url': base_url + '/' + controller + '/uploadImage',
	// 	// 	'type': 'POST',
	// 	// 	'data': dataJson,
	// 	// 	// 'dataType': 'json',
	// 	// 	'success': function(data){
	// 	// 		if(data){
	// 	// 			// setMySqlTable(data['records']);
	// 	// 			alert(JSON.stringify(data));

	// 	// 		}
	// 	// 	},
	// 	// 	'error': function(jqXHR, exception)
	// 	// 	{
	// 	// 		document.write(jqXHR.responseText);
	// 	// 	}
	// 	// });
	// }

	// function tableToArray()
	// {
	// 	$.ajax({
	// 		'url': base_url + '/' + controller + '/tableToArray',
	// 		'type': 'POST',
	// 		'data': {'bankaccount' : 1},
	// 		'dataType': 'json',
	// 		'success': function(data){
	// 			if(data){
	// 				setMySqlTable(data['records']);
	// 				// alert(JSON.stringify(data));

	// 			}
	// 		},
	// 		'error': function(jqXHR, exception)
	// 		{
	// 			document.write(jqXHR.responseText);
	// 		}
	// 	});
	// }
	// function setMySqlTable(records)
	// {
	// 	$("#tblSql").find("tr:gt(0)").remove();
	//       var table = document.getElementById("tblSql");
	// 		// alert(JSON.stringify(records));
	// 		// alert(records.length);
	//       for(i=0; i<records.length; i++)
	//       {
	//           newRowIndex = table.rows.length;
	//           row = table.insertRow(newRowIndex);

	//           var cell = row.insertCell(0);
	//           cell.innerHTML = i+1;
	//           var cell = row.insertCell(1);
	//           cell.innerHTML = records[i].purchaseDetailRowId;
	//           var cell = row.insertCell(2);
	//           cell.innerHTML = records[i].itemRowId;
	//           var cell = row.insertCell(3);
	//           cell.innerHTML = records[i].itemName;
	//           var cell = row.insertCell(4);
	//           cell.innerHTML = records[i].customerRowId;
	//           var cell = row.insertCell(5);
	//           cell.innerHTML = records[i].customerName;
	//       }
	// }
</script>

<script type="text/javascript">
	// function createTable()
	// {
	// 	$.ajax({
	// 		'url': base_url + '/' + controller + '/createTable',
	// 		'type': 'POST',
	// 		'data': {'bankaccount' : 1},
	// 		'success': function(data){
	// 			if(data){
	// 				alert(data);
	// 			}
	// 		}
	// 	});
	// }


	// function doEmail()
	// {
	//    // alert();
	// 	$.ajax({
	// 		'url': base_url + '/' + controller + '/doEmail',
	// 		'type': 'POST',
	// 		'data': {'bankaccount' : 1},
	// 		'success': function(data){
	// 			//if(data){
	// 				alert("done");
	// 			//}
	// 		}
	// 	});
	// }

	// function alterDb()
	// {
	//    // alert();
	// 	$.ajax({
	// 		'url': base_url + '/' + controller + '/alterDb',
	// 		'type': 'POST',
	// 		'data': {'bankaccount' : 1},
	// 		'success': function(data){
	// 				alert("done");
	// 		}
	// 	});
	// }

	// function deleteOldPdfs()
	// {
	//    // alert();
	// 	$.ajax({
	// 		'url': base_url + '/' + controller + '/deleteOldPdfs',
	// 		'type': 'POST',
	// 		'data': {'bankaccount' : 1},
	// 		'success': function(data){
	// 				alert("done..." + data);
	// 		}
	// 	});
	// }


	// function copyItems()
	// {
	//    // alert();
	// 	$.ajax({
	// 		'url': base_url + '/' + controller + '/copyItems',
	// 		'type': 'POST',
	// 		'data': {'bankaccount' : 1},
	// 		'success': function(data){
	// 				alert("done...");
	// 		}
	// 	});
	// }
</script>


<!-- bar code -->
<script type="text/javascript">
	$(document).ready(function() {
		$("#txtEanCodeP2").keyup(function(){
			if($("#txtEanCodeP2").val().length == 12)
			{
				res = 0;
				vBarcode = $("#txtEanCodeP2").val();
				res = mod10CheckDigit(vBarcode);
				$("#txtEanCodeP3").val(res);
			}
		});
	});

	function generateBarCode()
	{
		bCode = $("#txtEanCodeP2").val() + $("#txtEanCodeP3").val();
		if(bCode.length < 13)
		{
			alertPopup("Invalid...", 4000, "red", "white");
			return;
		}
		
	      $.ajax({
	          'url': base_url + '/' + controller + '/generateLabels',
	          'type': 'POST',
	          // 'dataType': 'json',
	          'data': {		
	          				'bCode': bCode
	              		},
	          'success': function(data)
	          {
				window.open(data, '_blank');
	          },
	          'error': function(jqXHR, exception)
	          {
	            $("#paraAjaxErrorMsg").html( jqXHR.responseText );
	            $("#modalAjaxErrorMsg").modal('toggle');
	          }
	      });
	}


	function mod10CheckDigit(Barcode)
	{
		// alert((Barcode.length));
		totalOdd = 0;
		totalEven = 0;
		for(i=0; i<= Barcode.length-1; i=i+2)
		{
			totalOdd = totalOdd + parseInt(Barcode.substring(i, i+1));
		}
		// alert('total odd: ' + totalOdd);
		for(i=1; i<= Barcode.length; i=i+2)
		{
			totalEven = totalEven + parseInt(Barcode.substring(i, i+1));
		}
		// alert('total even: ' + totalEven);
		totalEven = totalEven * 3;
		// alert('total even*3: ' + totalEven);
		total = totalOdd + totalEven;
		// alert('total is: ' + total);
		x = Right(total,1)
		// alert('right: ' + x);
		if(x==0)
		{
			return(10 - 10);
		}
		else
		{
			return(10 - x);
		}
	}
	function Right(str, n)
	{
	    if (n <= 0)
	       return parseInt(0);
	    else if (n > String(str).length)
	    {
	       return parseInt(str);
	    }
	    else 
	    {
	       var iLen = String(str).length;
	       return parseInt(String(str).substring(iLen, iLen - n));
	    }
	}
</script>


<script type="text/javascript">
	$(document).ready(function () {
	    $("input[type='button']").click(function () {
	        generateBarcode();
	    });

	    function generateBarcode() {
	        var value = $("#barcodeValue").val();
	        var btype = $("input[name=btype]:checked").val();
	        var renderer = $("input[name=renderer]:checked").val();

	        var quietZone = false;
	        if ($("#quietzone").is(':checked') || $("#quietzone").attr('checked')) {
	            quietZone = true;
	        }

	        var settings = {
	            output: renderer,
	            bgColor: $("#bgColor").val(),
	            color: $("#color").val(),
	            barWidth: $("#barWidth").val(),
	            barHeight: $("#barHeight").val(),
	            moduleSize: $("#moduleSize").val(),
	            posX: $("#posX").val(),
	            posY: $("#posY").val(),
	            addQuietZone: $("#quietZoneSize").val()
	        };
	        if ($("#rectangular").is(':checked') || $("#rectangular").attr('checked')) {
	            value = {
	                code: value,
	                rect: true
	            };
	        }
	        if (renderer == 'canvas') {
	            clearCanvas();
	            $("#barcodeTarget").hide();
	            $("#canvasTarget").show().barcode(value, btype, settings);
	        } else {
	            $("#canvasTarget").hide();
	            $("#barcodeTarget").html("").show().barcode(value, btype, settings);
	        }
	    }

	    function showConfig1D() {
	        $('.config .barcode1D').show();
	        $('.config .barcode2D').hide();
	    }

	    function showConfig2D() {
	        $('.config .barcode1D').hide();
	        $('.config .barcode2D').show();
	    }

	    function clearCanvas() {
	        var canvas = $('#canvasTarget').get(0);
	        var ctx = canvas.getContext('2d');
	        ctx.lineWidth = 1;
	        ctx.lineCap = 'butt';
	        ctx.fillStyle = '#FFFFFF';
	        ctx.strokeStyle = '#000000';
	        ctx.clearRect(0, 0, canvas.width, canvas.height);
	        ctx.strokeRect(0, 0, canvas.width, canvas.height);
	    }

	    $(function () {
	        $('input[name=btype]').click(function () {
	            if ($(this).attr('id') == 'datamatrix') showConfig2D();
	            else showConfig1D();
	        });
	        $('input[name=renderer]').click(function () {
	            if ($(this).attr('id') == 'canvas') $('#miscCanvas').show();
	            else $('#miscCanvas').hide();
	        });
	        // generateBarcode();
	    });
	});

</script>

<script>	
	function createDummyData()
	{	
		// alert("DDD");
		$.ajax({
				'url': base_url + '/' + controller + '/createDummyData',
				'type': 'POST',
				'data': {'bankaccount' : 1},
				'success': function(data){
					if(data){
						alert("done");
					}
				}
		});
	}

	function qrCode()
	{	
		// alert("DDD");
		$.ajax({
				'url': base_url + '/' + controller + '/qrCode',
				'type': 'POST',
				'data': {'bankaccount' : 1},
				'success': function(data){
					if(data){
						// alert("done");
						console.log(data);
					}
				}
		});
	}
</script>