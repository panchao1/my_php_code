<?php
/**
 * 文件操作类
 * eg: 
 *  File::Create()->
 */
class File {

	protected static $_className = '';

	/**
	 * 调用
	 * @param  string $className 类名
	 * @param  string $arguments 参数
	 * @return object
	 */
	public static function __callStatic($className, $arguments = array ()) {

		$className = "File_" . $className;
		if(!class_exists($className)) {
			throw new Exception("class $className not found");
		}
		return call_user_func(array ($className, 'instance'));
	}

}