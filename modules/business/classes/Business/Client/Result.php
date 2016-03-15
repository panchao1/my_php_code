<?php
class Business_Client_Result {
	static public function factory($result, $resultType, $asObject = NULL) {
		if($resultType == Business_Client::RETURN_JSON) {
			return new Business_Client_Result_JSON($result);
		}
		if($resultType == Business_Client::RETURN_XML) {
			return new Business_Client_Result_XML($result);
		}
		if($resultType == Business_Client::RETURN_OBJECT) {
			return new Business_Client_Result_Object($result, $asObject);
		}
		if($resultType == Business_Client::RETURN_ARRAY) {
			return $result;
		}
		
		return $result;
	}
}