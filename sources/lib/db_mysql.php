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
	var $last_query;
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
		$this->database 	=& $config['db_database'];
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
			$this->conn = @mysql_connect($this->host, $this->user, $this->pass);
			$this->times['connect'] = $this->timer->stop();
		}
		
		if(!$this->conn) {
			die('Error: Could not connect to the database!');
		}
		
		if(!@mysql_select_db($this->database, $this->conn)) {
			die('Error: Could not select the database!');
		}
		
		return true;
	}
	
	function close() {
		@mysql_close($this->conn);
		
		$this->times['total'] = $this->times['connect'];
		
		if(isset($this->times['queries'])) {
			foreach($this->times['queries'] as $time) {
				$this->times['total'] += $time;
			}
		}
		
		return true;
	}
	
	function query($query) {
		return false;
	}
}
?>