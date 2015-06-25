<?php

// variables or catch variable name misspellings ...)
error_reporting(E_ALL & ~E_DEPRECATED);

function get_campaign_select($company_id, $selected_id){

	$campaign_select = "<select class = \"required\" name = \"campaign_id\"><option value = \"\">Please select</option>\n";
	$arr_campaigns = get_campaigns($company_id, 1);

	if (!empty($arr_campaigns)){
		foreach ($arr_campaigns as $campaign_row){
				$selected = "";
				$campaign_id = $campaign_row["campaign_id"];
				$campaign_code = $campaign_row["campaign_code"];
				$campaign_description = $campaign_row["campaign_description"];
				if ((string)$selected_id == (string)$campaign_id){$selected = "selected";}
				$campaign_select .= "<option " . $selected . " value = \"" . $campaign_id . "\">" . $campaign_description . " (" . $campaign_code . ")</option>\n";
				
		}
	}
	$campaign_select .= "</select>";
	return $campaign_select;
}


function get_campaign_code_select($company_id, $selected_id){

	$campaign_select = "<select class = \"required\" name = \"campaign_id\" id = \"campaign_select\"><option value = \"\">Please select</option>\n";
	$arr_campaigns = get_campaigns($company_id, 1);

	if (!empty($arr_campaigns)){
		foreach ($arr_campaigns as $campaign_row){
				$selected = "";
				$campaign_id = $campaign_row["campaign_id"];
				$campaign_code = $campaign_row["campaign_code"];
				$campaign_description = $campaign_row["campaign_description"];
				$business_unit_name = $campaign_row["business_unit_name"];
				$campaign_year =  $campaign_row["campaign_year"];
				$default_cost_code = $campaign_row["default_cost_code"];
				$business_unit_owner_id = $campaign_row["business_unit_owner_id"];
				if ((string)$selected_id == (string)$campaign_id){$selected = "selected";}
				$campaign_select .= "<option " . $selected . " value = \"" . $campaign_id . "\" costcenter = \"" . $default_cost_code . "\" business_unit_owner = \"" . $business_unit_owner_id . "\">" . $campaign_year . " - " . $business_unit_name . " (" . $campaign_code . ")</option>\n";
				
		}
	}
	$campaign_select .= "</select>";
	return $campaign_select;
}


function get_campaign_code_select_all($company_id, $selected_id){

	$campaign_select = "<select class = \"required\" name = \"campaign_id\" id = \"campaign_select\"><option value = \"\">Please select</option>\n";
	$arr_campaigns = get_campaigns($company_id, 0);

	if (!empty($arr_campaigns)){
		foreach ($arr_campaigns as $campaign_row){
				$selected = "";
				$campaign_id = $campaign_row["campaign_id"];
				$campaign_code = $campaign_row["campaign_code"];
				$campaign_description = $campaign_row["campaign_description"];
				$business_unit_name = $campaign_row["business_unit_name"];
				$campaign_year =  $campaign_row["campaign_year"];
				$default_cost_code = $campaign_row["default_cost_code"];
				$business_unit_owner_id = $campaign_row["business_unit_owner_id"];
				if ((string)$selected_id == (string)$campaign_id){$selected = "selected";}
				$campaign_select .= "<option " . $selected . " value = \"" . $campaign_id . "\" costcenter = \"" . $default_cost_code . "\" business_unit_owner = \"" . $business_unit_owner_id . "\">" . $campaign_year . " - " . $business_unit_name . " (" . $campaign_code . ")</option>\n";
				
		}
	}
	$campaign_select .= "</select>";
	return $campaign_select;
}



function get_project_code_select($company_id, $selected_id){

	$project_code_select = "<select class = \"required\" name = \"project_id\"><option value = \"\">Please select</option>\n";
	$arr_project_code = get_project_codes($company_id, 1);

	if (!empty($arr_project_code)){
		foreach ($arr_project_code as $project_code_row){
				$selected = "";
				$project_id = $project_code_row["project_id"];
				$project_code = $project_code_row["project_code"];
				if ((string)$selected_id == (string)$project_id){$selected = "selected";}
				$project_code_select .= "<option " . $selected . " value = \"" . $project_id . "\">" . $project_code . "</option>\n";
				
		}
	}
	$project_code_select .= "</select>";
	return $project_code_select;
}


function get_business_unit_select($company_id, $selected_id){
	$business_unit_select = "<select class = \"required\" name = \"business_unit_id\"><option value = \"\">Please select</option>";
	$arr_business_units = get_business_units($company_id, 1);

	if (!empty($arr_business_units)){
		foreach ($arr_business_units as $business_unit_row){
				$selected = "";
				$business_unit_id = $business_unit_row["business_unit_id"];
				$business_unit_name = $business_unit_row["business_unit_name"];
				if ((string)$selected_id == (string)$business_unit_id){$selected = "selected";}
				$business_unit_select .= "<option " . $selected . " value = \"" . $business_unit_id . "\">" . $business_unit_name . "</option>";
				
		}
	}
	$business_unit_select .= "</select>";
	return $business_unit_select;
}



function get_product_select($company_id, $selected_id){
	$product_select = "<select class = \"required\" name = \"product_id\"><option value = \"\">Please select</option>\n";
	$arr_products = get_products($company_id, 1);
	if (!empty($arr_products)){
		foreach ($arr_products as $product_row){
				$selected = "";
				$product_id = $product_row["product_id"];
				$product_name = $product_row["product_name"];
				
				if ((string)$selected_id == (string)$product_id){$selected = "selected";}
				
				$product_select .= "<option " . $selected . " value = \"" . $product_id . "\">" . $product_name . "</option>\n";
		}
	}
	$product_select .= "</select>";
	return $product_select;
}


function get_audience_select($company_id, $selected_id){
	$audience_select = "<select class = \"required\" name = \"audience_id\"><option value = \"\">Please select</option>\n";
	$arr_audience = get_audience($company_id, 1);
	if (!empty($arr_audience)){
		foreach ($arr_audience as $audience_row){
				$selected = "";
				$audience_id = $audience_row["audience_id"];
				$audience_name = $audience_row["audience_name"];
				
				if ((string)$selected_id == (string)$audience_id){$selected = "selected";}
				
				$audience_select .= "<option " . $selected . " value = \"" . $audience_id . "\">" . $audience_name . "</option>\n";
		}
	}
	$audience_select .= "</select>";
	return $audience_select;
}


function get_project_manager_select($company_id, $selected_id){
	$projet_manager_select = "<select class = \"required\" name = \"project_manager_id\"><option value = \"\">Please select</option>\n";
	$arr_pm = get_project_managers($company_id, 1);
	if (!empty($arr_pm)){
		foreach ($arr_pm as $pm_row){
				$selected = "";
				$pm_user_id = $pm_row["user_id"];
				$pm_first_name = $pm_row["first_name"];
				$pm_last_name = $pm_row["last_name"];
				$pm_name = $pm_first_name . " " . $pm_last_name;
				if ((string)$selected_id == (string)$pm_user_id){$selected = "selected";}
				
				$projet_manager_select .= "<option " . $selected . " value = \"" . $pm_user_id . "\">" . $pm_name . "</option>\n";
		}
	}
	$projet_manager_select .= "</select>";
	return $projet_manager_select;
}


function get_project_status_select($company_id, $selected_id){
	$projet_status_select = "<select class = \"required\" name = \"project_status_id\"><option value = \"\">Please select</option>\n";
	$arr_project_status = get_project_status($company_id, 1);
	if (!empty($arr_project_status)){
		foreach ($arr_project_status as $status_row){
				$selected = "";
				$project_status_id = $status_row["project_status_id"];
				$project_status_name = $status_row["project_status_name"];
				if ((string)$selected_id == (string)$project_status_id){$selected = "selected";}
				
				$projet_status_select .= "<option " . $selected . " value = \"" . $project_status_id . "\">" . $project_status_name . "</option>\n";
		}
	}
	$projet_status_select .= "</select>";
	return $projet_status_select;
}


function get_vendor_select($company_id, $selected_id){
	$vendor_select = "<select name = \"vendor_id\" id = \"vendor_select\"><option value = \"\">Please select</option>\n";
	$arr_vendor = get_vendors($company_id, 1);
	if (!empty($arr_vendor)){
		foreach ($arr_vendor as $vendor_row){
				$selected = "";
				$vendor_id = $vendor_row["vendor_id"];
				$vendor_name = $vendor_row["vendor_name"];
				if ((string)$selected_id == (string)$vendor_id){$selected = "selected";}
				
				$vendor_select .= "<option " . $selected . " value = \"" . $vendor_id . "\">" . $vendor_name . "</option>\n";
		}
	}
	$vendor_select .= "</select>";
	return $vendor_select;
}


function get_asset_select($project_id, $selected_id){
	$asset_select = "<select name = \"asset_id\"><option value = \"\">Please Select</option>\n";
	$arr_asset = get_assets($project_id);
	if (!empty($arr_asset)){
		foreach ($arr_asset as $asset_row){
				$selected = "";
				$asset_id = $asset_row["asset_id"];
				$asset_name = $asset_row["asset_name"];
				$asset_type_name = $asset_row["asset_type_name"];
				if ((string)$selected_id == (string)$asset_id){$selected = "selected";}
				
				$asset_select .= "<option " . $selected . " value = \"" . $asset_id . "\">" . $asset_name . " (" . $asset_type_name . ")</option>\n";
		}
	}
	$asset_select .= "</select>";
	return $asset_select;
}

