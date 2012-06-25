<?php

class ModelLogin{

    static function Login()
        {
        if (isset($_GET['logout'])) {
            self::Logout();
        }
        $msg = "";
        Core::loadClass("form","error");
        Core::loadLib("crypt");

        $Error = new ClassError();
        $Form  = new ClassForm($Error,TABLE_PREFIX."user","","","");

        $Form->AddField(new ClassField("user_login_email","",$param=array("discription"=>"E-mail","fill"=>1,"check"=>"email","template"=>"text","class"=>"base_i w300")));
        $Form->AddField(new ClassField("user_password","",$param=array("discription"=>"Пароль","fill"=>1,"template"=>"text","class"=>"base_i w300")));

        if(isset($_POST['go']))
            {
            $Form->CheckFields();

            if($Error->no_error)
                {
                $error = false;
                $user=Core::$sql->selectRow("SELECT * FROM ?_user WHERE (user_login_email=?)",$Form->fields['user_login_email']->value);
                if(count($user)==0) {
                    $error = true;
                } else {
                    if ($Form->fields['user_password']->value != LibCrypt::decrypt($user['user_password']))
                        $error = true;
                }

                if($error)
                    {
                    $Error->AddError("Пара логин/пароль введена неверно");
                    }
                else
                    {
                     $_SESSION['user_login_email']=$user['user_login_email'];
                     $_SESSION['user_id']=$user['user_id'];

                     $time=time()+1209600;
                     setcookie('user_login_email',$user['user_login_email'],$time,"/");
                     setcookie('user_id',$user['user_id'],$time,"/");

                    header("location:/admin");
                    }
                }
             $msg=$Error->ErrorReport();
            }

        if($Error->no_error)
             $Form->DataInit();

        $Form->fields['user_password']->value="";//$this->encrypt->decode($Form->fields['user_password']->value);


        $Form->DrawTemplates();
        Core::PutData("form", $Form->form);
        Core::PutData("msg", $msg);


        }

    static function Logout()
        {
        setcookie('user_login_email', '', time() - 3600,"/");
        setcookie('user_id', '', time() - 3600,"/");
        unset($_SESSION['user_login_email']);
        unset($_SESSION['user_id']);
        header("Location:/admin");
        exit();
        }

}
