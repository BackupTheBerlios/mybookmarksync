<?php
//	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//	index.php
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
//	Description: bare-bones login to set session variable
//	
//	Author:      Michael Berneis, Terence Way
//	Created:     July 1998
//	Modified:    9/22/2003 by Michael Berneis
//	E-mail:      mailto:opensource@syncit.com
//	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$email = (isset($_POST["email"]))?$_POST["email"]:NULL;
$pass = (isset($_POST["pass"]))?$_POST["pass"]:NULL;
include 'db.php';
if ($email && $pass) {
	if (!db_connect()){
		exit();
	}
	$sql = "select personid,pass from person where email='$email'";
	//echo "$sql<hr>";
	$res = mysql_query($sql);
	if (!$res) {
		echo "invalid email";
		exit();
	}
	$ra = mysql_fetch_row($res);
	if ($ra[1] != $pass) {
		echo "invalid password";
		exit();
	}
	session_start();
	$_SESSION["pid"]=$ra[0];
	header ("Location:view.php");
	exit();
}
?>
<body>
<form method='post' action='index.php'>
EMail: <input name='email' type='text'><br>
Password: <input name='pass' type='password'><br>
<input type=submit value='Log in'>
</form>
</body>