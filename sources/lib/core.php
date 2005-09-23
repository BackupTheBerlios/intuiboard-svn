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
|  Core Function Library
+----------------------------------------------------------------------------------------
*/

if(!defined('breeze')) {
	die('Error: You may not access this file directly!');
}

class breeze {
	var $version = '{$version_str$}';
	var $version_num = '{$version_num$}';
	
	var $conf;
	
	var $db;
	var $func;
	var $output;
	
	var $caches = array();
	
	var $skin;
	var $lang;
	
	var $nav = array();
	
	var $baseurl = '?';
	var $imgurl = './cache/images/';
	
	var $member;
	
	var $timer;
	
	function breeze($conf_file) {
		require $conf_file;
		
		$this->conf =& $conf;
		
		$this->baseurl = $this->conf['base_url'].'?';
		$this->imgurl = $this->conf['image_url'];
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
		
		$db_queries = $this->db->query_count;
		
		if($this->db->debug) {
			$db_queries = '<a href="'.$this->baseurl.$_SERVER['QUERY_STRING'].'&amp;db_debug">'.$db_queries.'</a>';
		}
		
		$gzip = ($this->conf['gzip_compress']) ? 'On' : 'Off';
		
		// how long did we take?
		$stats = $this->skin['global']->stats(array('exec' => $this->timer->stop(), 'sql' => $this->db->times['total'], 'queries' => $db_queries, 'load' => $load, 'gzip' => $gzip));
		
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
		$this->skin[$skin] = new $class($this);
		
		return true;
	}
	
	function load_lang($lang) {
		if(isset($this->lang[$lang])) {
			return false;
		}
		
		require_once rootpath. 'cache/lang/lang_'. $lang. '.php';
		
		$var = 'lang_'. $lang;
		$this->lang[$lang] =& $$var;
		
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
		$breadcrumb = $this->skin['global']->breadcrumb_start($this->conf['board_name'], $this->baseurl.'act=index');
		
		foreach($this->nav as $crumb) {
			$breadcrumb .= $this->skin['global']->breadcrumb_crumb($crumb[0], $crumb[1]);
		}
		
		return $breadcrumb;
	}
	
	function error($type, $extra = false) {
		if(!isset($this->lang['error'])) {
			$this->load_lang('error');
		}
		
		$str = $this->lang['error'][$type];
		
		if($extra && count($extra)) {
			foreach($extra as $var => $val) {
				$str = str_replace('{'.$var.'}', $val, $str);
			}
		}
		
		$str = $this->skin['global']->error($str);
		
		$this->nav = array(array('Error', $this->baseurl));
		
		$this->output->clear_output();
		$this->output->add_output($str);
		$this->output->do_output('Test Forums');
		
		die();
	}
	
	// Generates password salts
	// $length: length of the salt required
	function generate_salt($length = 5) {
		$salt = '';
	
		for($i = 0; $i < $length; $i++) {
			$salt .= chr(rand(40, 126));
		}
		
		return $salt;
	}
	
	// Generates password hashes
	// $md5_password: md5 hash of the password to be hashed
	// $salt: salt to be used, will be generated if not given
	function generate_pass_hash($md5_password, $salt = false) {
		if(!$salt) {
			$salt = $this->generate_salt(5);
		}
	
		$hash = md5(md5($salt).$md5_password);
		
		return $hash;
	}
	
	function my_set_cookie($key, $value) {
		setcookie($key, $value);
	}
	
	function my_unset_cookie($key) {
		setcookie($key, '', time()-(60*60*24*365));
	}
	
	function redirect($url, $msg = '', $wait = 2) {
		if($msg) {
			$msg = $this->lang['global'][$msg];
		}
		
		$wait = intval($wait);
		
		if(!$wait) {
			@header("Location: ".$url);
		}
		
		$html = $this->skin['global']->redirect($url, $msg);
		
		$this->output->clear_output();
		$this->output->add_output($html);
		$this->output->do_output('Test Forums', array('head' => '<meta http-equiv="refresh" content="'.$wait.'; url='.$url.'" />'));
		die();
	}
	
	function get_date($time, $format = 'short') {
		if($format == 'short') {
			$diff = time() - $time;
			
			if($diff < (60 * 59)) {
				$diff = ($diff / 60);
				$diff = round($diff);
				return $diff.' minutes ago';
			}
			else {
				$tz = isset($this->member['m_tz_offset']) ? intval($this->member['m_tz_offset']) : 0;
				$tz = ($tz * 60 * 60);
				$time = ($time + $tz);
				
				$now = (time() + $tz);
				$daydiff = gmdate('j', $now) - gmdate('j', $time);
				
				if(($diff < (60 * 60 * 48)) && ($daydiff < 2)) {
					$timestr = gmdate('g:i a', $time);
					
					switch($daydiff) {
						case 0:
							$daystr = 'Today';
							break;
						case 1:
							$daystr = 'Yesterday';
							break;
					}
					
					return $daystr.', '.$timestr;
				}
				else {
					$datestr = gmdate('j M, g:i a', $time);
					return $datestr;
				}
			}
		}
		else {
			$tz = isset($this->member['m_tz_offset']) ? intval($this->member['m_tz_offset']) : 0;
			$tz = ($tz * 60 * 60);
			$time = ($time + $tz);
			
			$datestr = gmdate('g:i a, jS F Y', $time);
			
			return $datestr;
		}
	}
	
	function load_caches($caches) {
		if(count($caches)) {
			$keys = '';
			
			foreach($caches as $key) {
				$keys .= "'".$key."',";
			}
			
			$keys = preg_replace("#,$#", "", $keys);
			
			$query = "SELECT c_key, c_value FROM ib_caches WHERE c_key IN({$keys})";
			
			$this->db->query($query, __FILE__, __LINE__);
			
			while($row = $this->db->fetch_row()) {
				$this->caches[$row['c_key']] = unserialize($row['c_value']);
			}
			
			return true;
		}
		else {
			return false;
		}
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