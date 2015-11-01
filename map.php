<?php
require_once 'pristup.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="">

	<title>Map</title>
<!-- js knihovny -->
<script src="lib/leaflet/leaflet.js"></script>

<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-draw/v0.2.2/leaflet.draw.js'></script>
<script src="lib/js/jquery.js"></script>
<script src="module/myscript.js"></script>
<!-- styly -->
<link rel="stylesheet" href="lib/leaflet/leaflet.css" />
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-draw/v0.2.2/leaflet.draw.css' rel='stylesheet' />
<link rel="stylesheet" href="css/css/kraken.css" />
<link rel="stylesheet" href="css/map.css" />
<link rel="shortcut icon" href="img/sviti.png" />	

<!-- testy -->
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/Leaflet.fullscreen.min.js'></script>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/leaflet.fullscreen.css' rel='stylesheet' />

<style>
 .maps{
 
 background:rgb(218, 160, 85);
 }     
       
</style>

	
</head>

<body>
<div class="container">
<script> document.documentElement.className+=' js' </script>
<div class="space50">
</div>
<div class="menu_container">
<?php
  require_once 'module/usermenu.php';
  require_once 'module/menu.php';
?>
</div>
<div id="snippet--flashes"></div>

<div id="content_container" class="content_container">
<div id="map" class="map_container"></div>



<div class="form_container">
<div id="newlamp">
</div>
<div id="editace">

<div id="formular">

<div id="lampid"></div>
<div id="buttons"></div>
</div>
</div>



</div>
</div>

