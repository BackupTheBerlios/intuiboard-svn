<?php
/*
========================================
| IntuiBoard 0.1
| http://www.intuiboard.com
----------------------------------------
| Copyright 2005 Michael Corcoran
========================================
| skin_global
========================================
*/

class skin_global {
var $ib_core;
	
function skin_global(&$ibcore) {
	$this->ib_core =& $ibcore;
}


function wrapper($vars) {
return <<<EOF

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>{$vars['title']}</title>
	<style type="text/css" media="screen">
		{$vars['css']}
	</style>
</head>
<body>
	<div id="wrapper">
		<div id="header">
			{$vars['header']}
		</div>
		<div id="body">
			{$vars['mbar']}
			{$vars['body']}
		</div>
		<div id="footer">
			{$vars['footer']}
		</div>
	</div>
</body>
</html>

EOF;
}

function css_cached($file) {
return <<<EOF

@import url($file);

EOF;
}

function board_header() {
return <<<EOF

<em>Intui</em>Board

EOF;
}

function board_footer($stats = '') {
return <<<EOF

$stats
<div class="copyright">
	<strong><a href="http://www.intuiboard.com">IntuiBoard</a></strong> <em>{$this->ib_core->version}</em> &copy; Copyright 2005 Michael Corcoran
</div>

EOF;
}

function stats($stats) {
return <<<EOF

<div class="stats">
	[ Execution Time: {$stats['exec']}s | SQL Time: {$stats['sql']}s | Queries: {$stats['queries']} | GZip: {$stats['gzip']} | Server Load: {$stats['load']} ]
</div>

EOF;
}

function member_bar(&$user) {
return <<<EOF

<div id="memberbar">
	Welcome <a href="?act=profile&id={$user['m_id']}">{$user['m_name']}</a>! ( <a href="?act=login&code=logout">Logout</a> )
</div>

EOF;
}

function member_bar_guest() {
return <<<EOF

<div id="memberbar">
	Welcome Guest! ( <a href="?act=login">Login</a> or <a href="?act=login&code=reg">Register</a> )
</div>

EOF;
}

function error($str) {
return <<<EOF

<div class="error">
	<h4>Error!</h4>
	<p>$str</p>
</div>

EOF;
}

function redirect($url, $msg) {
return <<<EOF


<div id="redirect">
	<p>{$msg}Please wait while you are redirected.</p>
	<p><a href="{$url}">Click here to continue...</a></p>
</div>

EOF;
}


}
?>