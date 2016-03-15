<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Slice Mode Number
 * @author PanChao
 */
abstract class Slice_Mode_Number extends Slice_Mode {

	protected static $_n = 0; //切分数

	/**
	 * factroy
	 * @param  integer $number
	 * @return object
	 */
	public static function factroy($number = 0) {

		if(!$number) {
			return new Slice_Mode_None();
		}
		if(!is_numeric($number)) {
			throw new Slice_Exception("Slice mode number error");
		}

		self::$_n = $number;
		
		return new Slice_Mode_Number_N();
	}

	abstract public function execute();
}