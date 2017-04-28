<?php
include "./class.php";
$head = [
    'css'=>
        [
            '//resource.arily.moe/resource.main/style/LL.css',
            '//resource.arily.moe/resource.main/style/top.css'
        ],
    'script' =>
        [
            [
              'type'=>'text/javascript',
              'location'=>'//resource.arily.moe/Chart.js'
            ]
        ]
];

$body = 

[
    [
        'h1'=>[
            '__in'=>'YES! easy html tagger using php!(It\'s that f()king easy?????????'
        ],
        'div'=>[
            'id'=>'hi_i_am_div',
            '__in'=>[
                'h2'=>[
                    'id'=>'heading_2_inside_a_div!',
                    '__in'=>'I\'m h2 inside the fatty div '.str_repeat('that in a array ',5).'.'
                ]
            ]
        ]
    ]
];
$html = new htmltagger();
$html->redis()->setHead($head)->setBody($body)->setTitle('wowTitle')->prn();
