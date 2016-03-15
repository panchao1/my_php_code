<?php 
/**
 * DB 操作类
 * @author panchao
 *  eg:
 *         DB::select('*')
 *         	->from('test')
 *         	->execute('video_live')
 *         	->as_array();
 */
class DB  {

	protected static $_sql = '';
	
	protected static $_instance = NULL;

	protected $_results = NULL;

	protected $_databaseObject = NULL;

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
	 * select
	 * @param  string $param 参数
	 * @return instance
	 */
	public static function select($param = '*') {
		self::$_sql = 'select '. $param;
		return self::instance();
	}

	/**
	 * update
	 * @param string $table 表名
	 * @return instance
	 */
	public static function update($table = '') {

		self::$_sql = 'update '. $table;
		return self::instance();
	}

	/**
	 * insert
	 * @param string $table 表名
	 * @return instance
	 */
	public static function insert($table = '') {
		
		self::$_sql = 'insert into '. $table;
		return self::instance();
	}

	/**
	 * delete
	 * @param string $table 表名
	 * @return instance
	 */
	public static function delete($table = '') {
		self::$_sql = 'delete from '. $table;
		return self::instance();
	}

	/**
	 * from ->select
	 * @param  string $table 
	 * @return object
	 */
	public  function from($table = '') {
		self::$_sql .= ' from '.$table;
		return $this;
	}
	
	/**
	 * where
	 * @param  string $column 
	 * @param  string $sign   
	 * @param  string $value 
	 * @return object    
	 */
	public function where($column, $sign, $value) {
		self::$_sql .= ' where '.$column. $sign. "'$value'";
		return $this;
	}
	
	/**
	 * where and 
 	 * @param  string $column 
	 * @param  string $sign  
	 * @param  string $value  
	 * @return object
	 */
	public function and_where($column, $sign, $value) {

		self::$_sql .= ' and '.$column . $sign . "'$value'";
		return $this;
	}

	/**
	 * where or 
	 * @param  string $column
	 * @param  string $sign 
	 * @param  string $value
	 * @return object
	 */
	public function or_where($column, $sign, $value) {
		
		self::$_sql .= ' or '.$column . $sign . "'$value'";
		return $this;
	}

	/**
	 * set ->update
	 * @param array $columes 
	 * @param object
	 */
	public function set(array $columns) {
		
		$sqlInfo = array ();
		foreach ($columns as $key => $value) {
			$sqlInfo[] = "$key='$value'"; 
		}

		$sql = " set " . implode(',', $sqlInfo);
		self::$_sql .= $sql;
		return $this;
	}

	/**
	 * colums ->insert
	 * @param  array  $columns 
	 * @return object
	 */
	public function columns(array $columns) {
		
		$columns = implode(',', $columns);
		self::$_sql .= " (" . $columns . ")";
		return $this; 
	}

	/**
	 * values ->insert
	 * @param  array  $values 
	 * @return object
	 */
	public function values(array $values) {
		$values = implode("','", $values);
		self::$_sql .= " values ('" . $values . "')";
		return $this;
	}

	/**
	 * limit $number
	 * @param $integer $number
	 * @return object
	 */
	public function number($number = 0) {

		self::$_sql .= " limit $number,";
		return $this;
	}

	/**
	 * limit $number,offset
	 * @return object
	 */
	public function offset($offset = 0) {
		
		self::$_sql .= "$offset";
		return $this;
	}

	/**
	 * ORDER BY
	 * @param string $column
	 * @param string $type
	 * @return object
	 */
	public function order_by($column, $type = 'DESC') {

		self::$_sql .= " ORDER BY '{$column}' $type";
		return $this;
	}

	/**
	 * execute 执行操作
	 * @return mixed object 
	 */
	public function execute($db = '') {
		
		//此处需要加载数据库配置信息
		//$config = Config::load('database.'.$db);
		//连接数据库
		$this->_databaseObject = Database::factory($config['type'])
				->host($config['connection']['hostname'])
				->port($config['connection']['port'])
				->user($config['connection']['username'])
				->password($config['connection']['password'])
				->dbName($config['connection']['database'])
				->charset($config['charset'])
				->connect();
		
		$this->_databaseObject->query(self::$_sql);
		return $this;
	}
	
	/**
	 * as_array 返回二维数组（select）
	 */
	public function as_array() {
		return $this->_databaseObject->returnArray();
	}

	/**
	 * current 返回一维数组（select 的第一条结果）
	 */
	public function as_current() {
		return $this->_databaseObject->returnCurrent();
	}

	/**
	 * insert_id 返回插入的id（用于insert）
	 */
	public function insert_id() {
		return $this->_databaseObject->returnInsertId();
	}

	/**
	 * affetch_row 返回影响的行数（update/delete/insert）
	 */
	public function row_count() {
		return $this->_databaseObject->returnRow();
	}

}