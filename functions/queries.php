<?php
include_once "dbconn.php";
/**
 * @param $company_id
 * @param $active_flag
 * @return array
 */
function get_spend_info($spend_id){
	db_connect();	
	$query="select s.* from spend s where spend_id =  " . $spend_id;
	//print $query . "<br>";	
	$arr = get_aa_for_query( $query );	
	return $arr;
}

function get_business_units($company_id, $active_flag){
    $active=($active_flag==1)?1:0;
    $active_string = "and bu.active = :active";
    $link=dbConn();
    $handle=$link->prepare("select bu.*, u.first_name, u.last_name, u.initials from business_unit bu left join user u on bu.business_unit_owner_id = u.user_id
                            where bu.company_id =:companyId " . $active_string . " order by bu.business_unit_name asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":active",$active,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $active_flag
 * @return array
 */
function get_project_codes($company_id, $active_flag){
    $active = "";
    if ($active_flag == 1){
        $active = " and p.active = 1";
    }
    $link=dbConn();
    $handle=$link->prepare("select p.* from project p join campaign c on p.campaign_id = c.campaign_id where c.company_id = :companyId ". $active. " order by project_code asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $active_flag
 * @return array
 */
function get_campaigns($company_id, $active_flag){
    $active = "";
    if ($active_flag == 1){
        $active = " and c.active = 1";
    }
    $link=dbConn();
    $handle=$link->prepare("select c.*, b.business_unit_name, b.business_unit_abbrev, b.default_cost_code, b.business_unit_owner_id from campaign c join business_unit b on c.business_unit_id = b.business_unit_id
                            where c.company_id = :companyId " . $active . " order by c.campaign_year, b.business_unit_name asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $campaign_id
 * @param $business_unit_id
 * @param $quarter
 * @param $year
 * @param $active_flag
 * @return array
 */
function get_campaign_query($company_id, $campaign_id, $business_unit_id, $quarter, $year, $active_flag){
    $active_string = "";
    if ($active_flag == 1){
        $active_string = " and c.active = 1";
    }elseif($active_flag == 2){
        $active_string = " and c.active = 0";
    }elseif($active_flag == 3){
        $active_string = "";
    }
    $campaign_string = "";
    $business_unit_string = "";
    $quarter_string = "";
    $year_string = "";
    if (!empty($campaign_id)){
        $campaign_string = " and c.campaign_id = :campaignId";
    }
    if (!empty($business_unit_id)){
        $business_unit_string = " and c.business_unit_id = :businessUnitId ";
    }
    if (!empty($quarter)){
        $quarter_string = " and c.campaign_quarter = :quarter";
    }
    if (!empty($year)){
        $year_string = " and c.campaign_year = :year";
    }
    $link=dbConn();
    $handle=$link->prepare("select c.*, b.business_unit_name, b.business_unit_abbrev from campaign c join business_unit b on c.business_unit_id = b.business_unit_id
                            where c.company_id = :companyId " . $campaign_string . $business_unit_string . $quarter_string .$year_string . $active_string . "
                            order by campaign_year, campaign_quarter asc;");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    if (!empty($campaign_id)) $handle->bindValue(":campaignId",$campaign_id,PDO::PARAM_INT);
    if (!empty($business_unit_id)) $handle->bindValue(":businessUnitId",$business_unit_id,PDO::PARAM_INT);
    if (!empty($quarter)) $handle->bindValue(":quarter",$quarter);
    if (!empty($year)) $handle->bindValue(":year",$year);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $campaign_id
 * @return array
 */
function get_campaign_info($campaign_id){
    $link=dbConn();
    $handle=$link->prepare("select c.* from campaign c where campaign_id = :campaignID");
    $handle->bindValue(":campaignID",$campaign_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $campaign_id
 * @param $project_manager_id
 * @param $active_flag
 * @return array
 */
function get_projects($company_id, $campaign_id, $project_manager_id, $active_flag){
    $active_string = ($active_flag==1)?" and p.active = 1":"";
    $campaign_string =($campaign_id!=0)?" and p.campaign_id = :campaignId":"";
    $project_manager_string =($project_manager_id!=0)?" and p.project_manager_id = :pmId":"";
    $link=dbConn();
    $handle=$link->prepare("select p.*, c.campaign_code, pr.product_name, a.audience_name, ps.project_status_name, u.first_name, u.last_name from project p
            join campaign c on p.campaign_id = c.campaign_id join product pr on p.product_id = pr.product_id left join audience a on p.audience_id = a.audience_id
            join project_status ps on p.project_status_id = ps.project_status_id join user u on p.project_manager_id = u.user_id
            where c.company_id = :companyId " . $campaign_string . $project_manager_string . $active_string);
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    if ($campaign_id!=0) $handle->bindValue(":campaignId",$campaign_id,PDO::PARAM_INT);
    if ($project_manager_id!=0) $handle->bindValue(":pmId",$project_manager_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $project_id
 * @param $campaign_id
 * @param $product_id
 * @param $audience_id
 * @param $project_status_id
 * @param $project_manager_id
 * @param $active_flag
 * @return array
 */
function get_projects_query($company_id, $project_id, $campaign_id, $product_id, $audience_id, $project_status_id, $project_manager_id, $active_flag){
    $active_string = "";
    if ($active_flag == 1){
        $active_string = " and p.active = 1";
    }elseif($active_flag == 2){
        $active_string = " and p.active = 0";
    }elseif($active_flag == 3){
        $active_string = "";
    }
    $project_string =(!empty($project_id))? " and p.project_id = :projectId":"";
    $campaign_string =(!empty($campaign_id))? " and p.campaign_id = :campaignId":"";
    $product_string =(!empty($product_id))? " and p.product_id = :productId":"";
    $audience_string =(!empty($audience_id))?  " and p.audience_id = :audienceId":"";
    $project_status_string =(!empty($project_status_id))?" and p.project_status_id = :projectStatusString":"";
    $project_manager_string =(!empty($project_manager_id))?" and p.project_manager_id = :pmId":"";
    $link=dbConn();
    $handle=$link->prepare("select p.*, c.campaign_code, pr.product_name, a.audience_name, ps.project_status_name, u.first_name, u.last_name from project p
            join campaign c on p.campaign_id = c.campaign_id join product pr on p.product_id = pr.product_id left join audience a on p.audience_id = a.audience_id
            join project_status ps on p.project_status_id = ps.project_status_id join user u on p.project_manager_id = u.user_id
            where c.company_id = :companyId". $project_string . $campaign_string . $product_string . $audience_string . $project_status_string . $project_manager_string . $active_string);
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    if (!empty($campaign_id)) $handle->bindValue(":campaignId",$campaign_id,PDO::PARAM_INT);
    if (!empty($product_id)) $handle->bindValue(":productId",$product_id,PDO::PARAM_INT);
    if (!empty($audience_id)) $handle->bindValue(":audienceId",$audience_id,PDO::PARAM_INT);
    if (!empty($project_status_id)) $handle->bindValue(":projectStatusString",$project_status_id,PDO::PARAM_INT);
    if (!empty($project_manager_id)) $handle->bindValue(":pmId",$project_manager_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $active_flag
 * @return array
 */
function get_products($company_id, $active_flag){
    $link=dbConn();
    $handle=$link->prepare("select * from product where company_id =:companyId and active = :activeFlag order by product_name asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":activeFlag",$active_flag,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $active_flag
 * @return array
 */
function get_audience($company_id, $active_flag){
    $active_string = "";
    if ($active_flag == 1){
        $active_string = " and active = 1";
    }
    if ($active_flag == 2){
        $active_string = " and active = 0";
    }
    $link=dbConn();
    $handle=$link->prepare("select * from audience where company_id = :companyId" . $active_string . " order by audience_name asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $active_flag
 * @return array
 */
function get_project_managers($company_id, $active_flag){
    $active_string = ($active_flag == 1)?" and active = 1":"";
    $link=dbConn();
    $handle=$link->prepare("select * from user where is_project_manager = 1 and company_id = :companyId" . $active_string . " order by first_name asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $active_flag
 * @return array
 */
function get_vendors($company_id, $active_flag){
    $active_string = "";
    if ($active_flag == 1){
        $active_string = " and active = 1";
    }
    if ($active_flag == 2){
        $active_string = " and active = 0";
    }
    $link=dbConn();
    $handle=$link->prepare("select * from vendor where company_id = :companyId" . $active_string . " order by vendor_name asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $project_id
 * @return array
 */
function get_assets($project_id){
    $link=dbConn();
    $handle=$link->prepare("select a.*, at.asset_type_name from asset a join asset_type at on a.asset_type_id = at.asset_type_id where project_id = :projectId order by asset_id asc");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $active_flag
 * @return array
 */
function get_project_status($company_id, $active_flag){
    $active_string = ($active_flag == 1)?" and active = 1":"";
    $link=dbConn();
    $handle=$link->prepare("select * from project_status where company_id = :companyId". $active_string . " order by display_order asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $active_flag
 * @return array
 */
function get_asset_types($company_id, $active_flag){
    $active_string = "";
    if ($active_flag == 1){
        $active_string = " and at.active = 1";
    }
    if ($active_flag == 2){
        $active_string = " and at.active = 0";
    }
    $link=dbConn();
    $handle=$link->prepare("select at.*, att.*, atc.* from asset_type at left join asset_type_template att on at.asset_type_template_id = att.asset_type_template_id
                            left join asset_type_category atc on at.asset_type_category_id = atc.asset_type_category_id where at.company_id = :companyId " . $active_string . " order by atc.asset_type_category_name, at.asset_type_name");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $campaign_id
 * @return array
 */
function get_business_code_by_campaign($campaign_id){
    $link=dbConn();
    $handle=$link->prepare("select bu.business_unit_abbrev from business_unit bu join campaign c on bu.business_unit_id = c.business_unit_id where campaign_id = :campaignId");
    $handle->bindValue(":campaignId",$campaign_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $project_id
 * @return array
 */
function get_project_info($project_id){
    $link=dbConn();
    $handle=$link->prepare("select p.*, c.campaign_code, c.campaign_description, c.campaign_budget, c.campaign_year, pr.product_name, a.audience_name, ps.project_status_name, u.first_name, u.last_name, bu.business_unit_name, bu.business_unit_abbrev, bu.default_cost_code, u2.first_name as business_unit_owner_first_name, u2.last_name as business_unit_owner_last_name, u2.initials as business_unit_owner_initials, u3.first_name as acd_first_name, u3.last_name as acd_last_name, u3.initials as acd_initials, aat.aop_activity_type_name from project p join campaign c on p.campaign_id = c.campaign_id join product pr on p.product_id = pr.product_id left join audience a on p.audience_id = a.audience_id join project_status ps on p.project_status_id = ps.project_status_id join user u on p.project_manager_id = u.user_id join business_unit bu on c.business_unit_id = bu.business_unit_id left join user u2 on p.business_unit_owner_id = u2.user_id
                            left join user u3 on p.acd_id = u3.user_id left join aop_activity_type aat on p.aop_activity_type_id = aat.aop_activity_type_id where p.project_id = :projectId");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $project_id
 * @return array
 */
function get_asset_info($project_id){
    $link=dbConn();
    $handle=$link->prepare("select a.*, at.asset_type_name, at.asset_type_template_id, atc.asset_type_category_abbrev, atc.asset_type_category_name from asset a
                            join asset_type at on a.asset_type_id = at.asset_type_id left join asset_type_category atc on at.asset_type_category_id = atc.asset_type_category_id
                            where a.project_id = :projectId");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $project_id
 * @return array
 */
function get_project_people($project_id){
    $link=dbConn();
    $handle=$link->prepare("select pu.*, u.first_name, u.last_name, r.role_name, r.role_abbrev from project_user pu join user u on pu.user_id = u.user_id
                            join role r on u.role_id = r.role_id where project_id = :projectId order by u.first_name asc");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $project_id
 * @return array
 */
function get_spend_by_project($project_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT s.*, v.vendor_name, a.asset_type_id, a.asset_quantity, at.asset_type_name, a.asset_name FROM spend s
                            left join vendor v on s.vendor_id = v.vendor_id  left join asset a on s.asset_id = a.asset_id left join asset_type at on a.asset_type_id = at.asset_type_id
                            WHERE s.project_id = :projectId");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    $handle->execute();
    return ($handle->fetchAll(\PDO::FETCH_ASSOC));
}
/**
 * @param $project_id
 * @return mixed
 */
function get_spend_amount_by_project($project_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT sum(spend_amount) as total_spend FROM spend s  WHERE s.project_id = :projectId");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    $handle->execute();
    $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
    return $tmp[0]["total_spend"];
}
function insert_campaign($company_id, $business_unit_id, $campaign_description, $campaign_quarter, $campaign_year, $campaign_budget, $user_id ){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO campaign (company_id, business_unit_id, campaign_description, campaign_quarter, campaign_year, campaign_budget, created_by, created_date, active) VALUES (:company_id, :business_unit_id, :campaign_description, :campaign_quarter, :campaign_year, :campaign_budget, :user_id, now(),1)");
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':business_unit_id', $business_unit_id);
    $stmt->bindParam(':campaign_description', $campaign_description);
    $stmt->bindParam(':campaign_quarter', $campaign_quarter);
    $stmt->bindParam(':campaign_year', $campaign_year);
    $stmt->bindParam(':campaign_budget', $campaign_budget);
    $stmt->bindParam(':user_id', $user_id);
    try{
        $stmt->execute();
        $campaign_id = $dbConnection->lastInsertId('campaign_id');
        return $campaign_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function update_campaign($campaign_id, $business_unit_id, $campaign_description, $campaign_quarter, $campaign_year, $campaign_budget, $user_id, $active){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("update campaign set campaign_description = :campaign_description, business_unit_id = :business_unit_id, campaign_quarter = :campaign_quarter, campaign_year = :campaign_year, campaign_budget = :campaign_budget, modified_by = :user_id, modified_date = now(), active = :active where campaign_id = :campaign_id ");
    $stmt->bindParam(':campaign_id', $campaign_id);
    $stmt->bindParam(':business_unit_id', $business_unit_id);
    $stmt->bindParam(':campaign_description', $campaign_description);
    $stmt->bindParam(':campaign_quarter', $campaign_quarter);
    $stmt->bindParam(':campaign_year', $campaign_year);
    $stmt->bindParam(':campaign_budget', $campaign_budget);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':active', $active);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function insert_project($campaign_id, $project_name, $product_id, $audience_id, $project_manager_id, $project_summary, $project_status_id, $start_date, $end_date, $cost_center, $media_budget, $production_budget, $approved_aop_activity, $upload_to_aps, $user_id, $business_unit_owner_id, $project_requester, $compliance_project, $aop_activity_type_id, $acd_id ){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO project (campaign_id, project_name, product_id, audience_id, project_manager_id, project_summary, project_status_id, start_date, end_date, cost_center, media_budget, production_budget, approved_aop_activity, upload_to_aps, created_by, created_date, active, business_unit_owner_id, project_requester, compliance_project, aop_activity_type_id, acd_id) VALUES (:campaign_id, :project_name, :product_id, :audience_id, :project_manager_id, :project_summary, :project_status_id, :start_date, :end_date, :cost_center, :media_budget, :production_budget, :approved_aop_activity, :upload_to_aps, :user_id, now(), 1, :business_unit_owner_id, :project_requester, :compliance_project, :aop_activity_type_id, :acd_id)");
    $stmt->bindParam(':campaign_id', $campaign_id);
    $stmt->bindParam(':project_name', $project_name);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':audience_id', $audience_id);
    $stmt->bindParam(':project_manager_id', $project_manager_id);
    $stmt->bindParam(':acd_id', $acd_id);
    $stmt->bindParam(':project_summary', $project_summary);
    $stmt->bindParam(':project_status_id', $project_status_id);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);
    $stmt->bindParam(':cost_center', $cost_center);
    $stmt->bindParam(':media_budget', $media_budget);
    $stmt->bindParam(':production_budget', $production_budget);
    $stmt->bindParam(':approved_aop_activity', $approved_aop_activity);
    $stmt->bindParam(':upload_to_aps', $upload_to_aps);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':business_unit_owner_id', $business_unit_owner_id);
    $stmt->bindParam(':project_requester', $project_requester);
    $stmt->bindParam(':compliance_project', $compliance_project);
    $stmt->bindParam(':aop_activity_type_id', $aop_activity_type_id);
    try{
        $stmt->execute();
        $project_id = $dbConnection->lastInsertId('project_id');
        return $project_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function update_project($project_id, $project_name, $product_id, $audience_id, $project_manager_id, $project_summary, $project_status_id, $start_date, $end_date, $cost_center, $media_budget, $production_budget, $approved_aop_activity, $upload_to_aps, $user_id, $active, $business_unit_owner_id, $project_requester, $compliance_project, $campaign_id, $aop_activity_type_id, $acd_id ){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("update project set project_name = :project_name,  product_id = :product_id, audience_id = :audience_id, project_manager_id = :project_manager_id, business_unit_owner_id = :business_unit_owner_id, acd_id = :acd_id, project_requester = :project_requester, project_summary = :project_summary, project_status_id = :project_status_id, start_date = :start_date, end_date = :end_date, cost_center = :cost_center,  media_budget = :media_budget, production_budget = :production_budget, approved_aop_activity = :approved_aop_activity, upload_to_aps = :upload_to_aps, modified_by = :user_id, active = :active, modified_date = now(), compliance_project = :compliance_project, campaign_id = :campaign_id, aop_activity_type_id = :aop_activity_type_id where project_id = :project_id ");
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':project_name', $project_name);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':audience_id', $audience_id);
    $stmt->bindParam(':project_manager_id', $project_manager_id);
    $stmt->bindParam(':acd_id', $acd_id);
    $stmt->bindParam(':project_summary', $project_summary);
    $stmt->bindParam(':project_status_id', $project_status_id);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);
    $stmt->bindParam(':cost_center', $cost_center);
    $stmt->bindParam(':media_budget', $media_budget);
    $stmt->bindParam(':production_budget', $production_budget);
    $stmt->bindParam(':approved_aop_activity', $approved_aop_activity);
    $stmt->bindParam(':upload_to_aps', $upload_to_aps);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':active', $active);
    $stmt->bindParam(':business_unit_owner_id', $business_unit_owner_id);
    $stmt->bindParam(':project_requester', $project_requester);
    $stmt->bindParam(':compliance_project', $compliance_project);
    $stmt->bindParam(':campaign_id', $campaign_id);
    $stmt->bindParam(':aop_activity_type_id', $aop_activity_type_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function insert_project_code($project_id, $project_code){
    $dbConnection = dbConn();
    $sql = "UPDATE project set project_code = :project_code where project_id = :project_id";
    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':project_code', $project_code);
    $stmt->bindParam(':project_id', $project_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function delete_project_person($project_user_id){
    $dbConnection = dbConn();
    $sql = "delete from project_user where project_user_id = :project_user_id";
    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':project_user_id', $project_user_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }

}
function add_project_person($project_id, $user_id){
    $dbConnection = dbConn();
    $sql = "insert into project_user (project_id, user_id) values (:project_id, :user_id)";
    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':user_id', $user_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }

}
function add_spend($project_id, $vendor_id, $spend_amount, $spend_type, $asset_id, $notes, $po_number, $invoice_number, $percent_complete, $cost_expense_account, $user_id, $vendor_other){
    $dbConnection = dbConn();
    $sql = "insert into spend (project_id, vendor_id, spend_amount, spend_type, asset_id, po_number, invoice_number, percent_complete, spend_notes, cost_expense_account, created_by, vendor_other, create_date) values (:project_id, :vendor_id, :spend_amount, :spend_type, :asset_id, :po_number, :invoice_number, :percent_complete, :notes, :cost_expense_account, :user_id, :vendor_other, now())";
    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':vendor_id', $vendor_id);
    $stmt->bindParam(':spend_amount', $spend_amount);
    $stmt->bindParam(':spend_type', $spend_type);
    $stmt->bindParam(':asset_id', $asset_id);
    $stmt->bindParam(':notes', $notes);
    $stmt->bindParam(':po_number', $po_number);
    $stmt->bindParam(':invoice_number', $invoice_number);
    $stmt->bindParam(':percent_complete', $percent_complete);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':cost_expense_account', $cost_expense_account);
    $stmt->bindParam(':vendor_other', $vendor_other);
    try{
        $stmt->execute();
        $spend_id = $dbConnection->lastInsertId('spend_id');
        return $spend_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function insert_spend_month_percentage($spend_id, $spend_month, $spend_percent){
    $dbConnection = dbConn();
    $sql = "insert into spend_percent (spend_id, spend_month, spend_percent) values (:spend_id, :spend_month, :spend_percent)";
    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':spend_id', $spend_id);
    $stmt->bindParam(':spend_month', $spend_month);
    $stmt->bindParam(':spend_percent', $spend_percent);
    try{
        $stmt->execute();
        $spend_percent_id = $dbConnection->lastInsertId('spend_percent_id');
        return $spend_percent_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $project_id
 * @return array|bool
 */
function get_users_for_project($project_id){
    $link=dbConn();
    $handle=$link->prepare("select u.*, r.role_abbrev, r.role_name from user u join role r on u.role_id = r.role_id where user_id not in (select user_id from project_user where project_id = :projectId) order by r.role_abbrev, u.first_name asc");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $project_id
 * @return array|bool
 */
function get_users_for_project2($project_id){
    $link=dbConn();
    $handle=$link->prepare("select u.*, r.role_abbrev, r.role_name from user u join role r on u.role_id = r.role_id where user_id not in (select user_id from project_user
    where project_id = :projectId) and u.active = 1 order by u.first_name asc ");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $active
 * @return array|bool
 */
function get_users_by_company($company_id, $active){
    $link=dbConn();
    $handle=$link->prepare("select u.*, r.role_abbrev, r.role_name from user u join role r on u.role_id = r.role_id where u.company_id = :companyId and u.active = :active order by u.first_name asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":active",$active,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $active
 * @return array|bool
 */
function get_users_by_company_with_business_unit_id($company_id, $active){
    $link=dbConn();
    $handle=$link->prepare("select u.*, bu.business_unit_id from user u left join business_unit bu on u.user_id = bu.business_unit_owner_id
                            where u.company_id = :companyId and u.active = :active order by u.first_name asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":active",$active,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $project_id
 * @return array|bool
 */
function get_users_by_project($project_id){
    $link=dbConn();
    $handle=$link->prepare("select pu.*, u.*, r.role_abbrev, r.role_name from project_user pu join user u on pu.user_id = u.user_id join role r on u.role_id = r.role_id
                            where pu.project_id = :projectId and u.active = 1  order by u.first_name asc");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
function insert_field($company_id, $field_name, $display_type){
    $dbConnection = dbConn();
    $sql = "insert into field (company_id, field_name, display_type, active) values (:company_id, :field_name, :display_type, 1)";
    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':field_name', $field_name);
    $stmt->bindParam(':display_type', $display_type);
    try{
        $stmt->execute();
        $field_id = $dbConnection->lastInsertId('field_id');
        return $field_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function update_field($field_id, $field_name, $active){
    $dbConnection = dbConn();
    $sql = "update field set field_name = :field_name, active = :active where field_id = :field_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':field_id', $field_id);
    $stmt->bindParam(':field_name', $field_name);
    $stmt->bindParam(':active', $active);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $field_id
 * @return array|bool
 */
function get_field_info($field_id){
    $link=dbConn();
    $handle=$link->prepare("select f.* from field f where field_id  = :fieldId");
    $handle->bindValue(":fieldId",$field_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $field_id
 * @return array|bool
 */
function get_max_field_choice_display($field_id){
    $link=dbConn();
    $handle=$link->prepare("select max(display_order) as max_display from field_choice where field_id  = :fieldId");
    $handle->bindValue(":fieldId",$field_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
function insert_field_choice($field_id, $field_choice_name, $display_order){
    $dbConnection = dbConn();
    $sql = "insert into field_choice (field_id, field_choice_name, display_order, active) values (:field_id, :field_choice_name, :display_order, 1)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':field_id', $field_id);
    $stmt->bindParam(':field_choice_name', $field_choice_name);
    $stmt->bindParam(':display_order', $display_order);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $field_id
 * @return array|bool
 */
function get_field_choices($field_id){
    $link=dbConn();
    $handle=$link->prepare("select * from field_choice where field_id = :fieldId order by display_order asc");
    $handle->bindValue(":fieldId",$field_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
function insert_asset($project_id, $asset_type_id, $user_id){
    $dbConnection = dbConn();
    $sql = "insert into asset (project_id, asset_type_id, created_by, created_date) values (:project_id, :asset_type_id, :user_id, now())";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':asset_type_id', $asset_type_id);
    $stmt->bindParam(':user_id', $user_id);
    try{
        $stmt->execute();
        $asset_id = $dbConnection->lastInsertId('asset_id');
        return $asset_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function add_asset($project_id, $asset_type_id, $asset_name, $asset_budget_media, $asset_budget_production, $asset_quantity, $asset_notes, $asset_start_date, $asset_end_date, $user_id){
    $dbConnection = dbConn();
    $sql = "insert into asset (project_id, asset_type_id, asset_name, asset_budget_media, asset_budget_production, asset_quantity, asset_notes, asset_start_date, asset_end_date, created_by, created_date) values (:project_id, :asset_type_id, :asset_name, :asset_budget_media, :asset_budget_production, :asset_quantity, :asset_notes, :asset_start_date, :asset_end_date, :user_id, now())";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':asset_type_id', $asset_type_id);
    $stmt->bindParam(':asset_name', $asset_name);
    $stmt->bindParam(':asset_budget_media', $asset_budget_media);
    $stmt->bindParam(':asset_budget_production', $asset_budget_production);
    $stmt->bindParam(':asset_quantity', $asset_quantity);
    $stmt->bindParam(':asset_notes', $asset_notes);
    $stmt->bindParam(':asset_start_date', $asset_start_date);
    $stmt->bindParam(':asset_end_date', $asset_end_date);
    $stmt->bindParam(':user_id', $user_id);
    try{
        $stmt->execute();
        $asset_id = $dbConnection->lastInsertId('asset_id');
        return $asset_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $asset_id
 * @return array|bool
 */
function get_asset_details($asset_id){
    $link=dbConn();
    $handle=$link->prepare("select a.*, p.project_code, p.project_name from asset a join project p on a.project_id = p.project_id where asset_id = :assetId");
    $handle->bindValue(":assetId",$asset_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
function update_asset($asset_id, $asset_name, $asset_type_id, $asset_budget_media, $asset_budget_production, $asset_quantity, $asset_notes, $user_id, $asset_start_date, $asset_end_date, $asset_has_ge, $asset_for_aps){
    $dbConnection = dbConn();
    $sql = "UPDATE asset set asset_name = :asset_name, asset_type_id = :asset_type_id, asset_budget_media = :asset_budget_media, asset_budget_production = :asset_budget_production, asset_quantity = :asset_quantity, asset_notes = :asset_notes, last_modified_by = :user_id, last_modified = now(), asset_start_date = :asset_start_date, asset_end_date = :asset_end_date, asset_has_ge = :asset_has_ge, asset_for_aps = :asset_for_aps where asset_id = :asset_id";
    //print $sql;
    if($asset_end_date == "1969-12-31"){
        $asset_end_date = "";
    }
    if($asset_start_date == "1969-12-31"){
        $asset_start_date = "";
    }

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_id', $asset_id);
    $stmt->bindParam(':asset_name', $asset_name);
    $stmt->bindParam(':asset_type_id', $asset_type_id);
    $stmt->bindParam(':asset_budget_media', $asset_budget_media);
    $stmt->bindParam(':asset_budget_production', $asset_budget_production);
    $stmt->bindParam(':asset_quantity', $asset_quantity);
    $stmt->bindParam(':asset_notes', $asset_notes);
    $stmt->bindParam(':user_id', $user_id);
    //print "--" . $asset_start_date. "--<br>";
    if (empty($asset_start_date)){
        $stmt->bindValue(':asset_start_date', null, PDO::PARAM_NULL);
    }else{
        $stmt->bindParam(':asset_start_date', $asset_start_date);
    }
    if (empty($asset_end_date)){
        $stmt->bindValue(':asset_end_date', null, PDO::PARAM_NULL);
    }else{
        $stmt->bindParam(':asset_end_date', $asset_end_date);
    }
    $stmt->bindParam(':asset_has_ge', $asset_has_ge);
    $stmt->bindParam(':asset_for_aps', $asset_for_aps);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }

}
/**
 * @param $campaign_id
 * @return bool
 */
function get_total_spend_by_campaign($campaign_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT sum(s.spend_amount) as total_spend from spend s join project p on s.project_id = p.project_id join campaign c on p.campaign_id = c.campaign_id
                            where c.campaign_id = :campaignId");
    $handle->bindValue(":campaignId",$campaign_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))
        return $tmp[0]["total_spend"];
        else return 0;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $business_unit_id
 * @return array|bool
 */
function get_business_code($business_unit_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT business_unit_abbrev from business_unit where business_unit_id = :businessUnitId");
    $handle->bindValue(":businessUnitId",$business_unit_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
function insert_campaign_code($campaign_id, $campaign_code){
    $dbConnection = dbConn();
    $sql = "update campaign set campaign_code = :campaign_code where campaign_id = :campaign_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':campaign_id', $campaign_id);
    $stmt->bindParam(':campaign_code', $campaign_code);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $project_id
 * @return array|bool
 */
function get_project_code($project_id)
{
    $link = dbConn();
    $handle = $link->prepare("SELECT project_code from project where project_id = :projectId");
    $handle->bindValue(":projectId", $project_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["project_code"];
        else return false;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function insert_project_file($project_id, $filename, $file_notes, $file_type, $asset_item_id, $file_network_folder){
    $dbConnection = dbConn();
    $sql = "insert into project_file (project_id, project_file_name, file_notes, file_network_folder, file_type, asset_item_id, active) values(:project_id, :filename, :file_notes, :file_network_folder, :file_type, :asset_item_id, 1)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':filename', $filename);
    $stmt->bindParam(':file_notes', $file_notes);
    $stmt->bindParam(':file_type', $file_type);
    $stmt->bindParam(':file_network_folder', $file_network_folder);
    if ($asset_item_id == 0){
        $stmt->bindValue(':asset_item_id', null, PDO::PARAM_INT);
    }else{
        $stmt->bindParam(':asset_item_id', $asset_item_id);
    }
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        //print_r($e);
        $error_message = $e->getMessage();
        if(strpos($error_message, "unique_project_file_name")){
            return "duplicate file";
        }else{
            return "duplicate asset item";
        }
    }
}
/**
 * @param $project_file_id
 * @return array|bool
 */
function get_project_file_info($project_file_id){
    $link=dbConn();
    $handle=$link->prepare("select pf.* from project_file pf where project_file_id = :projectFileId");
    $handle->bindValue(":projectFileId",$project_file_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $project_id
 * @return array|bool
 */
function get_project_files($project_id){
    $link=dbConn();
    $handle=$link->prepare("select pf.*, ai.asset_item_name from project_file pf left join asset_item ai on pf.asset_item_id = ai.asset_item_id where project_id  = :projectId order by file_type, project_file_name asc");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $project_id
 * @param $file_type
 * @param $active
 * @return array|bool
 */
function get_project_files_by_type($project_id, $file_type, $active){
    $file_type_string = " and file_type = '" . $file_type . "' ";
    if ($file_type == "Rounds"){
        $file_type_string = " and file_type like 'R%' ";
    }
    $link=dbConn();
    $handle=$link->prepare("select pf.* from project_file pf where project_id  = :projectId" . $file_type_string . " and active = :active order by file_type, project_file_name asc");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    $handle->bindValue(":active",$active,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @return array|bool
 */
function get_roles($company_id){
    $link=dbConn();
    $handle=$link->prepare("select r.* from role r where company_id  = :companyId order by role_name asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
function delete_project_file($project_file_id, $active){
    $dbConnection = dbConn();
    if ($active == 2){
        $active = 0;
    }
    $sql = "update project_file set active = " . $active . " where project_file_id = :project_file_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':project_file_id', $project_file_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function insert_task($company_id, $task_name, $role_id, $task_rate, $is_approval){
    $dbConnection = dbConn();
    $sql = "insert into task (company_id, task_name, role_id, task_rate, is_approval, active) values (:company_id, :task_name, :role_id, :task_rate, :is_approval, 1)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':task_name', $task_name);
    $stmt->bindParam(':role_id', $role_id);
    $stmt->bindParam(':task_rate', $task_rate);
    $stmt->bindParam(':is_approval', $is_approval);
    try{
        $stmt->execute();
        $task_id = $dbConnection->lastInsertId('task_id');
        return $task_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $company_id
 * @param $project_id
 * @param $campaign_id
 * @param $phase_id
 * @param $project_manager_id
 * @param $task_id
 * @param $user_id
 * @param $active_flag
 * @return array|bool
 */
function get_task_list($company_id, $project_id, $campaign_id, $phase_id, $project_manager_id, $task_id, $user_id, $active_flag){
    $active_string =($active_flag == 1)?" and p.active = 1":" and p.active = 0";
    $campaign_string = ($campaign_id == 0)?"":" and p.campaign_id = :campaignId";
    $project_string = ($project_id == 0)?"":" and p.project_id = :projectId";
    $project_manager_string =($project_manager_id == "")?"":" and p.project_manager_id = :pmId";
    $task_string =($task_id == "")?"":" and st.task_id = :taskId";
    $phase_string =($phase_id == "")?"":" and s.phase_id = :phaseId";
    $link=dbConn();
    $handle=$link->prepare("select st.*, s.schedule_name, t.task_name, p.project_id, p.project_code, p.project_name, c.campaign_code, u.initials, ph.phase_id, ph.phase_name from schedule_task st
                            join schedule s on st.schedule_id = s.schedule_id join project p on s.project_id = p.project_id join campaign c on p.campaign_id = c.campaign_id
                            join task t on st.task_id = t.task_id join user u on p.project_manager_id = u.user_id left join phase ph on s.phase_id = ph.phase_id
                            where c.company_id = :companyId" . $active_string . $campaign_string . $project_string . $project_manager_string  . $task_string . $phase_string . " order by c.campaign_code, p.project_code, s.schedule_phase_order, st.display_order");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    if ($campaign_id != 0) $handle->bindValue(":campaignId",$campaign_id,PDO::PARAM_INT);
    if ($project_id != 0) $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    if ($project_manager_id != "") $handle->bindValue(":pmId",$project_manager_id,PDO::PARAM_INT);
    if ($task_id != "") $handle->bindValue(":taskId",$task_id,PDO::PARAM_INT);
    if ($phase_id != "") $handle->bindValue(":phaseId",$phase_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $active
 * @return array|bool
 */
function get_tasks($company_id, $active){
    $link=dbConn();
    $handle=$link->prepare("select t.* from task t where company_id  = :companyId and t.active = :active order by task_name asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":active",$active,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $task_id
 * @return array|bool
 */
function get_task_info($task_id){
    $link=dbConn();
    $handle=$link->prepare("select t.* from task t where t.task_id = :taskId");
    $handle->bindValue(":taskId",$task_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
function activate_task($task_id, $active){
    $dbConnection = dbConn();
    $sql = "update task set active = :active where task_id = :task_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':task_id', $task_id);
    $stmt->bindParam(':active', $active);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function update_task($task_id, $task_name, $role_id, $task_rate, $is_approval, $active){
    $dbConnection = dbConn();
    $sql = "update task set task_name = :task_name, role_id = :role_id, ";

    if ($task_rate <> "ignore"){
        $sql .= "task_rate = :task_rate, ";
    }
    $sql .= "is_approval = :is_approval, active = :active where task_id = :task_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':task_id', $task_id);
    $stmt->bindParam(':task_name', $task_name);
    $stmt->bindParam(':role_id', $role_id);
    if ($task_rate <> "ignore"){
        $stmt->bindParam(':task_rate', $task_rate);
    }
    $stmt->bindParam(':is_approval', $is_approval);
    $stmt->bindParam(':active', $active);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }

}
function insert_phase($company_id, $phase_name){
    $dbConnection = dbConn();
    $sql = "insert into phase (company_id, phase_name, active) values (:company_id, :phase_name, 1)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':phase_name', $phase_name);
    try{
        $stmt->execute();
        $phase_id = $dbConnection->lastInsertId('phase_id');
        return $phase_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $company_id
 * @param $active
 * @return array|bool
 */
function get_phases($company_id, $active){
    $link=dbConn();
    $handle=$link->prepare("select p.* from phase p where company_id  = :companyId and p.active = :active order by phase_name asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":active",$active,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $project_id
 * @return array|bool
 */
function get_remaining_phases($company_id, $project_id){
    $link=dbConn();
    $handle=$link->prepare("select p.* from phase p where p.company_id = :companyId and p.phase_id not in(select phase_id from project_phase pp
                            where pp.project_id = :projectId) order by phase_name asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $project_id
 * @return array|bool
 */
function get_project_phases_and_schedules($project_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT s.*, pp.display_order, pp.project_id, p.phase_name, a.asset_name FROM schedule s
                            left join project_phase pp on s.phase_id = pp.phase_id  and pp.project_id = :projectId left join phase p on pp.phase_id = p.phase_id left join asset a on s.asset_id = a.asset_id
                            where s.project_id = :projectId2 order by pp.display_order asc, s.schedule_phase_order asc");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    $handle->bindValue(":projectId2",$project_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $project_id
 * @return array|bool
 */
function get_project_phases($project_id){
    $link=dbConn();
    $handle=$link->prepare("select pp.*, p.phase_name from project_phase pp join phase p on pp.phase_id = p.phase_id where pp.project_id  = :projectId order by pp.display_order asc");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
function activate_phase($phase_id, $active){
    $dbConnection = dbConn();
    $sql = "update phase set active = :active where phase_id = :phase_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':phase_id', $phase_id);
    $stmt->bindParam(':active', $active);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $phase_id
 * @return array|bool
 */
function get_phase_info($phase_id){
    $link=dbConn();
    $handle=$link->prepare("select p.* from phase p where p.phase_id = :phaseId");
    $handle->bindValue(":phaseId",$phase_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $business_unit_id
 * @param $company_id
 * @return array|bool
 */
function get_business_unit_info($business_unit_id, $company_id){
    $link=dbConn();
    $handle=$link->prepare("select bu.* from business_unit bu where company_id = :companyId and bu.business_unit_id = :businessUnitId" );
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":businessUnitId",$business_unit_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false;
    }
}
function update_phase($phase_id, $phase_name, $active){
    $dbConnection = dbConn();
    $sql = "update phase set phase_name = :phase_name, active = :active where phase_id = :phase_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':phase_id', $phase_id);
    $stmt->bindParam(':phase_name', $phase_name);
    $stmt->bindParam(':active', $active);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function update_business_unit($business_unit_id, $business_unit_name, $business_unit_abbrev, $default_cost_code, $active, $business_unit_owner_id){
    $dbConnection = dbConn();
    $sql = "update business_unit set business_unit_name = :business_unit_name, business_unit_abbrev = :business_unit_abbrev, default_cost_code = :default_cost_code, business_unit_owner_id = :business_unit_owner_id, active = :active where business_unit_id = :business_unit_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':business_unit_id', $business_unit_id);
    $stmt->bindParam(':business_unit_name', $business_unit_name);
    $stmt->bindParam(':business_unit_abbrev', $business_unit_abbrev);
    $stmt->bindParam(':default_cost_code', $default_cost_code);
    $stmt->bindParam(':active', $active);
    $stmt->bindParam(':business_unit_owner_id', $business_unit_owner_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $project_id
 * @param $phase_id
 * @return bool
 */
function get_max_phase_order($project_id, $phase_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT max(schedule_phase_order) as max_schedule_phase_order from schedule where project_id = :projectId and phase_id = :phaseId");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    $handle->bindValue(":phaseId",$phase_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["max_schedule_phase_order"];
        else return 0;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $schedule_task_id
 * @return array|bool
 */
function get_total_time_by_schedule_task_id($schedule_task_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT SEC_TO_TIME( SUM( TIME_TO_SEC( `time_worked` ) ) ) AS total_time from schedule_task_time where schedule_task_id = :scheduleTaskId");
    $handle->bindValue(":scheduleTaskId",$schedule_task_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["total_time"];
        else return 0;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function  insert_schedule($project_id, $schedule_name, $phase_id, $asset_id, $schedule_description, $schedule_phase_order, $user_id ){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO schedule (project_id, schedule_name, phase_id, asset_id, schedule_description, schedule_phase_order, created_by, created_date) values (:project_id, :schedule_name, :phase_id, :asset_id, :schedule_description, :schedule_phase_order, :user_id, now() )");
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':schedule_name', $schedule_name);
    $stmt->bindParam(':phase_id', $phase_id);
    $stmt->bindParam(':asset_id', $asset_id);
    $stmt->bindParam(':schedule_description', $schedule_description);
    $stmt->bindParam(':schedule_phase_order', $schedule_phase_order);
    $stmt->bindParam(':user_id', $user_id);
    try{
        $stmt->execute();
        $schedule_id = $dbConnection->lastInsertId('schedule_id');
        return $schedule_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        //print $e;
        return 0;
    }
}
/**
 * @param $project_id
 * @return array|bool
 */
function get_schedules_for_project($project_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT s.* from schedule s where s.project_id = :projectId");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $project_id
 * @return array|bool
 */
function get_schedules_and_phases($project_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT s.*, p.phase_name, p.phase_id from schedule s left join phase p on s.phase_id = p.phase_id where s.project_id = :projectId order by s.phase_id asc, s.schedule_phase_order asc");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $project_id
 * @param $phase_id
 * @return array|bool
 */
function get_schedules_by_phase_and_project($project_id, $phase_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT s.* from schedule s where s.project_id = :projectId and s.phase_id = :phaseId order by s.schedule_phase_order asc");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    $handle->bindValue(":phaseId",$phase_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function  insert_project_phase($project_id, $phase_id, $display_order ){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO project_phase (project_id, phase_id, display_order) values (:project_id, :phase_id, :display_order)");
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':phase_id', $phase_id);
    $stmt->bindParam(':display_order', $display_order);
    try{
        $stmt->execute();
        $project_phase_id = $dbConnection->lastInsertId('project_phase_id');
        return $project_phase_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function delete_project_phase($project_phase_id){
    $dbConnection = dbConn();
    $sql = "delete from project_phase where project_phase_id = :project_phase_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':project_phase_id', $project_phase_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function update_project_phase_display_order($project_id, $display_order){
    $dbConnection = dbConn();
    $sql = "UPDATE project_phase set display_order = (display_order - 1) where project_id = :project_id and display_order > :display_order";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':display_order', $display_order);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }

}
function update_project_phase_specific_display($project_id, $display_id, $new_value){
    $dbConnection = dbConn();
    $sql = "UPDATE project_phase set display_order = :new_value where project_id = :project_id and display_order = :display_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':display_id', $display_id);
    $stmt->bindParam(':new_value', $new_value);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }

}
function update_project_schedule_order($project_id, $phase_id, $schedule_phase_order, $new_value){
    $dbConnection = dbConn();
    $sql = "UPDATE schedule set schedule_phase_order = :new_value where project_id = :project_id and phase_id = :phase_id and schedule_phase_order = :schedule_phase_order";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':phase_id', $phase_id);
    $stmt->bindParam(':schedule_phase_order', $schedule_phase_order);
    $stmt->bindParam(':new_value', $new_value);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function update_schedule_task_order($schedule_id, $display_order, $new_value){
    $dbConnection = dbConn();
    $sql = "UPDATE schedule_task set display_order = :new_value where schedule_id = :schedule_id and display_order = :display_order";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_id', $schedule_id);
    $stmt->bindParam(':display_order', $display_order);
    $stmt->bindParam(':new_value', $new_value);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function update_schedule_task($schedule_task_id, $task_id, $task_manager_id, $start_date, $end_date, $progress, $estimated_hours, $daily_hours, $complete, $predecessor){
    $dbConnection = dbConn();
    $sql = "UPDATE schedule_task set task_manager_id = :task_manager_id, task_id = :task_id, start_date = :start_date, end_date = :end_date, progress = :progress, estimated_hours = :estimated_hours, daily_hours = :daily_hours, complete = :complete, predecessor = :predecessor where schedule_task_id = :schedule_task_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_task_id', $schedule_task_id);
    $stmt->bindParam(':task_id', $task_id);
    $stmt->bindParam(':task_manager_id', $task_manager_id);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);
    $stmt->bindParam(':progress', $progress);
    $stmt->bindParam(':estimated_hours', $estimated_hours);
    $stmt->bindParam(':daily_hours', $daily_hours);
    $stmt->bindParam(':complete', $complete);
    $stmt->bindParam(':predecessor', $predecessor);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function get_schedule_info($schedule_id){
    db_connect();
    $query="select s.*, p.project_id, p.project_name, p.project_code, p.start_date, u.first_name as pm_fname, u.last_name as pm_lname, u.email as pm_email, u.user_id as pm_user_id from schedule s join project p on s.project_id = p.project_id left join user u on p.project_manager_id = u.user_id where schedule_id = " . $schedule_id;
    //print $query . "<br>";
    $arr = get_aa_for_query( $query );
    return $arr;
}
function update_schedule($schedule_id, $schedule_name, $phase_id, $asset_id, $schedule_description, $schedule_phase_order ){
    $dbConnection = dbConn();
    $sql = "UPDATE schedule set schedule_name = :schedule_name, phase_id = :phase_id, asset_id = :asset_id, schedule_description = :schedule_description, schedule_phase_order = :schedule_phase_order where schedule_id = :schedule_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_id', $schedule_id);
    $stmt->bindParam(':schedule_name', $schedule_name);
    $stmt->bindParam(':phase_id', $phase_id);
    $stmt->bindParam(':asset_id', $asset_id);
    $stmt->bindParam(':schedule_description', $schedule_description);
    $stmt->bindParam(':schedule_phase_order', $schedule_phase_order);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $project_id
 * @param $phase_id
 * @return array|bool
 */
function get_count_schedules_in_a_phase($project_id, $phase_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT count(*) as count_schedules from schedule where project_id = :projectId and phase_id = :phaseId");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    $handle->bindValue(":phaseId",$phase_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["count_schedules"];
        else return 0;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_schedule_orders($project_id, $current_phase_id, $schedule_phase_order){
    $dbConnection = dbConn();
    $sql = "UPDATE schedule set schedule_phase_order = (schedule_phase_order-1) where project_id = :project_id and phase_id = :current_phase_id and schedule_phase_order > :schedule_phase_order";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':current_phase_id', $current_phase_id);
    $stmt->bindParam(':schedule_phase_order', $schedule_phase_order);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $company_id
 * @param $active
 * @return array|bool
 */
function get_tasks_by_company($company_id, $active){
    $link=dbConn();
    $handle=$link->prepare("select t.* from task t where active = :active and company_id  = :companyId order by t.task_name asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":active",$active,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $schedule_id
 * @return array|bool
 */
function get_max_display_order_schedule_task($schedule_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT max(display_order) as max_display_order from schedule_task where schedule_id = :scheduleId");
    $handle->bindValue(":scheduleId",$schedule_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["max_display_order"];
        else return 0;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function insert_schedule_task($schedule_id, $task_id, $start_date, $end_date, $estimated_hours, $task_manager_id, $progress, $display_order, $daily_hours, $user_id, $predecessor){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO schedule_task (schedule_id, task_id, start_date, end_date, estimated_hours, task_manager_id, progress, is_current_task, complete, display_order, daily_hours, created_by, created_date, predecessor) values (:schedule_id, :task_id, :start_date, :end_date, :estimated_hours, :task_manager_id, :progress, 0, 0, :display_order, :daily_hours, :user_id, now(), :predecessor )");
    $stmt->bindParam(':schedule_id', $schedule_id);
    $stmt->bindParam(':task_id', $task_id);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);
    $stmt->bindParam(':estimated_hours', $estimated_hours);
    $stmt->bindParam(':task_manager_id', $task_manager_id);
    $stmt->bindParam(':progress', $progress);
    $stmt->bindParam(':display_order', $display_order);
    $stmt->bindParam(':daily_hours', $daily_hours);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':predecessor', $predecessor);
    try{
        $stmt->execute();
        $schedule_task_id = $dbConnection->lastInsertId('schedule_task_id');
        return $schedule_task_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        //print $e;
        return 0;
    }
}
function insert_schedule_task_assignee($schedule_task_id, $user_id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO schedule_task_assignee (schedule_task_id, user_id) values (:schedule_task_id, :user_id)");
    $stmt->bindParam(':schedule_task_id', $schedule_task_id);
    $stmt->bindParam(':user_id', $user_id);
    try{
        $stmt->execute();
        $schedule_task_assignee_id = $dbConnection->lastInsertId('schedule_task_assignee_id');
        return $schedule_task_assignee_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        //print $e;
        return 0;
    }
}
/**
 * @param $schedule_id
 * @return bool
 */
function get_schedule_tasks($schedule_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT st.*, t.task_name, t.is_approval, t.role_id, u.first_name, u.last_name, u.initials, u.user_id, u2.initials as approver, r.role_name FROM schedule_task st
                            join task t on st.task_id = t.task_id left join user u on st.task_manager_id = u.user_id left join user u2 on st.approved_by = u2.user_id
                            left join role r on t.role_id = r.role_id WHERE schedule_id  = :scheduleId order by st.display_order asc");
    $handle->bindValue(":scheduleId",$schedule_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $schedule_id
 * @return array|bool
 */
function get_schedule_tasks_only($schedule_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT st.* FROM schedule_task st WHERE st.schedule_id  = :scheduleId order by st.display_order asc");
    $handle->bindValue(":scheduleId",$schedule_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $schedule_task_id
 * @return array|bool
 */
function get_assignees_by_stid($schedule_task_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT sta.*, u.first_name, u.last_name, u.initials, u.email, u.user_id FROM schedule_task_assignee sta join user u on sta.user_id = u.user_id WHERE schedule_task_id  = :scheduleTaskId");
    $handle->bindValue(":scheduleTaskId",$schedule_task_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $schedule_task_id
 * @return array|bool
 */
function get_assignee_ids_by_stid($schedule_task_id){
    $link=dbConn();
    $result=Array();
    $handle=$link->prepare("SELECT sta.user_id FROM schedule_task_assignee sta WHERE schedule_task_id  = :scheduleTaskId");
    $handle->bindValue(":scheduleTaskId",$schedule_task_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        foreach($tmp as $usrId){
            array_push($result,$usrId["user_id"]);
        }
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $schedule_task_id
 * @return array|bool
 */
function get_all_assignees_by_stid($schedule_task_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT sta.* FROM schedule_task_assignee sta WHERE schedule_task_id  = :scheduleTaskId");
    $handle->bindValue(":scheduleTaskId",$schedule_task_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_task_fields($schedule_task_id, $field_name, $value){
    $dbConnection = dbConn();
    //$sql = "UPDATE schedule_task set :field_name = :value where schedule_task_id = :schedule_task_id";
    $sql = "UPDATE schedule_task set " . $field_name . " = '" . $value . "' where schedule_task_id = " . $schedule_task_id;
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    //$stmt->bindParam(':schedule_task_id', $schedule_task_id);
    //$stmt->bindParam(':field_name', $field_name);
    //$stmt->bindParam(':value', $value);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function delete_schedule_task_assignees($schedule_task_id){
    $dbConnection = dbConn();
    $sql = "delete from schedule_task_assignee where schedule_task_id = :schedule_task_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_task_id', $schedule_task_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function delete_one_schedule_task_assignee($schedule_task_id, $user_id){
    $dbConnection = dbConn();
    $sql = "delete from schedule_task_assignee where schedule_task_id = :schedule_task_id and user_id = :user_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_task_id', $schedule_task_id);
    $stmt->bindParam(':user_id', $user_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function insert_schedule_task_time($schedule_task_id, $worker_user_id, $time_worked, $day_worked, $notes, $user_id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO schedule_task_time (schedule_task_id, worker_user_id, time_worked, day_worked, notes, entered_by, entry_date) VALUES (:schedule_task_id, :worker_user_id, :time_worked, :day_worked, :notes, :user_id, now())");
    $stmt->bindParam(':schedule_task_id', $schedule_task_id);
    $stmt->bindParam(':worker_user_id', $worker_user_id);
    $stmt->bindParam(':time_worked', $time_worked);
    $stmt->bindParam(':day_worked', $day_worked);
    $stmt->bindParam(':notes', $notes);
    $stmt->bindParam(':user_id', $user_id);
    try{
        $stmt->execute();
        $schedule_task_time_id = $dbConnection->lastInsertId('schedule_task_time_id');
        return $schedule_task_time_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $campaign_id
 * @return bool|int
 */
function get_soft_cost_by_campaign($campaign_id){
    $link=dbConn();
    $handle=$link->prepare("select p.campaign_id, round(sum(((TIME_TO_SEC(stt.time_worked)/60/60) * t.task_rate)),2) as sum_total_cost from schedule_task st
                            join task t on st.task_id = t.task_id join schedule_task_time stt on st.schedule_task_id = stt.schedule_task_id
                            join schedule s on st.schedule_id = s.schedule_id join project p on s.project_id = p.project_id where  p.campaign_id = :campaignId");
    $handle->bindValue(":campaignId",$campaign_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["sum_total_cost"];
        else return 0;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $project_id
 * @return bool|int
 */
function get_soft_cost_by_project($project_id){
    $link=dbConn();
    $handle=$link->prepare("select p.campaign_id, round(sum(((TIME_TO_SEC(stt.time_worked)/60/60) * t.task_rate)),2) as sum_total_cost from schedule_task st
                            join task t on st.task_id = t.task_id join schedule_task_time stt on st.schedule_task_id = stt.schedule_task_id
                            join schedule s on st.schedule_id = s.schedule_id join project p on s.project_id = p.project_id where  p.project_id = :projectId");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["sum_total_cost"];
        else return 0;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $user_id
 * @param $date
 * @return bool|int
 */
function get_user_hours_worked_by_day($user_id, $date){
    $date = convert_datepicker_date($date);
    $link=dbConn();
    $handle=$link->prepare("select sta.user_id, sum(st.daily_hours) as daily_total from schedule_task st join schedule_task_assignee sta on st.schedule_task_id = sta.schedule_task_id
                            where st.start_date <= '" .  $date . "' and st.end_date >= '" .  $date . "' and sta.user_id =  :userId");
    $handle->bindValue(":userId",$user_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["daily_total"];
        else return 0;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $user_id
 * @param $date
 * @return bool|int
 */
function get_user_hours_worked_by_day_no_complete($user_id, $date){
    $date = convert_datepicker_date($date);
    $link=dbConn();
    $handle=$link->prepare("select sta.user_id, sum(st.daily_hours) as daily_total from schedule_task st join schedule_task_assignee sta on st.schedule_task_id = sta.schedule_task_id
                            join schedule s on st.schedule_id = s.schedule_id where st.complete <> 1 and st.start_date <= '" .  $date . "' and st.end_date >= '" .  $date . "'
                            and sta.user_id = :userId");
    $handle->bindValue(":userId",$user_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["daily_total"];
        else return 0;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $user_id
 * @return array|bool
 */
function get_user_info($user_id){
    $link=dbConn();
    $handle=$link->prepare("select u.* from user u where user_id = :userId");
    $handle->bindValue(":userId",$user_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $initials
 * @return array|bool
 */
function get_user_info_by_initials($company_id, $initials){
    $link=dbConn();
    $handle=$link->prepare("select u.* from user u where company_id = :companyId and initials = :initials");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":initials",$initials,PDO::PARAM_STR);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $role_id
 * @return array|bool
 */
function get_employees_by_role($role_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT u.*, r.role_name, r.role_abbrev from user u join role r on u.role_id=r.role_id where u.role_id  = :roleId order by u.first_name asc");
    $handle->bindValue(":roleId",$role_id,PDO::PARAM_STR);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $role_id
 * @param $active
 * @return array|bool
 */
function get_employees_by_role_and_active($role_id, $active){
    $active_string = "";
    if ($active == 1){
        $active_string = " and u.active = 1 ";
    }
    if ($active == 2){
        $active_string = " and u.active = 0 ";
    }
    $link=dbConn();
    $handle=$link->prepare("SELECT u.*, r.role_name, r.role_abbrev from user u join role r on u.role_id=r.role_id where u.role_id  = :roleId" . $active_string . " order by u.first_name asc");
    $handle->bindValue(":roleId",$role_id,PDO::PARAM_STR);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $user_id
 * @return array|bool
 */
function get_users_for_resource_report($company_id, $user_id){
    $user_string =empty($user_id)?"":" and user_id = :userId";
    $link=dbConn();
    $handle=$link->prepare("SELECT u.*, r.role_name, r.role_abbrev from user u join role r on u.role_id=r.role_id where u.company_id  = :companyId". $user_string . " order by u.first_name asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_STR);
    $handle->bindValue(":userId",$user_id,PDO::PARAM_STR);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $user_id
 * @param $active
 * @return array|bool
 */
function get_users_for_resource_report_and_active($company_id, $user_id, $active){
    $active_string = "";
    if ($active == 1){
        $active_string = " and u.active = 1 ";
    }
    if ($active == 2){
        $active_string = " and u.active = 0 ";
    }
    $user_string =empty($user_id)?"":" and user_id = :userId";
    $link=dbConn();
    $handle=$link->prepare("SELECT u.*, r.role_name, r.role_abbrev from user u join role r on u.role_id=r.role_id where u.company_id  = :companyId" .  $user_string . $active_string . " order by u.first_name asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    if (!empty($user_id)) $handle->bindValue(":userId",$user_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $user_id
 * @param $start_date
 * @param $end_date
 * @param $use_dates
 * @return array|bool
 */
function get_tasks_for_user($company_id, $user_id, $start_date, $end_date, $use_dates){
    $start_date = convert_datepicker_date($start_date);
    $end_date = convert_datepicker_date($end_date);
    $date_string = " and st.start_date <= '" . $start_date . "' and st.end_date >= '" . $end_date . "'";
    if($use_dates == 0){
        $date_string = " and st.complete = 0";
    }
    $link=dbConn();
    $handle=$link->prepare("SELECT st.*, sta.user_id, u.first_name, u.last_name, u.initials, s.schedule_name, s.schedule_id, p.project_name, p.project_id, r.role_name, r.role_abbrev, t.task_name
                            from schedule_task st join schedule_task_assignee sta on st.schedule_task_id = sta.schedule_task_id join user u on sta.user_id = u.user_id
                            join role r on u.role_id = r.role_id join schedule s on st.schedule_id = s.schedule_id join project p on s.project_id = p.project_id
                            join task t on st.task_id = t.task_id where sta.user_id = :userId and u.company_id = :companyId "  . $date_string);
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_STR);
    $handle->bindValue(":userId",$user_id,PDO::PARAM_STR);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_spend($spend_id, $vendor_id, $spend_amount, $spend_type, $asset_id, $notes, $po_number, $invoice_number, $percent_complete, $cost_expense_account, $vendor_other){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("update spend set vendor_id = :vendor_id, spend_amount = :spend_amount, spend_type = :spend_type, asset_id = :asset_id, spend_notes = :notes, po_number = :po_number, invoice_number = :invoice_number, percent_complete = :percent_complete, cost_expense_account = :cost_expense_account, vendor_other = :vendor_other where spend_id = :spend_id ");
    $stmt->bindParam(':spend_id', $spend_id);
    $stmt->bindParam(':vendor_id', $vendor_id);
    $stmt->bindParam(':spend_amount', $spend_amount);
    $stmt->bindParam(':spend_type', $spend_type);
    $stmt->bindParam(':asset_id', $asset_id);
    $stmt->bindParam(':notes', $notes);
    $stmt->bindParam(':po_number', $po_number);
    $stmt->bindParam(':invoice_number', $invoice_number);
    $stmt->bindParam(':percent_complete', $percent_complete);
    $stmt->bindParam(':cost_expense_account', $cost_expense_account);
    $stmt->bindParam(':vendor_other', $vendor_other);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function delete_spend($spend_id){
    $dbConnection = dbConn();
    $sql = "delete from spend where spend_id = :spend_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':spend_id', $spend_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function  insert_schedule_template($company_id, $schedule_template_name){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO schedule_template (company_id, schedule_template_name, active) values (:company_id, :schedule_template_name, 1)");
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':schedule_template_name', $schedule_template_name);
    try{
        $stmt->execute();
        $schedule_template_id = $dbConnection->lastInsertId('schedule_template_id');
        return $schedule_template_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        //print $e;
        return 0;
    }
}
/**
 * @param $company_id
 * @param $active
 * @return array|bool
 */
function get_schedule_templates($company_id, $active){
    $link=dbConn();
    $handle=$link->prepare("select st.* from schedule_template st where company_id  = :companyId and st.active = :active order by schedule_template_name asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_STR);
    $handle->bindValue(":active",$active,PDO::PARAM_STR);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function activate_schedule_template($schedule_template_id, $active){
    $dbConnection = dbConn();
    $sql = "update schedule_template set active = :active where schedule_template_id = :schedule_template_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_template_id', $schedule_template_id);
    $stmt->bindParam(':active', $active);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $schedule_template_id
 * @return array|bool
 */
function get_schedule_template_tasks($schedule_template_id){
    $link=dbConn();
    $handle=$link->prepare("select stt.*, t.task_name, r1.role_name as manager_role_name, r2.role_name as assignee_role_name from schedule_template_tasks stt
                            join task t on stt.task_id = t.task_id join role r1 on stt.manager_role_id = r1.role_id join role r2 on stt.assignee_role_id = r2.role_id
                            where stt.schedule_template_id  = :scheduleTemplateId order by display_order asc");
    $handle->bindValue(":scheduleTemplateId",$schedule_template_id,PDO::PARAM_STR);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $schedule_template_id
 * @return array|bool
 */
function get_max_schedule_template_task_display_order($schedule_template_id){
    $link=dbConn();
    $handle=$link->prepare("select max(display_order) as max_display from schedule_template_tasks where schedule_template_id  = :scheduleTemplateId");
    $handle->bindValue(":scheduleTemplateId",$schedule_template_id,PDO::PARAM_STR);
    try{
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["max_display"];
        else return 0;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function  insert_schedule_template_task($schedule_template_id, $task_id, $manager_role_id, $assignee_role_id, $start_day, $end_day, $total_time, $display_order, $predecessor){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO schedule_template_tasks (schedule_template_id, task_id, manager_role_id, assignee_role_id, start_day, end_day, total_time, display_order, predecessor) VALUES (:schedule_template_id, :task_id, :manager_role_id, :assignee_role_id, :start_day, :end_day, :total_time, :display_order, :predecessor)");
    $stmt->bindParam(':schedule_template_id', $schedule_template_id);
    $stmt->bindParam(':task_id', $task_id);
    $stmt->bindParam(':manager_role_id', $manager_role_id);
    $stmt->bindParam(':assignee_role_id', $assignee_role_id);
    $stmt->bindParam(':start_day', $start_day);
    $stmt->bindParam(':end_day', $end_day);
    $stmt->bindParam(':total_time', $total_time);
    $stmt->bindParam(':display_order', $display_order);
    $stmt->bindParam(':predecessor', $predecessor);
    try{
        $stmt->execute();
        $schedule_template_tasks_id = $dbConnection->lastInsertId('schedule_template_tasks_id');
        return $schedule_template_tasks_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function update_schedule_template_tasks_order($schedule_template_id, $display_order, $new_value){
    $dbConnection = dbConn();
    $sql = "UPDATE schedule_template_tasks set display_order = :new_value where schedule_template_id = :schedule_template_id and display_order = :display_order";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_template_id', $schedule_template_id);
    $stmt->bindParam(':display_order', $display_order);
    $stmt->bindParam(':new_value', $new_value);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function delete_schedule_template_task($schedule_template_tasks_id){
    $dbConnection = dbConn();
    $sql = "delete from schedule_template_tasks where schedule_template_tasks_id = :schedule_template_tasks_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_template_tasks_id', $schedule_template_tasks_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function update_schedule_template_tasks_display_order($schedule_template_id, $display_order){
    $dbConnection = dbConn();
    $sql = "UPDATE schedule_template_tasks set display_order = (display_order - 1) where schedule_template_id = :schedule_template_id and display_order > :display_order";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_template_id', $schedule_template_id);
    $stmt->bindParam(':display_order', $display_order);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $schedule_template_id
 * @return bool
 */
function get_schedule_task_template_info($schedule_template_id){
    $link=dbConn();
    $handle=$link->prepare("select st.* from schedule_template st where schedule_template_id = :scheduleTemplateId");
    $handle->bindValue(":scheduleTemplateId",$schedule_template_id,PDO::PARAM_STR);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function move_schedule_tasks($schedule_id, $display_order_num, $increment_num){
    $dbConnection = dbConn();

    $asc_desc = "desc";

    if($increment_num<0){
        $asc_desc = "asc";
    }
    $sql = "UPDATE schedule_task set display_order = (display_order + :increment_num) where schedule_id = :schedule_id and display_order >= :display_order_num order by display_order " . $asc_desc;

    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_id', $schedule_id);
    $stmt->bindParam(':display_order_num', $display_order_num);
    $stmt->bindParam(':increment_num', $increment_num);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $project_id
 * @param $role_id
 * @return array|bool
 */
function get_user_by_project_and_role($project_id, $role_id){
    $link=dbConn();
    $handle=$link->prepare("select max(u.user_id) as user_id from user u join project_user pu on u.user_id = pu.user_id where project_id  = :projectId and u.role_id = :roleId");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_STR);
    $handle->bindValue(":roleId",$role_id,PDO::PARAM_STR);
    try{
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["user_id"];
        else return 0;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function add_user($company_id, $first_name, $last_name, $system_user_name, $email, $initials, $role_id, $is_project_manager, $is_aps_admin, $user_level){
    $dbConnection = dbConn();
    if(empty($is_project_manager)){
        $is_project_manager = 0;
    }
    $sql = "insert into user (company_id, first_name, last_name, system_user_name, email, initials, role_id, is_project_manager, is_aps_admin, user_level, active) values (:company_id, :first_name, :last_name, :system_user_name, :email, :initials, :role_id, :is_project_manager, :is_aps_admin, :user_level, 1)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':system_user_name', $system_user_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':initials', $initials);
    $stmt->bindParam(':role_id', $role_id);
    $stmt->bindParam(':is_project_manager', $is_project_manager);
    $stmt->bindParam(':is_aps_admin', $is_aps_admin);
    $stmt->bindParam(':user_level', $user_level);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }

}
function activate_user($user_id, $active){
    $dbConnection = dbConn();
    $sql = "update user set active = :active where user_id = :user_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':active', $active);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function update_user($user_id, $first_name, $last_name, $email, $initials, $role_id, $is_project_manager, $is_aps_admin, $user_level, $system_user_name, $active){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("update user set first_name = :first_name, last_name = :last_name, email = :email, initials = :initials, role_id = :role_id, is_project_manager = :is_project_manager, is_aps_admin = :is_aps_admin, user_level = :user_level, system_user_name = :system_user_name, active = :active where user_id = :user_id ");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':initials', $initials);
    $stmt->bindParam(':role_id', $role_id);
    $stmt->bindParam(':is_project_manager', $is_project_manager);
    $stmt->bindParam(':is_aps_admin', $is_aps_admin);
    $stmt->bindParam(':user_level', $user_level);
    $stmt->bindParam(':system_user_name', $system_user_name);
    $stmt->bindParam(':active', $active);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $company_id
 * @param $active
 * @return array|bool
 */
function get_statuses($company_id, $active){
    $link=dbConn();
    $handle=$link->prepare("select ps.* from project_status ps where company_id  = :companyId and ps.active = :active order by display_order asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_STR);
    $handle->bindValue(":active",$active,PDO::PARAM_STR);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $active
 * @return array|bool
 */
function get_max_status($company_id, $active){
    $link=dbConn();
    $handle=$link->prepare("SELECT max(display_order) as max_display_order from project_status where company_id = :companyId and active = :active");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_STR);
    $handle->bindValue(":active",$active,PDO::PARAM_STR);
    try{
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["max_display_order"];
        else return 0;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function insert_status($company_id, $project_status_name, $display_order){
    $dbConnection = dbConn();
    $sql = "insert into project_status (company_id,  project_status_name, display_order, active) values( :company_id, :project_status_name, :display_order, 1)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':project_status_name', $project_status_name);
    $stmt->bindParam(':display_order', $display_order);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }

}
function activate_status($project_status_id, $active, $display_order){
    $dbConnection = dbConn();
    $sql = "update project_status set active = :active, display_order = :display_order where project_status_id = :project_status_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':project_status_id', $project_status_id);
    $stmt->bindParam(':active', $active);
    $stmt->bindParam(':display_order', $display_order);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function move_project_statuses($company_id, $display_order_num, $active){
    $dbConnection = dbConn();
    $sql = "UPDATE project_status set display_order = (display_order - 1) where company_id = :company_id and display_order >= :display_order_num and active = :active order by display_order asc";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':display_order_num', $display_order_num);
    $stmt->bindParam(':active', $active);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function update_status_order($company_id, $display_order, $new_value){
    $dbConnection = dbConn();
    $sql = "UPDATE project_status set display_order = :new_value where company_id = :company_id and display_order = :display_order and active = 1";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':display_order', $display_order);
    $stmt->bindParam(':new_value', $new_value);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function insert_vendor($company_id, $vendor_name){
    $dbConnection = dbConn();
    $sql = "insert into vendor (company_id, vendor_name, active) values (:company_id, :vendor_name, 1)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':vendor_name', $vendor_name);
    try{
        $stmt->execute();
        $vendor_id = $dbConnection->lastInsertId('vendor_id');
        return $vendor_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function activate_vendor($vendor_id, $active){
    $dbConnection = dbConn();
    $sql = "update vendor set active = :active where vendor_id = :vendor_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':vendor_id', $vendor_id);
    $stmt->bindParam(':active', $active);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $company_id
 * @param $active
 * @return array|bool
 */
function get_roles2($company_id, $active){
    $link=dbConn();
    $handle=$link->prepare("select * from role where company_id  = :companyId and active = :active order by role_name asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_STR);
    $handle->bindValue(":active",$active,PDO::PARAM_STR);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @return array|bool
 */
function get_user_levels($company_id){
    $link=dbConn();
    $handle=$link->prepare("select * from user_level where company_id  = :companyId and user_level < 40 order by user_level asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_STR);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @return array|bool
 */
function get_user_levels_all($company_id){
    $link=dbConn();
    $handle=$link->prepare("select * from user_level where company_id  = :companyId order by user_level asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_STR);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function insert_role($company_id, $role_name, $role_abbrev){
    $dbConnection = dbConn();
    $sql = "insert into role (company_id, role_name, role_abbrev, active) values (:company_id, :role_name, :role_abbrev, 1)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':role_name', $role_name);
    $stmt->bindParam(':role_abbrev', $role_abbrev);
    try{
        $stmt->execute();
        $role_id = $dbConnection->lastInsertId('role_id');
        return $role_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function activate_role($role_id, $active){
    $dbConnection = dbConn();
    $sql = "update role set active = :active where role_id = :role_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':role_id', $role_id);
    $stmt->bindParam(':active', $active);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function insert_audience($company_id, $audience_name){
    $dbConnection = dbConn();
    $sql = "insert into audience (company_id, audience_name, active) values (:company_id, :audience_name, 1)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':audience_name', $audience_name);
    try{
        $stmt->execute();
        $audience_id = $dbConnection->lastInsertId('audience_id');
        return $audience_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function activate_audience($audience_id, $active){
    $dbConnection = dbConn();
    $sql = "update audience set active = :active where audience_id = :audience_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':audience_id', $audience_id);
    $stmt->bindParam(':active', $active);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function insert_business_unit($company_id, $business_unit_name, $business_unit_abbrev, $default_cost_code, $business_unit_owner_id){
    $dbConnection = dbConn();
    $sql = "insert into business_unit (company_id, business_unit_name, business_unit_abbrev, default_cost_code, business_unit_owner_id, active) values (:company_id, :business_unit_name, :business_unit_abbrev, :default_cost_code, :business_unit_owner_id,1)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':business_unit_name', $business_unit_name);
    $stmt->bindParam(':business_unit_abbrev', $business_unit_abbrev);
    $stmt->bindParam(':default_cost_code', $default_cost_code);
    $stmt->bindParam(':business_unit_owner_id', $business_unit_owner_id);
    try{
        $stmt->execute();
        $business_unit_id = $dbConnection->lastInsertId('business_unit_id');
        return $business_unit_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function activate_business_unit($business_unit_id, $active){
    $dbConnection = dbConn();
    $sql = "update business_unit set active = :active where business_unit_id = :business_unit_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':business_unit_id', $business_unit_id);
    $stmt->bindParam(':active', $active);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function insert_asset_type($company_id, $asset_type_name, $asset_type_category_id, $asset_type_template_id){
    $dbConnection = dbConn();
    $sql = "insert into asset_type (company_id, asset_type_name, asset_type_category_id, asset_type_template_id, active) values (:company_id, :asset_type_name, :asset_type_category_id, :asset_type_template_id, 1)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':asset_type_name', $asset_type_name);
    $stmt->bindParam(':asset_type_category_id', $asset_type_category_id);
    $stmt->bindParam(':asset_type_template_id', $asset_type_template_id);
    try{
        $stmt->execute();
        $asset_type_id = $dbConnection->lastInsertId('asset_type_id');
        return $asset_type_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function activate_asset_type($asset_type_id, $active){
    $dbConnection = dbConn();
    $sql = "update asset_type set active = :active where asset_type_id = :asset_type_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_type_id', $asset_type_id);
    $stmt->bindParam(':active', $active);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function delete_asset($asset_id){
    $dbConnection = dbConn();
    $sql = "delete from asset where asset_id = :asset_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_id', $asset_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }

}
function delete_schedule_tasks($schedule_id){
    $dbConnection = dbConn();
    $sql = "delete from schedule_task where schedule_id = :schedule_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_id', $schedule_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function delete_schedule_task($schedule_task_id){
    $dbConnection = dbConn();
    $sql = "delete from schedule_task where schedule_task_id = :schedule_task_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_task_id', $schedule_task_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function delete_schedule($schedule_id){
    $dbConnection = dbConn();
    $sql = "delete from schedule where schedule_id = :schedule_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_id', $schedule_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $schedule_task_id
 * @return array|bool
 */
function get_project_info_by_schedule_task($schedule_task_id){
    $link=dbConn();
    $handle=$link->prepare("select st.*, u.first_name as m_fname, u.last_name as m_lname, u.initials as m_initials, s.schedule_name, s.schedule_id, p.project_name, p.project_id, p.project_code,
                            u2.first_name as pm_fname, u2.last_name as pm_lname, u2.initials as pm_initials, u3.first_name as a_fname, u3.last_name as a_lname,
                            u3.initials, t.task_name, c.campaign_id, c.campaign_code, c.company_id from schedule_task st left join user u on st.task_manager_id = u.user_id
                            join schedule s on st.schedule_id = s.schedule_id join project p on s.project_id = p.project_id join user u2 on p.project_manager_id = u2.user_id
                            join task t on st.task_id = t.task_id left join user u3 on st.approved_by = u3.user_id join campaign c on p.campaign_id = c.campaign_id
                            where schedule_task_id = :scheduleTaskId");
    $handle->bindValue(":scheduleTaskId",$schedule_task_id,PDO::PARAM_STR);
    try{
        $handle->execute();
        return ($handle->fetchAll(\PDO::FETCH_ASSOC));
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_approval($schedule_task_id, $user_id, $is_approved, $approval_notes){
    $dbConnection = dbConn();
    $sql = "UPDATE schedule_task set approved_by = :user_id, approval_date = now(), is_approved = :is_approved, approval_notes = :approval_notes where schedule_task_id = :schedule_task_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_task_id', $schedule_task_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':is_approved', $is_approved);
    $stmt->bindParam(':approval_notes', $approval_notes);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }

}
function insert_email_send($email_send_type, $project_id, $schedule_task_id, $recipient_id, $send_success, $sender_id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO email_send (email_send_type, project_id, schedule_task_id, recipient_id, send_success, send_date, sender_id) values (:email_send_type, :project_id, :schedule_task_id, :recipient_id, :send_success, now(), :sender_id )");
    $stmt->bindParam(':email_send_type', $email_send_type);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':schedule_task_id', $schedule_task_id);
    $stmt->bindParam(':recipient_id', $recipient_id);
    $stmt->bindParam(':send_success', $send_success);
    $stmt->bindParam(':sender_id', $sender_id);
    try{
        $stmt->execute();
        $email_send_id = $dbConnection->lastInsertId('email_send_id');
        return $email_send_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        print $e;
        return 0;
    }
}
function insert_email_send_with_error($email_send_type, $project_id, $schedule_task_id, $recipient_id, $send_success, $sender_id, $error_message){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO email_send (email_send_type, project_id, schedule_task_id, recipient_id, send_success, send_date, sender_id, error_message) values (:email_send_type, :project_id, :schedule_task_id, :recipient_id, :send_success, now(), :sender_id, :error_message )");
    $stmt->bindParam(':email_send_type', $email_send_type);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':schedule_task_id', $schedule_task_id);
    $stmt->bindParam(':recipient_id', $recipient_id);
    $stmt->bindParam(':send_success', $send_success);
    $stmt->bindParam(':sender_id', $sender_id);
    $stmt->bindParam(':error_message', $error_message);
    try{
        $stmt->execute();
        $email_send_id = $dbConnection->lastInsertId('email_send_id');
        return $email_send_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        print $e;
        return 0;
    }
}
function toggle_fasttrack($schedule_id, $status){
    $dbConnection = dbConn();
    $sql = "UPDATE schedule set fast_track_status = :status where schedule_id = :schedule_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_id', $schedule_id);
    $stmt->bindParam(':status', $status);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }

}
/**
 * @param $schedule_id
 * @return array|bool
 */
function get_min_incomplete_task($schedule_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT min(display_order) as display_order, schedule_task_id FROM schedule_task WHERE complete <> 1 and schedule_id = :scheduleId ");
    $handle->bindValue(":scheduleId",$schedule_id,PDO::PARAM_STR);
    try{
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["schedule_task_id"];
        else return 0;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function clear_current_task($schedule_id){
    $dbConnection = dbConn();
    $sql = "update schedule_task set is_current_task = 0 where schedule_id = :schedule_id and is_current_task = 1";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_id', $schedule_id);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function clear_and_complete_current_task($schedule_task_id){
    $dbConnection = dbConn();
    $sql = "update schedule_task set is_current_task = 0, complete = 1, progress = 100 where schedule_task_id = :schedule_task_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_task_id', $schedule_task_id);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function set_current_task($schedule_task_id){
    $dbConnection = dbConn();
    $sql = "update schedule_task set is_current_task = 1 where schedule_task_id = :schedule_task_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_task_id', $schedule_task_id);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $system_user_name
 * @return bool|int
 */
function get_user_info_by_system_user_name($system_user_name){
    $link=dbConn();
    $handle=$link->prepare("select u.* from user u where u.system_user_name = :systemUserName");
    $handle->bindValue(":systemUserName",$system_user_name,PDO::PARAM_STR);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $user_id
 * @return array|bool|int
 */
function get_projects_for_user($user_id){
    $link=dbConn();
    $handle=$link->prepare("select distinct p.project_id, p.project_name, p.project_code, ps.project_status_name, u.last_name as pm_lname, u2.last_name as acd_lname, c.campaign_code
                            from project p join schedule s on s.project_id = p.project_id join schedule_task st on s.schedule_id = st.schedule_id
                            join schedule_task_assignee sta on st.schedule_task_id = sta.schedule_task_id join project_status ps on p.project_status_id = ps.project_status_id
                            join user u on p.project_manager_id = u.user_id left join user u2 on p.acd_id = u2.user_id join campaign c on p.campaign_id = c.campaign_id
                            where sta.user_id = :userId and p.active = 1 order by s.project_id, s.schedule_id");
    $handle->bindValue(":userId",$user_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $user_id
 * @return array|bool|int
 */
function get_projects_user_is_assigned_to($user_id){
    $link=dbConn();
    $handle=$link->prepare("select distinct p.project_id, p.project_name, p.project_code, ps.project_status_name, u.last_name as pm_lname, u2.last_name as acd_lname, c.campaign_code
            from project p
            join project_user pu on p.project_id = pu.project_id
            join project_status ps on p.project_status_id = ps.project_status_id
            join user u on p.project_manager_id = u.user_id
            left join user u2 on p.acd_id = u2.user_id
            join campaign c on p.campaign_id = c.campaign_id where pu.user_id = :userId and p.project_status_id <> 9 and p.active = 1 order by p.project_code");
    $handle->bindValue(":userId",$user_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $user_id
 * @return array|bool|int
 */
function get_open_tasks_for_user($user_id){
    $link=dbConn();
    $handle=$link->prepare("select p.project_id, p.project_name, p.project_code, s.schedule_name, st.schedule_task_id, st.progress, t.task_name from project p
                            join schedule s on p.project_id = s.project_id join schedule_task st on s.schedule_id = st.schedule_id
                            join schedule_task_assignee sta on st.schedule_task_id = sta. schedule_task_id join task t on st.task_id = t.task_id
                            where p.active = 1 and st.complete = 0 and sta.user_id = :userId order by s.project_id, s.schedule_id");
    $handle->bindValue(":userId",$user_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $user_id
 * @param $day_count
 * @return array|bool|int
 */
function get_open_tasks_for_pm($user_id, $day_count){
    $today = date("Y-m-d");
    $end_date = get_date_no_weekends($today, $day_count);
    $link=dbConn();
    $handle=$link->prepare("select p.project_id, p.project_name, p.project_code, s.schedule_id, s.schedule_name, st.schedule_task_id, st.progress, st.start_date, st.end_date, t.task_name from project p
                            join schedule s on p.project_id = s.project_id join schedule_task st on s.schedule_id = st.schedule_id join schedule_task_assignee sta on st.schedule_task_id = sta. schedule_task_id
                            join task t on st.task_id = t.task_id where p.active = 1 and st.complete = 0 and p.project_manager_id = :userId and st.end_date <= '" . $end_date . "' order by st.end_date desc");
    $handle->bindValue(":userId",$user_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $user_id
 * @return array|bool|int
 */
function get_tasks_with_no_time_for_user($user_id){
    $link=dbConn();
    $handle=$link->prepare("select p.project_id, p.project_name, p.project_code, s.schedule_name, st.schedule_task_id, st.progress, st.end_date, t.task_name, ((SUM(TIME_TO_SEC( `time_worked` ))/60)/60) as time_worked from project p
                            join schedule s on p.project_id = s.project_id join schedule_task st on s.schedule_id = st.schedule_id join task t on st.task_id = t.task_id
                            left join schedule_task_time stt on st.schedule_task_id = stt.schedule_task_id and stt.worker_user_id =:userId join schedule_task_assignee sta on st.schedule_task_id = sta. schedule_task_id
                            where p.active = 1 and st.complete = 0 and sta.user_id = :userId2 group by st.schedule_task_id order by st.end_date, s.project_id, s.schedule_id");
    $handle->bindValue(":userId",$user_id,PDO::PARAM_INT);
    $handle->bindValue(":userId2",$user_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $user_id
 * @return array|bool|int
 */
function get_approvals_for_user($user_id){
    $link=dbConn();
    $handle=$link->prepare("select p.project_id, p.project_name, p.project_code, s.schedule_name, st.schedule_task_id, st.progress, t.task_name, t.is_approval, st.is_approved, st.approved_by, st.approval_notes
                            from project p join schedule s on p.project_id = s.project_id join schedule_task st on s.schedule_id = st.schedule_id
                            join schedule_task_assignee sta on st.schedule_task_id = sta. schedule_task_id join task t on st.task_id = t.task_id
                            where p.active = 1 and st.complete = 0 and t.is_approval = 1 and !(st.is_approved <=> 1) and sta.user_id = :userId" );
    $handle->bindValue(":userId",$user_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $user_id
 * @param $day_count
 * @return array|bool|int
 */
function get_approvals_for_pm($user_id, $day_count){
    $today = date("Y-m-d");
    $end_date = get_date_no_weekends($today, $day_count);
    $link=dbConn();
    $handle=$link->prepare("select p.project_id, p.project_name, p.project_code, s.schedule_id, s.schedule_name, st.schedule_task_id, st.progress, t.task_name, t.is_approval, st.is_approved, st.approved_by, st.approval_notes, st.end_date
                            from project p join schedule s on p.project_id = s.project_id join schedule_task st on s.schedule_id = st.schedule_id
                            join schedule_task_assignee sta on st.schedule_task_id = sta. schedule_task_id join task t on st.task_id = t.task_id
                            where p.active = 1 and t.is_approval = 1 and !(st.is_approved <=> 1) and st.end_date <= '" . $end_date . "' and p.project_manager_id = :userId");
    $handle->bindValue(":userId",$user_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_progress($schedule_task_id, $progress){
    $dbConnection = dbConn();
    $sql = "UPDATE schedule_task set progress = :progress where schedule_task_id = :schedule_task_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_task_id', $schedule_task_id);
    $stmt->bindParam(':progress', $progress);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $company_id
 * @param $project_manager_id
 * @return array|bool|int
 */
function get_campaigns_and_spend($company_id, $project_manager_id){
    $link=dbConn();
    $handle=$link->prepare("select c.*, sum(s.spend_amount) as spend_amount from campaign c join project p on c.campaign_id = p.campaign_id left join spend s on s.project_id = p.project_id
                            where c.active = 1 and p.active = 1 and p.project_manager_id = :pmId and c.company_id = :companyId group by c.campaign_id order by campaign_year, campaign_quarter");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":pmId",$project_manager_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $campaign_id
 * @param $project_manager_id
 * @return array|bool|int
 */
function get_projects_and_spend($campaign_id, $project_manager_id){
    $link=dbConn();
    $handle=$link->prepare("select p.*, a.audience_name, ps.project_status_name from project p left join audience a on p.audience_id = a.audience_id
                            join project_status ps on p.project_status_id = ps.project_status_id where p.project_manager_id = :pmId and p.active = 1 and campaign_id = :campaignId group by p.project_id  order by p.project_code");
    $handle->bindValue(":campaignId",$campaign_id,PDO::PARAM_INT);
    $handle->bindValue(":pmId",$project_manager_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $project_id
 * @param $spend_type
 * @return bool|int
 */
function get_spend_by_project_and_type($project_id, $spend_type){
    $link=dbConn();
    $handle=$link->prepare("select sum(spend_amount) as spend_amount from spend where project_id = :projectId and spend_type = :spendType");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    $handle->bindValue(":spendType",$spend_type,PDO::PARAM_STR);
    try{
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["spend_amount"];
        else return 0;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $year
 * @return array|bool|int
 */
function get_active_campaigns($company_id, $year){
    $year_string = empty($year)?"":" and c.campaign_year = :year";
    $link=dbConn();
    $handle=$link->prepare("select c.campaign_id, c.campaign_year, c.campaign_budget as total_budget, count(p.project_id) as num_projects, bu.business_unit_name, bu.business_unit_abbrev
                            from campaign c join project p on p.campaign_id = c.campaign_id join business_unit bu on c.business_unit_id = bu.business_unit_id
                            where c.active = 1 and c.company_id = :companyId" . $year_string . " group by c.campaign_id order by campaign_year, campaign_code");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    if (!empty($year)) $handle->bindValue(":year",$year,PDO::PARAM_STR);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $quarter
 * @param $year
 * @return array|bool|int
 */
function get_total_spend_per_quarter_year($company_id, $quarter, $year){
    $link=dbConn();
    $handle=$link->prepare("select sum(spend_amount) as total_spend from spend s join project p on s.project_id = p.project_id join campaign c on p.campaign_id = c.campaign_id
                            where c.campaign_quarter = :quarter and c.campaign_year = :year and c.company_id = :companyId");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":year",$year,PDO::PARAM_STR);
    $handle->bindValue(":quarter",$quarter,PDO::PARAM_STR);
    try{
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["total_spend"];
        else return 0;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $campaign_id
 * @return bool|int
 */
function get_total_spend_per_quarter_campaign($company_id, $campaign_id){
    $link=dbConn();
    $handle=$link->prepare("select sum(spend_amount) as total_spend from spend s join project p on s.project_id = p.project_id join campaign c on p.campaign_id = c.campaign_id
                            where c.campaign_id = :campaignId and c.company_id = :companyId");
    $handle->bindValue(":campaignId",$campaign_id,PDO::PARAM_INT);
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["total_spend"];
        else return 0;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $quarter
 * @param $year
 * @return bool|int
 */
function get_work_effort_spend_per_quarter_year($company_id, $quarter, $year){
    $link=dbConn();
    $handle=$link->prepare(" SELECT (((SUM(TIME_TO_SEC( `time_worked` ))/60)/60)*t.task_rate) as effort_cost from schedule_task_time stt
                             join schedule_task st on stt.schedule_task_id = st.schedule_task_id join task t on st.task_id = t.task_id
                             join schedule s on st.schedule_id = s.schedule_id join project p on s.project_id = p.project_id join campaign c on p.campaign_id = c.campaign_id
                             where c.campaign_quarter = :quarter and c.campaign_year = :year and c.company_id = :companyId group by st.schedule_task_id");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":quarter",$quarter,PDO::PARAM_STR);
    $handle->bindValue(":year",$year,PDO::PARAM_STR);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        $sum=0;
        foreach ($result as $value ) {
            $sum+=$value["effort_cost"];
        }
        return ($sum);
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @return array|bool|int
 */
function get_counts_by_pm($company_id){
    $link=dbConn();
    $handle=$link->prepare("select u.user_id, u.first_name, u.last_name, u.initials, count(project_id) as num_projects from project p join campaign c on p.campaign_id = c.campaign_id
                            join user u on p.project_manager_id = u.user_id where c.company_id = :companyId and p.active = 1 group by project_manager_id order by u.last_name, p.project_code");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_start_and_end_dates($schedule_task_id, $start_date, $end_date){
    $dbConnection = dbConn();
    $sql = "UPDATE schedule_task set start_date = :start_date, end_date =:end_date where schedule_task_id = :schedule_task_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_task_id', $schedule_task_id);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $company_id
 * @param $quarter
 * @param $year
 * @return array|bool|int
 */
function get_active_campaigns_by_quarter_year($company_id, $quarter, $year){
    $link=dbConn();
    $handle=$link->prepare("select c.*, b.business_unit_name from campaign c join business_unit b on c.business_unit_id = b.business_unit_id
                            where c.active = 1 and c.company_id = :companyId and c.campaign_quarter = :quarter and c.campaign_year = :year") ;
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":quarter",$quarter,PDO::PARAM_STR);
    $handle->bindValue(":year",$year,PDO::PARAM_STR);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $campaign_id
 * @return bool|int
 */
function get_work_effort_spend_per_campaign($company_id, $campaign_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT (((SUM(TIME_TO_SEC( `time_worked` ))/60)/60)*t.task_rate) as effort_cost from schedule_task_time stt
                            join schedule_task st on stt.schedule_task_id = st.schedule_task_id join task t on st.task_id = t.task_id
                            join schedule s on st.schedule_id = s.schedule_id join project p on s.project_id = p.project_id join campaign c on p.campaign_id = c.campaign_id
                            where c.campaign_id = :campaignId and c.company_id = :companyId group by st.schedule_task_id");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":campaignId",$campaign_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        $sum=0;
        foreach ($result as $value ) {
            $sum+=$value["effort_cost"];
        }
        return ($sum);
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $campaign_id
 * @return array|bool|int
 */
function get_campaign_projects($campaign_id){
    $link=dbConn();
    $handle=$link->prepare("select p.*, sum(s.spend_amount) as total_spend, pr.product_name, a.audience_name, u.last_name as pm_last_name from project p
                            left join spend s on s.project_id = p.project_id join product pr on p.product_id = pr.product_id left join audience a on p.audience_id = a.audience_id
                            left join user u on p.project_manager_id = u.user_id where p.campaign_id = :campaignId group by p.project_id order by p.project_code");
    $handle->bindValue(":campaignId",$campaign_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $project_id
 * @return bool|int
 */
function get_work_effort_spend_per_project($project_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT (((SUM(TIME_TO_SEC( `time_worked` ))/60)/60)*t.task_rate) as effort_cost from schedule_task_time stt
                            join schedule_task st on stt.schedule_task_id = st.schedule_task_id join task t on st.task_id = t.task_id
                            join schedule s on st.schedule_id = s.schedule_id join project p on s.project_id = p.project_id
                            where p.project_id = :projectId group by st.schedule_task_id");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        $sum=0;
        foreach ($result as $value ) {
            $sum+=$value["effort_cost"];
        }
        return ($sum);
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $campaign_id
 * @return bool|int
 */
function total_project_count_by_campaign($company_id, $campaign_id){
    $link=dbConn();
    $handle=$link->prepare("select count(p.project_id) as num_projects from project p join campaign c on p.campaign_id = c.campaign_id where c.campaign_id = :campaignId and c.company_id = :companyId");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":campaignId",$campaign_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["num_projects"];
        else return 0;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $spend_id
 * @return array|bool
 */
function get_spend_month_percentage($spend_id){
    $link=dbConn();
    $handle=$link->prepare("SELECT * from spend_percent where spend_id  = :spendId order by spend_month desc");
    $handle->bindValue(":spendId",$spend_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return ($result);
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_spend_month($spend_percent_id, $spend_percent){
    $dbConnection = dbConn();
    $sql = "update spend_percent set spend_percent = :spend_percent where spend_percent_id = :spend_percent_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':spend_percent_id', $spend_percent_id);
    $stmt->bindParam(':spend_percent', $spend_percent);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $spend_id
 * @return array|bool|int
 */
function get_max_spend_percent($spend_id){
    $link=dbConn();
    $handle=$link->prepare("select spend_percent_id, spend_month, spend_percent from spend_percent where spend_id = :spendId and spend_month = (select MAX(spend_month) from spend_percent where spend_id = :spendId2)");
    $handle->bindValue(":spendId",$spend_id,PDO::PARAM_INT);
    $handle->bindValue(":spendId2",$spend_id,PDO::PARAM_INT);
    try{
        $handle->execute();
        $result=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result[0]["spend_percent_id"])) $result=0;
        return $result;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_approval_file_id($schedule_task_id, $file_id){
    $dbConnection = dbConn();
    $sql = "update schedule_task set approval_file_id = :file_id where schedule_task_id = :schedule_task_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_task_id', $schedule_task_id);
    $stmt->bindParam(':file_id', $file_id);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function  insert_approval_log($schedule_task_id, $approver_id, $event, $approver_notes, $approval_file_id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO approval_log (schedule_task_id, approver_id, event_date, event, approver_notes, approval_file_id) values (:schedule_task_id, :approver_id, now(), :event, :approver_notes, :approval_file_id)");
    $stmt->bindParam(':schedule_task_id', $schedule_task_id);
    $stmt->bindParam(':approver_id', $approver_id);
    $stmt->bindParam(':event', $event);
    $stmt->bindParam(':approver_notes', $approver_notes);
    $stmt->bindParam(':approval_file_id', $approval_file_id);
    try{
        $stmt->execute();
        $approval_log_id = $dbConnection->lastInsertId('approval_log');
        return $approval_log_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        //print $e;
        return 0;
    }
}
/**
 * @param $schedule_task_id
 * @return array|bool|int
 */
function get_approval_history($schedule_task_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT al.*, pf.project_file_name, pf.file_type from approval_log al left join project_file pf on al.approval_file_id = pf.project_file_id where al.schedule_task_id  = :scheduleTaskId order by al.event_date desc");
    $handle->bindValue(":scheduleTaskId", $schedule_task_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result = 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $schedule_template_tasks_id
 * @return array|bool|int
 */
function get_schedule_template_task_info($schedule_template_tasks_id){
    $link = dbConn();
    $handle = $link->prepare("select stt.* from schedule_template_tasks stt where stt.schedule_template_tasks_id = :scheduleTemplateTaskId" );
    $handle->bindValue(":scheduleTemplateTaskId", $schedule_template_tasks_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result = 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_schedule_template_task($schedule_template_tasks_id, $task_id, $manager_role_id, $assignee_role_id, $start_day, $end_day, $total_time, $predecessor){
    $dbConnection = dbConn();
    $sql = "UPDATE schedule_template_tasks set task_id = :task_id, manager_role_id = :manager_role_id, assignee_role_id = :assignee_role_id, start_day = :start_day, end_day = :end_day, total_time = :total_time, predecessor = :predecessor where schedule_template_tasks_id = :schedule_template_tasks_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':schedule_template_tasks_id', $schedule_template_tasks_id);
    $stmt->bindParam(':task_id', $task_id);
    $stmt->bindParam(':manager_role_id', $manager_role_id);
    $stmt->bindParam(':assignee_role_id', $assignee_role_id);
    $stmt->bindParam(':start_day', $start_day);
    $stmt->bindParam(':end_day', $end_day);
    $stmt->bindParam(':total_time', $total_time);
    $stmt->bindParam(':predecessor', $predecessor);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $schedule_task_id
 * @param $user_id
 * @return int
 */
function check_schedule_task_assignee($schedule_task_id, $user_id){
    $link = dbConn();
    $handle = $link->prepare("select * from schedule_task_assignee where schedule_task_id = :scheduleTaskId and user_id = :userId");
    $handle->bindValue(":scheduleTaskId", $schedule_task_id, PDO::PARAM_INT);
    $handle->bindValue(":userId", $user_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        else return 1;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @return array|bool|int
 */
function get_spend_months($company_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT distinct sp.spend_month from spend_percent sp join spend s on sp.spend_id = s.spend_id join project p on s.project_id = p.project_id
                              join campaign c on p.campaign_id = c.campaign_id where c.company_id = :companyId order by spend_month desc");
    $handle->bindValue(":companyId", $company_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $campaign_id
 * @param $spend_month
 * @param $month_name
 * @return array|bool|int
 */
function get_spend_report($company_id, $campaign_id, $spend_month, $month_name){
    $campaign_string =(!empty($campaign_id))?" and c.campaign_id = :campaignId":"";
    $link = dbConn();
    $handle = $link->prepare("select s.spend_id, bu.business_unit_name, p.project_code, p.project_name, v.vendor_name, v.vendor_id, s.vendor_other, p.media_budget, p.production_budget,
                              (p.media_budget + p.production_budget) as total_project_cost, sp.spend_month, sp.spend_percent, s.spend_amount, (s.spend_amount * (sp.spend_percent/100)) as accrue_amount,
                              s.cost_expense_account, s.po_number, s.invoice_number, a.asset_name, s.spend_notes, s.posted from spend_percent sp join spend s on sp.spend_id = s.spend_id
                              join project p on s.project_id = p.project_id join campaign c on p.campaign_id = c.campaign_id join business_unit bu on c.business_unit_id = bu.business_unit_id
                              left join vendor v on s.vendor_id = v.vendor_id left join asset a on s.asset_id = a.asset_id
                              where sp.spend_month = :spendMonth and c.company_id = :companyId " . $campaign_string);
    $handle->bindValue(":companyId", $company_id, PDO::PARAM_INT);
    if (!empty($campaign_id)) $handle->bindValue(":campaignId", $campaign_id, PDO::PARAM_INT);
    $handle->bindValue(":spendMonth", $spend_month, PDO::PARAM_STR);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $campaign_id
 * @param $start_date
 * @param $end_date
 * @return array|bool|int
 */
function get_spend_report_for_range($company_id, $campaign_id, $start_date, $end_date){
    $campaign_string =(!empty($campaign_id))?" and c.campaign_id = :campaignId":"";
    $link = dbConn();
    $handle = $link->prepare("select s.spend_id, bu.business_unit_name, p.project_code, p.project_name, v.vendor_name, v.vendor_id, s.vendor_other, p.media_budget, p.production_budget,
                              (p.media_budget + p.production_budget) as total_project_cost, sp.spend_month, sp.spend_percent, s.spend_amount, (s.spend_amount * (sp.spend_percent/100)) as accrue_amount,
                               s.cost_expense_account, s.po_number, s.invoice_number, a.asset_name, s.spend_notes, s.posted from spend_percent sp join spend s on sp.spend_id = s.spend_id
                               join project p on s.project_id = p.project_id join campaign c on p.campaign_id = c.campaign_id join business_unit bu on c.business_unit_id = bu.business_unit_id
                               left join vendor v on s.vendor_id = v.vendor_id left join asset a on s.asset_id = a.asset_id where sp.spend_month >= :startDate and sp.spend_month <= :endDate
                               and c.company_id = :companyId" . $campaign_string . " order by p.project_code, sp.spend_id, sp.spend_month");
    $handle->bindValue(":companyId", $company_id, PDO::PARAM_INT);
    if (!empty($campaign_id)) $handle->bindValue(":campaignId", $campaign_id, PDO::PARAM_INT);
    $handle->bindValue(":startDate", $start_date, PDO::PARAM_STR);
    $handle->bindValue(":endDate", $end_date, PDO::PARAM_STR);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $product_id
 * @return array|bool|int
 */
function get_product_info($product_id){
    $link = dbConn();
    $handle = $link->prepare("select p.* from product p where p.product_id =:productId");
    $handle->bindValue(":productId", $product_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_product($product_id, $product_name, $active){
    $dbConnection = dbConn();
    $sql = "update product set product_name = :product_name, active = :active where product_id = :product_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':product_name', $product_name);
    $stmt->bindParam(':active', $active);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        //print $e;
        return 0;
    }
}
function add_product($company_id, $product_name){
    $dbConnection = dbConn();
    $sql = "insert into product (company_id, product_name, active) values (:company_id, :product_name, 1)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':product_name', $product_name);
    try{
        $stmt->execute();
        $product_id = $dbConnection->lastInsertId('product_id');
        return $product_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        //print $e;
        return 0;
    }

}
function activate_product($product_id, $active){
    $dbConnection = dbConn();
    if ($active == 2){
        $active = 0;
    }
    $sql = "update product set active = :active where product_id = :product_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':active', $active);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function update_file_notes($project_file_id, $file_notes){
    $dbConnection = dbConn();
    $sql = "UPDATE project_file set file_notes = :file_notes where project_file_id = :project_file_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':project_file_id', $project_file_id);
    $stmt->bindParam(':file_notes', $file_notes);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $company_id
 * @return array|bool|int
 */
function get_production_status_report($company_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT p.project_id, p.project_code, p.project_name, pr.product_name, ps.project_status_name, p.end_date, u.last_name as pm_last_name, '' as notes FROM project p
                              join product pr on p.product_id = pr.product_id join user u on p.project_manager_id = u.user_id join project_status ps on p.project_status_id = ps.project_status_id
                              join campaign c on p.campaign_id = c.campaign_id where p.active = 1 and c.company_id =:companyId order by p.project_manager_id asc, p.project_code asc");
    $handle->bindValue(":companyId", $company_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @return array|bool|int
 */
function get_vendor_other_id($company_id){
    $link = dbConn();
    $handle = $link->prepare("select vendor_id from vendor where vendor_name = '_Other' and company_id = :companyId");
    $handle->bindValue(":companyId", $company_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["vendor_id"];
        else return 0;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @return bool|int
 */
function get_aop_activity_type($company_id){
    $link = dbConn();
    $handle = $link->prepare("select aat.* from aop_activity_type aat where aat.company_id = :companyId");
    $handle->bindValue(":companyId", $company_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function move_one_schedule_task($schedule_task_id, $move_to){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("update schedule_task set display_order = :move_to where schedule_task_id = :schedule_task_id ");
    $stmt->bindParam(':schedule_task_id', $schedule_task_id);
    $stmt->bindParam(':move_to', $move_to);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $schedule_task_id
 * @return array|bool|int
 */
function get_schedule_task_display_order($schedule_task_id){
    $link = dbConn();
    $handle = $link->prepare("select display_order from schedule_task where schedule_task_id = :scheduleTaskId");
    $handle->bindValue(":scheduleTaskId", $schedule_task_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["display_order"];
        else return 0;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_spend_posted($spend_id, $update_value){
    $dbConnection = dbConn();
    $sql = "update spend set posted = :update_value where spend_id = :spend_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':spend_id', $spend_id);
    $stmt->bindParam(':update_value', $update_value);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        //print $e;
        return 0;
    }
}
/**
 * @param $spend_id
 * @param $month_year
 * @return bool|string
 */
function get_spend_percent_by_month($spend_id, $month_year){
    $link = dbConn();
    $handle = $link->prepare("select spend_percent from spend_percent where spend_id  = :spendId and spend_month = :monthYear");
    $handle->bindValue(":spendId", $spend_id, PDO::PARAM_INT);
    $handle->bindValue(":monthYear", $month_year, PDO::PARAM_STR);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return("n/a");
        else  return ($result[0]["spend_percent"]);
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $active_flag
 * @return bool|string
 */
function get_pif_asset_types($company_id, $active_flag){
    $active_string = ($active_flag == 1)?" and pat.active = 1":"";
    $link = dbConn();
    $handle = $link->prepare("select pat.* from pif_asset_type pat where pat.company_id = :companyId" . $active_string . " order by pat.display_order asc");
    $handle->bindValue(":companyId", $company_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function insert_pif($pif_project_name, $company_id, $version, $marketing_owner_id, $exec_sponsor_id, $business_unit_id, $product_id, $request_date, $desired_delivery_date, $target_in_market_date,
                    $expiration_date, $project_budget, $cost_code, $project_description, $uopx_benefit, $uopx_risk, $project_objective, $estimated_total_reach, $segment_reach_potential_students, $segment_reach_current_students, $segment_reach_employee, $segment_reach_faculty,
                    $segment_reach_alumni, $segment_reach_wfs, $segment_quantity_potential_students, $segment_quantity_current_students, $segment_quantity_employee, $segment_quantity_faculty, $segment_quantity_alumni, $segment_quantity_wfs, $requester_id, $orig_pif_id,
                    $background,$audience,$objectives,$core_message,$support_points,$aop_activity_type_id,$required_elem,$is_bm=0){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO pif (company_id, version, business_unit_id, product_id, pif_project_name, requester_id, exec_sponsor_id, marketing_owner_id, request_date, desired_delivery_date, target_in_market_date, expiration_date,
                                                    project_budget, cost_code, project_description, uopx_benefit, uopx_risk, project_objective, estimated_total_reach, segment_reach_potential_students, segment_reach_current_students, segment_reach_employee,
                                                    segment_reach_faculty, segment_reach_alumni, segment_reach_wfs, segment_quantity_potential_students, segment_quantity_current_students, segment_quantity_employee, segment_quantity_faculty, segment_quantity_alumni,
                                                    segment_quantity_wfs, created_date, pif_approval_status_id, orig_pif_id, background, audience, objectives, core_message, support_points,aop_activity_type_id,required_elem,is_bm) VALUES (:company_id, :version, :business_unit_id, :product_id,
                                                    :pif_project_name, :requester_id, :exec_sponsor_id, :marketing_owner_id, :request_date, :desired_delivery_date, :target_in_market_date, :expiration_date, :project_budget, :cost_code, :project_description, :uopx_benefit,
                                                    :uopx_risk, :project_objective, :estimated_total_reach, :segment_reach_potential_students, :segment_reach_current_students, :segment_reach_employee, :segment_reach_faculty, :segment_reach_alumni, :segment_reach_wfs,
                                                    :segment_quantity_potential_students, :segment_quantity_current_students, :segment_quantity_employee, :segment_quantity_faculty, :segment_quantity_alumni, :segment_quantity_wfs, now(), 1, :orig_pif_id,
                                                    :background, :audience, :objectives, :coreMessage, :supportPoints, :aop_activity_type_id, :required_elem,:is_bm)");
    $stmt->bindParam(':pif_project_name', $pif_project_name);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':version', $version);
    $stmt->bindParam(':marketing_owner_id', $marketing_owner_id);
    $stmt->bindParam(':exec_sponsor_id', $exec_sponsor_id);
    $stmt->bindParam(':business_unit_id', $business_unit_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':pif_project_name', $pif_project_name);
    $stmt->bindParam(':requester_id', $requester_id);
    $stmt->bindParam(':request_date', $request_date);
    $stmt->bindParam(':desired_delivery_date', $desired_delivery_date);
    $stmt->bindParam(':target_in_market_date', $target_in_market_date);
    $stmt->bindParam(':expiration_date', $expiration_date);
    $stmt->bindParam(':project_budget', $project_budget);
    $stmt->bindParam(':cost_code', $cost_code);
    $stmt->bindParam(':project_description', $project_description);
    $stmt->bindParam(':uopx_benefit', $uopx_benefit);
    $stmt->bindParam(':uopx_risk', $uopx_risk);
    $stmt->bindParam(':project_objective', $project_objective);
    $stmt->bindParam(':estimated_total_reach', $estimated_total_reach);
    $stmt->bindParam(':segment_reach_potential_students', $segment_reach_potential_students);
    $stmt->bindParam(':segment_reach_current_students', $segment_reach_current_students);
    $stmt->bindParam(':segment_reach_employee', $segment_reach_employee);
    $stmt->bindParam(':segment_reach_faculty', $segment_reach_faculty);
    $stmt->bindParam(':segment_reach_alumni', $segment_reach_alumni);
    $stmt->bindParam(':segment_reach_wfs', $segment_reach_wfs);
    $stmt->bindParam(':segment_quantity_potential_students', $segment_quantity_potential_students);
    $stmt->bindParam(':segment_quantity_current_students', $segment_quantity_current_students);
    $stmt->bindParam(':segment_quantity_employee', $segment_quantity_employee);
    $stmt->bindParam(':segment_quantity_faculty', $segment_quantity_faculty);
    $stmt->bindParam(':segment_quantity_alumni', $segment_quantity_alumni);
    $stmt->bindParam(':segment_quantity_wfs', $segment_quantity_wfs);
    $stmt->bindParam(':orig_pif_id', $orig_pif_id);
    $stmt->bindParam(':background', $background);
    $stmt->bindParam(':audience', $audience);
    $stmt->bindParam(':objectives', $objectives);
    $stmt->bindParam(':coreMessage', $core_message);
    $stmt->bindParam(':supportPoints', $support_points);
    $stmt->bindParam(':aop_activity_type_id', $aop_activity_type_id);
    $stmt->bindParam(':required_elem', $required_elem);
    $stmt->bindParam(':is_bm', $is_bm);
    try{
        $stmt->execute();
        $pif_id = $dbConnection->lastInsertId('pif_id');
        return $pif_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function update_pif_code($pif_id, $pif_code){
    $dbConnection = dbConn();
    $sql = "UPDATE pif set pif_code = :pif_code where pif_id = :pif_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':pif_id', $pif_id);
    $stmt->bindParam(':pif_code', $pif_code);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $business_unit_id
 * @return array|bool|int
 */
function get_business_unit_abbrev($business_unit_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT business_unit_abbrev from business_unit where business_unit_id = :businessUnitId");
    $handle->bindValue(":businessUnitId", $business_unit_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["business_unit_abbrev"];
        else return 0;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function insert_pif_asset($pif_id, $pif_asset_type_id, $asset_quantity, $asset_description){
    $dbConnection = dbConn();
    $sql = "insert into pif_asset (pif_id, pif_asset_type_id, asset_quantity, asset_description) values (:pif_id, :pif_asset_type_id, :asset_quantity, :asset_description)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':pif_id', $pif_id);
    $stmt->bindParam(':pif_asset_type_id', $pif_asset_type_id);
    $stmt->bindParam(':asset_quantity', $asset_quantity);
    $stmt->bindParam(':asset_description', $asset_description);
    try{
        $stmt->execute();
        $pif_asset_id = $dbConnection->lastInsertId('pif_asset_id');
        return $pif_asset_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        //print $e;
        return 0;
    }

}
function insert_pif_log($pif_id, $pif_log_notes, $approver_notes, $approver_id){
    $dbConnection = dbConn();
    $sql = "insert into pif_log (pif_id, pif_log_notes, pif_log_created, approver_notes, approver_id) values (:pif_id, :pif_log_notes, now(), :approver_notes, :approver_id)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':pif_id', $pif_id);
    $stmt->bindParam(':pif_log_notes', $pif_log_notes);
    $stmt->bindParam(':approver_notes', $approver_notes);
    $stmt->bindParam(':approver_id', $approver_id);
    try{
        $stmt->execute();
        $pif_log_id = $dbConnection->lastInsertId('pif_log_id');
        return $pif_log_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        //print $e;
        return 0;
    }

}
/**
 * @param $company_id
 * @param $pif_approval_status_id
 * @return array|bool|int
 */
function get_pifs($company_id, $pif_approval_status_id){
    $status_string = ($pif_approval_status_id == 100)?$status_string = " ":" and p.pif_approval_status_id = :pifApprovalStatusId";
    $link = dbConn();
    $handle = $link->prepare("select p.*, pas.pif_approval_status_name, u1.first_name as requester_first_name, u1.last_name as requester_last_name, aop.aop_activity_type_name
                              from pif p join pif_approval_status pas on p.pif_approval_status_id = pas.pif_approval_status_id join user u1 on p.requester_id = u1.user_id
                              left join aop_activity_type aop on p.aop_activity_type_id = aop.aop_activity_type_id where p.company_id = :companyId " . $status_string . " order by p.created_date asc");
    $handle->bindValue(":companyId", $company_id, PDO::PARAM_INT);
    $handle->bindValue(":pifApprovalStatusId", $pif_approval_status_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $pif_approval_status_id
 * @param $sortby
 * @param $ascdesc
 * @return array|bool|int
 */
function get_pifs_with_sort($company_id, $pif_approval_status_id, $sortby, $ascdesc){
    $status_string = ($pif_approval_status_id == 100)?" ":" and p.pif_approval_status_id = :pifApprovalStatusId";
    $link = dbConn();
    $handle = $link->prepare("select p.*, pas.pif_approval_status_name, u1.first_name as requester_first_name, u1.last_name as requester_last_name, u2.last_name as marketing_owner_last_name, aop.aop_activity_type_name
                              from pif p join pif_approval_status pas on p.pif_approval_status_id = pas.pif_approval_status_id join user u1 on p.requester_id = u1.user_id
                              join user u2 on p.marketing_owner_id = u2.user_id left join aop_activity_type aop on p.aop_activity_type_id = aop.aop_activity_type_id
                              where p.company_id = :companyId " . $status_string . " and p.is_bm <> 1 order by ".$sortby." ".$ascdesc);
    $handle->bindValue(":companyId", $company_id, PDO::PARAM_INT);
    if ($pif_approval_status_id != 100) $handle->bindValue(":pifApprovalStatusId", $pif_approval_status_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $pif_approval_status_id
 * @param $sortby
 * @param $ascdesc
 * @return array|bool|int
 */
function get_bm($company_id, $pif_approval_status_id, $sortby, $ascdesc){
    $status_string = ($pif_approval_status_id == 100)?" ":" and p.pif_approval_status_id = :pifApprovalStatusId";
    $link = dbConn();
    $handle = $link->prepare("select p.*, pas.pif_approval_status_name, u1.first_name as requester_first_name, u1.last_name as requester_last_name, u2.last_name as marketing_owner_last_name, aop.aop_activity_type_name
                              from pif p join pif_approval_status pas on p.pif_approval_status_id = pas.pif_approval_status_id join user u1 on p.requester_id = u1.user_id
                              join user u2 on p.marketing_owner_id = u2.user_id left join aop_activity_type aop on p.aop_activity_type_id = aop.aop_activity_type_id
                              where p.company_id = :companyId " . $status_string . " and p.is_bm = 1 order by ".$sortby." ".$ascdesc);
    $handle->bindValue(":companyId", $company_id, PDO::PARAM_INT);
    if ($pif_approval_status_id != 100) $handle->bindValue(":pifApprovalStatusId", $pif_approval_status_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @return array|bool|int
 */
function get_pif_status($company_id){
    $link = dbConn();
    $handle = $link->prepare("select pas.* from pif_approval_status pas where pas.company_id = :companyId order by pas.pif_approval_status_id asc");
    $handle->bindValue(":companyId", $company_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $pif_id
 * @return array|bool|int
 */
function get_pif_info($pif_id){
    $link = dbConn();
    $handle = $link->prepare("select p.*, u1.first_name as exec_sponsor_first_name, u1.last_name as exec_sponsor_last_name, u2.first_name as marketing_owner_first_name, u2.last_name
                              as marketing_owner_last_name, u3.first_name as requester_first_name, u3.last_name as requester_last_name, bu.business_unit_abbrev, bu.business_unit_name, pr.product_name,
                              at.aop_activity_type_name FROM pif p join user u1 on p.exec_sponsor_id = u1.user_id join user u2 on p.marketing_owner_id = u2.user_id
                              join business_unit bu on p.business_unit_id = bu.business_unit_id join product pr on p.product_id = pr.product_id join user u3 on p.requester_id = u3.user_id
                              left join aop_activity_type at on p.aop_activity_type_id = at.aop_activity_type_id WHERE pif_id = :pifId");
    $handle->bindValue(":pifId", $pif_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $pif_id
 * @return array|bool|int
 */
function get_pif_assets($pif_id){
    $link = dbConn();
    $handle = $link->prepare("select pa.*, pat.pif_asset_type_name, pat.asset_type_id, patg.pif_asset_type_group_name FROM pif_asset pa join pif_asset_type pat on pa.pif_asset_type_id = pat.pif_asset_type_id
                              join pif_asset_type_group patg on pat.pif_asset_type_group_id = patg.pif_asset_type_group_id WHERE pif_id = :pifId order by patg.pif_asset_type_group_id, pat.display_order asc");
    $handle->bindValue(":pifId", $pif_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) $result=0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_pif_status($pif_id, $pif_approval_status_id){
    $dbConnection = dbConn();
    $sql = "UPDATE pif set pif_approval_status_id = :pif_approval_status_id where pif_id = :pif_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':pif_id', $pif_id);
    $stmt->bindParam(':pif_approval_status_id', $pif_approval_status_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }

}
/**
 * @param $pif_approval_status_id
 * @return bool|int
 */
function get_status_name($pif_approval_status_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT pif_approval_status_name from pif_approval_status where pif_approval_status_id = :pifApprovalId");
    $handle->bindValue(":pifApprovalId", $pif_approval_status_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $tmp=$handle->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($tmp))return $tmp[0]["pif_approval_status_name"];
        else return 0;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $business_unit_id
 * @return bool|int
 */
function get_probable_campaign($business_unit_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT campaign_id from campaign where active = 1 and business_unit_id = :businessUnitId order by campaign_id desc limit 1");
    $handle->bindValue(":businessUnitId", $business_unit_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result[0]["campaign_id"];
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_pif_project_id($pif_id, $project_id){
    $dbConnection = dbConn();
    $sql = "UPDATE pif set project_id = :project_id where pif_id = :pif_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':pif_id', $pif_id);
    $stmt->bindParam(':project_id', $project_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $pif_id
 * @param $asset_type_id
 * @return bool|int
 */
function get_quantity_for_asset_type($pif_id, $asset_type_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT asset_quantity from pif_asset where pif_id = :pifId and pif_asset_type_id = :assetTypeId");
    $handle->bindValue(":pifId", $pif_id, PDO::PARAM_INT);
    $handle->bindValue(":assetTypeId", $asset_type_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result[0]["asset_quantity"];
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_orig_pif($pif_id){
    $dbConnection = dbConn();
    $sql = "UPDATE pif set orig_pif_id = pif_id where pif_id = :pif_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':pif_id', $pif_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $orig_pif_id
 * @return bool|int
 */
function get_pif_history($orig_pif_id){
    $link = dbConn();
    $handle = $link->prepare("select pl.*, u.first_name as approver_first_name, u.last_name as approver_last_name, u.initials as approver_initials from pif_log pl
                              join pif p on pl.pif_id = p.pif_id left join user u on pl.approver_id = u.user_id where p.orig_pif_id = :origPifId order by pif_log_created desc");
    $handle->bindValue(":origPifId", $orig_pif_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $user_id
 * @return bool|int
 */
function get_user_email($user_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT email from user where user_id = :userId");
    $handle->bindValue(":userId", $user_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result[0]["email"];
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $role_abbrev
 * @return array|bool|int
 */
function get_users_by_role_abbrev($company_id, $role_abbrev){
    $link = dbConn();
    $handle = $link->prepare("select u.*, r.role_abbrev, r.role_name from user u join role r on u.role_id = r.role_id
                              where r.company_id = :companyId and r.role_abbrev = :roleAbbrev order by u.first_name asc");
    $handle->bindValue(":companyId", $company_id, PDO::PARAM_INT);
    $handle->bindValue(":roleAbbrev", $role_abbrev, PDO::PARAM_STR);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_pif_approval_date($pif_id){
    $dbConnection = dbConn();
    $sql = "UPDATE pif set approval_date = now() where pif_id = :pif_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':pif_id', $pif_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function update_pif_aop_activity_id($pif_id, $aop_activity_type_id){
    $dbConnection = dbConn();
    $sql = "UPDATE pif set aop_activity_type_id = :aop_activity_type_id where pif_id = :pif_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':pif_id', $pif_id);
    $stmt->bindParam(':aop_activity_type_id', $aop_activity_type_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $company_id
 * @param $business_unit_id
 * @param $start_date
 * @param $end_date
 * @return array|bool|int
 */
function get_aop_type_pif_counts($company_id, $business_unit_id, $start_date, $end_date){
    $start_date = $new_date = date('Y-m-d', strtotime($start_date));
    $end_date = $new_date = date('Y-m-d', strtotime($end_date));
    $link = dbConn();
    $handle = $link->prepare("select count(pif_id) as num_pifs, aat.aop_activity_type_name from pif p left join aop_activity_type aat on p.aop_activity_type_id = aat.aop_activity_type_id
                              where p.created_date >= '" . $start_date . "' and p.created_date < '" . $end_date . "' and p.business_unit_id = :businessUnitId and pif_approval_status_id = 1 group by aat.aop_activity_type_name");
    $handle->bindValue(":businessUnitId", $business_unit_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $pif_asset_type_id
 * @param $aop_activity_type_id
 * @param $start_date
 * @param $end_date
 * @return bool|int
 */
function get_pif_asset_count($company_id, $pif_asset_type_id, $aop_activity_type_id, $start_date, $end_date){
    //convert dates to mysql
    $start_date = $new_date = date('Y-m-d', strtotime($start_date));
    $end_date = $new_date = date('Y-m-d', strtotime($end_date));
    $link = dbConn();
    $handle = $link->prepare("select pa.pif_asset_type_id, sum(pa.asset_quantity) as asset_count from pif_asset pa join pif p on pa.pif_id = p.pif_id where p.created_date >= '" . $start_date . "'
                                and p.created_date < '" . $end_date . "' and p.aop_activity_type_id = :aopActivityTypeId and pa.pif_asset_type_id = :pifAssetTypeId
                                and pif_approval_status_id = 1 group by pa.pif_asset_type_id");
    $handle->bindValue(":pifAssetTypeId", $pif_asset_type_id, PDO::PARAM_INT);
    $handle->bindValue(":aopActivityTypeId", $aop_activity_type_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result[0]["asset_count"];
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_last_login($user_id){
    $dbConnection = dbConn();
    $sql = "UPDATE user set last_login = now() where user_id = :user_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }

}
/**
 * @param $company_id
 * @param $start_month
 * @param $end_month
 * @return array|bool|int
 */
function get_project_aop_counts_by_month($company_id, $start_month, $end_month){
    $link = dbConn();
    $handle = $link->prepare("select aop.aop_activity_type_name, count(p.project_id) as num_projects from project p join aop_activity_type aop on p.aop_activity_type_id = aop.aop_activity_type_id
                              where p.created_date > :startMonth and p.created_date < :endMonth group by aop.aop_activity_type_name");
    $handle->bindValue(":startMonth", $start_month, PDO::PARAM_STR);
    $handle->bindValue(":endMonth", $end_month, PDO::PARAM_STR);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $start_month
 * @param $end_month
 * @return bool|int
 */
function get_number_of_cancelled_proejcts($company_id, $start_month , $end_month){
    $link = dbConn();
    $handle = $link->prepare("select ps.project_status_name, count(p.project_id) as num_projects from project p join project_status ps on p.project_status_id = ps.project_status_id
                              where p.created_date > :startMonth and p.created_date <  :endMonth and ps.project_status_name = 'Cancelled' group by ps.project_status_name");
    $handle->bindValue(":startMonth", $start_month, PDO::PARAM_STR);
    $handle->bindValue(":endMonth", $end_month, PDO::PARAM_STR);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result[0]["num_projects"];
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $start_month
 * @param $end_month
 * @return array|bool|int
 */
function get_project_counts_by_month($company_id, $start_month, $end_month){
    $link = dbConn();
    $handle = $link->prepare("select month(p.created_date) as month, year(p.created_date) as year, count(p.project_id) as num_projects from project p
                              join project_status ps on p.project_status_id = ps.project_status_id where p.created_date > :startMonth and p.created_date < :endMonth group by month(p.created_date) order by  year(p.created_date), month(p.created_date)");
    $handle->bindValue(":startMonth", $start_month, PDO::PARAM_STR);
    $handle->bindValue(":endMonth", $end_month, PDO::PARAM_STR);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $start_month
 * @param $end_month
 * @return array|bool|int
 */
function get_asset_counts_by_month_range($company_id, $start_month, $end_month){
    $link = dbConn();
    $handle = $link->prepare("select at.asset_type_name, count(a.asset_id) as num_assets, atc.asset_type_category_abbrev, atc.asset_type_category_name from asset a join project p on a.project_id = p.project_id
                              join asset_type at on a.asset_type_id = at.asset_type_id  left join asset_type_category atc on at.asset_type_category_id = atc.asset_type_category_id
                              where p.created_date > :startMonth and p.created_date < :endMonth group by a.asset_type_id order by at.asset_type_name");
    $handle->bindValue(":startMonth", $start_month, PDO::PARAM_STR);
    $handle->bindValue(":endMonth", $end_month, PDO::PARAM_STR);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function insert_user_group($company_id, $user_group_name, $created_by){
    $dbConnection = dbConn();
    $sql = "insert into user_group (company_id, user_group_name, created_by, created_date) values (:company_id, :user_group_name,  :created_by, now())";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':user_group_name', $user_group_name);
    $stmt->bindParam(':created_by', $created_by);
    try{
        $stmt->execute();
        $user_group_id = $dbConnection->lastInsertId('user_group_id');
        return $user_group_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $company_id
 * @return array|bool|int
 */
function get_user_groups($company_id){
    $link = dbConn();
    $handle = $link->prepare("select ug.* from user_group ug where company_id  = :companyId order by user_group_name asc");
    $handle->bindValue(":companyId", $company_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $user_group_id
 * @return array|bool|int
 */
function get_user_group_info($user_group_id){
    $link = dbConn();
    $handle = $link->prepare("select ug.* from user_group ug where ug.user_group_id = :userGorupId");
    $handle->bindValue(":userGroupId", $user_group_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_user_group($user_group_id, $user_group_name){
    $dbConnection = dbConn();
    $sql = "update user_group set user_group_name = :user_group_name where user_group_id = :user_group_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':user_group_id', $user_group_id);
    $stmt->bindParam(':user_group_name', $user_group_name);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $user_group_id
 * @return array|bool|int
 */
function get_user_group_members($user_group_id){
    $link = dbConn();
    $handle = $link->prepare("select ugm.*, u.first_name, u.last_name, u.active, r.role_name, r.role_abbrev from user_group_member ugm join user u on u.user_id = ugm.user_id join role r on u.role_id = r.role_id
                              where ugm.user_group_id = :userGroupId order by u.first_name asc");
    $handle->bindValue(":userGroupId", $user_group_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $user_group_id
 * @param $active
 * @return array|bool
 */
function get_user_group_members_and_active($user_group_id, $active){
    $active_string = "";
    if ($active == 1){
        $active_string = " and u.active = 1 ";
    }
    if ($active == 2){
        $active_string = " and u.active = 0 ";
    }
    $link = dbConn();
    $handle = $link->prepare("select ugm.*, u.first_name, u.last_name, u.active, r.role_name, r.role_abbrev from user_group_member ugm join user u on u.user_id = ugm.user_id join role r on u.role_id = r.role_id
                              where ugm.user_group_id = :userGroupId" . $active_string . " order by u.first_name asc");
    $handle->bindValue(":userGroupId", $user_group_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function delete_user_group_member($user_group_member_id){
    $dbConnection = dbConn();
    $sql = "delete from user_group_member where user_group_member_id = :user_group_member_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':user_group_member_id', $user_group_member_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $user_group_id
 * @param $company_id
 * @return array|bool|int
 */
function get_users_for_group($user_group_id, $company_id){
    $link = dbConn();
    $handle = $link->prepare("select u.*, r.role_abbrev, r.role_name from user u join role r on u.role_id = r.role_id where user_id not in (select user_id from user_group_member
                              where user_group_id = :userGroupId) and u.active = 1 and u.company_id = :companyId order by u.first_name asc");
    $handle->bindValue(":userGroupId", $user_group_id, PDO::PARAM_INT);
    $handle->bindValue(":companyId", $company_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function insert_user_group_member($user_group_id, $user_id){
    $dbConnection = dbConn();
    $sql = "insert into user_group_member (user_group_id, user_id) values (:user_group_id, :user_id)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':user_group_id', $user_group_id);
    $stmt->bindParam(':user_id', $user_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }

}
/**
 * @param $asset_id
 * @return array|bool|int
 */
function get_max_asset_item_num($asset_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT max(asset_item_num) as max_asset_item_num from asset_item where asset_id = :assetId");
    $handle->bindValue(":assetId", $asset_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result[0]["max_asset_item_num"];
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function  insert_asset_item($asset_id, $asset_item_code, $aps_product_id, $asset_item_num, $asset_item_in_market_date,  $asset_item_expiration_date, $asset_item_has_ge){
    $dbConnection = dbConn();
    $sql = "insert into asset_item (asset_id, asset_item_code, aps_product_id, asset_item_num, asset_item_in_market_date, asset_item_expiration_date, asset_item_has_ge) values (:asset_id, :asset_item_code, :aps_product_id, :asset_item_num, :asset_item_in_market_date, :asset_item_expiration_date, :asset_item_has_ge)";
    //print $sql;

    if($asset_item_in_market_date == "1969-12-31"){
        $asset_item_in_market_date = "";
    }
    if($asset_item_expiration_date == "1969-12-31"){
        $asset_item_expiration_date = "";
    }


    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_id', $asset_id);
    $stmt->bindParam(':asset_item_code', $asset_item_code);
    $stmt->bindParam(':aps_product_id', $aps_product_id);
    $stmt->bindParam(':asset_item_num', $asset_item_num);
    if (empty($asset_item_in_market_date)){
        $stmt->bindValue(':asset_item_in_market_date', null, PDO::PARAM_NULL);
    }else{
        $stmt->bindParam(':asset_item_in_market_date', $asset_item_in_market_date);
    }

    if (empty($asset_item_expiration_date)){
        $stmt->bindValue(':asset_item_expiration_date', null, PDO::PARAM_NULL);
    }else{
        $stmt->bindParam(':asset_item_expiration_date', $asset_item_expiration_date);
    }

    $stmt->bindParam(':asset_item_has_ge', $asset_item_has_ge);
    try{
        $stmt->execute();
        $asset_item_id = $dbConnection->lastInsertId('asset_item_id');
        return $asset_item_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $asset_id
 * @return array|bool|int
 */
function get_asset_items($asset_id){
    $link = dbConn();
    $handle = $link->prepare("select * from asset_item where asset_id = :assetId order by asset_item_num asc");
    $handle->bindValue(":assetId", $asset_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_aps_product_id($asset_item_id, $aps_product_id, $asset_item_name, $asset_item_has_ge, $asset_item_in_market_date, $asset_item_expiration_date){
    $dbConnection = dbConn();

    $sql = "UPDATE asset_item set aps_product_id = :aps_product_id, asset_item_name = :asset_item_name, asset_item_has_ge = :asset_item_has_ge, asset_item_in_market_date = :asset_item_in_market_date,  asset_item_expiration_date = :asset_item_expiration_date where asset_item_id = :asset_item_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':aps_product_id', $aps_product_id);
    $stmt->bindParam(':asset_item_id', $asset_item_id);
    $stmt->bindParam(':asset_item_name', $asset_item_name);
    $stmt->bindParam(':asset_item_has_ge', $asset_item_has_ge);
    if(empty($asset_item_in_market_date)){
        $stmt->bindValue(':asset_item_in_market_date', null, PDO::PARAM_INT);
    }else{
        $stmt->bindParam(':asset_item_in_market_date', $asset_item_in_market_date);
    }

    if(empty($asset_item_expiration_date)){
        $stmt->bindValue(':asset_item_expiration_date', null, PDO::PARAM_INT);
    }else{
        $stmt->bindParam(':asset_item_expiration_date', $asset_item_expiration_date);
    }


    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }

}
function delete_asset_item($asset_item_id){
    $dbConnection = dbConn();
    $sql = "delete from asset_item where asset_item_id = :asset_item_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_item_id', $asset_item_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }

}
/**
 * @param $asset_id
 * @return bool|int
 */
function get_asset_item_count($asset_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT count(*) as asset_item_count from asset_item where asset_id = :assetId");
    $handle->bindValue(":assetId", $asset_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result[0]["asset_item_count"];
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_asset_quantity($asset_id, $num_asset_items){
    $dbConnection = dbConn();
    $sql = "UPDATE asset set asset_quantity = :num_asset_items where asset_id = :asset_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_id', $asset_id);
    $stmt->bindParam(':num_asset_items', $num_asset_items);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }

}
/**
 * @param $company_id
 * @param $active_flag
 * @return array|bool|int
 */
function get_asset_attributes($company_id, $active_flag){
    $active_string = "";
    if ($active_flag == 1){
        $active_string = " and aa.active = 1";
    }
    if ($active_flag == 2){
        $active_string = " and aa.active = 0";
    }
    $link = dbConn();
    $handle = $link->prepare("select aa.* from asset_attribute aa where company_id = :companyId". $active_string . " order by aa.asset_attribute_name asc");
    $handle->bindValue(":companyId", $company_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function insert_asset_attribute($company_id, $asset_attribute_name, $display_type, $active){
    $dbConnection = dbConn();
    $sql = "insert into asset_attribute (company_id, asset_attribute_name, display_type, active) values (:company_id, :asset_attribute_name, :display_type, :active)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':asset_attribute_name', $asset_attribute_name);
    $stmt->bindParam(':display_type', $display_type);
    $stmt->bindParam(':active', $active);
    try{
        $stmt->execute();
        $asset_attribute_id = $dbConnection->lastInsertId('asset_attribute_id');
        return $asset_attribute_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function update_asset_attribute($asset_attribute_id, $asset_attribute_name, $display_type){
    $dbConnection = dbConn();
    $sql = "UPDATE asset_attribute set asset_attribute_name = :asset_attribute_name, display_type = :display_type where asset_attribute_id = :asset_attribute_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_attribute_id', $asset_attribute_id);
    $stmt->bindParam(':asset_attribute_name', $asset_attribute_name);
    $stmt->bindParam(':display_type', $display_type);
    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $asset_attribute_id
 * @param $active
 * @return array|bool|int
 */
function get_asset_attribute_choices($asset_attribute_id, $active){
    $link = dbConn();
    $handle = $link->prepare("select aac.* from asset_attribute_choice aac where asset_attribute_id = :assetAttrId and active = :active order by aac.display_order asc");
    $handle->bindValue(":assetAttrId", $asset_attribute_id, PDO::PARAM_INT);
    $handle->bindValue(":active", $active, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function  insert_asset_attribute_choice($asset_attribute_id, $asset_attribute_choice_name, $display_order, $active){
    $dbConnection = dbConn();
    $sql = "insert into asset_attribute_choice (asset_attribute_id, asset_attribute_choice_name, display_order, active) values (:asset_attribute_id, :asset_attribute_choice_name, :display_order, :active)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_attribute_id', $asset_attribute_id);
    $stmt->bindParam(':asset_attribute_choice_name', $asset_attribute_choice_name);
    $stmt->bindParam(':display_order', $display_order);
    $stmt->bindParam(':active', $active);
    try{
        $stmt->execute();
        $asset_attribute_choice_id = $dbConnection->lastInsertId('asset_attribute_choice_id');
        return $asset_attribute_choice_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function update_asset_attribute_choice_order($asset_attribute_id, $display_order, $new_value){
    $dbConnection = dbConn();
    $sql = "UPDATE asset_attribute_choice set display_order = :new_value where asset_attribute_id = :asset_attribute_id and display_order = :display_order";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_attribute_id', $asset_attribute_id);
    $stmt->bindParam(':display_order', $display_order);
    $stmt->bindParam(':new_value', $new_value);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function activate_asset_attribute_choice($asset_attribute_choice_id, $active, $display_order){
    $dbConnection = dbConn();
    $sql = "update asset_attribute_choice set active = " . $active . ", display_order = " . $display_order . " where asset_attribute_choice_id = :asset_attribute_choice_id";

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_attribute_choice_id', $asset_attribute_choice_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function move_asset_attribute_choices($asset_attribute_id, $display_order){
    $dbConnection = dbConn();
    $sql = "UPDATE asset_attribute_choice set display_order = (display_order -1) where asset_attribute_id = :asset_attribute_id and display_order > :display_order";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_attribute_id', $asset_attribute_id);
    $stmt->bindParam(':display_order', $display_order);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function activate_asset_attribute($asset_attribute_id, $active){
    $dbConnection = dbConn();
    $sql = "update asset_attribute set active = " . $active . " where asset_attribute_id = :asset_attribute_id";

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_attribute_id', $asset_attribute_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $asset_attribute_id
 * @return array|bool|int
 */
function get_asset_attribute_and_choices($asset_attribute_id){
    $link = dbConn();
    $handle = $link->prepare("select aa.asset_attribute_name, aa.display_type, aac.asset_attribute_choice_name from asset_attribute aa left outer
                              join asset_attribute_choice aac on aa.asset_attribute_id = aac.asset_attribute_id and aac.active = 1
                              where aa.asset_attribute_id = :assetAttrId order by aac.display_order");
    $handle->bindValue(":assetAttrId", $asset_attribute_id, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $active
 * @return array|bool|int
 */
function get_asset_type_templates($company_id, $active){
    if($active == 2){
        $active = 0;
    }
    $link = dbConn();
    $handle = $link->prepare("select att.* from asset_type_template att where company_id = :companyId and active = :active order by asset_type_template_name");
    $handle->bindValue(":companyId", $company_id, PDO::PARAM_INT);
    $handle->bindValue(":active", $active, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @return array|bool|int
 */
function get_states(){
    $link = dbConn();
    $handle = $link->prepare("select * from state order by state_name asc");
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_asset_item_states($asset_item_id, $state_list){
    $dbConnection = dbConn();
    $sql = "update asset_item set asset_item_states ='" . $state_list . "' where asset_item_id = :asset_item_id";
    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_item_id', $asset_item_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $company_id
 * @param $active
 * @return array|bool|int
 */
function get_asset_type_categories($company_id, $active){
    $link = dbConn();
    $handle = $link->prepare("select atc.* from asset_type_category atc where company_id = :companyId and active = :active order by asset_type_category_name");
    $handle->bindValue(":companyId", $company_id, PDO::PARAM_INT);
    $handle->bindValue(":active", $active, PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_asset_type($asset_type_id, $asset_type_name, $asset_type_category_id, $asset_type_template_id){
    $dbConnection = dbConn();
    $sql = "UPDATE asset_type set asset_type_name = :asset_type_name, asset_type_category_id = :asset_type_category_id,  asset_type_template_id = :asset_type_template_id where asset_type_id = :asset_type_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_type_id', $asset_type_id);
    $stmt->bindParam(':asset_type_name', $asset_type_name);
    $stmt->bindParam(':asset_type_category_id', $asset_type_category_id);
    $stmt->bindParam(':asset_type_template_id', $asset_type_template_id);

    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function insert_asset_type_category($company_id, $asset_type_category_name, $asset_type_category_abbrev, $active){
    $dbConnection = dbConn();

    $sql = "insert into asset_type_category (company_id, asset_type_category_name, asset_type_category_abbrev, active) values (:company_id, :asset_type_category_name, :asset_type_category_abbrev, :active)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':asset_type_category_name', $asset_type_category_name);
    $stmt->bindParam(':asset_type_category_abbrev', $asset_type_category_abbrev);
    $stmt->bindParam(':active', $active);
    try{
        $stmt->execute();
        $asset_type_category_id = $dbConnection->lastInsertId('asset_type_category_id');
        return $asset_type_category_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function activate_asset_type_category($asset_type_category_id, $active){
    $dbConnection = dbConn();
    $sql = "update asset_type_category set active = :active where asset_type_category_id = :asset_type_category_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_type_category_id', $asset_type_category_id);
    $stmt->bindParam(':active', $active);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function update_asset_type_category($asset_type_category_id, $asset_type_category_name, $asset_type_category_abbrev){
    $dbConnection = dbConn();
    $sql = "UPDATE asset_type_category set asset_type_category_name = :asset_type_category_name, asset_type_category_abbrev = :asset_type_category_abbrev where asset_type_category_id = :asset_type_category_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_type_category_id', $asset_type_category_id);
    $stmt->bindParam(':asset_type_category_name', $asset_type_category_name);
    $stmt->bindParam(':asset_type_category_abbrev', $asset_type_category_abbrev);

    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $campaign_id
 * @param $start_date
 * @param $end_date
 * @param $has_ge
 * @param $asset_type_category_id
 * @param $location_list
 * @return array|bool|int
 */
function get_asset_item_report_for_range($campaign_id, $start_date, $end_date, $has_ge, $asset_type_category_id, $location_list ){
    $campaign_string =!empty($campaign_id)?" and c.campaign_id =". $campaign_id:"";
    $ge_string = !empty($has_ge)? " and ai.asset_item_has_ge = 1 ":"";
    $asset_type_category_string = !empty($asset_type_category_id)?" and at.asset_type_category_id = " . $asset_type_category_id :"";
    if($location_list == "All"){
        $location_list = "";
    }
    $location_list_string = "";
    if($location_list == "All"){
        $location_list = "";
    }
    if(!empty($location_list)){
        $location_list_prep = str_replace(", ", "|", $location_list);
        $location_list_string = " and ai.asset_item_states REGEXP '" . $location_list_prep . "' ";
    }
    $link = dbConn();
    $handle = $link->prepare("SELECT ai.*, a.*, at.*, pf.project_file_name, pf.file_network_folder, p.project_code, p.project_id, c.campaign_id, c.business_unit_id, c.campaign_code, atc.asset_type_category_id, atc.asset_type_category_name, atc.asset_type_category_abbrev, bu.business_unit_name
	from asset_item ai
	join asset a on ai.asset_id = a.asset_id
	join asset_type at on a.asset_type_id = at.asset_type_id
	left join project_file pf on ai.asset_item_id = pf.asset_item_id
	left join project p on a.project_id = p.project_id
	left join campaign c on p.campaign_id = c.campaign_id
	left join business_unit bu on c.business_unit_id = bu.business_unit_id
	left join asset_type_category atc on at.asset_type_category_id = atc.asset_type_category_id
	where ai.asset_item_in_market_date >= '".$start_date."' and ai.asset_item_in_market_date <= '".$end_date."' " . $campaign_string . $ge_string . $asset_type_category_string . $location_list_string  . " order by a.asset_id");
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $asset_type_template_id
 * @return array|bool|int
 */
function get_asset_type_template_attributes($asset_type_template_id){
    $link = dbConn();
    $handle = $link->prepare("select atta.*, at.asset_attribute_name from asset_type_template_attribute atta join asset_attribute at on atta.asset_attribute_id = at.asset_attribute_id
                              where asset_type_template_id = :assetTypeTemplate");
    $handle->bindValue(":assetTypeTemplate",$asset_type_template_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_asset_type_template_attribute_position($asset_type_template_attribute_id, $x_offset, $y_offset){
    $dbConnection = dbConn();
    $sql = "UPDATE asset_type_template_attribute set x_offset = :x_offset, y_offset = :y_offset where asset_type_template_attribute_id = :asset_type_template_attribute_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_type_template_attribute_id', $asset_type_template_attribute_id);
    $stmt->bindParam(':x_offset', $x_offset);
    $stmt->bindParam(':y_offset', $y_offset);

    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $company_id
 * @param $asset_type_template_id
 * @return array|bool|int
 */
function get_unused_asset_attributes($company_id, $asset_type_template_id){
    $link = dbConn();
    $handle = $link->prepare("select aa.* from asset_attribute aa where asset_attribute_id not in (select asset_attribute_id from asset_type_template_attribute
                              where asset_type_template_id = :assetTypeTemplateId) and aa.company_id = :companyId order by asset_attribute_name");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":assetTypeTemplateId",$asset_type_template_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function insert_asset_type_template_attribute($asset_type_template_id, $asset_attribute_id, $include_attribute_name,  $x_offset,$y_offset){
    $dbConnection = dbConn();

    $sql = "insert into asset_type_template_attribute (asset_type_template_id, asset_attribute_id, include_attribute_name, x_offset, y_offset) values (:asset_type_template_id, :asset_attribute_id, :include_attribute_name, :x_offset, :y_offset)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_type_template_id', $asset_type_template_id);
    $stmt->bindParam(':asset_attribute_id', $asset_attribute_id);
    $stmt->bindParam(':include_attribute_name', $include_attribute_name);
    $stmt->bindParam(':x_offset', $x_offset);
    $stmt->bindParam(':y_offset', $y_offset);
    try{
        $stmt->execute();
        $asset_type_template_attribute_id = $dbConnection->lastInsertId('asset_type_template_attribute_id');
        return $asset_type_template_attribute_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function insert_asset_type_template_garnish($asset_type_template_id, $garnish_type, $garnish_text, $garnish_color, $garnish_font_size, $garnish_height, $garnish_width, $x_offset, $y_offset){
    $dbConnection = dbConn();

    $sql = "insert into asset_type_template_garnish (asset_type_template_id, garnish_type, garnish_text, garnish_color, garnish_font_size, garnish_height, garnish_width, x_offset, y_offset) values (:asset_type_template_id, :garnish_type, :garnish_text, :garnish_color, :garnish_font_size, :garnish_height, :garnish_width, :x_offset, :y_offset)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_type_template_id', $asset_type_template_id);
    $stmt->bindParam(':garnish_type', $garnish_type);
    $stmt->bindParam(':garnish_text', $garnish_text);
    $stmt->bindParam(':garnish_color', $garnish_color);
    $stmt->bindParam(':garnish_font_size', $garnish_font_size);
    $stmt->bindParam(':garnish_height', $garnish_height);
    $stmt->bindParam(':garnish_width', $garnish_width);
    $stmt->bindParam(':x_offset', $x_offset);
    $stmt->bindParam(':y_offset', $y_offset);
    try{
        $stmt->execute();
        $asset_type_template_garnish_id = $dbConnection->lastInsertId('asset_type_template_garnish_id');
        return $asset_type_template_garnish_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $asset_type_template_id
 * @return array|bool|int
 */
function get_asset_type_template_garnishes($asset_type_template_id){
    $link = dbConn();
    $handle = $link->prepare("select attg.* from asset_type_template_garnish attg where asset_type_template_id = :assetTypeTemplateId");
    $handle->bindValue(":assetTypeTemplateId",$asset_type_template_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_asset_type_template_garnish_position($asset_type_template_garnish_id, $x_offset, $y_offset){
    require_once "dbconn.php";
    $dbConnection = dbConn();
    $sql = "UPDATE asset_type_template_garnish set x_offset = :x_offset, y_offset = :y_offset where asset_type_template_garnish_id = :asset_type_template_garnish_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_type_template_garnish_id', $asset_type_template_garnish_id);
    $stmt->bindParam(':x_offset', $x_offset);
    $stmt->bindParam(':y_offset', $y_offset);

    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function delete_asset_type_template_attribute($asset_type_template_attribute_id){
    require_once "dbconn.php";
    $dbConnection = dbConn();
    $sql = "delete from asset_type_template_attribute where asset_type_template_attribute_id = :asset_type_template_attribute_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_type_template_attribute_id', $asset_type_template_attribute_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }

}
function delete_asset_type_template_garnish($asset_type_template_garnish_id){
    $dbConnection = dbConn();
    $sql = "delete from asset_type_template_garnish where asset_type_template_garnish_id = :asset_type_template_garnish_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_type_template_garnish_id', $asset_type_template_garnish_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }

}
/**
 * @param $asset_type_id
 * @return array|bool|int
 */
function get_asset_type_template_id($asset_type_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT asset_type_template_id from asset_type where asset_type_id  = :assetTypeId");
    $handle->bindValue(":assetTypeId",$asset_type_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result[0]["asset_type_template_id"];
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function replace_into_asset_item_attribute($asset_item_id, $asset_attribute_id, $asset_item_value){
    $dbConnection = dbConn();
    $sql = "replace into asset_item_attribute (asset_item_id, asset_attribute_id, asset_item_value) values (:asset_item_id, :asset_attribute_id, :asset_item_value)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_item_id', $asset_item_id);
    $stmt->bindParam(':asset_attribute_id', $asset_attribute_id);
    $stmt->bindParam(':asset_item_value', $asset_item_value);

    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        echo '<br>Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $asset_item_id
 * @return array|bool|int
 */
function get_asset_item_attributes($asset_item_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT * from asset_item_attribute where asset_item_id  = :assetItemId");
    $handle->bindValue(":assetItemId",$asset_item_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_empty_item_attribute_checkbox($asset_item_id, $asset_attribute_id){
    $dbConnection = dbConn();
    $sql = "UPDATE asset_item_attribute set asset_item_value = 0 where asset_item_id = :asset_item_id and asset_attribute_id = :asset_attribute_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_item_id', $asset_item_id);
    $stmt->bindParam(':asset_attribute_id', $asset_attribute_id);

    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function insert_asset_item_vendor($asset_item_id, $vendor_id, $delivery_method, $released_by, $released_what,  $release_date, $issue_date){
    $dbConnection = dbConn();

    $sql = "insert into asset_item_vendor (asset_item_id, vendor_id, delivery_method, released_by, released_what, release_date, issue_date) values (:asset_item_id, :vendor_id, :delivery_method, :released_by, :released_what, :release_date, :issue_date)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_item_id', $asset_item_id);
    $stmt->bindParam(':vendor_id', $vendor_id);
    $stmt->bindParam(':delivery_method', $delivery_method);
    $stmt->bindParam(':released_by', $released_by);
    $stmt->bindParam(':released_what', $released_what);
    $stmt->bindParam(':release_date', $release_date);
    $stmt->bindParam(':issue_date', $issue_date);
    try{
        $stmt->execute();
        $asset_item_vendor_id = $dbConnection->lastInsertId('asset_item_vendor_id');
        return $asset_item_vendor_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $asset_item_id
 * @return array|bool|int
 */
function get_asset_item_vendors($asset_item_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT aiv.*, v.vendor_name from asset_item_vendor aiv join vendor v on aiv.vendor_id = v.vendor_id where aiv.asset_item_id  = :assetItemId");
    $handle->bindValue(":assetItemId",$asset_item_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function delete_asset_item_vendor($asset_item_vendor_id){
    $dbConnection = dbConn();
    $sql = "delete from asset_item_vendor where asset_item_vendor_id = :asset_item_vendor_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_item_vendor_id', $asset_item_vendor_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $company_id
 * @param $selected_id
 * @return array|bool|int
 */
function get_print_colors($company_id, $selected_id){
    $link = dbConn();
    $handle = $link->prepare("select pc.* from print_color pc where pc.company_id = :companyId order by print_color_name");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function insert_asset_item_color($asset_item_id, $print_color_id, $coated, $process_or_spot, $ink_used_in, $tint, $notes){
    $dbConnection = dbConn();

    $sql = "insert into asset_item_color (asset_item_id, print_color_id, coated, process_or_spot, ink_used_in, tint, notes) values (:asset_item_id, :print_color_id, :coated, :process_or_spot, :ink_used_in, :tint, :notes)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_item_id', $asset_item_id);
    $stmt->bindParam(':print_color_id', $print_color_id);
    $stmt->bindParam(':coated', $coated);
    $stmt->bindParam(':process_or_spot', $process_or_spot);
    $stmt->bindParam(':ink_used_in', $ink_used_in);
    $stmt->bindParam(':tint', $tint);
    $stmt->bindParam(':notes', $notes);
    try{
        $stmt->execute();
        $asset_item_color_id = $dbConnection->lastInsertId('asset_item_color_id');
        return $asset_item_color_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $asset_item_id
 * @return array|bool|int
 */
function get_asset_item_colors($asset_item_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT aic.*, c.print_color_name from asset_item_color aic join print_color c on aic.print_color_id = c.print_color_id where aic.asset_item_id  = :assetItemId");
    $handle->bindValue(":assetItemId",$asset_item_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function delete_asset_item_color($asset_item_color_id){
    $dbConnection = dbConn();
    $sql = "delete from asset_item_color where asset_item_color_id = :asset_item_color_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_item_color_id', $asset_item_color_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function insert_asset_type_template($company_id, $asset_type_template_name){
    $dbConnection = dbConn();

    $sql = "insert into asset_type_template (company_id, asset_type_template_name, active) values (:company_id, :asset_type_template_name, 1)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':asset_type_template_name', $asset_type_template_name);
    try{
        $stmt->execute();
        $asset_type_template_id = $dbConnection->lastInsertId('asset_type_template_id');
        return $asset_type_template_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function activate_asset_type_template($asset_type_template_id, $active){
    $dbConnection = dbConn();
    $sql = "update asset_type_template set active = :active where asset_type_template_id = :asset_type_template_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_type_template_id', $asset_type_template_id);
    $stmt->bindParam(':active', $active);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $company_id
 * @param $active
 * @return array|bool|int
 */
function get_agencies($company_id, $active){
    $link = dbConn();
    $handle = $link->prepare("SELECT a.* from agency a where a.company_id  = :companyId and a.active = :active order by agency_name asc");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":active",$active,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function insert_model($model_name, $model_email, $model_address, $model_phone, $model_notes, $model_gender, $model_is_minor, $model_territory, $model_territory_other, $model_usage_category, $model_usage_category_other, $model_start_date, $model_end_date, $representation_type, $agency_id, $model_released, $duration_type, $media_rights, $media_rights_other, $user_id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO model (model_name, model_email, model_address, model_phone, model_notes, model_gender, model_is_minor, model_territory, model_territory_other, model_usage_category, model_usage_category_other, model_start_date, model_end_date, representation_type, agency_id, model_released, duration_type, media_rights, media_rights_other, created_by, created_date, active) VALUES (:model_name, :model_email, :model_address, :model_phone, :model_notes, :model_gender, :model_is_minor, :model_territory, :model_territory_other, :model_usage_category, :model_usage_category_other, :model_start_date, :model_end_date, :representation_type, :agency_id, :model_released, :duration_type, :media_rights, :media_rights_other, :user_id, now(),1)");
    $stmt->bindParam(':model_name', $model_name);
    $stmt->bindParam(':model_email', $model_email);
    $stmt->bindParam(':model_address', $model_address);
    $stmt->bindParam(':model_phone', $model_phone);
    $stmt->bindParam(':model_notes', $model_notes);
    $stmt->bindParam(':model_gender', $model_gender);
    $stmt->bindParam(':model_is_minor', $model_is_minor);
    $stmt->bindParam(':model_territory', $model_territory);
    $stmt->bindParam(':model_territory_other', $model_territory_other);
    $stmt->bindParam(':model_usage_category', $model_usage_category);
    $stmt->bindParam(':model_usage_category_other', $model_usage_category_other);
    $stmt->bindParam(':model_start_date', $model_start_date);
    $stmt->bindParam(':model_end_date', $model_end_date);
    $stmt->bindParam(':representation_type', $representation_type);
    $stmt->bindParam(':agency_id', $agency_id);
    $stmt->bindParam(':model_released', $model_released);
    $stmt->bindParam(':duration_type', $duration_type);
    $stmt->bindParam(':media_rights', $media_rights);
    $stmt->bindParam(':media_rights_other', $media_rights_other);
    $stmt->bindParam(':user_id', $user_id);
    try{
        $stmt->execute();
        $model_id = $dbConnection->lastInsertId('model_id');
        return $model_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        print "<br>";
        print_r($e);
        return 0;
    }
}
/**
 * @param $model_id
 * @return array|bool|int
 */
function get_model_info($model_id){
    $link = dbConn();
    $handle = $link->prepare("select m.* from model m where m.model_id = :modelId");
    $handle->bindValue(":modelId",$model_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function update_model($model_id, $model_name, $model_email, $model_address, $model_phone, $model_notes, $model_gender, $model_is_minor, $model_territory, $model_territory_other, $model_usage_category, $model_usage_category_other, $model_start_date, $model_end_date, $representation_type, $agency_id, $model_released, $duration_type, $media_rights, $media_rights_other){
    $dbConnection = dbConn();
    $sql = "UPDATE model set model_name = :model_name,  model_email = :model_email, model_address = :model_address, model_phone = :model_phone, model_notes = :model_notes, model_gender = :model_gender, model_is_minor = :model_is_minor, model_territory = :model_territory, model_territory_other = :model_territory_other, model_usage_category = :model_usage_category, model_usage_category_other = :model_usage_category_other, model_start_date = :model_start_date, model_end_date = :model_end_date, representation_type = :representation_type, agency_id = :agency_id, model_released = :model_released, duration_type = :duration_type, media_rights = :media_rights, media_rights_other = :media_rights_other where model_id = :model_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':model_id', $model_id);
    $stmt->bindParam(':model_name', $model_name);
    $stmt->bindParam(':model_email', $model_email);
    $stmt->bindParam(':model_address', $model_address);
    $stmt->bindParam(':model_phone', $model_phone);
    $stmt->bindParam(':model_notes', $model_notes);
    $stmt->bindParam(':model_gender', $model_gender);
    $stmt->bindParam(':model_is_minor', $model_is_minor);
    $stmt->bindParam(':model_territory', $model_territory);
    $stmt->bindParam(':model_territory_other', $model_territory_other);
    $stmt->bindParam(':model_usage_category', $model_usage_category);
    $stmt->bindParam(':model_usage_category_other', $model_usage_category_other);
    $stmt->bindParam(':model_start_date', $model_start_date);
    $stmt->bindParam(':model_end_date', $model_end_date);
    $stmt->bindParam(':representation_type', $representation_type);
    $stmt->bindParam(':agency_id', $agency_id);
    $stmt->bindParam(':model_released', $model_released);
    $stmt->bindParam(':duration_type', $duration_type);
    $stmt->bindParam(':media_rights', $media_rights);
    $stmt->bindParam(':media_rights_other', $media_rights_other);

    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @return array|bool|int
 */
function get_max_model_id(){
    $link = dbConn();
    $handle = $link->prepare("SELECT max(model_id) as max_model_id from model");
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result[0]["max_model_id"];
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $image_id
 * @return array|bool|int
 */
function get_image_info($image_id){
    $link = dbConn();
    $handle = $link->prepare("select i.* from image i where i.image_id = :imageId");
    $handle->bindParam(":imageId",$image_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @return bool|int
 */
function get_max_image_id(){
    $link = dbConn();
    $handle = $link->prepare("SELECT max(image_id) as max_image_id from image");
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result[0]["max_image_id"];
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function insert_image($width, $height, $file_size, $resolution, $stock_ref_code, $image_stock_name, $stock_quote_id, $stock_or_photographer, $rep_or_stock_house, $photographer_name, $rights_managed_type, $royalty_free_type, $image_media_rights, $image_media_rights_other, $image_notes, $image_usage_start, $image_usage_end, $unlimited_usage, $image_territory, $image_territory_other, $release_received, $release_type, $image_exclusivity, $exclusivity_notes, $image_usage_category, $image_usage_category_other, $original_project_code, $original_project_manager, $original_art_buyer, $posting_to_asset_library, $high_resolution_location, $low_resolution_location, $image_needs_retouching, $image_has_been_replaced, $user_id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO image (width, height, file_size, resolution, stock_ref_code, image_stock_name, stock_quote_id, stock_or_photographer, rep_or_stock_house, photographer_name, rights_managed_type, royalty_free_type, image_media_rights, image_media_rights_other, image_notes, image_usage_start, image_usage_end, unlimited_usage, image_territory, image_territory_other, release_received, release_type, image_exclusivity, exclusivity_notes, image_usage_category, image_usage_category_other, original_project_code, original_project_manager, original_art_buyer, posting_to_asset_library, high_resolution_location, low_resolution_location, image_needs_retouching, image_has_been_replaced, created_by, created_date, active) VALUES (:width, :height, :file_size, :resolution, :stock_ref_code, :image_stock_name, :stock_quote_id, :stock_or_photographer, :rep_or_stock_house, :photographer_name, :rights_managed_type, :royalty_free_type, :image_media_rights, :image_media_rights_other, :image_notes, :image_usage_start, :image_usage_end, :unlimited_usage, :image_territory, :image_territory_other, :release_received, :release_type, :image_exclusivity, :exclusivity_notes, :image_usage_category, :image_usage_category_other, :original_project_code, :original_project_manager, :original_art_buyer, :posting_to_asset_library, :high_resolution_location, :low_resolution_location, :image_needs_retouching, :image_has_been_replaced, :user_id, now(),1)");
    $stmt->bindParam(':width', $width);
    $stmt->bindParam(':height', $height);
    $stmt->bindParam(':file_size', $file_size);
    $stmt->bindParam(':resolution', $resolution);
    $stmt->bindParam(':stock_ref_code', $stock_ref_code);
    $stmt->bindParam(':image_stock_name', $image_stock_name);
    $stmt->bindParam(':stock_quote_id', $stock_quote_id);
    $stmt->bindParam(':stock_or_photographer', $stock_or_photographer);
    $stmt->bindParam(':rep_or_stock_house', $rep_or_stock_house);
    $stmt->bindParam(':photographer_name', $photographer_name);
    $stmt->bindParam(':rights_managed_type', $rights_managed_type);
    $stmt->bindParam(':royalty_free_type', $royalty_free_type);
    $stmt->bindParam(':image_media_rights', $image_media_rights);
    $stmt->bindParam(':image_media_rights_other', $image_media_rights_other);
    $stmt->bindParam(':image_notes', $image_notes);
    $stmt->bindParam(':image_usage_start', $image_usage_start);
    $stmt->bindParam(':image_usage_end', $image_usage_end);
    $stmt->bindParam(':unlimited_usage', $unlimited_usage);
    $stmt->bindParam(':image_territory', $image_territory);
    $stmt->bindParam(':image_territory_other', $image_territory_other);
    $stmt->bindParam(':release_received', $release_received);
    $stmt->bindParam(':release_type', $release_type);
    $stmt->bindParam(':image_exclusivity', $image_exclusivity);
    $stmt->bindParam(':exclusivity_notes', $exclusivity_notes);
    $stmt->bindParam(':image_usage_category', $image_usage_category);
    $stmt->bindParam(':image_usage_category_other', $image_usage_category_other);
    $stmt->bindParam(':original_project_code', $original_project_code);
    $stmt->bindParam(':original_project_manager', $original_project_manager);
    $stmt->bindParam(':original_art_buyer', $original_art_buyer);
    $stmt->bindParam(':posting_to_asset_library', $posting_to_asset_library);
    $stmt->bindParam(':high_resolution_location', $high_resolution_location);
    $stmt->bindParam(':low_resolution_location', $low_resolution_location);
    $stmt->bindParam(':image_needs_retouching', $image_needs_retouching);
    $stmt->bindParam(':image_has_been_replaced', $image_has_been_replaced);
    $stmt->bindParam(':user_id', $user_id);

    try{
        $stmt->execute();
        $model_id = $dbConnection->lastInsertId('model_id');
        return $model_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        print "<br>";
        print_r($e);
        return 0;
    }
}
function update_image($image_id, $width, $height, $file_size, $resolution, $stock_ref_code, $image_stock_name, $stock_quote_id, $stock_or_photographer, $rep_or_stock_house, $photographer_name, $rights_managed_type, $royalty_free_type, $image_media_rights, $image_media_rights_other, $image_notes, $image_usage_start, $image_usage_end, $unlimited_usage, $image_territory, $image_territory_other, $release_received, $release_type, $image_exclusivity, $exclusivity_notes, $image_usage_category, $image_usage_category_other, $original_project_code, $original_project_manager, $original_art_buyer, $posting_to_asset_library, $high_resolution_location, $low_resolution_location, $image_needs_retouching, $image_has_been_replaced, $active){
    $dbConnection = dbConn();
    $sql = "UPDATE image set width = :width,  height = :height, file_size = :file_size, resolution = :resolution, stock_ref_code = :stock_ref_code, image_stock_name = :image_stock_name, stock_quote_id = :stock_quote_id, stock_or_photographer = :stock_or_photographer, rep_or_stock_house = :rep_or_stock_house, photographer_name = :photographer_name, rights_managed_type = :rights_managed_type, royalty_free_type = :royalty_free_type, image_media_rights = :image_media_rights, image_media_rights_other = :image_media_rights_other, image_notes = :image_notes, image_usage_start = :image_usage_start, image_usage_end = :image_usage_end, unlimited_usage = :unlimited_usage, image_territory = :image_territory, image_territory_other = :image_territory_other, release_received = :release_received, release_type = :release_type, image_exclusivity = :image_exclusivity, exclusivity_notes = :exclusivity_notes, image_usage_category = :image_usage_category, image_usage_category_other = :image_usage_category_other, original_project_code = :original_project_code, original_project_manager = :original_project_manager, original_art_buyer = :original_art_buyer, posting_to_asset_library = :posting_to_asset_library, high_resolution_location = :high_resolution_location, low_resolution_location = :low_resolution_location, image_needs_retouching = :image_needs_retouching, image_has_been_replaced = :image_has_been_replaced, active = :active where image_id = :image_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':image_id', $image_id);
    $stmt->bindParam(':width', $width);
    $stmt->bindParam(':height', $height);
    $stmt->bindParam(':file_size', $file_size);
    $stmt->bindParam(':resolution', $resolution);
    $stmt->bindParam(':stock_ref_code', $stock_ref_code);
    $stmt->bindParam(':image_stock_name', $image_stock_name);
    $stmt->bindParam(':stock_quote_id', $stock_quote_id);
    $stmt->bindParam(':stock_or_photographer', $stock_or_photographer);
    $stmt->bindParam(':rep_or_stock_house', $rep_or_stock_house);
    $stmt->bindParam(':photographer_name', $photographer_name);
    $stmt->bindParam(':rights_managed_type', $rights_managed_type);
    $stmt->bindParam(':royalty_free_type', $royalty_free_type);
    $stmt->bindParam(':image_media_rights', $image_media_rights);
    $stmt->bindParam(':image_media_rights_other', $image_media_rights_other);
    $stmt->bindParam(':image_notes', $image_notes);
    $stmt->bindParam(':image_usage_start', $image_usage_start);
    $stmt->bindParam(':image_usage_end', $image_usage_end);
    $stmt->bindParam(':unlimited_usage', $unlimited_usage);
    $stmt->bindParam(':image_territory', $image_territory);
    $stmt->bindParam(':image_territory_other', $image_territory_other);
    $stmt->bindParam(':release_received', $release_received);
    $stmt->bindParam(':release_type', $release_type);
    $stmt->bindParam(':image_exclusivity', $image_exclusivity);
    $stmt->bindParam(':exclusivity_notes', $exclusivity_notes);
    $stmt->bindParam(':image_usage_category', $image_usage_category);
    $stmt->bindParam(':image_usage_category_other', $image_usage_category_other);
    $stmt->bindParam(':original_project_code', $original_project_code);
    $stmt->bindParam(':original_project_manager', $original_project_manager);
    $stmt->bindParam(':original_art_buyer', $original_art_buyer);
    $stmt->bindParam(':posting_to_asset_library', $posting_to_asset_library);
    $stmt->bindParam(':high_resolution_location', $high_resolution_location);
    $stmt->bindParam(':low_resolution_location', $low_resolution_location);
    $stmt->bindParam(':image_needs_retouching', $image_needs_retouching);
    $stmt->bindParam(':image_has_been_replaced', $image_has_been_replaced);
    $stmt->bindParam(':active', $active);

    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $project_id
 * @return bool|int
 */
function get_max_pif_for_project($project_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT max(pif_id) as pif_id, pif_code from pif where project_id = :projectId");
    $handle->bindValue(":projectId",$project_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $meta_data_category_id
 * @param $active
 * @return array|bool|int
 */
function get_meta_data_list_by_category($company_id, $meta_data_category_id, $active){
    $link = dbConn();
    $handle = $link->prepare("select md.* from meta_data md where company_id = :companyId and meta_data_category_id = :metaDataCategoryId and active = :active");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":metaDataCategoryId",$meta_data_category_id,PDO::PARAM_INT);
    $handle->bindValue(":active",$active,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $image_id
 * @return array|bool|int
 */
function get_meta_data_for_image($image_id){
    $link = dbConn();
    $handle = $link->prepare("select meta_data_id from image_meta_data where image_id = :imageId");
    $handle->bindValue(":imageId",$image_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function add_meta_data_to_image($image_id, $meta_data_id){
    $dbConnection = dbConn();

    $sql = "insert into image_meta_data (image_id, meta_data_id) values (:image_id, :meta_data_id)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':image_id', $image_id);
    $stmt->bindParam(':meta_data_id', $meta_data_id);
    try{
        $stmt->execute();
        $image_meta_data_id = $dbConnection->lastInsertId('image_meta_data_id');
        return $image_meta_data_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function del_image_meta_data($image_id, $meta_data_id){
    $dbConnection = dbConn();
    $sql = "delete from image_meta_data where image_id = :image_id and meta_data_id = :meta_data_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':image_id', $image_id);
    $stmt->bindParam(':meta_data_id', $meta_data_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $image_id
 * @return array|bool|int
 */
function get_models_for_image($image_id){
    $link = dbConn();
    $handle = $link->prepare("select im.*, m.model_name from image_model im join model m on im.model_id = m.model_id where im.image_id = :imageId order by m.model_name");
    $handle->bindValue(":imageId",$image_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function delete_image_model($image_model_id){
    require_once "dbconn.php";
    $dbConnection = dbConn();
    $sql = "delete from image_model where image_model_id = :image_model_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':image_model_id', $image_model_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function get_unused_models_for_image($image_id){
    db_connect();
    $active_string = "";
    $query="select m.model_id, m.model_name from model m where m.model_id not in (select model_id from image_model where image_id = " . $image_id . ") order by m.model_name";
    //print $query . "<br>";
    $arr = get_aa_for_query( $query );
    return $arr;
}
function insert_image_model($image_id, $model_id){
    $dbConnection = dbConn();

    $sql = "insert into image_model (image_id, model_id) values (:image_id, :model_id)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':image_id', $image_id);
    $stmt->bindParam(':model_id', $model_id);
    try{
        $stmt->execute();
        $image_model_id = $dbConnection->lastInsertId('image_model_id');
        return $image_model_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
/**
 * @param $asset_item_id
 * @return array|bool|int
 */
function get_asset_item_images($asset_item_id){
    $link = dbConn();
    $handle = $link->prepare("select aii.* from asset_item_image aii where asset_item_id = :assetItemId order by aii.image_id asc");
    $handle->bindValue(":assetItemId",$asset_item_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function insert_asset_item_image($asset_item_id, $image_id){
    require_once "dbconn.php";
    $dbConnection = dbConn();

    $sql = "insert into asset_item_image (asset_item_id, image_id) values (:asset_item_id, :image_id)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_item_id', $asset_item_id);
    $stmt->bindParam(':image_id', $image_id);
    try{
        $stmt->execute();
        $asset_item_image_id = $dbConnection->lastInsertId('asset_item_image_id');
        return $asset_item_image_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function delete_asset_item_image($asset_item_image_id){
    $dbConnection = dbConn();
    $sql = "delete from asset_item_image where asset_item_image_id = :asset_item_image_id";
    //print $sql . "-" . $asset_item_image_id;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':asset_item_image_id', $asset_item_image_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }

}
function get_project_info_by_project_code($project_code){
    $link = dbConn();
    $handle = $link->prepare("select p.* from project p where project_code = :projectCode");
    $handle->bindValue(":projectCode",$project_code,PDO::PARAM_STR);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function delete_pif_ranks($company_id){
    $dbConnection = dbConn();
    $sql = "update pif set pif_rank = NULL where pif.company_id = :company_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function update_pif_rank($pif_id, $pif_rank){
    $dbConnection = dbConn();
    $sql = "update pif set pif_rank = :pif_rank where pif_id = :pif_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':pif_id', $pif_id);
    $stmt->bindParam(':pif_rank', $pif_rank);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function update_pif_rank2($company_id, $pif_rank_old, $pif_rank_new){
    $dbConnection = dbConn();
    $sql = "update pif set pif_rank = :pif_rank_new where pif_rank = :pif_rank_old and company_id = :company_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':pif_rank_old', $pif_rank_old);
    $stmt->bindParam(':pif_rank_new', $pif_rank_new);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $company_id
 * @param $active_flag
 * @return array|bool|int
 */
function get_wif_types($company_id, $active_flag){
    $active_string = "";
    if ($active_flag == 1){
        $active_string = " and wt.active = 1";
    }
    if ($active_flag == 2){
        $active_string = " and wt.active = 0";
    }
    $link = dbConn();
    $handle = $link->prepare("select wt.*, at.asset_type_name, atc.asset_type_category_abbrev from wif_type wt left join asset_type at on wt.asset_type_id = at.asset_type_id
                              left join asset_type_category atc on at.asset_type_category_id = atc.asset_type_category_id where wt.company_id = :companyId" . $active_string . " order by wt.display_order");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $active_flag
 * @return array|bool|int
 */
function get_wif_types_web_only($company_id, $active_flag){
    $active_string = "";
    if ($active_flag == 1){
        $active_string = " and wt.active = 1";
    }
    if ($active_flag == 2){
        $active_string = " and wt.active = 0";
    }
    $link = dbConn();
    $handle = $link->prepare("select wt.* from wif_type wt where wt.company_id = :companyId" . $active_string . " and is_web_request = 9 order by wt.display_order");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function  insert_wif_type($company_id, $wif_type_name, $wif_type_abbrev, $wif_type_description, $display_order, $is_web_request, $asset_type_id){
    $dbConnection = dbConn();
    $sql = "insert into wif_type (company_id, wif_type_name, wif_type_abbrev, wif_type_description, display_order, is_web_request, asset_type_id, active) values (:company_id, :wif_type_name, :wif_type_abbrev, :wif_type_description, :display_order, :is_web_request, :asset_type_id, 1)";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':wif_type_name', $wif_type_name);
    $stmt->bindParam(':wif_type_abbrev', $wif_type_abbrev);
    $stmt->bindParam(':wif_type_description', $wif_type_description);
    $stmt->bindParam(':display_order', $display_order);
    $stmt->bindParam(':is_web_request', $is_web_request);
    $stmt->bindParam(':asset_type_id', $asset_type_id);
    try{
        $stmt->execute();
        $wif_type_id = $dbConnection->lastInsertId('wif_type_id');
        return $wif_type_id;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function update_wif_type($wif_type_id, $wif_type_name, $wif_type_abbrev, $wif_type_description, $display_order, $is_web_request, $asset_type_id){
    $dbConnection = dbConn();
    $sql = "UPDATE wif_type set wif_type_name = :wif_type_name, wif_type_abbrev = :wif_type_abbrev,  wif_type_description = :wif_type_description, display_order = :display_order, is_web_request = :is_web_request, asset_type_id = :asset_type_id where wif_type_id = :wif_type_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':wif_type_id', $wif_type_id);
    $stmt->bindParam(':wif_type_name', $wif_type_name);
    $stmt->bindParam(':wif_type_abbrev', $wif_type_abbrev);
    $stmt->bindParam(':wif_type_description', $wif_type_description);
    $stmt->bindParam(':display_order', $display_order);
    $stmt->bindParam(':is_web_request', $is_web_request);
    $stmt->bindParam(':asset_type_id', $asset_type_id);

    try{
        $stmt->execute();
        return 1;
    }catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function activate_wif_type($wif_type_id, $active){
    $dbConnection = dbConn();
    $sql = "update wif_type set active = :active where wif_type_id = :wif_type_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':wif_type_id', $wif_type_id);
    $stmt->bindParam(':active', $active);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
/**
 * @param $wif_type_id
 * @return array|bool|int
 */
function get_wif_type_abbrev($wif_type_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT wif_type_abbrev from wif_type where wif_type_id = :wifTypeId");
    $handle->bindValue(":wifTypeId",$wif_type_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result[0]["wif_type_abbrev"];
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
function insert_wif($wif_name, $company_id, $requester_name, $requester_email, $wif_type_id, $desired_delivery_date, $description){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO wif (company_id, wif_name, requester_name, requester_email, wif_type_id, desired_delivery_date, description, request_date, wif_status_id) VALUES (:company_id, :wif_name, :requester_name, :requester_email, :wif_type_id, :desired_delivery_date, :description, now(),1)");
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':wif_name', $wif_name);
    $stmt->bindParam(':requester_name', $requester_name);
    $stmt->bindParam(':requester_email', $requester_email);
    $stmt->bindParam(':wif_type_id', $wif_type_id);
    $stmt->bindParam(':desired_delivery_date', $desired_delivery_date);
    $stmt->bindParam(':description', $description);
    try{
        $stmt->execute();
        $wif_id = $dbConnection->lastInsertId('wif_id');
        return $wif_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function update_wif_code($wif_id, $wif_code){
    $dbConnection = dbConn();
    $sql = "UPDATE wif set wif_code = :wif_code where wif_id = :wif_id";
    //print $sql;

    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':wif_id', $wif_id);
    $stmt->bindParam(':wif_code', $wif_code);
    if ($stmt->execute() === FALSE) {
        return 0;
    }else{
        return 1;
    }
}
function insert_wif_file($wif_id, $wif_file_name){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO wif_file (wif_id, wif_file_name) VALUES (:wif_id, :wif_file_name)");
    $stmt->bindParam(':wif_id', $wif_id);
    $stmt->bindParam(':wif_file_name', $wif_file_name);
    try{
        $stmt->execute();
        $wif_file_id = $dbConnection->lastInsertId('wif_file_id');
        return $wif_file_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function insert_pif_file($pif_id, $pif_file_name){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO pif_files (pif_id, pif_file_name) VALUES (:pif_id, :pif_file_name)");
    $stmt->bindParam(':pif_id', $pif_id);
    $stmt->bindParam(':pif_file_name', $pif_file_name);
    try{
        $stmt->execute();
        $pif_file_id = $dbConnection->lastInsertId('pif_file_id');
        return $pif_file_id;
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}

/**
 * @param $pif_id
 * @return array|bool
 */
function get_pif_file_by_id($pif_id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT * FROM pif_files WHERE pif_id = :pifID");
    $stmt->bindValue(":pifID",$pif_id,PDO::PARAM_INT);
    try {
        $stmt->execute();
        $result= $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e){
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
/**
 * @param $company_id
 * @param $wif_status_id
 * @return array|bool|int
 */
function get_wifs($company_id, $wif_status_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT w.*, wt.wif_type_name, p.project_code FROM wif w join wif_type wt on w.wif_type_id = wt.wif_type_id
                              left join project p on w.project_id= p.project_id WHERE w.company_id = :companyId and w.wif_status_id = :wifStatusId");
    $handle->bindValue(":companyId",$company_id,PDO::PARAM_INT);
    $handle->bindValue(":wifStatusId",$wif_status_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}

/**
 * @param $active
 * @return array|bool
 */
function get_wif_statuses($active){
    $link = dbConn();
    $handle = $link->prepare("SELECT ws.* from wif_status ws where active = :active");
    $handle->bindValue(":active",$active,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}


function update_wif_status($wif_id, $wif_status_id){

	$dbConnection = dbConn();

	$sql = "UPDATE wif set wif_status_id = :wif_status_id where wif_id = :wif_id";
	//print $sql;
	
	$stmt = $dbConnection->prepare($sql);
	$stmt->bindParam(':wif_id', $wif_id);
	$stmt->bindParam(':wif_status_id', $wif_status_id);
	if ($stmt->execute() === FALSE) {
		return 0;
	}else{
		return 1;
	}
}

/**
 * @param $wif_id
 * @return array|bool|int
 */
function get_wif_info($wif_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT w.*, wt.wif_type_name, wt.wif_type_abbrev, wt.asset_type_id from wif w left join wif_type wt on w.wif_type_id = wt.wif_type_id
                              where wif_id = :wifId");
    $handle->bindValue(":wifId",$wif_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}

/**
 * @param $campaign_id
 * @return mixed
 */
function get_business_unit_abbrev_from_campaign_id($campaign_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT business_unit_abbrev from business_unit bu join campaign c on bu.business_unit_id = c.business_unit_id where c.campaign_id = :campaignId");
    $handle->bindValue(":campaignId",$campaign_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result[0]["business_unit_abbrev"];

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}

function update_wif_project_id($wif_id, $project_id){
	$dbConnection = dbConn();

	$sql = "UPDATE wif set project_id = :project_id where wif_id = :wif_id";
	//print $sql;
	
	$stmt = $dbConnection->prepare($sql);
	$stmt->bindParam(':wif_id', $wif_id);
	$stmt->bindParam(':project_id', $project_id);
	if ($stmt->execute() === FALSE) {
		return 0;
	}else{
		return 1;
	}
}

/**
 * @param $wif_id
 * @return array|bool|int
 */
function get_wif_files($wif_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT wf.* from wif_file wf where wif_id = :wifId");
    $handle->bindValue(":wifId",$wif_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result;

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}

/**
 * @param $wif_status_id
 * @return bool|int
 */
function get_wif_status_name($wif_status_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT wif_status_name from wif_status where wif_status_id = :wifStatusId");
    $handle->bindValue(":wifStatusId",$wif_status_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result[0]["wif_status_name"];

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}

function update_wif_campaign_id($wif_id, $campaign_id){

	$dbConnection = dbConn();

	$sql = "UPDATE wif set campaign_id = :campaign_id where wif_id = :wif_id";
	//print $sql;
	
	$stmt = $dbConnection->prepare($sql);
	$stmt->bindParam(':wif_id', $wif_id);
	$stmt->bindParam(':campaign_id', $campaign_id);
	if ($stmt->execute() === FALSE) {
		return 0;
	}else{
		return 1;
	}
}


/**
 * @param $admin_type
 * @return mixed
 */
function get_admin_value($admin_type){
    $link = dbConn();
    $handle = $link->prepare("SELECT admin_value from admin where admin_type = :adminType");
    $handle->bindValue(":adminType",$admin_type,PDO::PARAM_STR);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result[0]["admin_value"];

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}

function update_admin_value($admin_type, $admin_value){

	$dbConnection = dbConn();

	$sql = "UPDATE admin set admin_value = :admin_value where admin_type = :admin_type";
	//print $sql;
	
	$stmt = $dbConnection->prepare($sql);
	$stmt->bindParam(':admin_type', $admin_type);
	$stmt->bindParam(':admin_value', $admin_value);
	if ($stmt->execute() === FALSE) {
		return 0;
	}else{
		return 1;
	}
}

/**
 * @param $user_id
 * @return bool|int
 */
function get_user_email_address($user_id){
    $link = dbConn();
    $handle = $link->prepare("SELECT email from user where user_id = :userId");
    $handle->bindValue(":userId",$user_id,PDO::PARAM_INT);
    try {
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) return 0;
        return $result[0]["email"];

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return false;
    }
}
