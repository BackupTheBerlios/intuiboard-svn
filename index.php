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

// core class to store globally accessible stuff
class intui_core {
	var $conf;
	var $db;
	
	function intui_core($conf_file) {
		require $conf_file;
		
		$this->conf =& $conf;
	}
}

// setup our core class so we can get onto other things
$ib_core = new intui_core(rootpath.'ib_config.php');
?>