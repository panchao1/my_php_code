<?php
/**
 * 下载文件类
 * @author  Pan.Chao
 */
class File_Download {
	

	protected static $_instance = NULL;

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

	public function hheader($string, $replace = true, $http_response_code = 0){
        $string = str_replace(array("\r", "\n"), array('', ''), $string);
        if(empty($http_response_code) || PHP_VERSION <'4.3'){
            @header($string, $replace);
        }else{
            @header($string, $replace, $http_response_code);
        }
        if(preg_match('/^\s*location:/is', $string)){
            exit();
        }
    }

	public function execute($filepath, $filename = '') {

		global $encoding;
		if(!file_exists($filepath)){
			return 1;
		}
		if($filename == ''){
			$tem = explode('/',$filepath);
			$num = count($tem) - 1;
			$filename = $tem[$num];
			$filetype = substr($filepath, strrpos($filepath, ".") + 1);
		}else{
			$filetype = substr($filename, strrpos($filename, ".") + 1);
		}
		$filename ='"'.(strtolower($encoding) == 'utf-8' && !(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') === FALSE) ? urlencode($filename) : $filename).'"';
		$filesize = filesize($filepath);
		$dateline = time();
		File_Download::hheader('date: '.gmdate('d, d m y h:i:s', $dateline).' gmt');
		File_Download::hheader('last-modified: '.gmdate('d, d m y h:i:s', $dateline).' gmt');
		File_Download::hheader('content-encoding: none');
		File_Download::hheader('content-disposition: attachment; filename='.$filename);
		File_Download::hheader('content-type: '.$filetype);
		File_Download::hheader('content-length: '.$filesize);
		File_Download::hheader('accept-ranges: bytes');
		if(!@empty($_SERVER['HTTP_RANGE'])) {
			list($range) = explode('-',(str_replace('bytes=', '', $_SERVER['HTTP_RANGE'])));
			$rangesize = ($filesize - $range) > 0 ?  ($filesize - $range) : 0;
			File_Download::hheader('content-length: '.$rangesize);
			File_Download::hheader('http/1.1 206 partial content');
			File_Download::hheader('content-range: bytes='.$range.'-'.($filesize-1).'/'.($filesize));
		}
		if($fp = @fopen($filepath, 'rb')) {
			@fseek($fp, $range);
			echo fread($fp, filesize($filepath));
		}
		fclose($fp);
		flush();
		ob_flush();
	}
}