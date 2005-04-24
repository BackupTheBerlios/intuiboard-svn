<?php
/*
========================================
| IntuiBoard 0.1
| http://www.intuiboard.com
----------------------------------------
| Copyright 2005 Michael Corcoran
========================================
| skin_board
========================================
*/

class skin_board {
var $ib_core;
	
function skin_board(&$ibcore) {
	$this->ib_core =& $ibcore;
}
	
function root_forum($forum) {
return <<<EOF

<div class="title"><a href="?act=forum&id={$forum['f_id']}">&raquo;&nbsp;{$forum['f_name']}</a></div>

EOF;
}

function forum_head() {
return <<<EOF

<table class="forum_table">
	<thead>
		<tr class="tile1">
			<td style="width: 36px">&nbsp;</td>
			<td>Forum</td>
			<td class="centre" style="width: 80px">Topics</td>
			<td class="centre" style="width: 80px">Replies</td>
			<td style="width: 150px">Last Post</td>
		</tr>
	</thead>
EOF;
}

function forum_row($forum) {
return <<<EOF

	<tbody>
		<tr>
			<td class="centre">
				<div style="width: 32px; height: 32px; background: silver">Icon</div>
			</td>
			<td><a href="?act=forum&id={$forum['f_id']}">{$forum['f_name']}</a><br /><em>{$forum['f_description']}</em></td>
			<td class="smalltext centre">{$forum['f_topics']}</td>
			<td class="smalltext centre">{$forum['f_replies']}</td>
			<td class="smalltext">
				{$forum['f_last_topic']}<br />
				by: {$forum['f_last_poster']}
			</td>
		</tr>
	</tbody>

EOF;
}

function forum_foot() {
return <<<EOF

</table>

EOF;
}


}
?>