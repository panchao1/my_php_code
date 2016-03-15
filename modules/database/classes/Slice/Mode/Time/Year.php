<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Slice Mode Time Year
 * @author PanChao
 */
class Slice_Mode_Time_Year extends Slice_Mode_Time {

	public function execute() {

		return $this->_name . "_" .date('Y', $this->_key);
	}
}