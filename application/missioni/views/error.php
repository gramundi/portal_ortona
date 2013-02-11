<?php

/*
 * BadgeEntry
 *
 * Copyright (C) 2007 Scott Severance <http://badgeentry.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA
 * 02111-1307, USA.
 *
 * Author:
 *	Scott Severance <http://www.scottseverance.us>
 */

if(!isset($title)) $title = 'Error';
if(isset($http_status)) {
    switch($http_status) {
        case 404:
            $s = 'HTTP/1.0 404 Not Found';
            break;
        case 403:
            $s = 'HTTP/1.0 403 Forbidden';
            break;
        default: // we should never get here
            $s = 'HTTP/1.0 500 Internal Server Error';
    }
    header($s);
}
if(isset($refresh)) { // $refresh[0]: timeout; $refresh[1]: Optional URL
    $extraHeadData = '<meta http-equiv="refresh" content="'.$refresh[0];
    if(isset($refresh[1])) $extraHeadData .= ';url='.$refresh[1];
    $extraHeadData .= '" />';
}
require('includes/head.php');
?>
<h1><?php echo $title?></h1>
<p class="error"><?=$error?></p>
<?php
require('includes/footer.php');
exit();
?>