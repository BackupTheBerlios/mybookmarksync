<?php
//	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//	config.php
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
//	Description: Bare-bones user registration
//	
//	Author:      Michael Berneis, Terence Way
//	Created:     July 1998
//	Modified:    9/22/2003 by Michael Berneis
//	E-mail:      mailto:opensource@syncit.com
//	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

// Change this master password
	$masterpass = 'ilovesyncit';
//  ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$master = (isset($_POST["master"]))?$_POST["master"]:NULL;
$name = (isset($_POST["name"]))?$_POST["name"]:NULL;
$email = (isset($_POST["email"]))?$_POST["email"]:NULL;
$pass = (isset($_POST["pass"]))?$_POST["pass"]:NULL;
include 'db.php';
echo "<html><head><title>SyncIt Configuration</title></head><body>";
if ($email && $pass && $name && $master) {
	if ($master != $masterpass) die();
	if (!db_connect()){
		die();
	}
	$name=str_replace("'","''",$name);
	$sql = "insert into person (name,email,pass,registered,token) values ('$name','$email','$pass',Now(),0)";
	//echo "$sql<hr>";
	$res = mysql_query($sql);
	if (!$res) {
		echo "email already registered";	
	} else {
		echo "$email is now registered";
	}
} else {
	print "
		<h3>SyncIt Configuration</h3>
		<form method='post' action='config.php' name='conf'>
		Name: <input name='name' type='text' value='$name'><br>
		EMail: <input name='email' type='text' value='$email'><br>
		Password: <input name='pass' type='password'><br>
		<input type=submit value='Register'><br>
		Master Password: <input name='master' type='password'><br>
		<hr>
	";
}

echo "<a href='reg.php' target=_blank>Click here</a> and open this file to modify your registry for the syncit client to point to your server.<p>";

echo "Or <a href='reg.php' target=_blank>Right-click here</a> and save the file as SYNCIT.REG<br>";
echo "Look at it with a text editor to make sure no mailicious statements are included.<br>";
echo "Then execute it to have the client point to your server:<br>";



?>