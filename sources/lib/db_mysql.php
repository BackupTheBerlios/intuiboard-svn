<?php
/*
========================================
| IntuiBoard 0.1
| http://www.intuiboard.com
----------------------------------------
| Copyright 2005 Michael Corcoran
========================================
| MySQL Database Abstraction Layer
========================================
*/

class database {
	// classes needed
	var $ib_core;
	var $timer;
	
	// config info
	var $host;
	var $user;
	var $pass;
	var $database;
	var $prefix;
	var $persistent;
	var $debug;
	
	// connection, query and debug info
	var $conn;
	var $last_result;
	var $last_query;
	var $last_file;
	var $last_line;
	var $query_count = 0;
	var $times = array();
	var $debug_html;
	
	function database(&$ib_core, $config = false) {
		$this->ib_core =& $ib_core;
		
		if(!$config) {
			$config =& $this->ib_core->conf;
		}
		
		$this->host 		=& $config['db_host'];
		$this->user 		=& $config['db_user'];
		$this->pass 		=& $config['db_pass'];
		$this->database 		=& $config['db_database'];
		$this->prefix 		=& $config['db_prefix'];
		$this->persistent 	=& $config['db_persistent'];
		$this->debug 		=& $config['db_debug'];
		
		$this->timer = new timer();
	}
	
	function connect() {
		if($this->persistent) {
			$this->timer->start();
			$this->conn = @mysql_pconnect($this->host, $this->user, $this->pass);
			$this->times['connect'] = $this->timer->stop();
		}
		else {
			$this->timer->start();
			$this->conn = @mysql_connect($this->host, $this->user, $this->pass, true);
			$this->times['connect'] = $this->timer->stop();
		}
		
		if(!$this->conn) {
			$this->_error();
		}
		
		if(!@mysql_select_db($this->database, $this->conn)) {
			$this->_error();
		}
		
		return true;
	}
	
	function close() {
		// can't close, causes issues with sess handler
		//@mysql_close($this->conn);
		
		$this->times['total'] = $this->times['connect'];
		
		if(isset($this->times['queries'])) {
			foreach($this->times['queries'] as $time) {
				$this->times['total'] += $time;
			}
		}
		
		return true;
	}
	
	function query($query, $file, $line, $unbuf = false) {
		$this->last_file = $file;
		$this->last_line = $line;
		
		$this->last_query = $query;
		$this->query_count++;
		
		$this->timer->start();
		if($unbuf) {
			$this->last_result = @mysql_unbuffered_query($query, $this->conn);
		}
		else {
			$this->last_result = @mysql_query($query, $this->conn);
		}
		
		if(!$this->last_result) {
			$this->_error();
		}
		
		$this->times['queries'][$this->query_count] = $this->timer->stop();
		
		return $this->last_result;
	}
	
	function fetch_row($result = false) {
		if(!$result) {
			$result =& $this->last_result;
		}
		
		$this->timer->start();
		
		$row = @mysql_fetch_array($result);
		
		$this->times['queries'][$this->query_count] += $this->timer->stop();
		
		return $row;
	}
	
	function affected_rows() {
		return mysql_affected_rows($this->conn);
	}
	
	function free_result($result = false) {
		if(!$result) {
			$result =& $this->last_result;
		}
		
		return mysql_free_result($result);
	}
	
	function last_insert_id() {
		return mysql_insert_id($this->conn);
	}
	
	function num_rows($result = false) {
		if(!$result) {
			$result =& $this->last_result;
		}
		
		return mysql_num_rows($result);
	}
	
	function server_version() {
		$ver = mysql_get_server_info($this->conn);
		
		if(!preg_match("/^([3-5]{1}).([0-9]{1,2}).([0-9]{1,2})([a-z\-]*)$/", $ver, $matches)) {
			return false;
		}
		
		$version = array(
					'major' => $matches[1],
					'minor' => $matches[2],
					'release' => $matches[3],
					'extra' => $matches[4],
					'full' => $matches[0]
				);
		
		
		return $version;
	}
	
	function _error() {
		die('File: '.$this->last_file.'; Line: '.$this->last_line.'; '.mysql_error($this->conn));
	}
}
?>