<?php
// ----------------------------------------------------------------------------
// reg.php
// Copyright (C) 2003  SyncIT.com, Inc.
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software Foundation,
// Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
// ----------------------------------------------------------------------------
// This library is GPL'd.  If you distribute this program or a derivative of
// this program publicly you must include the source code.  It is easy
// enough to drop us an email requesting a different license, if necessary.
//
// Description: Creates registry file for Win32.
// Created:     July 1998, SyncIT.com, Inc.
// Modified:    $Date: 2003/11/01 11:14:21 $, $Author: siebert $
// ----------------------------------------------------------------------------
?>
<?php
header("Content-type: text/plain");
header("Content-Disposition: attachment; filename=syncit.reg");

$reg_client = str_replace ("reg.php","client.php",($HTTP_SERVER_VARS["REQUEST_URI"])); 
$reg_root = $_SERVER['SERVER_NAME'];
$reginfo = 'REGEDIT4

[HKEY_CURRENT_USER\Software\SyncIT\BookmarkSync]
"LastEmail"="Enter_Your_Email"
"root"="http://'.$reg_root.'"
"file3"="'.$reg_client.'"
"LastMD5"=""
';
echo $reginfo
?>
