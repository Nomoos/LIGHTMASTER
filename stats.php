<?php
require_once 'pristup.php';
?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    <script type="text/javascript">

        <?php

        //vstup pro generováni logu
        //mysqli_query($dataconection, "INSERT INTO  logs (`ID_lamp` ,`time` ,`workload`) VALUES ();");



        echo 'var list_logs=[];';

        $ID_Company = $_SESSION['company'];
        if(!empty($ID_Company)){
        echo 'list_logs['.$ID_Company.'] = [];';


        $result = mysqli_query($dataconection, "SELECT lamp.id FROM `company`
        LEFT OUTER JOIN control_gateway ON control_gateway.ID_company = company.ID_company
        LEFT OUTER JOIN lamp ON lamp.ID_control = control_gateway.ID_control
        WHERE company.ID_company='".$ID_Company."' AND lamp.x_deleted = '0';");
        if (!$result) {
            die('Invalid query: ' . mysqli_error($dataconectios));
        }
        while ($lamp = mysqli_fetch_array($result, MYSQLI_NUM)) {
        echo 'list_logs['.$ID_Company.']['.$lamp[0].']=[];';


        $resultlogs = mysqli_query($dataconection, "SELECT logs.ID_lamp,logs.time,logs.workload FROM `logs` WHERE logs.ID_lamp ='".$lamp[0]."' ORDER by logs.time;");
        if (!$resultlogs) {
            die('Invalid query: ' . mysqli_error($dataconection));
        }
        while ($log = mysqli_fetch_array($resultlogs, MYSQLI_NUM)) {
        echo 'list_logs['.$ID_Company.']['.$log[0].'].push(['.$log[1].','.$log[2].']);';

        }
        }
        }

        ?>
        var list_data = [];
        var selected_lamp;
        var aktual_workload;
        var mean_workload;

        var default_chart_option = {
            backgroundColor: '#F3D2A7',
            title: 'Reálné vytížení',
            vAxis: {title: 'Vytíženi(%)'},
            hAxis: {title: 'Čas(HH:MM)'},
            isStacked: true
        };


        google.load("visualization", "1", {packages: ["gauge"]});
        google.setOnLoadCallback(drawGauge);
        function drawGauge() {
            if (aktual_workload != undefined && mean_workload != undefined) {
                var data = google.visualization.arrayToDataTable([
                    ['Label', 'Value'],
                    ['%', aktual_workload],
                    ['%', mean_workload]
                ]);

                var options = {
                    legend: {position: 'bottom'},

                    width: 400, height: 120,
                    redFrom: 90, redTo: 100,
                    yellowFrom: 75, yellowTo: 90,
                    minorTicks: 5
                };

                var chart = new google.visualization.Gauge(document.getElementById('chart_div'));

                chart.draw(data, options);
            } else {
                document.getElementById('gaudge_container').innerHTML = "NEDOSTUPNA DATA PRO VYKRESLENI GRAFU";
            }
        }
    </script>
    <script src="lib/js/jquery.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    <script type="text/javascript">
        var default_chart_option = {
            backgroundColor: '#F3D2A7',
            title: 'Nastavené vytížení',
            vAxis: {title: 'Vytíženi(%)', ticks: [0, 25, 50, 75, 100]},
            hAxis: {title: 'Čas(HH:MM)', ticks: [{v: 0, f: '0:00'}, {v: 720, f: '12:00'}, {v: 1440, f: '24:00'}]},
            'width': 750,
            'height': 450,
            isStacked: true
        };

        google.load("visualization", "1", {packages: ["corechart"]});
        //google.setOnLoadCallback(drawChart);
        function drawChart() {
            if (list_data.length != 0) {
                var data = google.visualization.arrayToDataTable(list_data);


                var chart = new google.visualization.AreaChart(document.getElementById('left_chart'));
                var option = default_chart_option;
                option['colors'] = ['red'];
                option['title'] = 'Reálné vytížení (včera)';
                chart.draw(data, option);
            } else {
                document.getElementById('left_chart').innerHTML = "";
            }
        }


        function unselect_all() {

        }
        function draw_map() {
            create_select();
            build_data();
        }

    </script>

    <link rel="stylesheet" href="css/css/kraken.css"/>
    <link rel="stylesheet" href="css/map.css"/>

    <link rel="shortcut icon" href="img/sviti.png"/>
    <style>
        .stats {
            background: rgb(218, 160, 85);
        }

        .content_container {
            background: rgb(218, 160, 85);
            height: 655px;
        }

        .gaudge_container {
            margin-left: 5px;
        }

        .select_lamp {
            height: 39px;
            display: inline;
            margin-left: 5px;
            width: auto;
            margin-top: 5px;
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
        <script>
            <?php
            If(!empty($_POST)){

            //spolecnost
            If(!empty($_POST['select_company'])){
            echo 'document.getElementById("company").value='.$_POST['select_company'].';';
            }else{
            echo 'console.log("Je POST není vybraná společnost.");';
            }
            //kdyz neni k dispozici
            }else{
            echo 'console.log("POST není k dispozici.");';
            }
            echo 'select_company=document.getElementById("company").value;';
              ?>


        </script>
    </div>
    <div id="content_container" class="content_container">


        <div class="chart_container">
            <div style="display:inline;margin-left:5px">Vybraná lampa</div>
            <div id="select_container" class="select_container" style="display:inline"></div>
            <div id="gaudge_container" class="gaudge_container">
                <h5 style="margin-left: 10px;margin-bottom: 0px;display: inline;">Aktuální vytížení</h5>
                <h5 style="margin-left: 10px;margin-bottom: 0px;display: inline;">Průměrné vytížení</h5>

                <div id="chart_div" style="width: 400px; height: 120px;"></div>
            </div>
            <div id="left_chart" class="left_chart chart"></div>

            <div id="right_chart" class="right_chart chart"></div>
        </div>
    </div>
</div>
</body>
</html>
<script>
    function create_select() {
        var first;
        select = '<select id="select_lamp" class="select_lamp" onchange="selected_lamp=this.value;change_lamp();">';

        for (temp in list_logs[select_company]) {
            if (temp == 0) {

            } else {
                if (first == undefined) {
                    first = temp;
                }
                select = select + '<option id="lamp_' + temp + '" value="' + temp + '">Lampa #' + temp + '</option>';

            }
        }
        select = select + '</div>';

        document.getElementById("select_container").innerHTML = select;

        document.getElementById("select_lamp").value = first;
        selected_lamp = document.getElementById("select_lamp").value;
        change_lamp();
    }
    create_select();

    function change_lamp() {
        console.log(selected_lamp);

        build_data();
        drawGauge();
        drawChart();

    }


    function build_data() {
        list_data = [];
        mean_workload = undefined;
        aktual_workload = undefined;
        if (select_company != "") {
            if (list_logs[select_company][selected_lamp] != undefined) {
                if (list_logs[select_company][selected_lamp].length != 0) {
                    list_data[0] = ['Čas (HH:MM)', 'Vytížení'];
                    list_data[1] = [0, list_logs[select_company][selected_lamp][list_logs[select_company][selected_lamp].length - 1][1]];
                    list_data[2] = [list_logs[select_company][selected_lamp][0][0], list_logs[select_company][selected_lamp][list_logs[select_company][selected_lamp].length - 1][1]];

                    var count = 0;
                    for (var i = 0, n = list_logs[select_company][selected_lamp].length; i < n; i++) {
                        count += list_logs[select_company][selected_lamp][i][1];
                    }
                    mean_workload = Math.round(count / i);

                    aktual_workload = list_logs[select_company][selected_lamp][list_logs[select_company][selected_lamp].length - 1][1];


                    for (position = 3, temp = 0; temp <= list_logs[select_company][selected_lamp].length; temp = temp + 2, position = position + 4) {

                        if ((list_logs[select_company][selected_lamp].length - temp) >= 1) {
                            list_data[position] = [list_logs[select_company][selected_lamp][temp][0], list_logs[select_company][selected_lamp][temp][1]];
                        }
                        if ((list_logs[select_company][selected_lamp].length - temp) >= 2) {
                            list_data[position + 1] = [list_logs[select_company][selected_lamp][temp + 1][0], list_logs[select_company][selected_lamp][temp][1]];
                            list_data[position + 2] = [list_logs[select_company][selected_lamp][temp + 1][0], list_logs[select_company][selected_lamp][temp + 1][1]];
                        }
                        if ((list_logs[select_company][selected_lamp].length - temp) >= 3) {
                            list_data[position + 3] = [list_logs[select_company][selected_lamp][temp + 2][0], list_logs[select_company][selected_lamp][temp + 1][1]];
                        }
                    }
                    list_data[list_data.length] = [1440, list_logs[select_company][selected_lamp][list_logs[select_company][selected_lamp].length - 1][1]];
                } else {
                    console.log("Nemám logy k lampě " + selected_lamp);
                }
            } else {
                console.log("Ve společnosti " + select_company + " není lampa " + selected_lamp);
            }
        } else {
            console.log("Nevybrana spolecnost");

        }

    }
    if (select_company == "") {

        document.getElementById("content_container").innerHTML = 'NEJSTE ČLENEM ŽÁDNÉ SPOLECNOSTI POZÁDEJTE ADMINA VASÍ SPOLECNOSTI O PŘIDÁNI NEBO SI ZAKUPTE LICENCI PRO NOVOU SPOLEČNOST';
    }

</script>