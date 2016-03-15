<?php 
/**
 * 查找文件方法
 */

/**
 * 取得输入目录所包含的所有目录和文件夹
 * @param  String $dir 查找的目录
 * @return Array       关联数组返回
 */
function deepScanDirAndFiles($dir) {

	$fileArray = array();
	$dirArray = array();

	$dir = rtrim($dir, '//');
	if(is_dir($dir)) {
		$dirHandle = opendir($dir);
		while(false !== ($fileName = readdir($dirHandle))){
			$subFile = $dir . DIRECTORY_SEPARATOR . $fileName;
			if(is_file($subFile)){
				$fileArray[] = $subFile;
			} elseif (is_dir($subFile) && str_replace('.', '', $fileName) !=''){
				$dirArray[] = $subFile;
				$arr = deepScanDir($subFile);  
				$dirArray = array_merge($dirArray, $arr['dir']);
				$fileArray = array_merge($fileArray, $arr['file']);
			}
		}
		closedir($dirHandle);
	}
	return array('dir'=>$dirArray, 'file'=>$fileArray);
}

/**
 * 取得输入目录所包含的所有文件
 * @param  String $dir 查找的目录
 * @return Array       数组
 */
function deepScanFiles($dir) {

	if (is_file($dir)) {
		return array($dir);
	}
	$files = array();
	if (is_dir($dir) && ($dirHandle = opendir($dir))) {
		
		$bslash = DIRECTORY_SEPARATOR;
		while (($filename = readdir($dirHandle)) !== false) {
			if ($filename == '.' || $filename == '..') {
				continue;
			}
			$filetype = filetype($dir . $bslash . $filename);
			if ($filetype == 'dir') {
				$files = array_merge($files, deepScanFiles($dir . $bslash . $filename));
			} elseif ($filetype == 'file') {
				$files[] = $dir . $bslash . $filename;
			}
		}
		closedir($dirHandle);
	}
	return $files;
}
