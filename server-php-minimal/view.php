<?php
//	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//	db.php
//	Copyright (C) 2003  SyncIT.com, Inc.
//	
//	This program is free software; you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation; either version 2 of the License, or
//	(at your option) any later version.
//	
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//	
//	You should have received a copy of the GNU General Public License
//	along with this program; if not, write to the Free Software
//	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//	-----------------
//	This library is GPL'd.  If you distribute this program or a derivative of
//	this program publicly you must include the source code.  It is easy
//	enough to drop us an email requesting a different license, if necessary.
//	
//	Description: bare-bones bookmark view
//	
//	Author:      Michael Berneis, Terence Way
//	Created:     July 1998
//	Modified:    9/22/2003 by Michael Berneis
//	E-mail:      mailto:opensource@syncit.com
//	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

session_start();
?>
<html>
<head>
<base target=_blank>
<title>Your Bookmarks</title>
</head>
<body>
<?php
include 'db.php';

$pid = (isset($_SESSION["pid"]))?$_SESSION["pid"]:"";
if ($pid == "")
	exit();

if (!db_connect())
	die();

$res = mysql_query("select name from person where personid = ".$pid);
$data = mysql_fetch_assoc($res);

echo "<h3>" . $data["name"] . "- Bookmarks</h3><ul>";

$res = mysql_query("select link.path,bookmarks.url from bookmarks right join link on bookmarks.bookid=link.book_id where link.person_id=" . $pid . " and link.expiration is null order by link.path");

$delim = "";
$showit = true;
$level = 1;
$foldernum = 0;
$url = "";
while ($data = mysql_fetch_assoc($res)){

	$p = $data["path"];
	$u = $data["url"];
	if (strlen($u)>0) // only bookmarks, no folders
		echo "<li><a href='$u'>$p</a>\r\n";
}
echo "</ul>";
?>

</body>
</html>
