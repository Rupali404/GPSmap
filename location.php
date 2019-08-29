<?php
  require 'connection.php';
?>
<html>
  <head><title>OpenLayers Marker Popups</title></head>
  <body>
  <div id="mapdiv"></div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/openlayers/2.11/lib/OpenLayers.js"></script>
  <script>
    map = new OpenLayers.Map("mapdiv");
    map.addLayer(new OpenLayers.Layer.OSM());

    epsg4326 =  new OpenLayers.Projection("EPSG:4326"); //WGS 1984 projection
    projectTo = map.getProjectionObject(); //The map projection (Spherical Mercator)

    var lonLat = new OpenLayers.LonLat(73.856255,18.516726).transform(epsg4326, projectTo);


    var zoom=9;
    map.setCenter (lonLat, zoom);

    var vectorLayer = new OpenLayers.Layer.Vector("Overlay");
          <?php 

  $select=mysqli_query($conn,"SELECT * FROM gps_map");
                  $row=mysqli_num_rows($select);
                  if($row){
                    while($result=mysqli_fetch_assoc($select)){
                    $id=$result['id'];
                    $place=$result['place'];
                    $lat=$result['lat'];
                    $longi=$result['longi'];
                    $zoom=$result['zoom'];

                    ?>
    // Define markers as "features" of the vector layer:
    var feature = new OpenLayers.Feature.Vector(
            new OpenLayers.Geometry.Point( <?php echo $lat;?>, <?php echo $longi;?> ).transform(epsg4326, projectTo),
            {description:'<?php echo $lat."<br> ".$longi."<br>".$place;?>'} ,
            {externalGraphic: 'marker.png', graphicHeight: 25, graphicWidth: 21, graphicXOffset:-12, graphicYOffset:-25  }
        );
    vectorLayer.addFeatures(feature);
        <?php
                    }
    }
  ?>

   


    map.addLayer(vectorLayer);


    //Add a selector control to the vectorLayer with popup functions
    var controls = {
      selector: new OpenLayers.Control.SelectFeature(vectorLayer, { onSelect: createPopup, onUnselect: destroyPopup })
    };

    function createPopup(feature) {
      feature.popup = new OpenLayers.Popup.FramedCloud("pop",
          feature.geometry.getBounds().getCenterLonLat(),
          null,
          '<div class="markerContent">'+feature.attributes.description+'</div>',
          null,
          true,
          function() { controls['selector'].unselectAll(); }
      );
      //feature.popup.closeOnMove = true;
      map.addPopup(feature.popup);
    }

    function destroyPopup(feature) {
      feature.popup.destroy();
      feature.popup = null;
    }

    map.addControl(controls['selector']);
    controls['selector'].activate();

  </script>
  <div id="explanation">Popup bubbles appearing when you click a marker. The marker content is set within a feature attribute</div>
</body></html>