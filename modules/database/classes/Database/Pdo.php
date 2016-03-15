<?php 
/**
 * Database 数据库连接 Pdo
 * @author panchao
 */
class Database_Pdo extends Database {


	protected $_pdo = NULL;

	protected static $_instance = NULL;

	/**
	 * 单例
	 * @return instance
	 */
	public static function instance() {
		if(! (self::$_instance instanceof self)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * 连接数据库
	 * @return object
	 */
	public function connect() {
		
		if($this->_host === '') {
			throw new Exception("Mysql database host is not empty");
		}
		if($this->_port === '') {
			throw new Exception("Mysql database port is not empty");
		}
		if($this->_user === '') {
			throw new Exception("Mysql database username is not empty");
		}
		if($this->_dbName === '') {
			throw new Exception("Mysql database dbName is not empty");
		}
		$dns = "mysql:dbname=$this->_dbName;host=$this->_host;port=$this->_port";
		try {
			$this->_pdo = @new PDO($dns, $this->_user, $this->_password, array(PDO::MYSQL_ATTR_INIT_COMMAND =>"SET NAMES $this->_charset"));
			//设置连接属性，抛出异常
			$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			throw new PDOException("Mysql connection error:" . $e->getMessage());
		}
		
		return $this;
	}

	/**
	 * sql执行
	 * @param string $sql 需要执行的sql语句
	 * @return results
	 */
	public function query($sql) {
		
		try {
			$this->_results = $this->_pdo->prepare($sql);
			$this->_results->execute();
			if(! $this->_results) {
				throw new PDOException("Mysql execute sql error:coed :" . PDO::errorCode . "content: " . PDO::errorInfo);
			}
		} catch (PDOException  $e) {
			throw new PDOException("Mysql execute sql error:" . $e->getMessage());
		}
		return $this->_results;
	}

	/**
	 * 根据资源返回影响的行数
	 * @return mixed 影响的行数 or FALSE
	 */
	public function returnRow() {
		
		return $this->_results ? $this->_results->rowCount() : FALSE;
	}

	/**
	 * 根据资源返回数组
	 * @return mixed array() or FALSE
	 */
	public function returnArray() {

		return $this->_results ? $this->_results->fetchAll(PDO::FETCH_ASSOC) : FALSE;
	}

	/**
	 * 根据资源返回当前的第一条数据
	 * @return mixed array() or FALSE
	 */
	public function returnCurrent() {

		return $this->_results ? $this->_results->fetch(PDO::FETCH_ASSOC) : FALSE;
	}

	/**
	 * 返回操作insert_id
	 * @return id
	 */
	public function returnInsertId() {

		return $this->_results ? $this->_pdo->lastinsertid() : FALSE;
	}

	/**
	 * 关闭资源
	 * @return
	 */
	public function close() {
		if($this->_pdo) {
			$this->_pdo = NULL;
		}
	}

	/**
	 * 析构
	 */
	public function __destruct() {
		$this->close();
	}

}

