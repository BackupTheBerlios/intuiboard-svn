<?php
/*
========================================
| IntuiBoard 0.1
| http://www.intuiboard.com
----------------------------------------
| Copyright 2005 Michael Corcoran
========================================
| Board Index
========================================
*/

class board {
	var $ib_core;
	
	function board(&$ibcore) {
		$this->ib_core =& $ibcore;
	}
}
?>