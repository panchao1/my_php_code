<?php
/**
 * 上传文件类
 */
class File_Upload {
	
	//文件上传的路径
	protected $_path = 'uploads/';

	//文件上传允许的格式
	protected $_allowExt = array();
	
	//文件的最大限制(M)
	protected $_maxSize = 5;
	
	//文件信息
	private $_fileInfos = array();
	
	//对象
	public static $instance = NULL;

	/**
	 * 单例
	 * @return object
	 */
	public static function instance() {

		if(!self::$instance instanceof self) {

			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * 文件上传路径
	 * @param  string $path 路径
	 * @return object
	 */
	public function path($path = '') {
		$this->_path = $path;
		return $this;
	}

	/**
	 * 允许文件上传的格式
	 * @param  array $allowExt 格式
	 * @return object          
	 */
	public function allowExt($allowExt = array()) {
		$this->_allowExt = $allowExt;
		return $this;
	}

	/**
	 * 上传文件大小限制（M）
	 * @param  integer $maxSize 
	 * @return object         
	 */
	public function maxSize($maxSize = 0) {
		$this->_maxSize = $maxSize*1024*1024;
		return $this;
	}

	/**
	 * 上传前准备
	 * @return true or false
	 */
	private function _before() {

		//变量是否存在
		if (!$_FILES || !isset($_FILES)) {
			return FALSE;
		}
		return true;
	}

	/**
	 * 开始执行上传
	 * @return array(); 
	 */
	public function execute() {

		if(!$this->_before()) {
			throw new Exception("The variable $_FILES does not exists", 1);
		}

		$this->_fileInfos = $this->_buildInfo();

		$this->_uploadFile();
	}
	
	/**
	 * 得到文件的信息
	 * @return array() 文件数组信息
	 */
	private function _buildInfo(){
		
		$fileInfos = array();
		//得到文件信息
		$i = 0;
		foreach($_FILES as $file) {
			// 单文件
			if(is_string($file['name'])) {
				$fileInfos[$i] = $file;
				$i++;
			} else {
				// 多文件
				foreach($file['name'] as $key => $value) {
					$fileInfos[$i]['name'] = $value;
					$fileInfos[$i]['size'] = $file['size'][$key];
					$fileInfos[$i]['tmp_name'] = $file['tmp_name'][$key];
					$fileInfos[$i]['error'] = $file['error'][$key];
					$fileInfos[$i]['type'] = $file['type'][$key];
					$i++;
				}
			}
		}
		return $fileInfos;
	}

	/**
	 * 得到文件的扩展名
	 * @param string $filename        	
	 * @return string
	 */
	private function _getExtName($filename) {

		$string1 = explode ('.', $filename);
		$string2 = end($string1);
		//全部变为小写
		$extName = strtolower($string2);
		return $extName;
	}

	/**
	 * 生成唯一的字符串
	 * @return string
	 */
	private function _getUniName() {
		return md5(uniqid(microtime(true), true));
	}
	
	/**
	 * 上传文件的核心函数
     * @param Sting $filename
	 * @return string
	 */
	private function _uploadFile() {
		
		if (!file_exists($this->_path)) {
			mkdir($this->path, 0777, TRUE);
		}
		
		$i = 0;
		if(!($this->_fileInfos && is_array($this->_fileInfos))) {
			throw new Exception("File information is empty", 1);
		}

		foreach($this->_fileInfos as $file) {
			if ($file['error'] == 0) {

				$extName = $this->_getExtName($file['name']);
				if (!in_array($extName, $this->_allowExt)) {
					throw new Exception("The file ".$extName." format error", 1);
				}
				//如果是图片可将其开启
				// 校验是否是一个真正的图片类型
				// if (!getimagesize($file['tmp_name'])) {
					
				// 	exit ( "不是真正的图片类型" );
				// }
				
				if ($file['size'] > $this->_maxSize) {
					throw new Exception("Upload file is too large", 1);
				}
				if (!is_uploaded_file($file['tmp_name'])) {
					throw new Exception("Not uploaded by way of HTTP", 1);	
				}

				$filename =$this->_getUniName() . "." . $extName;
				$destination = $this->_path . "/" . $filename;

				if (move_uploaded_file($file['tmp_name'], $destination)) {
					
					$file['name'] = $filename;
					unset ($file['tmp_name'], $file['size'], $file['type']);
					$uploadedFiles[$i] = $file;
					$i++;
				}
			} else {
				throw new Exception($this->_error($file['error']), 1);
			}
		}
		return $uploadedFiles;
	}

	/**
	 * 上传错误信息提示函数
	 * @param int $data 错误号
	 * @return  String $message 错误信息
	 */
	private function _error($data){
		switch ($data) {
			case 1 :
				$message = "超过了配置文件上传文件的大小"; // UPLOAD_ERR_INI_SIZE
				break;
			case 2 :
				$message = "超过了表单设置上传文件的大小"; // UPLOAD_ERR_FORM_SIZE
				break;
			case 3 :
				$message = "文件部分被上传"; // UPLOAD_ERR_PARTIAL
				break;
			case 4 :
				$message = "没有文件被上传"; // UPLOAD_ERR_NO_FILE
				break;
			case 6 :
				$message = "没有找到临时目录"; // UPLOAD_ERR_NO_TMP_DIR
				break;
			case 7 :
				$message = "文件不可写"; // UPLOAD_ERR_CANT_WRITE;
				break;
			case 8 :
				$message = "由于PHP的扩展程序中断了文件上传"; // UPLOAD_ERR_EXTENSION
				break;
		}
		return $message;		
	}
} 