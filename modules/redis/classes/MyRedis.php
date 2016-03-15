<?php
/**
 * redis 操作类 (只能连接一个redis)
 * @author Pan.Chao
 */
 class MyRedis {

 	protected static $_instance = NULL;

 	private $_redisInstance = NULL;

 	private $_host = '127.0.0.1';

 	private $_port = '6379';

 	//单例
 	public static function instance() {

 		if(self::$_instance === NULL) {

 			self::$_instance = new self();

 		}

 		return self::$_instance;
 	}

 	/**
 	 * 构造方法
 	 */
 	private function __construct() {

 		$redis = new Redis();
 		$redis->connect($this->_host, $this->_port);
 		
 		$this->_redisInstance = $redis;
 	}
 	
 	/**
 	 * 设置值，构建一个字符串 
 	 * $timeOut = 0,表示无过期时间
 	 */
 	public function set($key, $value, $timeOut = 0) {

 		$retRedis = $this->_redisInstance->set($key, $value);

 		if($timeOut > 0) {
 			$retRedis = $this->_redisInstance->expire($key, $timeOut);
 		}
 		return $retRedis;
 	}

 	/**
 	 * 构建一个无序集合
 	 */
 	public function sadd($key, $value) {

 		return $this->_redisInstance->sadd($key, $value);
 	}

 	/**
 	 * 构建一个有序集合
 	 */
 	public function zadd($key, $value) {

 		return $this->_redisInstance->zadd($key, $value);
 	}

 	/**
 	 * 取集合对应的元素
 	 */
 	public function smembers($setName) {

 		return $this->_redisInstance->smembers($setName);
 	}

 	/**
 	 * 构建一个列表 -- 先进后出（栈）
 	 */
 	public function lpush($key, $value) {

 		return $this->_redisInstance->lpush($key, $value);
 	}

 	/**
 	 * 构建一个列表 -- 先进先出（队列）
 	 */
 	public function rpush($key, $value) {

 		return $this->_redisInstance->rpush($key, $value);
 	}
 	
 	/**
 	 * 获取所有的列表的数据
 	 */
 	public function lranges($key, $head, $tail) {

 		return $this->_redisInstance->lranges($key, $head, $tail);
 	}

 	/**
 	 * HASH类型，设置一个值
 	 */
 	public function hset($tableName, $key, $value) {

 		return $this->_redisInstance->hset($tableName, $key, $value);
 	}

 	/**
 	 * HASH类型，取一个值
 	 */
 	public function hget($tableName, $key) {

 		return $this->_redisInstance->hget($tableName, $key);
 	}

 	/**
 	 * 设置多个值
 	 */
 	public function sets($keyArray, $timeOut) {
 		if(is_array($keyArray)) {
 			$result = $this->_redisInstance->mset($keyArray);

 			if($timeOut > 0) {
 				foreach ($keyArray as $key => $value) {
 					$this->_redisInstance->expire($key, $timeOut);
 				}
 			}

 			return $result;
 		} else {
 			return "Call function" . __FUNCTION__ . "paramet error!";
 		}
 	}

 	/**
 	 * 根据key来获取数据
 	 */
 	public function get($key) {

 		$result = $this->_redisInstance->get($key);
 		return $result;
 	}

 	/**
 	 * 批量获取多个值
 	 */
 	public function gets($keyArray) {

 		if(is_array($keyArray)) {
 			return $this->_redisInstance->mget($keyArray);
 		} else {
 			return "Call function" . __FUNCTION__ . "paramet error!";
 		}
 	}

 	/**
 	 * 获取所有的key
 	 */
 	public function keyAll() {

 		return $this->_redisInstance->keys('*');
 	}

 	/**
 	 * 删除一条数据
 	 */
 	public function del($key){

 		return $this->_redisInstance->del($key);
 	}

 	/**
 	 * 批量删除多个值
 	 */
 	public function dels($keyArray) {

 		if(is_array($keyArray)) {
 			return $this->_redisInstance->dels($keyArray);
 		} else {
 			return "Call function" . __FUNCTION__ . "paramet error!";
 		}

 	}

 	/**
 	 * 数据自增
 	 */
 	public function increment($key) {

 		return $this->_redisInstance->incr($key);
 	}

 	/**
 	 * 数据自减
 	 */
 	public function decrement($key) {

 		return $this->_redisInstance->decr($key);
 	}

 	/**
 	 * 判断key 是否存在
 	 */
 	public function keyIsExists($key) {

 		return $this->_redisInstance->exists($key);
 	}

 	/**
 	 * 修改key名称
 	 * newKey 不存在时，success; 存在时，failed
 	 */
 	public function updatenName($key, $newKey) {

 		return $this->_redisInstance->rename($key, $newKey);
 	}

 	/**
 	 * 清空数据
 	 */
 	public function flushAll() {

 		return $this->_redisInstance->flushAll();
 	}

 	/**
 	 * 得到redis对象
 	 */
 	public function redisInstance() {

 		return $this->_redisInstance;
 	}

 }


