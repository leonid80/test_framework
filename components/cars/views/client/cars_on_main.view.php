
<h2>Main page</h2>

<?foreach($arr_cars as $key=>$item):?>
    <?$href_on_car = ($settings['set_seo_url']==1) ? "car/".$item['car_id'] : "?act=car&amp;car_id=".$item['car_id'] ; ?>
    <div class="car_on_main">
    <table>
      <tr>
        <td class="a">
        <?if(file_exists("images/cars/".$item['car_id']."_0_s.jpg")):?>
            <a href="<?=$href_on_car?>"><img src="images/cars/<?=$item['car_id']?>_0_s.jpg" alt="" /></a> 
        <?else:?>
            <a href="<?=$href_on_car?>"><img src="images/nofoto.gif" alt="" /></a> 
        <?endif;?>
        </td>
        <td class="b"> 
        <?=stripslashes($item['car_description_short'])?>
        </td>
      </tr>
    </table>
        <a href="<?=$href_on_car?>"><?=$item['brand_name']?> <?=$item['model_name']?> <?=$item['car_name']?></a>
    <div class="clear"></div>
    </div>
<?endforeach;?>
