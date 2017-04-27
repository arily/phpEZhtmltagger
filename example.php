<?php
include "./class.php";

$head = 
[
	'icon'=>'favicon.svg',
	'script'=>
		[
			[
			'type'=>'text/javascript',
			'location'=>'//resource.arily.moe/Chart.js'
			]
		],
	'css'=>
		[
			'//resource.arily.moe/resource.main/style/LL.css',
			'//resource.arily.moe/resource.main/style/top.css'
		]
];


$body = 
[
	[
		'h1'=>
			[
				'inside'=>'IT\'S JUST F**KING EAZY DON\'t IT ?'
			],
		'div'=>
			[
				'class'=>'myClass',
				'inside'=>
					[
						[
							'h2'=>'why Iub Title',
						],
						[
							'h2'=>
								[
									'class'=>'h2withclass',
									'inside'=>'Sub Title 2'
								]
						]
					]
			]
	],
			[
				'h1'=>
					[
						'inside'=>'finally another Heading'
					],
				'canvas'=>
					[
						'id'=>'1',
						'inside'=>'yes. You are Fucking working?'
					]
			]
];

$body['0']['div']['inside']['0']['h2']= 'I\'m changed';

$html = new htmltagger();
//$html->redis('localhost');
$html->setHead($head)->setBody($body)->setTitle('wowTitle')->prn();
