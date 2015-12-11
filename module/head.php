<?php
function get_head($page){
    ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="">

    <title>Lightmaster</title>
    <?php if($page=='map'){ ?>
    <!-- js knihovny -->
    <script src="lib/leaflet/leaflet.js"></script>

    <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-draw/v0.2.2/leaflet.draw.js'></script>
    <script src="lib/js/jquery.js"></script>
    <!-- styly -->
    <link rel="stylesheet" href="lib/leaflet/leaflet.css"/>
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-draw/v0.2.2/leaflet.draw.css' rel='stylesheet'/>

        <link rel="stylesheet" href="css/map.css"/>
    <?php } ?>
        <link rel="stylesheet" href="css/css/kraken.css"/>
    <link rel="stylesheet" href="css/table.css"/>
    <link rel="shortcut icon" href="img/sviti.png"/>


    <style>


    </style>


</head>
<?php
}
?>