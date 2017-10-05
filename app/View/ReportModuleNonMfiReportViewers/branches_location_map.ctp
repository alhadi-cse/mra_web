
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

        /*        #mapLabelPanel {
                    display: none;
                }
        */

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
            border: 2px solid #fff;
            width: 20px;
            height: 20px;
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
            background: #fff url("./img/map_full_extent.svg") no-repeat center center/70% auto;
        }
        .map_close {
            background: #fff url("./img/close.png") no-repeat center center/50% auto;
        }
        .filter_option {
            background: #fff url("./img/search.png") no-repeat center center/75% auto;
        }
        .map_full_extent:hover {
            background-size: 85% auto;
        }
        .map_close:hover {
            background-size: 65% auto;
        }
        .filter_option:hover {
            background-size: 90% auto;
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
        <div class="legend_btn_left_img"></div>
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
        <div id="filter_option" onclick="option_modal_open('option_opt');" title="Filter the Map" class="map_btns filter_option"></div>
    </div>

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
                                    echo $this->Form->input('', array('id' => 'bd_info', 'name' => 'bd_info', 'type' => 'radio', 'options' => $bd_admins, 'escape' => false, 'legend' => false, 'div' => false, 'label' => true));
                                    ?>
                                    <div>
                                        <select id="bd_info_dist" style="width:175px; height:25px; margin:5px 10px; padding:0; display:none;">
                                        </select>
                                        <select id="bd_info_upaz" style="width:175px; height:25px; margin:5px; padding:0; display:none;">
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

            set_basic_opts();
        });


        var isOpen = false;
        function option_modal_open(content) {
            if (!isOpen || $('#' + content + "_bg").css('display') == 'none')
                modal_open(content, 50);
            isOpen = true;
        }
        function option_modal_close(content) {
            if (isOpen || $('#' + content + "_bg").css('display') != 'none')
                modal_close(content);
            isOpen = false;
        }

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

        function set_map_label() {
            //alert('set_map_label');

            $(".map_label").fadeOut(50);
            if ($("#map_label_opt").is(':checked')) {
                var lblCls = ".map_label";
                if ($("#bd_info_dist").val())
                    lblCls += ".dist_" + $("#bd_info_dist").val();
                if ($("#bd_info_upaz").val())
                    lblCls += ".upaz_" + $("#bd_info_upaz").val();

                $(lblCls).fadeIn(500);
            }
            return;
        }

        var map,
                bd_center = new google.maps.LatLng(23.777777, 90.444444),
                map_center = new google.maps.LatLng(23.777777, 90.444444),
                curr_poly = null,
                markers = [],
                all_map_poly = [];


        function set_basic_opts() {
            load_districts();

            $("#map_close").on('click', function () {
                if (!confirm('Are you sure to close map ?'))
                    return;
                $('#map_info').fadeOut(500, function () {
                    $(this).remove();
                });
            });
            $("#legend_info_opt").on('change', function () {
                if ((this).checked) {
                    legend_open_close('legend_info', 'open', 'left');
                } else {
                    legend_open_close('legend_info', 'close', 'left');
                }
                return false;
            });
            $("#legend_opt").on('change', function () {
                if ((this).checked) {
                    legend_open_close('legend', 'open', 'right');
                } else {
                    legend_open_close('legend', 'close', 'right');
                }
                return false;
            });

            $("#map_label_opt").on('change', function () {
                set_map_label();
                return false;
            });



            $('#org_list').filterByText($('#txtFilter_org_list'), true);

            $("#org_list").on("change", function () {
                set_branch_data();
                return false;
            });
            $("input[name='branch_types']").on("change", function () {
                set_branch_data();
                return false;
            });


            $("input[type=radio][name='bd_info']").on('change', function () {
                var adminCode = $(this).val();
                set_bd_info(adminCode);
                set_admin_boundary(adminCode);

                set_map_label();
                return false;
            });

            $('#txtFilter_org_list').on("change", function () {
                if ($("#org_list").children('option').length === 1)
                    set_branch_data();
                return false;
            });


            $("#bd_info_dist").on("change", function () {
                var distCode = $("#bd_info_dist").val();
                load_upazila(distCode);
                set_district_map(distCode);
                map_selected_info();
            });

            $("#bd_info_upaz").on("change", function () {
                var distCode = $("#bd_info_dist").val(),
                        upazCode = $("#bd_info_upaz").val();

                set_upazila_map(upazCode, distCode);
                map_selected_info();
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
            google.maps.event.addListener(map, "mousemove", function (evt) {
                $("#map_cord_info").html(evt.latLng.lat().toFixed(6) + ", " + evt.latLng.lng().toFixed(6));
            });

            google.maps.event.addListenerOnce(map, "idle", function () {
                //alert('ML:1');
                set_map_label();
            });

            set_map_label();
            return true;
        }

        function load_districts() {
            $("#bd_info_dist").empty();
            $("#bd_info_dist").append('<option value="">----- Select District -----</option>');
            $("#bd_info_upaz").empty();
            $("#bd_info_upaz").append('<option value="">----- Select Upazila -----</option>');
            for (var fc = 0; fc < bd_all["dist"].features.length; fc++) {
                $("#bd_info_dist").append('<option value="' + bd_all["dist"].features[fc].properties.dist_code + '">' + bd_all["dist"].features[fc].properties.dist_name + '</option>');
            }
            $("#bd_info_dist").sortSelectBy('text');
        }

        function load_upazila(distCode) {
            $("#bd_info_upaz").empty();
            $("#bd_info_upaz").append('<option value="">----- Select Upazila -----</option>');

            for (var pc = 0; pc < bd_all["upaz"].features.length; pc++) {
                if (!distCode || distCode == '' || bd_all["upaz"].features[pc].properties.dist_code == distCode) {
                    $("#bd_info_upaz").append('<option value="' + bd_all["upaz"].features[pc].properties.upaz_code + '">' + bd_all["upaz"].features[pc].properties.upaz_name + '</option>');
                }
            }
            $("#bd_info_upaz").sortSelectBy('text');
        }


        function set_branch_data() {

            var orgId = $("#org_list").val(),
                    brancheTypeId = $("input[name='branch_types']:checked").val(),
                    post_url = document.location + "ReportModuleReportViewers\/get_branches_location\/" +
                    ((!orgId || orgId == '') ? "0" : orgId) + "\/" + brancheTypeId;
            
            alert(orgId);
            alert(post_url);
            $.ajax({
                async: true,
                type: "post",
                dataType: "html",
                url: post_url,
                //url: document.location + "ReportModuleReportViewers\/get_loan_info\/" + orgId + "\/" + brancheTypeId,
                beforeSend: function (XMLHttpReq) {
                    $("#busy-indicator").fadeIn();
                },
                complete: function (XMLHttpReq, textStat) {
                    $("#busy-indicator").fadeOut();
                },
                success: function (branch_data, status) {
                    if (branch_data && branch_data.length > 0) {
                        try {
                            set_branch_location(JSON.parse(branch_data));
                        } catch (e) {
                            alert("Error trying to parse JSON." + e.message);
                        }
                    } else {

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

            var distName = $("#bd_info_dist").find(":selected").text(),
                    upazName = $("#bd_info_upaz").find(":selected").text();

            if ($("#bd_info_upaz").val()) {
                $("#selected_info").append('<p>Selected Upazila: <span>' + upazName + ', ' + distName + '</span></p>');
            } else if ($("#bd_info_dist").val()) {
                $("#selected_info").append('<p>Selected District: <span>' + distName + '</span></p>');
            }

            //set_map_label();
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

        function set_bd_info(adminCode) {

            //alert('set_bd_info');
            switch (adminCode) {
                case 'dist' :
                {
                    $("#bd_info_dist").show();

                    $("#bd_info_upaz").empty();
                    $("#bd_info_upaz").hide();
                    break;
                }
                case 'upaz' :
                {
                    $("#bd_info_dist").show();
                    $("#bd_info_upaz").show();
                    break;
                }
                default :
                {
                    $("#bd_info_upaz").empty();
                    $("#bd_info_dist").hide();
                    $("#bd_info_upaz").hide();
                }
            }
            //map_selected_info();
        }

        function set_admin_boundary(adminCode) {
            //alert('set_admin_boundary');
            $("#busy-indicator").fadeIn();

            $("#mapLabelPanel").empty();
            if (all_map_poly && all_map_poly.length > 0) {
                for (var pc = 0; pc < all_map_poly.length; pc++) {
                    all_map_poly[pc].map_poly.setMap(null);
                }
            }
            all_map_poly = [];

            if (markers || markers.length > 0)
                set_legend_info();

            if (!adminCode || !bd_all[adminCode]) {
                $("#busy-indicator").fadeOut();
                return;
            }

            var bd_admin = bd_all[adminCode];
            var admin_field_code = adminCode + '_code';
            var admin_field_name = adminCode + '_name';
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

                    map_poly.setMap(null);
                    all_map_poly.push({center: poly_center, div_code: admin_props.div_code, dist_code: admin_props.dist_code, upaz_code: admin_props.upaz_code, map_poly: map_poly});

                    var lblCls = "map_label" +
                            (admin_props.div_code ? " div_" + admin_props.div_code : "") +
                            (admin_props.dist_code ? " dist_" + admin_props.dist_code : "") +
                            (admin_props.upaz_code ? " upaz_" + admin_props.upaz_code : "");

                    labelOptions.boxClass = lblCls;
                    labelOptions.content = curr_admin_name;
                    labelOptions.position = poly_center;

                    var polygonLabel = new InfoBox(labelOptions);
                    polygonLabel.open(map);
                } catch (e) {
                }
            });

            var distCode = $("#bd_info_dist").val(),
                    upazCode = $("#bd_info_upaz").val();

            if (upazCode && upazCode != '')
                set_upazila_map(upazCode, distCode);
            else if (distCode && distCode != '')
                set_district_map(distCode);
            else {
                for (var pc = 0; pc < all_map_poly.length; pc++) {
                    if (all_map_poly[pc].map_poly.getMap() == null)
                        all_map_poly[pc].map_poly.setMap(map);
                }

                //bc = markers.length;
//                for (mc = 0; mc < markers.length; mc++) {
//                    if (markers[mc].marker.getMap() == null)
//                        markers[mc].marker.setMap(map);
//                }

//                map.setCenter(bd_center);
//                map.setZoom(7);

                if (markers.length > 0) {
                    for (mc = 0; mc < markers.length; mc++) {
                        if (markers[mc].marker.getMap() == null)
                            markers[mc].marker.setMap(map);
                    }
                }
            }

            google.maps.event.addListenerOnce(map, "idle", function () {
                //alert('ML: d1');
                set_map_label();
            });

            map_selected_info();
            //set_map_label();

//            google.maps.event.addListenerOnce(map, "idle", function () {
//                set_map_label();
//            });

            $("#busy-indicator").fadeOut();
        }

        function set_legend_info() {
            //alert('set_legend_info');
            $("#legend_info_title").empty();
            $("#map_legend_infos").empty();

            var adminCode = $("input[name='bd_info']:radio:checked").val(),
                    admin_name = $("input[name='bd_info']:radio:checked").next('label').text();

            if (!adminCode || !bd_all[adminCode]) {
                legend_open_close('legend_info', 'close', 'left');
                $("#legend_info").css('left', '-1000px');
                return;
            }

            var bd_admin = bd_all[adminCode];
            var admin_field_code = adminCode + '_code';
            var admin_field_name = adminCode + '_name';
            var total_branch_count = 0;
            var curr_admin_code, curr_admin_name, curr_value;
            bd_admin.features.forEach(function (feature) {
                try {
                    var admin_props = feature.properties;
                    curr_admin_name = admin_props[admin_field_name];
                    curr_admin_code = admin_props[admin_field_code];

                    try {
                        curr_value = markers.filter(marker => marker[admin_field_code] === curr_admin_code).length;
                    } catch (ex) {
                        curr_value = 0;
                    }

                    if (curr_value) {
                        total_branch_count += curr_value;
                        $("#map_legend_infos").append('<p>' + curr_admin_name + ' (' + curr_value + ')</p>');
                    }
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

            if (!google)
                return;

            if (markers && markers.length > 0) {
                for (var mc = 0; mc < markers.length; mc++) {
                    markers[mc].marker.setMap(null);
                }
            }

            if (!branch_data || branch_data.length < 1) {
                legend_open_close('legend', 'close', 'right');
                $("#legend").css('right', '-1000px');
                legend_open_close('legend_info', 'close', 'left');
                $("#legend_info").css('left', '-1000px');
                alert('Branch data not available !');
                $("#busy-indicator").fadeOut();
                return;
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

            $("#legend_title").html('Branch Office Type');
            $("#map_legend_colors").append('<label class="map_legend_label">' + '<label class="map_legend_color circle" style="border:3px solid ' + legend_info.head_office.border_color + ';background-color:' + legend_info.head_office.fill_color + '; "></label>' + legend_info.head_office.title + '</label><br/>');
            $("#map_legend_colors").append('<label class="map_legend_label">' + '<label class="map_legend_color circle" style="border:2px solid ' + legend_info.region_office.border_color + ';background-color:' + legend_info.region_office.fill_color + '; "></label>' + legend_info.region_office.title + '</label><br/>');
            $("#map_legend_colors").append('<label class="map_legend_label">' + '<label class="map_legend_color circle" style="border:1px solid ' + legend_info.branch_office.border_color + ';background-color:' + legend_info.branch_office.fill_color + '; "></label>' + legend_info.branch_office.title + '</label><br/>');

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

                markers.push({org_id: branch_data[dc].org_id, dist_code: branch_data[dc].dist_code, upaz_code: branch_data[dc].upaz_code, marker: marker});

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

        function set_district_map(distCode) {
            //alert('set_district_map: ' + distCode);

            $("#busy-indicator").fadeIn();

            var mc, bc = 0;
            if (!distCode || distCode == '') {
                for (var pc = 0; pc < all_map_poly.length; pc++) {
                    if (all_map_poly[pc].map_poly.getMap() == null)
                        all_map_poly[pc].map_poly.setMap(map);
                }

                bc = markers.length;
                for (mc = 0; mc < markers.length; mc++) {
                    if (markers[mc].marker.getMap() == null)
                        markers[mc].marker.setMap(map);
                }
                //alert('set_district_map:setCenter: ' + distCode);
                map.setCenter(bd_center);
                map.setZoom(7);
            } else {
                for (var pc = 0; pc < all_map_poly.length; pc++) {
                    if (all_map_poly[pc].dist_code == distCode) {
                        if (all_map_poly[pc].map_poly.getMap() == null)
                            all_map_poly[pc].map_poly.setMap(map);

                        map_center = all_map_poly[pc].center;
                        console.log(map_center);
                    } else {
                        if (all_map_poly[pc].map_poly.getMap() != null)
                            all_map_poly[pc].map_poly.setMap(null);
                    }
                }
                map.setCenter(map_center);
                map.setZoom(9);

                for (mc = 0; mc < markers.length; mc++) {
                    if (markers[mc].dist_code == distCode) {
                        if (markers[mc].marker.getMap() == null)
                            markers[mc].marker.setMap(map);
                        ++bc;
                    } else {
                        if (markers[mc].marker.getMap() != null)
                            markers[mc].marker.setMap(null);
                    }
                }
            }

            $("#branch_count").html("Total no. of Branches: " + bc);
            $("#busy-indicator").fadeOut();
            $('#map_info').fadeIn(500);
            return false;
        }

        function set_upazila_map(upazCode, distCode) {

            //alert('set_upazila_map: ' + upazCode + ': ' + distCode);

            $("#busy-indicator").fadeIn();
            var mc, bc = 0;
            if (!upazCode || upazCode == '') {
                if (distCode && distCode != '') {
                    set_district_map(distCode);
                    return;
                }

                bc = markers.length;
                for (mc = 0; mc < markers.length; mc++) {
                    if (markers[mc].marker.getMap() != null)
                        markers[mc].marker.setMap(null);
                }
            } else {
                for (var pc = 0; pc < all_map_poly.length; pc++) {
                    if (all_map_poly[pc].dist_code == distCode && all_map_poly[pc].upaz_code == upazCode) {
                        if (all_map_poly[pc].map_poly.getMap() == null)
                            all_map_poly[pc].map_poly.setMap(map);

                        map_center = all_map_poly[pc].center;
                        console.log(map_center);
                    } else {
                        if (all_map_poly[pc].map_poly.getMap() != null)
                            all_map_poly[pc].map_poly.setMap(null);
                    }
                }
                map.setCenter(map_center);
                map.setZoom(11);

                for (mc = 0; mc < markers.length; mc++) {
                    if (markers[mc].dist_code == distCode && markers[mc].upaz_code == upazCode) {
                        if (markers[mc].marker.getMap() == null)
                            markers[mc].marker.setMap(map);
                        ++bc;
                    } else {
                        if (markers[mc].marker.getMap() != null)
                            markers[mc].marker.setMap(null);
                    }
                }
            }

            $("#branch_count").html("Total no. of Branches: " + bc);
            $("#busy-indicator").fadeOut();
            $('#map_info').fadeIn(500);
            return false;
        }

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

        echo $this->Html->scriptBlock("$('#branch_type_id').val('$branch_type_id'); set_branch_location($branch_infos);", array('inline' => true));
    }
    ?>

</div>
