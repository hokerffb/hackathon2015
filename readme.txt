# 测试

## json

所有人总共发言次数和；（群的能量）
发言次数TOP10；（谁是活跃分子）
http://hackathon2015.sinaapp.com/qq.php?file=qq/0565092e998f2dadfff9576e593bd12e.txt

名词被提及次数TOP10；（热议的是什么东西）
http://hackathon2015.sinaapp.com/qq3.php?file=qq/0565092e998f2dadfff9576e593bd12e.txt

谁提及了最多次TOP1的名词（谁是粉丝）
http://hackathon2015.sinaapp.com/qq4.php?file=qq/0565092e998f2dadfff9576e593bd12e.txt

## chart

http://hackathon2015.sinaapp.com/result2.php


# 文件

index.php

    上传QQ聊天记录到SAE的Storage的userfiles域中
    在SaeKV中保存这个文件的处理状态
    通过SaeTaskQueue开始一个后台任务，用于分析文件内容

analysis.php
    
    逐行分析Storage中的文件内容
    在SaeKV中保存实时的进度

chart.xml

    draw.io图表源文件
    

# 存储

Storage
    
    /userfiles


SaeKV

    key=$filename value="进度描述文本"
    进度描述文本的取值：running、line x、OK

# TaskQueue

qqtask

# MySQl

参考app_hackathon2015.sql

# Interface

所有人总共发言次数和；（群的能量）
发言次数TOP10；（谁是活跃分子）

```
{
    total: 99,
    users:[
        zhang: 11,
        li:12,
        san:33,
    ]
}
```

名词被提及次数TOP10；（热议的是什么东西）
谁提及了最多次TOP1的名词（谁是粉丝）

```
{
    word1: 12,
    word2: 33,
    word3:44,
}

{
    user1: [
        'word1'=>12,
        'word2'=>234
    ]
}

```


# TODO

考虑支持如下统计：
    所有人总共发言次数和；
    某个人总发言次数；
    所有人发言次数排名；
    发言次数TOP10；
    某个人更换昵称的次数；分别更换成了什么昵称；
    更换了0-n次昵称的人数统计；
    某个人总发言字数；
    所有人总发言字数排名；
    某个人发言表情数、图片数；
    所有人发言表情书、图片数排名；
    特定关键词被某个人提及次数排名；（例如“吃”，“买”，“门头沟”）
    某个人发言在一天内的时间分布；
    所有人发言在一天内的时间分布；


# Author

@0xFF_
pengyuwei@gmail.com

@ajian303
sunsky303@gmail.com

Toyshop Studio
2015.4.17-18