function get_asset_type_select($company_id, $selected_id){
	$asset_type_select = "<select class = \"required \" name = \"asset_type_id\"><option value = \"\">Please Select</option>\n";
	$arr_asset_type = get_asset_types($company_id, 1);
	if (!empty($arr_asset_type)){
		foreach ($arr_asset_type as $asset_type_row){
				$selected = "";
				$asset_type_id = $asset_type_row["asset_type_id"];
				$asset_type_name = $asset_type_row["asset_type_name"];
				$asset_type_category_abbrev = $asset_type_row["asset_type_category_abbrev"];

				if ((string)$selected_id == (string)$asset_type_id){$selected = "selected";}
				
				$asset_type_select .= "<option " . $selected . " value = \"" . $asset_type_id . "\">" . $asset_type_category_abbrev . ": " . $asset_type_name . "</option>\n";
		}
	}
	$asset_type_select .= "</select>";
	return $asset_type_select;
}


function get_role_select($company_id, $selected_id){
	$role_select = "<select class = \"required\" name = \"role_id\"><option value = \"\">Please Select</option>\n";
	$arr_roles = get_roles2($company_id, 1);
	if (!empty($arr_roles)){
		foreach ($arr_roles as $role_row){
				$selected = "";
				$role_id = $role_row["role_id"];
				$role_name = $role_row["role_name"];

				if ((string)$selected_id == (string)$role_id){$selected = "selected";}
				
				$role_select .= "<option " . $selected . " value = \"" . $role_id . "\">" . $role_name . "</option>\n";
		}
	}
	$role_select .= "</select>";
	return $role_select;
}

function get_user_level_select($company_id, $selected_id){
	$user_level_select = "<select class = \"required\" name = \"user_level\">\n";
	
	if ($_SESSION["user_level"] > 30){
		$arr_user_levels = get_user_levels_all($company_id);
	}else{
		$arr_user_levels = get_user_levels($company_id);
	}
	if (!empty($arr_user_levels)){
		foreach ($arr_user_levels as $user_level_row){
				$selected = "";
				$user_level_id = $user_level_row["user_level"];
				$user_level_name = $user_level_row["user_level_name"];

				if ((string)$selected_id == (string)$user_level_id){$selected = "selected";}
				
				$user_level_select .= "<option " . $selected . " value = \"" . $user_level_id . "\">" . $user_level_name . "</option>\n";
		}
	}
	$user_level_select .= "</select>";
	return $user_level_select;
}

function get_phase_select($company_id, $selected_id){

	$phase_select = "<select name = \"phase_id\"><option value = \"\">Please select</option>\n";
	$arr_phases = get_phases($company_id, 1);

	if (!empty($arr_phases)){
		foreach ($arr_phases as $phase_row){
				$selected = "";
				$phase_id = $phase_row["phase_id"];
				$phase_name = $phase_row["phase_name"];

				if ((string)$selected_id == (string)$phase_id){$selected = "selected";}
				$phase_select .= "<option " . $selected . " value = \"" . $phase_id . "\">" . $phase_name . "</option>\n";
				
		}
	}
	$phase_select .= "</select>";
	return $phase_select;
}


function get_remaining_phase_select($company_id, $project_id, $selected_id){

	$phase_select = "<select name = \"phase_id\"><option value = \"\">Please select</option>\n";
	$arr_phases = get_remaining_phases($company_id, $project_id);

	if (!empty($arr_phases)){
		foreach ($arr_phases as $phase_row){
				$selected = "";
				$phase_id = $phase_row["phase_id"];
				$phase_name = $phase_row["phase_name"];

				if ((string)$selected_id == (string)$phase_id){$selected = "selected";}
				$phase_select .= "<option " . $selected . " value = \"" . $phase_id . "\">" . $phase_name . "</option>\n";
				
		}
	}
	$phase_select .= "</select>";
	return $phase_select;
}


function get_phase_select_for_project($project_id, $selected_id){

	$phase_select = "<select name = \"phase_id\"><option value = \"0\">None</option>\n";
	$arr_phases = get_project_phases($project_id);

	if (!empty($arr_phases)){
		foreach ($arr_phases as $phase_row){
				$selected = "";
				$phase_id = $phase_row["phase_id"];
				$phase_name = $phase_row["phase_name"];

				if ((string)$selected_id == (string)$phase_id){$selected = "selected";}
				$phase_select .= "<option " . $selected . " value = \"" . $phase_id . "\">" . $phase_name . "</option>\n";
				
		}
	}
	$phase_select .= "</select>";
	return $phase_select;
}


function get_task_select($company_id, $selected_id){

	$task_select = "<select class = \"required\" name = \"task_id\"><option value = \"\">Please Select</option>\n";
	$arr_tasks = get_tasks_by_company($company_id, 1);

	if (!empty($arr_tasks)){
		foreach ($arr_tasks as $task_row){
				$selected = "";
				$task_id = $task_row["task_id"];
				$task_name = $task_row["task_name"];

				if ((string)$selected_id == (string)$task_id){$selected = "selected";}
				$task_select .= "<option " . $selected . " value = \"" . $task_id . "\">" . $task_name . "</option>\n";
				
		}
	}
	$task_select .= "</select>";
	return $task_select;
}

function get_user_select($company_id, $field_name, $select_label, $selected_id, $required){
	$class_string = "";
	if ($required == 1){
		$class_string = " class = \"required\" ";
	}

	$user_select = "<select  " . $class_string  . " name = \"" .  $field_name . "\"><option value = \"\">" . $select_label . "</option>\n";
	$arr_users = get_users_by_company($company_id, 1);

	if (!empty($arr_users)){
		foreach ($arr_users as $user_row){
				$selected = "";
				$user_id = $user_row["user_id"];
				$first_name = $user_row["first_name"];
				$last_name = $user_row["last_name"];
				 $user_name = $first_name . " " . $last_name;
				if ((string)$selected_id == (string)$user_id){$selected = "selected";}
				$user_select .= "<option " . $selected . " value = \"" . $user_id . "\">" . $user_name . "</option>\n";
				
		}
	}
	$user_select .= "</select>";
	return $user_select;
}


function get_user_select_by_role_abbrev($company_id, $field_name, $select_label, $selected_id, $required, $role_abbrev){
	$class_string = "";
	if ($required == 1){
		$class_string = " class = \"required\" ";
	}

	$user_select = "<select  " . $class_string  . " name = \"" .  $field_name . "\"><option value = \"\">" . $select_label . "</option>\n";
	$arr_users = get_users_by_role_abbrev($company_id, $role_abbrev);

	if (!empty($arr_users)){
		foreach ($arr_users as $user_row){
				$selected = "";
				$user_id = $user_row["user_id"];
				$first_name = $user_row["first_name"];
				$last_name = $user_row["last_name"];
				 $user_name = $first_name . " " . $last_name;
				if ((string)$selected_id == (string)$user_id){$selected = "selected";}
				$user_select .= "<option " . $selected . " value = \"" . $user_id . "\">" . $user_name . "</option>\n";
				
		}
	}
	$user_select .= "</select>";
	return $user_select;
}

function get_business_unit_owner_select($company_id, $field_name, $select_label, $selected_id, $required){
	$class_string = "";
	if ($required == 1){
		$class_string = " class = \"required\" ";
	}

	$user_select = "<select  id = \"business_unit_owner\"" . $class_string  . " name = \"" .  $field_name . "\"><option value = \"\">" . $select_label . "</option>\n";
	$arr_users = get_users_by_company($company_id, 1);

	if (!empty($arr_users)){
		foreach ($arr_users as $user_row){
				$selected = "";
				$user_id = $user_row["user_id"];
				$first_name = $user_row["first_name"];
				$last_name = $user_row["last_name"];
				//$business_unit_id = $user_row["business_unit_id"];
				 $user_name = $first_name . " " . $last_name;
				if ((string)$selected_id == (string)$user_id){$selected = "selected";}
				$user_select .= "<option " . $selected . " value = \"" . $user_id . "\">" . $user_name . "</option>\n";
				
		}
	}
	$user_select .= "</select>";
	return $user_select;
}


function get_project_user_select($project_id, $field_name, $select_label, $selected_id, $required){
	$class_string = "";
	if ($required == 1){
		$class_string = " class = \"required\" ";
	}

	$user_select = "<select  " . $class_string  . " name = \"" .  $field_name . "\"><option value = \"\">" . $select_label . "</option>\n";
	$arr_users = get_users_by_project($project_id, 1);

	if (!empty($arr_users)){
		foreach ($arr_users as $user_row){
				$selected = "";
				$user_id = $user_row["user_id"];
				$first_name = $user_row["first_name"];
				$last_name = $user_row["last_name"];
				 $user_name = $first_name . " " . $last_name;
				if ((string)$selected_id == (string)$user_id){$selected = "selected";}
				$user_select .= "<option " . $selected . " value = \"" . $user_id . "\">" . $user_name . "</option>\n";
				
		}
	}
	$user_select .= "</select>";
	return $user_select;
}


function get_schedule_template_select($company_id, $selected_id){
	$schedule_template_select = "<select class = \"required\" name = \"schedule_template_id\"><option value = \"\">Please Select</option>\n";
	$arr_schedule_templates = get_schedule_templates($company_id, 1);
	if (!empty($arr_schedule_templates)){
		foreach ($arr_schedule_templates as $schedule_template_row){
				$selected = "";
				$schedule_template_id = $schedule_template_row["schedule_template_id"];
				$schedule_template_name = $schedule_template_row["schedule_template_name"];

				if ((string)$selected_id == (string)$schedule_template_id){$selected = "selected";}
				
				$schedule_template_select .= "<option " . $selected . " value = \"" . $schedule_template_id . "\">" . $schedule_template_name . "</option>\n";
		}
	}
	$schedule_template_select .= "</select>";
	return $schedule_template_select;
}


