<?php
/*
----------------------------------------
| IntuiBoard 0.1
| http://www.intuiboard.com
----------------------------------------
| Copyright 2005 Michael Corcoran
----------------------------------------
*/

// define what software we are and the rootpath
define('ib', true);
define('rootpath', './');

// which errors to report?
error_reporting(E_ALL);

// core class to store globally accessible stuff
class ib_core {
	var $version = '0.1 dev';
	
	var $conf;
	
	var $db;
	var $func;
	var $skin;
	var $output;
	
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
		$stats = $this->skin->stats(array('exec' => $this->timer->stop(), 'sql' => $this->db->times['total'], 'queries' => $this->db->query_count, 'load' => $load, 'gzip' => 'Off'));
		
		return $stats;
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

// setup our core class so we can get onto other things
$ib_core = new ib_core(rootpath.'ib_config.php');

// start timing here
$ib_core->timer = new timer();
$ib_core->timer->start();

// get our standard function library before anything else
require rootpath.'sources/lib/functions.php';
$ib_core->func = new functions($ib_core);

// setup the database
require rootpath.'sources/lib/db_'.$ib_core->conf['db_driver'].'.php';
$ib_core->db = new database($ib_core);

// get ready for output
require rootpath.'sources/lib/output.php';
$ib_core->output = new output($ib_core);

// get the global skin ready
$ib_core->skin =& $ib_core->func->load_skin('global');

// retrieve user input
$ib_core->input = $ib_core->func->get_input();

// connect the database
$ib_core->db->connect();

// heres what we can do
$acts = array(	
				'index'			=> array('board'		,'act_board'		, array()),
				'forum'			=> array('forum'		,'act_forum'		, array()),
				'topic'			=> array('topic'		,'act_topic'		, array()),
				'login'			=> array('login'		,'act_login'		, array()),
			);
			
// what do they want and can they do it?
if(!isset($ib_core->input['act'])) {
	$ib_core->input['act'] = 'index';
}
elseif(!isset($acts[$ib_core->input['act']])) {
	$ib_core->input['act'] = 'index';
}

// do it
require rootpath."sources/{$acts[$ib_core->input['act']][1]}.php";
$act = new $acts[$ib_core->input['act']][0]($ib_core);


// disconnect the database just in case
$ib_core->db->close();
?>