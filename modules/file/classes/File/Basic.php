<?php
/**
 * 文件基本操作类
 * 创建文件/文件夹
 * 移动文件/文件夹
 * 删除文件/文件夹
 * 复制文件/文件夹
 * @author Pan.Chao
 */
class File_Basic {


	protected static $_instance = NULL;

	public static function instance() {
		if(self::$_instance === NULL) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * 创建目录
	 * @param  string $dir 
	 * @param  string $code 
	 * @return boolen
	 */
	public function createDir($dir, $code = 0777) {
		
		$result = TRUE;
		$dir = str_replace('', '/', $dir);
		if(!is_dir($dir)) {
			try {
				$result = mkdir($dir, $code, TRUE);	
			} catch (Exception $e) {
				throw new Exception("创建目录 $dir 失败：$e->getMessage()");
			}
		}

		return $result;
	}

	/**
	 * 创建文件 
	 * @param  string $fileUrl  文件路径
	 * @param  boolean $overWrite 是否覆盖
	 * @return boolean
	 */
	public function createFile($fileUrl, $overWrite = FALSE) {

		if(file_exists($fileUrl) && $overWrite == FALSE) {
			throw new Exception("创建文件 $fileUrl 失败：文件 $fileUrl 已经存在");
		} elseif (file_exists($fileUrl) && $overWrite == TRUE) {
			//先删除文件
			$this->deleteFile($fileUrl);
		}

		//创建文件夹
		$path = dirname($fileUrl);
		$this->createDir($path, 0777);

		//创建文件
		try {
			touch($fileUrl);
		} catch (Exception $e) {
			throw new Exception("创建文件 $fileUrl 失败：$e->getMessage()");
		}
		return TRUE;
	}

	/**
	 * 移动文件夹
	 * @param  string  $oldDir    要移动的文件夹
	 * @param  string  $newDir    目标文件夹
	 * @param  boolean $overWrite 是否覆盖
	 * @return boolean
	 */
	public function moveDir($oldDir, $newDir, $overWrite = FALSE) {
		$oldDir = str_replace('', '/', $oldDir);
		$oldDir = substr($oldDir, -1) == '/' ? $oldDir : $oldDir . '/';

		$newDir = str_replace('', '/', $newDir);
		$newDir = substr($newDir, -1) == '/' ? $newDir : $newDir . '/';

		if(!is_dir($oldDir)) {
			throw new Exception("移动文件夹失败：$oldDir 不是目录");
		}
		if(!is_dir($newDir)) {
			$this->createDir($newDir);
		}
		//@todo move dir
		$dir = @opendir($oldDir);
		if(!$dir) {
			throw new Exception("移动文件夹失败：打开目录 $oldDir 失败");
		}
		while(($file = readdir($dir)) !== FALSE) {
			if($file == '.' || $file == '..') {
				continue;
			}

			if(is_file($oldDir . $file)) {
				$this->moveFile($oldDir . $file, $newDir . $file, $overWrite);
			} elseif(is_dir($oldDir . $file)) {
				$this->moveDir($oldDir . $file, $newDir . $file, $overWrite);
			}
		}
		closedir($dir);
		try {
			rmdir($oldDir);	
		} catch (Exception $e) {
			throw new Exception("移动文件夹失败：$e->getMessage()");
		}
		return TRUE;
	}

	/**
	 * 移动文件
	 * @param  string  $fileUrl    要移动的文件
	 * @param  string  $newUrl     目标文件夹下文件
	 * @param  boolean $overWrite  是否覆盖
	 * @return boolean
	 */
	public function moveFile($fileUrl, $newUrl, $overWrite = FALSE) {

		if(!file_exists($fileUrl)) {
			throw new Exception("移动文件失败：文件 $fileUrl 不存在");
		}

		if(file_exists($newUrl) && $overWrite == FALSE) {
			throw new Exception("移动文件失败：文件 $newUrl 已存在");
		} elseif(file_exists($newUrl) && $overWrite == TRUE) {
			$this->deleteFile($newUrl);
		}
		$dir = dirname($newUrl);
		$this->createDir($dir);
		//@todo move file
		try {
			rename($fileUrl, $newUrl);
		} catch (Exception $e) {
			throw new Exception("移动文件 $fileUrl 到 $newUrl 失败");
		}
		return TRUE;

	}

	/**
	 * 删除文件
	 * @param string $fileUrl
	 * @return boolean
	 */
	public function deleteFile($fileUrl) {
		if(file_exists($fileUrl)) {
			try {
				unlink($fileUrl);
			} catch (Exception $e) {
				throw new Exception("删除文件 $fileUrl 失败");
			}
		}
		return TRUE;
	}

	/**
	 * 删除文件夹
	 * @param string $dirUrl
	 * @return boolean
	 */
	public function deleteDir($dirUrl) {
		$dirUrl = substr($dirUrl, -1) == '/' ? $dirUrl : $dirUrl . '/';
		if (!is_dir($dirUrl)) {
			throw new Exception("删除文件夹失败：$dirUrl 不是目录");
		}
		//打开目录
		$dir = @opendir($dirUrl);
		if(!$dir) {
			throw new Exception("删除文件夹失败：不能打开目录 $dirUrl ");
		}
		//列出目录中的文件
		while (($file = readdir($dir)) !== false) {
			if($file == '.' || $file == '..') {
				continue;
			}
			if(is_file($dirUrl . $file)) {
				$this->deleteFile($dirUrl . $file);
			} elseif(is_dir($dirUrl . $file)) {
				$this->deleteDir($dirUrl . $file);
			}
		}
		closedir($dir);
		try {
			rmdir($dirUrl);
		} catch (Exception $e) {
			throw new Exception("删除目录 $dirUrl 失败");
		}

		return TRUE;
	}

	/**
	 * 复制文件
	 * @param string $fileUrl
	 * @param string $newUrl
	 * @param boolean $overWrite
	 * @return boolean
	 */
	public function copyFile($fileUrl, $newUrl, $overWrite = FALSE) {
		if(!file_exists($fileUrl)) {
			throw new Exception("复制文件失败：文件 $fileUrl 不存在");
		}
		if(file_exists($newUrl) && $overWrite == FALSE) {
			throw new Exception("复制文件失败：文件 $newUrl 已经存在");
		} elseif(file_exists($newUrl) && $overWrite == TRUE) {
			$this->deleteFile($newUrl);
		}
		$dir = dirname($newUrl);
		$this->createDir($dir);
		//@to do copy file
		try {
			copy($fileUrl, $newUrl);
		} catch (Exception $e) {
			throw new Exception("复制文件 $fileUrl 到 $newUrl 失败: $e->getMessage()");
		}
		return TRUE;
	}

	/**
	 * 复制文件夹
	 * @param  string $oldDir    
	 * @param  string $newDir    
	 * @param  boolean $overWrite
	 * @return boolean
	 */
	public function copyDir($oldDir, $newDir, $overWrite = FALSE) {
		$oldDir = str_replace('', '/', $oldDir);
		$oldDir = substr($oldDir, -1) == '/' ? $oldDir : $oldDir . '/';

		$newDir = str_replace('', '/', $newDir);
		$newDir = substr($newDir, -1) == '/' ? $newDir : $newDir . '/';

		if(!is_dir($oldDir)) {
			throw new Exception("复制文件夹失败：$oldDir 不是目录");
		}
		if(!is_dir($newDir)) {
			$this->createDir($newDir);
		}
		//@todo copy dir
		$dir = opendir($oldDir);
		if(!$dir) {
			throw new Exception("复制文件夹失败：不能打开目录 $oldDir ");
		}
		while(($file = readdir($dir)) != FALSE) {
			if($file == '.' || $file = '..') {
				continue;
			}
			if(is_file($oldDir . $file)) {
				$this->copyFile($old . $file, $newDir . $file, $overWrite);
			} elseif(is_dir($oldDir . $file)) {
				$this->copyDir($old . $file, $newDir . $file, $overWrite);
			}
		}
		closedir($dir);
		return TRUE;
	}

}