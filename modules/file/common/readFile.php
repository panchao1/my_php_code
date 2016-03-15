<?php
/**
 * 读取文件内容相关方法
 */

	/**
	 * 返回文件从X行到Y行的内容(支持php5、php4)
	 * @param  String  $filename  文件名
	 * @param  integer $startLine 开始行
	 * @param  integer $endLine   结束行
	 * @param  string  $method    方法
	 * @return array() 返回数组
	 */
	function readFileByLines($filename, $startLine = 1, $endLine = 50, $method = 'rb') {
		
		$content = array();
		$count = $endLine - $startLine;
		
		// 判断php版本（因为要用到SplFileObject，PHP>=5.1.0）
		if(version_compare(PHP_VERSION, '5.1.0', '>=')) {
			$fp = new SplFileObject($filename, $method);
			// 转到第N行, seek方法参数从0开始计数
			$fp->seek($startLine - 1);
			for($i = 0; $i <= $count; ++$i) {
				// current()获取当前行内容
				$content[]=$fp->current();

				// 下一行
				$fp->next();
			}
		} else {
			//PHP<5.1
			$fp = fopen($filename, $method);

			if(!$fp) return 'error:can not read file';
			// 跳过前$startLine行
			for($i=1; $i<$startLine; ++$i) {
				fgets($fp);
			}
			// 读取文件行内容
			for($i;$i<=$endLine;++$i){
				$content[]=fgets($fp);
			}
			fclose($fp);
		}
		
		// array_filter过滤：false,null,''
		return array_filter($content); 
	}

	/**
	 * 利用 SplFileObject 读取文件
	 * @param  string $method   方法
	 * @return array
	 */
	function readFile($filename) {

		$datas = new SplFileObject($filename, 'r');

		$contents = array ();
		foreach ($datas as $value) {
			$contents[] = $value;
		}

		return $contents;
	}