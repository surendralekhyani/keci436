<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Billing System</title>
    <script type="text/javascript" src="<?php echo base_url(); ?>/public/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>/public/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>/public/js/jquery.blockUI.js"></script>
    <link rel='stylesheet' href='<?php echo base_url(); ?>/public/css/bootstrap.css'>

    <style type="text/css">
        #tree1 {
            width: 100%;
            padding: 10px;
            float: left;
        }


        .myHead {
            text-align: center;
        }

        .boc {
            width: 100%;
            padding: 10px;
            float: left;
            /*padding: 20px;*/
            background-color: #f5f5f5;
            margin: auto;
            margin-top: 10px;
            border-radius: 15px;
        }

        .yellow {
            color: #337ab7;
        }

        .black {
            color: black;
        }
    </style>

</head>

<body style="padding-top:35px;">


    <script type="text/javascript">
        var controller = 'FamilyTree_Controller';
        var base_url = '<?php echo site_url(); ?>';
        vModuleName = "Family Tree";
    </script>

    <div class="container-fluid" style="width: 90%; ">
        <div class="row boc">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 myHead">
                <h1><span style="color:#C70039;">Folding Tree Structure of Lekhyani Family:</span></h1>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 myHead">
                <span style="color:#3498DB;">Nukh: Dhurgia</span>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 myHead">
                <span style="color:#626567">Village: GAJRA, Town: Tharu Shah, Dist.: Nawab Shah, State: Sindh, Country: India (Now Pakistan)</span>
            </div>
        </div>
        <div class="row boc">
            <div id="divTree" class="col-md-12">
                <?php
                ?>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        $(document).ready(function() {
            var records = '<?php echo json_encode($records); ?>';
            var records = records.replace(/(\r\n|\n|\r)/gm, ", "); ///Multilinse of Address field with comma replce
            var records = $.map(JSON.parse(records), function(obj) {
                return {
                    label: obj.name,
                    familyRowId: obj.familyRowId,
                    parentRowId: obj.parentRowId,
                    contactNo: obj.contactNo,
                    address: obj.address,
                    remarks: obj.remarks
                }
            });
            // console.log(records);
            $("#divTree").append('<ul id="ulFirst" style="cursor:context-menu; color:#337ab7; font-size:14pt; line-height: 1.4; font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;" class="lstRoot"><li id="' + records[0]['familyRowId'] + '">' + records[0]['label'] + ' <span style="color: black; font-size:10pt;">' + records[0]['contactNo'] + '</span>' + ' <span style="color: black; font-size:10pt;">' + records[0]['address'] + '</span>' + ' <span style="color: black; font-size:10pt;">' + records[0]['remarks'] + '</span>' + '</li></ul>');
            for (i = 1; i < records.length; i++) {
                parentRowId = "#" + records[i]['parentRowId'];
                // console.log(parentRowId);
                $(parentRowId).append('<ul class="lstRoot"><li id="' + records[i]['familyRowId'] + '" contactNo="' + records[i]['contactNo'] + '" address="' + records[i]['address'] + '" remarks="' + records[i]['remarks'] + '">' + records[i]['label'] + ' <span style="color: black; font-size:10pt;">' + records[i]['contactNo'] + '</span>' + ' <span style="color: black; font-size:10pt;">' + records[i]['address'] + '</span>' + ' <span style="color: black; font-size:10pt;">' + records[i]['remarks'] + '</span>' + '</li></ul>');

            }
            $('.lstRoot').off();
            $('.lstRoot').on('click', showHideChildren);

            // //// hidin children
            // $('#ulFirst li:gt(3)').each(function(idx, li) {
            //     {
            //         $(this).find("ul").slideToggle();
            //     }
            // });


        });

        function showHideChildren() {
            event.stopPropagation();
            // $(this).find("ul").slideToggle();
            $(".lstRoot").removeClass("black").addClass("yellow");
            $(this).addClass("black");
            $(this).find("ul").addClass("black");
        }



        $(document).ready(function() {
            $('#ulFirst li:gt(3)').each(function(idx, li) {
                // console.log( $(this).children().first().text()  );
                if ($(this).find('ul').length > 0) {
                    // console.log(li );
                    // console.log( $(this).find('ul').length);
                    // console.log(li.firstChild.innerText);
                }
            });
        });
    </script>