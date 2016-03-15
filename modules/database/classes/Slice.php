<?php
/**
 * 数据库切分
 * @author PanChao
 * //数据库切分
 * Slice::database($format)
 * 		->name($name)
 * 		->key($key)
 * 		->execute()
 * //表切分
 * Slice::table($format)
 * 		->name($name)
 * 		->key($key)
 * 		->execute()
 */
class Slice {

	/**
	 * 数据库切分
	 * @param  string $mode
	 * @return string
	 */
	public static function database($format) {
		
		return Slice_Mode::factory($format);
	}

	/**
	 * 表切分
	 * @param  string $mode 
	 * @return string
	 */
	public static function table($format) {
		return Slice_Mode::factory($format);
	}

}
