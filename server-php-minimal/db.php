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
//	Description: database configuration
//	
//	Author:      Michael Berneis, Terence Way
//	Created:     July 1998
//	Modified:    9/22/2003 by Michael Berneis
//	E-mail:      mailto:opensource@syncit.com
//	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


//-----------------------------------------
// global db connect function
// configure the following 4 variables for your environment
//-----------------------------------------

function db_connect()
{
$SYNCIT_DATABASE= "syncit";
$MYSQL_HOST 	= "localhost";
$MYSQL_USER 	= "root";
$MYSQL_PASSWORD	= "";

	if (@mysql_connect("$MYSQL_HOST","$MYSQL_USER","$MYSQL_PASSWORD")){
		if (@mysql_select_db("$SYNCIT_DATABASE"))
			return true;
	}

	// else we got an error
//	mysql_close();
	echo mysql_error();
	
	//header("Status: 500 Database error");
	return false;
}


//---------------------------------
// database error handler
//---------------------------------
function dberror($errstr)
{
	echo "Our database is experiencing temporary problems.<br>The site administrator has been notified.<hr>";
	echo "Please try again shortly...";
	echo "<br><h6><font color='#FF0000'>" . $errstr . "</font></h6>";
	exit();
} 

?>