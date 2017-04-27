<?php
class htmltagger {
	//declare
	public $head   = [];
	public $body   = [];
	public $top    = [];
	public $footer = [];
	//disable redis as default
	protected $redis = [
		'enable' => FALSE
	];
	//setup redis
	public function redis($addr = 'localhost', $port = 6379, $exp = 300) {
		$this->redis = [
			'enable'      => TRUE,
			'addr'        => $addr,
			'port'        => $port,
			'expire_time' => $exp
		];
		return $this;
	}
	public function setTitle(string $title) {
		$this->head = array_merge_recursive($this->head, array(
			'title'	=> htmlspecialchars($title)
			)
		);
		return $this;
	}
	public function setHead(array $head) {
		if (is_array($head)) {
			$this->head = array_merge_recursive($this->head, $head);
			return $this;
		} else {
			$this->head = array();
			$this->head = $head;
		}
		return $this;
	}
	public function setBody(array $body) {
		if (is_array($body)) {
			$this->body = array_merge_recursive($this->body, $body);
		} else {
			$this->body = array();
			$this->body = array_merge_recursive($this->body, $body);
		}
		return $this;
	}

	public function getHeadHtml() {
		$head	= new head($this->head);
		return $head->trn();
	}
	public function getBodyHtml() {
		$body 	= new body($this->body);
		return $body->rtn();
	}
	public function prn() {
		$head_html    = FALSE;
		$body_html    = FALSE;
		$redis_enable = $this->redis['enable'];
		if ($redis_enable) {
			$redis = new Redis();
			if (!$redis->connect($addr = 'localhost', $port = 6379)) {
				exit('unable to connect to Redis.');
			}
			$head_md5  = md5(serialize($this->head));
			$body_md5  = md5(serialize($this->body));
			$head_html = $redis->hGet('htmltagger', $head_md5);
			$body_html = $redis->hGet('htmltagger', $body_md5);
		}
		echo "<html>\n";
		if (!$head_html) {
			$head = new head($this->head);
			if ($redis_enable) {
				$head_html = $head->rtn();
				$redis->hSet('htmltagger', $head_md5, $head_html);
			}
			$head->prn();
		} else {
			echo $head_html;
		}if (!$body_html) {
			$body = new body($this->body);
			if ($redis_enable) {
				$body_html = $body->rtn();
				$redis->hSet('htmltagger', $body_md5, $body_html);
			}
			$body->prn();
		} else {
			echo $body_html;
		}
		echo '</html>';
		return 0;
	}

	public function setTop($id, $class, $style, $top) {
	}
	public function setString($member, $data) {
		if (!is_string($this->$member)) {
			echo ($this->errCatcher('Abort', __METHOD__,
				      'setting a NON-String member', __LINE__));
			return FALSE;
		}
		if (!is_string($data)) {
			echo ($this->errCatcher('Abort', __METHOD__,
					  'parameter is not string', __LINE__));
			return FALSE;
		}
		$this->$member = $data;
		return TRUE;
	}
	protected function shrink($member) {
		$member = $this->$member();
		if (!isset($this->$member)) {
			return FALSE;
		}
		$content = $member['content'];
		foreach ($content as $head_name => $data) {
			if ($data == '') {
				unset($content[$head_name]);
			}
		}
	}
	protected function errCatcher($level, $method, $comment, $line) {
		$printData = $level . ': METHOD( ' . $method . ' ): ' . $comment . " at: $line. \n";
		return $printData;
	}
}

