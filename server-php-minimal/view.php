<?php
// ----------------------------------------------------------------------------
// view.php
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
// Description: Tree folder bookmark view.
// Created:     July 1998, SyncIT.com, Inc.
// Modified:    $Date: 2003/10/15 19:35:27 $, $Author: siebert $
// ----------------------------------------------------------------------------
session_start();
?>

<html>
<head>
<base target=_blank>
<title>Your Bookmarks</title>
<style>
.fi {
    font-family: "MS Sans Serif", Arial, Helvetica, sans-serif;
    font-size: 11px;
    font-weight: normal;
	list-style-image: url("images/f.gif");
	cursor: hand;
}

.bi {
	font-family: "MS Sans Serif", Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-weight: normal;
	list-style-image: url("images/a.gif");
}
.bi a:link {text-decoration: none; color: #000099; }
.bi a:visited {text-decoration: none; color: #000099; }
.bi a:hover {text-decoration: underline;color: #0000FF; }
</style>
</head>
<script language="JavaScript">
<!--
var head="display:''"
img1=new Image()
img1.src="images/f.gif"
img2=new Image()
img2.src="images/o.gif"

function nchange(f) {
	f1.style.display='';
	return false;
}

function change(){
   if(!document.all) {
      return
     }
   var noff,soff,myid;
   myid=event.srcElement.id;
   noff=5;soff=4;
   //alert (myid);
   if (myid =="fx1") {
   	myid="fx";
   	soff=0;noff=1;
   }
   if (myid=="fx") {
      var srcIndex = event.srcElement.sourceIndex
      var nested = document.all[srcIndex+noff]
      if (nested.style.display=="none") {
         document.all[srcIndex+soff].src="images/o.gif"
         nested.style.display=''
         //event.srcElement.style.listStyleImage="url(images/o.gif)"
      }
      else {
         document.all[srcIndex+soff].src="images/f.gif"
         nested.style.display="none"
         //event.srcElement.style.listStyleImage="url(images/f.gif)"
      }
   }
}

document.onclick=change

//-->

</script>

<body>
<?php
include 'db.php';

$pid = (isset($_SESSION["pid"]))?$_SESSION["pid"]:"";
if ($pid == "")
	exit();

if (!db_connect())
	die();

{
	$vlen = 0;
	$myurl = array();
	$url_idx = 0;
	$last_line = array();
	$last_items = 0;
	$items = 0;

	$ID = $pid;

	if (!db_connect())
		die();

	$MSIE = strpos($_SERVER['HTTP_USER_AGENT'],"MSIE");
	$NS6 = strpos($_SERVER['HTTP_USER_AGENT'],"Netscape6");
	$NS6 = false;
	$target = $_SESSION['target'];
	if ($target == "")
		$target = "_blank";
	$sql = "select link.path,bookmarks.url from bookmarks right join link on bookmarks.bookid = link.book_id where link.person_id=" . $ID . " and link.expiration is null order by link.path";

	$res = mysql_query($sql);
	if (!$res)
		die("$sql<hr>Cannot retrieve data in tree.php");

	$delim = "";
	$showit = true;
	$level = 1;
	$foldernum = 0;

	while($data = mysql_fetch_assoc($res)){

		$is_folder = false;
		if (!isset($data['url']))
			$is_folder = true;

		$line = explode("\\",substr($data['path'],1));
		$items = count($line);

		if ($is_folder == true){

			if ($items > $last_items){
				for ($idx = 0; $idx < $items-1; $idx++){
					if (!isset($last_line[$idx]) || $line[$idx] != $last_line[$idx]){
						echo "<div id='fx' class='fi'><img src='images/s.gif' width='0'><img src='images/s.gif' width='0'><img src='images/s.gif' height=10 width=" . ($idx*15) . "><img id='fx1' src='images/f.gif'>" . $line[$idx] . "</div>\r\n";
						echo "<div id='fl' style='display:none' style=&{head};>\r\n";
					}
				}
			}

			else if ($items <= $last_items){
				for ($idx = 0; $idx < $last_items - $items +1; $idx++)
					echo "</div>\r\n";

				for ($idx = 0; $idx < $items-1; $idx++){
					if ($last_line[$idx] != $line[$idx]){
						echo "<div id='fx' class='fi'><img src='images/s.gif' width='0'><img src='images/s.gif' width='0'><img src='images/s.gif' height=10 width=" . ($idx*15) . "><img id='fx1' src='images/f.gif'>" . $line[$idx] . "</div>\r\n";
						echo "<div id='fl' style='display:none' style=&{head};>\r\n";
					}
				}
			}

			$last_line = $line;
			$last_items = $items;
		}

		// else we differ on the link only so add a link in the current folder
		else {
			if ($items > 1){
				echo "<div class='bi'><img src='images/s.gif' width='0'><img src='images/s.gif' height=10 width=" . (($items-1)*15) . "><img src='images/a.gif'><a target='" . $target . "' href='" . $data['url'] . "'>" . $line[$items-1] . "</a></div>\r\n";
			}
			else {
				$myurl[$url_idx++] = "<div class='bi'><img src='images/s.gif' width='0'><img src='images/s.gif' height=10 width=" . (($items-1)*15) . "><img src='images/a.gif'><a target='" . $target . "' href='" . $data['url'] . "'>" . $line[0] . "</a></div>\r\n";
			}
		}
	}

	for ($idx = 0; $idx < $items; $idx++)
		echo "</div>\r\n";

	echo "</div>\r\n";

	if ($url_idx > 0){
		for ($idx = 0; $idx < $url_idx; $idx++)
			echo $myurl[$idx];
	}
} 
	for ($idx = 0; $idx < $items; $idx++)
		echo "</div>\r\n";

	echo "</div>\r\n";

	if ($url_idx > 0){
		for ($idx = 0; $idx < $url_idx; $idx++)
			echo $myurl[$idx];
	}

?>
</body>
</html>
