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
|  Skin Template: skin_forum
+----------------------------------------------------------------------------------------
*/

if(!defined('breeze')) {
	die('Error: You may not access this file directly!');
}

class skin_forum {
	var $breeze;
	
	function skin_forum(&$breeze) {
		$this->breeze =& $breeze;
	}

function topic_head() {
return <<<EOF

<table class="topicrow">
	<tr class="headerrow">
		<td>
			<h3>Topic Title and Description</h3>
		</td>
		<td class="centre" style="width: 8%">
			<h3>Replies</h3>
		</td>
		<td>
			<h3>Last Post</h3>
		</td>
	</tr>

EOF;
}

function topic_foot() {
return <<<EOF

</table>

EOF;
}

function topic_row($topic) {
return <<<EOF

	<tr class="topicrow">
		<td>
			<h4><a href="?act=topic&amp;id={$topic['t_id']}">{$topic['t_title']}</a></h4>
			<p>{$topic['t_description']}</p>
		</td>
		<td class="centre">{$topic['t_replies']}</td>
		<td>
			<p><a href="{$this->breeze->baseurl}act=member&amp;code=profile&amp;id={$topic['t_last_post_author_id']}">{$topic['t_last_post_author_name']}</a></p>
			<p>{$topic['t_last_post_date']}</p>
		</td>
	</tr>

EOF;
}

}
?>