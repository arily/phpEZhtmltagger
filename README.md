# phpEZhtmltagger
A F**KING EASY WAY TO WRITE HTML TAGS

### changelog
#### 0.0.3 
##### using multi-arguments in body. 
##### increase array-re-using-ability for such as top, footer and so on.
##### bug fix (rtn made a typo)
##### $$$$$ more memory cost
```php
$body = [[	'h1'=>'Your LangShiID:'],['br'=>''],['h2'=>file_get_contents('http://composer.subnet.arily.tk/api/wolf')]];

$html = new htmltagger();
html->setBody($body)->prn();
```
this' a 0.0.1-0.0.2 array set for phpEZhtmltagger.
in 0.0.3 we need make them separate.
```php
$h1 = ['h1'=>'Your LangShiID:'];
$br = ['br'=>'']; // empty string to output <**> and NULL to output <** />
$h2 = ['h2'=>file_get_contents('http://composer.subnet.arily.tk/api/wolf')];

$html = new htmltagger();
$html->setBody($h1,br,h2)->prn();
```
```html
	<body>
		<h1>Your LangShiID:</h1>
		<br>
		<h2>kAthcirnoAm</h2>
	</body>
```
#### 0.0.2 using buffer to increase speed and lowing cost. 
also we print \<head\> first even we aren't finish prepearing \<body\> so browser can pull js and css before we feed him \<body\>.
this'll get an instant-loading-speed-up-feeling because we might have a huge \<body\>.

#### 0.0.1 body - update: when there's nothing more than '__in'  you can simply write direct to array:
```php
$body=[
	[
		'h1'=>[
			'__in' =>'wowHeading'
		],
		//...
	]
]
```
can like:
```php
$body=[
	[
		'h1'=>'wowHeading'
		//...
	]
]
```
# How to use it 
include './class.php'

# What's it doing? 
a GREAT WAY to write your HTML tags forced you write ABSOLUTELY formatted.
and save it to your redis server if u want.

# OK, I want to try this sh*tty no-using buggy tagger so what I need to do? 
you can check the example.php. just MIND BLOWING easy.
## first let's write some fatty array!
```php
<?php #of course you need it

$head = [
  /* you put css in a array in the 'css',
   * then it'll write to <head>(here)</head>
   * just simple right?
   */
	'css'=>
		[
			'//resource.arily.moe/resource.main/style/LL.css',
			'//resource.arily.moe/resource.main/style/top.css'
		],
    'script' =>
        [
        // this could be harder for you cause every single script needs
        // a specific array to tell tagger what this script is,
        // and it just copied your input to tag <script type="(here)" src=""></script>
        // also I forget about location, that'll be put in src label just  ^-here!
            [
                'type'=>'text/javascript',
                'location'=>'//resource.arily.moe/Chart.js'
            ]
        ]
    /*...*/
];
```
### This' 0.0.1 Readme File below. watch out that we don't need put all things in arrays in array.
```php
$anything = [[...],[...]];
```
	if you want this work in 0.0.3
	you should do like this:
```php
$html = new htmltagger();
$html->sethead($head)->setBody($anything[0],$anything[1])->prn();
```
	or this
```php
$anything_1 = [....];
$anything_2 = [....];

$html = new htmltagger();
$html->sethead($head)->setBody($anything_1,$anything_2)->prn();
```
```php
$body = 
#       body's more complicate that you need to put all things in arrays in array.
#       like array(array('h1'=>. because we always using same tag names but array in php
//	don't allow duplicate keys with different containing.
#       also keep in mind that every tag you want to specify need a array to tell tagger 
##      what it's containing.
###     like 'h1'=>array('id'=>'',
##      what inside the tag uses key '__in' ,
#       'h1'=>array('id'=>'','__in' =>,
//      what inside the '__in'  can be array(s) for containing sth also.
#/	like 'div'=>array('id'=>'','__in' =>array('h1'=>... 
//
#//	with new version, if you just want inside but not any other arguments,
##/	it can be simply write like 'h1'=>'wowHeading';
#/#	
/**     
*/
[
	[
		'h1'=>'YES! easy html tagger using php!' //<- this' not wrong in new version. or you need to wtire like
	//this v
		'h1'=>[
			'__in' =>'YES! easy html tagger using php!(It\'s that f()king easy?????????'
		],
		'div'=>[
			'id'=>'hi_i_am_div',
		'__in' =>[
				'h2'=>[
					'id'=>'heading_2_inside_a_div!',
					'__in' =>'I\'m h2 inside the fatty div '.str_repeat('that in a array ',5).'.'
				]
			]
		]
	]
];
```

