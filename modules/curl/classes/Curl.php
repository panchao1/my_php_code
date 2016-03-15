<?php
/**
 * curl 基本操作封装类 post/get/put/delete/trace
 * @author PanChao
 * 
 * eg : $response = Curl::instance()
 * 					->timeout('10')
 * 					->url($url)
 * 					->setIp()
 * 					->setReferer()
 * 			  		->get($data = array());
 * 		$response->getCode() or $response->code
 * 		$response->getMessage() or $response->message
 * 		$response->getData() or $response->data
 */
class Curl {

	/**
	 * url
	 * @var string
	 */
	protected $_url = '';
	
	/**
	 * 请求耗时
	 * @var integer
	 */
	protected $_timeout = 30;

	/**
	 * 构造ip
	 * @var string
	 */
	protected $_ip = '';

	/**
	 * 构造来路
	 * @var string
	 */
	protected $_referer = '';
	
	/**
	 * 返回 body 信息 
	 */
	protected $_body = '';

	/**
	 * 返回头信息
	 * @var string
	 */
	protected $_header = '';

	/**
	 * 返回content
	 * @var string
	 */
	protected $_content = '';

	/**
	 * 参数
	 * @var array
	 */
	protected $_options = array ();

	/**
	 * curl 连接
	 * @var null
	 */
	protected $_ch = NULL;

	/**
	 * instance
	 * @var null
	 */
	protected static $_instance = NULL;

	/**
	 * 单例
	 * @return object
	 */
	public static function instance() {
		if(self::$_instance === NULL) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * 初始化
	 */
	private function __construct() {
		
		$this->_options['user_agent'] = 'Mozilla/5.0 (Windows; U; Windows NT 6.0; zh-CN; rv:1.8.1.20) Gecko/20081217 Firefox/2.0.0.20';
		//请求耗时
		$this->_options['timeout'] = 10;
		
		$this->_ch = curl_init();

		if($this->_ch === FALSE) {
			throw new Exception("curl_init error");
		}

	}

	/**
	 * url
	 * @param  string $url
	 * @return object
	 */
	public function url($url = '') {
		
		if($url) {
			//判断url 结尾是否有 ? 
			$end = substr($url, -1, 1);
			if($end != '?') {
				$url .= '?';
			}
		}
		$this->_url = $url;
		return $this;
	}

	/**
	 * 请求的 timeout
	 * @param  integer $timeout 
	 * @return object
	 */
	public function timeout($timeout = 30) {
		$this->_timeout = $timeout;
		return $this;
	}

	/**
	 * 构造ip
	 * @param  string $ip
	 * @return object
	 */
	public function setIp($ip = '') {
		$this->_ip = $ip;
		return $this;
	}

	/**
	 * 构造来路
	 * @param string $referer
	 * @return object
	 */
	public function setReferer($referer = '') {
		$this->_referer = $referer;
		return $this;
	}

	/**
	 * get
	 * @param array $query
	 * @return object
	 */
	public function get($query = array ()) {

		if(! $this->_url) {
			throw new Exception("url not found");
		}

		$query = http_build_query($query);
		if(!is_string($query) || !$query) {
			$url = $this->_url;
		} else {
			$url = $this->_url . $query;
		}
		curl_setopt ($this->_ch, CURLOPT_USERAGENT, $this->_options ['user_agent']);
		curl_setopt($this->_ch, CURLOPT_URL, $url);
		curl_setopt ($this->_ch, CURLOPT_CONNECTTIMEOUT, $this->_options['timeout']);
		
		$this->_requrest();

		return $this;
	}

	/**
	 * post
	 * @param array $query
	 * @return object
	 */
	public function post($query = array ()) {
		if(! $this->_url) {
			throw new Exception("url not found");
		}

		curl_setopt($this->_ch, CURLOPT_USERAGENT, $this->_options['user_agent']);
		curl_setopt($this->_ch, CURLOPT_POST, TRUE);
		curl_setopt($this->_ch, CURLOPT_URL, $this->_url);
		curl_setopt($this->_ch, CURLOPT_CONNECTTIMEOUT, $this->_options['timeout']);
		curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $query);
		
		$this->_requrest();

		return $this;
	}

