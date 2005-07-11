<?php
/*
========================================
| IntuiBoard 0.1
| http://www.intuiboard.com
----------------------------------------
| Copyright 2005 Michael Corcoran
========================================
| skin_login
========================================
*/

class skin_login {
	var $ib_core;
	
	function skin_login(&$ibcore) {
		$this->ib_core =& $ibcore;
	}


function login_form() {
return <<<EOF

<form name="frmLogin" id="frmLogin" method="post" action="./">
	<input type="hidden" name="act" value="login" />
	<input type="hidden" name="code" value="doreg" />
	<fieldset>
		<legend>Login</legend>
		Username:&nbsp;<input type="text" name="username" id="frmUsername" /><br />
		Password:&nbsp;<input type="password" name="password" id="frmPassword" /><br />
		<input type="checkbox" name="remember" id="frmRemember" checked="checked" />
		<legend>Remember me?</legend><br />
	</fieldset>
	<input type="submit" name="submit" value="Login" />
</form>

EOF;
}


}
?>