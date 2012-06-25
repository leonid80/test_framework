<?php

class CtrlLogin{
    
    static function index(){
        Core::$layout="layout_login";
        Core::SetComponent("login", "form_login", "center");
    }
    
}

