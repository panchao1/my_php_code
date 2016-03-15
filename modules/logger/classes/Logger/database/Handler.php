<?php
/**
 * 日志数据库操作
 * @author PanChao
 */
class Logger_Database_Handler extends Logger_Database {


	/**
	 * 初始化对象
	 * @return [type] [description]
	 */
	public static function init() {

		return new self();
	}
	
	public function __construct() {

		parent::__construct();
	}

	/**
	 * 插入信息
	 * @param  array $data
	 * @return boolean
	 */
	public function insert($data) {

		return DB::insert($this->_table)
			->columns(array_keys($data))
			->values(array_values($data))
			->execute($this->_group);
	}

}