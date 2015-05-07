<?php
/**
 * 
 * @author  qiangjian@staff.sina.com.cn sunsky303@gmail.com
 * Date: 15/4/18
 * Time: 11:34
 * @version $Id: $
 * @since 1.0
 * @copyright Sina Corp.
 */

$html = <<<SCRIPT

<div id="word_user_{{IDX}}"  style="height:400px;width:50%;float:left;">
</div>
<script>
require(
    [
        'echarts',
        'echarts/chart/bar',
        'echarts/chart/pie',
        'echarts/chart/line',
        'echarts/chart/map'
    ],
    function (ec, defaultTheme) {
        var option = {
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient : 'vertical',
                x : 'left',
                data:[
//                '直达','营销广告','搜索引擎','邮件营销','联盟广告','视频广告','百度','谷歌','必应','其他'
                ]
            },
            toolbox: {
                show : true,
                feature : {
                    mark : {show: true},
                    dataView : {show: true, readOnly: false},
                    magicType : {
                        show: true,
                        type: ['pie', 'funnel']
                    },
                    restore : {show: true},
                    saveAsImage : {show: true}
                }
            },
            calculable : false,
            series : [
                {
                    name:'关键词粉丝数',
                    type:'pie',
                    selectedMode: 'single',
                    radius : [0, 70],

                    // for funnel
                    x: '20%',
                    width: '40%',
                    funnelAlign: 'right',
                    max: 1548,

                    itemStyle : {
                        normal : {
                            label : {
                                position : 'inner'
                            },
                            labelLine : {
                                show : false
                            }
                        }
                    },
                    data:[
                        {value:1, name:'{{USER}}'}
                    ]
                },
                {
                    name:'关键词粉丝数',
                    type:'pie',
                    radius : [100, 140],

                    // for funnel
//                    x: '60%',
                    width: '35%',
                    funnelAlign: 'left',
                    max: 1048,
                    data: {{DATA}}
                }
            ]
        };
        var myChart3 = ec.init(document.getElementById('word_user_{{IDX}}'));
        myChart3.setOption(option);

});
</script>
SCRIPT;
$keywordFansAPI = 'http://hackathon2015.sinaapp.com/qq4.php?file=qq/0565092e998f2dadfff9576e593bd12e.txt';
$keywordFans = curl_get($keywordFansAPI);
$keywordFans = json_decode($keywordFans, 1);
$keywordFans2 = array();
$fanHmtl = '';
$idx=0;
foreach ($keywordFans as $k => $v) {
//    rsort($v);
    $v = (array)$v;

    $fan = array();
    foreach($v as $user => $times){
        $fan[] = array(
            'name' => $user,
            'value' => intval($times),
        );
    }

    $fanHtml .= str_replace(array('{{USER}}','{{IDX}}','{{DATA}}'),array($k,$idx,json_encode($fan)), $html);
    ++$idx;
//    break;
}
echo <<<HTML
<h2 style="text-align: center;padding: 20px;">关键词粉丝</h2>
HTML;

echo $fanHtml;