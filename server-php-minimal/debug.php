<?php
session_start();
$passwd = trim($_POST["passwd"]);
if ($passwd == 'secret') {
    $_SESSION["pid"] = "ok";
    header("Location: debug.php");
    exit;
}
?>

<html>
<head>
<title>Syncit debugging</title>
</head>
<body>
<h1>Syncit debugging</h1>

<?php
$pid = (isset($_SESSION["pid"]))?$_SESSION["pid"]:"";
if ($pid == "") {
?>
<form method='post' action='debug.php'>
<input type="submit" value="Login">
<input size="14" name="passwd" type="password">
</form>
<?php
} else {    
?>

<form method='post' action='debug.php'>
<h2>State
<input type="submit" value="Toggle" name="button">
</h2>
</form>
<?php
// open our database or die crying.
include "db.php"; if (!db_connect()) { die; }

$debug = false; // set this to log SQL statements and client communication.

$res  = mysql_query("select value from syncit_config where param='debug'");
$data = mysql_fetch_assoc($res);
if (!$data) {
    mysql_query("insert into syncit_config (param, value) values ('debug', 'false')");
} else {
    if ($data["value"] == 'true') {
      $debug = true;
    }
}

$button = trim($_POST["button"]);

if ($button == "Toggle") {
    if ($debug == true) {
        $debug = false;
        mysql_query("update syncit_config set value = 'false' where param = 'debug'");
    } else {
        $debug = true;
        mysql_query("update syncit_config set value = 'true' where param = 'debug'");
    }
} else if ($button == "Truncate") {
    $fh = fopen('debug.log', 'w');
    ftruncate($fh, 0);
    fclose($fh);
} else {
}

if ($debug) {
    print "Debugging is <em>enabled</em>";
} else {
    print "Debugging is <em>disabled</em>";
}
?>
<p>
<form method='post' action='debug.php'>
<h2>Debug log
<input type="submit" value="Refresh" name="button">
<input type="submit" value="Truncate" name="button" onClick="return confirm('Do you really want to delete the logfile?')">
</h2>
</form>
<code>
<?php
if ($fh = fopen('debug.log','r')) {
  while (!feof($fh)) {
    if ($line = fgets($fh,1048576)) {
     print $line . "<br>";
    }
  }
}
?>
</code>
<form method='post' action='debug.php'>
<input type="submit" value="Refresh" name="button">
<input type="submit" value="Truncate" name="button" onClick="return confirm('Do you really want to delete the logfile?')">
</form>
<?php
}
?>
</body>
</html>
