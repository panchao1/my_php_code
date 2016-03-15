<?php
/**
 * 原生方式请求业务逻辑层
 * 继承业务逻辑客户端基础类，实现执行动作。
 * 要求业务逻辑层返回二维数据
 */
class Business_Client_Native extends Business_Client {
	
	public function execute() {
		$prefix = 'Business_';
		$instance = new ReflectionClass($prefix.ucfirst($this->_className));
		$business = $instance->newInstance();
		$response = $instance->getMethod($this->_method)->invokeArgs($business, $this->_arguments);

		return Business_Client_Result::factory($response, $this->_returnType, $this->_asObject);
	}
}