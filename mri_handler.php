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
        case "Update mri":{
            $id=$_POST["id"];
            $field=$_POST["field"];
            $value=$_POST["value"];
            $handle = $link->prepare("UPDATE MRI_common SET ".$field."=:value where id=:id");
            $handle->bindValue("id",$id,PDO::PARAM_INT);
            $handle->bindValue("value",$value,PDO::PARAM_STR);
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
