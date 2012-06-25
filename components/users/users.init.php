<?php

class InitUsers{
    
    
    static function users()
        {
//        Core::AppToJSList("cars");    
        ModelUsers::GetListUsers();
        }
        
    static function users_add_edit()
        {
        ModelUsers::UsersAddEdit();
        }
        
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
    

}
