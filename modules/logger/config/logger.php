<?php
/**
 * 日志信息配置
 */
return array(

	/**
	 * 运行日志（文件）
	 */
	'run_log' => array (

		'type' => 'file',
		'parameters' => array (
			'name' => 'run_log',
			'ext' => 'log',
			'path' => APPPATH.'/logs',
			'slice' => '',
		)

	),
	
	/**
	 * 行为日志（数据库）
	 */
	'error_log' => array(
		'type' => 'database',
		'parameters' => array (
			'group' 	 => 'test', 
			'table'      => 'test',
			'slice'		 => '',
		),
		'columns' => array(
			'controller' => strtolower(Request::current()->controller()),
			'action' => strtolower(Request::current()->action()),
			'get' => json_encode($_GET),
			'post' => json_encode($_POST),
			'message' => '',
			'ip' => Request::$client_ip,
			'referer' => Request::current()->referrer(),
			'user_agent' => Request::$user_agent,
			'create_time' => time(),
		),
	),

	
);
