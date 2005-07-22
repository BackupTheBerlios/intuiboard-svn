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
|  Config File
+----------------------------------------------------------------------------------------
*/

$conf = array(

// Database info
'db_host'			=> 'localhost',
'db_user'			=> 'intuiboa_dev',
'db_pass'			=> 'Sk4CHgfkbr',
'db_database'		=> 'intuiboa_dev',
'db_prefix'			=> 'ib_',
'db_persistent'		=> 0,
'db_debug'			=> 1,
'db_driver'			=> 'mysql',

'base_url'			=> 'http://localhost/intuiboard/dev/current/',
'image_url'			=> 'http://localhost/intuiboard/dev/current/cache/images/',
'file_ext'			=> 'php',

'sess_max_age' 		=> 15,

'single_forum'		=> 0,
'show_stats'		=> 1,
'stats_online_max_age'	=> 15,

'board_name' 		=> 'Development Board',
'site_name'			=> 'IntuiBoard Home',
'site_url'			=> 'http://www.intuiboard.com',

);
?>