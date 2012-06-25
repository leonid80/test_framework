<?php

class CtrlWelcome{
    
    static function index(){
        Core::SetComponent("menu", "menu_main", "left");
        Core::SetComponent("_simple_text", "main","center", true);
    }
    
}

