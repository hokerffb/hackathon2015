<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>分析结果</title>
<?php
//http://hackathon2015.sinaapp.com/show.php?file=xxxxx.txt
$filename = $_GET['file'];
$kv = new SaeKV();
$ret = $kv->init();
$ret = $kv->get($filename);

$queue = new SaeTaskQueue('qqtask');
// if ($queue->curLength() > 0) {
if ($ret != "OK") {
    echo "<meta http-equiv=\"refresh\" content=\"5\">";
    die("Task already running..." . $ret);
}


// 显示结果图表
?>
        <meta http-equiv="refresh" content="1; url=result2.php?file=<?=$filename;?>" />
    </head>
<body>
<a href="hackathon2015.sinaapp.com/qq.php?file=<?=$filename;?>">
</body>
</html>
