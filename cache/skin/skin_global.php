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
			<div id="nav">
				{$vars['nav']}
			</div>
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
<a href="?act=index"><img src="./cache/images/header.png" alt="IntuiBoard" title="IntuiBoard" /></a>
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
				Welcome <a href="{$this->ib_core->baseurl}act=member&amp;code=profile&amp;id={$user['m_id']}">{$user['m_name']}</a>! ( <a href="{$this->ib_core->baseurl}act=login&amp;code=logout">Logout</a> )
			</div>
EOF;
}

function member_bar_guest() {
return <<<EOF
<div id="memberbar">
				Welcome Guest! ( <a href="{$this->ib_core->baseurl}act=login">Login</a> or <a href="{$this->ib_core->baseurl}act=login&amp;code=reg">Register</a> )
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

function breadcrumb_start($title, $url) {
return <<<EOF
<img src="{$this->ib_core->imgurl}nav.png" alt="&raquo;" />&nbsp;<a href="{$url}">{$title}</a>
EOF;
}

function breadcrumb_crumb($title, $url) {
return <<<EOF
&raquo;<a href="{$url}">{$title}</a>
EOF;
}

function online_stats($stats) {
return <<<EOF

<div class="onlinestats">
	<h3>{$this->ib_core->lang['global']['board_stats']}</h3>
	<h4>{$this->ib_core->lang['global']['online_users']}</h4>
	{$stats['users']}
</div>

EOF;
}


}
?>