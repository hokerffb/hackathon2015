<html xmlns=http://www.w3.org/1999/xhtml>
<meta http-equiv=Content-Type content="text/html;charset=utf-8">
<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
/*
Usage:
http://hackathon2015.sinaapp.com/analysis.php?file=xxxxx.txt&show=0
Used SAE service:
Storage, kvdb, task queue, saeSegment
*/

class Member {
    public $qq = 0; // QQ号码
    public $name = ''; // QQ昵称
    
    public $total_words = 0; // 总发言字数
    public $total_say = 0; // 总发言次数
    public $total_pics = 0; // 总发言包含图片数
    public $change_names = 0; // 修改昵称次数
    public $total_verb = 0; // 总动词数
    public $total_noun = 0; // 总名词数
    public $noun_top10 = array(); // 所有名词计数
    public $noun_rec = array(); // 所有名词和每个名词的次数

    public function copy($m) {
        $this->qq = $m->qq;
        $this->name = $m->name;
        $this->total_words = $m->total_words;
        $this->total_say = $m->total_say;
        $this->total_verb = $m->total_verb;
        $this->total_noun = $m->total_noun;
        $this->noun_top10 = $m->noun_top10;
        $this->noun_rec = $m->noun_rec;
    }
}

// 一共发现的所有的群成员对象集合
$members = array();

// 总状态机
// 0:刚开始的初始状态; 
// 1:聊天记录开始（发现QQ号和姓名）
// 2:聊天内容
// 流转：0-->1-->2-->1-->...
$step = 0;
define('STEP_INIT', 0);
define('STEP_QQ', 1);
define('STEP_CONTENT', 2);

// 从文件或者storage加载聊天记录内容
// return: 按行拆分的array
function loadfile($filename)
{
    $storage = new SaeStorage();
    $domain = 'userfiles';
    $destFileName = $filename;
    $text = $storage->read($domain, $destFileName); // read all
    
    $textarray = explode("\n", $text);
    
    return $textarray;
}

// 分析对话内容， 统计关键字
// $line:聊天内容中的一行
// return：分析失败返回NULL，成功返回更新后的member对象
function check_key($line, $member)
{
    $str = $line;
    $seg = new SaeSegment();

    $ret = $seg->segment($str, 1);
    if ($ret === false) {
        // int(-3) string(24) "context can not be empty"
        // var_dump($seg->errno(), $seg->errmsg());
        // echo "$str<br>";
        return NULL;
    }

    //echo $line;
    foreach ($ret as $v) {
        $word = $v["word"];
        $tag = $v["word_tag"];
        if (SaeSegment::POSTAG_ID_N == $tag) {
            if (array_key_exists($word, $member->noun_top10)) {
                $member->noun_top10[$word] += 1;
            } else {
                $member->noun_top10[$word] = 1;
            }
        }
        $member->total_words++;
    }
    if ($_GET['show'] == 1) {
        echo "$member->name: $str words+=>" . $member->total_words . " say=$member->total_say<br>";
    } else {
        echo "$member->name: $str words:" . $member->total_words . " say=$member->total_say<br>";
    }

    return $member;
}

// 识别某个人对话的开始和结束
// return : 1对话开始， 0对话内容
function check_start($line)
{
    global $members, $member;

    // /(\S+) (\S+) (\S+)\((\d+)\)/
    // 2015-4-17 9:21:59 nickname(1234567)
    $mode1 = preg_match("/(\S+) (\S+) (.*)\((\d+)\)/", $line, $matches);
    if ($mode1 == 0) {
        // 2015-4-17 15:00:34 nickname<name@qq.com>
        $mode2 = preg_match("/(\S+) (\S+) (.*)\<(\S+)\>/", $line, $matches);
        if ($mode2 == 0) {
            return 0;
        }
    }

    //echo "-------------------------------------------------------------------------------------<br />";
    $date = $matches[1];
    $time = $matches[2];
    $name = $matches[3];
    $qq = $matches[4];
    
    //echo "time=" . $time . " name=" . $name . " qq=" . $qq . "<br />";

    if (array_key_exists($qq, $members)) {
        $members[$qq]->name = $name;
        $members[$qq]->total_say = $members[$qq]->total_say + 1;
    } else {
        $member = new Member;
        $member->qq = $qq;
        $member->name = $name;
        $members[$qq] = $member;
        $members[$qq]->total_say = 1;
        // echo "+New member[" . count($members) . "]" . $qq . " " . $name . "<br />";
    }

    //echo $members[$qq]->name . " words=" . $members[$qq]->total_words . "<br />";
    $member = $members[$qq];
    
    return 1;
}

