<?php
//http://hackathon2015.sinaapp.com/qq.php?file=xxxxx.txt

// 获取结果
$filename = $_GET['file'];
$link = @mysql_connect(SAE_MYSQL_HOST_M . ':' . SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);

// 所有人总共发言次数和；（群的能量）
// 发言次数TOP10；（谁是活跃分子）
$sql = "select * from `app_hackathon2015`.`result_man` where filename=\"$filename\" order by total_say desc limit 15;";
$result = mysql_query($sql);
if ($result == false) {
    var_dump($sql);
    die("no record.");
}

$output = array();
while ($row = mysql_fetch_assoc($result)) {
    $output[$row['name']] = $row['total_say'];
}
mysql_free_result($result);

// 输出json，显示结果图表用
// ...
/*
{
    total: 99,
    users:[
        zhang: 11,
        li:12,
        san:33,
    ]
}
*/
$total = 0;
$sql = "select sum(total_say) as a from `app_hackathon2015`.`result_man` where filename=\"$filename\";";
$result = mysql_query($sql);
if ($result == false) {   
}
$row = mysql_fetch_assoc($result);
$total = $row['a'];
mysql_free_result($result);

$a = array("total"=>$total, "users"=>$output);

echo json_encode($a);
?>
