<?php 
require_once 'pristup.php';
?>
<html>
  <head>
  	<meta http-equiv="content-type" content="text/html;charset=utf-8" >
     
    <script src="lib/js/jquery.js"></script>
    <script src="lib/js/jquery-ui.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>   
<script src="module/myscript.js"></script>
    <script type="text/javascript">
    
    
    
   
    var default_chart_option = {
          backgroundColor: '#F3D2A7',
          title: 'Nastavené vytížení',
          vAxis: {title: 'Vytíženi(%)', ticks: [0,25,50,75,100] },
          hAxis: {title: 'Čas(HH:MM)', ticks: [{v: 0, f: '0:00'}, {v: 720, f: '12:00'}, {v: 1440, f: '24:00'}] },
          'width':680,
          'height':409,
          isStacked: true
        };
    
      google.load("visualization", "1", {packages:["corechart"]});
      //google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable(list_data);

        

        
         var chart = new google.visualization.AreaChart(document.getElementById('right_chart'));
        var option = default_chart_option;
        option['colors'] = ['red'];
        option['title']  = 'Naplánované vytížení' ;
        chart.draw(data, option);
      }
    </script>
    
    
  
    
<link rel="stylesheet" href="css/css/kraken.css" />
<link  rel="stylesheet" href="css/jquery-ui.css">
<link rel="stylesheet" href="css/map.css" />

<link rel="shortcut icon" href="img/sviti.png" />	
<style>
.plans{
 background:rgb(218, 160, 85);
}
.content_container{
  background: rgb(218, 160, 85);
  height: 555px;
  
}
.gaudge{
width: 120px;
  height: 120px;
  /*float: left;*/
  margin-left: 5px;
  padding-right: 50px;
}
.inputs{
margin-left:5px;
float: left;
}
.plan_container{
color:white;
}


.left{
 /*float:left;*/
 display: inline;
 height: 37px;
  width: 90px ;
}
.right{
 /*float:right; */
 display: inline;
 height: 37px;
 width: 90px ;
}
.plan_container{
height: 170px;
  float: left;
}

.slider_container{
  margin-left: auto;
  margin-right: auto;
  width: 100%;
  height: 100px;
  background: rgb(218, 160, 85);
  float: left;
}

.slider{
margin-top: 30px;
  margin-bottom: 5px;
  padding: 5px;
  width: 90%;
  margin-left: auto;
  margin-right: auto;
}
.ui-slider-handle{
  padding: 1px;
  margin-top: 4px;
  width: 5px;
  background: #fece2f
  }
  .buttons{
  height: 37px;
  vertical-align:baseline;
  }
  .select_container{
    margin-left: 15px;
  }
</style>
  </head>
  <body>
  
  
  
  <div class="container">
<div class="space50">
</div>
<div class="menu_container">
  <?php 
  require_once 'module/usermenu.php';
  require_once 'module/menu.php';
  ?>
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

//update
If(!empty($_POST['status'])){

$workload = explode(",",$_POST['workload']);
$values = explode(",",$_POST['values']);
$quary = "";

if($_POST['plan']!=-1){
mysqli_query($dataconection, "DELETE FROM Plans WHERE ID_workload_plan = ".$_POST['plan']." ;");

$ID_plan=$_POST['plan'];
}else{
mysqli_query($dataconection, "INSERT INTO  Workload_plan (`ID_COMPANY` ,`PLAN_NAME` ,`x_deleted`)
VALUES (".$_POST['select_company'].",  '".$_POST['plan_name']."',  '0'
);");

$result = mysqli_query($dataconection, "SELECT ID_PLAN FROM  `Workload_plan` ORDER BY ID_PLAN DESC LIMIT 1;");
$ID_plan = mysqli_fetch_array($result);
$ID_plan = $ID_plan[0];
echo 'console.log("'.$ID_plan.'");';

}


foreach($values as $ID_position => $value ){
if($quary==""){
$quary="INSERT INTO Plans (`ID_workload_plan`, `switch_time`, `switch_workload`) VALUES (".$ID_plan.",".$values[$ID_position].", ".$workload[$ID_position].")";
}else{
$quary=$quary." ,(".$ID_plan.",".$values[$ID_position].", ".$workload[$ID_position].")";
}
}
mysqli_query($dataconection, $quary);

echo 'console.log("'.$quary.'");';

}


//kdyz neni k dispozici
}else{
echo 'console.log("POST není k dispozici.");'; 
}
echo 'select_company=document.getElementById("company").value;';

