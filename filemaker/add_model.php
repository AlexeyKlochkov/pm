<?php
/**
 * Created by PhpStorm.
 * User: aklochko
 * Date: 8/17/15
 * Time: 11:02 AM
 */
include_once "../functions/db.php";
function insertModel($name,$active,$gender,$isminor,$email,$address,$phone,$notes,$territory,$other_territory,$other_usage,$creator) {
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("INSERT INTO model (name,email,address,phone,notes,active,gender,isminor,territory_id,other_territory,other_usage,creator)
                                    VALUES (:name,:email,:address,:phone,:notes,:active,:gender,:isminor,:territory_id,:other_territory,:other_usage,:creator)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':notes', $notes);
    $stmt->bindParam(':territory_id', $territory);
    $stmt->bindParam(':active', $active);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':isminor', $isminor);
    $stmt->bindParam(':territory_id', $territory);
    $stmt->bindParam(':other_territory', $other_territory);
    $stmt->bindParam(':other_usage', $other_usage);
    $stmt->bindParam(':creator', $creator);

    try{
        $stmt->execute();
        return $dbConnection->lastInsertId();
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function updateModel($model_id,$name,$active,$gender,$isminor,$email,$address,$phone,$notes,$territory,$other_territory,$other_usage,$creator) {
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("UPDATE model
                                    SET name=:name, email=:email, address=:address, phone=:phone, notes=:notes, active=:active,
                                    gender=:gender, isminor=:isminor, territory_id=:territory_id, other_territory=:other_territory,
                                    other_usage=:other_usage, creator=:creator WHERE id=:model_id");
    $stmt->bindParam(':model_id', $model_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':notes', $notes);
    $stmt->bindParam(':territory_id', $territory);
    $stmt->bindParam(':active', $active);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':isminor', $isminor);
    $stmt->bindParam(':territory_id', $territory);
    $stmt->bindParam(':other_territory', $other_territory);
    $stmt->bindParam(':other_usage', $other_usage);
    $stmt->bindParam(':creator', $creator);

    try{
        $stmt->execute();
        return $dbConnection->lastInsertId();
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return 0;
    }
}
function insert_model_general_usage_info($model_id,$start_date,$end_date,$representation_type,$agency,$is_released,$duration_type,$media_rights,$other_media_rights){
    $dbConnection = dbConn();
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

    if (!empty($media_rights)) {
        $media_rights=array_unique($media_rights);
        $media_rights2 = implode(',', $media_rights);
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
        return 1;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return 0;
    }
}
function insert_model_usage_category($id,$name){
    $dbConnection = dbConn();

    foreach ($name as $name1) {

            $stmt = $dbConnection->prepare("INSERT INTO model_usage_category (model_id,category_id) VALUES (:image_id,:categoryId)");
            $stmt->bindParam(':image_id', $id);
            $stmt->bindParam(':categoryId', $name1);
            try {
                $stmt->execute();
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage(), "\n";
                return 0;
            }
        }

}
function update_model_general_usage_info($model_id,$start_date,$end_date,$representation_type,$agency,$is_released,$duration_type,$media_rights,$other_media_rights){
    $dbConnection = dbConn();
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

    if (!empty($media_rights)) {
        $media_rights=array_unique($media_rights);
        $media_rights2 = implode(',', $media_rights);
    }
    else $media_rights2=null;
    $stmt = $dbConnection->prepare("UPDATE model_general_usage_info SET start_date=:start_date, end_date=:end_date, representation_type=:representation_type,
                                    agency=:agency ,is_released=:is_released, duration_type=:duration_type,media_rights=:media_rights,other_media_rights=:other_media_rights WHERE model_id=:model_id");
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
        return 1;
    }catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return 0;
    }
}
function delete_old_data($id){
    $dbConnection = dbConn();
    $stmt = $dbConnection->prepare("DELETE FROM model_usage_category WHERE model_id=:model_id");
    $stmt->bindParam(':model_id', $id);
    try {
        $stmt->execute();
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        return 0;
    }

}
function update_model_usage_category($id,$name){
    $dbConnection = dbConn();
    delete_old_data($id);
    foreach ($name as $name1) {

        $stmt = $dbConnection->prepare("INSERT INTO model_usage_category (model_id,category_id) VALUES (:image_id,:categoryId)");
        $stmt->bindParam(':image_id', $id);
        $stmt->bindParam(':categoryId', $name1);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
            return 0;
        }
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

if (isset($_REQUEST["model_id"]) && $_REQUEST["model_id"]>0) {
    updateModel($_REQUEST["model_id"],$_REQUEST["name"], $_REQUEST["active"], $_REQUEST["gender"], $_REQUEST["isminor"], $_REQUEST["email"],
        $_REQUEST["address"], $_REQUEST["phone"], $_REQUEST["notes"], $_REQUEST["territory"], $_REQUEST["other_territory"], $_REQUEST["other_usage"], "aklochko");
    if (!empty($_FILES["file"]["name"])){
        $uploads_dir = '../images/models';
        $tmp_name = $_FILES["file"]["tmp_name"];
        $name = "m" . $_REQUEST["model_id"] . ".jpg";
        if (file_exists("$uploads_dir/$name")){
            unlink("$uploads_dir/$name");
        }
        move_uploaded_file($tmp_name, "$uploads_dir/$name");
    }
    update_model_general_usage_info($_REQUEST["model_id"], date('Y-m-d', strtotime($_REQUEST["start_date"])), date('Y-m-d', strtotime($_REQUEST["end_date"])),
        $_REQUEST["representation_type"], $_REQUEST["agency"], $_REQUEST["is_released"], $_REQUEST["duration_type"], $_REQUEST["media_rights"], $_REQUEST["other_media"]);
    update_model_usage_category($_REQUEST["model_id"], $_REQUEST["usage_category"]);
    header( 'Location: model.php?id='.$_REQUEST["model_id"]) ;
}
else {
    $newModelId = insertModel($_REQUEST["name"], $_REQUEST["active"], $_REQUEST["gender"], $_REQUEST["isminor"], $_REQUEST["email"],
        $_REQUEST["address"], $_REQUEST["phone"], $_REQUEST["notes"], $_REQUEST["territory"], $_REQUEST["other_territory"], $_REQUEST["other_usage"], "aklochko");
    insert_model_general_usage_info($newModelId, date('Y-m-d', strtotime($_REQUEST["start_date"])), date('Y-m-d', strtotime($_REQUEST["end_date"])),
        $_REQUEST["representation_type"], $_REQUEST["agency"], $_REQUEST["is_released"], $_REQUEST["duration_type"], $_REQUEST["media_rights"], $_REQUEST["other_media"]);
    insert_model_usage_category($newModelId, $_REQUEST["usage_category"]);
    if (!empty($_FILES)) {
        $uploads_dir = '../images/models';
        $tmp_name = $_FILES["file"]["tmp_name"];
        $name = "m" . $newModelId . ".jpg";
        move_uploaded_file($tmp_name, "$uploads_dir/$name");
    }
    header( 'Location: model.php?id='.$newModelId ) ;

}
