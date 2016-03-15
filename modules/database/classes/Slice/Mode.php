<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 数据库slice Mode
 * @author PanChao
 *
 * eg: Slice_Mode::factory("number:2")
 * 				->name('video')
 * 				->key('12345')
 * 				->execute();
 */
class Slice_Mode {

	protected $_name = '';

	protected $_key = ''; 

	/**
	 * factory 
	 * @param  string $mode "number:2"
	 * @return string
	 */
	public static function factory($format = '') {
		
		if($format == '') {
			return new Slice_Mode_None();
		}
		$format = explode(':', $format);
		$type = strtolower($format[0]);
		$mode = strtolower($format[1]);

		if($type == 'number') {
			return Slice_Mode_Number::factroy($mode);
		}
		if($type == 'time') {
			return Slice_Mode_Time::factroy($mode);
		}
		if($type == 'hash') {
			return Slice_Mode_Hash::factroy($mode);
		}
		// extends other type
		
		//throw new Slice_Exception("Slice type $type is not exists");
		return new Slice_Mode_None();
		
	}

	/**
	 * name
	 * @param  string $name 
	 * @return object
	 */
	public function name($name) {
		$this->_name = $name;
		return $this;
	}

	/**
	 * key
	 * @param  string $key 
	 * @return object
	 */
	public function key($key) {
		$this->_key = $key;
		return $this;
	}
}