<?php
// ----------------------------------------------------------------------------
// index.php
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
// Description: Bare-bones login to set session variable.
// Created:     July 1998, SyncIT.com, Inc.
// Modified:    $Date: 2003/11/01 10:44:28 $, $Author: siebert $
// ----------------------------------------------------------------------------

$email = (isset($_POST["email"])) ? $_POST["email"] : NULL;
$pass  = (isset($_POST["pass"]))  ? $_POST["pass"]  : NULL;

if ($email && $pass) {

  include 'db.php'; if (!db_connect()){ exit(); }

  // TODO: These should throw pretty browser errors.
  $result = mysql_query("select personid, pass from syncit_person where email = '$email'");
  if (!$result) { echo "Email address was invalid!"; exit; }
  $row = mysql_fetch_row($result); $md5pw = base64_encode(pack("H*", md5($email . $pass)));
  if ($row[1] != $md5pw) { echo "Password was invalid! ($row[1] / $pass)"; exit; }

  session_start();
  $_SESSION["pid"] = $row[0];
  header("Location: view.php");
  exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
  <style type="text/css" media="all">@import "style.css";</style>
  <title>Bookmark Management Software - BookmarkSync</title>
</head>
<body><div align="center">

<img src="images/header_logo.gif" alt="Bookmark management software, open-sourced and free!"
     height="55" width="700" border="0" vspace="0" style="vertical-align: bottom;"/>

<div class="body">
  <div class="loginbox">
    <form method="post" action="index.php" name="LOGIN">
      <img src="images/header_member.gif" alt="Existing member? Login here!"
           height="18" width="215" border="0" style="background-color: #fff;"/>
      <div style="text-align: right; width: 190px;">
        EMAIL: <input size="14" name="email" style="width: 100px;" type="text" />
        <br />PASSWORD: <input size="14" name="pass" style="width: 100px;" type="password" />
        <br /><input type="image" src="images/button_login.gif" name="LOGIN" 
                     style="background-color: transparent; border: 0;
                            margin-right: 30px; margin-top: 5px;" />
      </div>
    </form>

    <form method="post" action="config.php" name="REGISTER">
      <img src="images/header_newusers.gif" alt="New user? Register here!" height="18"
           width="215" border="0" style="background-color: #fff; padding-top: 5px;"/>
      <p style="padding: 5px; padding-top: 0px; text-transform: none;">
      Fill out below, along with the <em>auth code</em> given to you by this server's admin.</p>

      <div style="text-align: right; width: 190px;">
        <span class="login">NAME: </span> <input size="14" name="name" style="width: 100px;" type="text" />
        <br />EMAIL: <input size="14" name="email" style="width: 100px;" type="text" />
        <br />PASSWORD: <input size="14" name="pass" style="width: 100px;" type="password" />
        <br /><em>AUTH CODE</em>: <input size="14" name="master" style="width: 100px;" type="password" />
        <br /><input type="image" src="images/button_register.gif" name="REGISTER"
                     style="background-color: transparent; border: 0;
                            margin-right: 15px; margin-top: 5px;" />
      </div>
    </form>
  </div>

  <p class="intro">BookmarkSync is the only FREE and OPEN-SOURCED real-time automatic
  synchronization service that allows you to access your bookmarks or favorites
  from any computer or any browser, without changing the way you use the Internet.</p>

  <ul>
    <li>Keep bookmarks in sync across different computers.</li>
    <li>No manual importing or exporting required!</li>
    <li>Keep your Netscape (and/or Mozilla, Firebird, etc.), Internet Explorer,
        your work, and home links in perfect sync.</li>
  </ul>

  <div align="center">
    <img src="images/download_bms.gif" width="180" height="65" border="0"
         alt="Download the BookmarkSync client software for your platform." />
  </div>

  <p>This site is running the BookmarkSync server; users will need to
  <a href="http://sourceforge.net/project/showfiles.php?group_id=91038">download
  the free BookmarkSync client</a> for their platform. Users interested
  in running their own private servers (PHP w/MySQL) should visit the
  <a href="http://www.sourceforge.net/projects/bookmarksync/">SourceForge
  project page</a> for further information.</p>
</div>

<div class="footer">
  Valid <a href="http://validator.w3.org/check/referer">XHTML 1.0</a>
  and <a href="http://jigsaw.w3.org/css-validator/">CSS</a>; Development
  services provided by <a href="http://www.sourceforge.net/">Sourceforge</a>
</div>

</div></body>
</html>