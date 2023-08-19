<!--Checkbox Tree-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/checktree/css/jquery-checktree.css">

<style type="text/css">
	label{color:black;}
</style>
<script type="text/javascript">
	var controller='Right_Controller';
	var base_url='<?php echo site_url();?>';

	vModuleName = "User Rights";

	function loaddata()
	{	
		var str="";
		var cn = "";
		$('#tree input:checked').each(function() {
			str = $(this).val() + "," + str;
			cn = $(this).attr("cn") + "," + cn;
		});


		var OcboUsers = document.getElementById("cboUsers");
		var uid = OcboUsers.options[OcboUsers.selectedIndex].value;
		if(uid==-1)
		{
			myAlert('Please Select User');
			return;	
		}
		if(str=="")
		{
			myAlert('Please Select Atleast one right');
			return;
		}

		arr = str.split(",");
		arr = arr.slice(0,arr.length-1);
		str = arr.reverse().join(",");

		arr = cn.split(",");
		arr = arr.slice(0,arr.length-1);
		cn = arr.reverse().join(",");
		$.ajax
		(
			{
				'url': base_url + '/' + controller + '/insertRights',
				'type': 'POST', 
				'data': {'uid' : uid, 'rights' : str, 'cn' : cn},
				'success': function(data){
					myAlert("Rights Saved...!!!");
				},
					error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
			}
		);

	 	blankcontrol();
	}

	function blankcontrol()
	{
		document.getElementById("cboUsers").selectedIndex = "0";
		$('#tree input:checked').each(function() {
			$(this).removeAttr('checked');
		});		 
	}
	$(document).ready(function(){
		$("#cboUsers").change(function(){
			$.ajax({
	        'url': base_url + "/" + controller + '/getRights',
	        'data': {'uid':$("#cboUsers").val()},
	        'type': 'POST', 
	        'success': function(data)
	        {
	        	// console.log(data);
				arr = data.split(",");
				arr = arr.slice(0,arr.length-1);
				arr = arr.reverse();
	            $('input[type="checkbox"]').each(function(){
					for(j=0;j<arr.length;j++)
					{
						if($(this).val() === arr[j])
						{
							$(this).prop("checked",true);
							break;
						}
						else
						{
							$(this).removeAttr("checked");
						}
					}
	            });
	        },
					error: function (jqXHR, exception) {
						ajaxCallErrorMsg(jqXHR, exception)
				}
	      });
		});
	});
</script>


<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-0">
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style='border:1px solid lightgray; border-radius:10px; padding: 10px;'>
		<h1 class="text-center" style='margin-top:0px'>User Rights</h1>
		<?php
			$attributes[] = array("class"=>"form-control" );
			echo form_open('Right_Controller/insertRights', "onsubmit='return(false);'");
			$arr = array();
			foreach ($users as $row)
			{
        		$arr[$row['rowid']]= $row['uid'];
			}
			$temp["-1"] = "--- SELECT USER ---";
			$users = $temp+$arr;
			echo form_dropdown('uid',$users,"-1","class='form-control' id='cboUsers'");
		?>
		<br/>
	</div>
