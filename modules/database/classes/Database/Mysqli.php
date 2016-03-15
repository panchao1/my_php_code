<?php 
/**
 * Database 数据库连接 Mysqli
 * @author panchao
 */
class Database_Mysqli extends Database {


	protected $_mysqli = NULL;

	protected static $_instance = NULL;

	/**
	 * 单例
	 * @return instance
	 */
	public static function instance() {
		if(self::$_instance === NULL) {
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
		try {
			$this->_mysqli = @new mysqli($this->_host, $this->_user, $this->_password, $this->_dbName, $this->_port);  	
			if($this->_mysqli->connect_error){
				throw new Exception("Mysql connection error:code: ". $this->_mysqli->connect_errno ."; content:". $this->_mysqli->connect_error);
			}
		} catch (Exception $e) {
			throw new Exception("Mysql connection error:code: ". $this->_mysqli->connect_errno ."; content:". $this->_mysqli->connect_error);
		}
		
		$this->_mysqli->set_charset("utf8");

		return $this;
	}

	/**
	 * sql执行
	 * @param string $sql 需要执行的sql语句
	 * @return results
	 */
	public function query($sql) {
		
		$this->_results = $this->_mysqli->query($sql);

		if(! $this->_results) {
			throw new Exception("Mysql execute sql error:code:". $this->_mysqli->errno ."; content:". $this->_mysqli->error);
		}
	}

	/**
	 * 根据资源返回影响的行数
	 * @return mixed 影响的行数 or FALSE
	 */
	public function returnRow() {
		
		return $this->_results ? $this->_mysqli->affected_rows : FALSE;
	}

	/**
	 * 根据资源返回数组
	 * @return mixed array() or FALSE
	 */
	public function returnArray() {

		if($this->_results->num_rows){
			$list = array();
			while($row = $this->_results->fetch_assoc()){
				$list[] = $row;
			}
			$this->_results->free();
			return $list;
		}
		return FALSE;
	}

	/**
	 * 根据资源返回当前的第一条数据
	 * @return mixed array() or FALSE
	 */
	public function returnCurrent() {

		return $this->_results->num_rows ? $this->_results->fetch_assoc() : FALSE;
	}

	/**
	 * 返回操作insert_id
	 * @return id
	 */
	public function returnInsertId() {

		return $this->_results ? $this->_mysqli->insert_id : FALSE;
	}

	/**
	 * 关闭资源
	 * @return
	 */
	public function close() {
		
		if($this->_mysqli && ! $this->_mysqli->connect_error) {
			mysqli_close($this->_mysqli);
		}
	}

	/**
	 * 析构
	 */
	public function __destruct() {
		$this->close();
	}

}