this you'll write an html tags like this:
```html
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="//resource.arily.moe/resource.main/style/LL.css">
		<link rel="stylesheet" type="text/css" href="//resource.arily.moe/resource.main/style/top.css">
		<script type="text/javascript" src="//resource.arily.moe/Chart.js"></script>
	</head>
PHP Notice:  Undefined offset: 0 in /home/webapp/info/easyhtml/class.php on line 245 (this won't happen with new code.)
	<body>
		<h1>YES! easy html tagger using php!(It's that f()king easy?????????</h1>
		<div id="hi_i_am_div">
			<h2 id="heading_2_inside_a_div!">I'm h2 inside the fatty div that in a array that in a array that in a array that in a array that in a array .</h2>
		</div>
	</body>
</html>
```
## In html, we use a lot of same tags in one div, how can I solve this?
That's can be ezzzzzz~ 
in phpEZhtmltagger all insideing can not only contains one object. 
instead of keep taping f(())king English that I totally non-undetstandable,
let's use another example:

	in this case you are going to crate two divs in a main div.
	that's <div id="main"> <div class="sub"> </div> <div class="sub"> </div> </div>.
	to crate two obj shares one tag, you can make every one of them into ().
	
	yes you got it. an array().
```php
$body = 
[
    [
        'h1'=>[
            '__in' =>'YES! easy html tagger using php!(It\'s that f()king easy?????????'
        ],
        'div'=>[
            'id'=>'hi_i_am_div',
        '__in' =>
            [
                [
                    'div'=>[
                        'id'=>'div_inside_a_div!',
                        '__in' =>'I\'m div inside the fatty div '.str_repeat('that in a array ',6).'.'
                    ]
                ],
                [
                    'div'=>[
                        'id'=>'div_inside_a_div_num_2!',
                        '__in' =>'I\'m another div inside the fatty div '.str_repeat('that in a array ',6).'.'
                    ]
                ]
            ]
        ]
    ]
];
```
this'll output like this:
```html
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="//resource.arily.moe/resource.main/style/LL.css">
		<link rel="stylesheet" type="text/css" href="//resource.arily.moe/resource.main/style/top.css">
		<script type="text/javascript" src="//resource.arily.moe/Chart.js"></script>
	</head>
	<body>
		<h1>YES! easy html tagger using php!(It's that f()king easy?????????</h1>
		<div id="hi_i_am_div">
			<div id="div_inside_a_div!">I'm div inside the fatty div that in a array that in a array that in a array that in a array that in a array that in a array .</div>
			<div id="div_inside_a_div_num_2!">I'm another div inside the fatty div that in a array that in a array that in a array that in a array that in a array that in a array .</div>
		</div>
	</body>
</html>
```

## wow! how can I change my ugly arrays into sth. like this?
	because there're high dimension arrays above you need to check it carefully at first.
our magic:
```php
include './class.php';

$html = new htmltagger();
$html->setHead($head);
$html->setBody($body);
$html->prn();
```
That's it.
## but wait, that's not enough! I want to set a title also! what I should do?

actually you have 2 ways to do it.
first you can just put your title in the $head array.
```php
$head = array();
$head['title'] = 'wowTitle';
```
```html
<head>
	<title>wowTitle</title>
</head>
```
also you can use method 'setTitle'.
I personally recommend this because once you decide to rename your website, it's a disaster to find 'title' in such a bunch of arrays and array in array in array.....
you can do it like this:
```php
$html = new htmltagger();
$html->setTitle('wowTitle');
```
## OK OK I did all stuffs you told me, I CAN't WAIT!!!!!
once you finished `designing` your website, this's sth. additional you can let it out:

```php
// use redis to cache your website once it's been phrased into html.
// redis server defaults setting to localhost:6379 and 300 seconds expire time.
$html->redis($host,$port,$exp_time);	
$html->setHead($head)->setBody($body)->setTitle('wowTitle')->prn();	//A cool chaining~
## of course you can write them in single line. but the print method must be last one.
$html->redis()->setHead($head)->setBody($body)->setTitle('wowTitle')->prn();
```
![final](https://github.com/arily/phpEZhtmltagger/blob/master/QQ20170427-195857.png)
# That's it. You are well on your way to write clear html tags without type any < and > ~