function get_user_group_select($company_id, $selected_id){
	$user_group_select = "<select name = \"user_group_id\" id = \"user_group_id\"><option value = \"\">All</option>\n";
	$arr_user_groups = get_user_groups($company_id);
	if (!empty($arr_user_groups)){
		foreach ($arr_user_groups as $user_group_row){
				$selected = "";
				$user_group_id = $user_group_row["user_group_id"];
				$user_group_name = $user_group_row["user_group_name"];

				if ((string)$selected_id == (string)$user_group_id){$selected = "selected";}
				
				$user_group_select .= "<option " . $selected . " value = \"" . $user_group_id . "\">" . $user_group_name . "</option>\n";
		}
	}
	$user_group_select .= "</select>";
	return $user_group_select;
}


function get_asset_type_template_select($company_id, $selected_id){
	$asset_type_template_select = "<select class = \"\" name = \"asset_type_template_id\" id = \"asset_type_template_select\"><option value = \"\">Please Select</option>\n";
	$arr_asset_type_templates = get_asset_type_templates($company_id, 1);
	if (!empty($arr_asset_type_templates)){
		foreach ($arr_asset_type_templates as $asset_type_template_row){
				$selected = "";
				$asset_type_template_id = $asset_type_template_row["asset_type_template_id"];
				$asset_type_template_name = $asset_type_template_row["asset_type_template_name"];

				if ((string)$selected_id == (string)$asset_type_template_id){$selected = "selected";}
				
				$asset_type_template_select .= "<option " . $selected . " value = \"" . $asset_type_template_id . "\">" . $asset_type_template_name . "</option>\n";
		}
	}
	$asset_type_template_select .= "</select>";
	return $asset_type_template_select;
}

function get_asset_type_category_select($company_id, $selected_id){
	$asset_type_category_select = "<select class = \"\" name = \"asset_type_category_id\"><option value = \"\">Please Select</option>\n";
	$arr_asset_type_category = get_asset_type_categories($company_id, 1);
	if (!empty($arr_asset_type_category)){
		foreach ($arr_asset_type_category as $asset_type_category_row){
				$selected = "";
				$asset_type_category_id = $asset_type_category_row["asset_type_category_id"];
				$asset_type_category_name = $asset_type_category_row["asset_type_category_name"];

				if ((string)$selected_id == (string)$asset_type_category_id){$selected = "selected";}
				
				$asset_type_category_select .= "<option " . $selected . " value = \"" . $asset_type_category_id . "\">" . $asset_type_category_name . "</option>\n";
		}
	}
	$asset_type_category_select .= "</select>";
	return $asset_type_category_select;
}

function get_add_attribute_select($company_id, $asset_type_template_id){
	$asset_attribute_select = "<select class = \"\" name = \"asset_attribute_id\" id = \"asset_attribute_select\"><option value = \"\">Add Field</option>\n";
	$arr_asset_attributes = get_unused_asset_attributes($company_id, $asset_type_template_id);
	if (!empty($arr_asset_attributes)){
		foreach ($arr_asset_attributes as $asset_attribute_row){
				$selected = "";
				$asset_attribute_id = $asset_attribute_row["asset_attribute_id"];
				$asset_attribute_name = $asset_attribute_row["asset_attribute_name"];

				//if ((string)$selected_id == (string)$asset_attribute_id){$selected = "selected";}
				
				$asset_attribute_select .= "<option " . $selected . " value = \"" . $asset_attribute_id . "\">" . $asset_attribute_name . "</option>\n";
		}
	}
	$asset_attribute_select .= "</select>";
	return $asset_attribute_select;
}

function get_print_color_select($company_id, $selected_id){
	$print_color_select = "<select class = \"\" name = \"print_color_id\" id = \"print_color_select\"><option value = \"\">Please Select</option>\n";
	$arr_print_colors = get_print_colors($company_id, 1);
	if (!empty($arr_print_colors)){
		foreach ($arr_print_colors as $print_color_row){
				$selected = "";
				$print_color_id = $print_color_row["print_color_id"];
				$print_color_name = $print_color_row["print_color_name"];

				if ((string)$selected_id == (string)$print_color_id){$selected = "selected";}
				
				$print_color_select .= "<option " . $selected . " value = \"" . $print_color_id . "\">" . $print_color_name . "</option>\n";
		}
	}
	$print_color_select .= "</select>";
	return $print_color_select;
}

function convertMySQLdate_to_PHP($MySQLdate){
	$phpdate = strtotime( $MySQLdate );
	return $phpdate;
}

function convertPHPdate_to_MySQL ($PHPdate){
	$mysqldate = date( 'Y-m-d H:i:s', $PHPdate );
	return $mysqldate;
}

function convert_datepicker_date($date){
    $new_date = date('Y-m-d', strtotime($date));
    return $new_date;
}

function convert_mysql_to_datepicker($date){
	$new_date = date('m/d/Y', strtotime($date));
	return $new_date;
}

function convertMySQLdate_to_PHP_month_year($date){
	$new_date = date('m-Y', strtotime($date));
	return $new_date;
}

function convertMySQLdatetime_to_PHP($date){
	$phpdate = strtotime( $date );
	$mysqldate = date( 'm/d/Y H:i a', $phpdate );
	return $mysqldate;
}

function get_quarter_select($quarter){
	$quarter_select = "<select class = \"required\" name = \"campaign_quarter\"><option value = \"\">All</option>\n";
	if (strval($quarter) == "Q1"){
		$quarter_select .= "<option selected value = \"Q1\">Q1</option>\n";
	}else{
		$quarter_select .= "<option value = \"Q1\">Q1</option>\n";
	}
	if (strval($quarter) == "Q2"){
		$quarter_select .= "<option selected value = \"Q2\">Q2</option>\n";
	}else{
		$quarter_select .= "<option value = \"Q2\">Q2</option>\n";
	}
	if (strval($quarter) == "Q3"){
		$quarter_select .= "<option selected value = \"Q3\">Q3</option>\n";
	}else{
		$quarter_select .= "<option value = \"Q3\">Q3</option>\n";
	}
	if (strval($quarter) == "Q4"){
		$quarter_select .= "<option selected value = \"Q4\">Q4</option>\n";
	}else{
		$quarter_select .= "<option value = \"Q4\">Q4</option>\n";
	}
	
	$quarter_select .= "</select>\n";
	return $quarter_select;

}


function get_quarter_select_style1($quarter){
	$quarter_select = "<select class = \"select_style1\" name = \"campaign_quarter\" onchange=\"this.form.submit()\"><option value = \"\">All Quarters</option>\n";
	if (strval($quarter) == "Q1"){
		$quarter_select .= "<option selected value = \"Q1\">Q1</option>\n";
	}else{
		$quarter_select .= "<option value = \"Q1\">Q1</option>\n";
	}
	if (strval($quarter) == "Q2"){
		$quarter_select .= "<option selected value = \"Q2\">Q2</option>\n";
	}else{
		$quarter_select .= "<option value = \"Q2\">Q2</option>\n";
	}
	if (strval($quarter) == "Q3"){
		$quarter_select .= "<option selected value = \"Q3\">Q3</option>\n";
	}else{
		$quarter_select .= "<option value = \"Q3\">Q3</option>\n";
	}
	if (strval($quarter) == "Q4"){
		$quarter_select .= "<option selected value = \"Q4\">Q4</option>\n";
	}else{
		$quarter_select .= "<option value = \"Q4\">Q4</option>\n";
	}
	
	$quarter_select .= "</select>\n";
	return $quarter_select;

}

function get_year_select($selected_year){
	$current_year = date("Y");
	$year_select = "<select class = \"required\" name = \"campaign_year\"><option value = \"\">All</option>";
	$incrementing_year = $current_year - 2;
	
	for ($i = 0; $i < 5; $i++){
		$selected = "";
		if (intval($incrementing_year) == intval($selected_year)){
			$selected = "selected";
		}
		//print $incrementing_year;
		$year_select .= "<option " . $selected . " value = \"" . $incrementing_year . "\">" . $incrementing_year . "</option>\n";
		$incrementing_year = $incrementing_year + 1;
	}
	$year_select .= "</select>";
	
	return $year_select;
}


function get_year_select_no_all($selected_year){
	$current_year = date("Y");
	$year_select = "<select class = \"required\" name = \"year\">";
	$incrementing_year = $current_year - 1;
	
	for ($i = 0; $i < 5; $i++){
		$selected = "";
		if (intval($incrementing_year) == intval($selected_year)){
			$selected = "selected";
		}
		//print $incrementing_year;
		$year_select .= "<option " . $selected . " value = \"" . $incrementing_year . "\">" . $incrementing_year . "</option>\n";
		$incrementing_year = $incrementing_year + 1;
	}
	$year_select .= "</select>";
	
	return $year_select;
}

function get_year_select_spend($selected_year){
	$current_year = date("Y");
	$year_select = "<select class = \"required\" name = \"spend_year\">";
	$incrementing_year = $current_year - 2;
	
	for ($i = 0; $i < 5; $i++){
		$selected = "";
		if (intval($incrementing_year) == intval($selected_year)){
			$selected = "selected";
		}
		//print $incrementing_year;
		$year_select .= "<option " . $selected . " value = \"" . $incrementing_year . "\">" . $incrementing_year . "</option>\n";
		$incrementing_year = $incrementing_year + 1;
	}
	$year_select .= "</select>";
	
	return $year_select;
}

function get_year_select_style1($selected_year){
	$current_year = date("Y");
	$year_select = "<select class = \"select_style1\" name = \"campaign_year\" onchange=\"this.form.submit()\"><option value = \"\">All Years</option>";
	$incrementing_year = $current_year - 2;
	
	for ($i = 0; $i < 5; $i++){
		$selected = "";
		if (intval($incrementing_year) == intval($selected_year)){
			$selected = "selected";
		}
		//print $incrementing_year;
		$year_select .= "<option " . $selected . " value = \"" . $incrementing_year . "\">" . $incrementing_year . "</option>\n";
		$incrementing_year = $incrementing_year + 1;
	}
	$year_select .= "</select>";
	
	return $year_select;
}

