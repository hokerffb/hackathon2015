<?php
//http://hackathon2015.sinaapp.com/qq2.php?file=xxxxx.txt

// 获取结果
$filename = $_GET['file'];
$link = @mysql_connect(SAE_MYSQL_HOST_M . ':' . SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);

// 名词被提及次数TOP10；（热议的是什么东西）
// 谁提及了最多次TOP1的名词（谁是粉丝）
$sql = "select * from `app_hackathon2015`.`result_words` where filename=\"$filename\" order by name,count desc;";
$result = mysql_query($sql);
if ($result == false) {
    var_dump($sql);
    die("no record.");
}

$users = array();
while ($row = mysql_fetch_assoc($result)) {
    $users[$row['name']][$row['word']] = $row['count'];
}
mysql_free_result($result);

// 输出json，显示结果图表用
// ...
/*
{
    user1: [
        'word1'=>12,
        'word2'=>234
    ]
}
*/

// 排序并截取
function cmp($a, $b)
{
    if ($a == $b) {
        return 0;
    }
    return (count($a) > count($b)) ? -1 : 1;
}

uasort($users, "cmp");
$users = array_slice($users, 0, 6);

foreach ($users as $key => $value) {
    arsort($value);
    $users[$key] = array_slice($value, 0, 5);
}

echo json_encode($users);
?>
