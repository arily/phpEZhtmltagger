<?php
class htmltagger {
	//declare
	private $buffer = FALSE;
	private $head 	= [];
	private $body 	= [];
	//disable redis as default
	protected $redis = ['enable' => FALSE];
	//setup redis
	public function redis($addr = 'localhost', $port = 6379, $exp = 300) {
		$this->redis = ['enable' => TRUE, 'addr' => $addr, 'port' => $port, 'expire_time' => $exp];
		return $this;
	}
	//set html <title>
	public function setTitle($title) {
		$this->head = array_merge_recursive($this->head, array('title' => htmlspecialchars($title)));
		return $this;
	}
	//set html <head>
	public function setHead(array $head) {
		if (is_array($head)) {
			$this->head = array_merge_recursive($this->head, $head);
		} else {
			$this->head = array();
			$this->head = $head;
		}
		return $this;
	}
	//set html <body>
	public function setBody() {
		$body 		= func_get_args();
		$this->body = array_merge_recursive($this->body, $body);
		return $this;
	}
	//get htmlized <head>
	public function getHeadHtml() {
		$head = new head($this->head);
		return $head->__rtn();
	}
	//get htmlized <body>
	public function getBodyHtml() {
		$body = new body($this->body);
		return $body->__rtn();
	}
	//print html tags
	public function prn() {
		ob_start();
		$this->buffer 	= TRUE;
		$head_html 		= FALSE;
		$body_html 		= FALSE;
		$redis_enable 	= $this->redis['enable'];
		if ($redis_enable) {
			$redis = new Redis();
			if (!$redis->connect($this->redis['addr'],$this->redis['port'])) {
				exit('unable to connect to Redis.');
			}
			$head_md5 	= md5(serialize($this->head));
			$body_md5 	= md5(serialize($this->body));
			$head_html 	= $redis->get("htmltagger:$head_md5");
			$body_html 	= $redis->get("htmltagger:$body_md5");
		}
		echo "<!DOCTYPE html>", PHP_EOL, "<html>", PHP_EOL;
		if (!$head_html) {
			$head = new head($this->head);
			if ($redis_enable) {
				$head_html = $head->__rtn();
				$redis->set("htmltagger:$head_md5", $head_html);
				$redis->setTimeout("htmltagger:$head_md5",$this->redis['expire_time']);
			}
			$head->__prn();
		} else {
			echo $head_html;
		}
		ob_flush();
		if (!$body_html) {
			$body = new body($this->body);
			if ($redis_enable) {
				$body_html = $body->__rtn();
				$redis->set("htmltagger:$body_md5", $body_html);
				$redis->setTimeout("htmltagger:$body_md5",$this->redis['expire_time']);
			}
			$body->__prn();
		} else {
			echo $body_html;
		}
		echo '</html>';
		ob_flush();
		$this->buffer = FALSE;
		return;
	}
	//shrink the unused tags
	protected function shrink($member) {
		$member = $this->$member();
		if (!isset($member)) {
			return FALSE;
		}
		$content = $member['content'];
		foreach ($content as $head_name => $data) {
			if ($data == '') {
				unset($content[$head_name]);
			}
		}
	}
	public static function errHandler($errlevel, $errmsg, $errfile, $errline) {
		if ($errlevel < 256 || $errlevel > 1024 && $errlevel != 16384) return FALSE;
		$invisable = ($errlevel > 256);
		$cli = (php_sapi_name() == 'cli');
		if (!$cli) {
			echo '<phpEZhtmltaggerErr';
			if ($invisable) echo ' style="visibility:hidden;"';
			echo '>';
		}
		$type = ($invisable) ? 'Error: ' : 'Fat Error: ';
		echo PHP_EOL, $type, $errmsg, PHP_EOL;
		if (!$cli) echo '</phpEZhtmltaggerErr>';
		if ($need_to_die = ($errlevel = 256)) {
			ob_flush();
			die();
		}
	}
	public function __destruct() {
		if ($this->buffer) ob_end_clean();
	}
}
class head extends htmltagger {
	private $buffer = FALSE;
	protected function __exchange($data) {
		//we don't need toch anything in the '__in' so just unset it if it's exist
		if (isset($data['__in'])) {
			unset($data['__in']);
		}
		//prepare the array to return
		$convert = array();
		//Converting is just fucking easy. Cause html tag's just fucking easy to do.
		if (!$data == NULL) {
			foreach ($data as $k => $s) {
				if (!$s == '') {
					//converting is like this:
					//    ['content'=>'what'] -> content="what"
					//    and we add spage before it or it'll not be able to understand by browser.
					//    then we put it in an array to return
					$convert[$k] = "$k=\"$s\"";
				}
			}
		}
		//kick converted parameters back
		return $convert;
	}
	public function __construct($head) {
		if ($head == []) {
			echo '<--!empty head-->', PHP_EOL;
			return;
		}
		ob_start();
		$this->buffer = TRUE;
		echo "\t<head>", PHP_EOL, "\t\t<meta charset=\"UTF-8\">",PHP_EOL;
		foreach ($head as $k => $s) {
			echo "\t\t";
			$this->$k($s);
			echo PHP_EOL;
		}
		echo "\t</head>", PHP_EOL;
	}
	public function title($title) {
		echo "<title>$title</title>";
	}
	public function icon($icon) {
		echo '<link rel="icon" href="', $icon, '">';
	}
	public function script($script) {
		$length = count($script);
		foreach ($script as $k => $s) {
			echo '<script type="', $s['type'], '" src="', $s['location'], '"></script>';
			echo ($k < ($length - 1)) ? PHP_EOL . "\t\t" : '';
		}
	}
	public function css($css) {
		$length = count($css);
		foreach ($css as $k => $s) {
			if (is_string($s)){
				echo '<link rel="stylesheet" href="', $s, '">';
			}
			elseif (is_array($s)){
				echo '<link rel="stylesheet" ';
				$return = $this->__exchange($css[$k]);
				foreach ($return as $config) {
					echo ' ',$config;
				}
				echo '>';
			}
			echo ($k < ($length - 1)) ? PHP_EOL . "\t\t" : '';
		}
	}
	public function style($style) {
		echo "<style>", PHP_EOL;
		if (is_array($style)) {
			foreach ($style as $s) {
				echo "\t\t\t$s", PHP_EOL;
			}
		} else if (is_string($style)) {
			echo "\t\t$style", PHP_EOL;
		}
		echo "\t\t</style>";
	}
	public function __prn() {
		if ($this->buffer) ob_end_flush();
		$this->buffer = FALSE;
		return;
	}
	public function __rtn() {
		$return = ob_get_contents();
		if ($this->buffer) ob_end_flush();
		$this->buffer = FALSE;
		return $return;
	}
	public function __call($method, $parameters) {
		return;
	}
	public function __destruct() {
		if ($this->buffer) ob_end_clean();
	}
}
class body extends htmltagger {
	private $buffer = FALSE;
	//when a new body object's cerating
	public function __construct($body) {
		//set custom error handler for user error
		set_error_handler(array('htmltagger', 'errHandler'));
		//stop script if somebody feeds me nothing. WE DON'T WORK WITH NO PAY!
		if ($body == []) {
			echo '<--!empty body-->', PHP_EOL;
			return;
		}
		//if threr's ver0.0.2 array we reject tag it although it's just easy to make a another foreach.
		// if you see this, you, you might be a friend that writes php code.
		// you can change this code (v) to {foreach ($body as $inner){...}} but anyway it will just not work.
		if (isset($body[0][0])) 
			trigger_error('You are feeding phpEZhtmltagger ver 0.0.1-0.0.2 arrays to phpEZhtmltagger 0.0.3. ' . PHP_EOL . 'This\' need some easy-array-changing-operation. ', E_USER_ERROR);
		ob_start();
		//all tags need to be placed in <body> so we print it at FUCKING FIRST without anything above it.
		$this->buffer = TRUE;
		echo "\t<body>";
		//open every array
		foreach ($body as $s) {
			$this->__do($s, 2);
		}
		echo PHP_EOL, "\t</body>", PHP_EOL;
	}
	function __do($s, $offset) {
		if (is_string($s)) {
			echo $s;
		} else {
			foreach ($s as $k => $s) {
				echo PHP_EOL;
				//every thing in <body> are tabing use offset just below
				//and the first offset is 2 instead of 1 because <body> uses offset 1 and <html> uses 0
				$this->$k($s, $offset);
			}
		}
	}
	public function __prn() {
		if ($this->buffer) ob_end_flush();
		$this->buffer = FALSE;
		return;
	}
	public function __rtn() {
		$return = ob_get_contents();
		if ($this->buffer) ob_end_clean();
		$this->buffer = FALSE;
		return $return;
	}
	// __exchange htmltagging array to html tag's parameters
	protected function __exchange($data) {
		//we don't need toch anything in the '__in' so just unset it if it's exist
		if (isset($data['__in'])) {
			unset($data['__in']);
		}
		//prepare the array to return
		$convert = array();
		//Converting is just fucking easy. Cause html tag's just fucking easy to do.
		if (!$data == NULL) {
			foreach ($data as $k => $s) {
				if (!$s == '') {
					//converting is like this:
					//    ['content'=>'what'] -> content="what"
					//    and we add spage before it or it'll not be able to understand by browser.
					//    then we put it in an array to return
					$convert[$k] = "$k=\"$s\"";
				}
			}
		}
		//kick converted parameters back
		return $convert;
	}
	//print all other tags just using this function.
	//all this function need that there's a string or sth just printable if it's not an array.
	public function __call($type, $arguments) {
		$data 		= $arguments['0'];
		$offset 	= $arguments['1'];
		$dataType 	= is_string($data) ? 's' : 'a';
		$inside 	= isset($data['__in']) ? $data['__in'] : NULL;
		//formating html tag
		echo str_repeat("\t", $offset);
		//open a html tag
		echo "<$type";
		//convert array setting-arguments to html tag's parameters @ADD 2017-04-27 if it's array
		if ($dataType == 'a' && $data != []) {
			$return = $this->__exchange($data);
			//print all parameters
			foreach ($return as $config) {
				echo ' ',$config;
			}
		}
		//close prefix
		if (is_null($inside) && $dataType == 'a') {
			//for <img /> ( <** />)
			echo '/>';
		} else {
			//for <h1></h1> ( <**></**>) and <br> (<**>)
			echo '>';
		}
		if (is_null($data)) {
			return;
		} else if ($dataType == 's') {
			if ($data == '') return;
			echo "$data</$type>";
			return;
		} else {
			//if it's an array
			if (is_array($inside)) {
				//we admire if there's only on array in the $inside. we use another array inside there to avoid
				//if you want two tag that's uses one name.
				if (!isset($inside[0])) {
					foreach ($inside as $k => $s) {
						echo PHP_EOL;
						$this->$k($s, $offset + 1);
					}
				} else { //if it comes' with more array.
					foreach ($inside as $i) {
						if (is_string($i)) {
							echo $i;
						} else {
							$this->__do($i, $offset + 1);
						}
					}
				}
				//finish the tag
				echo PHP_EOL, str_repeat("\t", $offset), "</$type>";
				return;
			}
			//if what we want inside the tag is a string, just print it.
			else if (is_string($inside)) {
				echo "$inside</$type>";
				return;
			}
			//or we don't know how to do with the data, so print it out.
			else if (!is_null($inside)) {
				$cli 		= (php_sapi_name() == 'cli');
				$nl 		= ($cli) ? '<br>' : PHP_EOL;
				$msg 		= 'I don\'t know how to do with the data. Please check.' . $nl;
				if ($cli) 
					$msg	.= '<phpEZhtmltaggerErrdata>' . PHP_EOL;
				$msg		.= var_dump($inside);
				if ($cli) 
					$msg	.= '</phpEZhtmltaggerErrdata>' . PHP_EOL;
				trigger_error($msg, E_USER_WARNING);
				return;
			}
		}
	}
	public function __destruct() {
		if ($this->buffer) ob_end_clean();
	}
}