function get_month_select($current_month){
	$jan_select = "";
	$feb_select = "";
	$mar_select = "";
	$apr_select = "";
	$may_select = "";
	$jun_select = "";
	$jul_select = "";
	$aug_select = "";
	$sep_select = "";
	$oct_select = "";
	$nov_select = "";
	$dec_select = "";
	if($current_month == "1"){$jan_select = "selected";}
	if($current_month == "2"){$feb_select = "selected";}
	if($current_month == "3"){$mar_select = "selected";}
	if($current_month == "4"){$apr_select = "selected";}
	if($current_month == "5"){$may_select = "selected";}
	if($current_month == "6"){$jun_select = "selected";}
	if($current_month == "7"){$jul_select = "selected";}
	if($current_month == "8"){$aug_select = "selected";}
	if($current_month == "9"){$sep_select = "selected";}
	if($current_month == "10"){$oct_select = "selected";}
	if($current_month == "11"){$nov_select = "selected";}
	if($current_month == "12"){$dec_select = "selected";}
	
	$month_select = "<select name = \"month\">";
	$month_select .= "<option " . $jan_select . " value = \"1\">Jan</option>\n";
	$month_select .= "<option " . $feb_select . " value = \"2\">Feb</option>\n";
	$month_select .= "<option " . $mar_select . " value = \"3\">Mar</option>\n";
	$month_select .= "<option " . $apr_select . " value = \"4\">Apr</option>\n";
	$month_select .= "<option " . $may_select . " value = \"5\">May</option>\n";
	$month_select .= "<option " . $jun_select . " value = \"6\">Jun</option>\n";
	$month_select .= "<option " . $jul_select . " value = \"7\">Jul</option>\n";
	$month_select .= "<option " . $aug_select . " value = \"8\">Aug</option>\n";
	$month_select .= "<option " . $sep_select . " value = \"9\">Sep</option>\n";
	$month_select .= "<option " . $oct_select . " value = \"10\">Oct</option>\n";
	$month_select .= "<option " . $nov_select . " value = \"11\">Nov</option>\n";
	$month_select .= "<option " . $dec_select . " value = \"12\">Dec</option>\n";
	$month_select .= "</select>";
	return $month_select;
}

function translate_mysql_todatepicker($date){
	$arr_date = explode("-",$date);
	$year = $arr_date[0];
	$month = $arr_date[1];
	$day = $arr_date[2];

	$new_date = $month . "/" . $day . "/" . $year;
	return $new_date;
}

function get_percentage_select($field_name, $selected_id){
	$percentage_select = "<select name = \"" . $field_name . "\">";
	$i = 0;
	while ($i <= 100){
		$selected = "";
		if ($selected_id == $i){$selected = " selected ";}
		$percentage_select .= "<option " . $selected . " value = \"" . $i . "\">" . $i . "</option>\n";
		$i = $i + 10;
	}
	$percentage_select .= "</select>";
	return $percentage_select;
}

function get_daily_percentage($num_days, $hours, $minutes){

	$total_minutes = ($hours * 60) + $minutes;
	$minutes_per_day = $total_minutes/$num_days;
	$daily_hours = round(($minutes_per_day/60),2);
	return $daily_hours;

}

function get_total_days_minus_weekends($start_date, $end_date){
	$start = new DateTime($start_date);
	$end = new DateTime($end_date);
	// otherwise the  end date is excluded (bug?)
	$end->modify('+1 day');

	$interval = $end->diff($start);

	// total days
	$days = $interval->days;

	// create an iterateable period of date (P1D equates to 1 day)
	$period = new DatePeriod($start, new DateInterval('P1D'), $end);

	// best stored as array, so you can add more than one
	//$holidays = array('2012-09-07');

	foreach($period as $dt) {
		$curr = $dt->format('D');

		// for the updated question
		//if (in_array($dt->format('Y-m-d'), $holidays)) {
		 //  $days--;
		//}

		// substract if Saturday or Sunday
		if ($curr == 'Sat' || $curr == 'Sun') {
			$days--;
		}
	}
	return $days; // 4
}

function get_date_no_weekends($start_date, $day_count){
	$d = new DateTime( $start_date );
    $t = $d->getTimestamp();
	$orig_day_count = $day_count;
	$day_count = abs($day_count);
	//print $day_count;
    // loop for X days
    for($i=0; $i<$day_count; $i++){
		if($orig_day_count  < 0){
			 // subtrack 1 day to timestamp if it's negative
			$addDay = -86400;
		}else{
			 // add 1 day to timestamp
			$addDay = 86400;
		}
       
        // get what day it is next day
        $nextDay = date('w', ($t+$addDay));

        // if it's Saturday or Sunday get $i-1
        if($nextDay == 0 || $nextDay == 6) {
            $i--;
        }

        // modify timestamp, add 1 day
        $t = $t+$addDay;
    }

    $d->setTimestamp($t);

    return $d->format( 'Y-m-d' );
}

function get_assignee_initials($schedule_task_id){
	$assignee_list = "";
	$arr_assignees = get_assignees_by_stid($schedule_task_id);
	//print_r($arr_assignees);
	if (!empty($arr_assignees)){
		foreach ($arr_assignees as $assignee_row){
			$initials = $assignee_row["initials"];
			$assignee_list .= $initials . ", ";
		}
		$assignee_list = substr($assignee_list, 0, -2);
	}else{
		$assignee_list = "Nobody Assigned.";
	}
	return $assignee_list;
}


function get_assignee_initials_for_popup($schedule_task_id, $project_name, $task_name, $schedule_name){
	$assignee_list = "";
	$arr_assignees = get_assignees_by_stid($schedule_task_id);
	//print_r($arr_assignees);
	if (!empty($arr_assignees)){
		foreach ($arr_assignees as $assignee_row){
			$user_id = $assignee_row["user_id"];
			$initials = $assignee_row["initials"];
			$user_first_name = $assignee_row["first_name"];
			$user_last_name = $assignee_row["last_name"];
			$user_name = $user_first_name . " " . $user_last_name;
			$popup_string = "<a href=\"#\" onclick=\"openpopup('popup1','" . $schedule_task_id . "','" . $user_id . "','" . $project_name . "','" . $task_name . "','" . $schedule_name . "','" . $user_name . "')\">" . $initials . "</a>";
			$assignee_list .= $popup_string . ", ";
		}
		$assignee_list = substr($assignee_list, 0, -2);
	}else{
		$assignee_list = "Nobody Assigned.";
	}
	return $assignee_list;
}

function get_assignee_form($project_id, $schedule_task_id, $schedule_id){
	$arr_project_users = get_users_by_project($project_id);
	//print_r($arr_project_users) . "<br><br>";
	$arr_task_users = get_assignee_ids_by_stid($schedule_task_id);
	//print_r($arr_task_users) . "<br><br>";
	$v=1;
	$user_table = "<form action = \"update_schedule_task_user.php\" method = \"POST\"><table class = \"budget\"><tr><th colspan = \"2\">Add Users</th></tr>";
	if (!empty($arr_project_users)){
		foreach ($arr_project_users as $user_row){
			$checked = "";

			$initials = $user_row["initials"];
			$first_name = $user_row["first_name"];
			$last_name = $user_row["last_name"];
			$user_id = $user_row["user_id"];
			if (in_array($user_id, $arr_task_users)){
				$checked = "checked";
			}
			$user_table .= "<tr><td><input type = \"checkbox\" " . $checked . " name = \"" . $v . "-user_id\" value = \"" . $user_id . "\"></td><td>" . $first_name . " " . $last_name . " (" . $initials . ")</td></tr>";
			$v++;
		}
		$user_table .= "<tr><td colspan = \"2\" align=\"center\"><input type = \"hidden\" name = \"schedule_task_id\" value = \"" .$schedule_task_id . "\"><input type = \"hidden\" name = \"schedule_id\" value = \"" .$schedule_id . "\"><input type = \"submit\" value = \"Assign\"></td></tr>";
	}else{
		$user_table .= "No users for this project.";
	}
	$user_table .= "</table></form>";
	//print_r($arr_project_users) . "<br><br>";
	return $user_table;
}

function get_assignee_form_radio($project_id, $schedule_task_id, $schedule_id){
	$arr_project_users = get_users_by_project($project_id);
	//print_r($arr_project_users) . "<br><br>";
	$arr_task_users = get_assignee_ids_by_stid($schedule_task_id);
	//print_r($arr_task_users) . "<br><br>";
	$v=1;
	$user_table = "<form action = \"update_schedule_task_user.php\" method = \"POST\"><table class = \"budget\"><tr><th colspan = \"2\">Add Users</th></tr>";
	if (!empty($arr_project_users)){
		foreach ($arr_project_users as $user_row){
			$checked = "";

			$initials = $user_row["initials"];
			$first_name = $user_row["first_name"];
			$last_name = $user_row["last_name"];
			$user_id = $user_row["user_id"];
			if (in_array($user_id, $arr_task_users)){
				$checked = "checked";
			}
			$user_table .= "<tr><td><input type = \"radio\" " . $checked . " name = \"user_id\" value = \"" . $user_id . "\"></td><td>" . $first_name . " " . $last_name . " (" . $initials . ")</td></tr>";
			$v++;
		}
		$user_table .= "<tr><td colspan = \"2\" align=\"center\"><input type = \"hidden\" name = \"schedule_task_id\" value = \"" .$schedule_task_id . "\"><input type = \"hidden\" name = \"schedule_id\" value = \"" .$schedule_id . "\"><input type = \"hidden\" name = \"approval\" value = \"1\"><input type = \"submit\" value = \"Assign\"></td></tr>";
	}else{
		$user_table .= "No users for this project.";
	}
	$user_table .= "</table></form>";
	//print_r($arr_project_users) . "<br><br>";
	return $user_table;
}


