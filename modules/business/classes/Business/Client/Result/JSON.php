<?php
class Business_Client_Result_JSON extends Business_Client_Result {
	
	protected $_result;
	
	public function __construct(array $result) {
		$this->_result = json_encode($result, TRUE);
	}
	
	public function __toString() {
		return $this->_result;
	}
}