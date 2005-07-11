<?php
/*
========================================
| IntuiBoard 0.1
| http://www.intuiboard.com
----------------------------------------
| Copyright 2005 Michael Corcoran
========================================
| Core Function Library
========================================
*/

class ib_core {
	var $version = '0.1 dev';
	
	var $conf;
	
	var $db;
	var $func;
	var $skin;
	var $output;
	var $nav = array();
	
	var $member;
	
	var $timer;
	
	function ib_core($conf_file) {
		require $conf_file;
		
		$this->conf =& $conf;
		
		$this->member = array('m_id' => 0);
	}
	
	function finish() {
		//won't be needing this no more
		$this->db->close();
		
		// get the server load for stats
		$load = '-';
		if(strpos(strtolower(PHP_OS), "win") === false) {
			if(file_exists("/proc/loadavg")) {
				$load = file_get_contents("/proc/loadavg");
				$load = explode(' ', $load);
				$load = $load[0];
			}
		}
		
		// how long did we take?
		$stats = $this->skin['global']->stats(array('exec' => $this->timer->stop(), 'sql' => $this->db->times['total'], 'queries' => $this->db->query_count, 'load' => $load, 'gzip' => 'Off'));
		
		return $stats;
	}
	
	function get_input() {
		$input = array();
		
		foreach($_GET as $k => $v) {
			$input[$k] = $v;
		}
		foreach($_POST as $k => $v) {
			$input[$k] = $v;
		}
		
		return $input;
	}
	
	function load_skin($skin) {
		if(isset($this->skin[$skin])) {
			return false;
		}
		
		require_once rootpath. 'cache/skin/skin_'. $skin. '.php';
		
		$class = 'skin_'. $skin;
		$this->skin[$skin] = new $class($this->ib_core);
		
		return true;
	}
	
	function validate_form($fields) {
		array(	'username' 	=> 'text',
							'password' 	=> 'text',
							'remember' 	=> 'bool',
							'submit'	=> 'submit'
						);
						
		$clean_data = array();
						
		foreach($fields as $field => $type) {
			if(!isset($this->input[$field])) {
				$value = false;
			}
			else {
				$value = $this->input[$field];
			}
			
			switch($type) {
				case 'text':
					$value = trim($value);
					
					if(!strlen($value)) {
						//error
						die($field.' too short');
					}
					break;
				case 'int':
					if(!strlen($value)) {
						//error
						die($field.' too short');
					}
					
					$value = intval($value);
					break;
				case 'bool':
					//not sure
					break;
				case 'submit':
					if(strlen(trim($value)) < 2) {
						//error
						die('submit not there, possible hack');
					}
					break;
				default:
					break;
			}
			
			if(!$value) {
				//error
				die($field.' empty');
			}
			
			$clean_data[$field] = $value;
		}
		
		return $clean_data;
	}
	
	function build_breadcrumb() {
		$breadcrumb = $this->skin['global']->breadcrumb_start('IntuiBoard', './?act=index');
		
		foreach($this->nav as $crumb) {
			$breadcrumb .= $this->skin['global']->breadcrumb_crumb($crumb[0], $crumb[1]);
		}
		
		return $breadcrumb;
	}
}

// timer class
class timer {
	var $start;
	var $end;
	var $total;
	
	function start() {
		$micro = microtime();
		$micro = explode(' ', $micro);
		$this->start = $micro[1] + $micro[0];
	}
	
	function stop($precision = 4) {
		$micro = microtime();
		$micro = explode(' ', $micro);
		$this->end = $micro[1] + $micro[0];
		
		$this->total = $this->end - $this->start;
		
		return round($this->total, $precision);
	}
}
?>