function get_date_array($start_date, $end_date){
	$total_days = get_total_days_minus_weekends($start_date, $end_date);

	$add_days = 1;
	$arr_weekends = array("Saturday", "Sunday");
	//$start_date = date('Y-m-d',strtotime($start_date) + (24*3600*$add_days));
	//$start_date = date('m/d/Y',strtotime($start_date) + (24*3600*$add_days));
	//$end_date = date('Y-m-d',strtotime($end_date));
	$end_date = date('m/d/Y',strtotime($end_date));
	$arr_days = array();
	//$arr_days[0] = $start_date;
	$current_date = $start_date;
	$i=1;
	//while ($current_date <= $end_date) {
	//while ($i <= $total_days) {
	//have to convert to time in order to compare
	$foo = 50;
	while (strtotime($current_date) <= strtotime($end_date)) {
		//print "<br><br>Current Date:" . $current_date;
		//print "<br>End Date:" . $end_date;
		//exit;
		$weekday =  date('l', strtotime( $current_date));
		//print "<br>Weekday:" . $weekday;
		if (!in_array($weekday, $arr_weekends)){
			array_push($arr_days, $current_date);
		}
		//print $current_date . "-" . $weekday . "<br>";
		//$current_date_strtotime = strtotime($current_date);
		$current_date = new DateTime($current_date);
		$current_date->add(new DateInterval('P1D'));
		
		$current_date = $current_date->format('m/d/Y');

		if ($i > 60){
			break;
		}
		$i++;  

	}
	
	return $arr_days;
}

function array2csv(array &$array)
{
   if (count($array) == 0) {
     return null;
   }
   ob_start();
   $df = fopen("php://output", 'w');
   fputcsv($df, array_keys(reset($array)));
   foreach ($array as $row) {
      fputcsv($df, $row);
   }
   fclose($df);
   return ob_get_clean();
}


function array2csv2(array &$array)
{
   if (count($array) == 0) {
     return null;
   }
   ob_start();
   $df = fopen("php://output", 'w');
   //fputcsv($df, array_keys(reset($array)));
   foreach ($array as $row) {
      fputcsv($df, $row);
   }
   fclose($df);
   return ob_get_clean();
}


function download_send_headers($filename) {
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}

function smtpmailer($to, $subject, $body, $altbody) {
	require_once("phpmail/class.phpmailer.php");
	global $error;
	$mail = new PHPMailer();  // create a new object
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
	//$mail->SMTPAuth = true;  // authentication enabled
	//$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
	$mail->Host = 'mailrelay.apollogrp.edu';
	$mail->Port = 25; 
	//$mail->Username = "loomis95650@gmail.com";  
	//$mail->Username = "uopx.pm@gmail.com"; 
	//$mail->Password = "admin199";           
	$mail->SetFrom("mktgintake@apollogrp.edu", "Marketing Intake");
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AltBody = $altbody;
	$mail->AddAddress($to);
	$mail->IsHTML(true);
	if(!$mail->Send()) {
		$error = 'Mail error: '.$mail->ErrorInfo; 
		//print $error;
		return 0;
	} else {
		$error = 'Message sent!';
		return 1;
	}
}


function smtpmailer_with_cc($to, $subject, $body, $altbody, $cc) { 
	require_once("phpmail/class.phpmailer.php");
	global $error;
	$mail = new PHPMailer();  // create a new object
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
	//$mail->SMTPAuth = true;  // authentication enabled
	//$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
	$mail->Host = 'mailrelay.apollogrp.edu';
	$mail->Port = 25; 
	//$mail->Username = "loomis95650@gmail.com";  
	//$mail->Username = "uopx.pm@gmail.com"; 
	//$mail->Password = "admin199";           
	$mail->SetFrom("mktgintake@apollogrp.edu", "Marketing Intake");
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AltBody = $altbody;
	$mail->AddAddress($to);
	$mail->AddCC($cc);
	$mail->AddBCC("brian.haight@apollo.edu");
	$mail->IsHTML(true);
	if(!$mail->Send()) {
		$error = 'Mail error: '.$mail->ErrorInfo; 
		//print $error;
		return 0;
	} else {
		$error = 'Message sent!';
		return 1;
	}
}

function send_next_task_email($schedule_task_id){
	//get all assignees (recipients) for this schedule_task
	$arr_project = get_project_info_by_schedule_task($schedule_task_id);
	$project_id = $arr_project[0]["project_id"];
	$project_name = $arr_project[0]["project_name"];
	$project_code = $arr_project[0]["project_code"];
	$project_manager_name = $arr_project[0]["pm_fname"] . " " . $arr_project[0]["pm_lname"];
	$schedule_id = $arr_project[0]["schedule_id"];
	$schedule_name = $arr_project[0]["schedule_name"];
	$task_name = $arr_project[0]["task_name"];
	$approved_by = $arr_project[0]["approved_by"];
	$approval_date = $arr_project[0]["approval_date"];
	$approver_name = $arr_project[0]["a_fname"] . " " . $arr_project[0]["a_lname"];
	$body = "";
	$arr_assignee_list = get_assignees_by_stid($schedule_task_id);
	if (!empty($arr_assignee_list)){
		foreach ($arr_assignee_list as $user_row){
			$checked = "";
			$initials = $user_row["initials"];
			$user_full_name = $user_row["first_name"] . " " . $user_row["last_name"];
			$user_email = $user_row["email"];
			$recipient_user_id = $user_row["user_id"];
			$altbody .= "Project: " . $project_name . "(" . $project_code . ")\n";
			$altbody .= "Task: " . $task_name . "\n";
			$altbody .= "Project Manager: " . $project_manager_name . "\n\n";
			$altbody .= "Hi " . $user_full_name . ",\n\n";
			$altbody .= "You're up. Please complete " . $task_name . " for project " . $project_code . ":\n\n";
			$altbody .= "Project Info: http://ac-00019162.apollogrp.edu/pm/manage_project.php?p=" . $project_id . "\n\n";
			$altbody .= "When you are finished, please click this link to complete the task:\n";
			$altbody .= " http://ac-00019162.apollogrp.edu/pm/fast_track_complete.php?stid=" . $schedule_task_id. "\n\n";
			$altbody .= "Thank you,\n\n";
			$altbody .= $project_manager_name;
			
			
			$body .= "<font style=\"font-family:Arial, Helvetica, sans-serif;line-height:18px; font-size:13px; color:#333333;text-align:left\">";
			$body .= "<b>Project: <a href = \"http://ac-00019162.apollogrp.edu/pm/manage_project.php?p=" . $project_id . "\">" . $project_name . "(" . $project_code . ")</a><br>";
			$body .= "Task: " . $task_name . "<br>";
			$body .= "Project Manager: " . $project_manager_name . "</b><br><br>";
			$body .= "Hi " . $user_full_name . ",<br><br>";
			$body .= "You're up. Please complete " . $task_name . " for project " . $project_code . ":<br><br>";
			$body .= "When you are finished, please <br><a href = \"http://ac-00019162.apollogrp.edu/pm/fast_track_complete.php?stid=" . $schedule_task_id . "\">CLICK HERE TO COMPLETE THE TASK</a><br><br>";
			$body .= "Thank you,<br><br>";
			$body .= $project_manager_name;
			$body .= "</font>";

			$to = $user_email;
			//print $to;
			$subject = "Project " . $project_code . " - Your turn";
			$send_success = smtpmailer($to, $subject, $body, $altbody);
			if ($send_success == 1){
				$send_success = "Success";
			}else{
				$send_success = "Fail";
			}
			$email_send = insert_email_send("Fast Track", $project_id, $schedule_task_id, $recipient_user_id, $send_success);
		}
	}
}

function send_pm_schedule_complete_email($schedule_id){
	$arr_schedule_info = get_schedule_info($schedule_id);
	$project_id = $arr_schedule_info[0]["project_id"];
	$project_name = $arr_schedule_info[0]["project_name"];
	$project_code = $arr_schedule_info[0]["project_code"];
	$schedule_name = $arr_schedule_info[0]["schedule_name"];
	$project_manager_name = $arr_schedule_info[0]["pm_fname"] . " " . $arr_schedule_info[0]["pm_lname"];
	$project_manager_email = $arr_schedule_info[0]["pm_email"];
	$project_manager_user_id = $arr_schedule_info[0]["pm_user_id"];
	$altbody  = "Hi " . $project_manager_name . ",\n\n";
	$altbody .= "Schedule " . $schedule_name . " is complete for project " . $project_name . "(" . $project_code . ").\n\n";
	$altbody .= "Project/schedule info: http://ac-00019162.apollogrp.edu/pm/manage_project.php?p=" . $project_id . "&show_schedules=1#showschedules\n\n";
	$body  = "<font style=\"font-family:Arial, Helvetica, sans-serif;line-height:18px; font-size:13px; color:#333333;text-align:left\">";
	$body .= "Hi " . $project_manager_name . ",<br><br>";
	$body .= "Schedule " . $schedule_name . " is complete for project " . $project_name . "(" . $project_code . ").<br><br>";
	$body .= "<a href = \"http://ac-00019162.apollogrp.edu/pm/manage_project.php?p=" . $project_id . "&show_schedules=1#showschedules\">Click here for project schedule info</a>.<br><br>";
	$body .= "</font>";
	$to = $project_manager_email;
	//print $to;
	$subject = "Fast Track Schedule Complete:  " . $schedule_name . " (" . $project_code . ")";
	$send_success = smtpmailer($to, $subject, $body, $altbody);
	if ($send_success == 1){
		$send_success = "Success";
	}else{
		$send_success = "Fail";
	}
	$email_send = insert_email_send("Fast Track Complete", $project_id, 0, $project_manager_user_id, $send_success);
	
	return $send_success;
}


