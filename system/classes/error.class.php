<?php
/**************************************************************************************
 Test task

 Here are defined class CError.

 Author: Loginov Leonid
 File revision: 1.0.0

 Copyright (c) 2011 Loginov Leonid
***************************************************************************************/

define('F_ERROR','system/classes/Error_template/');

/**************************************************************************************
 "CMySQL" responsible for processing and error output to the user.
 Often used in the processing of data received from the form.
***************************************************************************************/
class ClassError
{
    public function __construct($template_error="default_error",$template_success="default_success")
    {
        $this->no_error=true;
        $this->template_error=$template_error;
        $this->template_success=$template_success;
    }

    /*
    * Add new error message in list of errors
    * @param $error: string contains error message
    */
    public function addError($error)
    {
        $this->arr_error[]=$error;
        $this->no_error=false;
    }

    /*
    * Set text which will be output if isn't error
    * @param $str: contains a message which will appear in case of success
    */
    public function setSuccessStr($str)
    {
        $this->success_str=$str;
    }

    /*
    * Output an error message
    */
    public function printError()
    {
        $str_code = file_get_contents(F_ERROR.$this->template_error.".php");
        return eval($str_code);
    }

    /*
    * Output an success message
    */
    public function printSuccess()
    {
        $str_code = file_get_contents(F_ERROR.$this->template_success.".php");
        return eval($str_code);
    }

    /*
    * Output an error report
    */
    public function errorReport()
    {
        if($this->no_error)
            return  $this->PrintSuccess();
        else
            return $this->PrintError();
    }

    /*
    * Array contains list of errors.
    */
    public $arr_error=array();

    /*
    * String contains text of success message.
    */
    public $success_str="";

    /*
    * Flag defining the array $arr_error is empty or not
    */
    public $no_error;


    /*
    * Varaible contains name of template which uses for output error message.
    */
    private $template_error;

    /*
    * Varaible contains name of template which uses for output success message.
    */
    private $template_success;

}