echo 'var list_plans=[];';
foreach($_SESSION['company'] as $ID_Company => $name ){
if(!empty($ID_Company)){
echo 'list_plans['.$ID_Company.'] = ["'.$name.'"];';

$plans=mysqli_query($dataconection, "$dataconection, SELECT ID_PLAN,PLAN_NAME FROM Workload_plan WHERE ID_company = ".$ID_Company." AND x_deleted = '0';");
while ($plan = mysqli_fetch_array($plans, mysqli_NUM)) {
echo 'list_plans['.$ID_Company.']['.$plan[0].']=["'.$plan[1].'"];';
$workloads=mysqli_query($dataconection, "SELECT switch_time, switch_workload
FROM Plans
WHERE ID_workload_plan =".$plan[0]."
ORDER BY switch_time;");
while ($workload = mysqli_fetch_array($workloads, mysqli_NUM)) {


echo 'list_plans['.$ID_Company.']['.$plan[0].'].push(['.$workload[0].','.$workload[1].']);';

}
}
}
}
?>
</script>

<div id="content_container" class="content_container">
    <div id="slider_container" class="slider_container">
    <div id="slider" class="slider"></div>
    </div>
    <div id="plan_container" class="plan_container">
    <!--<div id="chart_div" class="gaudge"></div>-->
    
    <div id="inputs_container" class="inputs">
    <div id="select_container" class="select_container"></div>
 
    <div id="inputs" class="inputs">
    </div>
    </div>
    
    </div>
    
    <div id="chart_container" class="chart_container">
    <!--<div id="left_chart" class="left_chart chart" ></div> -->
    <div id="right_chart" class="right_chart chart"></div>
    </div>
  </div>
  </div>
  <script>
<?php

?>
var list_values=[];
var list_workload=[];
var list_options;
var list_data;
var select;

function new_plan(choice){

if(choice=="new_plan"){
    document.getElementById("inputs").innerHTML='';
    
    list_values = [720];
    list_workload = [0];
    
    list_options=undefined;    
    
    document.getElementById("new_input").innerHTML = '<div class="nazev_atributu">Jméno planu:</div><input id="New_plan_name" type="text"></input>';

}else{
 document.getElementById("new_input").innerHTML = '';
list_values=[];
list_workload=[]; 
for(k in list_plans[select_company][choice]){
if(k==0){
}else{
list_values.push(list_plans[select_company][choice][k][0]);
list_workload.push(list_plans[select_company][choice][k][1]);
}
}


 
}
list_options=undefined;
new_slider();
}
 
function create_select(plan){
select='<button class="buttons" type="Save_btt" onclick="create_plan();">Uložit plán</button><select id="select_plan" class="select_plan" style="width:50%;height: 39px;display:inline;margin-left: 5px;" onchange="new_plan(this.value);">';

for(temp in list_plans[select_company]){
if(temp==0){

}else{
    select= select+'<option id='+list_plans[select_company][temp][0]+' value="'+temp+'">'+list_plans[select_company][temp][0]+'</option>';
      
      }
}
select=select+'<option id="new_plan" value="new_plan">Nový plan</option></select><div id="new_input"></div>';

   
document.getElementById("select_container").innerHTML=select;

new_plan(document.getElementById("select_plan").value);
}
create_select();

function create_plan(){
if(document.getElementById("select_plan").value=="new_plan"){
plan_name=document.getElementById("New_plan_name").value;
plan_id=-1;
}else{
plan_name = list_plans[select_company][document.getElementById("select_plan").value][0];
plan_id = document.getElementById("select_plan").value;
}
string_workload='';
string_values='';
for(position in list_workload){

if(position==0){
string_workload=string_workload+list_workload[position];
string_values=string_values+list_values[position];
}else{
string_workload=string_workload+','+list_workload[position];
string_values=string_values+','+list_values[position];

}

}

obj={ 'status': 'update',
      'plan':plan_id,
      'plan_name' : plan_name,      
      'select_company' : select_company,
      'workload': string_workload,
      'values': string_values
      };

post("",obj);
}

function add_time(value){


build_data();
new_slider(value);
}

function compare(a, b){
return a-b
}

function new_slider(value){
var btt='';
var new_time;
var next_time;
var previous_time;
var move=0;
document.getElementById("slider_container").innerHTML='<div id="slider" class="slider"></div>';

if(value!=undefined){
      list_values.push(value);
      list_values.sort(compare);
      var new_place=list_values.indexOf(value);
      }


for(temp in list_values){
var workload_value;
var hodiny=Number(Math.floor(list_values[Number(temp)]/60));
var minuty=Math.round(((list_values[Number(temp)]/60)-Math.floor(list_values[Number(temp)]/60))*60);
if(hodiny<10){
hodiny="0"+hodiny;
}
if(minuty<10){
minuty="0"+minuty;
}

if(temp==new_place){
 workload_value=Number(work);
 list_workload[Number(temp)] = Number(work);
 move=1;
}else{
if(list_options!=undefined){
if(list_options[Number(temp)+2-move]!=undefined){
workload_value=list_options[Number(temp)+2-move][1];
list_workload[Number(temp)] =list_options[Number(temp)+2-move][1];
}else{
list_workload[Number(temp)]=0; 
workload_value=0;
}
}else{
//nastavení z databaze pokud bude

workload_value=list_workload[temp]
}
}

if(list_values[Number(temp)+1]!=undefined){
next_time=list_values[Number(temp)]+((list_values[Number(temp)+1]-list_values[Number(temp)])/2);
}else{ 
next_time=list_values[Number(temp)]+(1440-list_values[Number(temp)])/2;
}
if(list_values[Number(temp)-1]!=undefined){
previous_time=list_values[Number(temp)]-((list_values[Number(temp)]-list_values[Number(temp)-1])/2);
}else{
previous_time=list_values[temp]-list_values[temp]/2;
}


btt = btt + '<div id="input_'+temp+'">'+(Number(temp)+1)+'<input id="time_'+temp+'" class="time left" type="time" name="time" value="'+hodiny+':'+minuty+'" > <input id="workload_'+temp+'" type="text" class="number right" value ="'+workload_value+'" onchange="build_data();drawChart();"> <button class="buttons" type="button" onclick="work=document.getElementById(\'workload_'+temp+'\').value;add_time('+previous_time+')">Přidat před</button> <button class="buttons" type="button" onclick="work=document.getElementById(\'workload_'+temp+'\').value;add_time('+next_time+')"> Přidat za </button></div>';

} 
document.getElementById("inputs").innerHTML=btt;

build_data();
drawChart();

$(function() {
      
      
     $("#slider").slider({
         min: 0,
         max: 1439,
         values: list_values,
         start: function (event, ui) {
             var hIndex = $(ui.handle).index();
             $("#slider").slider("option", "step", 1);
             
             
         },
         slide: function (event, ui) {
         for(temp in ui.values){
             if(ui.values[Number(temp)+1]!=undefined){ 
             if (ui.values[Number(temp)] > ui.values[Number(temp)+1]) {
                 return false;
             }
             }
             list_values[Number(temp)]=ui.values[temp];
             var hodiny=Number(Math.floor(list_values[Number(temp)]/60));
              var minuty=Math.round(((list_values[Number(temp)]/60)-Math.floor(list_values[Number(temp)]/60))*60);
              if(hodiny<10){
              hodiny="0"+hodiny;
              }
              if(minuty<10){
              minuty="0"+minuty;
              }
             document.getElementById("time_"+temp).value=hodiny+':'+minuty;
             }
             build_data();
             
             document.getElementById("chart_container").innerHTML='<div id="right_chart" class="right_chart chart"></div>';
             drawChart();
         }
     });

 });
  };
  
function build_data(){
list_options=[];
list_options[0]=['Čas (HH:MM)', 'Vytížení'];
if(list_values.length!=0){
list_options[1]=[Number("0"),Number(document.getElementById("workload_"+(list_values.length-1)).value)];

for(temp in list_values){
list_workload[temp]=Number(document.getElementById("workload_"+temp).value);
var hodiny=Number(Math.floor(list_values[Number(temp)]/60));
var minuty=Math.round(((list_values[Number(temp)]/60)-Math.floor(list_values[Number(temp)]/60))*60);
if(hodiny<10){
hodiny="0"+hodiny;
}
if(minuty<10){
minuty="0"+minuty;
}
list_options[Number(temp)+2]=[list_values[Number(temp)],list_workload[Number(temp)]]
}
list_options[Number(temp)+3]=[Number("1440"),list_workload[Number(temp)]]
}

list_data=[];
list_data[0]=[list_options[0][0],list_options[0][1]];

for (position=1,temp = 0; temp < list_options.length; temp=temp+2,position=position+4){

if((list_options.length-temp)>1){
list_data[position]=[list_options[temp+1][0],list_options[temp+1][1]];
}
if((list_options.length-temp)>2){
list_data[position+1]=[list_options[temp+2][0],list_options[temp+1][1]];
list_data[position+2]=[list_options[temp+2][0],list_options[temp+2][1]];
}
if((list_options.length-temp)>3){
list_data[position+3]=[list_options[temp+3][0],list_options[temp+2][1]];
}

}

}
new_slider();
build_data();
drawChart();


function unselect_all(){
document.getElementById("select_container").innerHTML='';
document.getElementById("inputs").innerHTML='';

}
function draw_map(){
create_select();
new_slider();
build_data();
drawChart();
}

if(select_company==""){

document.getElementById("content_container").innerHTML='NEJSTE ČLENEM ŽÁDNÉ SPOLECNOSTI POZÁDEJTE ADMINA VASÍ SPOLECNOSTI O PŘIDÁNI NEBO SI ZAKUPTE LICENCI PRO NOVOU SPOLEČNOST';
}
</script>
  
  </body>
</html>
