
<div id='map_info' class="map_info">

    <script async defer type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyCikNZIKgnf41sAFCmoQjQ2nEp7VbLrMEU"></script>

<!--    <script type="text/javascript" src="./js/jquery.js"></script>
<script type="text/javascript" src="./js/jquery-ui.js"></script>
<script type="text/javascript" src="./js/map_google_api.js?sensor=false&key=AIzaSyCikNZIKgnf41sAFCmoQjQ2nEp7VbLrMEU"></script>
<script type="text/javascript" src="./js/map_tooltip.js"></script>-->

    <style>

        /*        body {
                    overflow: hidden !important;
                }*/
        h3 {
            margin: 0;
            padding: 5px;
            font-size: 15px;
            font-weight: normal;
        }
        h4 {
            margin: 0;
            padding: 3px 5px;
            font-size: 13px;
            font-weight: normal;
        }
        .info-div {
            width: auto;
            max-width: 980px;
        }        

        .map_info {
            z-index: 990;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;

            width: 100%;
            height: 100vh;
            margin: 0;
            padding: 0;
            display: block;
        }

        .org_info {
            z-index: 995;
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
            width: 350px;
            margin: 0 10px;
            padding: 1px;
        }
        .branch_count {
            margin: 2px 0 0 13px;
            color: #047;
            font: bold 15px/1.0 Roboto, Arial, sans-serif;
            text-align: left;
        }

        .map_btn_content {            
            z-index: 995;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;

            width: 200px;
            height: auto;
            margin: 10px auto;
            padding: 0;
        }
        .map_btn_center, .map_btn_close {
            display: inline-block;
            width: 20px;
            margin: 0 3px;
            padding: 4px 7px;
            font: normal 21px/1.0 Roboto, Arial, sans-serif;
            color: #777;
            text-align: center;
            background-color: #fff;
            border-radius: 3px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.25);
            cursor: pointer;
        }
        .map_btn_center:hover, .map_btn_close:hover {
            color: #fa2413;
            background-color: #eee;
        }

        .map_filter_options {
            position: fixed;
            top: 50px;
            left: 0;
            right: 0;
            z-index: 999;
            border: 1px solid #ddd;
            width: 670px;
            height: 350px;            
            background: #f5f7f8 none repeat scroll 0 0;
            
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            -o-border-radius: 3px;
            border-radius: 3px;
        }

    </style>



    <div id="tt"><?php echo date('H:i:s'); ?></div>

    <div id="mapBranches" class="map_info"></div>

    <div class="map_btn_content">
        <div id="map_center" class="map_btn_center" title="Set initial position the map">+</div>
        <div id="map_close" class="map_btn_close" title="Close the map">&times;</div>        
    </div>

    <div class="map_filter_options">

    </div>

    <div class="org_info">
        <?php echo $this->Form->input('', array('id' => 'org_list', 'type' => 'select', 'class' => 'org_opt', 'options' => $org_list, 'value' => $org_id, 'empty' => '----------- All MFIs -----------', 'escape' => false, 'div' => false, 'label' => false)); ?>

        <div id="branch_count" class="branch_count"></div>
    </div>

    <script>

        var mapCanvas = document.getElementById("mapBranches");
        var bd_center = new google.maps.LatLng(23.777176, 90.399452);
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


        var markers = [];
        var all_branch_info = [];

        function RemoveMarkers() {
            for (i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
        }

        function set_branch_map(org_id) {


            var div = document.getElementById('tt');
            var d = new Date();
            //alert(d);
            div.innerHTML = div.innerHTML + " : " + d.toLocaleTimeString();

//            $("#tt").
//            var map, orgname, marker, icon, i;
//            var infowindow = new google.maps.InfoWindow({
//                maxWidth: 980,
//                infoBoxClearance: new google.maps.Size(1, 1),
//                disableAutoPan: false
//            });

//            for (i = 0; i < markers.length; i++) {
//                markers[i].setMap(null);
//            }

            var data;
            if (!org_id)
                data = all_branch_info;
            else
            {
                data = all_branch_info.filter(function (branch_info) {
                    return branch_info.org_id == org_id;
                });
            }

            $("#branch_count").html("Total no. of Branches: " + data.length);

            var mapOptions = {
                center: bd_center,
                zoom: 7,
                styles: mapStyle
            };

            var map = new google.maps.Map(mapCanvas, mapOptions);

            var icon = {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 4,
                fillColor: "#d43",
                fillOpacity: 1,
                strokeWeight: 1
            };

            var orgname, marker, dc;
            for (dc = 0; dc < data.length; dc++) {
                if (!data[dc])
                    continue;
                orgname = (data[dc].org_name ? data[dc].org_name.replace("<strong>", "").replace("</strong>", "") : null);
                marker = new google.maps.Marker({map: map,
                    position: new google.maps.LatLng(data[dc].lat, data[dc].lon),
                    icon: icon,
                    title: "Branch Name: " + data[dc].branch_name + (orgname ? "\nOrganization Name : " + orgname : "")
                });

                google.maps.event.addListener(marker, 'click', (function (marker, dc) {
                    return function () {
                        var infowindow = new google.maps.InfoWindow({
                            maxWidth: 980,
                            content: "<div class='info-div'>" +
                                    "<table><tr><td colspan='2'>" +
                                    "<h3><b>Organization Name :</b> <span style='color:#05c;'>" + data[dc].org_name + "</span></h3>" +
                                    "<h3><b>Branch Name :</b> <span style='color:#18d;'>" + data[dc].branch_name + (data[dc].branch_code ? " (" + data[dc].branch_code + ")" : "") + "</span></h3>" +
                                    "</td></tr><tr><td style='vertical-align:top;'>" +
                                    "<h4><b>Road Name/Village : </b>" + data[dc].road_name_or_village + "</h4>" +
                                    "<h4><b>Mohalla/Post Office : </b> " + data[dc].mohalla_or_post_office + "</h4>" +
                                    "<h4><b>Mailing Address : </b> " + data[dc].mailing_address + "</h4>" +
                                    "<h4><b>Contract Info : </b> " + data[dc].contract_info + "</h4>" +
                                    "</td><td>" +
                                    (!data[dc].file_name ? "" : "<div style='padding:3px 10px;'><img style='border:0 none; width:auto; max-height:240px; margin:0; padding:0;' src='./files/uploads/branches/" + data[dc].file_name + "' /></div>") +
                                    //"<div style='padding:3px 10px;'>" + (!data[dc].file_name ? "" : "<img style='border:0 none; width:auto; max-height:240px; margin:0; padding:0;' src='./files/uploads/branches/" + data[dc].file_name + "' />") + "</div>" +
                                    "</td></tr></table>" +
                                    "</div>"
                        });
                        infowindow.open(map, marker);
                    };
                })(marker, dc));

            }

            var d = new Date();
            //alert(d);
            div.innerHTML = div.innerHTML + " : " + d.toLocaleTimeString();

            /*
             for (i = 0; i < data.length; i++) {
             if (!data[i])
             continue;
             orgname = (data[i].org_name ? data[i].org_name.replace("<strong>", "").replace("</strong>", "") : null);
             marker = new google.maps.Marker({map: map,
             position: new google.maps.LatLng(data[i].lat, data[i].lon),
             icon: icon,
             title: "Branch Name: " + data[i].branch_name + (orgname ? "\nOrganization Name : " + orgname : "")
             });
             markers.push(marker);
             
             google.maps.event.addListener(marker, 'click', (function (marker, i) {
             return function () {
             infowindow.setContent("<div class='info-div'>" +
             "<h3><b>Organization Name :</b> <span style='color:#05c;'>" + data[i].org_name + "</span></h3>" +
             "<h3><b>Branch Name :</b> <span style='color:#18d;'>" + data[i].branch_name + (data[i].branch_code ? " (" + data[i].branch_code + ")" : "") + "</span></h3>" +
             "<h4><b>Road Name/Village : </b>" + data[i].road_name_or_village + "</h4>" +
             "<h4><b>Mohalla/Post Office : </b> " + data[i].mohalla_or_post_office + "</h4>" +
             "<h4><b>Mailing Address : </b> " + data[i].mailing_address + "</h4>" +
             "<h4><b>Contract Info : </b> " + data[i].contract_info + "</h4>" +
             (data[i].file_name ? "<div style='padding:2px 25px 3px 10px;'><img style='border:0 none; width:auto; max-height:250px; margin:0; padding:0;' src='./files/uploads/branches/" + data[i].file_name + "' /> </div>" : "") +
             "</div>");
             infowindow.open(map, marker);
             
             };
             })(marker, i));
             }
             */
            $('#map_info').fadeIn(750);
        }

        $(document).ready(function () {

            $("#org_list").change(function () {
                set_branch_map($("#org_list").val());
                return false;
            });

            $("#map_center").on('click', function () {
                map.setCenter(bd_center);
                map.setZoom(7);
            });

            $("#map_close").on('click', function () {
                if (!confirm('Are you sure to close map ?'))
                    return;
                $('#map_info').fadeOut(750, function () {
                    $(this).remove();
                });
            });

        });

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
        echo $this->Html->scriptBlock("all_branch_info = $branch_infos; set_branch_map($org_id);", array('inline' => true));
    }
    ?>

</div>
