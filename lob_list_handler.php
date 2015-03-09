<?php
/**
 * Created by PhpStorm.
 * User: aklochko
 * Date: 3/9/15
 * Time: 4:28 PM
 */
include_once "functions/dbconn.php";
$link = dbConn();
if (isset ($_POST["request"])){
    switch ($_POST["request"]){
        case "Save active":{
            $id=$_POST["id"];
            $active=$_POST["active"];
            $handle = $link->prepare("UPDATE business_unit SET active=:active where business_unit_id=:id");
            $handle->bindValue("id",$id,PDO::PARAM_INT);
            $handle->bindValue("active",$active,PDO::PARAM_STR);
            try {
                $handle->execute();
                return "Success";
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage(), "\n";
                return "DB error";
            }
        }
        case "Save MRI":{
            $id=$_POST["id"];
            $active=$_POST["active"];
            $handle = $link->prepare("UPDATE business_unit SET is_mri=:active where business_unit_id=:id");
            $handle->bindValue("id",$id,PDO::PARAM_INT);
            $handle->bindValue("active",$active,PDO::PARAM_STR);
            try {
                $handle->execute();
                return "Success";
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage(), "\n";
                return "DB error";
            }
        }
        default:break;
    }
}