// return: 0: false, continue next; 1:OK
function check_line($line)
{
    global $step, $members, $member;
    $ret = 0;
    if ($_GET['show'] == 1) {
        //echo "[" . $step . "]" . $line . "<br>";
        echo $line . "<br>";
    }

    switch ($step) {
        case STEP_CONTENT:
            $memb = check_key($line, $member);
            if ($memb != NULL) {
                $members[$member->qq]->copy($memb);
            }
            $step = STEP_QQ;
            break;
        case STEP_QQ:
            $ret = check_start($line);
            if ($ret == 0) { // 本行是某人对话的内容
                $memb = check_key($line, $member);
                if ($memb != NULL) {
                    $members[$member->qq]->copy($memb);
                }
            } else {
                $step = STEP_CONTENT;
            }
            break;
        case STEP_INIT:
            $ret = check_start($line);
            if ($ret == 1) { // 本行是某人对话的开始
                $step = STEP_CONTENT;
                $ret = 0;
            }
            break;
        default:
            echo "***default***<br>";
            break;
    }

    //$members[$member->qq].copy($member);
    return $ret;
}
/*
QQ chat record example:

2015-4-17 9:59:26 彭昱玮(4073351)
还是三星

2015-4-17 9:59:45 小明(5973606)
nexus 6

2015-4-17 9:59:48 小郭(93881548)
锤子

2015-4-17 9:59:53 小晓(17531938)
华为 M7
*/

// 程序入口

$kv = new SaeKV();
$ret = $kv->init();
$step = STEP_INIT;
$member = new Member;
$filename = $_GET['file'];
$textarray = loadfile($filename);
$lineno = 0;
echo "等一会我先去散个步，筋斗云负责分析你的$filename...<br>";

// analysis
foreach ($textarray as $line) {
    $lineno++;
    $ret = $kv->set($filename, "line " . $lineno); // save live process
    $ret = check_line($line);
}

// show and save result
$link = mysql_connect(SAE_MYSQL_HOST_M . ':' . SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
mysql_select_db('app_hackathon2015');
$result = mysql_query("delete from `app_hackathon2015`.`result_man` where filename=\"$filename\"");
$result = mysql_query("delete from `app_hackathon2015`.`result_words` where filename=\"$filename\"");
$result = mysql_query("set names utf8;");
$i = 0;
foreach ($members as $memb) {
    $i++;
    echo $memb->name . " " . $memb->qq . " words=$memb->total_words say=$memb->total_say<br>";
    $name = str_replace("\"", "'", $memb->name);
    $sql = "INSERT INTO `app_hackathon2015`.`result_man` ";
    $sql = $sql . "(`filename`, `name`, `qq`, `total_words`, `total_say`) ";
    $sql = $sql . "VALUES ('$filename', '$name', '$memb->qq', '$memb->total_words', '$memb->total_say');";
    $result = mysql_query($sql);

    //
    foreach ($memb->noun_top10 as $key => $value) {
        $sql = "INSERT INTO `app_hackathon2015`.`result_words` (`filename`, `name`, `word` ,`count`) VALUES ('$filename', '$name', '$key', '$value');";
        $result = mysql_query($sql);
        echo $sql . "<br>";
    }
}
mysql_close($link);
$ret = $kv->set($filename, "OK");
?>