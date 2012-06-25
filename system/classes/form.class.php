<?php
/**************************************************************************************
 Test task

 Here are defined classes CForm, CField, CList.

 Author: Loginov Leonid
 File revision: 1.0.0

 Copyright (c) 2011 Loginov Leonid
***************************************************************************************/

define('F_FORM','system/classes/Form_template/');

/**************************************************************************************
 "CForm" responsible for treatment forms.
 Checks whether the user filled field.
 Rresponsible for in an insert, update and retrieve data from database.
***************************************************************************************/
class ClassForm
{

    public function __construct(&$error,$table_name="",$name_field_id="",$id_key="",$action="")
    {
        $this->error   = $error;
        $this->table   = $table_name;
        $this->name_field_id=$name_field_id;
        $this->action=$action;
        $this->get_structure=true;

        /*
        * Id record in the table can be passed to the constructor, either directly (a number) and
        * through the name of GET parameter
        */

        if(is_numeric($id_key))
        {
            $this->id=(int)$id_key;
        }
        else
        {
            isset($_GET[$id_key]) ? $this->id=(int)$_GET[$id_key] : $this->id=0;
        }

    }

    /*
    * Adds a object of class CField to an array of fields
    * @$field - object reference of class CField
    */
    public function addField(&$field)
    {
        $this->fields[$field->name]=$field;
    }


    /*
    * In the loop initializes the values b
    * @$exeption - an array containing the field (exceptions) are not necessary to insert
    */
    public function checkFields($exeption=array())
    {
        count($exeption)==0 ? $exept=false :  $exept=true;

        if(count($this->table_structure)==0 && $this->get_structure)
        {
            $this->getTableStructure();
        }

        foreach($this->fields as $key=>$field)
         {
            if($exept && in_array($field->name,$exeption))
                continue;
            /*
            * if there is a matching, field value in the  POST array then assign it
            */
            if(isset($_POST[$field->name]))
            {
                $this->fields[$key]->value=$_POST[$field->name];
            }
            /*
            * otherwise, assigning a value which is defined by default
            */
            else
            {
                $this->fields[$key]->value=$this->fields[$key]->init_value;
            }

            $this->fields[$key]->checkData($this->error);
        }
    }

    /*
    * Forms a request to insert or change a record
    * @exeption - an array containing the field (exceptions) are not necessary to insert
    */
    public function createQuery($append=array(),$exeption=array())
     {
        $sql="";
        $arr_fields_name=array();
        $arr_fields_value=array();
        count($exeption)==0 ? $exept=false : $exept=true;


        if($append)
            foreach($append as $key=>$item)
            {
                $arr_fields_name[]=$key;
                $arr_fields_value[]="'".$item."'";
                $arr_fields_update[]=$key."='".$item."'";
            }

        foreach($this->fields as $field)
        {
            if($exept && in_array($field->name,$exeption))
                continue;

            $arr_fields_name[]=$field->name;
            $arr_fields_value[]="'".mysql_real_escape_string($field->value)."'";
            $arr_fields_update[]=$field->name."='".mysql_real_escape_string($field->value)."'";
        }

        if($this->action=="add")
        {
            $sql="INSERT INTO ".$this->table." (";
            $sql.=implode(",",$arr_fields_name);
            $sql.=") VALUES(";
            $sql.=implode(",",$arr_fields_value);
            $sql.=")";
        }
        else
        {
            $sql="UPDATE ".$this->table." SET ";
            $sql.=implode(",",$arr_fields_update);
            $sql.=" WHERE(".$this->name_field_id."='".$this->id."')";
        }

        return $sql;
     }


    /*
    * Initializes the appropriate form field values b
    * @exeption - an array containing the field (exceptions) are not necessary to insert
    */
    public function dataFromBase($exeption=array())
    {
        count($exeption)==0 ? $exept=false : $exept=true;

        $res=mysql_query("SELECT * FROM ".$this->table." WHERE (".$this->name_field_id."='".$this->id."')");
        $base_data=mysql_fetch_assoc($res);
        foreach($this->fields as $name=>$field)
        {
            if($exept && in_array($field->name,$exeption))
                continue;

             $this->fields[$name]->value=$base_data[$name];
        }
    }

    /*
    * Initializes the values  form fields initial values that were specified by the programmer when declaring the form field
    * @exeption - an array containing the field (exceptions) are not necessary to initial
    */
    public function dataInit($exeption=array())
    {
        count($exeption)==0 ? $exept=false : $exept=true;

        foreach($this->fields as $name=>$field)
        {
            if($exept && in_array($field->name,$exeption))
                continue;

             $this->fields[$name]->value=$field->init_value;
        }
    }

