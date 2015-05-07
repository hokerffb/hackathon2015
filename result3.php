<?php
/**
 *
 * @author  qiangjian@staff.sina.com.cn sunsky303@gmail.com
 * Date: 15/4/17
 * Time: 20:36
 * @version $Id: $
 * @since 1.0
 * @copyright Sina Corp.
 */
//$result = [
//    'total'=>99,
//	'users'=>[
//        'zhang'=> 11,
//		'li'=>12,
//		'san'=>33,
//	]
//];
function curl_get($url){
    $hdl = curl_init($url);
    curl_setopt_array($hdl, [
        CURLOPT_RETURNTRANSFER => 1,
    ]);
    $rt = curl_exec($hdl);
    curl_close($hdl);
    return $rt;
}

$result = curl_get('http://hackathon2015.sinaapp.com/qq.php?file=qq/0565092e998f2dadfff9576e593bd12e.txt');
$result = json_decode($result, 1);
$users = (array)$result['users'];
$users2 = [];
foreach ($users as $k => $v) {
    $users2[] = [
        'name' => $k,
        'value' => $v
    ];
}
$userLabels = array_keys($users);
$userTimes = array_values($users);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="echarts/doc/asset/css/font-awesome.min.css" rel="stylesheet">
    <link href="echarts/doc/asset/css/bootstrap.css" rel="stylesheet">
    <link href="echarts/doc/asset/css/carousel.css" rel="stylesheet">
    <link href="echarts/doc/asset/css/echartsHome.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="echarts/doc/example/www/js/echarts.js"></script>
    <script src="echarts/doc/asset/js/codemirror.js"></script>
    <script src="echarts/doc/asset/js/javascript.js"></script>

    <link href="echarts/doc/asset/css/codemirror.css" rel="stylesheet">
    <link href="echarts/doc/asset/css/monokai.css" rel="stylesheet">
</head>
<body>
<!-- Fixed navbar -->
<div class="navbar navbar-default navbar-fixed-top" role="navigation" id="head"></div>

<div class="container">
    <div class="row" style="margin:20px 0;">
        <div class='col-md-8'>
            <select style="width:100%;height:34px;" name="theme-select">
                <option selected="true" name='infographic'>infographic</option>
                <option name='macarons'>macarons</option>
                <option name='shine'>shine</option>
                <option name='dark'>dark</option>
                <option name='blue'>blue</option>
                <option name='green'>green</option>
                <option name='red'>red</option>
                <option name='gray'>gray</option>
                <option name="helianthus">helianthus</option>
                <option name='default'>default</option>
            </select>
        </div>
        <button class="btn btn-info col-md-4" type="button" onclick="saveAsImage()">Save As Image</button>
    </div>

    <div class="row-fluid">
        <div id="graphic" class="col-md-12">
            <div class="row-fluid">
                <div class="col-md-6">
                    <div md="main" class="chart"></div>
                </div><!--/col-md--->
                <div class="col-md-6">
                    <div md="main" class="chart"></div>
                </div><!--/col-md--->
            </div>
            <div class="row-fluid">
                <div class="col-md-6">
                    <div md="main" class="chart"></div>
                </div><!--/col-md--->
                <div class="col-md-6">
                    <div md="main" class="chart"></div>
                </div><!--/col-md--->
            </div>
            <div class="row-fluid">
                <div class="col-md-12">
                    <div md="main" class="chart" style="height:500px"></div>
                </div><!--/col-md--->
            </div>
            <div class="row-fluid">
                <div class="col-md-4">
                    <div md="main" class="chart" style="height:270px"></div>
                </div><!--/col-md--->
                <div class="col-md-4">
                    <div md="main" class="chart" style="height:270px"></div>
                </div><!--/col-md--->
                <div class="col-md-4">
                    <div md="main" class="chart" style="height:270px"></div>
                </div><!--/col-md--->
            </div>
            <div class="row-fluid">
                <div class="col-md-4">
                    <div md="main" class="chart" style="height:270px"></div>
                </div><!--/col-md--->
                <div class="col-md-4">
                    <div md="main" class="chart" style="height:270px"></div>
                </div><!--/col-md--->
                <div class="col-md-4">
                    <div md="main" class="chart" style="height:270px"></div>
                </div><!--/col-md--->
            </div>
        </div><!--/col-md--->
    </div><!--/row-->

    <div class="row">
        <button class="btn btn-info col-md-3" type="button" onclick="saveAsImage()">Save As Image</button>
        <div class='col-md-6'>
            <select style="width:100%;height:34px;" name="theme-select">
                <option selected="true" name='infographic'>infographic</option>
                <option name='macarons'>macarons</option>
                <option name='shine'>shine</option>
                <option name='dark'>dark</option>
                <option name='blue'>blue</option>
                <option name='green'>green</option>
                <option name='red'>red</option>
                <option name='gray'>gray</option>
                <option name="helianthus">helianthus</option>
                <option name='default'>default</option>
            </select>
        </div>
    </div>

</div><!--/.fluid-container-->

<footer id="footer"></footer>
<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="echarts/doc/asset/js/jquery.min.js"></script>
<script src="echarts/doc/asset/js/bootstrap.min.js"></script>
<script src="echarts/doc/asset/js/echartsTheme.js"></script>
</body>
</html>
