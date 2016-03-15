<?php
/**
 * 检测php运行时间和内存的类
 * @author Pan.Chao
 *
 * eg: 
 *   	RunTime::start();
 *   	......php code......
 *   	RunTime::stop();
 *   	
 *   	RunTime::spent();
 *    	Runtime::useinternal();
 */
class RunTime {

	//程序运行开始时间
	static private $_startTime = 0;

	//程序运行结束时间
	static private $_topTime  = 0;

	//程序运行花费时间
	static private $_spentTime = 0;

	/**
	 * 程序运行开始
	 */
	public static function start(){
		self::$_startTime = microtime(true);
	}

	/**
	 * 程序运行结束
	 */
	public static function stop(){
		self::$_stopTime = microtime(true);
	}

	/**
	 * 程序运行花费的时间
	 */
	public static function spent(){
		self::$_spentTime = self::$_topTime - self::$_startTime;
		//返回获取到的程序运行时间差 microtime() 单位是微秒
		return number_format(self::$_spentTime * 1000, 4) . 'ms';
	}

	/**
	 * 消耗的内存
	 * @return string
	 */
	public static function useinternal() {
		if (function_exists('memory_get_usage')) {
			return number_format(memory_get_usage()).' 字节';
		}
	}

}
