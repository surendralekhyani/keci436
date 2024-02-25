<style type="text/css">
  .menu1{
    cursor: pointer;
  }
</style>
<script>
        var totalReminders = 0;
        var base_url='<?php echo site_url();?>';  


        $(document).ready( function () {
          <?php
            $php_array = $mr;
            $js_array = json_encode($php_array);
            echo "var javascript_array = ". $js_array . ";\n";
          ?>
          
          $('a.menu1').each(function(){
              for(i=0;i<javascript_array.length;i++)
              {
                  if(javascript_array[i]['menuoption'] === $(this).text())
                  {
                      $(this).css("display","block");
                      break;
                  }
                  else
                  {
                      $(this).css("display","none");
                  }
              }
          });
          // alert(javascript_array[0]['menuoption']);
          $("#txtMenuEval").keypress(function(e) {
              if(e.which == 13) {
                var result = eval($('#txtMenuEval').val());
                result = parseFloat(result);
                  $('#txtMenuEvalResult').val( result.toFixed(2) );
                  // $('#txtEvalResult').text().toFixed(2);

              }
          });

          $("#txtMenuEval").focus(function () {
             $(this).select();
          });
          $.unblockUI();
        }); 
</script>
    <div class="container">
        <nav class="nav navbar navbar-inverse navbar-fixed-top">
          <div class="navbar-header"> <!-- It is responsible to Touch Menu for small devices -->
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php  echo base_url();  ?>/index.php/dashboard">:</a>
          </div>

          <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">

              <li class="dropdown">
                <a tabindex="0" class="menu1" style="display:none;" data-toggle="dropdown">Masters<span class="caret"></span></a>
                <!-- role="menu": fix moved by arrows (Bootstrap dropdown) -->
                <ul class="dropdown-menu" role="menu">
                  <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/organisation">Organisation</a></li>
                  <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/customers">Customers</a></li>
                  <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/itemgroups">Item Groups</a></li>
                  <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/items">Items</a></li>
                  <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/edititems">Edit Items</a></li>
                  <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/edititemsgroup">Edit Items (Group)</a></li>
                </ul>
              </li>

              <li class="dropdown">
                <a tabindex="0" class="menu1" style="display:none;" data-toggle="dropdown">Transactions<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/quotation">Quotation</a></li>
                  <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/purchase">Purchase</a></li>
                  <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/sale">Sale</a></li>
                  <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/paymentreceipt">Payment / Receipt</a></li>
                  <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>index.php/dates">Dates</a></li>
                  <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/reminders">Reminders</a></li>
                  <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/requirement">Requirement</a></li>
                  <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>index.php/complaint">Complaint</a></li>   
                  <li><a tabindex="0" class="menu1" style="display:none;color:red;" href="<?php  echo base_url();  ?>index.php/Recharge">Recharge</a></li>        
                  <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/replacement">Replacement</a></li>          
                  <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/stocks">Stocks</a></li>          
                </ul>
              </li>

              
              <li class="dropdown">
                <a tabindex="0" class="menu1" style="display:none;" data-toggle="dropdown">Reports<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                      <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/rptledger">Ledger</a></li>
                      <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/rptledgeritem">Ledger Items</a></li>
                      <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/rptitemspurchaseandsold">Items Purchase And Sold</a></li>
                      <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/rptitemspurchaseandsoldpaging">Items Purchase And Sold (Paging)</a></li>
                      <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/rptdues">Dues</a></li>
                      <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/rptsearch">Search</a></li>
                      <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/rptdaybook">Day Book</a></li>
                    </ul>
              </li>

              <li class="dropdown">
                <a tabindex="0" class="menu1" style="display:none;" data-toggle="dropdown">Tools<span class="caret"></span></a>

                <!-- role="menu": fix moved by arrows (Bootstrap dropdown) -->
                    <ul class="dropdown-menu" role="menu">
                      <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/user">Create Users</a></li>
                      <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/right">User Rights</a></li>
                      <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/changepwdadmin">Reset Password</a></li>
                      <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/backupdata">Backup Data</a></li>
                      <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/adminrights">Admin Rights</a></li>
                      <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/duplicates">Duplicates</a></li>
                      <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/duplicatecustomers">Duplicate Customers</a></li>
                      <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/addressbook">Address Book</a></li>
                      <li class="divider"></li>
                      <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/conclusions">Conclusions</a></li>
                      <li class="divider"></li>
                      <li><a tabindex="0" class="menu1" style="display:none; color:red;" href="<?php  echo base_url();  ?>/index.php/todo">To Do List</a></li>
                      <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/dailycash">Daily Cash</a></li>
                      <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/family">Family</a></li>
                      <li><a tabindex="0" class="menu1" style="display:none;" href="<?php  echo base_url();  ?>/index.php/familytree" target="_blank">Family Tree</a></li>
                    </ul>
              </li> 
                <?php
                    //echo form_input('txtEval', '', "class='form-control' id='txtEval' style='background-color:yellow;' maxlength=100 autocomplete='off'");
                ?>

            </ul>

            <ul class="nav navbar-nav navbar-right" style="padding-right:25px;">
              <li class="dropdown">
              <a href="#" tabindex="0" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-user"> <?php echo session('userId') ?> <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a  tabindex="0" href="<?php  echo base_url();  ?>/index.php/Login_controller/logout">Logout <span class="glyphicon glyphicon-log-out"></span></a></li>
                  <li class="divider"></li>
                  <li><a  tabindex="0" href="<?php  echo base_url();  ?>/index.php/changepwd">Change Password</a></li>
                </ul>
              </li>
            </ul>

            
            <ul class="nav navbar-nav navbar-right" style="padding-right:25px;">
              <li class="dropdown">
              <input type="text" class="form-control" style="margin-top: 10px; width: 80px;" id='txtMenuEvalResult' disabled="yes">
              </li>
            </ul>
            <ul class="nav navbar-nav navbar-right" style="padding-right:25px;">
              <li class="dropdown">
              <input type="text" class="form-control" style="margin-top: 10px;" id='txtMenuEval' maxlength=100 placeholder="calc">
              </li>
            </ul>
            <ul class="nav navbar-nav navbar-right" style="padding-right:25px;">
              <li class="dropdown">
                <a  tabindex="0" href="<?php  echo base_url();  ?>/index.php/rptledger"><span style="padding: 5px 10px;" class="label label-default">Ledger</span></a>
              </li>
              <li class="dropdown">
                <a  tabindex="0" href="<?php  echo base_url();  ?>/index.php/sale"><span style="padding: 5px 10px;" class="label label-primary">SV</span></a>
              </li>
              <li class="dropdown" >
                <a tabindex="0" href="<?php  echo base_url();  ?>/index.php/purchase"><span style="padding: 5px 10px;" class="label label-success">PV</span></a>
              </li>
              <li class="dropdown">
                <a  tabindex="0" href="<?php  echo base_url();  ?>/index.php/paymentreceipt"><span style="padding: 5px 10px;" class="label label-warning">PR</span></a>
              </li>
              <li class="dropdown">
                <a  tabindex="0" href="<?php  echo base_url();  ?>/index.php/rptdues"><span style="padding: 5px 10px;" class="label label-default">Dues</span></a>
              </li>
              <li class="dropdown">
                <a  tabindex="0" href="<?php  echo base_url();  ?>/index.php/dailycash"><span style="padding: 5px 10px;" class="label label-primary">DC</span></a>
              </li>
            </ul>

            <ul class="nav navbar-nav navbar-right" style="padding-right:5px;">
              <li class="dropdown">
              <a href="#"><span style="padding: 5px 10px;" id="spanNotificationAsli" class="label label-danger" onclick="notificationPadhLiya();">0</span></a>
              </li>
            </ul>

          </div>
        </nav>
        <script>  
          var notificationCount = 0;


         </script>
    </div> <!-- end of 'container' div   -->



            
            
