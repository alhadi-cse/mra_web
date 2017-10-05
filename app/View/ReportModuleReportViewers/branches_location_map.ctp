<div id='map_info' class="map_info">
    <style>

        body {
            overflow: hidden;
        }
        h2 {
            margin: 0;
            padding: 5px;
            font: normal 16px/1.5 Roboto,Arial,sans-serif;
        }
        h3 {
            margin: 0;
            padding: 5px;
            font: normal 15px/1.4 Roboto,Arial,sans-serif;
        }
        h4 {
            margin: 0;
            padding: 3px 5px;
            font: normal 13px/1.3 Roboto,Arial,sans-serif;
        }
        .iw-title {
            margin: 3px;
            padding: 10px;
            color: white;

            background-color: #3be;
            border-radius: 3px 3px 0 0;
        }
        .info-div {
            width: auto;
            max-width: 980px;
            color: #333;
        }
        .info-title {
            margin-top: -7px;
            color: #333;
            font: 600 15px/1.5 'Open Sans Condensed',Roboto,Arial,sans-serif;
            text-align: center;
            text-transform: capitalize;
        }
        .gm-style-iw h2, .gm-style-iw h3 {
            font-size: 14px;
        }
        .gm-style-iw h4, .gm-style-iw h5 {
            font-size: 13px;
        }
        #mapLabelPanel {
            display: none;
        }

        .map_info, .map_content {
            z-index: 99;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;

            display: block;
            width: 100%;
            height: 100vh;
            margin: 0;
            padding: 0;
            background: rgba(0, 0, 0, 0.15) none repeat scroll 0 0;
        }
        .map_content {
            background: #fff none repeat scroll 0 0;
        }
        .coordinate_info {
            z-index: 104;
            position: absolute;
            left: 75px;
            bottom: 3px;
            border: 1px solid #e3e5e7;
            margin: 0;
            padding: 3px 5px;
            color: #037;
            font: 11px/1.0 "Source Sans Pro",Helvetica,sans-serif;
            cursor: default;
            background: rgba(245, 250, 253, 0.5) none repeat scroll 0 0;
        }
        .map_label_panel {
            z-index: 108;
            display: block;
            color: #333;
            font: normal 13px/1.3 Roboto, Helvetica, Arial, sans-serif;
        }
        .map_label {
            z-index: 105;
            display: inline-block;
            color: #333;
            font: normal 13px/1.3 Roboto, Helvetica, Arial, sans-serif;
            vertical-align: middle;
        }

        .org_info {
            z-index: 101;
            position: fixed;
            top: 0;
            right: 0;
            float: right;
            display: block;
            min-width: 40%;
            margin: 10px;
            padding: 0;
            text-align: right;
        }
        .org_opt {
            position: static !important;
            width: 350px;
            margin: 0;
            padding: 2px;
        }

        .map_btns_content {            
            z-index: 101;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;

            width: 200px;
            height: auto;
            margin: 10px auto;
            padding: 0;

            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        .map_btns {
            display: inline-block;            
            border: 2px solid rgba(255, 255, 255, 0.01);
            width: 18px;
            height: 18px;
            margin: 0 3px;
            padding: 5px 7px;
            font: normal 23px/1.0 Roboto, Helvetica, Arial, sans-serif;
            color: #777;
            text-align: center;
            background-color: #fff;
            cursor: pointer;

            border-radius: 3px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.25);

            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;

            transition: background-size 0.15s;
        }
        .map_btns:hover {
            border: 2px solid #f24;
            color: #fa2413;
            background-color: #eee;
        }
        .map_full_extent {
            background: #fff url("./img/map_full_extent.svg") no-repeat center center/80% auto;
        }
        .map_close {
            background: #fff url("./img/close.png") no-repeat center center/60% auto;
        }
        .filter_option {
            background: #fff url("./img/search.png") no-repeat center center/80% auto;
        }
        .map_full_extent:hover {
            background-size: 95% auto;
        }
        .map_close:hover {
            background-size: 75% auto;
        }
        .filter_option:hover {
            background-size: 95% auto;
        }

        .map_legend, .map_opt_content {
            z-index: 101;
            position: absolute;
            top: 85px;
            border: 1px solid #e5e7e8;
            width: auto;
            min-width: 200px;
            margin: 0;
            padding: 0;
            overflow: auto;
            font: bold 13px/1.5 Roboto, Helvetica, Arial, sans-serif;
            background: rgba(255, 255, 255, 0.85) none repeat scroll 0 0;

            -webkit-box-shadow: 0 3px 3px rgba(12, 13, 15, 0.23);
            -moz-box-shadow: 0 3px 3px rgba(12, 13, 15, 0.23);
            -ms-box-shadow: 0 3px 3px rgba(12, 13, 15, 0.23);
            box-shadow: 0 3px 3px rgba(12, 13, 15, 0.23);

            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            -ms-border-radius: 3px;
            border-radius: 3px;

            transition-property: left, right;
            transition-duration: 0.5s, 0.5s;
            transition-delay: 0.3s, 0.3s;
        }

        .map_legend_header {
            border-bottom: 1px solid #f2f3f5;
            width: 100%;
            margin: 0;
            padding: 0;
            line-height: 30px;
            cursor: move;
            background-color: #eff0f3;

            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .map_legend_title {
            display: inline-block;
            width: auto;
            margin: 0;
            padding: 5px 8px;
            color: #27a;
            font: bold 14px/1.5 Roboto, Helvetica, Arial, sans-serif;
            text-align: center;
            text-transform: capitalize;
            cursor: move;

            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .map_legend_close {
            display: inline-block;
            float: right;
            border: 0 none;
            width: 13px;
            margin: 5px 7px 0 0;
            padding: 0;
            text-align: center;
            color: #555;
            font: normal 21px/1.0 Roboto, Helvetica, Arial, sans-serif;
            cursor: pointer;
            background: rgba(0, 0, 0, 0) none repeat scroll 0 0;

            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .map_legend_close:hover {
            color: #fa2413;
        }
        .map_legend_infos {
            border: 0 none;
            width: auto;
            margin: 5px;
            padding: 1px 5px;
            max-height: 70vh;
            overflow: auto;
            font: normal 13px/1.5 Roboto, Helvetica, Arial, sans-serif;
        }
        .map_legend_infos label {
            font-size: 13px;
            font-family: Roboto, Helvetica, Arial, sans-serif;
        }
        .map_legend_label {
            display: inline-block;
            margin: 0;
            padding: 3px;
            color: #333;
            font: bold 13px/20px Roboto, Helvetica, Arial, sans-serif;
            vertical-align: bottom;
        }
        .map_legend_color {
            display: inline-block;
            border: 1px solid #aaa;
            width: 20px;
            height: 20px;
            margin: 0 3px;
            padding: 0;
            vertical-align: middle;
        }
        .map_legend_color.circle {
            display: inline-block;
            border: 1px solid #aaa;
            width: 18px;
            height: 18px;
            margin: 0 3px;
            padding: 0;
            vertical-align: middle;
            border-radius: 20px;
        }

        .map_legend_close {
            display: inline-block;
            float: right;
            border: 0 none;
            width: 13px;
            margin: 3px 7px 0 0;
            padding: 0;
            text-align: center;
            color: #07c;
            font: bold 23px/1.0 Roboto, Helvetica, Arial, sans-serif;
            cursor: pointer;
            background: rgba(0, 0, 0, 0) none repeat scroll 0 0;

            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .map_legend_close:hover {
            color: #fa2413;
        }


        .legend_btn_content {
            z-index: 101;
            position: absolute;
            top: 85px;
            border: 0 none;
            padding: 5px 6px;
            cursor: pointer;
            background: #fff none repeat scroll 0 0;
            box-shadow: 0 1px 3px 4px rgba(0, 0, 0, 0.3);

            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            -ms-border-radius: 3px;
            border-radius: 3px;

            transition-property: left, right;
            transition-duration: 0.2s, 0.2s;
            transition-delay: 0.2s, 0.2s;
        }
        .legend_btn_left {
            right: -10px;
        }
        .legend_btn_left:hover {
            right: -5px !important;
        }
        .legend_btn_left_img {
            width: 24px;
            height: 24px;
            margin-right: 10px;
            text-align: left;
            background: #fff url("./img/arrow_left.png") no-repeat center left/100% auto;
        }

        .legend_btn_right {
            left: -10px;
        }
        .legend_btn_right:hover {
            left: -5px !important;
        }
        .legend_btn_right_img {
            width: 24px;
            height: 24px;
            margin-left: 10px;
            text-align: right;
            background: #fff url("./img/arrow_right.png") no-repeat center right/100% auto;
        }

        .map_legend_btn {
            color: #047;
            cursor: pointer;
            font: bold 21px/1.0 Roboto, Helvetica, Arial, sans-serif;

            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .map_legend_btn:hover {
            color: #258;
        }

        .map_opt_content {
            top: 10px;
            left: auto;
            right: auto;
            padding: 5px 10px;
            color: #235;
            font: normal 14px/22px Roboto, Arial, sans-serif;
        }
        .map_opt_content span {
            color: #247;
            font: bold 15px/22px Roboto, Arial, sans-serif;
        }
        .branch_count {
            clear: both;
            margin: 0;
            padding: 0;
            color: #247;
            font: bold 14px/22px Roboto, Arial, sans-serif;
            text-align: left;
        }

    </style>

    <div id="map" class="map_content">
    </div>

    <div id="map_cord_info" class="coordinate_info"></div>

    <div id="legend_info_btn" class="legend_btn_content legend_btn_right" onclick="javascript: legend_open_close('legend_info', 'open', 'left');" title="Show Map Information">
        <div class="legend_btn_right_img"></div>
    </div>
    <div id="legend_info" class="map_legend" style="left:-3px;">
        <div class="map_legend_header">
            <div id="legend_info_title" class="map_legend_title"></div>
            <div class="map_legend_close" onclick="javascript: legend_open_close('legend_info', 'close', 'left');" title="Close Map Information">&times;</div>
        </div>
        <div id="map_legend_infos" class="map_legend_infos"></div>        
    </div>

    <div id="legend_btn" class="legend_btn_content legend_btn_left" onclick="javascript: legend_open_close('legend', 'open', 'right');" title="Show Map Legend">
        <div class="legend_btn_left_img"></div><!--<span class="map_legend_btn" onclick="javascript: legend_open_close('legend', 'open', 'right');" title="Show Map Legend">&#9776;</span>--> 
    </div>
    <div id="legend" class="map_legend" style="right:-3px;">
        <div class="map_legend_header">
            <div id="legend_title" class="map_legend_title"></div>
            <div class="map_legend_close" onclick="javascript: legend_open_close('legend', 'close', 'right');" title="Close Map Legend">&times;</div>
        </div>
        <div id="map_legend_colors" class="map_legend_infos"></div>
    </div>

    <div class="map_btns_content">
        <div id="map_center" class="map_btns map_full_extent" title="Full extent the map"></div>
        <div id="map_close" class="map_btns map_close" title="Close the map"></div>
        <div id="filter_option" class="map_btns filter_option" title="Filter the Map" onclick="option_modal_open('option_opt');"></div>
    </div>


    <!--    onclick="javascript: legend_open_close('legend_info', 'close', 'left');" 
        onclick="javascript: legend_open_close('legend', 'close', 'right');"-->

    <div class="map_opt_content" style="left:10%;">
        <label><input type="checkbox" id="legend_info_opt" checked="checked" title="Show/Hide Map Information" />Map Information</label>
        <label><input type="checkbox" id="legend_opt" checked="checked" title="Show/Hide Map Legend" />Map Legend</label>
        <label><input type="checkbox" id="map_label_opt" title="Show/Hide Map Label" />Map Label</label>
    </div>
    <div class="map_opt_content" style="right:25%;">        
        <div id="branch_count" class="branch_count"></div>
        <div id="selected_info"></div>        
    </div>

    <div id="option_opt_bg" class="modal-bg">
        <!-- org_info-->
        <div id="option_opt" class="modal-content" style="width:600px; margin:85px auto 0 auto;">

            <div id="option_title" class="modal-title">
                <span class="modal-title-txt">Map Filter Options</span>
                <button class="close" onclick="if (confirm('Are you sure to Cancel ?'))
                            option_modal_close('option_opt');
                        return false;">✖</button>
            </div>
            <div id="option_content" style="width:auto; height:auto; max-height:470px; max-height:75vh; margin:0; padding:7px; overflow:auto; cursor:default;">

                <fieldset style="margin-top:0;">
                    <table style="width:100%;">
                        <tr>
                            <td colspan="2" style='vertical-align:top; text-align:left; padding:3px 0;'>
                                <fieldset style="margin-top:0;">
                                    <legend>Admin Boundary</legend>
                                    <?php
                                    $bd_admins = array('none' => 'None', 'dist' => 'District', 'upaz' => 'Upazila');
                                    echo $this->Form->input('', array('name' => 'bd_info', 'type' => 'radio', 'options' => $bd_admins, 'escape' => false, 'legend' => false, 'div' => false, 'label' => true));
                                    ?>
                                    <div>
                                        <select id="bd_info_district" style="width:175px; height:25px; margin:5px 10px; padding:0; display:none;">
                                            <option value="">--- Select District ---</option>
                                        </select>
                                        <select id="bd_info_upazila" style="width:175px; height:25px; margin:5px; padding:0; display:none;">
                                            <option value="">--- Select Upazila ---</option>
                                        </select>
                                    </div>
                                </fieldset>
                                <?php
                                //$branch_types['0'] = 'All Type';
                                //array_unshift($branch_types, array('0' => 'All Type'));
                                $branch_types = array('0' => 'All Type') + $branch_types;
                                echo $this->Form->input('', array('name' => 'branch_types', 'type' => 'radio', 'options' => $branch_types, 'value' => $branch_type_id, 'escape' => false, 'legend' => 'Branch Office Type', 'div' => false, 'label' => true));
                                ?>
                            </td>
                            <td colspan="2" style='vertical-align:top; text-align:left; padding:3px 0;'>                        

                            </td>
                        </tr>

                        <tr>
                            <td style='vertical-align:top; text-align:left; width:360px; padding:3px 0;'>
                                <?php
                                echo $this->Form->input('', array('id' => 'org_list', 'type' => 'select', 'class' => 'org_opt', 'style' => 'position:absolute;left:0; width:350px; height:28px; padding:0 5px;', 'options' => $org_list, 'value' => $org_id, 'empty' => '--------- All MFIs --------', 'escape' => false, 'div' => false, 'label' => false))
                                ?>
                            </td>
                            <td style='vertical-align:top; text-align:left; padding:3px 0;'>
                                <?php
                                echo "<span style='margin:0; font-weight:bold;'>filter:<input type='text' id='txtFilter_org_list' style='width:100px; height:28px; margin-left:5px; padding:0 5px;'></span>";
                                ?>
                            </td>
                        </tr>
                    </table>
                    <!--            <div id="branch_count" class="branch_count"></div>-->
                </fieldset>
            </div>
        </div>
    </div>


    <!--    <div class="org_info" style="display:none">
            <div class=""></div>
    
        </div>-->

    <script>

        $(function () {
            draggable_modal('option_title', 'option_opt', 'option_opt_bg');
        });

        var isOpen = false;

        function option_modal_open(content) {
            if (!isOpen)
                modal_open(content, 50);
            isOpen = true;
        }

        function option_modal_close(content) {
            if (isOpen)
                modal_close(content);
            isOpen = false;
        }

        //$('#filter_option').css('display', 'none');
        //$('#filter_option').prop('disabled', 'disabled');

    </script>


    <script>

        function legend_open_close(legend_id, open_close_opt, prop_opt) {
            if (open_close_opt == 'open') {
                $('#' + legend_id + '_btn').css(prop_opt, '-' + ($('#' + legend_id + '_btn').outerWidth(true) + 10) + 'px');
                $('#' + legend_id).css(prop_opt, '-4px');
                $('#' + legend_id + '_opt').prop('checked', true);
            } else if (open_close_opt == 'close') {
                $('#' + legend_id).css(prop_opt, '-' + ($('#' + legend_id).outerWidth(true) + 5) + 'px');
                $('#' + legend_id + '_btn').css(prop_opt, '-10px');
                $('#' + legend_id + '_opt').prop('checked', false);
            }
        }

        var map;
        var bd_center = null;
        var curr_poly = null;
        var markers = [];
        var all_map_poly = [];


        $(function () {

            $('#org_list').filterByText($('#txtFilter_org_list'), true);

            set_basic_opts();
            set_admin_boundary();

            $("input[name='bd_info']").on("change", function () {
                set_admin_boundary();
                return false;
            });
            $("#org_list").on("change", function () {
                set_branch_data();
                return false;
            });
            $("input[name='branch_types']").on("change", function () {
                set_branch_data();
                return false;
            });
            $('#txtFilter_org_list').on("change", function () {
                if ($("#org_list").children('option').length === 1)
                    set_branch_data();
                return false;
            });
        });

        function set_basic_opts() {
            $("#map_close").on('click', function () {
                if (!confirm('Are you sure to close map ?'))
                    return;
                $('#map_info').fadeOut(500, function () {
                    $(this).remove();
                });
            });
            $("#legend_info_opt").change(function () {
                if ((this).checked) {
                    legend_open_close('legend_info', 'open', 'left');
                } else {
                    legend_open_close('legend_info', 'close', 'left');
                }
                return false;
            });
            $("#legend_opt").change(function () {
                if ((this).checked) {
                    legend_open_close('legend', 'open', 'right');
                } else {
                    legend_open_close('legend', 'close', 'right');
                }
                return false;
            });
            $("#map_label_opt").change(function () {
                if ((this).checked) {
                    $("#mapLabelPanel").fadeIn(500);
                } else {
                    $("#mapLabelPanel").fadeOut(500);
                }
                return false;
            });
            if (typeof (google) == 'undefined') {
                legend_open_close('legend', 'close', 'right');
                $("#legend").css('right', '-1000px');
                legend_open_close('legend_info', 'close', 'left');
                $("#legend_info").css('left', '-1000px');
                alert('Map Loading Failed !');
                $("#busy-indicator").fadeOut();
                return false;
            }

            bd_center = new google.maps.LatLng(23.777777, 90.444444);
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 7,
                center: bd_center,
                mapTypeId: 'terrain',
                styles: mapStyle
            });
            $("#map_center").on('click', function () {
                map.setCenter(bd_center);
                map.setZoom(7);
            });
            google.maps.event.addListenerOnce(map, "mousemove", function (evt) {
                $("#map_cord_info").html(evt.latLng.lat().toFixed(6) + ", " + evt.latLng.lng().toFixed(6));
            });
            google.maps.event.addListenerOnce(map, "idle", function () {
                $("#mapLabelPanel").css('display', $("#map_label_opt").prop('checked') ? 'block' : 'none');
            });
            return true;
        }

        function set_branch_data() {

            var orgId = $("#org_list").val();
            var brancheTypeId = $("input[name='branch_types']:checked").val();           
            var adminDist = $("#bd_info_district").val();
            var adminUpz = $("#bd_info_upazila").val();
            
            orgId = (orgId || orgId != '') ? orgId : '0';
            adminDist = (adminDist || adminDist != '') ? adminDist : '0';
            adminUpz = (adminUpz || adminUpz != '') ? adminUpz : '0';
            //brancheTypeId = (brancheTypeId || brancheTypeId != '') ? brancheTypeId : '0';
            if(adminDist==0 && brancheTypeId==0){ 
                
                if($("#org_list").val()!=""){
                //$("#1").prop("checked", true);
                //alert("Please Select at least one District Otherwise it takes more time to show all Branch");
                //return;               
               }else{
                $("#1").prop("checked", true);
                alert("Please Select Admin Boundary or Organization Otherwise it takes more time to show all Branches Data");
                return;
               }
                
                
            }
            //alert("Org Id:"+orgId+ ", branche Id:"+brancheTypeId); 
            //alert("Org Id:"+orgId+ ", brancheTypeId:"+brancheTypeId+", AdminDist : "+adminDist+", AdminUpz : "+adminUpz);
            $.ajax({
                async: true,
                type: "post",
                dataType: "html",
                url: document.location + "ReportModuleReportViewers\/get_branches_location\/" + orgId + "\/" + brancheTypeId + "\/" + adminDist+ "\/" + adminUpz,
                //url: document.location + "ReportModuleReportViewers\/get_loan_info\/" + orgId + "\/" + brancheTypeId,
                beforeSend: function (XMLHttpReq) {
                    $("#busy-indicator").fadeIn();
                },
                complete: function (XMLHttpReq, textStat) {
                    $("#busy-indicator").fadeOut();
                },
                success: function (branch_data, status) {
//                    alert(branch_data);
//                    return;
                    if (branch_data && branch_data.length > 0) {
                        try {
                            set_branch_location(JSON.parse(branch_data));

                        } catch (e) {
                            alert("Error trying to parse JSON." + e.message);
                        }
                    }
                },
                error: function (branch_data, status) {
                    alert('Error! \n' + status);
                    $("#busy-indicator").fadeOut();
                }
            });
        }
        
        function map_selected_info() {
            $("#selected_info").empty();
//            if ($('input:radio[name=bd_info]:checked').val() == 'upaz') {
//                $("#selected_info").append('<p>Admin: Upazila</p>');
//            }
//            if ($('input:radio[name=bd_info]:checked').val() == 'dist') {
//                $("#selected_info").append('<p>Admin: District</p>');
//            }

            var upazName = $("#bd_info_upazila").find(":selected").text();
            var dstName = $("#bd_info_district").find(":selected").text();

            if ($("#bd_info_upazila").val()) {
                $("#selected_info").append('<p>Selected Upazila: <span>' + upazName + ', ' + dstName + '</span></p>');
            } else if ($("#bd_info_district").val()) {
                $("#selected_info").append('<p>Selected District: <span>' + dstName + '</span></p>');
            }
        }

        var mapStyle = [
            {
                "featureType": "administrative.land_parcel",
                "elementType": "labels",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "labels.text",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "poi.business",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "labels.icon",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "road.local",
                "elementType": "labels",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "transit",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            }
        ];

        function set_admin_boundary() {
            $("#busy-indicator").fadeIn();

            $("#mapLabelPanel").empty();
            if (all_map_poly && all_map_poly.length > 0) {
                for (var pc = 0; pc < all_map_poly.length; pc++) {
                    all_map_poly[pc].map_poly.setMap(null);
                }
            }
            all_map_poly = [];
            //all_map_poly.push(map_poly);

            if (markers || markers.length > 0)
                set_legend_info();
            var admin_code = $("input[name='bd_info']:checked").val();
            if (!admin_code || !bd_all[admin_code]) {
                $("#busy-indicator").fadeOut();
                return;
            }

            var bd_admin = bd_all[admin_code];
            var admin_field_code = admin_code + '_code';
            var admin_field_name = admin_code + '_name';
            var polyDefaultOptions = {
                zIndex: 101,
                strokeColor: "#553322",
                fillColor: "#FFFFFF",
                strokeWeight: 1.0,
                strokeOpacity: 0.7,
                fillOpacity: 0.25
            };
            var polyHoverOptions = {
                zIndex: 102,
                strokeColor: "#553322",
                strokeWeight: 2.0,
                strokeOpacity: 0.8,
                fillOpacity: 0.5
            };
            var polySelectedOptions = {
                zIndex: 103,
                strokeColor: "#0055AA",
                strokeWeight: 2.5,
                strokeOpacity: 0.9,
                fillOpacity: 0.9
            };
            var labelOptions = {
                zIndex: 105,
                boxClass: "map_label",
                disableAutoPan: false,
                pixelOffset: new google.maps.Size(-25, -10),
                closeBoxURL: "",
                isHidden: false,
                pane: "mapPane",
                paneId: "mapLabelPanel",
                paneClass: "map_label_panel",
                paneZIndex: 108,
                enableEventPropagation: true
            };

            bd_admin.features.forEach(function (feature) {
                try {
                    var admin_geom = feature.geometry;
                    var admin_props = feature.properties;

                    var curr_admin_name = admin_props[admin_field_name];
                    var curr_admin_code = admin_props[admin_field_code];
                    //"CNT_LAT","CNT_LONG"
                    var poly_center = new google.maps.LatLng(admin_props["CNT_LAT"], admin_props["CNT_LONG"]);
                    var polyOptions = $.extend(true, {}, polyDefaultOptions);
                    polyOptions.paths = GetGeoPaths(admin_geom.coordinates, (admin_geom.type === "MultiPolygon"));
                    var map_poly = new google.maps.Polygon(polyOptions);
                    map_poly.setMap(map);
                    //all_map_poly.push(map_poly);
                    //"THACODE":"100409","THANAME":"Amtali","CNT_LAT":22.0608388543,"CNT_LONG":90.1923356488,"DISTCODE":"04","DIVCODE":"10"
                    //all_map_poly.push({geo_code: curr_admin_code, map_poly: map_poly});

                    all_map_poly.push({center: poly_center, div_code: admin_props.div_code, dist_code: admin_props.dist_code, upaz_code: admin_props.upaz_code, map_poly: map_poly});

                    (function (map_poly) {
                        map_poly.addListener("mousemove", function (evt) {
                            $("#map_cord_info").html(evt.latLng.lat().toFixed(6) + ", " + evt.latLng.lng().toFixed(6));
                        });
                        map_poly.addListener("mouseover", function (evt) {
                            if (this.strokeWeight === polySelectedOptions.strokeWeight)
                                return;
                            this.setOptions(polyHoverOptions);
                        });
                        map_poly.addListener("mouseout", function (evt) {
                            if (this.strokeWeight === polySelectedOptions.strokeWeight)
                                return;
                            this.setOptions(polyDefaultOptions);
                        });
                    })(map_poly);
                    labelOptions.content = curr_admin_name;
                    labelOptions.position = poly_center;
                    var polygonLabel = new InfoBox(labelOptions);
                    polygonLabel.open(map);
                } catch (e) {
                }
            });

            if (markers.length > 0) {
                for (mc = 0; mc < markers.length; mc++) {
                    if (markers[mc].marker.getMap() == null)
                        markers[mc].marker.setMap(map);
                }
            }

            map_selected_info();
            $("#busy-indicator").fadeOut();
        }

        function set_legend_info() {

            $("#legend_info_title").empty();
            $("#map_legend_infos").empty();
            //var admin_code = $('#bd_info').val();
            var admin_code = $("input[name='bd_info']:checked").val();
            var admin_name = $("input[name='bd_info']:checked").next('label').text(); //$('#bd_info option:selected').text();
            //alert(admin_code);

            if (!admin_code || !bd_all[admin_code]) {
                legend_open_close('legend_info', 'close', 'left');
                $("#legend_info").css('left', '-1000px');
                return;
            }

            var bd_admin = bd_all[admin_code];
            var admin_field_code = admin_code + '_code';
            var admin_field_name = admin_code + '_name';
            var total_branch_count = 0;
            var curr_admin_code, curr_admin_name, curr_value;
            bd_admin.features.forEach(function (feature) {
                try {
                    var admin_props = feature.properties;
                    curr_admin_name = admin_props[admin_field_name];
                    curr_admin_code = admin_props[admin_field_code];
                    // {
                    //                        var curr_dist_codes = []; //all the dist of this div 
                    //                        curr_admin_code
                    //                        curr_value = markers.filter(marker => $.inArray(marker.dist_code, curr_dist_codes) > -1).length;

                    //                        var curr_dist_codes = []; //all the dist of this div 
                    //                        curr_admin_code
                    //                        curr_value = markers.filter(marker => $.inArray(marker.dist_code, curr_dist_codes) > -1).length;
                    //} 


                    try {
//                        if (admin_field_code == 'div')
//                            curr_value = markers.filter(marker => marker.dist_code == curr_admin_code).length;
//                        else
                        curr_value = markers.filter(marker => marker[admin_field_code] === curr_admin_code).length;
                    } catch (ex) {
                        curr_value = 0;
                    }

                    //                    if (admin_field_code == 'div')
                    //                        curr_value = markers.filter(marker => parseInt(marker.dist_code / 100) == curr_admin_code).length;
                    //                    else
                    //                        curr_value = markers.filter(marker => marker[admin_field_code] === curr_admin_code).length;

                    //                    if (admin_code == 'dist')
                    //                        curr_value = markers.filter(marker => marker.dist_code === curr_admin_code).length;
                    //                    else if (admin_code == 'upaz')
                    //                        curr_value = markers.filter(marker => marker.upaz_code === curr_admin_code).length;

                    //curr_value = map_data.filter(data => data. [admin_code + '_id'] === curr_admin_code).length;

                    //dist_code, upaz_code
                    //curr_value = map_data.filter(data => data.geo_code === curr_admin_code).map(data => data.data_value);

                    if (curr_value) {
                        total_branch_count += curr_value;
                        $("#map_legend_infos").append('<p>' + curr_admin_name + ' (' + curr_value + ')</p>');
                    }
                    //                    else {
                    ////                        data_value = "There is no branch";
                    ////                        polyOptions.fillColor = noDataClass;
                    ////                        polyOptions.fillOpacity = 0.25;
                    //                    }
                } catch (e) {
                }
            });
            $("#legend_info_title").append(admin_name + ' Branches Count (' + total_branch_count + ')');
        }

        function GetGeoPaths(geo_coordinates, multi_poly = false) {
            if (typeof (google) == 'undefined') {
                return;
            }

            var path = [];
            var paths = [];
            var geo_point;
            if (!multi_poly) {
                for (var ci = 0; ci < geo_coordinates.length; ci++) {
                    for (var cpc = 0; cpc < geo_coordinates[ci].length; cpc++) {
                        geo_point = new google.maps.LatLng(geo_coordinates[ci][cpc][1], geo_coordinates[ci][cpc][0]);
                        path.push(geo_point);
                    }
                }
                paths.push(path);
            } else {
                for (var pc = 0; pc < geo_coordinates.length; pc++) {
                    path = [];
                    for (var ci = 0; ci < geo_coordinates[pc].length; ci++) {
                        for (var cpc = 0; cpc < geo_coordinates[pc][ci].length; cpc++) {
                            geo_point = new google.maps.LatLng(geo_coordinates[pc][ci][cpc][1], geo_coordinates[pc][ci][cpc][0]);
                            path.push(geo_point);
                        }
                    }
                    paths.push(path);
                }
            }

            return paths;
        }

        var legend_info = {
            head_office: {fill_color: '#f53', border_color: '#035', title: 'Head Office'},
            region_office: {fill_color: '#2e7', border_color: '#f53', title: 'Area/Regional/Zonal Office'},
            branch_office: {fill_color: '#35e', border_color: '#f53', title: 'Branch Office'}
        };

        function set_branch_location(branch_data) {
            
            

            $("#legend_title").empty();
            $("#legend_info_title").empty();
            $("#map_legend_infos").empty();
            $("#map_legend_colors").empty();

            if (!branch_data || branch_data.length < 1) {
                legend_open_close('legend', 'close', 'right');
                $("#legend").css('right', '-1000px');
                legend_open_close('legend_info', 'close', 'left');
                $("#legend_info").css('left', '-1000px');
                //alert('Branch data not available !'); //RMO
                $("#busy-indicator").fadeOut();
                return;
            }
            if (!google)
                return;
            if (markers && markers.length > 0) {
                for (var mc = 0; mc < markers.length; mc++) {
                    markers[mc].marker.setMap(null);
                }
            }
            markers = [];
            var head_icon = {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 6,
                fillColor: legend_info.head_office.fill_color,
                fillOpacity: 1,
                strokeColor: legend_info.head_office.border_color,
                strokeWeight: 3,
                strokeOpacity: .7
            };
            var region_icon = {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 5,
                fillColor: legend_info.region_office.fill_color,
                fillOpacity: 1,
                strokeColor: legend_info.region_office.border_color,
                strokeWeight: 2,
                strokeOpacity: .7
            };
            var branch_icon = {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 5,
                fillColor: legend_info.branch_office.fill_color,
                fillOpacity: 1,
                strokeColor: legend_info.branch_office.border_color,
                strokeWeight: 1,
                strokeOpacity: .7
            };
            var goldStar = {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 5,
                fillColor: 'blue',
                fillOpacity: 1,
                strokeColor: 'red',
                strokeOpacity: .7,
                strokeWeight: 2

                        //                path: 'M 25,5 55,90 145,90 75,45 100,130 25,80 50,130 75,45 5,90 95,90 z',
                        //                fillColor: 'yellow',
                        //                fillOpacity: 1,
                        //                scale: 1,
                        //                strokeColor: 'gold',
                        //                strokeWeight: 2
            };
            $("#legend_title").html('Branch Office Type');
            $("#map_legend_colors").append('<label class="map_legend_label">' + '<label class="map_legend_color circle" style="border:3px solid ' + legend_info.head_office.border_color + ';background-color:' + legend_info.head_office.fill_color + '; "></label>' + legend_info.head_office.title + '</label><br/>');
            $("#map_legend_colors").append('<label class="map_legend_label">' + '<label class="map_legend_color circle" style="border:2px solid ' + legend_info.region_office.border_color + ';background-color:' + legend_info.region_office.fill_color + '; "></label>' + legend_info.region_office.title + '</label><br/>');
            $("#map_legend_colors").append('<label class="map_legend_label">' + '<label class="map_legend_color circle" style="border:1px solid ' + legend_info.branch_office.border_color + ';background-color:' + legend_info.branch_office.fill_color + '; "></label>' + legend_info.branch_office.title + '</label><br/>');


            //            for(var oc=0;oc<legend_info.length;++oc)            {
            //                $("#map_legend_colors").append('<label class="map_legend_label">' + '<label class="map_legend_color" style="background-color:' +  + ';"></label>' + legend_info[oc].+ '</label><br/>');
            //            }

            //            legend_info.forEach(function (legend_details) {
            //                alert(legend_details);
            //                //$("#map_legend_colors").append('<label class="map_legend_label">' + '<label class="map_legend_color" style="background-color:' +legend_details.fill_color +';"></label>' + legend_details.fill + '</label><br/>');
            //            });

            var orgname, marker, dc;
            var infoWindow = new google.maps.InfoWindow({maxWidth: 980});
            for (dc = 0; dc < branch_data.length; dc++) {
                if (!branch_data[dc])
                    continue;
                orgname = (branch_data[dc].org_name ? branch_data[dc].org_name.replace("<strong>", "").replace("</strong>", "") : null);
                marker = new google.maps.Marker({
                    map: map,
                    position: new google.maps.LatLng(branch_data[dc].lat, branch_data[dc].lon),
                    icon: branch_data[dc].branch_type_id == 1 ? head_icon : branch_data[dc].branch_type_id == 2 ? region_icon : branch_icon,
                    zIndex: branch_data[dc].branch_type_id == 1 ? 999 : branch_data[dc].branch_type_id == 2 ? 997 : 995,
                    title: "Branch Name: " + branch_data[dc].branch_name + (orgname ? "\nOrganization Name : " + orgname : "")
                });
                markers.push({org_id: branch_data[dc].org_id, dist_code: branch_data[dc].dist_code, upaz_code: branch_data[dc].upaz_code, marker: marker});
                (function (marker, branch_data_info) {
                    marker.addListener("click", function (evt) {
                        var contentString = "<div class='info-div'>" +
                                "<table><tr><td colspan='2'>" +
                                "<h3><b>Organization Name :</b> <span style='color:#05c;'>" + branch_data_info.org_name + "</span></h3>" +
                                "</td></tr><tr><td style='width:100%; vertical-align:top;'>" +
                                "<h3><b>Branch Name :</b> <span style='color:#18d;'>" + branch_data_info.branch_name + (branch_data_info.branch_code ? " (" + branch_data_info.branch_code + ")" : "") + "</span></h3>" +
                                "<h4><b>Road Name/Village : </b>" + branch_data_info.road_name_or_village + "</h4>" +
                                "<h4><b>Mohalla/Post Office : </b> " + branch_data_info.mohalla_or_post_office + "</h4>" +
                                "<h4><b>Mailing Address : </b> " + branch_data_info.mailing_address + "</h4>" +
                                "<h4><b>Contract Info : </b> " + branch_data_info.contract_info + "</h4>" +
                                "<h4><b>Latitude :</b>" + branch_data_info.lat +"<b>, Longitude : </b>"+ branch_data_info.lon + "</h4>" +
                                "</td><td style='width:auto; vertical-align:top;'>" +
                                (!branch_data_info.file_name ? "" : "<div style='height:250px;padding:0;'><img style='border:0 none; width:auto; max-height:240px; margin:0; padding:0;' src='" +
                                        document.location.href + "files/uploads/branches/" + branch_data_info.file_name + "' /></div>") +
                                //"<div style='padding:3px 10px;'>" + (!branch_data_info.file_name ? "" : "<img style='border:0 none; width:auto; max-height:240px; margin:0; padding:0;' src='./files/uploads/branches/" + branch_data_info.file_name + "' />") + "</div>" +
                                "</td></tr></table>" +
                                "</div>";
                        infoWindow.setContent(contentString);
                        infoWindow.setPosition(branch_data_info.pos);
                        infoWindow.open(map, marker);
                    });
                })(marker, branch_data[dc]);
                //markers.push(marker);
            }

            //alert(markers.length + " : " + i + " : " + aa + " : " + branch_data.length);

            //            if (org_id && org_id != '')
            //                set_branch_map(org_id);

            set_legend_info();
            map_selected_info();

            $("#branch_count").html("Total no. of Branches: " + branch_data.length);
            $('#map_info').fadeIn(750);
        }
//Moshiur
        $("input[name='bd_info']").on("click", function () {
            $("#bd_info_upazila").hide();
            $("#bd_info_district").hide();

            if ($('input:radio[name=bd_info]:checked').val() == 'dist') {
                $("#bd_info_district").show();
                $("#bd_info_upazila").hide();


            }
            if ($('input:radio[name=bd_info]:checked').val() == 'upaz') {
                $("#bd_info_district").show();
                $("#bd_info_upazila").show();

            }


            $("#bd_info_district").show();

            $("#bd_info_district").empty();
            $("#bd_info_district").append('<option value="">--- select ---</option>');
            $("#bd_info_upazila").empty();
            $("#bd_info_upazila").append('<option value="">--- select ---</option>');
            for (var t = 0; t < bd_all["dist"].features.length; t++) {
                $("#bd_info_district").append('<option value="' + bd_all["dist"].features[t].properties.dist_code + '">' + bd_all["dist"].features[t].properties.dist_name + '</option>');
            }

        });

        $("#bd_info_district").on("change", function () {
            // alert("OK");
            //  $("input[name='bd_info']:checked").val();
            var dstCode = $("#bd_info_district").val();
            $("#bd_info_upazila").empty();
            $("#bd_info_upazila").append('<option value="">--- select ---</option>');
            set_district_map(dstCode);
            for (var t = 0; t < bd_all["upaz"].features.length; t++) {
                // alert(bd_all["upaz"].features[0].properties.dist_code);
                //alert($("#bd_info_district").text());
                if (bd_all["upaz"].features[t].properties.dist_code == dstCode) {
                    // alert(dstCode);
                    $("#bd_info_upazila").append('<option value="' + bd_all["upaz"].features[t].properties.upaz_code + '">' + bd_all["upaz"].features[t].properties.upaz_name + '</option>');
                }
            }
            map_selected_info();

        });
        $("#bd_info_upazila").on("change", function () {
            var upzCode = $("#bd_info_upazila").val();
            set_upazila_map(upzCode);

            map_selected_info();
        });

        function set_district_map(dist_id) {
            
            //alert("dist_id: "+dist_id);

            $("#busy-indicator").fadeIn();
            if (!dist_id || dist_id == '') {
                for (var t = 0; t < all_map_poly.length; t++) {
                    all_map_poly[t].map_poly.setMap(map);
                }
            } else {

                for (var t = 0; t < all_map_poly.length; t++) {
                    if (!dist_id || dist_id == '') {
                        all_map_poly[t].map_poly.setMap(null);
                    }
                    if (all_map_poly[t].dist_code != dist_id)
                        all_map_poly[t].map_poly.setMap(null);
                    else
                        all_map_poly[t].map_poly.setMap(map);
                    //map.setCenter(all_map_poly[t].center);
                    //map.setZoom(9);
                }
            }
            var mc, bc = 0;
            if (dist_id && dist_id != '') {
                for (mc = 0; mc < markers.length; mc++) {
                    if (markers[mc].dist_code == dist_id) {
                        if (markers[mc].marker.getMap() == null)
                            markers[mc].marker.setMap(map);
                        ++bc;
                    } else {
                        if (markers[mc].marker.getMap() != null)
                            markers[mc].marker.setMap(null);
                    }
                }
            } else {
                bc = markers.length;
                for (mc = 0; mc < markers.length; mc++) {
                    markers[mc].marker.setMap(null);
                }
            }

            $("#branch_count").html("Total no. of Branches: " + bc);
            $("#busy-indicator").fadeOut();
            $('#map_info').fadeIn(500);
            return false;
        }

        function set_upazila_map(upaz_id) {
            for (var t = 0; t < bd_all["upaz"].features.length; t++) {
                if (all_map_poly[t].upaz_code != upaz_id)
                    all_map_poly[t].map_poly.setMap(null);
                else
                    all_map_poly[t].map_poly.setMap(map);
            }

            $("#busy-indicator").fadeIn();
            var mc, bc = 0;
            if (upaz_id && upaz_id != '') {
                for (mc = 0; mc < markers.length; mc++) {
                    if (markers[mc].upaz_code == upaz_id) {
                        if (markers[mc].marker.getMap() == null)
                            markers[mc].marker.setMap(map);
                        ++bc;
                    } else {
                        if (markers[mc].marker.getMap() != null)
                            markers[mc].marker.setMap(null);
                    }
                }
            } else {
                bc = markers.length;
                for (mc = 0; mc < markers.length; mc++) {
                    markers[mc].marker.setMap(null);
                }
            }
            $("#branch_count").html("Total no. of Branches: " + bc);
            $("#busy-indicator").fadeOut();
            $('#map_info').fadeIn(500);
            return false;
        }
//endMoshiur

    </script>

    <?php
    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }

    if (!empty($branch_infos)) {
        $branch_infos = json_encode($branch_infos);
        //echo $this->Html->scriptBlock("set_map_opt('$org_id', $branch_infos);", array('inline' => true));
        //set_admin_boundary();$('#bd_info').val('$admin_code'); 
        echo $this->Html->scriptBlock("$('#branch_type_id').val('$branch_type_id'); set_branch_location($branch_infos);", array('inline' => true));
    }
    ?>

</div>
