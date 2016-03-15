<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Slice Mode Time Day
 * @author PanChao
 */
class Slice_Mode_Time_Day extends Slice_Mode_Time {

	public function execute() {

		return $this->_name . "_" .date('d', $this->_key);
	}
}