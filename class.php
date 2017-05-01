<?php
class htmltagger {
	//declare
	private $buffer = FALSE;
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
	//set html <title>
	public function setTitle($title) {
		$this->head = array_merge_recursive($this->head, array(
			'title'	=> htmlspecialchars($title)
			)
		);
		return $this;
	}
	//set html <head>
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
	//set html <body>
	public function setBody(array $body) {
		if (is_array($body)) {
			$this->body = array_merge_recursive($this->body, $body);
		} else {
			$this->body = array();
			$this->body = array_merge_recursive($this->body, $body);
		}
		return $this;
	}
	//get htmlized <head>
	public function getHeadHtml() {
		$head	= new head($this->head);
		return $head->trn();
	}
	//get htmlized <body>
	public function getBodyHtml() {
		$body 	= new body($this->body);
		return $body->rtn();
	}
	//print html tags
	public function prn() {
		ob_start();
		$this->buffer = TRUE;
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
		echo "<!DOCTYPE html>\n<html>\n";
		if (!$head_html) {
			$head = new head($this->head);
			if ($redis_enable) {
				$head_html = $head->rtn();
				$redis->hSet('htmltagger', $head_md5, $head_html);
			}
			$head->prn();
		} else {
			echo $head_html;
		}
		ob_flush();
		if (!$body_html) {
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
		ob_end_flush();
		$this->buffer = FALSE;
		return 0;
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
	protected function errCatcher($level, $method, $comment, $line) {
		$printData = $level . ': METHOD( ' . $method . ' ): ' . $comment . " at: $line. \n";
		return $printData;
	}
	public function __destruct (){
		if ($this->buffer) ob_end_clean();
	}
}

class head extends htmltagger {
	private $buffer = FALSE;
	public function __construct($head) {
		ob_start();
		$this->buffer = TRUE;
		echo "\t<head>\n\t\t<meta charset=\"UTF-8\">\n";
		foreach ($head as $k => $s) {
			echo "\t\t";
			$this->$k($s);
			echo "\n";
		}
		echo "\t</head>\n";
	}
	public function title($title) {
		echo "<title>$title</title>";
	}
	public function icon($icon) {
		echo '<link rel="icon" href="' , $icon , '">';
	}
	public function script($script) {
		$length = count($script);
		$i = 0;
		foreach ($script as $s) {
			echo '<script type="' , $s['type'] , '" src="' , $s['location'] , '"></script>';
			echo $i < ($length - 1) ? "\n\t\t" : '';
			$i++;
		}
	}
	public function css($css) {
		$length = count($css);
		for ($i = 0; $i < $length; $i++) {
			echo '<link rel="stylesheet" type="text/css" href="' , $css[$i] , '">';
			echo $i < ($length - 1) ? "\n\t\t" : '';
		}
	}
	public function style($style){
		echo  "\n\t<style>\n";
		if (is_array($style)){
			foreach ($style as $s){
				echo  "\t\t$s";
			}
		} else if (is_string($style)){
			echo  "\t\t$style";
		}
		echo "\n\t</style>"; 
	}
	public function prn() {
		ob_end_flush();
		$this->buffer = FALSE;
		return ;
	}
	public function rtn() {
		$return = ob_get_contents();
		ob_end_clean();
		$this->buffet = FALSE;
		return $return;
	}
	public function __call($method, $parameters) {
		return;
	}
	public function __destruct () {
		if ($this->buffer) ob_end_clean();
	}
}

class body extends htmltagger {
	private $buffer = FALSE;
	//when a new body object's cerating
	public function __construct($body) {
		ob_start();
		//all tags need to be placed in <body> so we print it at FUCKING FIRST without anything above it.
		$this->buffer = TRUE;
		echo "\t<body>\n";
		//open every array
		foreach ($body as $inner) {
			if (is_string($inner)) {
				echo $inner;
			} else {
				foreach ($inner as $k => $s) {
					//every thing in <body> are tabing use offset just below
					//and the first offset is 2 instead of 1 because <body> uses offset 1 and <html> uses 0
					$this->$k($s, '2');
					echo "\n";
				}
			}
		}
		echo "\t</body>\n";
	}
	public function prn() {
		ob_end_flush();
		$this->buffer = FALSE;
		return ;
	}
	public function rtn() {
		$return = ob_get_contents();
		ob_end_clean();
		$this->buffet = FALSE;
		return $return;
	}
	// exchange htmltagging array to html tag's parameters
	protected function exchange($data) {
		//we don't need toch anything in the '__in' so just unset it if it's exist
		if (isset($data['__in'])) {
			unset($data['__in']);
		}
		//prepare the array to return
		$convert = array();
		//Converting is just fucking easy. Cause html tag's just fucking easy to do.
		if (!$data == NULL) {
			foreach ($data as $k => $s) {
				if (!$s== '') {
					//converting is like this:
					//    ['content'=>'what'] -> content="what"
					//    and we add spage before it or it'll not be able to understand by browser.
					//    then we put it in an array to return
					$convert[$k] = " $k=\"$s\"";
				}
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
		//is $data an array or a string?
		$dataType = is_string($data) ? 's' : 'a' ;
		//format the html tag
		echo str_repeat("\t", $offset);
		//open a html tag
		echo "<$type";
		//convert array setting-arguments to html tag's parameters @ADD 0127-04-27 if it's array
		if ($dataType == 'a') {
			$return = $this->exchange($data);
			//print all parameters
			foreach ($return as $config) {
				echo $config;
			}
		}
		//get what in the tag that just opened
		$inside = isset($data['__in']) ? $data['__in'] : NULL;
		//close prefix
		if (is_null($inside)){
			echo '/>';
		} else {
			echo '>';
		}
		if(is_null($data)){
			return;
		} else if ($dataType == 's') {
			echo "$data</$type>";
			return;
		} else {
			//if it's an array, re-call an function to keep phrasing html tags
			if (is_array($inside)) {
				//we admire if there's only on array in the $inside. we use another array inside there to avoid
				//if you want two tag that's uses one name.
				if (!isset($inside[0])) {
					foreach ($inside as $k => $s) {
						echo "\n";
						$this->$k($s, $offset + 1);
					}
				} //if it comes' with more array.
				else {
					foreach ($inside as $i) {
						if (is_string($i)) {
							echo $i;
						} else {
							foreach ($i as $k => $s) {
							echo "\n";
							$this->$k($s, $offset + 1);
							}
						}
					}
				}
				//finish the tag
				echo "\n" , str_repeat("\t", $offset) , "</$type>";
				return;
			}
			//if what we want inside the tag is a string, just print it.
			else if (is_string($inside)) {
				echo "$inside</$type>";
				return;
			} 
			//or we don't know how to do with the data, so print it out.
			else if(!is_null($inside)){
				var_dump($inside);
				echo 'Error: ', __METHOD__, ' No suitable parameters\' type at :', __LINE__-8,"\n";
				return;
			}
		}
	}

	public function __destruct () {
		if ($this->buffer) ob_end_clean();
	}
}