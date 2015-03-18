streamline
==========

project management tool

==========
Installation
==========

1.	With M/W/XAMP installed
	◦	Checkout the GitHub Streamline project to your local server’s web public folder (for the *AMP installs that’s the htdocs folder.
2.	Run your local server’s PHP myAdmin.
	◦	Take the SQL dump from either BH’s code folder or a new dump straight from the live site (be careful, though)
	◦	Upload it to the pm1 table you’ve created in PHPMyAdmin
3.	Go into the users table and check your system_user_name. Is it the same as your Apollo Group login name? It should be.
4.	In the application, go to functions->dbconn.php
	◦	Change the server login info to whatever your local setup is. In the db_connect() function and the dbConn() function
        ▪	Note that mysql_pconnect is a depreciated way to connect to a DB. This must be fixed, probably as a high priority. Currently, however, I just have the error reporting for depreciated code turned off (lines 3-4 functions.php). The correct new way via PDO        is present in the dbconn.php file. The code base needs to be combed through and all connections need to reference this function instead of the depreciated one.
        ▪	NOTE: verify that this file is part of your .gitignore. It MUST BE. don’t commit this file, ever. It’s for local use only. If you’ve probably checked out the project, then it should be part of .gitignore, but please verify this. Eventually, the dbconn.php should be            moved to it’s own directory and never messed with. This should probably happen when we update all the mysql_pconnect’s to PDO's
        ▪	Verify you are connecting to your local DB
5.	Run the application by going to the app’s index page running on your local server. Login. Done.

