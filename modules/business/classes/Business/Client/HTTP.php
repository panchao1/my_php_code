<?php
/**
 * HTTP方式请求业务逻辑层
 * 继承业务逻辑客户端基础类，实现执行动作。
 * 要求业务服务端返回json
 */
class Business_Client_HTTP extends Business_Client {
	protected $_url;
	protected $_secureKey;
	protected $_timeout;
	
	/**
	 * 响应数据
	 */
	private $_response = '';
	/**
	 * 响应头
	 * @var string
	 */
	private $_header = '';
	/**
	 * 响应内容
	 * @var string
	 */
	private $_body = '';
	/**
	 * 响应状态码
	 * @var integer
	 */
	private $_status = 200;
	/**
	 * 自定义状态码
	 * @var integer
	 */
	private $_code = 0;
	/**
	 * 自定义信息
	 * @var string
	 */
	private $_message = '';
	
	public function __construct($config) {
		$this->_url = $config['url'];
		$this->_secureKey = $config['secureKey'];
		$this->_timeout = $config['timeout'];
	}
	
	public function execute() {
		
		$post = array();
		$post['className'] = $this->_className;
		$post['method'] = $this->_method;
		$post['arguments'] = $this->_arguments;

		$post = json_encode($post);
		$token = md5($this->_secureKey.$post);
		
		$headers = array(
			"TOKEN: $token",
			"ACCOUNT_ID: ".Author::accountId(),
			"APPNAME: ".APPNAME
		);
		
		$options = array();
		$options[CURLOPT_URL] = $this->_url;
		$options[CURLOPT_RETURNTRANSFER] = TRUE;
		$options[CURLOPT_HTTPHEADER] = $headers;
		$options[CURLOPT_POST] = TRUE;
		$options[CURLOPT_HEADER] = TRUE;
		$options[CURLOPT_POSTFIELDS] = $post;
		$options[CURLOPT_TIMEOUT] = $this->_timeout;
		
		$curl = curl_init();
		if(!curl_setopt_array($curl, $options)) {
			throw new Business_Client_Exception('Failed to set CURL options, check CURL documentation');
		}
		
		$this->_response = curl_exec($curl);
		if($this->_response === FALSE) {
			throw new Business_Client_Exception(curl_error($curl));
		}
		
		$headerLength = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		$this->_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$this->_header = substr($this->_response, 0, $headerLength);
		$this->_body = substr($this->_response, $headerLength);
		$this->_code = NULL;
		$this->_message = '';
		
		if(preg_match('/Code:\s(\d+)\r\n/', $this->_header, $matches)) {
			$this->_code = $matches[1];
		}
		if(preg_match('/Message:\s([^\r\n]+)\r\n/', $this->_header, $matches)) {
			$this->_message = $matches[1];
		}
		if($this->_code === NULL) {
			throw new Business_Client_Exception("Business error: $this->_status ". Response::$messages[$this->_status]);
		}
		if($this->_code == '0') {
			throw new Business_Client_Exception("Business error: $this->_message");
		}
		
		$this->_body = trim($this->_body);
		$this->_body = json_decode($this->_body, TRUE);
		
		curl_close($curl);
		return Business_Client_Result::factory($this->_body, $this->_returnType, $this->_asObject);
		
	}
}