<script>
<?php
If(!empty($_POST)){

//spolecnost
If(!empty($_POST['select_company'])){
echo 'document.getElementById("company").value='.$_POST['select_company'].';';
}else{
echo 'console.log("Je POST není vybraná společnost.");';
}
//zoom
If(!empty($_POST['zoom'])){
echo 'var map = L.map(\'map\').setView(['.$_POST['lat_default'].', '.$_POST['lng_default'].'], '.$_POST['zoom'].');';

}else{
echo 'var map = L.map(\'map\').setView([49.5939, 17.2655], 14);';
}


//v postu je id
If(!empty($_POST['lamp_id'])){
//v postu je status
If(!empty($_POST['status'])){

//status lamp switch
If($_POST['status']=="switch_status"){
If(!empty($_POST['lamp_status'])){
If($_POST['lamp_status']=='-1'){
$_POST['lamp_status']=0;
}
echo "console.log('Přepinam do stavu".$_POST['lamp_status']."');";
mysqli_query($dataconection, "UPDATE lamp SET is_enabled =".$_POST['lamp_status']." WHERE id = ".$_POST['lamp_id'].";");
}else{
echo "console.log('Status lampy nedostupny');";
}
}else{
echo "console.log('Status :".$_POST['status']."');";
}

//status create lamp
If($_POST['status']=="create"){
  If($_POST['lamp_status']=='-1'){
  $_POST['lamp_status']=0;
  }
//if new controler  
  If($_POST['control']=="new"){
   mysqli_query($dataconection, "INSERT INTO control_gateway (ID_company, Name_control) VALUES ('".$_POST['select_company']."', '".$_POST['control_name']."');");
   $result=mysqli_query($dataconection, "SELECT ID_control FROM control_gateway ORDER BY ID_control DESC 
LIMIT 0 , 1 ");
$row = mysqli_fetch_array($result);
$_POST['control']=$row[0];
echo 'console.log("'.$row[0].'");';
  }      
      
If($_POST['lamp_id']=="new"){

echo 'console.log("tady");';
If($_POST['plan']=='-1'){
mysqli_query($dataconection, "INSERT INTO `lamp`(`is_enabled`, `long`, `lat`, `ID_control`, `ID_workload`, `set_workload`) VALUES (".$_POST['lamp_status'].",".$_POST['lat'].",".$_POST['lng'].",".$_POST['control'].",".$_POST['plan'].",".$_POST['workload'].");");
}else{
mysqli_query($dataconection, "INSERT INTO `lamp`(`is_enabled`, `long`, `lat`, `ID_control`, `ID_workload`) VALUES (".$_POST['lamp_status'].",".$_POST['lat'].",".$_POST['lng'].",".$_POST['control'].",".$_POST['plan'].");");
}
$result=mysqli_query($dataconection, "SELECT id FROM  `lamp` ORDER BY id DESC LIMIT 0 , 1;");
$row = mysqli_fetch_array($result);
$_POST['lamp_id']=$row[0];
}else{
If($_POST['plan']=='-1'){
mysqli_query($dataconection, "UPDATE  `lamps.lightmaster`.`lamp` SET  `is_enabled` =  ".$_POST['lamp_status'].",
`long` =  ".$_POST['lat'].",
`lat` =  ".$_POST['lng'].",
`ID_control` =  ".$_POST['control'].",
`set_workload` =  ".$_POST['workload'].",
`ID_workload` =  ".$_POST['plan']."
 WHERE id = ".$_POST['lamp_id'].";");
 }else{
  mysqli_query($dataconection, "UPDATE  `lamps.lightmaster`.`lamp` SET  `is_enabled` =  ".$_POST['lamp_status'].",
`long` =  ".$_POST['lat'].",
`lat` =  ".$_POST['lng'].",
`ID_control` =  ".$_POST['control'].",
`ID_workload` =  ".$_POST['plan']."
 WHERE id = ".$_POST['lamp_id'].";");
 }
}

}else{
echo "console.log('Status :".$_POST['status']."');";
}


//delete lamp
If($_POST['status']=="delete"){
echo 'console.log("UPDATE lamp SET x_deleted =\'0\' WHERE id = \"'.$_POST['lamp_id'].'\";");';
mysqli_query($dataconection, "UPDATE lamp SET x_deleted ='1' WHERE id = ".$_POST['lamp_id'].";");

}else{
echo "console.log('Status :".$_POST['status']."');";
}
}

}else{
echo 'console.log("Je POST není nastavená lampa.");';
}

//kdyz neni k dispozici
}else{
echo 'console.log("POST není k dispozici.");';
//inicializační místo
echo 'var map = L.map(\'map\').setView([49.5939, 17.2655], 13);'; 
}
echo 'select_company=document.getElementById("company").value;';

?>


	
// create a map in the "map" div, set the view to a given place and zoom
//var map = L.map('map').setView([49.5939, 17.2655], 13);
var markers = new L.LayerGroup().addTo(map);
//global variable
var edited_lamp=0;
var new_lamp=0;
var lamps = [];
// add an OpenStreetMap tile layer
L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);


//icon
var LeafIcon = L.Icon.extend({
    options: {
        shadowUrl: 'img/LightBulb-512.png',
        iconSize:     [15, 15],
        shadowSize:   [0, 0],
        iconAnchor:   [7, 7],
        shadowAnchor: [0, 0],
        popupAnchor:  [0, -4]
    }
});

//ikony konkretni 
var lampIcon = new LeafIcon({iconUrl: 'img/sviti.png'});
var lampIcon_nesviti = new LeafIcon({iconUrl: 'img/nesviti.png'});
var lampIcon_oznacena = new LeafIcon({iconUrl: 'img/oznacena.png'});
var lampIcon_edit = new LeafIcon({iconUrl: 'img/edit.png'});


//newlamp
create_lamp_button();
function create_lamp(){
unselect_all();
new_lamp=1;
document.getElementById("newlamp").innerHTML = '<span style="font-weight: bold">Pro vytvoření nové lampy klikněte do mapy.</span><br><button class="buttons pulk" type="Cancel_btt" onclick="new_lamp=0;create_lamp_button();">Zrušit</button>';
}

function create_lamp_button(){
 document.getElementById("newlamp").innerHTML = '<button id="new_lamp_btt" class="buttons" type="newlamp_btt" onclick="create_lamp();" style="width:100%;">Nová lampa</button>';
}


function form_Create(id){
//document.getElementById("formular").innerHTML = 0;
if(lamps[select_company][id].enabled=="1"){
var enabled = '<input id="check" type="checkbox" class="checkbox" onclick="set_status('+id+');" checked></input>';

if(lamps[select_company][id].workload_plan=="-1"){
var plan = '<div class="nazev_atributu">Aktuální výkon :</div>'+lamps[select_company][id].actual_workload+'<div class="nazev_atributu">Plán :</div>Manual<div class="nazev_atributu">Nastavený výkon:</div>'+lamps[select_company][id].workload;
}else{
var plan = '<div class="nazev_atributu">Aktuální výkon :</div>'+lamps[select_company][id].actual_workload+'<div class="nazev_atributu">Plán :</div>'+plan_list[select_company][lamps[select_company][id].workload_plan]+'<div class="nazev_atributu">Nastavený výkon:</div>Řízen plánem';
}

}else{
var enabled = '<input id="check" type="checkbox" class="checkbox" onclick="set_status('+id+');"></input>';
var plan = '';
}

document.getElementById("lampid").innerHTML = '<div class="description_lamp"><div class="nazev_atributu">ID Lampy:</div> '+id+'<div class="nazev_atributu">Kontroluje:</div>'+lamps[select_company][id].gate+'<div class="nazev_atributu">Zapnutá:</div>'+enabled+plan+'<div class="nazev_atributu">Délka:</div>'+lamps[select_company][id].getLatLng().lat+'<div class="nazev_atributu">Šířka:</div>'+lamps[select_company][id].getLatLng().lng+'</div></div>';
document.getElementById("buttons").innerHTML = '<button class="pulka buttons" type="Edit_btt" onclick="edit_lamp('+id+')">Editovat</button><button class="pulka buttons" type="Delete_btt" onclick="delete_lamp('+id+');">Smazat</button>';
};

function set_status(id){

if(document.getElementById("check").checked==true){
var zapnuto='1';
}else{
var zapnuto='-1';
}

obj={ 'lng_default':map.getCenter().lng,
      'lat_default':map.getCenter().lat,
      'zoom':map.getZoom(),
      'lamp_id' : id,
      'status' : 'switch_status',
      'lamp_status' : zapnuto,
      'select_company' : select_company
      };
post("",obj);
}

function delete_lamp(id){
if(confirm("Chcete smazat lampu "+id+"?")){
obj={ 'lng_default':map.getCenter().lng,
      'lat_default':map.getCenter().lat,
      'zoom':map.getZoom(),
      'lamp_id' : id,
      'status' : "delete",
      'select_company' : select_company
      };
post("",obj);
}else{
}
}

function unselect_all(){
for(id in lamps[select_company]){
if(lamps[select_company][id].enabled==1){
lamps[select_company][id].setIcon(lampIcon);
}else{
lamps[select_company][id].setIcon(lampIcon_nesviti);
}
}
document.getElementById("lampid").innerHTML = '';
document.getElementById("buttons").innerHTML = '';
}

function select_lamp(id){
unselect_all();   
lamps[select_company][id].setIcon(lampIcon_oznacena);
form_Create(id);
};




var lamps = [];
var company_list=[];
var plan_list=[];
var groups = {};

// add a marker in the given location, attach some popup content to it and open the popup
<?php

foreach($_SESSION['company'] as $ID_Company => $name ){
if(!empty($ID_Company)){
echo 'lamps["'.$ID_Company.'"]=[];';
echo 'company_list["'.$ID_Company.'"]=[];';
echo 'plan_list["'.$ID_Company.'"]=[];';

$result = mysqli_query($dataconection, "SELECT lamp.lat,lamp.long,lamp.id,Gate.Name_control,lamp.is_enabled,workload_plan.ID_PLAN,workload_plan.PLAN_NAME,lamp.set_workload FROM `company`
LEFT OUTER JOIN control_gateway AS Gate ON Gate.ID_company = company.ID_company
LEFT OUTER JOIN lamp ON lamp.ID_control = Gate.ID_control
LEFT OUTER JOIN workload_plan ON workload_plan.ID_PLAN = lamp.ID_workload
WHERE company.ID_company= ".$ID_Company." AND lamp.x_deleted = '0';");
if (!$result) { 
    echo 'console.log("'.$ID_Company.'");';
    die('console.log("Invalid query: ' . mysqli_error($dataconection).'");');
} 

$controls=mysqli_query($dataconection, "SELECT ID_control,Name_control FROM control_gateway WHERE ID_company = ".$ID_Company." AND x_deleted = '0';");
if (!$controls) {
    die('</script><div class="error">Invalid query: ' . mysqli_error($dataconection).'</div><script>');
}
//company_list je seznam spolecnosti a obsahuje na indexu id_spolecnosti vsechny jejich kontrolery
while ($company = mysqli_fetch_array($controls, MYSQLI_NUM)) {
echo 'company_list["'.$ID_Company.'"]['.$company[0].']= "'.$company[1].'";';
};


$plans=mysqli_query($dataconection, "SELECT ID_PLAN,PLAN_NAME FROM workload_plan WHERE ID_company = ".$ID_Company." AND x_deleted = '0';");
if (!$plans) {
    die('</script><div class="error">Invalid query: ' . mysqli_error($dataconection).'</div><script>');
} 
//plan_list je seznam planu spolecnosti
while ($plan = mysqli_fetch_array($plans, MYSQLI_NUM)) {
echo 'plan_list["'.$ID_Company.'"]['.$plan[0].']= "'.$plan[1].'";';
};
echo 'plan_list["'.$ID_Company.'"][-1]= "Manual";';

while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
if(!empty($row[0])){
if($row[4]==1){
echo 'lamps["'.$ID_Company.'"]['.$row[2].'] = L.marker(['.$row[0].', '.$row[1].'],{icon: lampIcon});';
}else{
echo 'lamps["'.$ID_Company.'"]['.$row[2].'] = L.marker(['.$row[0].', '.$row[1].'],{icon: lampIcon_nesviti});';
}
echo'lamps["'.$ID_Company.'"]['.$row[2].'].gate = "'.$row[3].'";';

$actual_workload=mysqli_query($dataconection, "SELECT logs.workload FROM `logs` WHERE logs.ID_lamp ='".$row[2]."' ORDER by logs.time DESC;");
$actual_workload=mysqli_fetch_array($actual_workload);
if(!empty($actual_workload[0])){
echo'lamps["'.$ID_Company.'"]['.$row[2].'].actual_workload = "'.$actual_workload[0].'";';
}else{
echo 'lamps["'.$ID_Company.'"]['.$row[2].'].actual_workload = "Neni k dispozici";';
}
echo 'lamps["'.$ID_Company.'"]['.$row[2].'].enabled = "'.$row[4].'";';

if(!empty($row[5])){
echo 'lamps["'.$ID_Company.'"]['.$row[2].'].workload_plan = "'.$row[5].'";';
}else{
echo 'lamps["'.$ID_Company.'"]['.$row[2].'].workload_plan = "-1";';
}
echo 'lamps["'.$ID_Company.'"]['.$row[2].'].workload = "'.$row[7].'";';
echo 'lamps["'.$ID_Company.'"]['.$row[2].'].company_list = company_list;

lamps["'.$ID_Company.'"]['.$row[2].'].on(\'click\',function editace(){
    if(edited_lamp==0&&new_lamp==0){     
    select_lamp('.$row[2].');
    }
    }
    ).on(\'dragend\',function upradecords(){
    if(edited_lamp!=0){
    update_cords('.$row[2].');
    }
    }
    );';
    };
    };
    echo 'groups.'.$name.' = new L.LayerGroup();';
    }
 } 
