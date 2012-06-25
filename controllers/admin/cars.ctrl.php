<?php

class CtrlCars{
    
    static function index(){
        Core::SetComponent("menu", "menu_main", "left");
        Core::SetComponent("cars", "cars", "center");
    }
    
    static function add_edit(){
        Core::SetComponent("menu", "menu_main", "left");
        Core::SetComponent("cars", "cars_add_edit", "center");
    }
    
}

