<?php

class ModelUsers{
    
    static function GetListUsers()
    {
        
      if(isset($_POST['dell']))
        {
        foreach($_POST['arr_dell'] as $user_id)
            Core::$sql->query("DELETE FROM ?_user WHERE(user_id=?d)",$user_id);
        }

      Core::loadClass("pager");
        
      $Pager = new ClassPager(array('record_on_page'=>30,'get_name'=>'p','limit_href'=>10,'href'=>'admin/users'));

      
      $arr_users = Core::$sql->selectPage(
           $Pager->amount_record,
            "SELECT *,DATE_FORMAT(user_date, '%d.%m.%y') as user_date_f  
            FROM ?_user
            ORDER BY user_date desc, user_id desc
            LIMIT ?d, ?d",
            $Pager->start_record, $Pager->record_on_page
        );

     $Pager->CreatePager();
     Core::PutData("pager", $Pager->pager_data);
     Core::PutData("arr_users", $arr_users);
    }
        
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
        
    static function UsersAddEdit()
    {
        $msg="";
        $data=array();
        
        if(isset($_GET['id'])) {
               $data['action']=$action=$data['header']="edit";
            $data['header']="User edit";
        } else {
               $data['action']=$action=$data['header']="add";
            $data['header']="User add";
        }
        
        Core::loadClass("error","form");
        Core::loadLib("crypt");
        $Error = new ClassError();
        $Form     = new ClassForm($Error,TABLE_PREFIX."user","user_id","id",$action);
            
        $Form->AddField(new ClassField("user_login_email","",$param=array("discription"=>"User name","fill"=>1,"template"=>"text","class"=>"base_i w300")));
        $Form->AddField(new ClassField("user_password","",$param=array("discription"=>"User password","fill"=>1,"template"=>"text","class"=>"base_i w300")));

        $arr_user_type = array("C"=>"Пользователь","A"=>"Администратор");
        $Form->AddField(new ClassList("user_type","",$param=array("discription"=>"User type","fill"=>1,"template"=>"select","class"=>"base_i w300"), $arr_user_type));


        $append = array();
        if ($action == "add") {
            $append['user_date'] = date("Y-m-d");
        }

        if(isset($_POST['go'])) {
            $Form->CheckFields();
            if($Error->no_error){
                $Form->fields["user_password"]->value = LibCrypt::encrypt($Form->fields["user_password"]->value);
                $Form->ExecuteQuery($Form->CreateQuery($append));
            }
             
             $msg=$Error->ErrorReport();
        }
        
        if($action=="edit" && $Error->no_error) {
              $Form->DataFromBase();
            $Form->fields["user_password"]->value = LibCrypt::decrypt($Form->fields["user_password"]->value);
        }
        
        if($action=="add" && $Error->no_error)
           $Form->DataInit();
        

        $Form->DrawTemplates();
        $data['form']=$Form->form;
        $data['msg']=$msg;

        Core::PutData("data", $data);
        
    }

}
