<?php
/**************************************************************************************
 Test task

 Here are defined namespace Core.

 Author: Loginov Leonid
 File revision: 1.0.0

 Copyright (c) 2011 Loginov Leonid
***************************************************************************************/
/**************************************************************************************
 "Core" is a basic namespace for all aplication.
 "Core" include all methods which is nessesary to render page of application.
 Also using this namespace is reference to MySQL.
***************************************************************************************/
class Core
{
    /**
    * This function creates an object of class MySQL and assigns a reference to the variable $sql
    * @param $config is an array that contains the data necessary to connect to the database
    * such as server_name, user_name, user_password and other.
    */
    static function initMySQL()
    {
        require_once "system/3d-party/DBSimple/Generic.php" ;
        self::$sql = DbSimple_Generic::connect('mysql://'.self::$config['db']['user'].':'.self::$config['db']['password'].'@'.self::$config['db']['server'].'/'.self::$config['db']['database']);
        self::$sql->setIdentPrefix(self::$config['db']['table_prefix']."_");
        self::$sql->setErrorHandler(array('Core', 'databaseErrorHandler'));

        mysql_set_charset("utf8") or trigger_error("An error occurred when specifying the encoding for MySQL", E_USER_WARNING);
        define('TABLE_PREFIX', self::$config['db']['table_prefix']."_");
    }



    function databaseErrorHandler($message, $info)
    {
        //
        if (!error_reporting() || !self::$config['debug']['display_mysql_error']) return;
        //
        if(self::$config['debug']['hacker_console'])
            debug($message, $info);
        else{
            echo "SQL Error: $message<br><pre>";
            print_r($info);
            echo "</pre>";
        }
        exit();
    }


