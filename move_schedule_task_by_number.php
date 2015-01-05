<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";

$schedule_id = $_POST["schedule_id"];
$schedule_task_id = $_POST["schedule_task_id"];
$move_after =  $_POST["after_task"];
$orig_display_order =  $_POST["orig_display_order"];
//$swap1 = $_GET["s1"];
//$swap2 = $_GET["s2"];

//print $schedule_id . "--" . $schedule_task_id . "--" . $move_after . "--" . $orig_display_order;

$move_stuff = 1;

//do nothing if they are the same
if ($move_after == $orig_display_order){
	$move_stuff = 0; 
}
if ($move_after == 0){
	if ($orig_display_order == 1){
		$move_stuff = 0; 
	}
}



if ($move_stuff == 1){
	//1. shift everything above $move_after plus 1
	$update_success = move_schedule_tasks($schedule_id, ($move_after + 1), 1);

	//2. move that task to the requested place - $move_after plus 1

	$update_success2 = move_one_schedule_task($schedule_task_id, ($move_after + 1));

	if($orig_display_order < $move_after){
	//3. set everything greater than the orig_display_order minus 1
		$update_success3 = move_schedule_tasks($schedule_id, $orig_display_order, -1);
	}
	if($move_after==0){
	//3. set everything greater than the orig_display_order minus 1
		$update_success3 = move_schedule_tasks($schedule_id, ($orig_display_order+1), -1);
	}
	

}
$location = "Location: manage_tasks.php?s=" . $schedule_id;

header($location) ;

?>