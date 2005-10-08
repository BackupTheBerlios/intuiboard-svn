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
*/

// define what software we are and the rootpath
define('breeze', true);
define('rootpath', './');

// which errors to report?
error_reporting(E_ALL);

// define the core class
require_once rootpath.'sources/lib/core.php';

// setup our core class so we can get onto other things
$breeze = new breeze(rootpath.'breeze_config.php');

// start timing here
$breeze->timer = new timer();
$breeze->timer->start();

// setup the database
require_once rootpath.'sources/lib/db_'.$breeze->conf['db_driver'].'.php';
$breeze->db = new database($breeze);

// get ready for output
require rootpath.'sources/lib/output.php';
$breeze->output = new output($breeze);

// get the global skin & lang ready
$breeze->load_skin('global');
$breeze->load_lang('global');

// retrieve user input
$breeze->input = $breeze->get_input();

// connect the database
$breeze->db->connect();

// session
require_once rootpath.'sources/lib/session.php';
$breeze->sess = new lib_session($breeze);
$breeze->sess->load_member();


// heres what we can do
$acts = array(	
				'index'			=> array('board'		,'act_board'		, array('stats')),
				'forum'			=> array('forum'		,'act_forum'		, array()),
				'topic'			=> array('topic'		,'act_topic'			, array()),
				'login'				=> array('login'		,'act_login'			, array()),
			);
			
// what do they want and can they do it?
if(!isset($breeze->input['act'])) {
	$breeze->input['act'] = 'index';
}

$breeze->input['act'] = strtolower($breeze->input['act']);

if(!isset($acts[$breeze->input['act']])) {
	$breeze->input['act'] = 'index';
}

// load caches
$breeze->add_cache('config');
$breeze->add_cache($acts[$breeze->input['act']][2]);
$breeze->load_caches();

// load config
$breeze->load_config_cache();

// do it
require_once rootpath."sources/{$acts[$breeze->input['act']][1]}.php";
$act = new $acts[$breeze->input['act']][0]($breeze);


// disconnect the database just in case
$breeze->db->close();
?>