	/**
	 * delete
	 * @param array $query
	 * @return array
	 */
	public function delete($query = array ()) {
		if(! $this->_url) {
			throw new Exception("url not found");
		}
		curl_setopt ($this->_ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		
		return $this->post($query);
	}

	/**
	 * put
	 * @param array $query
	 * @return array
	 */
	public function put($query = array ()) {
		if(! $this->_url) {
			throw new Exception("url not found");
		}
		curl_setopt ($this->_ch, CURLOPT_CUSTOMREQUEST, 'PUT' );

		return $this->post($query);
	}

	/**
	 * trace
	 * @param array $query
	 * @return array
	 */
	public function trace($query = array ()) {
		if(! $this->_url) {
			throw new Exception("url not found");
		}
		curl_setopt($this->_ch, CURLOPT_CUSTOMREQUEST, 'TRACE');
		
		return $this->post($query);
	}

	/**
	 * 执行返回
	 * @return object
	 */
	private function _requrest() {

		if($this->_ip) {
			curl_setopt($this->_ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$this->_ip, 'CLIENT-IP:'. $this->_ip));
		}
		if($this->_referer) {
			curl_setopt($this->_ch, CURLOPT_REFERER, $this->_referer);  
		}
		curl_setopt($this->_ch, CURLOPT_TIMEOUT, $this->_timeout);
		curl_setopt($this->_ch, CURLOPT_HEADER, TRUE);
		curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($this->_ch);
		$errno = curl_errno($this->_ch);
		$error = curl_error($this->_ch);

		if($errno > 0) {
			throw new Exception ($error);
		}

		$header_size = curl_getinfo ($this->_ch, CURLINFO_HEADER_SIZE);
		
		$this->_body = $response;
		$this->_header = substr($response, 0, $header_size);
		$this->_content = substr($response, $header_size);

		return $response;
	}

	/**
	 * 返回 body 信息
	 */
	public function body() {
		return $this->_body;
	}
	/**
	 * 返回 header 头信息
	 * @return string
	 */
	public function header() {
		return $this->_header;
	}

	/**
	 * 返回 content 信息
	 * @return string
	 */
	public function content() {
		return $this->_content;
	}

	/**
	 * 返回 http_code 状态码
	 * @return integer
	 */
	public function httpCode() {
		return curl_getinfo($this->_ch, CURLINFO_HTTP_CODE);
	}

	/**
	 * 返回 effective_url
	 * @return string
	 */
	public function effectiveUrl() {
		return curl_getinfo($this->_ch, CURLINFO_EFFECTIVE_URL);
	}

	/**
	 * get 方式取出数据 $response->getCode()
	 * @param  string $method 
	 * @param  array  $arguments
	 * @return mixed
	 */
	public function __call($method, $arguments = array()) {
		
		$prefix = substr($method, 0, 3);
		if($prefix == 'get') {
			$item = substr($method, 3);
			$item = strtolower(implode('_', preg_split('#(?=[A-Z])#', lcfirst($item))));
		} else {
			return NULL;
		}
		
		$content = json_decode($this->_content, TRUE);

		if(isset($content[$item])) {
			return $content[$item];
		}else {
			return NULL;
		}

	}

	/**
	 * 魔术 __get
	 * @param  string $item 
	 * @return 
	 */
	public function __get($item) {
		
		$item = strtolower(implode('_', preg_split('#(?=[A-Z])#', lcfirst($item))));

		$content = json_decode($this->_content, TRUE);

		if(isset($content[$item])) {
			return $content[$item];
		}else {
			return NULL;
		}
	}

	/**
	 * destruct
	 */
	public function __destruct() {
		if(is_resource($this->_ch)) {
			curl_close($this->_ch);
		}
	}
}