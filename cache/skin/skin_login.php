<?php
/*
+----------------------------------------------------------------------------------------
|  IntuiBoard {$version_str$} ({$version_num$})
|  http://www.intuiboard.com
+----------------------------------------------------------------------------------------
|  Revision: $WCREV$
|  Date: $WCDATE$
+----------------------------------------------------------------------------------------
|  Copyright (C) {$copyright_year$} Michael Corcoran
+----------------------------------------------------------------------------------------
|  IntuiBoard is free software; you can redistribute it and/or modify
|  it under the terms of the GNU General Public License as published by
|  the Free Software Foundation; either version 2 of the License, or
|  (at your option) any later version.
|  
|  IntuiBoard is distributed in the hope that it will be useful,
|  but WITHOUT ANY WARRANTY; without even the implied warranty of
|  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
|  GNU General Public License for more details.
|  
|  You should have received a copy of the GNU General Public License
|  along with IntuiBoard; if not, write to the Free Software
|  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
+----------------------------------------------------------------------------------------
|  Skin Template: skin_login
+----------------------------------------------------------------------------------------
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
	<input type="hidden" name="code" value="dologin" />
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