<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Slice Mode Number N
 * @author PanChao
 */
class Slice_Mode_Number_N extends Slice_Mode_Number {

	public function execute() {

		return $this->_name . "_" .$this->_key%self::$_n;
	}
}