
<style>

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

</style>


<div id="mapBranches" style="width: 100%; height: 580px; margin: 0; padding: 0;">
</div>


<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyCikNZIKgnf41sAFCmoQjQ2nEp7VbLrMEU"></script>

<!--<script type="text/javascript" src="/mra_web/js/map_google_api.js"></script>
<script type="text/javascript" src="/mra_web/js/map_tooltip.js"></script>-->


<script>

    function initialize() {

        // For more details see 
// http://code.google.com/apis/maps/documentation/javascript/styling.html
        var noPOILabels = [
            {
                featureType: "poi",
                elementType: "labels",
                stylers: [{visibility: "off"}]

            }
        ];

        // Create a new StyledMapType object, passing it the array of styles,
        // as well as the name to be displayed on the map type control.
        var noPOIMapType = new google.maps.StyledMapType(noPOILabels,
                {name: "NO POI"});

        // Create a map object, and include the MapTypeId to add
        // to the map type control.
        var mapOptions = {
            center: new google.maps.LatLng(23.777176, 90.399452), //(53.408841, -2.981397), // dhaka 23.777176, 90.399452 //kushtia 23.9032,89.0562 /23.95190383 89.1113115
            zoom: 7,
            mapTypeControlOptions: {
                mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'no_poi']
            }
        };
        var map = new google.maps.Map(document.getElementById('mapBranches'),
                mapOptions);

        //Associate the styled map with the MapTypeId and set it to display.
        map.mapTypes.set('no_poi', noPOIMapType);
        map.setMapTypeId('no_poi');
    }

    function set_branch_map(data) {
        var mapCanvas = document.getElementById("mapBranches");

        var mapOptions = {
            center: new google.maps.LatLng(23.777176, 90.399452), //(53.408841, -2.981397), // dhaka 23.777176, 90.399452 //kushtia 23.9032,89.0562 /23.95190383 89.1113115
            zoom: 7,
            styles: [
                {elementType: 'geometry', stylers: [{color: '#242f3e', visibility: "off"}]},
                {elementType: 'labels.text.stroke', stylers: [{color: '#242f3e', visibility: "off"}]},
                {elementType: 'labels.text.fill', stylers: [{color: '#746855', visibility: "off"}]},
                {
                    featureType: 'administrative.locality',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#d59563', visibility: "off"}]
                },
                {
                    featureType: 'poi',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#d59563', visibility: "off"}]
                },
                {
                    featureType: 'poi.park',
                    elementType: 'geometry',
                    stylers: [{color: '#263c3f', visibility: "off"}]
                },
                {
                    featureType: 'poi.park',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#6b9a76', visibility: "off"}]
                },
                {
                    featureType: 'road',
                    elementType: 'geometry',
                    stylers: [{color: '#38414e', visibility: "off"}]
                },
                {
                    featureType: 'road',
                    elementType: 'geometry.stroke',
                    stylers: [{color: '#212a37', visibility: "off"}]
                },
                {
                    featureType: 'road',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#9ca5b3', visibility: "off"}]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'geometry',
                    stylers: [{color: '#746855', visibility: "off"}]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'geometry.stroke',
                    stylers: [{color: '#1f2835', visibility: "off"}]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#f3d19c', visibility: "off"}]
                },
                {
                    featureType: 'transit',
                    elementType: 'geometry',
                    stylers: [{color: '#2f3948', visibility: "off"}]
                },
                {
                    featureType: 'transit.station',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#d59563', visibility: "off"}]
                },
                {
                    featureType: 'water',
                    elementType: 'geometry',
                    stylers: [{color: '#17263c', visibility: "off"}]
                },
                {
                    featureType: 'water',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#515c6d', visibility: "off"}]
                },
                {
                    featureType: 'water',
                    elementType: 'labels.text.stroke',
                    stylers: [{color: '#17263c', visibility: "off"}]
                }
            ]
        };

        var map = new google.maps.Map(mapCanvas, mapOptions);
        var icon = {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 4,
            fillColor: "#d32",
            fillOpacity: 1,
            strokeWeight: 1
        };
        var orgname, marker, i;
        for (i = 0; i < data.length; i++) {
            if (!data[i])
                continue;
            orgname = (data[i].org_name ? data[i].org_name.replace("<strong>", "").replace("</strong>", "") : null);
            marker = new google.maps.Marker({'map': map,
                'position': new google.maps.LatLng(data[i].lat, data[i].lon),
                'icon': icon,
                'title': "Branch Name: " + data[i].branch_name + (orgname ? "\nOrganization Name : " + orgname : "") //,
                        //'tooltip': "Branch Name: " + data[i].branch_name + (data[i].org_name ? "\nOrganization Name : " + data[i].org_name : "")
            });
            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    var infowindow = new google.maps.InfoWindow({
                        content: "<div class='info-div'>" +
                                "<h3><b>Organization Name :</b> <span style='color:#05c;'>" + data[i].org_name + "</span></h3>" +
                                "<h3><b>Branch Name :</b> <span style='color:#18d;'>" + data[i].branch_name + (data[i].branch_code ? " (" + data[i].branch_code + ")" : "") + "</span></h3>" +
                                "<h4><b>Road Name/Village : </b>" + data[i].road_name_or_village + "</h4>" +
                                "<h4><b>Mohalla/Post Office : </b> " + data[i].mohalla_or_post_office + "</h4>" +
                                "<h4><b>Mailing Address : </b> " + data[i].mailing_address + "</h4>" +
                                "<h4><b>Contract Info : </b> " + data[i].contract_info + "</h4>" +
                                "<div><img style='width:350px; height:250px; margin:0 auto; padding:2px 25px 3px 10px;' src='" +
                                document.location.href + "data/branches/" + data[i].file_name + "' /> </div>" +
                                "</div>"
                    });
                    infowindow.open(map, marker);
                };
            })(marker, i));

            i++;
        }
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
    echo $this->Html->scriptBlock("set_branch_map($branch_infos);", array('inline' => true));
}
?>

