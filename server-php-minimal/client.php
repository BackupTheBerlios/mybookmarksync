<?php
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// client.php
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
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
// -----------------
// This library is GPL'd.  If you distribute this program or a derivative of
// this program publicly you must include the source code.  It is easy
// enough to drop us an email requesting a different license, if necessary.
//
// Description: Server code the Syncit client communicates with. 
//
// Author:      Michael Berneis, Terence Way
// Created:     July 1998
// Modified:    9/22/2003 by Michael Berneis
// E-mail:      mailto:opensource@syncit.com
//
// Modified:    10/01/2003 by Morbus Iff, morbus@disobey.com
// Changes:
//
//    * the debug file was renamed to be more generic, debug routines
//      are now generic (and not just SQL related), and the debug file
//      is opened outside of the debug routine to minimize fopen's.
//    * all exit calls were turned into end_script so that we can close
//      our opened debug file, as well as print to it if necessary.
//    * trimmed a bunch of un-used variables (like $version, $charset, etc.).
//    * added more error checking using the new debug format.
//    * removed currently un-used (in minimal) 'images' table, and
//      added a few apostraphes here and there for SQL statements.
//
// Known bugs:
//    * 8-bit characters? http://groups.yahoo.com/group/bookmarksync/message/12
//      and http://groups.yahoo.com/group/bookmarksync/message/28.
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$debug   = true;     // set this to log SQL statements and client communication.

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// MAIN PROGRAM. Nothing needs to be changed below for normal usage.
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

header("Content-type: text/plain");

// if debugging is turned on, open up the debug log in the current
// directory. this was moved out of the routine so that the fopen
// would only happen once, and not for every SQL statement. this
// debug.log file needs 666 file permissions for the webserver.
$debug_fh = fopen("debug.log", "a");
debug("*** Starting client operation. ***");

// get our passed client parameters
$email   = trim($_POST["Email"]);
$pass    = trim($_POST["md5"]);
$ctoken  = intval($_POST["Token"]);
$content = $_POST["Contents"];
$charset = trim($_POST['CharSet']);
$version = trim($_POST["Version"]);

// and cry if none of them exist. only charset and
// version are optional, but highly recommended.
if (!$email && !$pass && !$ctoken && !$content) {
   echo "*N\r\n*Z\r\n"; end_script("None of our required settings were received.");
}

// print some information about the client.
debug("Client version: $version; Charset: $charset");

// open our database or die crying.
include "db.php"; if (!db_connect()) { die; }

// check email address for validity.
$res  = my_mysql_query("select personid, token, pass from person where email='$email'");
$data = mysql_fetch_assoc($res); // now this should contain this user's db information.
if (!$data){ echo "*N\r\n*Z\r\n"; end_script("No matching email address for '$email'."); }
else { $ID = $data["personid"]; $stoken = intval($data["token"]); }

// check the user's password against an MD5 value.
$md5pw = base64_encode(pack("H*",md5($email . $data['pass'])));
if ($md5pw != $pass) { echo "*P\r\n*Z\r\n"; end_script("Incorrect password for '$email'."); }

// and spit back a little server 'hello'!
echo "*S,Root,\"http://" . $_SERVER['SERVER_NAME'] . "/\"\r\n";

// compare client and server tokens
// to see whether we should update.
if ($stoken > $ctoken) {

    // return the user's bookmark list if server token is greater.
    $res = my_mysql_query("select path, url from link, bookmarks where bookid = book_id ".
                          "and person_id = '$ID' and expiration is NULL order by path");

    // tell the client data is coming.
    echo "*T," . $stoken . "\r\n*B\r\n";

    // and send it all in.
    while ($data = mysql_fetch_assoc($res)) {
       debug("\"$data[path]\",\"$data[url]\"\r\n");
       echo "\"$data[path]\",\"$data[url]\"\r\n";
    }

    echo "*Z\r\n"; end_script("Finished sending server bookmarks.");
}

