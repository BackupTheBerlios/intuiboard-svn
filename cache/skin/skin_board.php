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
|  along with IntuiBoard; if not, write to the Free Software
|  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
+----------------------------------------------------------------------------------------
|  Skin Template: skin_board
+----------------------------------------------------------------------------------------
*/

if(!defined('breeze')) {
	die('Error: You may not access this file directly!');
}

class skin_board {
var $breeze;
	
function skin_board(&$breeze) {
	$this->breeze =& $breeze;
}


function forum_row($forum) {
return <<<EOF

<div class="forumrow">
	<h3><a href="?act=forum&amp;id={$forum['f_id']}">{$forum['f_name']}</a></h3>
	<p>{$forum['f_description']}</p>
	<p class="detail"><strong>{$forum['f_topics']}</strong> topics, <strong>{$forum['f_replies']}</strong> replies; Last post: {$forum['f_last_topic']} by: {$forum['f_last_poster']}</p>
</div>

EOF;
}

function board_stats($stats) {
return <<<EOF

<div class="onlinestats">
	<h3>{$this->breeze->lang['global']['board_stats']}</h3>
	<h4>{$this->breeze->lang['global']['online_users']}</h4>
	<h5>{$stats['guests']} {$this->breeze->lang['global']['online_guests']}, {$stats['members']} {$this->breeze->lang['global']['online_members']}</h5>
	{$stats['users']}
	<p>{$stats['total_members']} {$this->breeze->lang['global']['total_members']} {$this->breeze->lang['global']['stats_contributed']} {$stats['total_topics']} {$this->breeze->lang['global']['total_topics']} {$this->breeze->lang['global']['stats_and']} {$stats['total_replies']} {$this->breeze->lang['global']['total_replies']}.</p>
</div>

EOF;
}


}
?>