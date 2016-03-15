<?php
/**
 * 文件信息类
 * @author Pan.Chao
 */
class File_Info extends File {

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
	 * 文件的大小 kb
	 * @param  string $fileUrl 
	 * @return integer
	 */
	public function getSize($fileUrl) {
		$this->_before($fileUrl);
		return self::$_splFileObject->getSize();
	}

	/**
	 * 扩展名
	 * @param  string $fileUrl 
	 * @return string
	 */
	public function getExtension($fileUrl) {
		$this->_before($fileUrl);
		return self::$_splFileObject->getExtension();
	}

	/**
	 * 创建时间
	 * @param  string $fileUrl 
	 * @param  string $format 
	 * @return integer
	 */
	public function getCreateTime($fileUrl, $format = '') {
		$this->_before($fileUrl);
		if($format != '') {
			return date($format, self::$_splFileObject->getCTime());
		}
		return self::$_splFileObject->getCTime();
	}

	/**
	 * 修改时间
	 * @param  string $fileUrl 
	 * @param  string $format 
	 * @return integer
	 */
	public function getUpdateTime($fileUrl, $format = '') {
		$this->_before($fileUrl);
		if($format != '') {
			return date($format, self::$_splFileObject->getMTime());
		}
		return self::$_splFileObject->getMTime();
	}

	/**
	 * 文件名
	 * @param  string $fileUrl 
	 * @return string
	 */
	public function getFileName($fileUrl) {
		$this->_before($fileUrl);
		return self::$_splFileObject->getFilename();
	}

	/**
	 * 文件类型
	 * @param  string $fileUrl 
	 * @return string
	 */
	public function getType($fileUrl) {
		$this->_before($fileUrl);
		return self::$_splFileObject->getType();
	}

	/**
	 * 相对路径
	 * @param  string $fileUrl 
	 * @return string
	 */
	public function getPath($fileUrl) {
		$this->_before($fileUrl);
		return self::$_splFileObject->getPath();
	}

	/**
	 * 绝对路径
	 * @param  string $fileUrl 
	 * @return string
	 */
	public function getRealPath($fileUrl) {
		$this->_before($fileUrl);
		return self::$_splFileObject->getRealPath();
	}

	/**
	 * 用户uid
	 * @param  string $fileUrl 
	 * @return integer
	 */
	public function getUid($fileUrl) {
		$this->_before($fileUrl);
		return self::$_splFileObject->getOwner();
	}
	
}