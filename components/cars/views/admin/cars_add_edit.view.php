<?=$data['msg']?>
<form action="" method="post" enctype="multipart/form-data">

    
    <h3><?=$data['form']['car_model_id']['discription_f']?></h3>
    <div>
    <select name="car_model_id">
       <option value="0">not selected</option>
    <?foreach($data['arr_models'] as $brand_name=>$model_item):?>
        <option value="0"><?=$brand_name?></option>
        <?foreach($model_item as $model_name=>$model_id):?>
            <?if($data['form']['car_model_id']['value']==$model_id):?>
                <option value="<?=$model_id?>" selected="selected">&nbsp;&nbsp;&nbsp;<?=$model_name?></option>
            <?else:?>
                <option value="<?=$model_id?>">&nbsp;&nbsp;&nbsp;<?=$model_name?></option>
            <?endif;?>
        <?endforeach;?>
    <?endforeach;?>
    </select>
    </div>
    
    <h3><?=$data['form']['car_name']['discription_f']?></h3>
    <?=$data['form']['car_name']['tpl']?>
    
    <h3><?=$data['form']['car_description_short']['discription_f']?></h3>
    <?=$data['form']['car_description_short']['tpl']?>
    <h3><?=$data['form']['car_description_full']['discription_f']?></h3>
    <?=$data['form']['car_description_full']['tpl']?>
    <h3><?=$data['form']['car_tech']['discription_f']?></h3>
    <?=$data['form']['car_tech']['tpl']?>
    
    <h3><?=$data['form']['car_test']['discription_f']?></h3>
    <?=$data['form']['car_test']['tpl']?>
    
    <h3>Image</h3>
    <?if($data['action']=="edit" && file_exists("images/cars/".$_GET['id']."_0_s.jpg")):?>
        <img src="images/cars/<?=$_GET['id']?>_0_s.jpg"/>
    <?endif;?>
    <div><input type="file" name="image_car[]"/></div>


<div class="block_bottom">
    <?if($data['action']=="add"):?>
        <input type="submit"  value="Add" name="go"/>
    <?else:?>
        <input type="submit"  value="Edit" name="go"/>
    <?endif;?>
</div>

</form>