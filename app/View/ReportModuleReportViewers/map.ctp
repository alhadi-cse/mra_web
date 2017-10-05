 // load the necessary scripts
     
     <?php 
//        echo $this->Html->script("jquery-1.12.2.min");
        echo $this->Html->script("maps.google");
        ?>
 
    <?php
      $map_options = array(
        "id"           => "map_canvas",
        "width"        => "500px",
        "height"       => "500px",
        "zoom"         => 13,
        "type"         => "ROADMAP",
        "localize"     => false,
        "latitude"     => 81.69847032728747,
        "longitude"    => 29.9514422416687,
//        "marker"       => true,
//        "markerIcon"   => "http://google-maps-icons.googlecode.com/files/home.png",
//        "markerShadow" => "http://google-maps-icons.googlecode.com/files/shadow.png",
        "infoWindow"   => true,
        "windowText"   => "My Position custom text"
      );
      
      
    ?>
 
    // print the default map
    <?php echo $this->GoogleMap->map(); 
    
    
    

//if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
//    echo $this->Js->writeBuffer();
?>

    
<script>
//var mapCanvas = document.getElementById("map");
//var mapOptions = {
//    center: new google.maps.LatLng(51.5, -0.2), zoom: 10
//};
//var map = new google.maps.Map(mapCanvas, mapOptions);
</script>
 
