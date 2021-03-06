
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
            margin: 10px;
            padding: 0;
            text-align: right;
            float: right;
            display: block;
        }
        .org_opt {
            width: 300px;
            margin: 30 3px;
            padding: 2px;
        }
        .branch_count {
            clear: both;
            margin: 2px 0 0 13px;
            color: #047;
            font: bold 14px/1.2 Roboto, Arial, sans-serif;
            text-align: left;
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
            color: #fa2413;
            background-color: #eee;
        }
        .map_full_extent {
            background: #fff url("./img/map_full_extent.svg") no-repeat center center/70% auto;
        }
        .map_close {
            background: #fff url("./img/close.png") no-repeat center center/45% auto;
        }
        .map_full_extent:hover {
            background-size: 85% auto;
        }
        .map_close:hover {
            background-size: 55% auto;
        }


        .map_legend, .map_opt_content {
            z-index: 101;
            position: absolute;
            top: 100px;
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
            width: 24px;
            height: 18px;
            margin: 0 3px;
            padding: 0;
            vertical-align: middle;
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
        /*        //MOshiur*/
        .filter_option {
            height: 20px;

            border: 1px solid;
            border-radius: 3px;
            background: #fff url("./img/search.png") no-repeat center center/70% auto;


        }

        .filter_option:hover {
            background-size: 100% auto;
        }
        /*  //endMoshiur*/

        .legend_btn_content {
            z-index: 101;
            position: absolute;
            top: 100px;
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
            left: 145px;
            right: auto;
            padding: 5px 10px;
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

    <div class="map_opt_content">
        <label><input type="checkbox" id="legend_info_opt" checked="checked" title="Show/Hide Map Information" />Map Information</label>
        <label><input type="checkbox" id="legend_opt" checked="checked" title="Show/Hide Map Legend" />Map Legend</label>
        <label><input type="checkbox" id="map_label_opt" title="Show/Hide Map Label" />Map Label</label>
    </div>

    <!--    onclick="javascript: legend_open_close('legend_info', 'close', 'left');" 
        onclick="javascript: legend_open_close('legend', 'close', 'right');"-->

    <div class="map_btns_content">
        <div id="map_center" class="map_btns map_full_extent" title="Full extent the map"></div>
        <div id="map_close" class="map_btns map_close" title="Close the map"></div>
    </div>

    <div class="org_info">
        <table>
            <tr>
                <td colspan="2" style='vertical-align:top; text-align:left;'>

                    <select id="bd_info" style="width:150px; margin:0;">
                        <option value="">--- Select ---</option>
                        <option value="dist">District</option>
                        <option value="upaz">Upazila</option>
                        <!--<option value="div">Division</option>-->
                    </select>
                    <select id="bd_info_district" style="width:185px; margin-left:10px">
                        <option value="">--- Select ---</option>
                    </select>
                    <select id="bd_info_upazila" style="width:170px; margin-left:5px;display:none">
                        <option value="">--- Select ---</option>
                    </select>
                    
                </td>
                
            </tr>
            <tr>
                <td style='vertical-align:top;'>
                    <?php
                    echo "<div style='width:355px; margin:0 0 0 5px; padding:0; text-align:left;'><div style='width:auto; margin:0; padding:0;'>" 
                    . $this->Form->input('', array('id' => 'org_list', 'type' => 'select', 'class' => 'org_opt', 'style' => 'position:static; left:0; width:350px;', 'options' => $org_list, 'value' => $org_id, 'empty' => '--------- All MFIs --------', 'escape' => false, 'div' => false, 'label' => false))
                    . "</div></div></td>"
                    . "<td style='vertical-align:top;'>"
                    . "<span style='font-weight:bold;'>filter:<input type='text' id='txtFilter_org_list' style='width:100px !important; margin-left:5px;'></span>";
                    ?>
                </td>

            </tr>

        </table>
        <div id="branch_count" class="branch_count"></div>

    </div>

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
        var map_data_title;
        var bd_center = null;
        var map_data = [];
        var data_info = [];
        var all_map_poly = [];
        var lat = 23.777777;
        var log = 90.444444;
        var zooming = 7;
        //23.777777, 90.444444

        $(function () {

            $('#org_list').filterByText($('#txtFilter_org_list'), true);

            set_basic_opts();

            $("#bd_info, #org_list").on("change", function () {
                set_branch_data();
                return false;
            });

            $('#txtFilter_org_list').on("change", function () {
                if ($("#org_list").children('option').length === 1)
                    set_branch_data();
                return false;
            });

        });

        function set_branch_data() {
            var adminName = $("#bd_info").val();
            var orgId = $("#org_list").val();

            $.ajax({
                async: true,
                type: "post",
                dataType: "html",
                url: document.location + "ReportModuleReportViewers\/get_branche_count_info\/" + adminName + "\/" + orgId,
                beforeSend: function (XMLHttpReq) {
                    $("#busy-indicator").fadeIn();
                },
                complete: function (XMLHttpReq, textStat) {
                    $("#busy-indicator").fadeOut();
                },
                success: function (branch_data, status) {
                    if (branch_data) {
                        try {
                            map_data = JSON.parse(branch_data);
                            map_init(adminName, lat, log, zooming);
                        } catch (e) {
                            alert("Error trying to parse JSON." + e.message);
                        }
                    }
                },
                error: function (branch_data, status) {
                    alert('Error! response=' + JSON.stringify(branch_data) + " status=" + status);
                    $("#busy-indicator").fadeOut();
                }
            });
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

        var curr_poly = null;

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

            return true;
        }

        function map_init(admin_code, lat, log, zooming) {

            //Moshiu
            //alert("Admin Name:"+admin_code+"Lat:  "+lat+"Log: "+log+" Zoom: " +zooming);

            $("#legend_title").empty();
            $("#legend_info_title").empty();
            $("#map_legend_infos").empty();
            $("#map_legend_colors").empty();

            if (all_map_poly && all_map_poly.length > 0) {
                for (var pc = 0; pc < all_map_poly.length; pc++) {
                    all_map_poly[pc].map_poly.setMap(null);
                }
            }
            all_map_poly = [];

            if (typeof (google) == 'undefined') {
                legend_open_close('legend', 'close', 'right');
                $("#legend").css('right', '-1000px');
                legend_open_close('legend_info', 'close', 'left');
                $("#legend_info").css('left', '-1000px');

                alert('Map Loading Failed !');
                $("#busy-indicator").fadeOut();
                return;
            }

            bd_center = new google.maps.LatLng(lat, log);
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: zooming,
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

//            alert(admin_code);
//            alert(map_data.length);
//            alert(map_data.length+map_data.constructor);
//            // || map_data.constructor !== Array

            if (!admin_code || !bd_all[admin_code] || !map_data || map_data.length < 1) {
                legend_open_close('legend', 'close', 'right');
                $("#legend").css('right', '-1000px');
                legend_open_close('legend_info', 'close', 'left');
                $("#legend_info").css('left', '-1000px');

                alert('Data not available !');
                $("#busy-indicator").fadeOut();
                return;
            }

            var clrClass = ["#FFCCCC", "#FFAAAA", "#FF8888", "#FF6666", "#FF4444", "#FF2222"], noDataClass = "#FFFFFF";

            var bd_admin = bd_all[admin_code];
            var admin_field_code = admin_code + '_code';
            var admin_field_name = admin_code + '_name';

            var minVal = Infinity, maxVal = -Infinity, currVal;
            var total_branch_count = 0;
            for (var dc = 0; dc < map_data.length; dc++) {
                if (!map_data[dc].data_value)
                    continue;
                currVal = parseFloat(map_data[dc].data_value);
                total_branch_count += currVal;
                if (!currVal)
                    continue;
                if (minVal > currVal)
                    minVal = map_data[dc].data_value;
                if (maxVal < currVal)
                    maxVal = map_data[dc].data_value;
            }

            var admin_name = $('#bd_info option:selected').text();
            map_data_title = admin_name + ' Branches Count';
            $("#legend_title").append(map_data_title);
            $("#legend_info_title").append(map_data_title + ' (' + total_branch_count + ')');

            var delt = parseInt((maxVal - minVal) / clrClass.length) + 1;
            var curr_admin_code, curr_admin_name, curr_value, color_index, data_value;

            var map_poly, poly_center, mapStyle;

            var infoWindow = new google.maps.InfoWindow;
            var polyDefaultOptions = {
                zIndex: 101,
                strokeColor: "#553322",
                strokeWeight: 1.0,
                strokeOpacity: 0.7,
                fillOpacity: 0.7
            };
            var polyHoverOptions = {
                zIndex: 102,
                strokeColor: "#553322",
                strokeWeight: 2.0,
                strokeOpacity: 0.8,
                fillOpacity: 0.8
            };
            //fillColor: hoverColor,
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

                    curr_admin_name = admin_props[admin_field_name];
                    curr_admin_code = admin_props[admin_field_code];
                    curr_value = map_data.filter(data => data.geo_code === curr_admin_code).map(data => data.data_value);

                    //"CNT_LAT","CNT_LONG"
                    poly_center = new google.maps.LatLng(admin_props["CNT_LAT"], admin_props["CNT_LONG"]);

                    var polyOptions = $.extend(true, {}, polyDefaultOptions);
                    if (curr_value.length) {
                        data_value = curr_value[0];
                        color_index = parseInt(data_value / delt);

                        if (color_index >= clrClass.length)
                            color_index = clrClass.length - 1;

                        polyOptions.fillColor = clrClass[color_index];
                        $("#map_legend_infos").append('<p>' + curr_admin_name + ' (' + data_value + ')</p>');
                    } else {
                        data_value = "There is no branch";
                        polyOptions.fillColor = noDataClass;
                        polyOptions.fillOpacity = 0.25;
                    }
                    //$("#map_legend_infos").append('<p>' + curr_admin_name + ' (' + data_value + ')</p>');
                    //polyOptions.fillColor = map_color;

                    polyOptions.paths = GetGeoPaths(admin_geom.coordinates, (admin_geom.type === "MultiPolygon"));
                    map_poly = new google.maps.Polygon(polyOptions);
                    map_poly.setMap(map);

                    all_map_poly.push({center: poly_center, div_code: admin_props.div_code, dist_code: admin_props.dist_code, upaz_code: admin_props.upaz_code, map_poly: map_poly});

                    data_info = {pos: poly_center, admin_name: admin_name, data_admin_name: curr_admin_name, data_value: data_value, div_code: admin_props.div_code, dist_code: admin_props.dist_code, upaz_code: admin_props.upaz_code};
                    (function (map_poly, data_info) {
                        map_poly.addListener("click", function (evt) {

                            if (this.strokeWeight === polySelectedOptions.strokeWeight)
                                return;

                            if (curr_poly && curr_poly.getMap !== null) {
                                curr_poly.setOptions(polyDefaultOptions);
                            }

                            curr_poly = this;
                            this.setOptions(polySelectedOptions);

                            infoWindow.setContent('<div style="width:auto; min-width:150px; min-height:45px">' +
                                    '<h2 class="info-title">' + data_info.data_admin_name + ' ' + data_info.admin_name + '</h2>' +
                                    '<h3>No. of Branch: ' + data_info.data_value + '</h3></div>');
                            //infoWindow.setPosition(evt.latLng);
                            infoWindow.setPosition(data_info.pos);
                            infoWindow.open(map, this);
                            //infoWindow.open(map, this, function (){$(this).animate('height', 500);});
                            //setTimeout($(this).animate('height', 500), 1000);
                        });
                        infoWindow.addListener('closeclick', function () {
                            curr_poly = null;
                            map_poly.setOptions(polyDefaultOptions);
                            infoWindow.setMap(null);
                        });

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
                    })(map_poly, data_info);

                    labelOptions.content = curr_admin_name;
                    labelOptions.position = poly_center;

                    var polygonLabel = new InfoBox(labelOptions);
                    polygonLabel.open(map);
                } catch (e) {
                }
            });

            var min, max, sign;
            for (var ci = 0; ci < clrClass.length; ci++) {
                if (ci === 0) {
                    sign = " < ";
                    min = "  ";
                    max = delt;
                } else if (ci === clrClass.length - 1) {
                    sign = " =< ";
                    min = delt * ci;
                    max = "";
                } else {
                    sign = " - ";
                    min = delt * ci;
                    max = delt * (ci + 1) - 1;
                }

                $("#map_legend_colors").append('<label class="map_legend_label">' + '<label class="map_legend_color" style="background-color:' + clrClass[ci] + ';"></label>' + min + sign + max + '</label><br/>');
            }

            $("#branch_count").html("Total no. of Branches: " + total_branch_count);

            $("#map_legend_colors").append('<label class="map_legend_label">' + '<label class="map_legend_color" style="background-color:' + noDataClass + ';"></label>' + 'No Branch' + '</label>');



            set_map_filter();

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

