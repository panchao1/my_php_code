<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Slice Mode Time Mouth
 * @author PanChao
 */
class Slice_Mode_Time_Mouth extends Slice_Mode_Time {

	public function execute() {

		return $this->_name . "_" .date('m', $this->_key);
	}
}