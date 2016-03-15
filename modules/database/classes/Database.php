<?php
/**
 * Database 类
 * @author panchao
 * eg:
 * Database::factory('mysql')
 * 			->host($host)
 * 			->port($port)
 * 			->user($user)
 * 			->password($password)
 * 			->dbName($dbName)
 * 			->charset($charset)
 * 			->connect();
 */
abstract class Database {

	const TYPE_MYSQL = 'mysql';
	const TYPE_MYSQLI = 'mysqli';
	const TYPE_PDO = 'pdo';

	protected $_results = NULL;
	
	protected $_host = '127.0.0.1';
	protected $_port = '3306';
	protected $_user = 'root';
	protected $_password = '123456';
	protected $_dbName = 'test';
	protected $_charset = 'utf8';

	/**
	 * 工厂
	 * @param  string $type 数据库连接方式
	 * @return object
	 */
	public static function factory($type = '') {

		$type = $type ? strtolower($type) : 'mysql';

		if(!class_exists("Database_$type")) {
			throw new Task_Exception("database connect type: $type not exists");
		}

		return call_user_func(array ("Database_$type", 'instance'));

	}

	/**
	 * host
	 * @param  string $host 
	 * @return object
	 */
	public function host($host = '') {
		$this->_host = $host;
		return $this;
	}

	/**
	 * port
	 * @param  string $port
	 * @return object
	 */
	public function port($port = '3306') {
		$this->_port = $port;
		return $this;
	}

	/**
	 * user
	 * @param  string $user
	 * @return object
	 */
	public function user($user = '') {
		$this->_user = $user;
		return $this;
	}

	/**
	 * password
	 * @param  string $password
	 * @return object
	 */
	public function password($password = '') {
		$this->_password = $password;
		return $this;
	}   

	/**
	 * database
	 * @param  string $dbName
	 * @return object
	 */
	public function dbName($dbName = '') {
		$this->_dbName = $dbName;
		return $this;
	}

	/**
	 * charset
	 * @param  string $charset
	 * @return object
	 */
	public function charset($charset = '') {
		$this->_charset = $charset;
		return $this;
	}

	abstract public function connect();

	abstract public function query($sql);

	abstract public function returnArray();

	abstract public function returnCurrent();

	abstract public function returnRow();

	abstract public function returnInsertId();

	abstract public function close();

}