function send_on_hold_email_with_link($pif_id, $pif_code, $approver_notes, $email, $full_name, $approver_id, $project_name, $requester_email){
	//get all assignees (recipients) for this schedule_task
	$altbody= "";
	$body = "<font style=\"font-family:Arial, Helvetica, sans-serif;line-height:18px; font-size:13px; color:#333333;text-align:left\">";

	$body .= "Your requested project " . $project_name . " (" . $pif_code . ") has been set to \"On-Hold\".<br><br>";
	$body .= "Approver Notes: <br>" . $approver_notes . "<br><br>";
	$body .= "<b>Please: <a href = \"http://ac-00019162.apollogrp.edu/pm/edit_pif.php?pid=" . $pif_id . "\">CLICK HERE</a> to edit and re-submit your PIF<br><br>";
	$body .= "Thank you.";
	$body .= "</font>";

	$to = $email;
	//print $to;
	$subject = $project_name . " (" . $pif_code . ") - Set to ON HOLD";
	
	//if it gets this far, we want to send to the requester.
	$send_success = smtpmailer_with_cc($to, $subject, $body, $altbody, $requester_email);

	if ($send_success == 1){
		$send_success = "sent successfully.";
	}else{
		$send_success = "send failed.";
	}
	$enter_log_success = insert_pif_log($pif_id, "Email Sent to " . $full_name . " after Status was set to ON HOLD. Email " . $send_success, "", $approver_id);

}

function send_on_hold_email_no_link($pif_id, $pif_code, $approver_notes, $email, $full_name, $approver_id, $project_name, $requester_email){
	//get all assignees (recipients) for this schedule_task
	$altbody= "";
	$body = "<font style=\"font-family:Arial, Helvetica, sans-serif;line-height:18px; font-size:13px; color:#333333;text-align:left\">";

	$body .= "Your requested project " . $project_name . " (" . $pif_code . ") has been set to \"On-Hold\".<br><br>";
	$body .= "Approver Notes: <br>" . $approver_notes . "<br><br>";
	$body .= "<b>Please: discuss any updates with your project requester.<br><br>";
	$body .= "Thank you.";
	$body .= "</font>";

	$to = $email;
	//print $to;
	$subject = $project_name . " (" . $pif_code . ") - Set to ON HOLD";
	
	//if it gets this far, we want to send to the requester.
	$send_success = smtpmailer_with_cc($to, $subject, $body, $altbody, $requester_email);

	if ($send_success == 1){
		$send_success = "sent successfully.";
	}else{
		$send_success = "send failed.";
	}
	$enter_log_success = insert_pif_log($pif_id, "Email Sent to " . $full_name . " after Status was set to ON HOLD. Email " . $send_success, "", $approver_id);
}


function send_approved_email_to_requester($pif_id, $pif_code, $approver_notes, $requester_email, $requester_full_name, $approver_id, $bm_email, $pm_name, $project_name, $requester_email){
	//get all assignees (recipients) for this schedule_task
	$altbody= "";
	$body = "<font style=\"font-family:Arial, Helvetica, sans-serif;line-height:18px; font-size:13px; color:#333333;text-align:left\">";

	$body .= "Your requested project " . $project_name . " (" . $pif_code . ") has been APPROVED.<br><br>";
	$body .= "Approver Notes: <br>" . $approver_notes . "<br><br>";
	$body .= "<b>Your project manager " . $pm_name . " will contact you shortly.";
	$body .= "Thank you.";
	$body .= "</font>";

	$to = $requester_email;
	//print $to;
	$subject = $project_name . " (" . $pif_code . ") - APPROVED";
	if(empty($bm_email)){
		$send_success = smtpmailer_with_cc($to, $subject, $body, $altbody, $requester_email);
	}else{
		$send_success = smtpmailer_with_cc($to, $subject, $body, $altbody, $bm_email, $requester_email);
	}
	if ($send_success == 1){
		$send_success = "sent successfully.";
	}else{
		$send_success = "send failed.";
	}
	$enter_log_success = insert_pif_log($pif_id, "Email Sent to " . $requester_full_name . " after Status was set to APPROVED. Email " . $send_success, "", $approver_id);
}


function send_approved_email_to_pm($pif_id, $pif_code, $approver_notes, $pm_email, $requester_full_name, $approver_id, $pm_name, $project_code, $project_id, $project_name){
	//get all assignees (recipients) for this schedule_task
	$altbody= "";
	$body = "<font style=\"font-family:Arial, Helvetica, sans-serif;line-height:18px; font-size:13px; color:#333333;text-align:left\">";

	$body .= $project_name . " (" . $pif_code . ") requested by " . $requester_full_name . " has been APPROVED<br><br>";
	$body .= "Your new project " . $project_code . " has been assigned to you.<br><br>";
	$body .= "<b>Please: <a href = \"http://ac-00019162.apollogrp.edu/pm/manage_project.php?p=" . $project_id . "\">CLICK HERE</a> to manage your project.<br><br>";
	$body .= "Thank you.";
	$body .= "</font>";

	$to = $pm_email;
	//print $to;
	$subject = "New Project: " . $project_name . " (" . $project_code . ")";

	$send_success = smtpmailer($to, $subject, $body, $altbody);

	if ($send_success == 1){
		$send_success = "sent successfully.";
	}else{
		$send_success = "send failed.";
	}
	$enter_log_success = insert_pif_log($pif_id, "Email Sent to IPM " . $pm_name . " after Status was set to APPROVED. Email " . $send_success, "", $approver_id);
}

function send_other_pif_status_email($pif_id, $pif_code, $approver_notes, $requester_email, $requester_name, $approver_id, $bm_email, $status_name, $project_name, $requester_email){
	//get all assignees (recipients) for this schedule_task
	$altbody= "";
	$body = "<font style=\"font-family:Arial, Helvetica, sans-serif;line-height:18px; font-size:13px; color:#333333;text-align:left\">";

	$body .= "Your requested project  " .  $project_name . " (" . $pif_code . ") has been set to " . $status_name . ".<br><br>";
	$body .= "Approver Notes: <br>" . $approver_notes . "<br><br>";
	$body .= "Thank you.";
	$body .= "</font>";

	$to = $requester_email;
	//print $to;
	$subject = "Project " .  $project_name . " (" . $pif_code . ") - Set to " . $status_name;
	if(empty($bm_email)){
		$send_success = smtpmailer_with_cc($to, $subject, $body, $altbody, $requester_email);
	}else{
		$send_success = smtpmailer_with_cc($to, $subject, $body, $altbody, $bm_email, $requester_email);
	}
	if ($send_success == 1){
		$send_success = "sent successfully.";
	}else{
		$send_success = "send failed.";
	}
	$enter_log_success = insert_pif_log($pif_id, "Email Sent to " . $requester_name . " after Status was set to " . $status_name . ". Email " . $send_success, "", $approver_id);
}



function add_commas($number){
	$number_with_commas = number_format($number);
	return $number_with_commas;
}

function get_month_abbrev($month_num){
	if($month_num == 1){return "Jan";}
	if($month_num == 2){return "Feb";}
	if($month_num == 3){return "Mar";}
	if($month_num == 4){return "Apr";}
	if($month_num == 5){return "May";}
	if($month_num == 6){return "Jun";}
	if($month_num == 7){return "Jul";}
	if($month_num == 8){return "Aug";}
	if($month_num == 9){return "Sep";}
	if($month_num == 10){return "Oct";}
	if($month_num == 11){return "Nov";}
	if($month_num == 12){return "Dec";}
}

function get_approval_history_table($schedule_task_id, $project_code){
	$arr_approval_history = get_approval_history($schedule_task_id);
	$approval_log_table = "<div id = \"approval_history_stid" . $schedule_task_id . "\" style=\"display: none;\"><table class = \"stats_table\"><tr><th>Date</th><th>Event</th><th>Notes</th><th>File</th></tr>";
	$directory = "project_files/" . $project_code . "/";
	if (!empty($arr_approval_history)){
		
		foreach ($arr_approval_history as $log_row){
			$checked = "";
			$approval_log_id = $log_row["approval_log_id"];
			$approver_id = $log_row["approver_id"];
			$event_date = $log_row["event_date"];
			$event = $log_row["event"];
			$approver_notes = $log_row["approver_notes"];
			$approval_file_id = $log_row["approval_file_id"];
			$approval_file_name = $log_row["project_file_name"];
			$approval_file_type = $log_row["file_type"];
			$approval_log_table .= "<tr><td nowrap valign=\"top\">" . $event_date  . "</td><td valign=\"top\">" . $event . "</td><td valign=\"top\">" . $approver_notes . "</td><td valign=\"top\"><a href = \"" . $directory . $approval_file_name . "\" target = \"_blank\">" . $approval_file_type . " - " . $approval_file_name . "</a></td></tr>";
			
			}
		}
	$approval_log_table .= "</table></div>";
	return $approval_log_table;
}

function get_spend_month_select($company_id, $selected_id){

	$spend_month_select = "<select name = \"spend_month\"><option value = \"\">Please select</option>\n";
	$arr_spend_month = get_spend_months($company_id);

	if (!empty($arr_spend_month)){
		foreach ($arr_spend_month as $spend_month_row){
				$selected = "";
				$spend_month = $spend_month_row["spend_month"];
				$arr_spend_month = explode("-", $spend_month);
				$month = get_month_abbrev($arr_spend_month[1]);
				$year = $arr_spend_month[0];
				$month_year = $month . "-" . $year;
				if ((string)$selected_id == (string)$spend_month){$selected = "selected";}
				$spend_month_select .= "<option " . $selected . " value = \"" . $spend_month . "\">" . $month_year . "</option>\n";
				
		}
	}
	$spend_month_select .= "</select>";
	return $spend_month_select;
}