if(!empty($_POST['lamp_id'])){
if($_POST['status']!='delete'){
echo 'select_lamp("'.$_POST['lamp_id'].'");';
}
}

?>

//console.log(groups);

//L.control.layers(groups).addTo(map);
draw_map();

function update_cords(id){
document.getElementById("cords").innerHTML='<div class="nazev_atributu">Délka :</div><input type="text" id="lat" value="'+Math.round(lamps[select_company][id].getLatLng().lng*10000)/10000+'""></input></div></div><div class="nazev_atributu">Šířka :</div><input type="text" id="lng" value="'+Math.round(lamps[select_company][id].getLatLng().lat*10000)/10000+'">';
 
}

//formular pro editaci lampy 
function edit_lamp(id) {
    document.getElementById("newlamp").innerHTML = '';
    lamps[select_company][id].dragging.enable()
    lamps[select_company][id].setIcon(lampIcon_edit);
    edited_lamp=lamps[select_company][id].getLatLng();
    
    select='<select id="select_control" class="lamp_control" onchange="new_controler(this.value);">';
    for(temp in company_list[select_company]){
    select= select+'<option id='+company_list[select_company][temp]+' value="'+temp+'">'+company_list[select_company][temp]+'</option>';
      }
    select=select+'<option value="new">Nový kontroler</option></select><div id="new_input"></div>';
    
    
    if(lamps[select_company][id].enabled=="1"){
    enabled='<input id="check" style="check" type="checkbox" name="status" value="1" onclick="change_status('+id+');" checked>';
    }else{
    enabled='<input id="check" style="check" type="checkbox" name="status" value="1" onclick="change_status('+id+');">';
    }
    
    plan='<select id="select_plan" class="lamp_control" onchange="new_plan(this.value);">';
    for(temp in plan_list[select_company]){
    plan = plan + '<option id='+temp+' value="'+temp+'">'+plan_list[select_company][temp]+'</option>';
      }
    plan = plan + '</select>';
    
    if(lamps[select_company][id].workload_plan==undefined){
      //manual = -1
     lamps[select_company][id].workload_plan="-1";
    }
    if(lamps[select_company][id].workload_plan=="-1"){
    if(lamps[select_company][id].workload==undefined){
    lamps[select_company][id].workload='0';
    }
    workload='<div id="status_container" class="status_container"><div class="nazev_atributu">Plán:</div>'+plan+'<div id="Manual_container"><div class="nazev_atributu">Nastavený výkon :</div><input id="workload_input" type="number" class="number right" min="0" max="100" step="5" value="'+lamps[select_company][id].workload+'">'+'</div></div>';
    
    }else{    
    workload='<div id="status_container"  class="status_container"><div class="nazev_atributu">Plán:</div>'+plan+'<div id="Manual_container"><div class="nazev_atributu">Nastavený výkon :</div>'+lamps[select_company][id].workload+'</div></div>';
    }
    
    
    
    document.getElementById("lampid").innerHTML = '<div class="description_lamp"><div >ID Lampy:</div> '+id+'<div class="nazev_atributu">Kontroluje:</div>'+select+'<div class="nazev_atributu">Zapnutá:</div>'+enabled+workload+'<div id="cords"></div></div>';
    // pokud společnost nemá kontroler tak vytvarime spolu z lampou nový
    if (company_list[select_company].length == 0){
    new_controler("new");
    }
    //staré lampy
    if(id!='new'){
    document.getElementById(lamps[select_company][id].gate).selected =true;    
     }
    document.getElementById(lamps[select_company][id].workload_plan).selected =true;
    update_cords(id);
    
    
    
    if(id=='new'){
    document.getElementById("buttons").innerHTML = '<button class="buttons pulka" type="Save_btt" onclick="save_lamp(\'new\')">Vytvořit</button><button class="buttons pulka" type="Cancel_btt" onclick="cancel_new()">Zrušit</button>';
    }else{
    document.getElementById("buttons").innerHTML = '<button class="buttons pulka" type="Save_btt" onclick="save_lamp('+id+')">Uložit</button><button class="buttons pulka" type="Cancel_btt" onclick="cancel_edit('+id+')">Zrušit</button>';
    }
    
};