//Moshiur
        for (var t = 0; t < bd_all["dist"].features.length; t++) {
            $("#bd_info_district").append('<option value="' + bd_all["dist"].features[t].properties.dist_code + '">' + bd_all["dist"].features[t].properties.dist_name + '</option>');
        }
        $("#bd_info").on("change", function () {
            $("#bd_info_upazila").hide();

            $("#bd_info_district").empty();
            $("#bd_info_district").append('<option value="">--- select ---</option>');
            $("#bd_info_upazila").empty();
            $("#bd_info_upazila").append('<option value="">--- select ---</option>');
            for (var t = 0; t < bd_all["dist"].features.length; t++) {
                $("#bd_info_district").append('<option value="' + bd_all["dist"].features[t].properties.dist_code + '">' + bd_all["dist"].features[t].properties.dist_name + '</option>');
            }

        });

        $("#bd_info_district").on("change", function () {
//            map.setCenter(23.777777, 90.444444);
//            map.setZoom(7);

            if ($("#bd_info").val() == 'upaz') {
                $("#bd_info_upazila").show();
            }

            $("#bd_info_upazila").empty();
            $("#bd_info_upazila").append('<option value="">--- select ---</option>');

            var dstCode = $("#bd_info_district").val();
            for (var t = 0; t < bd_all["upaz"].features.length; t++) {
                //alert($("#bd_info_district").text());
                if (bd_all["upaz"].features[t].properties.dist_code == dstCode) {
                    $("#bd_info_upazila").append('<option value="' + bd_all["upaz"].features[t].properties.upaz_code + '">' + bd_all["upaz"].features[t].properties.upaz_name + '</option>');
                }
            }
            set_map_filter();
            //alert("OK");
            //  $("input[name='bd_info']:checked").val();
            //set_district_map(dstCode);

        });

        $("#bd_info_upazila").on("change", function () {
            set_map_filter();

//            var upzCode = $("#bd_info_upazila").val();
//            set_upazila_map(upzCode);
        });

        function set_map_filter() {
            var upzCode = $("#bd_info_upazila").val();
            if (upzCode) {
                set_upazila_map(upzCode);
                return;
            }

            var dstCode = $("#bd_info_district").val();
            set_district_map(dstCode);
        }

        function set_district_map(dist_id) {

            $("#busy-indicator").fadeIn();
            var dist_center;
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
                    else {
                        all_map_poly[t].map_poly.setMap(map);
                        map.setCenter(all_map_poly[t].center);
                        map.setZoom(9);
                        //break;
                    }
                }
            }

            var select_dist, bc = 0;
