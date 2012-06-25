<?php

class CtrlWelcome{
    
    static function index(){
        Core::SetComponent("menu", "menu_main", "left");
        //Core::SetComponent("cars", "cars_in_menu", "left");
        Core::SetComponent("cars", "cars_on_main", "center");
    }
    
}
