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
<script src="lib/js/jquery.js"></script>
<script src="module/myscript.js"></script>
<!-- styly -->
<link rel="stylesheet" href="lib/leaflet/leaflet.css" />
<link rel="stylesheet" href="css/css/kraken.css" />	
<style>
      body{
        background: #FFE4C2;
      }
      .buttons:hover{
      background: rgb(255, 228, 194);
    }
    .buttons{
    padding: 3px;
    border: 2px;
    background: #F3D2A7;

      }
      #map {
        height: 500px;
        width: 88%;
        position:absolute;
      }
      .item{
        display: inline;
      }
      last{
        margin-right: 5px;
      }
      .nav, .nav_select, .menu, .menu_container, .item{
      background: #C99F6A;
      font-family: "Helvetica Neue", Arial, sans-serif;
      color: white;
      }
      .nav{
        float:right;         
      }
      .nav_select{
        display: inline;
        width:auto;
        margin-bottom: 0px;
        border-bottom: 0px;
        border-top: 0px;
        border-color: white;
      }
      .menu{
        float:left;
        padding:3px;
      }
      .menu_container{
      }
      a.link {
        padding-right: 5px;
        padding-left: 5px;
        padding-bottom: 7px;
        padding-top: 6px;
        text-decoration: none;
        font-family: "Helvetica Neue", Arial, sans-serif;
        color: white;
      }
      a.link:hover {
        background: #FFE4C2;
        text-decoration: none;
        color: black;
      }
      a.link:active {
      background: #F3D2A7;
      color: white;
      }
      .space50{
      height : 50px;
      }
      .space31{
      height : 31px;
      }
      .form_container{
      color: white;
      margin-top: 30px;
      float: right;
      padding:5px;
      width: 15%;
      position: relative;
      background: #C99F6A;
      width: auto;
      }
      .container{
      height: 581px;
      /*background:#DEB887; */
      padding-left: 3px;
      padding-right: 3px;
      }
      .newlamp{
      
      }
      .nazev_atributu {
      font-weight: bold;
      color: bisque;
      }
      .pulka{
      width:50%;
      }
      .grey{
      background:lightgray;
      }
      .red{
      background:lightcoral;
      }
      .green{
      background:lightgreen;
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

<div class="content_container">
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

//v postu je id
If(!empty($_POST['lamp_id'])){
//v postu je status
If(!empty($_POST['status'])){

//status lamp
If($_POST['status']=="switch_status"){
If(!empty($_POST['lamp_status'])){
If($_POST['lamp_status']){
$lamp_status=1;
}else{
$lamp_status=0;
}
mysql_query("UPDATE lamp SET is_enabled =".$lamp_status." WHERE id = ".$_POST['lamp_id'].";");
}
}

//create lamp
If($_POST['status']=="create"){
If($_POST['lamp_status']){
$lamp_status=1;
}else{
$lamp_status=0;
}
  
  If($_POST['control']=="new"){
   mysql_query("INSERT INTO Control_gateway (ID_company, Name_control) VALUES ('".$_POST['select_company']."', '".$_POST['control_name']."');");
   $result=mysql_query("SELECT ID_control FROM Control_gateway ORDER BY ID_control DESC 
LIMIT 0 , 1 ");
$row = mysql_fetch_array($result);
$_POST['control']=$row;
echo 'console.log("'.$row[0].'");';
  }
      
      
If($_POST['lamp_id']=="new"){
echo 'console.log("tady");';
mysql_query("INSERT INTO `lamp`(`is_enabled`, `long`, `lat`, `ID_control`) VALUES (".$lamp_status.",".$_POST['lat'].",".$_POST['lng'].",".$_POST['control'].");");
}
}else{
echo "console.log('Status".$_POST['status']."');";
}


//delete lamp
If($_POST['status']=="delete"){
echo 'console.log("UPDATE lamp SET x_deleted =\'0\' WHERE id = \"'.$_POST['lamp_id'].'\";");';
mysql_query("UPDATE lamp SET x_deleted ='1' WHERE id = ".$_POST['lamp_id'].";");

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
}
echo 'select_company=document.getElementById("company").value;';

?>


	
// create a map in the "map" div, set the view to a given place and zoom
var map = L.map('map').setView([49.5939, 17.2655], 13);
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
var lampIcon_oznacena = new LeafIcon({iconUrl: 'img/oznacena.png'});
var lampIcon_edit = new LeafIcon({iconUrl: 'img/edit.png'});


//newlamp
create_lamp_button();
function create_lamp(){
unselect_all();
new_lamp=1;
document.getElementById("newlamp").innerHTML = '<span style="font-weight: bold">Pro vytvoření nové lampy klikněte do mapy.</span><br><button class="buttons" type="Cancel_btt" onclick="new_lamp=0;create_lamp_button();">Zrusit</button>';
}
function create_lamp_button(){
 document.getElementById("newlamp").innerHTML = '<button class="buttons" type="newlamp_btt" onclick="create_lamp();">Nová lampa</button>';
}


function form_Create(id){
//document.getElementById("formular").innerHTML = 0;
if(lamps[select_company][id].enabled=="1"){
enabled = '<input type="checkbox" class="checkbox" onclick="set_status('+id+',0);" checked></input>';
}else{
enabled = '<input type="checkbox" class="checkbox" onclick="set_status('+id+',1);"></input>';
}

document.getElementById("lampid").innerHTML = '<div class="description_lamp"><div class="nazev_atributu">ID Lampy:</div> '+id+'<div class="nazev_atributu">Kontroluje:</div>'+lamps[select_company][id].gate+'</div><div class="nazev_atributu">Rozsvícená:</div>'+enabled+'</div><div class="nazev_atributu">Délka:</div>'+lamps[select_company][id].getLatLng().lat+'</div><div class="nazev_atributu">Šířka:</div>'+lamps[select_company][id].getLatLng().lng+'</div>';
document.getElementById("buttons").innerHTML = '<button class="pulka buttons" type="Edit_btt" onclick="edit_lamp('+id+')">Editovat</button><button class="pulka buttons" type="Delete_btt" onclick="delete_lamp('+id+');">Smazat</button>';
};

function set_status(id,status){
obj={ 'lamp_id' : id,
      'status' : 'switch_status',
      'lamp_status' : status+1,
      'select_company' : select_company
      };
post("",obj);
}

function delete_lamp(id){
if(confirm("Chcete smazat lampu "+id+"?")){
obj={ 'lamp_id' : id,
      'status' : "delete",
      'select_company' : select_company
      };
post("",obj);
}else{
}
}

function unselect_all(){
for(id in lamps[select_company]){
lamps[select_company][id].setIcon(lampIcon);
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
var groups = {};

// add a marker in the given location, attach some popup content to it and open the popup
<?php
foreach($_SESSION['company'] as $ID_Company => $name ){
echo 'lamps["'.$ID_Company.'"]=[];';
echo 'company_list["'.$ID_Company.'"]=[];';

$result = mysql_query("SELECT lamp.lat,lamp.long,lamp.id,Gate.Name_control,lamp.is_enabled FROM `Company`
LEFT OUTER JOIN Control_gateway AS Gate ON Gate.ID_company = Company.ID_company
LEFT OUTER JOIN lamp ON lamp.ID_control = Gate.ID_control
WHERE Company.ID_company=".$ID_Company." AND lamp.x_deleted = '0';");
  

$controls=mysql_query("SELECT ID_control,Name_control FROM Control_gateway WHERE ID_company = ".$ID_Company." AND x_deleted = '0';");

while ($company = mysql_fetch_array($controls, MYSQL_NUM)) {
echo 'company_list["'.$ID_Company.'"]['.$company[0].']= "'.$company[1].'";';
};

while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
if(!empty($row[0])){
echo 'lamps["'.$ID_Company.'"]['.$row[2].'] = L.marker(['.$row[0].', '.$row[1].'],{icon: lampIcon});
lamps["'.$ID_Company.'"]['.$row[2].'].gate = "'.$row[3].'";
lamps["'.$ID_Company.'"]['.$row[2].'].enabled = "'.$row[4].'";
lamps["'.$ID_Company.'"]['.$row[2].'].company_list = company_list;

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
    select= select+'<option value="'+temp+'">'+company_list[select_company][temp]+'</option>';
      }
    select=select+'<option value="new">Nový kontroler</option></select><div id="new_input"></div>';
    
    if(lamps[select_company][id].enabled=="1"){
    enabled='<input id="check" style="check" type="checkbox" name="status" value="1" checked>';
    }else{
    enabled='<input id="check" style="check" type="checkbox" name="status" value="1">';
    }
    
    document.getElementById("lampid").innerHTML = '<div class="description_lamp"><div class="nazev_atributu">ID Lampy:</div> '+id+'<div class="nazev_atributu">Kontroluje:</div>'+select+'<div class="nazev_atributu">Zapnutá:</div>'+enabled+'<div id="cords"></div></div>';
    
    update_cords(id);
    
    
    
    if(id=='new'){
    document.getElementById("buttons").innerHTML = '<button class="buttons" type="Save_btt" onclick="save_lamp(\'new\')">Uložit</button><button class="buttons" type="Cancel_btt" onclick="cancel_new()">Cancel</button>';
    }else{
    document.getElementById("buttons").innerHTML = '<button class="buttons" type="Save_btt" onclick="save_lamp('+id+')">Uložit</button><button class="buttons" type="Cancel_btt" onclick="cancel_edit('+id+')">Cancel</button>';
    }
};

function new_controler(choice){
if(choice=="new"){
   document.getElementById("new_input").innerHTML = '<div class="nazev_atributu">Jméno kontroleru:</div><input id="New_controler" type="text"></input>';
}else{
 document.getElementById("new_input").innerHTML = '';
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
    if(document.getElementById("New_controler")==null){
    controler_name=0;
    }else{
    controler_name = document.getElementById("New_controler").value;
    }
    obj={ 'lamp_id' : id,
      'status': 'create',
      'lamp_status' : document.getElementById("check").checked,
      'select_company' : select_company,
      'lat':document.getElementById("lat").value,
      'lng':document.getElementById("lng").value,
      'control':document.getElementById("select_control").value,
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
</script>
</div>
  </body>
</html>
