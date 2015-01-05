<?php
session_start();
$_SESSION["user_id"] = "";
session_destroy();
print "session is destroyed";
?>
