<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Slice Mode Time
 * @author PanChao
 */
abstract class Slice_Mode_Time extends Slice_Mode {

	
	public static function factroy($timeType = '') {

		if($timeType == '') {
			return new Slice_Mode_None();
		}
		if(!is_string($timeType)) {
			throw new Slice_Exception("Slice mode hash error");
		}
		$timeType = ucfirst(strtolower($timeType));

		$class = "Slice_Mode_Time_$timeType";
		if(!class_exists($class)) {
			throw new Slice_Exception("class $class is not exists");
		}

		return new $class();
	}

	abstract public function execute();
}