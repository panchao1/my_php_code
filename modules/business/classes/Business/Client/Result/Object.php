<?php
class Business_Client_Result_Object extends Business_Client_Result implements Countable, Iterator, SeekableIterator, ArrayAccess {
	
	protected $_result;
	
	protected $_currentRow = 0;
	
	protected $_totalRows = 0;
	
	protected $_asObject = NULL;
	
	public function __construct(array $data, $asObject = NULL) {
		if(is_object($asObject)) {
			$asObject = get_class($asObject);
		}
		
		if(is_string($asObject)) {
			$className = $asObject;
			$result = array();
			foreach($data as $row) {
				$result[] = new $className($row);
			}
			
			$this->_asObject = $asObject;
			$this->_result = $result;
		} else {
			$this->_asObject = NULL;
			$this->_result = $data;
		}
		
		$this->_totalRows = count($data);
		unset($data);
	}
	

	public function __destruct() {
	}
	
	public function count() {
		return $this->_totalRows;
	}
	
	public function offsetExists($offset) {
		return ($offset >= 0 && $offset < $this->_totalRows);
	}
	
	public function offsetGet($offset) {
		if(!$this->seek($offset)) {
			return NULL;
		}
		
		return $this->current();
	}
	
	final public function offsetSet($offset, $value) {
		throw new Business_Client_Exception('Results are read-only');
	}
	
	final public function offsetUnset($offset) {
		throw new Business_Client_Exception('Database results are read-only');
	}
	
	public function key() {
		return $this->_currentRow;
	}
	
	public function next() {
		++$this->_currentRow;
		return $this;
	}
	
	public function prev() {
		--$this->_currentRow;
		return $this;
	}
	
	public function rewind() {
		$this->_currentRow = 0;
		return $this;
	}
	
	public function valid() {
		return $this->offsetExists($this->_currentRow);
	}
	
	public function seek($offset) {
		if($this->offsetExists($offset) && isset($this->_result[$offset])) {
			$this->_currentRow = $offset;
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function current() {
		if(!$this->seek($this->_currentRow)) {
			return NULL;
		}
		return $this->_result[$this->_currentRow];
	}
	
	public function __toString() {
		return '';
	}
}