function change_status(id){
if(document.getElementById("check").checked==true){
document.getElementById("status_container").style.display = "block";
}else{
document.getElementById("status_container").style.display = "none";
}
}

function new_controler(choice){
if(choice=="new"){
   document.getElementById("new_input").innerHTML = '<div class="nazev_atributu">Jméno kontroleru:</div><input id="New_controler" type="text"></input>';
}else{
 document.getElementById("new_input").innerHTML = '';
}
}

function new_plan(choice){
if(choice=="-1"){
   document.getElementById("Manual_container").innerHTML = '<div class="nazev_atributu">Nastavený výkon :</div><input id="workload_input" type="number" class="number right" min="0" max="100" step="5" value="'+lamps[select_company][id].workload+'">';
}else{
document.getElementById("Manual_container").innerHTML = '<div class="nazev_atributu">Nastavený výkon :</div>Podle plánu';

}

}

function set_cords(id){
//console.log('set');
//var lat=Math.round(document.getElementById("lat").value*10000)/10000;
//var lng=Math.round(document.getElementById("lat").value*10000)/10000;                  
//var latlng = L.latLng(lat,lng);
//lamps[select_company][id].setLatLng(latlng);
}

function cancel_new(){
cont=confirm("Ztratíte nový bod chcete pokračovat?");
delete lamps[select_company]['new'];
 draw_map();
 unselect_all();
 create_lamp_button();
}

