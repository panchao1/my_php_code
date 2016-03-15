<?php
/**
 * XML格式结果
 * 返回格式如：
 * <?xml version="1.0" encoding="utf-8"?>
 * <resultset statement="" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
 * <row>
 * 		<field name="video_id">1</field>
 * 		<field name="title">金刚王</field>
 * </row>
 * <row>
 * 		<field name="video_id">1</field>
 * 		<field name="title">功夫</field>
 * </row>
 * </resultset>
 */
class Business_Client_Result_XML extends Business_Client_Result {
	
	protected $_xmlObject;
	
	public function __construct(array $result) {
		$string = '<?xml version="1.0" encoding="utf-8"?>';
		$string .= '<resultset statement="" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
		$string .= '</resultset>';
		$this->_xmlObject = simplexml_load_string($string);
		foreach($result as $row) {
			$rowNode = $this->_xmlObject->addChild('row');
			foreach($row as $key=>$value) {
				$fieldNode = $rowNode->addChild('field', $value);
				$fieldNode->addAttribute('name', $key);
			}
		}
	}
	
	public function __toString() {
		return $this->_xmlObject->asXML();
	}
}