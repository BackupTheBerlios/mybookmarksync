Quick installation Guide
========================
SERVER:
=======

Requirements:
~~~~~~~~~~~~~
MySQL Database
PHP installed

Installation:
~~~~~~~~~~~~~
Go to MySQL database, create database, load bookmarksync.sql into it
Copy all php files and license into a web directory of your choice (/syncit?)
Edit the db.php file and set the following variables in the beginning of the 
code to reflect your environment:
$MYSQL_DATABASE 
$MYSQL_HOST 	
$MYSQL_USER 	
$MYSQL_PASSWORD 
Edit the config.php file and change the masterpassword to something secret.
You will need this to be able to configure new users

browse to the page "config.php" in the directory you installed the php files.
enter your name, password, masterpassword and email address, then press ENTER.

After a positive response copy the contents of the textbox to a local
file on your harddisk and name the file syncit.reg

CLIENT
*) Exit the syncit client if it is running
*) Back up your bookmarks
*) Execute the syncit.reg file
*) start the client

If everything went well your client should turn blue/purple after uploading all the bookmarks

Browse to the page index.php in where you installed the php files
Enter your login information - you should see your bookmarks

Repeat client steps for all your other computers




