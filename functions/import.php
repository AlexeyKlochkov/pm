<?php
include_once "db.php";
//ini_set("auto_detect_line_endings", true);
//$handle = fopen('models.txt','r');

function get_enum_values()
{
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT SUBSTRING(COLUMN_TYPE,5)
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA='filemaker'
AND TABLE_NAME='model_general_usage_info'
AND COLUMN_NAME='media_rights'");
    try{
        $stmt->execute();
        return ($stmt->fetchAll());
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}

function getFirstModelId(){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT MIN(id) as minid FROM model");
    try{
        $stmt->execute();
        return ($stmt->fetchColumn());
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function getModelInfo($id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT * FROM model WHERE id=:id");
    $stmt->bindParam(':id', $id);
    try{
        $stmt->execute();
        return ($stmt->fetchAll());
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function getImageInfo($id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT * FROM image WHERE id=:id");
    $stmt->bindParam(':id', $id);
    try{
        $stmt->execute();
        return ($stmt->fetchAll());
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function getModelUsageInfo($id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT * FROM model_general_usage_info WHERE model_id=:id");
    $stmt->bindParam(':id', $id);
    try{
        $stmt->execute();
        return ($stmt->fetchAll());
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function getModelCategories($id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT category_id FROM model_usage_category WHERE model_id=:id");
    $stmt->bindParam(':id', $id);
    try{
        $stmt->execute();
        return ($stmt->fetchAll());
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function getModelFeatured($id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT featured FROM model_featured WHERE image_id=:id");
    $stmt->bindParam(':id', $id);
    try{
        $stmt->execute();
        return ($stmt->fetchAll());
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function getImageCategories($id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT category_id FROM image_apollo_usage_category WHERE image_id=:id");
    $stmt->bindParam(':id', $id);
    try{
        $stmt->execute();
        return ($stmt->fetchAll());
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function getTerritory(){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT id,name FROM apollo_usage_territory");
    try{
        $stmt->execute();
        return ($stmt->fetchAll());
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function getCategory(){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT id,name FROM apollo_usage_category");
    try{
        $stmt->execute();
        return ($stmt->fetchAll());
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function getNextModelId($id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT MIN(id) as minid FROM model WHERE id > :id");
    $stmt->bindParam(':id', $id);
    try{
        $stmt->execute();
        return ($stmt->fetchColumn());
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}function getPrevModelId($id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT MAX(id) as minid FROM model WHERE id < :id");
    $stmt->bindParam(':id', $id);
    try{
        $stmt->execute();
        return ($stmt->fetchColumn());
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function getNextImageId($id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT MIN(id) as minid FROM image WHERE id > :id");
    $stmt->bindParam(':id', $id);
    try{
        $stmt->execute();
        return ($stmt->fetchColumn());
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}function getPrevImageId($id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("SELECT MAX(id) as minid FROM image WHERE id < :id");
    $stmt->bindParam(':id', $id);
    try{
        $stmt->execute();
        return ($stmt->fetchColumn());
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function insert_image($id,$active ){
    $dbConnection = dbConn();
    if ($active=='Active') $active=1;
        else $active =0;
    $stmt = $dbConnection->prepare("INSERT INTO image (id, active) VALUES (:id,:active)");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':active', $active);
    try{
        $stmt->execute();
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function insert_model_featured($image_id,$featured ){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO model_featured (image_id,featured) VALUES (:image_id,:featured)");
    $stmt->bindParam(':image_id', $image_id);
    $stmt->bindParam(':featured', $featured);
    try{
        $stmt->execute();
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function insert_dimensions($image_id,$width,$height,$file_size,$resolution,$file_measure,$height_measure ){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO image_dimensions (image_id,width,height,file_size,resolution,height_measure,file_size_measure)
                                    VALUES (:image_id,:width,:height,:file_size,:resolution,:height_measure,:file_measure)");
    $stmt->bindParam(':image_id', $image_id);
    $stmt->bindParam(':width', $width);
    $stmt->bindParam(':height', $height);
    $stmt->bindParam(':file_size', $file_size);
    $stmt->bindParam(':resolution', $resolution);
    $stmt->bindParam(':file_measure', $file_measure);
    $stmt->bindParam(':height_measure', $height_measure);
    try{
        $stmt->execute();
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}

function insert_source_info($image_id,$stock_code,$stock_name,$stock_quote_id,$stock_photographer,$stock_house,$photographer_name,$rights_managed_type,$other_media_rights,$royalty_free_type,$notes,$media_rights){
    $dbConnection = dbConn();
    $media_rights=strtolower($media_rights);
    if (strpos($media_rights,'all media') !== false) {
        $media_rights1[]='All media';
    }
    if (strpos($media_rights,'broadcast') !== false) {
        $media_rights1[]='Excl. Broadcast';
    }
    if (strpos($media_rights,'print media') !== false) {
        $media_rights1[]='Print Media';
    }
    if (strpos($media_rights,'ooh') !== false) {
        $media_rights1[]='OOH';
    }
    if (strpos($media_rights,'web') !== false) {
        $media_rights1[]='Web';
    }

    if (!empty($media_rights1)) {
        $media_rights1=array_unique($media_rights1);
        $media_rights2 = implode(',', $media_rights1);
    }
    else $media_rights2="";
    $stmt = $dbConnection->prepare("INSERT INTO image_source_info (image_id,stock_code,stock_name,stock_quote_id,stock_photographer,stock_house,photographer_name,rights_managed_type,other_media_rights,royalty_free_type,image_notes,image_media_rights)
                                    VALUES (:image_id,:stock_code,:stock_name,:stock_quote_id,:stock_photographer,:stock_house,:photographer_name,:rights_managed_type,:other_media_rights,:royalty_free_type,:image_notes,:media_rights)");
    $stmt->bindParam(':image_id', $image_id);
    $stmt->bindParam(':stock_code', $stock_code);
    $stmt->bindParam(':stock_name', $stock_name);
    $stmt->bindParam(':stock_quote_id', $stock_quote_id);
    $stmt->bindParam(':stock_photographer', $stock_photographer);
    $stmt->bindParam(':stock_house', $stock_house);
    $stmt->bindParam(':photographer_name', $photographer_name);
    $stmt->bindParam(':rights_managed_type', $rights_managed_type);
    $stmt->bindParam(':other_media_rights', $other_media_rights);
    $stmt->bindParam(':royalty_free_type', $royalty_free_type);
    $stmt->bindParam(':image_notes', $notes);
    $stmt->bindParam(':media_rights', $media_rights2);
    try{
        $stmt->execute();
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function insert_apollo_usage($image_id,$start_date,$end_date,$unlimited_time,$territory_id,$other_territory,$release_received,$release_type,$exclusivity,$exclusivity_notes,$other_category,$project_number,$ipm,$art_buyer){
    $dbConnection = dbConn();
    $exclusivity=strtolower($exclusivity);
    if (strpos($exclusivity,'exclusive') !== false) {
        $exclusivity2='Exclusive';
    }
    if (strpos($exclusivity,'non-exclusive') !== false) {
        $exclusivity2='Non-exclusive';
    }
    else $exclusivity2="";
    if ($unlimited_time!="")
        $unlimited_time=1;
    else $unlimited_time=0;
    if ($release_received=="Released")
        $release_received=1;
    elseif($release_received=="Not Released")
        $release_received=0;
    $stmt = $dbConnection->prepare("INSERT INTO image_apollo_usage (image_id,start_date,end_date,unlimited_time,territory_id,other_territory,release_received,release_type,exclusivity,exclusivity_notes,other_category,project_number,ipm,art_buyer)
                                    VALUES (:image_id,:start_date,:end_date,:unlimited_time,:territory_id,:other_territory,:release_received,:release_type,:exclusivity,:exclusivity_notes,:other_category,:project_number,:ipm,:art_buyer)");
    $stmt->bindParam(':image_id', $image_id);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);
    $stmt->bindParam(':unlimited_time', $unlimited_time);
    $stmt->bindParam(':territory_id', $territory_id);
    $stmt->bindParam(':other_territory', $other_territory);
    $stmt->bindParam(':release_received', $release_received);
    $stmt->bindParam(':release_type', $release_type);
    $stmt->bindParam(':exclusivity', $exclusivity2);
    $stmt->bindParam(':exclusivity_notes', $exclusivity_notes);
    $stmt->bindParam(':other_category', $other_category);
    $stmt->bindParam(':project_number', $project_number);
    $stmt->bindParam(':ipm', $ipm);
    $stmt->bindParam(':art_buyer', $art_buyer);
    try{
        $stmt->execute();
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}

function getTerritoryById($id){
    $dbConnection = dbConn();

    $stmt = $dbConnection->prepare("SELECT name FROM apollo_usage_territory WHERE id=:id");
    $stmt->bindValue(":id",$id,PDO::PARAM_INT);
    try{
        $stmt->execute();
        return ($stmt->fetch());
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}

function getCategoryIdByName($name){
    $dbConnection = dbConn();
    $name=strtoupper($name);
    $stmt = $dbConnection->prepare("SELECT id FROM apollo_usage_category WHERE name LIKE '%".$name."%'");
    $stmt->bindValue(":name",$name,PDO::PARAM_INT);
    try{
        $stmt->execute();
        return ($stmt->fetch());
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}

function insert_apollo_usage_category($id,$name){
    $dbConnection = dbConn();
    foreach (explode(chr(11),$name) as $name1) {
        $categoryId=getCategoryIdByName($name1)['id'];
        if ($categoryId!=0){
            $stmt = $dbConnection->prepare("INSERT INTO image_apollo_usage_category (image_id,category_id) VALUES (:image_id,:categoryId)");
            $stmt->bindParam(':image_id', $id);
            $stmt->bindParam(':categoryId', $categoryId);
            try {
                $stmt->execute();
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage(), "\n";
                return 0;
            }
        }
    }
}
function insert_model_usage_category($id,$name){
    $dbConnection = dbConn();

    foreach (explode(chr(11),$name) as $name1) {
        $categoryId=getCategoryIdByName($name1)['id'];
        if ($categoryId!=0){
            $stmt = $dbConnection->prepare("INSERT INTO model_usage_category (model_id,category_id) VALUES (:image_id,:categoryId)");
            $stmt->bindParam(':image_id', $id);
            $stmt->bindParam(':categoryId', $categoryId);
            try {
                $stmt->execute();
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage(), "\n";
                return 0;
            }
        }
    }
}

function getProgramIdByName($name){
    $dbConnection = dbConn();
    $name=strtoupper($name);
    $stmt = $dbConnection->prepare("SELECT id FROM meta_data_program WHERE name=:name");
    $stmt->bindValue(":name",$name,PDO::PARAM_INT);
    try{
        $stmt->execute();
        return ($stmt->fetch());
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}

function insert_image_meta_data_program($id,$name){
    $dbConnection = dbConn();
    foreach (explode('/t',$name) as $name1) {
        $categoryId=getProgramIdByName($name1)['id'];
        if ($categoryId!=0){
            $stmt = $dbConnection->prepare("INSERT INTO image_meta_data_program (image_id,program_id) VALUES (:image_id,:categoryId)");
            $stmt->bindParam(':image_id', $id);
            $stmt->bindParam(':categoryId', $categoryId);
            try {
                $stmt->execute();
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage(), "\n";
                return 0;
            }
        }
    }
}

function getSettingIdByName($name){
    $dbConnection = dbConn();
    $name=strtoupper($name);
    $stmt = $dbConnection->prepare("SELECT id FROM meta_data_setting WHERE name=:name");
    $stmt->bindValue(":name",$name,PDO::PARAM_INT);
    try{
        $stmt->execute();
        return ($stmt->fetch());
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function getTerritoryIdByName($name){
    $dbConnection = dbConn();
    $name=strtoupper($name);
    $stmt = $dbConnection->prepare("SELECT id FROM apollo_usage_territory WHERE name=:name");
    $stmt->bindValue(":name",$name,PDO::PARAM_INT);
    try{
        $stmt->execute();
        return ($stmt->fetch());
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}

function insert_image_meta_data_setting($id,$name){
    $dbConnection = dbConn();
    foreach (explode('/t',$name) as $name1) {
        $categoryId=getSettingIdByName($name1)['id'];
        if ($categoryId!=0){
            $stmt = $dbConnection->prepare("INSERT INTO image_meta_data_setting (image_id,setting_id) VALUES (:image_id,:categoryId)");
            $stmt->bindParam(':image_id', $id);
            $stmt->bindParam(':categoryId', $categoryId);
            try {
                $stmt->execute();
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage(), "\n";
                return 0;
            }
        }
    }
}
function insert_asset_storage($image_id,$is_posting,$hr_location,$lr_location,$needs_retouching,$has_been_replaced){
    $dbConnection = dbConn();
    $hr_location=strtolower($hr_location);
    if (strpos($hr_location,'server') !== false) {
        $hr_location2='Server';
    }
    if (strpos($hr_location,'backup') !== false) {
        $hr_location2='Backup';
    }if (strpos($hr_location,'asset') !== false) {
        $hr_location2='Asset Library';
    }
    else $hr_location2="";
    $lr_location=strtolower($lr_location);
    if (strpos($lr_location,'server') !== false) {
        $lr_location2='Server';
    }
    if (strpos($lr_location,'backup') !== false) {
        $lr_location2='Backup';
    }if (strpos($lr_location,'asset') !== false) {
        $lr_location2='Asset Library';
    }
    else $lr_location2="";
    if (strtolower($is_posting)=="yes")
        $is_posting=1;
    elseif (strtolower($is_posting)=="no")
        $is_posting=0;
    else $is_posting="";
    if (strtolower($needs_retouching)=="yes")
        $needs_retouching=1;
    elseif (strtolower($needs_retouching)=="no")
        $needs_retouching=0;
    else $needs_retouching="";
    if (strtolower($has_been_replaced)=="yes")
        $has_been_replaced=1;
    elseif (strtolower($has_been_replaced)=="no")
        $has_been_replaced=0;
    else $has_been_replaced="";
    $stmt = $dbConnection->prepare("INSERT INTO image_asset_storage (image_id,is_posting,hr_location,lr_location,needs_retouching,has_been_replaced)
                                    VALUES (:image_id,:is_posting,:hr_location,:lr_location,:needs_retouching,:has_been_replaced)");
    $stmt->bindParam(':image_id', $image_id);
    $stmt->bindParam(':is_posting', $is_posting);
    $stmt->bindParam(':hr_location', $hr_location);
    $stmt->bindParam(':lr_location', $lr_location);
    $stmt->bindParam(':needs_retouching', $needs_retouching);
    $stmt->bindParam(':has_been_replaced', $has_been_replaced);
    try{
        $stmt->execute();
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}

function insert_model($id,$email,$address,$phone,$notes,$active,$gender,$isminor,$name,$territory_id,$other_territory){
    $dbConnection = dbConn();
    if ($active=="Active") $active=1;
    else $active=0;
    if ($gender=="Male") $gender=1;
    elseif ($gender=="Female") $gender=0;
    else $gender=null;
    if ($isminor=="Yes") $isminor=1;
    elseif ($isminor=="No") $isminor=0;
    else $isminor=null;
    $stmt = $dbConnection->prepare("INSERT INTO model (id,email,address,phone,notes,active,gender,isminor,name,territory_id,other_territory)
                                    VALUES (:id,:email,:address,:phone,:notes,:active,:gender,:isminor,:name,:territory_id,:other_territory)");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':notes', $notes);
    $stmt->bindParam(':active', $active);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':isminor', $isminor);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':territory_id', $territory_id);
    $stmt->bindParam(':other_territory', $other_territory);
    try{
        $stmt->execute();
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return 0;
    }
}
function insert_model_general_usage_info($model_id,$start_date,$end_date,$representation_type,$agency,$is_released,$duration_type,$media_rights,$other_media_rights){
    $dbConnection = dbConn();
    if ($is_released=="Yes") $is_released=1;
    elseif ($is_released=="No") $is_released=0;
    else $is_released=null;
    $representation_type=strtolower($representation_type);
    if (strpos($representation_type,'agency representation') !== false) {
        $representation_type2='Agency Representation';
    }
    elseif (strpos($representation_type,'individual') !== false) {
        $representation_type2='Individual';
    }
    else $representation_type2=null;
    $duration_type=strtolower($duration_type);
    if (strpos($duration_type,'limited') !== false) {
        $duration_type2='Limited';
    }
    elseif (strpos($duration_type,'unlimited') !== false) {
        $duration_type2='Unlimited';
    }
    elseif (strpos($duration_type,'buyout') !== false) {
        $duration_type2='Buyout';
    }
    else $duration_type2=null;
    $media_rights=strtolower($media_rights);
    if (strpos($media_rights,'all media') !== false) {
        $media_rights1[]='All media';
    }
    if (strpos($media_rights,'broadcast') !== false) {
        $media_rights1[]='Excl. Broadcast';
    }
    if (strpos($media_rights,'print media') !== false) {
        $media_rights1[]='Print Media';
    }
    if (strpos($media_rights,'ooh') !== false) {
        $media_rights1[]='OOH';
    }
    if (strpos($media_rights,'web') !== false) {
        $media_rights1[]='Web';
    }

    if (!empty($media_rights1)) {
        $media_rights1=array_unique($media_rights1);
        $media_rights2 = implode(',', $media_rights1);
    }
    else $media_rights2=null;
    $stmt = $dbConnection->prepare("INSERT INTO model_general_usage_info (model_id,start_date,end_date,representation_type,agency,is_released,duration_type,media_rights,other_media_rights)
                                    VALUES (:model_id,:start_date,:end_date,:representation_type,:agency,:is_released,:duration_type,:media_rights,:other_media_rights)");
    $stmt->bindParam(':model_id', $model_id);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);
    $stmt->bindParam(':representation_type', $representation_type2);
    $stmt->bindParam(':agency', $agency);
    $stmt->bindParam(':is_released', $is_released);
    $stmt->bindParam(':duration_type', $duration_type2);
    $stmt->bindParam(':media_rights', $media_rights2);
    $stmt->bindParam(':other_media_rights', $other_media_rights);

    try{
        $stmt->execute();
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return 0;
    }
}

//while (($data = fgetcsv($handle,0,"\t")) !== FALSE) {
    /* for ($i=1;$i<=6;$i++) {
        if ($data[$i] != "") insert_model_featured($data[0], $data[$i]);
    }
    insert_dimensions($data[0],$data[19],$data[21],$data[23],$data[25],$data[24],$data[22]);
    insert_source_info($data[0],$data[7],$data[8],$data[9],$data[10],$data[11],$data[12],$data[13],$data[14],$data[32],$data[14],$data[41],$data[31]);
    insert_apollo_usage($data[0],date('Y-m-d',strtotime($data[16])),date('Y-m-d',strtotime($data[17])),$data[18],getTerritoryIdByName($data[26])['id'],$data[27],$data[28],$data[40],$data[29],$data[30],$data[34],$data[36],$data[53],$data[54]);
    insert_apollo_usage_category($data[0],$data[33]);
    insert_asset_storage($data[0],$data[55],$data[56],$data[57],$data[58],$data[59]);
    insert_image_meta_data_program($data[0],$data[44]);
    insert_image_meta_data_setting($data[0],$data[50]);



    insert_model($data[11],$data[8],$data[7],$data[15],$data[14],$data[0],$data[10],$data[12],$data[13],getTerritoryIdByName($data[16])['id'],$data[17]);

    */
   // insert_apollo_usage_category($data[0],$data[33]);
    //insert_model_usage_category($data[11],$data[21]);
    //insert_model_general_usage_info($data[11],date('Y-m-d',strtotime($data[18])),date('Y-m-d',strtotime($data[19])),$data[20],$data[22],$data[9],$data[4],$data[5],$data[6]);
//}