// Bookmarks seem up to date,
// let's process the add/delete/
// mkdir directives now.
else {

    // if there are commands in the client's
    // content data, then let's process them.
    if (strlen($content) > 0){

        do {

          // TODO: This seems like a really odd-construct to me - looping
          // until we receive an positive from the parseline routine. shouldn't
          // we instead loop through all the "lines" in the content, ending
          // the loop naturally when we've exceeded all of them?
          $i = strpos($content,"\r\n"); if ($i == 0) { break; }

          $bm_row = substr($content,0,$i); // get the current bookmark and send it to be parsed.
          if (parseline($bm_row, $ID)){ echo "*Z\r\n"; end_script("'parseline' returned a positive value."); } 

          // this bookmark is finished, so pop it off our list.
          $content = substr($content,strlen($content)-(strlen($content)-$i-1));
        } while (true);

        // with our updates finished, we update the token and move on.
        my_mysql_query("update person set token = token+1, lastchanged = now() where personid='$ID'");
        $res = my_mysql_query("select token from person where personid='$ID'");

        // get the new server token for client updating.
        $data = mysql_fetch_assoc($res); $stoken = $data["token"];

        // return new server token.
        echo "*T," . $stoken . "\r\n";
    }
}

echo "*Z\r\n"; end_script("*** Finished client operation. ***");




// parses bookmark list line,
// processes adds and deletes.
function parseline($bm_row,$ID) {

    debug("raw: $bm_row");                       // debugging love.
    if (get_magic_quotes_gpc() == 1) {           // auto-escape is on...
       $bm_row = stripslashes($bm_row); }        // so remove extra slashes.
    debug("final: $bm_row");                     // debugging love. 

    $bm_row = trim($bm_row);                     // get rid of ooky whitespace.
    $cmd = substr($bm_row,0,1);                  // first char is what command to perform.
    $bm_row = substr($bm_row,3);                 // strip token of COMMAND,COMMA,SLASH.
    $l = strpos($bm_row,"\"");                   // find the end of our bookmark path.
    $tpath = substr($bm_row,0,$l);               // and save the path itself into $tpath.
    $path = addslashes($tpath);                  // encode slashes and apostraphes for SQL.

    // add bookmark
    if ($cmd == "A") {
       $url = substr($bm_row,strlen($tpath)+3);       // get the URL from our row.
       $url = trim($url,"\"\r\n");                    // remove ookiness and junk.
       $surl = substr($url,7,55).substr($url,-200);   // MySQL indexes can only be 255. 

       // insert the bookmark.
       my_mysql_query("insert into bookmarks (url, surl) values ('$url', '$surl')");
       $res = my_mysql_query("select bookid from bookmarks where surl='$surl'");

       // and check the expiration date. deleted URLs should
       // be regularly purged from the database after a certain
       // amount of time, but if the current URL is set to be
       // deleted, but hasn't yet, we resurrect it.
       if ($data = mysql_fetch_assoc($res)) {
           $bid = $data['bookid']; // shorter.
           $res = my_mysql_query("insert into link (expiration, person_id, access, path, ".
                                 "book_id) values (NULL, '$ID', now(), '$path', '$bid')");

           // bookmark exists already, but expired. Unexpire!
           if (!$res) { my_mysql_query("update link set expiration = NULL, book_id = '$bid' " .
                                       "where person_id = '$ID' and path = '$path'");
           }
       }
    }

    // delete it by setting expiration
    // date. this allows us to undelete
    // before a certain time period passed.
    else if ($cmd == "D") {
        my_mysql_query("update link set expiration = now() where path = '$path' and person_id = '$ID'");
    }

    // make directory, catering
    // to previously expired ones.
    else if ($cmd == "M") {
        $res = my_mysql_query("insert into link (expiration, person_id, access, ".
                              "path) values (NULL, '$ID', now(), '$path')");

        if (!$res) { // resurrect a deleted directory.
            my_mysql_query("update link set expiration = NULL, book_id = NULL ".
                           "where person_id = '$ID' and path = '$path'");
        }
    }

    // remove directory by setting
    // the expiration date to now.
    else if ($cmd == "R") {
        my_mysql_query("update link set expiration = now() where path = '$path' and person_id = '$ID'");
    }

    // bah!
    else {
        echo "*E\r\nInvalid Bookmark Command: $cmd \r\n";
        debug("Invalid Bookmark Command: [$cmd].");
        return true;
    }

    return false;
}


// merely a wrapper around all our SQL queries so that
// we can handle whether a line to our debug should happen.
function my_mysql_query($str) {
    debug($str); // fun!
    $res = mysql_query($str);
    return $res;
}

// merely prints the passed
// string to a debug file.
function debug($str) {
    global $debug, $debug_fh;
    if (!$debug) { return; }
    $date = date("D M j G:i:s T Y");
    fputs($debug_fh,  "[$date] $str\r\n");
}

// end this puppy!
function end_script($str) {
    global $debug, $debug_fh;
    if ($debug) { debug($str); fclose($debug_fh); }
    exit; // good bye, so sad to see you go.
}

?>

