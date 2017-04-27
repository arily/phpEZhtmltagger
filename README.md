# phpEZhtmltagger
A F**KING EASY WAY TO WRITE HTML TAGS

# How to use it 
include './class.php'

# What's it doing? 
a GREAT WAY to write your HTML tags forced you write ABSOLUTELY formated.
and save it to your redis server if u want.

# OK, I want to try this sh*tty no-using buggy tagger so what I need to do? 
you can check the html.php. just MIND BLOWING easy.
## first let's write some fatty array!
```php
<?php #of coures you need it

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
        // this could be harder for you cause every sigle script needs
        // a specific array to tell tagger what this script is,
        // and it just copied your input to tag <scrpit type="(here)" src=""></script>
        // also I forget about location, that'll be put in src label just  ^-here!
            [
                'type'=>'text/javascript',
                'location'=>'//resource.arily.moe/Chart.js'
            ]
        ]
    /*...*/
];

$body = 
##      body are more complicated that you need to put all things in arrays in array.
##      like array(array('h1'=>.
##      also in mind that every tag you want to specify need a array to tell tagger what
##      it's containing.
##      like 'h1'=>array('id'=>'',
##      what inside the tag uses key 'inside',
##      'h1'=>array('id'=>'','inside'=>,
//      what inside the 'inside' can also be an array for containing sth.
/*      'div'=>array('id'=>'','inside'=>array('h1'=>
*/
[
	[
	//  'h1'=>'YES! easy html tagger using php!' <- this' wrong. you need to wtire like
	//this v
		'h1'=>[
			'inside'=>'YES! easy html tagger using php!(It\'s that f()king easy?????????'
		],
		'div'=>[
			'id'=>'hi_i_am_div',
		'inside'=>[
				'h2'=>[
					'id'=>'heading_2_inside_a_div!',
					'inside'=>'I\'m h2 inside the fatty div '.str_repeat('that in a array ',5).'.'
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
## wow! how can I change my ugly arrays into sth. like this?
	because there're high dimention arrays above you need to check it carefully at first.
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
# That's it. You are well on your way to write clear html tags without type any < and > ~