function get_spend_month_select2($company_id, $selected_id, $select_name){

	$spend_month_select = "<select name = \"" . $select_name . "\"><option value = \"\">Please select</option>\n";
	$arr_spend_month = get_spend_months($company_id);

	if (!empty($arr_spend_month)){
		foreach ($arr_spend_month as $spend_month_row){
				$selected = "";
				$spend_month = $spend_month_row["spend_month"];
				$arr_spend_month = explode("-", $spend_month);
				$month = get_month_abbrev($arr_spend_month[1]);
				$year = $arr_spend_month[0];
				$month_year = $month . "-" . $year;
				if ((string)$selected_id == (string)$spend_month){$selected = "selected";}
				$spend_month_select .= "<option " . $selected . " value = \"" . $spend_month . "\">" . $month_year . "</option>\n";
				
		}
	}
	$spend_month_select .= "</select>";
	return $spend_month_select;
}

function get_date_range_select($company_id, $selected_id, $select_name){
	$selected_3 = "";
	if ($selected_id == 3){$selected_3 = " selected";}
	$selected_5 = "";
	if ($selected_id == 5){$selected_5= " selected";}
	$selected_7 = "";
	if ($selected_id == 7){$selected_7 = " selected";}
	$selected_30 = "";
	if ($selected_id == 30){$selected_30 = " selected";}
	
	$date_range_select  = "<select name = \"" . $select_name . "\" id = \"" . $select_name . "\" onchange=\"this.form.submit();\">\n";
	$date_range_select .= "<option value = \"0\">Today</option>\n";
	$date_range_select .= "<option value = \"3\"" . $selected_3 . ">3 Days</option>\n";
	$date_range_select .= "<option value = \"5\"" . $selected_5 . ">5 Days</option>\n";
	$date_range_select .= "<option value = \"7\"" . $selected_7 . ">7 Days</option>\n";
	$date_range_select .= "<option value = \"30\"" . $selected_30 . ">30 Days</option>\n";
	$date_range_select .= "</select>";
	return $date_range_select;
}

function get_document_select_by_project($project_id){
	$approval_document_select = "No active documents";
	$arr_project_files = get_project_files($project_id);
	if (!empty($arr_project_files)){
		$approval_document_select = "<select name = \"approval_project_file_id\"><option value = \"\">Please select (optional)</option>";
		foreach ($arr_project_files as $file_row){
			$project_file_id = $file_row["project_file_id"];
			$file_name = $file_row["project_file_name"];
			$file_type = $file_row["file_type"];
			$approval_document_select .= "<option value = \"" .  $project_file_id . "\">" . $file_type . " - " . $file_name . "</option>\n"; 
		
		}
		$approval_document_select .= "</select>";
	}
	return $approval_document_select;

}


function get_aop_activity_select($company_id, $selected_id){

	$aop_activity_select = "<select name = \"aop_activity_type_id\" class = \"required\" id = \"aop_activity_type_select\"><option value = \"\">Please select</option>\n";
	$arr_aop_activity = get_aop_activity_type($company_id);

	if (!empty($arr_aop_activity)){
		foreach ($arr_aop_activity as $activity_row){
				$selected = "";
				$aop_activity_type_id = $activity_row["aop_activity_type_id"];
				$aop_activity_type_name = $activity_row["aop_activity_type_name"];

				if ((string)$selected_id == (string)$aop_activity_type_id){$selected = "selected";}
				$aop_activity_select .= "<option " . $selected . " value = \"" . $aop_activity_type_id . "\">" . $aop_activity_type_name . "</option>\n";
				
		}
	}
	$aop_activity_select .= "</select>";
	return $aop_activity_select;

}

function get_percent_increase($spend_id, $prev_month_year, $percent_complete){
	$prev_percent = get_spend_percent_by_month($spend_id, $prev_month_year);
	//print "--%" . $prev_percent;
	//if (empty($prev_percent)){
	//	$prev_percent = 0;
	//}
	if ($prev_percent == "n/a"){
		$spend_increase = "new";
	}else{
		$spend_increase =  $percent_complete - $prev_percent;
	}
	return $spend_increase;
}


function get_pif_status_select($company_id, $selected_id){

	$pif_status_select = "<select name = \"s\" class = \"required\" onchange=\"this.form.submit()\"><option value = \"100\">ALL</option>\n";
	$arr_pif_status = get_pif_status($company_id);

	if (!empty($arr_pif_status)){
		foreach ($arr_pif_status as $pif_status_row){
				$selected = "";
				$pif_approval_status_id = $pif_status_row["pif_approval_status_id"];
				$pif_approval_status_name = $pif_status_row["pif_approval_status_name"];

				if ((string)$selected_id == (string)$pif_approval_status_id){$selected = "selected";}
				$pif_status_select .= "<option " . $selected . " value = \"" . $pif_approval_status_id . "\">" . $pif_approval_status_name . "</option>\n";
				
		}
	}
	$pif_status_select .= "</select>";
	return $pif_status_select;

}


function get_pif_status_select2($company_id, $selected_id){

	$pif_status_select = "<select name = \"s\" class = \"required\" id = \"status1\">\n";
	$arr_pif_status = get_pif_status($company_id);

	if (!empty($arr_pif_status)){
		foreach ($arr_pif_status as $pif_status_row){
				$selected = "";
				$pif_approval_status_id = $pif_status_row["pif_approval_status_id"];
				$pif_approval_status_name = $pif_status_row["pif_approval_status_name"];

				if ((string)$selected_id == (string)$pif_approval_status_id){$selected = "selected";}
				$pif_status_select .= "<option " . $selected . " value = \"" . $pif_approval_status_id . "\">" . $pif_approval_status_name . "</option>\n";
				
		}
	}
	$pif_status_select .= "</select>";
	return $pif_status_select;

}

function get_display_type_select($selected_id){

	$display_type_select = "<select name = \"display_type\" class = \"required\">\n";
	$arr_display_type = array("Text Box","Radio Button","Check Box","Pull-Down Menu","Text Area");

	if (!empty($arr_display_type)){
		foreach ($arr_display_type as $display_type_row){
				$selected = "";
				$display_type_name = $display_type_row;
				//print $display_type_name . "--" . $selected_id . "<br>";
				if ((string)$selected_id == (string)$display_type_name){$selected = "selected";}
				$display_type_select .= "<option " . $selected . " value = \"" . $display_type_name . "\">" . $display_type_name . "</option>\n";
				
		}
	}
	$display_type_select .= "</select>";
	return $display_type_select;

}

function get_asset_attribute_input_field($asset_attribute_id, $include_attribute_name, $asset_item_value){
	$input_field_display = "";
	$arr_asset_attribute_field = get_asset_attribute_and_choices($asset_attribute_id);
	if (!empty($arr_asset_attribute_field)){
		$asset_attribute_name = $arr_asset_attribute_field[0]["asset_attribute_name"];
		$display_type = $arr_asset_attribute_field[0]["display_type"];
		$field_name = "att-" . $asset_attribute_id;
		
		if($include_attribute_name == 1){
			$input_field_display .= $asset_attribute_name . "<br>";
		}
		if ($display_type == "Text Box"){
			$input_field_display .= "<input type = \"text\" name = \"" . $field_name . "\" value = \"" . $asset_item_value . "\">";
		}
		if ($display_type == "Text Area"){
			$input_field_display .= "<textarea name =  \"" . $field_name . "\">" . $asset_item_value . "</textarea>";
		}
		//print $display_type;
		if ($display_type == "Pull-Down Menu"){
			$input_field_display .= "<select name =  \"" . $field_name . "\">";
		}
		
		
		
		if (in_array($display_type, array("Pull-Down Menu","Check Box","Radio Button"))){
			foreach ($arr_asset_attribute_field as $asset_attribute_choice_row){
				$asset_attribute_choice_name = $asset_attribute_choice_row["asset_attribute_choice_name"];
				$checked = "";
				
				if ($display_type == "Pull-Down Menu"){
					if($asset_attribute_choice_name == $asset_item_value){
						$checked = "selected";
					}
					$input_field_display .= "<option value = \"" . $asset_attribute_choice_name . "\" " . $checked . ">" . $asset_attribute_choice_name . "</option>";
				}
				if ($display_type == "Radio Button"){
					if($asset_attribute_choice_name == $asset_item_value){
						$checked = "checked";
					}
				
					$input_field_display .= "<input type = \"radio\" name =  \"" . $field_name . "\" value = \"" . $asset_attribute_choice_name . "\" " . $checked . "> " . $asset_attribute_choice_name . "<br>";
				}
				if ($display_type == "Check Box"){
					if($asset_item_value == 1){
						$checked = "checked";
					}
					$input_field_display .= "<input type = \"checkbox\" name =  \"" . $field_name . "\" value = \"1\" " . $checked . "> " . $asset_attribute_choice_name . "<br>";
				}
				
			}
		}
		
		if ($display_type == "Pull-Down Menu"){
			$input_field_display .= "</select>";
		}
		
	}
	return $input_field_display;
}

function zipFilesDownload($file_names,$archive_file_name){
	
	$zip = new ZipArchive();
	if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
	  exit("cannot open <$archive_file_name>\n");
	}
	
	foreach($file_names as $files){
		$arr_file = explode("/", $files);
		$current_file_name = $arr_file[2];
		//get rid of trailing apostrophe
		$current_file_name = substr($current_file_name, 0, -1);
		$files = str_replace("'","",$files);
		//print $files . "--";
		//print $current_file_name . "<br>";
		if (file_exists($files)){
			//print "Exists!";
			$zip->addFile($files,$current_file_name);
		}
	}
	$zip->close();


	header("Content-type: application/zip"); 
	header("Content-Disposition: attachment; filename=$archive_file_name"); 
	header("Pragma: no-cache"); 
	header("Expires: 0"); 
	readfile("$archive_file_name"); 
	//delete the zip file we just created to save disk space
	unlink($archive_file_name);
exit;

}

