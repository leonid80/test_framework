<?php

class InitCars{
    
    
    static function cars()
        {
        Core::AppToJSList("cars");    
        ModelCars::GetListBrandAndModel();
        }
        
    static function cars_on_main()
        {
        ModelCars::GetListCars();
        }
        
    static function cars_in_menu()
        {
        //ModelCars::GetListCars();
        }
        
    static function cars_add_edit()
        {
        ModelCars::CarsAddEdit();
        }
        
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
    

}
