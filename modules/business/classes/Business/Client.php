<?php
abstract class Business_Client {

	const PROXY_HTTP = 'http';
	
	const PROXY_NATIVE = 'native';
	
	const RETURN_OBJECT = 0;
	
	const RETURN_JSON = 1;
	
	const RETURN_XML = 2;
	
	const RETURN_ARRAY = 3;
	
	protected $_className = '';
	
	protected $_method = '';
	
	protected $_arguments = array();
	
	protected $_returnType = NULL;
	
	protected $_asObject = NULL;
	

	public static function factory($proxy = '') {
		if(!$proxy) {
			$proxy = self::PROXY_HTTP;
		}
		$config = Kohana::$config->load('bll.'.$proxy);
	
		if($proxy == self::PROXY_HTTP) {
			return new Business_Client_HTTP($config);
		}
		if($proxy == self::PROXY_NATIVE) {
			return new Business_Client_Native($config);
		}
	}
	
	/**
	 * 确定访问类
	 * @param string $className
	 * @return Business_Client
	 */
	public function name($className = '') {
		$this->_className = $className;
		return $this;
	}
	
	/**
	 * 确定访问方法
	 * @param string $method
	 * @return Business_Client
	 */
	public function method($method = '') {
		$this->_method = $method;
		return $this;
	}
	
	/**
	 * 接收参数
	 * @return Business_Client
	 */
	public function arguments($arguments = array()) {
		$this->_arguments = $arguments;
		return $this;
	}
	
	/**
	 * 按对象方式返回
	 * @param string $className
	 */
	public function asObject($className) {
		$this->_returnType = Business_Client::RETURN_OBJECT;
		$this->_asObject = $className;
		return $this;
	}
	
	/**
	 * 按json方式返回
	 */
	public function asJSON() {
		$this->_returnType = Business_Client::RETURN_JSON;
		return $this;
	}
	
	/**
	 * 按xml方式返回
	 */
	public function asXML() {
		$this->_returnType = Business_Client::RETURN_XML;
		return $this;
	}
	
	public function asArray() {
		$this->_returnType = Business_Client::RETURN_ARRAY;
		return $this;
	}
	
	/**
	 * 执行通信逻辑
	 */
	abstract public function execute();
	
}