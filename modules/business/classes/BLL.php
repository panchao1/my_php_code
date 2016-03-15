<?php
class BLL {
	
	protected $_proxy = '';
	
	protected $_className = '';
	
	protected $_method = '';
	
	protected $_arguments = array();
	
	protected $_returnType = Business_Client::RETURN_OBJECT;
	
	protected $_asObject = NULL;
	
	protected static $_instance = NULL;

	/**
	 * 分层调用控制重载方法
	 * @param string $name
	 * @param array $arguments
	 * @return BLL
	 */
	public static function __callstatic($className, $arguments = array()) {
		if(self::$_instance === NULL) {
			$proxy = isset($arguments[0]) ? $arguments[0] : Kohana::$config->load('bll.proxy');
			self::$_instance = new self($proxy);
		}
	
		self::$_instance->_className = $className;
		return self::$_instance;
	}
	
	/**
	 * 重载接口调用方法
	 * @param string $method
	 * @param array $arguments
	 * @return BLL
	 */
	public function __call($method, $arguments = array()) {
		$this->_method = $method;
		$this->_arguments = $arguments;
		return $this;
	}

	/**
	 * 构造函数
	 * 提供最后确定请求业务逻辑层调用方式参数，默认由配置决定。
	 * @param string $proxy
	 */
	public function __construct($proxy = '') {
		$this->_proxy = $proxy;
	}
	
	public function getArray() {
		$this->_returnType = Business_Client::RETURN_ARRAY;
		return $this->execute();
	}
	
	/**
	 * 按对象方式获得
	 * @param string $className
	 */
	public function getObject($className) {
		$this->_returnType = Business_Client::RETURN_OBJECT;
		$this->_asObject = $className;
		return $this->execute();
	}
	
	/**
	 * 按json结构获得
	 */
	public function getJSON() {
		$this->_returnType = Business_Client::RETURN_JSON;
		return $this->execute();
	}
	
	/**
	 * 按xml结构获得
	 */
	public function getXML() {
		$this->_returnType = Business_Client::RETURN_XML;
		return $this->execute();
	}
	
	/**
	 * 执行请求动作
	 * @throws BLL_Exception
	 */
	protected function execute() {
		$instance = Business_Client::factory($this->_proxy)
			->name($this->_className)
			->method($this->_method)
			->arguments($this->_arguments);
		if($this->_returnType == Business_Client::RETURN_OBJECT) {
			$instance->asObject($this->_asObject);
		}
		if($this->_returnType == Business_Client::RETURN_JSON) {
			$instance->asJSON();
		}
		if($this->_returnType == Business_Client::RETURN_XML) {
			$instance->asXML();
		}
		if($this->_returnType == Business_Client::RETURN_ARRAY) {
			$instance->asArray();
		}
		
		return $instance->execute();
	}
}