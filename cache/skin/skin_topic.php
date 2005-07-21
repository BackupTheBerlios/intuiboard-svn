<?php
/*
========================================
| IntuiBoard 0.1
| http://www.intuiboard.com
----------------------------------------
| Copyright 2005 Michael Corcoran
========================================
| skin_topic
========================================
*/

class skin_topic {
	var $ib_core;
	
	function skin_topic(&$ibcore) {
		$this->ib_core =& $ibcore;
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