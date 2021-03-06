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
|  Skin Template: skin_global
+----------------------------------------------------------------------------------------
*/

if(!defined('breeze')) {
	die('Error: You may not access this file directly!');
}

class skin_global {
var $breeze;
	
function skin_global(&$breeze) {
	$this->breeze =& $breeze;
}


function wrapper($vars) {
return <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>{$vars['title']}</title>
	<meta http-equiv="content-type" content="application/xhtml+xml; charset=iso-8859-1" />
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
<a href="?act=index"><img src="./cache/images/header.png" alt="Breeze" title="Breeze" /></a>
EOF;
}

function board_footer($stats = '') {
return <<<EOF

$stats
<div class="copyright">
	<strong><a href="http://www.breezeboard.com">Breeze</a></strong> <em>{$this->breeze->version}</em> &copy; Copyright 2005 Michael Corcoran
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
				Welcome <a href="{$this->breeze->baseurl}act=member&amp;code=profile&amp;id={$user['m_id']}">{$user['m_name']}</a>! ( <a href="{$this->breeze->baseurl}act=login&amp;code=logout">Logout</a> )
			</div>
EOF;
}

function member_bar_guest() {
return <<<EOF
<div id="memberbar">
				Welcome Guest! ( <a href="{$this->breeze->baseurl}act=login">Login</a> or <a href="{$this->breeze->baseurl}act=login&amp;code=reg">Register</a> )
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
<img src="{$this->breeze->imgurl}nav.png" alt="&raquo;" />&nbsp;<a href="{$url}">{$title}</a>
EOF;
}

function breadcrumb_crumb($title, $url) {
return <<<EOF
&raquo;<a href="{$url}">{$title}</a>
EOF;
}


}
?>