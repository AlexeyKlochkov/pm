<?php
include "loggedin.php";
include "functions/dbconn.php";
include "functions/queries.php";


$clicked_pif = $_GET["p"];
$direction = $_GET["dir"];
$rank_orig = $_GET["rank"];



//if down and the current rank is 3, set 3=4 and 4=3
//update pif set rank = 999 where rank = 4
//update pif set rank = 4 where rank = 3
//update pif set rank = 3 where rank = 999
//update_pif_rank($company_id, $pif_rank_old, $pif_rank_new)
if ($direction == "down"){
	$new_rank = $rank_orig + 1;
	$update1 = update_pif_rank2($company_id, $rank_orig, 999);
	$update2 = update_pif_rank2($company_id, $new_rank, $rank_orig);
	$update3 = update_pif_rank2($company_id, 999, $new_rank);
}

if ($direction == "up"){
	$new_rank = $rank_orig - 1;
	$update1 = update_pif_rank2($company_id, $rank_orig, 999);
	$update2 = update_pif_rank2($company_id, $new_rank, $rank_orig);
	$update3 = update_pif_rank2($company_id, 999, $new_rank);
}

$location = "Location: pif_list.php?s=6&sb=p.pif_rank&ascdesc=asc";
//print $company_id;
header($location) ;

?>