    /*
    * In a loop form the templates for all form fields
    * @exeption - an array containing the field (exceptions) for which there is no need to create a template
    */
    public function drawTemplates($exeption=array())
    {
        count($exeption)==0 ? $exept=false : $exept=true;

        if(count($this->table_structure)==0 && $this->get_structure)
            $this->getTableStructure();

        foreach($this->fields as $key=>$field)
        {
            if($exept && in_array($field->name,$exeption))
                continue;

            $this->form[$field->name]=get_object_vars($field);
            $this->form[$field->name]['tpl']=$field->drawTemplate();
        }
    }


    /*
    * Executes a query on table to further the formation of messages about the result of work
    * @sql - string of SQL query
    * @location - determines where the user is redirected after the request, if $ location == "" then remain on the same page
    */
    public function executeQuery($sql,$location="")
    {
        mysql_query($sql);
        $last_id=mysql_insert_id();
        if(mysql_error()!=0)
        {
            $this->error->addError("Произошла ошибка записи данных");
            return;
        }
        elseif($this->id==0)
        {
            /*
            * returns a ID inserted record
            */
             $this->id=$last_id;
        }

        if($location!=="")
        {
            header("Location:".$location);
            exit();
        }

        if($this->action=="edit")
        {
            $this->error->setSuccessStr("Запись успешно изменена");
        }
        elseif ($this->action=="add")
        {
            $this->error->setSuccessStr("Запись успешно добавлена");
        }

    }


    /*
    * Gets the table structure and in accordance with it forms a type of validation for the field and the maximum length of field
    */
   public function getTableStructure()
    {
        $data=array();
        $res=mysql_query("DESCRIBE ".$this->table);
        while ($row = mysql_fetch_array($res))
        {
            $data[]=$row;
        }

        /*
        * array of correspondences between the type of field, and type of checking fields, which will be used by CheckData ()
        */
        $correspond_type=array('tinyint'=>'int','smallint'=>'int','mediumint'=>'int','int'=>'int','integer'=>'int','bigint'=>'int','year'=>'int',
                               'float'=>'float','double'=>'float','double precision'=>'float',
                               'date'=>'date',
                               'char'=>'default','varchar'=>'default','tinyblob'=>'default','tinytext'=>'default','blob'=>'default','text'=>'default','mediumblob'=>'default','mediumtext'=>'default','longblob'=>'default','longtext'=>'default'
        );

        /*
        * an array of correspondences between the type of field and maximum length for the field
        */
        $correspond_len=array('date'=>10,'tinyblob'=>256,'tinytext'=>256,'blob'=>65536,'text'=>65536,'mediumblob'=>16777216,'mediumtext'=>16777216,'longblob'=>4294967296,'longtext'=>4294967296);

        foreach ($data as $key=>$item)
        {
            preg_match("/([a-z]+)\(?([0-9]+)?\)?/",$item['Type'], $match);

            $this->table_structure[$item['Field']]['type_base']=$match[1];
                if(isset($correspond_type[$match[1]]))
                    $this->table_structure[$item['Field']]['type']=$correspond_type[$match[1]];

                if(isset($match[2]))
                    $this->table_structure[$item['Field']]['len']=$match[2];

                if(isset($correspond_len[$match[1]]))
                    $this->table_structure[$item['Field']]['len']=$correspond_len[$match[1]];

        }

        /*
        *in accordance with the result, assigning a type of scan fields and the maximum length of the field
        */
        foreach($this->fields as $key=>$field)
        {
            if(isset($this->table_structure[$key]['type']) && $this->fields[$key]->type_check=="")
                $this->fields[$key]->type_check=$this->table_structure[$key]['type'];

            if(isset($this->table_structure[$key]['len']) && $this->fields[$key]->maxlength==0)
                $this->fields[$key]->maxlength=$this->table_structure[$key]['len'];
        }

    }

    /*
    * array that stores the field of form as objects
    */
    public $fields=array();

    /*
    * array that stores the generated form templates
    */
    public $form = array();

    /*
    * reference to an instance of class CError
    */
    private $error;

    /*
    * type of action: add / edit
    */
    private $action;

    /*
    * table name, which is supposed to keep the value of fields
    */
    private $table;

    /*
    * the name of the ID of field in the table
    */
    private $name_field_id;

    /*
    * ID of record in the table
    */
    public $id;


    /*
    * flag that determines need to get the table structure or not
    */
    public $get_structure;

