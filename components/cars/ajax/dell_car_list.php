<?php

$arr_dell=explode(",",$_POST['str_dell']);


foreach ($arr_dell as $key => $item) 
    {
    Core::$sql->query("DELETE FROM cars_car WHERE(car_id='".$item."')");
    if(mysql_errno()==0)
        Core::$sql->query("DELETE FROM cars_comment WHERE(comment_car_id='".$item."')");
    
    
    }
if(mysql_errno()==0)
    {
    echo "success";
    }

