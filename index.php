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
	var $conf;
	
	var $db;
	var $func;
	
	var $timer;
	
	function ib_core($conf_file) {
		require $conf_file;
		
		$this->conf =& $conf;
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


// disconnect the database
$ib_core->db->close();

// how long did we take?
echo '[ Execution Time: '. $ib_core->timer->stop(). ' | SQL Time: '. $ib_core->db->times['total']. 's ]';
?>