<!DOCTYPE html>
<html>
<head><title>OpenLayers Marker Popups</title></head>
  <form method = "POST" action = "" enctype="multipart/form-data" >
   place: <input id="place" name="place" type="text" placeholder="Type a place name"  required  /><BR><BR>

  Lat:<input id="lat" name="lat" type="text" placeholder="lat" class="width70"  value="" required  /><BR><BR>
  longi:<input id="longi" name="longi" type="text" placeholder="longi"  required  /><BR><BR>

  Zoom:<input id="zoom" name="zoom" type="text" placeholder="zoom"  required  /><BR><BR>

    <input type="file"  name = "image" ><BR>
    <input type = "submit" name = "locate" value = "Locate">
  </form>
</html>
<?php
require 'connection.php';
//error_reporting(E_ALL);
  if(isset($_POST['locate'])){
    $place=$_POST['place'];
    $lat=$_POST['lat'];
    $longi=$_POST['longi'];
    $zoom=$_POST['zoom'];

     $imgpath=$_FILES['image']['tmp_name'];
     if($imgpath){
          $img_binary = fread(fopen($imgpath, "r"), filesize($imgpath));
          $picture = base64_encode($img_binary);

          $insert=mysqli_query($conn,"INSERT INTO gps_map (place,lat,longi,zoom,image) VALUES ('$place','$lat','$longi','$zoom','$picture')");
            if($insert){
               //echo "inserted successfully";
                echo"<script language='javascript'>";
                echo'document.location.replace("./location.php")';
                echo"</script>";
            }else{
                echo $conn->error;
            }
    }else{
      echo "insert image";
    }
  }
  ?>
