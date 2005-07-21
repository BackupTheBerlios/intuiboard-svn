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


function forum_row($forum) {
return <<<EOF

<div class="forumrow">
	<h3><a href="?act=forum&id={$forum['f_id']}">{$forum['f_name']}</a></h3>
	<p>{$forum['f_description']}</p>
	<p class="detail"><strong>{$forum['f_topics']}</strong> topics, <strong>{$forum['f_replies']}</strong> replies; Last post: {$forum['f_last_topic']} by: {$forum['f_last_poster']}</p>
</div>

EOF;
}


}
?>