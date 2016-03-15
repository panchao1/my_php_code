<?php
/**
 * 文件写入操作类
 */
class File_Write {
	
	protected static $_instance = NULL;

	//单例
	public static function instance() {
		if(self::$_instance === NULL) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * 写入数组到文件
	 * @param string $fileUrl
	 * @param array  $arr
	 * @param string $mode a 默认是追加的方式 w 会重新从头开始写
	 * @param array 
	 */
	public function writeArray($fileUrl, $arr = array(), $mode = 'a') {

		$fp = new SplFileObject($fileUrl, $mode);
		foreach ($arr as $value) {
			$fp->fwrite($value . "\r\n");
		}
	}

	/**
	 * 写入一条信息到文件
	 * @param string $fileUrl 
	 * @param string $data
	 * @param string $mode
	 */
	public function writeOne($fileUrl, $data = '', $mode = 'a') {
		
		$fp = new SplFileObject($fileUrl, $mode);
		$fp->fwrite($data);
	}


} 