<?php
/**
 * 自动加载类 (默认是加载 classes 文件夹下面的类)
 * (不使用namespace)
 * 
 * @author Pan.Chao
 */
 class AutoLoad {

 	//需要加载的所有的文件路径
 	protected static $_paths = array ();

 	protected static $_modules = array ();

 	/**
 	 * 加载
 	 * @param  string $class     类名
 	 * @param  string $directory 指定目录
 	 * @return             
 	 */
 	public static function register($class, $directory = 'classes') {

 		$file = str_replace('_', DIRECTORY_SEPARATOR, $class);

 		self::modules();

 		$foundPath = self::findFile($directory, $file);

 		if($foundPath) {
 			require $foundPath;
 			return TRUE;
 		} else {
 			return FALSE;
 		}
 	}

 	/**
 	 * 加载 modules 的路径到 path
 	 * @return 
 	 */
 	public static function modules(array $modules = NULL) {

 		if($modules != NULL) {
 			self::$_modules = $modules;
 		}

 		$paths = array ();
 		foreach (self::$_modules as $name => $path) {

 			if(is_dir($path)) {
 				$paths[] = realpath($path).DIRECTORY_SEPARATOR;
 			} else {
 				continue;
 			}
 		}

		self::$_paths = $paths;
 	}


 	/**
 	 * 查找加载类所在的文件路径
 	 * @param  string $directory 指定目录
 	 * @param  string $file      路径
 	 * @return             
 	 */
 	public static function findFile($directory, $file) {

 		$path = $directory .DIRECTORY_SEPARATOR. $file . '.php';

 		$found = '';
 		foreach (self::$_paths as $dir) {
 			if(is_file($dir . $path)) {
 				$found = $dir . $path;
 				break;
 			}
 		}
 		return $found;
 	}

 }


