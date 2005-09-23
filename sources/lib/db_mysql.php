<?php
/*
+----------------------------------------------------------------------------------------
|  Breeze {$version_str$} ({$version_num$})
|  http://www.breezeboard.com
+----------------------------------------------------------------------------------------
|  Revision: $WCREV$
|  Date: $WCDATE$
+----------------------------------------------------------------------------------------
|  Copyright (C) {$copyright_year$} Michael Corcoran
+----------------------------------------------------------------------------------------
|  Breeze is free software; you can redistribute it and/or modify
|  it under the terms of the GNU General Public License as published by
|  the Free Software Foundation; either version 2 of the License, or
|  (at your option) any later version.
|  
|  Breeze is distributed in the hope that it will be useful,
|  but WITHOUT ANY WARRANTY; without even the implied warranty of
|  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
|  GNU General Public License for more details.
|  
|  You should have received a copy of the GNU General Public License
|  along with Breeze; if not, write to the Free Software
|  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
+----------------------------------------------------------------------------------------
|  MySQL Database Abstraction Layer
+----------------------------------------------------------------------------------------
*/

if(!defined('breeze')) {
	die('Error: You may not access this file directly!');
}

class database {
	// classes needed
	var $breeze;
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
	var $debug_queries = array();
	var $debug_info = '';
	
	function database(&$breeze, $config = false) {
		$this->breeze =& $breeze;
		
		if(!$config) {
			$config =& $this->breeze->conf;
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
		$this->debug_queries[$this->query_count] = $query;
		
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
	
	function get_debug_html() {
		if(!$this->times['connect']) {
			return '<p>MySQL never connected!</p>';
		}
		
		$html = '';
		
		$html .= '<h4>Connection time: '.$this->times['connect'].'</h4>';
		
		foreach($this->debug_queries as $id => $query) {
			$time = $this->times['queries'][$id];
			
			if(preg_match("/^SELECT/i", $query)) {
				$sql = mysql_query("EXPLAIN ".$query, $this->conn);
				
				$explain = mysql_fetch_assoc($sql);				
				$cols = '';
				$data = '';
				$gotcols = 0;
				
				foreach($explain as $col => $v) {
					$cols .= '<td><h5>'.$col.'</h5></td>';
					$data .= '<td>'.htmlspecialchars($v).'&nbsp;</td>';
					$gotcols++;
				}
						
				$html .= '<table class="db_debug"><tr>';
				$html .= '<td colspan="'.$gotcols.'"><h4>Select Query</h4></td>';
				$html .= '</tr><tr><td colspan="'.$gotcols.'">'.htmlspecialchars($query).'</td></tr>';
				$html .= '<tr>'.$cols.'</tr>';
					
				$html .= '<tr class="db_debug_row">'.$data.'</tr>';
				
				$html .= '<tr><td colspan="'.$gotcols.'"><h5>mySQL time</h5>: '.$time.'s</td></tr></table>';
			}
			else {
				$html .= '<table class="db_debug">';
				$html .= '<tr><td colspan="8"><h4>Non Select Query</h4></td></tr>';
				$html .= '<tr><td colspan="8">'.htmlspecialchars($query).'</td></tr>';
				$html .= '<tr><td colspan="8"><h5>mySQL time</h5>: '.$time.'s</td></tr>';
				$html .= '</table>';
			}
		}
		
		return $html;
	}
}
?>