class head extends htmltagger {
	protected $html = '';
	public function __construct($head) {
		$this->html .= "\t<head>\n";
		foreach ($head as $k => $s) {
			$this->html .= "\t\t";
			$this->$k($s);
			$this->html .= "\n";
		}
		$this->html .= "\t</head>\n";
	}
	public function title($title) {
		$this->html .= "<title>$title</title>";
	}
	public function icon($icon) {
		$this->html .= '<link rel="icon" href="' . $icon . '">';
	}
	public function script($script) {
		foreach ($script as $s) {
			$this->html .= '<script type="' . $s['type'] .
				     '" src="' . $s['location'] . '"></script>';
		}
	}
	public function css($css) {
		$length = count($css);
		for ($i = 0; $i < $length; $i++) {
			$this->html .=
	     '<link rel="stylesheet" type="text/css" href="' . $css[$i] . '">';
			$this->html .= $i < ($length - 1) ? "\n\t\t" : '';
		}
	}
	public function prn() {
		echo $this->html;
		return 0;
	}
	public function rtn() {
		return $this->html;
	}
	public function __call($method, $parameters) {
		return;
	}
}

class body extends htmltagger {
	            //declare the container of all html tags going to be print.
	protected $html = '';
	//when a new body object's cerating
	public function __construct($body) {
		//all tags need to be placed in <body> so we print it at FUCKING FIRST without anything above it.
		$this->html .= "\t<body>\n";
		//open every array
		foreach ($body as $inner) {
			foreach ($inner as $k => $s) {
				//every thing in <body> are tabing use offset just below
				//and the first offset is 2 instead of 1 because <body> uses offset 1 and <html> uses 0
				$this->$k($s, '2');
				$this->html .= "\n";
			}
		}
		$this->html .= "\t</body>\n";
	}

	public function prn() {
		echo $this->html;
		return 0;
	}
	//return phrased html data
	public function rtn() {
		return $this->html;
	}
	// exchange htmltagging array to html tag's parameters
	protected function exchange($data) {
		//we don't need toch anything in the 'inside' so just unset it if it's exist
		if (isset($data['inside'])) {
			unset($data['inside']);
		}

		//prepare the array to return
		$convert = array();
		//Converting is just fucking easy. Cause html tag's just fucking easy to do.
		foreach ($data as $k => $s) {
			if ($s != '') {
				//converting is like this:
				//    ['content'=>'what'] -> content="what"
				//    and we add spage before it or it'll not be able to understand by browser.
				//    then we put it in an array to return
				$convert[$k] = " $k=\"$s\"";
			}
		}
		//kick converted parameters back
		return $convert;
	}
	//print all tags just using this function.
	//all this function need that there's a string or sth just printable if it's not an array.
	public function __call($type, $arguments) {
		//recover arguments
		$data   = $arguments['0'];
		$offset = $arguments['1'];
		//format the html tag
		$this->html .= str_repeat("\t", $offset);
		//convert array setting-arguments to html tag's parameters
		$return = $this->exchange($data);
		//open a html tag
		$this->html .= "<$type";
		//pring all parameters
		foreach ($return as $config) {
			$this->html .= $config;
		}

		//close prefix
		$this->html .= '>';
		//get what in the tag that just opened
		$inside = $data['inside'];
		//if it's an array, re-call an function to keep phrasing html tags
		if (is_array($inside)) {
			//we admire if there's only on array in the $inside. we use another array inside there to avoid
			//if you want two tag that's uses one name.
			if (!isset($inside[0])) {
				foreach ($inside as $k => $s) {
					$this->html .= "\n";
					$this->$k($s, $offset + 1);
				}
			}
			//if it comes' with more array.
			else {
				foreach ($inside as $i) {
					foreach ($i as $k => $s) {
						$this->html .= "\n";
						$this->$k($s, $offset + 1);
					}
				}
			}
			//finish the tag
			$this->html .= "\n" . str_repeat("\t", $offset) . "</$type>";
		}
		//if what we want inside the tag is a string, just print it.
		else if (is_string($inside)) {
			$this->html .= "$inside</$type>";
		}

		//or we don't know how to do with the data, so print it out.
		else {
			$this->errCatcher('Error', __METHOD__,
				   'No suitable parameters\' type', __LINE__-8);
		}
	}
}