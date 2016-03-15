<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Slice Mode Hash Md5
 * @author PanChao
 */
class Slice_Mode_Hash_Md5 extends Slice_Mode_Hash {

	public function execute() {

		return $this->_name ."_".substr(md5($this->_key), -2);
	}
}
