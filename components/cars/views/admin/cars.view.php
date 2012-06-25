
<h2>Cars</h2>
<table class="cars_list">
  <tr>
    <td class="a"> 
        <ul class="brand_model_sheet">
        <?foreach ($arr_bm as $brand_name=>$models):?>
             <li><span id="<?=$brand_name?>" onclick="OpenChild('brand', this)"><?=$brand_name?></span></li>
             <li class="in"  id="brand_<?=$brand_name?>">
             <ul>
            <?foreach ($models as $model_name=>$model_id):?>
                 <li><span onclick="GetCarList(<?=$model_id?>)"><?=$model_name?></span></li>
            <?endforeach;?>
            </ul></li>
        <?endforeach;?>
        </ul>
    </td>
    <td class="b">
        <form action="" id="car_form">
        <ul id="car_names">
            <li></li>
        </ul>
        <div><input type="button" name="button_dell" style="display:none" onclick="DellCars()" value="Dell car"/></div>
        </form>
        <div class="loading"><img src="images/preloader.gif" alt=""/></div>
    </td>
  </tr>
</table>


