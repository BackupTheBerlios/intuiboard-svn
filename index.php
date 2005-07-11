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

// define the core class
require_once rootpath.'sources/lib/core.php';

// setup our core class so we can get onto other things
$ib_core = new ib_core(rootpath.'ib_config.php');

// start timing here
$ib_core->timer = new timer();
$ib_core->timer->start();

// setup the database
require_once rootpath.'sources/lib/db_'.$ib_core->conf['db_driver'].'.php';
$ib_core->db = new database($ib_core);

// get ready for output
require rootpath.'sources/lib/output.php';
$ib_core->output = new output($ib_core);

// get the global skin ready
$ib_core->load_skin('global');

// retrieve user input
$ib_core->input = $ib_core->get_input();

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
require_once rootpath."sources/{$acts[$ib_core->input['act']][1]}.php";
$act = new $acts[$ib_core->input['act']][0]($ib_core);


// disconnect the database just in case
$ib_core->db->close();
?>