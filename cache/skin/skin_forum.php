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
	
function topic_row($topic) {
return <<<EOF

<div class="topicrow">
	<h3><a href="?act=topic&id={$topic['t_id']}">{$topic['t_title']}</a></h3>
	<p>{$topic['t_description']}</p>
	<p class="detail"><strong>{$topic['t_replies']}</strong> replies</p>
</div>

EOF;
}

}
?>