function get_generic_radio($radio_choice_array, $radio_name, $selected_value){
	$radio_chioces = "";
	$radio_count = count($radio_choice_array);
	$n = 0;
	foreach ($radio_choice_array as $current_choice){
		$n++;
		$checked = "";
		if ($current_choice == $selected_value){
			$checked = "checked";
		}
		$radio_chioces .= "<input type = \"radio\" name = \"" . $radio_name . "\" value = \"" . $current_choice . "\" " . $checked . "> " . $current_choice;
		if ($n <> $radio_count){
			$radio_chioces .= "<br>";
		}
	}
	return $radio_chioces;
}

function get_generic_pulldown($pulldown_choice_array, $pulldown_name, $selected_value){
	$pulldown_chioces = "<select name = \"" . $pulldown_name . "\"><option value = \"\">Please select</option>\n";
	$pulldown_count = count($pulldown_choice_array);
	$n = 0;
	foreach ($pulldown_choice_array as $current_choice){
		$n++;
		$checked = "";
		if ($current_choice == $selected_value){
			$checked = "selected";
		}
		$pulldown_chioces .= "<option value = \"" . $current_choice . "\"" . $checked . "> " . $current_choice . "</option>\n";
	}
	$pulldown_chioces .= "</select>\n";
	return $pulldown_chioces;
}

function get_generic_checkboxes($checkbox_choice_array, $selected_checkbox_array, $checkbox_prefix){
	$checkbox_chioces = "";
	$checkbox_count = count($checkbox_choice_array);
	$n = 0;
	foreach ($checkbox_choice_array as $current_choice){
		$n++;
		$checked = "";
		if (in_array($current_choice, $selected_checkbox_array)){
			$checked = "checked";
		}
		$checkbox_chioces .= "<input type = \"checkbox\" name = \"" . $checkbox_prefix . "-" . $current_choice . "\" value = \"" . $current_choice . "\" " . $checked . "> " . $current_choice;
		if ($n <> $checkbox_count){
			$checkbox_chioces .= "<br>";
		}
	}
	return $checkbox_chioces;
}


function get_agency_select($company_id, $selected_id){
	$agency_select = "<select class = \"\" name = \"agency_id\" id = \"agency_select\"><option value = \"\">Please Select</option>\n";
	$arr_agencies = get_agencies($company_id, 1);
	if (!empty($arr_agencies)){
		foreach ($arr_agencies as $agency_row){
				$selected = "";
				$agency_id = $agency_row["agency_id"];
				$agency_name = $agency_row["agency_name"];

				if ((string)$selected_id == (string)$agency_id){$selected = "selected";}
				
				$agency_select .= "<option " . $selected . " value = \"" . $agency_id . "\">" . $agency_name . "</option>\n";
		}
	}
	$agency_select .= "</select>";
	return $agency_select;
}

function get_meta_checkbox_list($list_title,$list_array,$arr_image_meta_ids){
	$checkbox_html = "<h3>" . $list_title . "</h3>";
	if (!empty($list_array)){
		foreach ($list_array as $meta_data_row){
			$checked = "";
			if (in_array($meta_data_row["meta_data_id"],$arr_image_meta_ids)){
				$checked = "checked";
			}
			$checkbox_html .= "<input type = \"checkbox\" name = \"MD_" . $meta_data_row["meta_data_id"] . "\" " . $checked . "  value = \"" . $meta_data_row["meta_data_id"] . "\"> " .  $meta_data_row["meta_data_name"] . "<br>\n";
		}
	}
	return $checkbox_html ;
}


function get_model_select_for_image($image_id){
	$model_select = "<select class = \"\" name = \"model_id\" id = \"model_select\"><option value = \"\">Please Select</option>\n";
	$arr_models = get_unused_models_for_image($image_id);
	if (!empty($arr_models)){
		foreach ($arr_models as $model_row){
				$selected = "";
				$model_id = $model_row["model_id"];
				$model_name = $model_row["model_name"];
				
				$model_select .= "<option value = \"" . $model_id . "\">" . $model_name . "</option>\n";
		}
	}
	$model_select .= "</select>";
	return $model_select;
}


function get_wif_type_select($company_id, $selected_id){
	$wif_type_select = "<select class = \"\" name = \"wif_type_id\" id = \"wif_type_select\"><option value = \"\" disabled selected>Please Select</option>\n";
	$arr_wif_types = get_wif_types($company_id, 1);
	if (!empty($arr_wif_types)){
		foreach ($arr_wif_types as $wif_type_row){
				$selected = "";
				$wif_type_id = $wif_type_row["wif_type_id"];
				$wif_type_name = $wif_type_row["wif_type_name"];
				$is_web_request = $wif_type_row["is_web_request"];

				if ((string)$selected_id == (string)$wif_type_id){$selected = "selected";}
				
				$wif_type_select .= "<option " . $selected . " value = \"" . $wif_type_id . "-" . $is_web_request . "\">" . $wif_type_name . "</option>\n";
		}
	}
	$wif_type_select .= "</select>";
	return $wif_type_select;
}

function get_wif_type_select_web_only($company_id, $selected_id){
	$wif_type_select = "<select class = \"\" name = \"wif_type_id\" id = \"wif_type_select\"><option value = \"\">Please Select</option>\n";
	$arr_wif_types = get_wif_types_web_only($company_id, 1);
	if (!empty($arr_wif_types)){
		foreach ($arr_wif_types as $wif_type_row){
				$selected = "";
				$wif_type_id = $wif_type_row["wif_type_id"];
				$wif_type_name = $wif_type_row["wif_type_name"];
				$is_web_request = $wif_type_row["is_web_request"];

				if ((string)$selected_id == (string)$wif_type_id){$selected = "selected";}
				
				$wif_type_select .= "<option " . $selected . " value = \"" . $wif_type_id . "-" . $is_web_request . "\">" . $wif_type_name . "</option>\n";
		}
	}
	$wif_type_select .= "</select>";
	return $wif_type_select;
}

function get_wif_status_select($company_id, $selected_id){
	$wif_status_select = "<select class = \"\" name = \"wif_status_id\" id = \"wif_status_select\"><option value = \"\">Please Select</option>\n";
	$arr_wif_statuses = get_wif_statuses(1);
	if (!empty($arr_wif_statuses)){
		foreach ($arr_wif_statuses as $wif_status_row){
				$selected = "";
				$wif_status_id = $wif_status_row["wif_status_id"];
				$wif_status_name = $wif_status_row["wif_status_name"];

				if ((string)$selected_id == (string)$wif_status_id){$selected = "selected";}
				
				$wif_status_select .= "<option " . $selected . " value = \"" . $wif_status_id . "\">" . $wif_status_name . "</option>\n";
		}
	}
	$wif_status_select .= "</select>";
	return $wif_status_select;
}


function send_wif_status_change_email($wif_id, $wif_code, $requester_email, $wif_name, $wif_status_name){
	//get all assignees (recipients) for this schedule_task
	$altbody= "";
	$body = "<font style=\"font-family:Arial, Helvetica, sans-serif;line-height:18px; font-size:13px; color:#333333;text-align:left\">";

	$body .= "Your requested WIF " . $wif_name . " (" . $wif_code . ") has been set to " . $wif_status_name . "<br><br>";
	$body .= "Thank you.";
	$body .= "</font>";

	$to = $requester_email;
	//print $to;
	$subject = $wif_code . " - Set to " . $wif_status_name;
	
	//send to the requester.
	$send_success = smtpmailer($to, $subject, $body, $altbody);

	if ($send_success == 1){
		$send_success = "sent successfully.";
	}else{
		$send_success = "send failed.";
	}

}


function send_approved_wif_email_to_requester($wif_id, $wif_code, $requester_email, $pm_name, $project_name){
	//get all assignees (recipients) for this schedule_task
	$altbody= "";
	$body = "<font style=\"font-family:Arial, Helvetica, sans-serif;line-height:18px; font-size:13px; color:#333333;text-align:left\">";

	$body .= "Your requested project " . $project_name . " (" . $wif_code . ") has been APPROVED.<br><br>";
	$body .= "<br><br><b>Your project manager " . $pm_name . " will contact you shortly.";
	$body .= "Thank you.";
	$body .= "</font>";

	$to = $requester_email;
	//print $to;
	$subject = $project_name . " (" . $wif_code . ") - APPROVED";

	$send_success = smtpmailer_with_cc($to, $subject, $body, $altbody, $requester_email);

	if ($send_success == 1){
		$send_success = "sent successfully.";
	}else{
		$send_success = "send failed.";
	}
	//$enter_log_success = insert_pif_log($pif_id, "Email Sent to " . $requester_full_name . " after Status was set to APPROVED. Email " . $send_success, "", $approver_id);
}


function send_approved_wif_email_to_pm($wif_id, $wif_code, $pm_email, $requester_full_name, $pm_name, $project_code, $project_id, $project_name){
	//get all assignees (recipients) for this schedule_task
	$altbody= "";
	$body = "<font style=\"font-family:Arial, Helvetica, sans-serif;line-height:18px; font-size:13px; color:#333333;text-align:left\">";

	$body .= $project_name . " (" . $wif_code . ") requested by " . $requester_full_name . " has been APPROVED<br><br>";
	$body .= "Your new project " . $project_code . " has been assigned to you.<br><br>";
	$body .= "<b>Please: <a href = \"http://ac-00019162.apollogrp.edu/pm/manage_project.php?p=" . $project_id . "\">CLICK HERE</a> to manage your project.<br><br>";
	$body .= "Thank you.";
	$body .= "</font>";

	$to = $pm_email;
	//print $to;
	$subject = "New Project: " . $project_name . " (" . $project_code . ")";

	$send_success = smtpmailer($to, $subject, $body, $altbody);

	if ($send_success == 1){
		$send_success = "sent successfully.";
	}else{
		$send_success = "send failed.";
	}
	//$enter_log_success = insert_pif_log($pif_id, "Email Sent to IPM " . $pm_name . " after Status was set to APPROVED. Email " . $send_success, "", $approver_id);
}