    /*
    * array that stores the table structure (field types and lengths)
    */
    private $table_structure = array();

}

/**************************************************************************************
 "CField" class for working with form field
***************************************************************************************/
class ClassField
{

    function __construct($name,$init_value,$param)
    {
        $this->name=$name;
        $this->init_value=$init_value;

        isset($param['class']) ? $this->class=$param['class'] : $this->class="";

        isset($param['free']) ? $this->free=$param['free'] : $this->free="";

        isset($param['check']) ? $this->type_check=$param['check'] : $this->type_check="";

        isset($param['maxlength']) ? $this->maxlength=$param['maxlength'] : $this->maxlength=0;

        isset($param['fill']) ? $this->fill=(int)$param['fill'] : $this->fill=0;

        isset($param['template']) ? $this->template=$param['template'] : die("ошибка: не определён шаблон для ".$this->name);

        isset($param['discription']) ? $this->discription=$param['discription'] : $this->discription="";

        isset($param['discription']) && $this->fill==1 ? $this->discription_f=$param['discription']."<span class='red'>*</span>" : $this->discription_f=$param['discription'];

    }

    /*
    * Checks whether correctly filled form field
    * @$error - object reference of class CField
    */
    public function checkData(&$error)
    {
        $this->value=trim($this->value);

        if(strlen($this->value) > $this->maxlength)
        {
            $error->addError("длинна данных в поле\"".$this->discription."\" превышает допустимое значение");
            return;
        }

        if($this->fill == 1 && ($this->value == "" || (strtolower(get_class($this))=="classlist" && $this->value === "0")))
        {
            $error->addError("поле \"".$this->discription."\" не должно быть пустым");
            return;
        }

        if(is_array($this->type_check))
        {
            if(!preg_match($this->type_check['regex'],$this->value))
            {
                $error->addError($this->type_check['err_msg']." (поле: \"".$this->discription."\")");
            }
            return;
        }

        switch($this->type_check)
        {
            case 'default':
                $this->value=addslashes(htmlspecialchars($this->value,ENT_QUOTES));
            break;

            case 'int':
                $this->value=(int)$this->value;
            break;

            case 'float':
                $this->value=(float)$this->value;
                $this->value=str_replace(',','.',$this->value);
            break;

            case 'date':
                if(!ereg("([[:digit:]]{2})\.([[:digit:]]{2})\.([[:digit:]]{4})",$this->value,$arr_date))
                    $error->addError("некорректный формат даты (поле: \"".$this->discription."\")");
                else
                    $this->value=$arr_date[3].".".$arr_date[2].".".$arr_date[1];
            break;

            case 'email':
                if(!preg_match("/^[0-9a-z_\-\.]+@[0-9a-z_\-\.]+\.[a-z]{2,4}$/i",$this->value) && $this->value!="")
                    $error->addError("некорректный формат электронного адреса (поле: \"".$this->discription."\")");
            break;

            default:
                $this->value=addslashes(htmlspecialchars($this->value,ENT_QUOTES));
            break;
        }

    }

    /*
    * Forms a template for a form field
    */
    public function drawTemplate()
    {
        if($this->template=='')
            return;

        $str_code = file_get_contents(F_FORM.$this->template.".php");
        return eval($str_code);
    }

    /*
    * field name in database
    */
    public $name;

    /*
    * field description (title in local language)
    */
    public $discription;

    /*
    * field description (title in local language), with a a mark that it necessarily has to be filled
    */
    public $discription_f;

    /*
    * name of CSS class which will be applied to this field
    */
    public $class;

    /*
    * the maximum length of valeu for form field
    */
    public $maxlength;

    /*
    * flag, which determines the field is required or not (0 - this field is optional, 1 - required)
    */
    public $fill;

    /*
    * type of check (possible values: int, float, date, email, reg express)
    */
    public $type_check;

    /*
    * arbitrary data that can be inserted into the html representation of the form fields
    */
    public $free;

    /*
    * value of field
    */
    public $value;

    /*
    * template of form field
    */
    public $template;

    /*
    * the initial value of the field
    */
    public $init_value;

    /*
    * object reference of class CField
    */
    private $error;

}

/**************************************************************************************
 "CList" class for working with form field  (type 'select')
 extends CField
***************************************************************************************/
class ClassList extends ClassField
{

    function __construct($name,$init_value,$param,$arr_list)
    {
        parent::__construct($name,$init_value,$param);
        $this->arr_list=$arr_list;
    }

    /*
    * an array containing the values the list (option values)
    */
    public $arr_list;

}
