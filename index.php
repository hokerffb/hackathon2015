<?php
// 上传文件后调用此函数
function runtask($filename)
{
    // save status
    $kv = new SaeKV();
    $ret = $kv->init();
    $ret = $kv->set($filename, "running");

    // start work
    $queue = new SaeTaskQueue('qqtask');
    $queue->addTask("/analysis.php?file=$filename&show=0");
    $ret = $queue->push();
    if ($ret === false) {
        var_dump($queue->errno(), $queue->errmsg());
    }

    $url = "http://hackathon2015.sinaapp.com/show.php?file=$filename";
    header("Location: $url");

    die("");
}

// 上传文件，并保存到SAE的Storage服务的userfiles域中
$file = isset($_FILES['attachment']) ? $_FILES['attachment'] : NULL;
if (!is_null($file)) {
    $file['error'] && die('上传失败！');
    $file['size'] || die('文件大小错误！');
    $storage = new SaeStorage();
    $domain = 'userfiles';
    $fileName = md5(uniqid()) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    //echo "uploading " . $file['name'] . "-->" . $fileName;
    $result = $storage->upload($domain, "qq/" . $fileName, $file['tmp_name']);
    //var_dump($result);

    runtask("qq/" . $fileName);
}
?>
<!DOCTYPE html>
<html xmlns=http://www.w3.org/1999/xhtml>
<meta http-equiv=Content-Type content="text/html;charset=utf-8">

<head>
    <title>群聊热点统计</title>
    <style>
        h1 {
            margin: 30px auto;
            text-align: center;
            color: rgb(56, 105, 99);
        }

        body {
            background: url("social.jpg") no-repeat center;
            background-color: rgba(236, 243, 251, 1);
        }

        .main {
            width: 700px;
            height: 200px;
            margin: 200px auto;
            background-color: rgba(236, 243, 251, 0.8);
            padding: 50px;
            text-align: center;
        }

        select, input[type="file"], input[type="submit"] {
            border: none;
            margin: 0;
            padding: 0;
        }

        .file {
            font-size: 14px;
            position: relative;
            display: inline-block;
            background: #D0EEFF;
            /*border: 1px solid #99D3F5;*/
            overflow: hidden;
            color: #1E88C7;
            text-decoration: none;
            text-indent: 0;
            /*line-height: 20px;*/
        }

        .file input {
            position: absolute;
            right: 0;
            top: 0;
            opacity: 0;
            width:400px;
            height: 30px;
        }

        .file:hover {
            background: #AADFFD;
            border-color: #78C3F3;
            color: #004974;
            text-decoration: none;
        }

        .main .btnInput, .file {
            font-size: 14px;
            /*float: left;*/
            /*display: block;*/
            text-align: center;
            background-color: #559fd0;
            border: 1px solid #559fd0;
            background-image: none;
            border-radius: 0;
            background-color: #fbfbfb;
            border: 1px solid #9d9d9d;
            box-shadow: none;
            -webkit-box-shadow: none;
            -moz-user-select: none;
            -webkit-user-select: none;
            background-color: #49afcd;
            background-image: -moz-linear-gradient(top, #5bc0de, #2f96b4);
            background-image: -ms-linear-gradient(top, #5bc0de, #2f96b4);
            background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#5bc0de), to(#2f96b4));
            background-image: -webkit-linear-gradient(top, #5bc0de, #2f96b4);
            background-image: -o-linear-gradient(top, #5bc0de, #2f96b4);
            background-image: linear-gradient(top, #5bc0de, #2f96b4);
            background-repeat: repeat-x;
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#5bc0de', endColorstr='#2f96b4', GradientType=0);
            border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
            filter: progid:dximagetransform.microsoft.gradient(enabled=false);
            color: #fff;
            height: 30px;
            line-height: 30px;
        }


    </style>
</head>

<body>
<!--暂时不支持中文名称的文件-->

<div class="main">
    <h1>群聊热点统计</h1>

    <form id="form-d" method="POST" action="index.php" enctype="multipart/form-data">
        <a id="drop-area" style="width:400px;" href="javascript:;" class="file btnInput"><span id="filename">选择文件</span>
            <input id="btn-file" type="file" name="attachment"/>
        </a>
<!--        <input style="width:200px;height: 32px;margin-left:5px;" class="btnInput" type="submit" value="Go">-->
<!--        </input>-->
    </form>
    <script>
        var dropArea = document.getElementById('drop-area');
        var filesUpload = document.getElementById('btn-file');
        filesUpload.addEventListener("change", function () {
            traverseFiles(this.files);
        }, false);

//        dropArea.addEventListener("dragleave", function (evt) {
//            var target = evt.target;
//
//            if (target && target === dropArea) {
//                this.className = "";
//            }
//            evt.preventDefault();
//            evt.stopPropagation();
//        }, false);
//
//        dropArea.addEventListener("dragenter", function (evt) {
//            this.className = "over";
//            evt.preventDefault();
//            evt.stopPropagation();
//        }, false);
//
//        dropArea.addEventListener("dragover", function (evt) {
//            evt.preventDefault();
//            evt.stopPropagation();
//        }, false);
//
//        dropArea.addEventListener("drop", function (evt) {
//            traverseFiles(evt.dataTransfer.files);
//            this.className = "";
//            evt.preventDefault();
//            evt.stopPropagation();
//        }, false);
        function traverseFiles (files) {
            document.getElementById('filename').innerText = files[0].name;
            setTimeout(function(){
//                document.getElementById('form-d').submit();
                console.dir(filesUpload)
                document.forms[0].submit();
            },1000);
        }


    </script>
</div>
</body>

</html>