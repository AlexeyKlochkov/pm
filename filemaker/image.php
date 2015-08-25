<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Image</title>

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
    $nextId = getNextImageId($id);
    $prevId = getPrevImageId($id);
    $result = getImageInfo($id)[0];
    $result["category"] = getImageCategories($id);
    $result["featured"] = getModelFeatured($id);
    //if ($result["usage_info"][0]["start_date"] == "1970-01-01") $result["usage_info"][0]["start_date"] = null;
   // if ($result["usage_info"][0]["end_date"] == "1970-01-01") $result["usage_info"][0]["end_date"] = null;
var_dump($result);
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
        <h1>Image</h1>
        <nav>
            <ul class="pager">
                <?if (isset($id)):?> <li <?if (!isset($prevId) || $prevId<=0) echo "class='previous disabled'"; else echo "class='previous'"?>><a href="image.php?id=<?=$prevId?>"><span aria-hidden="true">&larr;</span>Previous</a></li><?endif;?>
                <label>Id:</label>
                <input type="text" id="goto" value="<?=(isset($id))?$id:""?>">
                <button class="btn btn-default" id="togo">Go</button>
                <?if (isset($id)):?><li <?if (!isset($nextId) || $nextId<=0) echo "class='next disabled'"; else echo "class='next'"?>><a href="image.php?id=<?=$nextId?>"><span aria-hidden="true">&rarr;</span>Next</a></li> <?endif;?>
            </ul>
        </nav>

    </div>
    <form class="form-horizontal" action="add_image.php" method="post" enctype="multipart/form-data">
        <input type="text" id="image_id" name="image_id" hidden="hidden" value="<?=(isset($id))?$id:""?>">
        <fieldset>
            <div class="form-group">
                <?if (isset($id)):?><button type="button" class="btn btn-success" id="newmodel">New image</button><?endif;?>
                <input type="submit" class="btn btn-primary" value="Save changes">
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <legend>Models featured</legend>
                    <!-- Name input-->
                    <?php for ($i=0;$i<5;$i++):?>
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="name"><?=$i+1?></label>
                        <div class="col-md-8">
                            <input id="name" name="featured[]" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["featured"][$i]["featured"]))?$result["featured"][$i]["featured"]:""?>">
                        </div>
                    </div>
                    <?endfor;?>


                    <!-- Name input-->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="name">6</label>
                        <div class="col-md-8">
                            <input id="name" name="featured[]" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["name"]))?$result["name"]:""?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <legend>Preview</legend>
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 400px; height: 300px;">
                            <img data-src="holder.js/100%x100%" alt="..." <?if (isset($id)):?>src="../images/image/i<?=$id?>.jpg"<?endif;?>>
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
                    <legend>Image Dimensions</legend>
                    <!-- Multiple Radios -->
                    <div class="form-group">
                        <div class="col-md-5">
                            <label class=" control-label" for="width">Width</label>
                            <div >
                                <input type="text" name="width" id="width" value="">
                            </div>
                            <label class=" control-label" for="height">Height</label>
                            <div ">
                                <input type="text" name="height" id="height" value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class=" control-label" for="fsize">File size</label>
                            <div >
                                <input type="text" name="fsize" id="fsize" value="">
                            </div>
                            <label class=" control-label" for="resolution">Resolution</label>
                            <div >
                                <input type="text" name="resolution" id="resolution" value="">
                            </div>
                        </div>
                    </div>
                    <legend>Image Source Info</legend>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="other_territory">Stock Ref. Code</label>
                        <div class="col-md-8">
                            <input id="other_territory" name="other_territory" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["other_territory"]))?$result["other_territory"]:""?>">
                        </div>
                    </div>
                <div class="form-group">
                    <label class="col-md-3 control-label" for="other_territory">Image Stock Name</label>
                    <div class="col-md-8">
                        <input id="other_territory" name="other_territory" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["other_territory"]))?$result["other_territory"]:""?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label" for="other_territory">Stock Quote ID</label>
                    <div class="col-md-8">
                        <input id="other_territory" name="other_territory" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["other_territory"]))?$result["other_territory"]:""?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label" for="other_territory">Stock/Photographer</label>
                    <div class="col-md-8">
                        <input id="other_territory" name="other_territory" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["other_territory"]))?$result["other_territory"]:""?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label" for="other_territory">Rep/Stock House</label>
                    <div class="col-md-8">
                        <input id="other_territory" name="other_territory" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["other_territory"]))?$result["other_territory"]:""?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label" for="other_territory">Photographer Name</label>
                    <div class="col-md-8">
                        <input id="other_territory" name="other_territory" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["other_territory"]))?$result["other_territory"]:""?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label" for="other_territory">Rights Managed Type</label>
                    <div class="col-md-8">
                        <input id="other_territory" name="other_territory" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["other_territory"]))?$result["other_territory"]:""?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label" for="other_territory">Royalty Free Type</label>
                    <div class="col-md-8">
                        <input id="other_territory" name="other_territory" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["other_territory"]))?$result["other_territory"]:""?>">
                    </div>
                </div>
                <!-- Multiple Checkboxes -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="media_rights">Image Media Rights</label>
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
                    <label class="col-md-3 control-label" for="other_media">Other Media Rights</label>
                    <div class="col-md-8">
                        <input id="other_media" name="other_media" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["usage_info"][0]["other_media_rights"]))?$result["usage_info"][0]["other_media_rights"]:""?>">
                    </div>
                </div>
                <!-- Address textarea -->
                <div class="form-group">
                    <label class="col-md-3 control-label" for="address">Image Notes</label>
                    <div class="col-md-8">
                        <textarea class="form-control" id="address" name="address"><?=(isset($result["address"]))?$result["address"]:""?></textarea>
                    </div>
                </div>
                </div>
            <div class="form-group">
                <div class="col-md-6">
                    <legend>Apollo Usage</legend>
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
                    <!-- Multiple Radios -->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="territory">Image Territory</label>
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

                    <!-- Is minor radios -->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="isminor">Release Received?</label>
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
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="other_territory">Release Type</label>
                        <div class="col-md-8">
                            <input id="other_territory" name="other_territory" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["other_territory"]))?$result["other_territory"]:""?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="isminor">Image Exclusivity</label>
                        <div class="col-md-8">
                            <label class="radio-inline" for="isminor-1">
                                <input type="radio" name="isminor" id="isminor-1" value="1" <?php if (isset($result["isminor"]) &&  $result["isminor"]===1) echo "checked='checked'"?>>
                                Exclusive
                            </label>
                            <label class="radio-inline" for="isminor-0">
                                <input type="radio" name="isminor" id="isminor-0" value="0" <?php if (isset($result["isminor"]) && $result["isminor"]===0) echo "checked='checked'"?>>
                                Non-Exclusive
                            </label>
                        </div>
                    </div>
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="other_territory">Exclusivity notes</label>
                        <div class="col-md-8">
                            <input id="other_territory" name="other_territory" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["other_territory"]))?$result["other_territory"]:""?>">
                        </div>
                    </div>
                    <!-- Multiple Checkboxes -->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="usage_category">Image Usage Category</label>
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
                        <label class="col-md-2 control-label" for="other_usage">Other Image Category</label>
                        <div class="col-md-8">
                            <input id="other_usage" name="other_usage" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["other_usage"]))?$result["other_usage"]:""?>">

                        </div>
                    </div>
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="other_usage">Original Project Number</label>
                        <div class="col-md-8">
                            <input id="other_usage" name="other_usage" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["other_usage"]))?$result["other_usage"]:""?>">

                        </div>
                    </div>
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="other_usage">Original IPM</label>
                        <div class="col-md-8">
                            <input id="other_usage" name="other_usage" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["other_usage"]))?$result["other_usage"]:""?>">

                        </div>
                    </div>
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="other_usage">OOriginal Art buyer</label>
                        <div class="col-md-8">
                            <input id="other_usage" name="other_usage" type="text" placeholder="" class="form-control input-md" value="<?=(isset($result["other_usage"]))?$result["other_usage"]:""?>">

                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <legend>Asset Storage</legend>
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="is_released">Posting to Asset Library?</label>
                        <div class="col-md-3">
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
                    <!-- Multiple Checkboxes -->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="media_rights">High Resolution Location</label>
                        <div class="col-md-8">
                                <div class="checkbox">
                                    <label for="media_rights-<?=$mr?>">
                                        <input type="checkbox" name="media_rights[]" id="media_rights-<?=$mr?>" value="Server" <?if (isset($result["usage_info"][0]["media_rights"]) &&  strpos($result["usage_info"][0]["media_rights"],$mr)!==false) echo "checked='checked'"?>>
                                        Server
                                    </label><label for="media_rights-<?=$mr?>">
                                        <input type="checkbox" name="media_rights[]" id="media_rights-<?=$mr?>" value="backup" <?if (isset($result["usage_info"][0]["media_rights"]) &&  strpos($result["usage_info"][0]["media_rights"],$mr)!==false) echo "checked='checked'"?>>
                                        Backup
                                    </label>
                                    <label for="media_rights-<?=$mr?>">
                                        <input type="checkbox" name="media_rights[]" id="media_rights-<?=$mr?>" value="Asset Library>" <?if (isset($result["usage_info"][0]["media_rights"]) &&  strpos($result["usage_info"][0]["media_rights"],$mr)!==false) echo "checked='checked'"?>>
                                        Asset Library
                                    </label>

                                </div>
                        </div>
                    </div>
                    <legend>Meta Data</legend>
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
            window.location.href = "image.php?id="+gotoid;
        });
        $("#newmodel").click(function() {
            window.location.href = "image.php";
        });
    });
</script>

