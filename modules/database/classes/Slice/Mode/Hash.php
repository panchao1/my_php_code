<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Slice Mode Hash
 * @author PanChao
 */
abstract class Slice_Mode_Hash extends Slice_Mode {

	/**
	 * factory
	 * @param  string $hashType 
	 * @return object
	 */
	public static function factroy($hashType = '') {

		if($hashType == '') {
			return new Slice_Mode_None();
		}
		if(!is_string($hashType)) {
			throw new Slice_Exception("Slice mode hash error");
		}
		$hashType = ucfirst(strtolower($hashType));

		$class = "Slice_Mode_Hash_$hashType";
		if(!class_exists($class)) {
			throw new Slice_Exception("class $class is not exists");
		}

		return new $class();
	}

	abstract public function execute();
}