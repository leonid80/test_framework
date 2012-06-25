<?php

class LibProject{

    static function getSettings()
        {
        $settings=Core::$sql->select("SELECT setting_name as ARRAY_KEY, setting_value FROM cars_setting");

        Core::PutData("settings", $settings);
        }

}


