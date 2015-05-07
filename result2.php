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
    curl_setopt_array($hdl, array(
        CURLOPT_RETURNTRANSFER => 1,
    ));
    $rt = curl_exec($hdl);
    curl_close($hdl);
    return $rt;
}

$filename = $_GET['file'];
$userAPI = curl_get('http://hackathon2015.sinaapp.com/qq.php?file=' . $filename);
$result = json_decode($userAPI, 1);
$users = (array)$result['users'];
$users2 = array();
foreach ($users as $k => $v) {
    $users2[] = array(
        'name' => ($k),
        'value' => $v
    );
}

$userLabels = array_keys($users);
$userTimes = array_values($users);

$keywordCountsAPI = 'http://hackathon2015.sinaapp.com/qq3.php?file=' . $filename;
$keywordCounts = curl_get($keywordCountsAPI);
$keywordCounts = json_decode($keywordCounts, 1);

$keywordCounts2 = array();
foreach ($keywordCounts as $k => $v) {
    $keywordCounts2[] = array(
        'name' => $k,
        'value' => $v
    );
}


?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>QQ Record:QQ群聊热点分析</title>
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
    <style>
        html {
            background-color: #fff;
        }
        body{
            padding-top: 0;
        }
        h1{
            font-size: 36px;
            margin: auto;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
<h1>QQ Record <span style="text-align: right;
  font-size: 16px;
  color: gray;
  margin: 0;">QQ群聊热点分析</span></h1>
<div id="graphic">
    <!--Step:1 Prepare a dom for ECharts which (must) has size (width & hight)-->
    <!--Step:1 为ECharts准备一个具备大小（宽高）的Dom-->
    <div id="top" class="chart" style="height:500px;border:1px solid #ccc;padding:10px;"></div>
    <div id="word_top" class="chart" style="height:500px;border:1px solid #ccc;padding:10px;"></div>
</div>


<!--Step:2 Import echarts.js-->
<!--Step:2 引入echarts.js-->
<script src="echarts/doc/asset/js/jquery.min.js"></script>
<!--<script src="echarts/src/echarts.js"></script>-->
<!--<script src="echarts/doc/asset/js/echartsTheme.js"></script>-->
<script type="text/javascript">
// Step:3 conifg ECharts's path, link to echarts.js from current page.
// Step:3 为模块加载器配置echarts的路径，从当前页面链接到echarts.js，定义所需图表路径
require.config({
    paths: {
        echarts: 'echarts/build/source/'
    }
});

// Step:4 require echarts and use it in the callback.
// Step:4 动态加载echarts然后在回调函数中开始使用，注意保持按需加载结构定义图表路径
require(
    [
        'echarts',
        'echarts/chart/bar',
        'echarts/chart/pie',
        'echarts/chart/line',
        'echarts/chart/map'
    ],
    function (ec, defaultTheme) {
        var myChart2 = ec.init(document.getElementById('top'));
        myChart2.setOption({
            title: {
                text: '发言最多用户TOP15',
                subtext: '总发言数：<?= $result['total'] ?>',
                x: 'center'
            },
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
//            legend: {
//                x: 'center',
//                y: 'bottom',
//                data: <?//= json_encode($userLabels) ?>
//            },
            toolbox: {
                show: true,
                feature: {
                    mark: {show: true},
                    dataView: {show: true, readOnly: false},
                    magicType: {
                        show: true,
                        type: ['pie', 'funnel']
                    },
                    restore: {show: true},
                    saveAsImage: {show: true}
                }
            },
            calculable: true,
            series: [
                {
                    name: '面积模式',
                    type: 'pie',
                    radius: [30, 110],
//                        center : ['75%', 200],
                    roseType: 'area',
                    x: '50%',               // for funnel
                    max: 40,                // for funnel
                    sort: 'ascending',     // for funnel
                    data: <?= json_encode($users2) ?>
                }
            ]
        });


        //--- 2 ---
        var myChart2 = ec.init(document.getElementById('word_top'));
        myChart2.setOption({
            title: {
                text: '关键词TOP15',
                subtext: '',
                x: 'center'
            },
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
//            legend: {
//                x: 'center',
//                y: 'bottom',
//                data:<?//= json_encode(array_keys($keywordCounts)) ?>
//            },
            toolbox: {
                show: true,
                feature: {
                    mark: {show: true},
                    dataView: {show: true, readOnly: false},
                    magicType: {
                        show: true,
                        type: ['pie', 'funnel']
                    },
                    restore: {show: true},
                    saveAsImage: {show: true}
                }
            },
            calculable: true,
            series: [
                {
                    name: '面积模式',
                    type: 'pie',
                    radius: [30, 110],
//                        center : ['75%', 200],
                    roseType: 'area',
                    x: '50%',               // for funnel
                    max: 40,                // for funnel
                    sort: 'ascending',     // for funnel
                    data:<?= json_encode($keywordCounts2) ?>
                }
            ]
        });


    }///env init
);

</script>
<?php
require_once('fans.php');
?>
<div style="height:50px; width:100%;clear: both;"></div>
<div style="background-image: url('2weima.png') ;background-repeat:no-repeat;background-position: center;clear: both;width:280px;height:280px;padding:100px;margin: 10px auto;"></div>
<div style="height:50px; width:100%;clear: both;"></div>
<!-- JiaThis Button BEGIN -->
<div class="jiathis_style_32x32" style="width: 280px;
  text-align: center;
  height: 40px;
  margin: 0 auto;">
    <a class="jiathis_button_qzone"></a>
    <a class="jiathis_button_tsina"></a>
    <a class="jiathis_button_tqq"></a>
    <a class="jiathis_button_renren"></a>
    <a class="jiathis_button_kaixin001"></a>
    <a href="http://www.jiathis.com/share" class="jiathis jiathis_txt jiathis_separator jtico jtico_jiathis" target="_blank"></a>
    <a class="jiathis_counter_style"></a>
</div>
<script type="text/javascript" >
    var jiathis_config={
        hideMore:false
    }
</script>
<script type="text/javascript" src="http://v3.jiathis.com/code/jia.js" charset="utf-8"></script>
<!-- JiaThis Button END -->

</body>

</html>