//            if (dist_id && dist_id != '') {
//                for (mc = 0; mc < markers.length; mc++) {
//                    if (markers[mc].dist_code == dist_id) {
//                        if (markers[mc].marker.getMap() == null)
//                            markers[mc].marker.setMap(map);
//                        ++bc;
//                    } else {
//                        if (markers[mc].marker.getMap() != null)
//                            markers[mc].marker.setMap(null);
//                    }
//                }
//            } else {
//                bc = markers.length;
//                for (mc = 0; mc < markers.length; mc++) {
//                    markers[mc].marker.setMap(null);
//                }
//            }
//

//            try {
////                select_dist = data_info.filter(data => data.dist_code === dist_id);
////                select_dist = data_info.filter(data => data.dist_code === dist_id);
////                console.log(select_dist);
////                console.log(select_dist[0]);
////                bc = select_dist.data_value;
////                var dist_center = select_dist.pos;
//                
//                                
//                map.setCenter(dist_center);
//                map.setZoom(10);
//                        //data_info = {pos: poly_center, admin_code: admin_code, data_admin_name: curr_admin_name, data_value: data_value};
//            } catch(ex) {
//console.log(ex);
//            }

            $("#branch_count").html("Total no. of Branches: " + bc);
            $("#busy-indicator").fadeOut();
            $('#map_info').fadeIn(500);
            return false;
        }


        function set_upazila_map(upaz_id) {


            $("#busy-indicator").fadeIn();

            for (var t = 0; t < bd_all["upaz"].features.length; t++) {
                if (all_map_poly[t].upaz_code != upaz_id)
                    all_map_poly[t].map_poly.setMap(null);
                else
                    all_map_poly[t].map_poly.setMap(map);
                map.setZoom(10);
            }

            var mc, bc = 0;

            try {
                bc = data_info.filter(data => data.dist_code === dist_id).map(data => data.data_value);
                //data_info = {pos: poly_center, admin_code: admin_code, data_admin_name: curr_admin_name, data_value: data_value};
            } catch (ex) {

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
        echo $this->Html->scriptBlock("map_data = $branch_infos; $('#bd_info').val('$admin_code'); map_init('$admin_code',23.777777, 90.444444,7);", array('inline' => true));
    }
    ?>

</div>
