<?php

$car_list=Core::$sql->selectCol("SELECT car_id as ARRAY_KEY, car_name 
                       FROM cars_car
                       WHERE(car_model_id='".$_POST['model_id']."')");

if(mysql_errno()==0)
    {
    echo json_encode($car_list);
    }

