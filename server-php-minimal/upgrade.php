<?php
// ----------------------------------------------------------------------------
// upgrade.php
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
// Description: Upgrades the database from previous releases.
// Created:     October 2003, Joe Berkemeier
// Modified:    $Date: 2003/11/01 10:44:28 $, $Author: siebert $
// ----------------------------------------------------------------------------

include 'db.php';

$err = 0;

if (!db_connect()) { die('Problem connecting to the database!'); }

// first up, we update all the plaintext passwords.
$res = mysql_query("SELECT personid, pass, email FROM person")
          or die('Problem getting rows from person table');

while ($data = mysql_fetch_assoc($res)) {
    $new_pass = base64_encode(pack("H*", md5($data["email"] . $data["pass"])));
    $id = $data["personid"];
    $sql = "UPDATE person SET pass = '$new_pass' WHERE personid = $id";
    $result = mysql_query($sql) or die('Problem updating passwords');
    if (!$result) { $err = 1; } 
}

if ($err == 1) { echo 'An error has occured updating user passwords. '; }
else { echo 'Your user passwords have been updated properly. '; }

// and now, we update all the tables to include a prefix.
$err = 0; // any positive value is an error. bugger!
mysql_query("alter table bookmarks rename to syncit_bookmarks") or $err++;
mysql_query("alter table buttugly_redir rename to syncit_buttugly_redir") or $err++;
mysql_query("alter table category rename to syncit_category") or $err++;
mysql_query("alter table charsets rename to syncit_charsets") or $err++;
mysql_query("alter table images rename to syncit_images") or $err++;
mysql_query("alter table invitations rename to syncit_invitations") or $err++;
mysql_query("alter table link rename to syncit_link") or $err++;
mysql_query("alter table person rename to syncit_person") or $err++;
mysql_query("alter table publish rename to syncit_publish") or $err++;
mysql_query("alter table removed rename to syncit_removed") or $err++;
mysql_query("alter table subscriptions rename to syncit_subscriptions") or $err++;

if ($err == 1) { echo 'An error has occured updating database table names. '; }
else { echo 'Your database tables have been updated properly. '; }

?>
