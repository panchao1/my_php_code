<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Slice Mode None
 * @author PanChao
 */
class Slice_Mode_None extends Slice_Mode {

	public function execute() {

		return $this->_name;
	}
}