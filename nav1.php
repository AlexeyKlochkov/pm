<?php
// Put the links together, loop through and figure out which one is current.

$items = array(
    array('link'=>'index.php', 'label'=>'Home'), 
    array('link'=>'campaigns.php', 'label'=>'AOP Budgets'), 
	array('link'=>'projects.php', 'label'=>'Projects'),
	array('link'=>'pif_list.php', 'label'=>'Project Briefs'),
);


if ($_SESSION["user_level"] ==20){
$items = array(
    array('link'=>'index.php', 'label'=>'Home'), 
    array('link'=>'campaigns.php', 'label'=>'AOP Budgets'), 
	array('link'=>'projects.php', 'label'=>'Projects'),
    array('link'=>'pif_list.php', 'label'=>'Project Briefs'),
    array('link'=>'reports.php', 'label'=>'Reports')
);
}

if ($_SESSION["user_level"] >20){
$items = array(
    array('link'=>'index.php', 'label'=>'Home'), 
    array('link'=>'campaigns.php', 'label'=>'AOP Budgets'), 
	array('link'=>'projects.php', 'label'=>'Projects'),
    array('link'=>'pif_list.php', 'label'=>'Project Briefs'),
    array('link'=>'reports.php', 'label'=>'Reports'), 
	array('link'=>'admin.php', 'label'=>'Admin Tools')
);
}

$menu = '<div id = "navbar">
    <ul class="nav_ul">';
foreach ($items as $val) {
	$menu .= "<li><a href = \"" . $val['link'] . "\"";
	//print  basename($_SERVER['PHP_SELF']);
	if (basename($_SERVER['PHP_SELF']) == $val['link']){
		$menu .= " class='current'";
		}

    $menu .= ">" . $val['label'] . "</a></li>";
}
$menu .= '
    </ul><div id="Logout" style="left: 85%;position:absolute;bottom:20%;"><span style="color: white;">'.strtolower($_SESSION["system_username"]).' </span>
    <a href="loggedout.php"><img src="images/logout.ico" alt="Log out"></a></div></div>';
echo $menu;	