function cancel_edit(id){
  cont=confirm("Ztratíte změny bodu chcete pokračovat?");
 lamps[select_company][id].dragging.disable();
 lamps[select_company][id].setLatLng(edited_lamp);
 draw_map();
 console.log(id);
 select_lamp(id);
 create_lamp_button();
}

function save_lamp(id){
    lamps[select_company][id].dragging.disable();
    var controler_name;
    if(document.getElementById("check").checked==true){
      var zapnuto='1';
    }else{
      var zapnuto='-1';
    }
    if(document.getElementById("New_controler")==null){
    controler_name=0;
    }else{
    controler_name = document.getElementById("New_controler").value;
    }
    
    if(document.getElementById("select_plan").value=='-1'){
    workload = document.getElementById("workload_input").value;
    }else{
    workload = '-1';
    }
    
    
    
    obj={ 'lng_default':map.getCenter().lng,
      'lat_default':map.getCenter().lat,
      'zoom':map.getZoom(),
      'lamp_id' : id,
      'status': 'create',
      'lamp_status' : zapnuto,
      'select_company' : select_company,
      'lat':document.getElementById("lat").value,
      'lng':document.getElementById("lng").value,
      'control':document.getElementById("select_control").value,      
      'plan':document.getElementById("select_plan").value,
      'workload':workload,
      'control_name': controler_name
      
      
      
      };
    post("",obj);
    
    
    draw_map();
    select_lamp(id);   
    create_lamp_button();
}


