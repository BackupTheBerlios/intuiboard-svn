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
|  Skin Template: skin_topic
+----------------------------------------------------------------------------------------
*/

if(!defined('breeze')) {
	die('Error: You may not access this file directly!');
}

class skin_topic {
	var $breeze;
	
	function skin_topic(&$breeze) {
		$this->breeze =& $breeze;
	}
	
function topic_head($topic) {
return <<<EOF

<table class="topicrow">
	<tr class="headerrow">
		<td colspan="2">
			<h3>{$topic['t_title']}</h3>
		</td>
	</tr>
EOF;
}

function topic_foot() {
return <<<EOF

</table>
	
EOF;
}

function post_row($post) {
return <<<EOF
	
	<tr class="topicrow">
		<td>
			<h5><a href="?act=member&amp;code=profile&amp;id={$post['p_author_id']}">{$post['p_author_name']}</a></h5>
		</td>
		<td>
			<p class="detail">Posted: {$post['p_date']}</p>
			<p>{$post['p_post']}</p>
		</td>
	</tr>

EOF;
}

}
?>