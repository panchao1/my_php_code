<?php
/**
 * 文件读取类
 * @author Pan.Chao
 */
class File_Read {
	
	protected static $_instance = NULL;

	protected $_fileName = '';

	protected $_fileObject = NULL;

	//单例
	public static function instance() {
		if(self::$_instance === NULL) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * before
	 * @return 
	 */
	private function _before($fileUrl) {
		if($this->_fileName == NULL || $this->_fileName != $fileUrl) {
			$this->_fileName = $fileUrl;
			try {
				$this->_fileObject = new SplFileObject($fileUrl, 'rb');
			} catch (Exception $e) {
				throw new Exception("打开文件 $fileUrl 失败：$e->getMessage()");
			}
		}
		
		//是否可读
		if(! $this->_fileObject->isReadable()) {
			throw new Exception("文件不可读");
		}
	}

	/** 
	 * 读取文件全部的数据
	 * @param string $fileurl
	 * @return array
	 */
	public function readAll($fileUrl) {

		$this->_before($fileUrl);

		$results = array ();
		foreach ($this->_fileObject as $values) {
			$results[] = $values;
		}

		return $results;
	}

	/**
	 * 读取文件制定行
	 * @param integer $starLine
	 * @param integer $endLine
	 * @return array()
	 */
	public function readByLines($fileUrl, $startLine = 1, $endLine = 1) {

		$this->_before($fileUrl);

		$count = $endLine - $startLine;
		$results = array ();
		// 转到第N行
		$this->_fileObject->seek($startLine - 1);
		for($i = 0; $i <= $count; ++$i) {
			
			$results[] = $this->_fileObject->current();
			// 下一行
			$this->_fileObject->next();
		}

		return $results;
	}

	/**
	 * 读取指定某一行
	 * @param integer $line 指定第几行（最小1）
	 * @return string 
	 */
	public function readLine($fileUrl, $line = 1) {

		$this->_before($fileUrl);

		$this->_fileObject->seek($line - 1);
		return $this->_fileObject->current();
	}

}