function draw_map(){
edited_lamp=0;
markers.clearLayers();
for(id in lamps[select_company]){
lamps[select_company][id].addTo(markers);
}
markers.addTo(map)
create_lamp_button();
};


map.on('click',function(e){
 if(edited_lamp==0){
unselect_all()
if(new_lamp==0){
document.getElementById("new_lamp_btt").className = "buttons";
}
};
if(new_lamp==1){
console.log(e.latlng);
lamps[select_company]['new']= L.marker(e.latlng,{icon: lampIcon}).addTo(markers).on('click',function editace(){
    if(edited_lamp==0&&new_lamp==0){     
    select_lamp('new');
    }
    }
    ).on('dragend',function upradecords(){
    if(edited_lamp!=0){
    update_cords('new');
    }
    }
    );
new_lamp=0;
edit_lamp('new');
};
});

if(select_company==""){

document.getElementById("content_container").innerHTML='NEJSTE ČLENEM ŽÁDNÉ SPOLECNOSTI POZÁDEJTE ADMINA VASÍ SPOLECNOSTI O PŘIDÁNI NEBO SI ZAKUPTE LICENCI PRO NOVOU SPOLEČNOST';
}

//testy editovani polygonu
var featureGroup = L.featureGroup().addTo(map);
drawControl = new L.Control.Draw({
    draw : {
        polygon : {
          //barva plochy
          shapeOptions: {color: '#FF55FF'}
        },
        polyline : false,
        rectangle : false,
        circle : false,
        marker : false
    },
    edit : {
      featureGroup: featureGroup
    },
     position : 'topleft'
});
map.addControl(drawControl);
map.on('draw:edited', function (e) {
var layers = e.layers;
layers.eachLayer(function (layer) {
        //do whatever you want, most likely save back to db
        var layer = e.layers;
        var shape = layer.toGeoJSON()
        var shape_for_db = JSON.stringify(shape);
        console.log(shape_for_db)
    });
}); 
map.on('draw:created', function(e) {
      featureGroup.addLayer(e.layer);
      
      var layer = e.layer;
      var shape = layer.toGeoJSON()
      var shape_for_db = JSON.stringify(shape);
      console.log(shape_for_db)
  });
// konec tesu editu polygonu

   
</script>
</div>
  </body>
</html>
