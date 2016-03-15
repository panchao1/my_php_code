<?php 
/**
 * Database 数据库连接 Mysql
 * @author panchao
 */
class Database_Mysql extends Database {

	protected $_conn = NULL;
	
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

		$this->_conn = @mysql_connect($this->_host. ":" .$this->_port, $this->_user, $this->_password);
		
		if(!$this->_conn){
			throw new Exception("Mysql connection error:code: ".mysql_errno()."; content:".mysql_error());
		}

		if(!mysql_select_db($this->_dbName, $this->_conn)) {
			throw new Exception("Mysql database select error");	
		}

		$this->query("set names $this->_charset");

		return $this;
	}

	/**
	 * sql执行
	 * @param string $sql 需要执行的sql语句
	 * @return results 
	 */
	public function query($sql) {
		
		$this->_results = mysql_query($sql, $this->_conn);
		if(!$this->_results){
			throw new Exception("Mysql execute sql error:code:".mysql_errno()."; content:".mysql_error(), 1);
		}
		return $this->_results;
	}

	/**
	 * 根据资源返回影响的行数
	 * @return mixed 影响的行数 or FALSE
	 */
	public function returnRow() {
		return $this->_results ? mysql_affected_rows() : FALSE;
	}

	/**
	 * 根据资源返回数组
	 * @return mixed array() or FALSE
	 */
	public function returnArray() {

		if(mysql_num_rows($this->_results)){
			$list = array();
			while($row = mysql_fetch_assoc($this->_results)){
				$list[] = $row;
			}
			return $list;
		}
		return FALSE;
	}

	/**
	 * 根据资源返回当前的第一条数据
	 * @return mixed array() or FALSE
	 */
	public function returnCurrent() {
		return mysql_num_rows($this->_results) ? mysql_fetch_assoc($this->_results) : FALSE;
	}

	/**
	 * 返回操作insert_id
	 * @return id
	 */
	public function returnInsertId() {
		return $this->_results ? mysql_insert_id() : FALSE;
	}

	/**
	 * 关闭资源
	 * @return
	 */
	public function close() {
		if($this->_conn) {
			mysql_close ($this->_conn);
		}
	}

	/**
	 * 析构
	 */
	public function __destruct() {
		$this->close();
	}

}

