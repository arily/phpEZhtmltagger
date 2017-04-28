<?php
include "./class.php";

$style = [
			'body:before{background-image: url(http://resource.arily.moe/resource.main/pictures/ppp.jpg);}'
		];

$script = [
            [
              'type'=>'text/javascript',
              'location'=>'//resource.arily.moe/Chart.js'
            ],
            [
              'type'=>'text/javascript',
              'location'=>'https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.0.js'
            ]
        ];

$css =	[
            '//resource.arily.moe/resource.main/style/LL.css',
            '//resource.arily.moe/resource.main/style/top.css',
            'https://netdna.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css',
            'https://resource.arily.moe/resource.main/style/awsomebutton.css'
        ];

$head = [
	'style' 	=> $style,
	'script'	=> $script,
	'css'		=> $css
];

$body = array (
  0 => 
  array (
    'div' => 
    array (
      'class' => 'top',
      '__in' => 
      array (
        'div' => 
        array (
          'id' => 'nav',
          '__in' => 
          array (
            0 => 
            array (
              'ul' => 
              array (
                'id' => 'menu',
                '__in' => 
                array (
                  0 => 
                  array (
                    'li' => 
                    array (
                      '__in' => 
                      array (
                        'a' => 
                        array (
                          '__in' => 
                          array (
                            'icon' => '☁',
                          ),
                        ),
                      ),
                    ),
                  ),
                  1 => 
                  array (
                    'li' => 
                    array (
                      '__in' => 
                      array (
                        0 => 
                        array (
                          'a' => 'arily.moe',
                        ),
                        1 => 
                        array (
                          'ul' => 
                          array (
                            '__in' => 
                            array (
                              0 => 
                              array (
                                'li' => 
                                array (
                                  '__in' => 
                                  array (
                                    'a' => 
                                    array (
                                      'href' => 'https://arily.moe',
                                      '__in' => '主页',
                                    ),
                                  ),
                                ),
                              ),
                              1 => 
                              array (
                                'li' => 
                                array (
                                  '__in' => 
                                  array (
                                    'hr' => NULL,
                                  ),
                                ),
                              ),
                              2 => 
                              array (
                                'li' => 
                                array (
                                  '__in' => 
                                  array (
                                    'a' => 
                                    array (
                                      'href' => 'https://cloud.arily.moe',
                                      '__in' => 
                                      array (
                                        0 => '- ☁ ',
                                        1 => 
                                        array (
                                          's' => 'owncloud',
                                        ),
                                        2 => ' nextcloud ☁ - ',
                                      ),
                                    ),
                                  ),
                                ),
                              ),
                              3 => 
                              array (
                                'li' => 
                                array (
                                  '__in' => 
                                  array (
                                    'a' => 
                                    array (
                                      'href' => 'https://fuckluna.arily.moe',
                                      '__in' => '🌇 ❤️ 草露娜 ❤️ 🌇',
                                    ),
                                  ),
                                ),
                              ),
                              4 => 
                              array (
                                'li' => 
                                array (
                                  '__in' => 
                                  array (
                                    'hr' => NULL,
                                  ),
                                ),
                              ),
                              5 => 
                              array (
                                'li' => 
                                array (
                                  '__in' => 
                                  array (
                                    'a' => 
                                    array (
                                      'href' => 'http://ss.arily.moe',
                                      '__in' => '低速Shadowsocks',
                                    ),
                                  ),
                                ),
                              ),
                              6 => 
                              array (
                                'li' => 
                                array (
                                  '__in' => 
                                  array (
                                    'a' => 
                                    array (
                                      'href' => 'http://info.arily.moe',
                                      '__in' => 'info.arily.moe',
                                    ),
                                  ),
                                ),
                              ),
                            ),
                          ),
                        ),
                      ),
                    ),
                  ),
                  2 => 
                  array (
                    'li' => 
                    array (
                      '__in' => 
                      array (
                        0 => 
                        array (
                          'a' => 'About',
                        ),
                        1 => 
                        array (
                          'ul' => 
                          array (
                            '__in' => 
                            array (
                              0 => 
                              array (
                                'li' => 
                                array (
                                  '__in' => 
                                  array (
                                    'a' => 
                                    array (
                                      'href' => 'http://osu.ppy.sh/u/1123053',
                                      '__in' => 'osu Mainpage',
                                      'target' => '_blank',
                                    ),
                                  ),
                                ),
                              ),
                              1 => 
                              array (
                                'li' => 
                                array (
                                  '__in' => 
                                  array (
                                    'hr' => NULL,
                                  ),
                                ),
                              ),
                              2 => 
                              array (
                                'li' => 
                                array (
                                  '__in' => 
                                  array (
                                    'a' => 
                                    array (
                                      'href' => 'http://writer.wa.vg/',
                                      '__in' => 'osu userpage Writer',
                                      'target' => '_blank',
                                    ),
                                  ),
                                ),
                              ),
                              3 => 
                              array (
                                'li' => 
                                array (
                                  '__in' => 
                                  array (
                                    'hr' => NULL,
                                  ),
                                ),
                              ),
                              4 => 
                              array (
                                'li' => 
                                array (
                                  '__in' => 
                                  array (
                                    'a' => 
                                    array (
                                      'href' => 'https://shop119432647.taobao.com/',
                                      '__in' => '我的淘宝',
                                      'target' => '_blank',
                                    ),
                                  ),
                                ),
                              ),
                            ),
                          ),
                        ),
                      ),
                    ),
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
    ),
  ),
);

$nice_button = ['button' => ['type' => 'button', 'class' => 'myButton', '__in' => '漂亮的按钮']];
$fuckluna_info = ['a' => ['href' => 'http://fuckluna.arily.moe/','style' => 'color:white;text-decoration:none;visited:color:white;','__in' => '顺便宣传下草露娜']];
$i = ['i' => ['class' => 'fa fa-download', '__in'=>'']];
$h1_containing_i_fuckluna_info = ['h1' => [ 'style' => 'font-size:65px;display:inline-block;padding-left:-370px;', '__in' => [$i,$fuckluna_info]]];
$h2_aricloud_cloudari = ['h2' => ['style' => 'padding-left:calc(50% + 10px);padding-top:10px;','__in' => '阿日云，云阿日。']];


$div_containing_h2_h1 = ['div'=> ['__in' =>[$h2_aricloud_cloudari, $h1_containing_i_fuckluna_info,$nice_button]]];

$div_containing_img_aricloud = ['div'=> ['__in' => ['img' =>['src' => '//arily.moe/page/images/AriCloud-1.png', 'class' => 'pic360', 'style' => 'float:left;', '__in' => NULL]]]];

$div_cdki_containing_div_containing_img_aricloud_div_containing_h2_h1 = ['div' => ['style' => 'height:335px;', '__in' => [$div_containing_img_aricloud,$div_containing_h2_h1]]];

$div_lovelive_containing_cdki_containing_div_containing_img_aricloud_div_containing_h2_h1 = ['div' => ['class' => 'lovelive', '__in' => $div_cdki_containing_div_containing_img_aricloud_div_containing_h2_h1]];

$h1_truth = ['h1' => ['style' => 'text-align: center;', '__in' => '事实上，根域那简洁专业大气的设计已经不复存在。']];

$wrap = ['div' => ['style' => ' height:380px;margin-left:-50%;margin-top:-20%;', 'class' => 'wrap', '__in' => [$h1_truth,$div_lovelive_containing_cdki_containing_div_containing_img_aricloud_div_containing_h2_h1]]];

$mainbody = ['div' => ['class' => 'mainbody', 'style'=> 'position:fixed;left:50%;rigth:50%;top:50%;bottom:50%;', '__in' => [$wrap]]];

$body['1'] =$mainbody;


$html = new htmltagger();
$html->redis()->setHead($head)->setBody($body)->setTitle('wowTitle')->prn();
