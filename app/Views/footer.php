
</div>
    <div class="environment">

        <p>Page rendered in {elapsed_time} seconds.</p>

        <p>Environment: <?= ENVIRONMENT ?></p>

    </div>

    <div id="dialog" style="display: none;">
        Something is wrong... pls check...
    </div>

    <div class="modal" id="modalAjaxErrorMsg" role="dialog" data-backdrop="static">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header" style="background: #D9534f; color: #fff;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">ERROR</h4>
          </div>
          <div class="modal-body">
            <p id="paraAjaxErrorMsg"></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"> OK </button>
          </div>
        </div>
      </div>
    </div>
</body>



    <script type="text/javascript">
        //high lighting selected row
        // $("#tbl1 tr:gt(0)").on("click", highlightRow);
        // function highlightRow()
        // {
        //     var tableObject = $(this).parent();
        //     // if($(this).index() > 0)
        //     {
        //         var selected = $(this).hasClass("highlight");
        //         tableObject.children().removeClass("highlight");
        //         if(!selected)
        //             $(this).addClass("highlight");
        //     }
        // }




        // function highlightRowAlag()
        // {
        //     var tableObject = $(this).parent();
        //     // if($(this).index() > 0)
        //     {
        //         var selected = $(this).hasClass("highlightAlag");
        //         tableObject.children().removeClass("highlightAlag");
        //         if(!selected)
        //             $(this).addClass("highlightAlag");
        //     }
        // }

        $(document).prop('title', "BS: " + vModuleName);
    </script>
</html>

<div id="divPrint" style="color: white;">
    
</div>

<div id="divHeader" class="header">h.</div>
<div id="divFooter" class="footer">f.</div>

<script type="text/javascript">
setInterval(loadIntervalJobs, 400000 );

function loadIntervalJobs() 
    {
        var controller='DailyCash_Controller';
        var base_url='<?php echo site_url();?>';
        $.ajax({
                'url': base_url + '/' + controller + '/loadIntervalJobs',
                'type': 'POST',
                 'global': false, /// not calling hourGlass function
                'dataType': 'json',
                'data': {
                            'dtFrom': 'ff'
                            , 'dtTo': 'gg'
                            , 'userRowId': 'tt'
                        },
                'success': function(data)
                {
                    if(data)
                    {
                        if( data['dailyCashInEntry'] == "notEntered" )
                        {
                            alert ("Daily Cash Entry IN not entered");
                        }
                        
                    }
                }
        });

      
      
      // notificationCount++;
    }
</script>