</div>
<br>
<br>
	<ul id="tree">
		<div class="row" style="position:relative;">

			<div class="col-lg-4 col-sm-4 col-md-4" style="border-right:1px solid lightgray;border-bottom:1px solid lightgray; height:300px;overflow:auto">
				<li>
					<label>
						<input type="checkbox" class="rights_menu" value="Masters" cn="mm_Controller" />
						<b>Masters</b>
					</label>
					<ul>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Dash Board" cn="DashBoard_Controller"  />
									Dash Board <span style="color: red;" >(Zaruri for every user)</span>
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Organisation" cn="Organisation_Controller"  />
									Organisation
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Customers"  cn="Customers_Controller" />
									Customers
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Item Groups" cn="ItemGroups_Controller" />
									Item Groups
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Items" cn="Items_Controller" />
									Items
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Edit Items" cn="EditItems_Controller" />
									Edit Items
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Edit Items (Group)" cn="EditItems_Controller" />
									Edit Items (Group)
							</label>
						</li>
					</ul>
				</li>
			</div>


			<div class="col-lg-4 col-sm-4 col-md-4" style="border-right:1px solid lightgray;border-bottom:1px solid lightgray;height:300px;overflow:auto">
				<li>
					<label>
						<input type="checkbox" class="rights_menu" value="Transactions" cn="DashBoard_Controller"/>
						<b>Transactions</b>
					</label>
					<ul>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Quotation" cn="Quotation_Controller" />
									Quotation
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Purchase" cn="Purchase_Controller" />
									Purchase
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Sale" cn="Sale_Controller" />
									Sale
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Payment / Receipt" cn="PaymentReceipt_Controller" />
									Payment / Receipt
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Reminders" cn="Reminders_Controller" />
									Reminders
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Requirement" cn="Requirement_Controller" />
									Requirement
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Replacement" cn="Replacement_Controller" />
									Replacement
							</label>
						</li>

					</ul>
				</li>
			</div>



			<div class="col-lg-4 col-sm-4 col-md-4" style="border-right:1px solid lightgray;border-bottom:1px solid lightgray;height:300px;overflow:auto">
				<li>
					<label>
						<input type="checkbox" class="rights_menu" value="Reports" cn="DashBoard_Controller"/>
						<b>Reports</b>
					</label>
					<ul>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Ledger" cn="RptLedger_Controller" />
									Ledger
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Items Purchase And Sold" cn="RptItemsPurchaseAndSold_Controller" />
									Items Purchase And Sold
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Dues" cn="RptDues_Controller" />
									Dues
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Search" cn="RptSearch_Controller" />
									Search
							</label>
						</li>

					</ul>
				</li>
			</div>
			<div class="col-lg-4 col-sm-4 col-md-4" style="border-right:1px solid lightgray;border-bottom:1px solid lightgray;height:300px;overflow:auto">
				<li>
					<label>
						<input type="checkbox" class="rights_menu" value="Tools" cn="DashBoard_Controller" />
						<b>Tools</b>
					</label>
					<ul>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Create Users" cn="User_Controller" />
								Create Users
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="User Rights" cn="Right_Controller" />
								User Rights
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Reset Password" cn="Changepwdadmin_Controller" />
								Reset Password
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Backup Data" cn="DashBoard_Controller" />
								Backup Data
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Admin Rights" cn="DashBoard_Controller" />
								Admin Rights
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Duplicates" cn="DashBoard_Controller" />
								Duplicates
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Reset Password" cn="DashBoard_Controller" />
								Duplicate Customers
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Address Book" cn="DashBoard_Controller" />
								Address Book
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Conclusions" cn="DashBoard_Controller" />
								Conclusions
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="To Do List" cn="DashBoard_Controller" />
								To Do List
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" class="rights_menu" value="Daily Cash" cn="DashBoard_Controller" />
								Daily Cash
							</label>
						</li>
					</ul>
				</li>
			</div>

		</div>
	</ul>
	</div>
	<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>/public/checktree/js/jquery-checktree.js"></script>
	<script>
		$('#tree').checktree();
	</script>
	<br/>
	<div class="row" style="margin-bottom:15px;">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-0">
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-0">
		<?php
			echo "<div class='col-lg-1 col-md-1 col-sm-1'></div>";
			echo "<input type='button' onclick='loaddata();' value='Save' id='btnSave' class='btn btn-danger col-lg-10 col-md-10 col-sm-10'>";
			// echo "<div class='col-lg-2 col-md-2 col-sm-2'></div>";
		 // 	echo "<input type='button' value='Cancel' onclick='blankcontrol();' class='btn btn-success col-lg-4 col-md-4 col-sm-4'>";
		 	echo "<div class='col-lg-1 col-md-1 col-sm-1'></div>";
			echo form_close();
		?>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-0">
		</div>
	</div>