    /**
    * This function parses the URL to get the controller name and action name
    * which will later be used to determine which page of the application must be formed.
    */
    static function parseURL()
    {
        if(isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO']!="/")
        {
            $url=explode("/",trim($_SERVER['PATH_INFO'],"/"));
            if($url[0]=="admin")
            {
                define("SIDE", "admin");
                self::$controller = $url[0];
                self::$controller = isset($url[1]) ? $url[1] : "welcome";
                self::$action = isset($url[2]) ? $url[2] : "index";
            }
            else
            {
                define("SIDE", "client");
                self::$controller = $url[0];
                self::$action = isset($url[1]) ? $url[1] : "index";
            }
        }
        else
        {
            define("SIDE", "client");
            self::$controller = "welcome";
            self::$action = "index";
        }
    }

    /**
    * Initializes application controller
    */
    static function initController()
    {
        if(file_exists("controllers/".SIDE."/".self::$controller.".ctrl.php"))
        {
            include_once "controllers/".SIDE."/".self::$controller.".ctrl.php";
            if(method_exists("Ctrl".self::$controller, self::$action)) {
                call_user_func(array("Ctrl".self::$controller, self::$action));
                if(method_exists("Ctrl".self::$controller, "__default")) {
                    call_user_func(array("Ctrl".self::$controller, "__default"));
                }
            }
            else {
              self::showError404();
            }
        } else {
           self::showError404();
        }

    }

    /**
    * Adds an element of an array $section contains all the components you want to display on the page
    */
    static function setComponent($component, $view, $section, $simple = false)
    {
        self::$components[]=array("component" => $component,
                                  "view" => $view,
                                  "section" => $section,
                                  "simple" => $simple,
                                  );
    }

    /*
    * Initializes the components of an application by calling the appropriate method
    */
    static function initComponents()
    {
        foreach(self::$components as $component) {
            if ($component['simple'])
                continue;

            $file_model = "components/{$component['component']}/{$component['component']}.model.php";
            $file_init = "components/{$component['component']}/{$component['component']}.init.php";
            if (file_exists($file_init)) {
                require_once ($file_init);
            } else {
                trigger_error("File of Init not exists", E_USER_ERROR);
            }
            if (file_exists($file_model)) {
                require_once ($file_model);
            }

            if(method_exists("Init{$component['component']}",$component['view']))
                call_user_func(array("Init{$component['component']}", $component['view']));
        }
    }

    /*
    * Forms views of components
    */
    static function renderComponents()
    {
        extract(self::$data_component);

        ob_start();
        foreach(self::$components as $component) {
            $file_view = "components/{$component['component']}/views/".SIDE."/{$component['view']}.view.php";
            if (file_exists($file_view))
            {
                require_once $file_view;
                self::$sections[$component['section']].=ob_get_contents();
                ob_clean();
            } else {
                trigger_error("Template {$component['view']} not exist", E_USER_WARNING);
            }
        }
        self::putData("sections",self::$sections,"L");
    }

    /*
    * Includes the template for the specified component
    * @param $component_name: name of component
    * @param $view_name: the name of the component template
    */
    static function includeView($component, $view_name)
    {
        $arg_list = func_get_args();
        list ($component, $view_name) = $arg_list;

        if (!empty($arg_list[2])) {
            foreach ($arg_list[2] as $key => $item) {
                if(is_int($key)) {
                    if (isset(self::$data_component[$item])) {
                        $$item = self::$data_component[$item];
                    } else {
                        trigger_error("Veraible '{$item}' not exists", E_USER_WARNING);
                    }
                } else {
                    if (isset(self::$data_component[$key])) {
                        trigger_error("Veraible '{$key}' allready exists", E_USER_WARNING);
                    } else {
                        $$key = $item;
                    }
                }
            }
        }

        if ($component == "") {
            include "views/{$view_name}.php";
        } else {
            include "components/{$component}/views/{$view_name}.php";
        }
    }

    /*
    * Forms an array of  JS files
    * @param $js_file: name of JS file without .EXT
    * @param $location: defines the location of the JS file.
    * Can take two values: "common" for files which are located in ./js and "component" for ./components/componen_name/js
    */
    static function appToJSList($js_file, $location = "component")
    {
        self::$arr_js_list[$js_file]=$location;
    }

    /*
    * Forms list of JS files
    */
    static function createJSList()
    {
        $result="";
        if(!empty(self::$arr_js_list))
            foreach (self::$arr_js_list as $js_file => $file_location)
            {
            if ($file_location == "common")
                $result.='<script type="text/javascript" src="js/'.$js_file.'.js"> </script>';
            elseif ($file_location == "component")
                $result.='<script type="text/javascript" src="components/'.$js_file.'/js/'.$js_file.'.js"> </script>';
            }

        self::putData("js_list",$result,"L");
        self::$js_list = $result;
    }

    /*
    * Includes file which contains the layout of page
    */
    static function renderLayout($root = false)
    {
        extract(self::$data_layout);
        if ($root) {
            include_once "views/layout/".self::$layout.".php";
        } else {
            include_once "views/layout/".SIDE."/".self::$layout.".php";
        }
    }

    /*
    * Forms an array of data which will later be used in the files of type "veiw"
    * @param $in_name: name of variable
    * @param $in_value: value of variable
    * @param $area: determines the type of template which will be used this variable ("c" => view file of component, "l" => layout file)
    */
    static function putData($in_name, $in_value, $area="C")
    {
        switch($area)
            {
            case "C":
                if(isset(self::$data_component[$in_name]))
                {
                    trigger_error("Veraible {$in_name} already exist", E_USER_WARNING);
                    return false;
                }
                self::$data_component[$in_name]=$in_value;
            break;

            case "L":
                if(isset(self::$data_layout[$in_name]))
                {
                    trigger_error("Veraible {$in_name} already exist", E_USER_WARNING);
                    return false;
                }
                self::$data_layout[$in_name]=$in_value;
            break;

            default:
                trigger_error("Put data error", E_USER_WARNING);
             break;
            }

    }

    /*
    * Load class(es). Names of classes is a list of arguments passed to the function.
    */
    static function loadClass()
    {
        $list_arg = func_get_args();
        if (!empty($list_arg))
        {
            foreach (func_get_args() as $item)
            {
                include_once "system/classes/{$item}.class.php";
            }
        }
    }

    /*
    *  This function applies when the user addressed to a nonexistent page.
    */
    static function showError404()
    {
        header("HTTP/1.0 404 Not Found");
        include("views/layout/404.php");
        exit();
    }


    static function loadLib()
    {
        $list_arg = func_get_args();
        if (!empty($list_arg))
        {
            foreach (func_get_args() as $item)
            {
                include_once "system/lib/{$item}.lib.php";
            }
        }
    }

    static function loadModelOfComponent($component)
    {
        $file_model = "components/{$component}/{$component}.model.php";
        if (file_exists($file_model)) {
            require_once ($file_model);
        }
    }

    static function setConfig($config)
    {
        self::$config = $config;
    }


    public static $controller;

    public static $action;

    public static $sql;

    public static $layout;

    private static $data_component=array();

    private static $data_layout=array();

    private static $sections=array();

    private static $components=array();

    private static $arr_js_list=array();

    private static $js_list;

    private static $array_warning=array();

    private static $config = array();

}