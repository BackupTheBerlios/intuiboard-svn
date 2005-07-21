<?php
/*
========================================
| IntuiBoard 0.1
| http://www.intuiboard.com
----------------------------------------
| Copyright 2005 Michael Corcoran
========================================
| skin_forum
========================================
*/

class skin_forum {
	var $ib_core;
	
	function skin_forum(&$ibcore) {
		$this->ib_core =& $ibcore;
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
			<p><a href="{$this->ib_core->baseurl}act=member&amp;code=profile&amp;id={$topic['t_last_post_author_id']}">{$topic['t_last_post_author_name']}</a></p>
			<p>{$topic['t_last_post_date']}</p>
		</td>
	</tr>

EOF;
}

}
?>