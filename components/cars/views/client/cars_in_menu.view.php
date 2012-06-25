<h3>Cars</h3>
<ul>
<?foreach($arr_cars as $key=>$item):?>
    <?$href_on_car = ($settings['set_seo_url']==1) ? "car/".$item['car_id'] : "?act=car&amp;car_id=".$item['car_id'] ; ?>
    <li><a href="<?=$href_on_car?>"><?=$item['brand_name']?> <?=$item['model_name']?> <?=$item['car_name']?></a></li>
<?endforeach;?>
</ul>
            