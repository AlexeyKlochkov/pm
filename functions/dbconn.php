<?php

function db_connect() {

    // defined in config_local.php
    $hostname   = "localhost";
    $user_name  = "root";
    $user_pw    = "";
    $user_db    = "pm1";

    $conn = mysql_pconnect( $hostname, $user_name, $user_pw, 128 );
    if (!$conn) {
      show_db_error( "Connecting to database" );
      return false;
    }

    if (!mysql_select_db( $user_db, $conn )) {
      show_db_error( "Connecting to $user_db" );
      return false;
    }

    return $conn;

  }


function show_db_error( $query = "" )
  {
    echo "<pre>";
    echo "Database Error:";
    echo mysql_errno();
    echo "&nbsp;";
    echo mysql_error();
    echo "<br>";
    echo "Offending Query: $query";
    echo "</pre>";
  }

  function get_aa_for_query( $query )
  {
    $conn = db_connect();
	$debug=0;
    if ( $debug==1 ) echo "get_aa_for_query: About to execute query: $query<br>";
    $result = mysql_query($query);
    if (!$result) {
      show_db_error( $query );
      return false;
    }

    $num_rows = mysql_num_rows($result);
    if ($num_rows ==0) {
      if ( $debug==1 ) echo "No results for query $query!<br>";
      return false;
    }

    $res_array = array();

    for ($count=0; $row = @mysql_fetch_assoc( $result ); $count++) {
      $res_array[$count] = $row;
    }

    return $res_array;
  }
  
function dbConn(){
	$dbConnection = new PDO('mysql:dbname=pm1;host=localhost;charset=utf8', 'root', '');  $dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbConnection;
}
