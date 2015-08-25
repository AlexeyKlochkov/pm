<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Model</title>

    <!-- Bootstrap -->
    <script src="../js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
</head>
<?php
include_once "../functions/import.php";
if (isset($_REQUEST["id"])) {
    $id = $_REQUEST["id"];
//else $id=getFirstModelId();
    $nextId = getNextModelId($id);
    $prevId = getPrevModelId($id);
    $result = getModelInfo($id)[0];
    $result["category"] = getModelCategories($id);
    $result["usage_info"] = getModelUsageInfo($id);
    if ($result["usage_info"][0]["start_date"] == "1970-01-01") $result["usage_info"][0]["start_date"] = null;
    if ($result["usage_info"][0]["end_date"] == "1970-01-01") $result["usage_info"][0]["end_date"] = null;
//var_dump($result["usage_info"]);
}
$territory = getTerritory();
$category = getCategory();
$mm = str_replace('(', "", get_enum_values("model_general_usage_info", "media_rights")[0][0]);
$mm = str_replace(')', "", $mm);
$mm = str_replace("'", "", $mm);
$mediaRights = explode(',', $mm);
?>
<div class="container">
    <div class="page-header">
        <h1>Model</h1>
        <nav>
            <ul class="pager">
                <?if (isset($id)):?> <li <?if (!isset($prevId) || $prevId<=0) echo "class='previous disabled'"; else echo "class='previous'"?>><a href="model.php?id=<?=$prevId?>"><span aria-hidden="true">&larr;</span>Previous</a></li><?endif;?>
                <label>Id:</label>
                <input type="text" id="goto" value="<?=(isset($id))?$id:""?>">
                <button class="btn btn-default" id="togo">Go</button>
                <?if (isset($id)):?><li <?if (!isset($nextId) || $nextId<=0) echo "class='next disabled'"; else echo "class='next'"?>><a href="model.php?id=<?=$nextId?>"><span aria-hidden="true">&rarr;</span>Next</a></li> <?endif;?>
            </ul>
        </nav>

    </div>
    <form class="form-horizontal" action="add_model.php" method="post" enctype="multipart/form-data">
        <input type="text" id="model_id" name="model_id" hidden="hidden" value="<?=(isset($id))?$id:""?>">
        <fieldset>
            <div class="form-group">
                <?if (isset($id)):?><button type="button" class="btn btn-success" id="newmodel">New model</button><?endif;?>
                <input type="submit" class="btn btn-primary" value="Save changes">
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <legend>Personal Info</legend>
                    <!-- Name input-->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="name">Name</label>
                        <div class="col-md-8">
                            <input id="name" name="name" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["name"]))?$result["name"]:""?>">

                        </div>
                    </div>

                    <!-- Active checkbox -->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="active"></label>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label for="active">
                                    <input type="checkbox" name="active" id="active" value="1" <?php if (isset($result["active"]) && $result["active"]==1) echo "checked='checked'"?>>
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Gender radios -->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="gender">Model Gender</label>
                        <div class="col-md-8">
                            <label class="radio-inline" for="gender-1">
                                <input type="radio" name="gender" id="gender-1" value="1" <?php if (isset($result["gender"]) && ($result["gender"]===1)) echo "checked='checked'"?>>
                                Male
                            </label>
                            <label class="radio-inline" for="gender-0">
                                <input
                                    type="radio" name="gender" id="gender-0" value="0" <?php if (isset($result["gender"]) && $result["gender"]===0) echo "checked='checked'"?>>
                                Female
                            </label>
                        </div>
                    </div>

                    <!-- Is minor radios -->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="isminor">Model is a minor?</label>
                        <div class="col-md-8">
                            <label class="radio-inline" for="isminor-1">
                                <input type="radio" name="isminor" id="isminor-1" value="1" <?php if (isset($result["isminor"]) &&  $result["isminor"]===1) echo "checked='checked'"?>>
                                Yes
                            </label>
                            <label class="radio-inline" for="isminor-0">
                                <input type="radio" name="isminor" id="isminor-0" value="0" <?php if (isset($result["isminor"]) && $result["isminor"]===0) echo "checked='checked'"?>>
                                No
                            </label>
                        </div>
                    </div>

                    <!-- Email input-->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="email">Email</label>
                        <div class="col-md-8">
                            <input id="email" name="email" type="text" placeholder="email@example.com" class="form-control input-md" value="<?=(isset($result["email"]))?$result["email"]:""?>">

                        </div>
                    </div>

                    <!-- Address textarea -->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="address">Address</label>
                        <div class="col-md-8">
                            <textarea class="form-control" id="address" name="address"><?=(isset($result["address"]))?$result["address"]:""?></textarea>
                        </div>
                    </div>

                    <!-- Phone input-->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="phone">Phone</label>
                        <div class="col-md-8">
                            <input id="phone" name="phone" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["phone"]))?$result["phone"]:""?>">

                        </div>
                    </div>

                    <!-- Notes textarea -->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="notes">Notes</label>
                        <div class="col-md-8">
                            <textarea class="form-control" id="notes" name="notes"><?=(isset($result["notes"]))?$result["notes"]:""?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <legend>Preview</legend>
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 400px; height: 300px;">
                            <img data-src="holder.js/100%x100%" alt="..." <?if (isset($id)):?>src="../images/models/m<?=$id?>.jpg"<?endif;?>>
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 400px; max-height: 300px;"></div>
                        <div>
                            <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" name="file"></span>
                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                        </div>
                    </div>
                </div>
           </div>
            <div class="form-group">
                <div class="col-md-6">
                <legend>Apollo Usage</legend>
                    <!-- Multiple Radios -->
                <div class="form-group">
                    <label class="col-md-2 control-label" for="territory">Territory</label>
                    <div class="col-md-6">
                        <?foreach ($territory as $ter):?>
                        <div class="radio">
                            <label for="territory-<?=$ter["id"]?>">
                                <input type="radio" name="territory" id="territory-<?=$ter["id"]?>" value="<?=$ter["id"]?>" <?php if (isset ($result["territory_id"]) && $result["territory_id"]==$ter["id"]) echo "checked='checked'";?>>
                                <?=$ter["name"]?>
                            </label>
                        </div>
                        <?endforeach;?>
                    </div>
                </div>

                    <!-- Text input-->
                <div class="form-group">
                        <label class="col-md-2 control-label" for="other_territory">Other Territory</label>
                        <div class="col-md-8">
                            <input id="other_territory" name="other_territory" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["other_territory"]))?$result["other_territory"]:""?>">
                        </div>
                </div>

                    <!-- Multiple Checkboxes -->
                <div class="form-group">
                        <label class="col-md-2 control-label" for="usage_category">Usage Category</label>
                        <div class="col-md-4">
                            <?foreach ($category as $cat):?>
                            <div class="checkbox">
                                <label for="usage_category-<?=$cat["id"]?>">
                                    <input type="checkbox" name="usage_category[]" id="usage_category-<?=$cat["id"]?>" value="<?=$cat["id"]?>" <?php
                                    if (isset ($result["category"])) {
                                        foreach ($result["category"] as $categ) {
                                            if ($categ["category_id"] == $cat["id"])
                                                echo "checked='checked'";
                                        }
                                    }
                                    ?>>
                                    <?=$cat["name"]?>
                                </label>
                            </div>
                            <?endforeach;
                            ?>
                        </div>
                    </div>

                    <!-- Text input-->
                <div class="form-group">
                        <label class="col-md-2 control-label" for="other_usage">Other Usage</label>
                        <div class="col-md-8">
                            <input id="other_usage" name="other_usage" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["other_usage"]))?$result["other_usage"]:""?>">

                        </div>
                </div>
            </div>
            <div class="col-md-6">
                <legend>General Usage Info</legend>

                <!-- Start Date input-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="start_date">Model Usage Start Date</label>
                    <div class="col-md-4">
                        <input id="start_date" name="start_date" type="text" placeholder="" class="form-control input-md" data-provide="datepicker"
                               <? if (isset($result["usage_info"][0]["start_date"]) && !is_null($result["usage_info"][0]["start_date"])): ?>value="<?=date('m/d/Y',strtotime($result["usage_info"][0]["start_date"]))?>"
                            <?endif;?>>
                    </div>
                </div>

                <!-- End Date input-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="end_date">Model Usage End Date</label>
                    <div class="col-md-4">
                        <input id="end_date" name="end_date" type="text"  class="form-control input-md"
                               <? if (isset($result["usage_info"][0]["end_date"]) && !is_null($result["usage_info"][0]["end_date"])): ?>value="<?=date('m/d/Y',strtotime($result["usage_info"][0]["end_date"]))?>"
                            <?endif;?>>

                    </div>
                </div>

                <!-- Representation Type Radios -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="representation_type">Representation Type</label>
                    <div class="col-md-6">
                        <div class="radio">
                            <label for="representation_type-0">
                                <input type="radio" name="representation_type" id="representation_type-0" value="Agency Representation" <?if (isset ($result["usage_info"][0]["representation_type"]) && $result["usage_info"][0]["representation_type"]=="Agency Representation") echo "checked='checked'";?>>
                                Agency Representation
                            </label>
                        </div>
                        <div class="radio">
                            <label for="representation_type-1">
                                <input type="radio" name="representation_type" id="representation_type-1" value="Individual" <?if (isset ($result["usage_info"][0]["representation_type"]) &&  $result["usage_info"][0]["representation_type"]=="Individual") echo "checked='checked'";?>>
                                Individual
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="agency">Agency</label>
                    <div class="col-md-8">
                        <input id="agency" name="agency" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["usage_info"][0]["agency"]))?$result["usage_info"][0]["agency"]:""?>">

                    </div>
                </div>

                <!-- Multiple Radios (inline) -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="is_released">Model Released?</label>
                    <div class="col-md-4">
                        <label class="radio-inline" for="is_released-0">
                            <input type="radio" name="is_released" id="is_released-0" value="1" <?if (isset ($result["usage_info"][0]["is_released"]) && $result["usage_info"][0]["is_released"]==1) echo "checked='checked'";?>>
                            Yes
                        </label>
                        <label class="radio-inline" for="is_released-1">
                            <input type="radio" name="is_released" id="is_released-1" value="0" <?if (isset ($result["usage_info"][0]["is_released"]) && $result["usage_info"][0]["is_released"]==0) echo "checked='checked'";?>>
                            No
                        </label>
                    </div>
                </div>

                <!-- Multiple Radios -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="duration_type">Duration Type</label>
                    <div class="col-md-4">
                        <div class="radio">
                            <label for="duration_type-0">
                                <input type="radio" name="duration_type" id="duration_type-0" value="Limited" <?if (isset($result["usage_info"][0]["duration_type"]) && $result["usage_info"][0]["duration_type"]=="Limited") echo "checked='checked'";?>>
                                Limited
                            </label>
                        </div>
                        <div class="radio">
                            <label for="duration_type-1">
                                <input type="radio" name="duration_type" id="duration_type-1" value="Unlimited" <?if (isset($result["usage_info"][0]["duration_type"]) && $result["usage_info"][0]["duration_type"]=="Unlimited") echo "checked='checked'";?>>
                                Unlimited
                            </label>
                        </div>
                        <div class="radio">
                            <label for="duration_type-2">
                                <input type="radio" name="duration_type" id="duration_type-2" value="Buyout" <?if (isset($result["usage_info"][0]["duration_type"]) && $result["usage_info"][0]["duration_type"]=="Buyout") echo "checked='checked'";?>>
                                Buyout
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Multiple Checkboxes -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="media_rights">Media Rights</label>
                    <div class="col-md-4">
                        <?foreach ($mediaRights as $mr):?>
                        <div class="checkbox">
                            <label for="media_rights-<?=$mr?>">
                                <input type="checkbox" name="media_rights[]" id="media_rights-<?=$mr?>" value="<?=$mr?>" <?if (isset($result["usage_info"][0]["media_rights"]) &&  strpos($result["usage_info"][0]["media_rights"],$mr)!==false) echo "checked='checked'"?>>
                                <?=$mr?>
                            </label>
                        </div>
                        <?endforeach;?>
                    </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="other_media">Other Media Rights</label>
                    <div class="col-md-8">
                        <input id="other_media" name="other_media" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["usage_info"][0]["other_media_rights"]))?$result["usage_info"][0]["other_media_rights"]:""?>">
                    </div>
                </div>
            </div>
        </div>
        </fieldset>
    </form>
</div>
</html>
<script type="text/javascript">
    $('#start_date').datepicker({});
    $('#end_date').datepicker({});$(document).ready(function() {
        $("nav ul li.disabled a").click(function() {
            return false;
        });
        $("#togo").click(function() {
            var gotoid=$("#goto").val();
            window.location.href = "model.php?id="+gotoid;
        });
        $("#newmodel").click(function() {
            window.location.href = "model.php";
        });
    });
</script>

