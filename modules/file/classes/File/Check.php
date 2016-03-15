<?php
/**
 * 检查文件类
 * @author  Pan.Chao
 */
class File_Check {
		
	
	protected static $_instance = NULL;

	protected static $_splFileObject = NULL;

	/**
	 * 单例
	 * @return object
	 */
	public static function instance() {

		if(self::$_instance === NULL) {
			self::$_instance = new self();
		}

		return self::$_instance;
	} 

	/**
	 * before
	 * @param  string $fileUrl 
	 */
	private function _before($fileUrl) {
		
		try {
			self::$_splFileObject = new SplFileObject($fileUrl);	
		} catch (Exception $e) {
			throw new Exception("打开文件 $fileUrl 失败：$e->getMessage()");
		}
	}

	/**
	 * 是否是文件
	 * @param  string $fileUrl 
	 * @return boolean 
	 */
	public function isFile($fileUrl) {
		$this->_before($fileUrl);
		return self::$_splFileObject->isFile();
	}

	/**
	 * 是否是文件夹
	 * @param  string $fileUrl 
	 * @return boolean 
	 */
	public function isDir($fileUrl) {
		$this->_before($fileUrl);
		return self::$_splFileObject->isDir();
	}

	/**
	 * 是否为一个符号连接
	 * @param  string $fileUrl 
	 * @return boolean 
	 */
	public function isLink($fileUrl) {
		$this->_before($fileUrl);
		return self::$_splFileObject->isLink();
	}

	/**
	 * 是否是可执行
	 * @param  string $fileUrl 
	 * @return boolean 
	 */
	public function isExecute($fileUrl) {
		$this->_before($fileUrl);
		return self::$_splFileObject->isExecutable();
	}

	/**
	 * 是否可写
	 * @param  string $fileUrl 
	 * @return boolean
	 */
	public function isWrited($fileUrl) {
		$this->_before($fileUrl);
		return self::$_splFileObject->isWritable();
	}

	/**
	 * 是否可读
	 * @param  string $fileUrl 
	 * @return boolean
	 */
	public function isReaded($fileUrl) {
		$this->_before($fileUrl);
		return self::$_splFileObject->isReadable();
	}

}