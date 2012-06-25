<?php

class CtrlUsers{
    
    static function index(){
        Core::SetComponent("menu", "menu_main", "left");
        Core::SetComponent("users", "users", "center");
    }
    
    static function add_edit(){
        Core::SetComponent("menu", "menu_main", "left");
        Core::SetComponent("users", "users_add_edit", "center");
    }
    
}

