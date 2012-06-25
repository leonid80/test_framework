<?php

class ModelCars{

    static function GetListCars()
        {
        $arr_cars=Core::$sql->select("select brand_name, model_name, car_name, car_description_short, car_id
                                       from ?_car join ?_model ON car_model_id=model_id
                                                  join ?_brand ON model_brand_id=brand_id
                                       order by car_id desc");
        Core::PutData("arr_cars", $arr_cars);
        }

////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

    static function GetListBrandAndModel()
        {
          $temp=array();
          $arr_bm=array();

          $temp = Core::$sql->select("SELECT brand_name, model_name, model_id, car_id
                                          FROM ?_brand
                                          LEFT JOIN ?_model ON (brand_id=model_brand_id)
                                          LEFT JOIN ?_car ON (model_id=car_model_id)
                                          WHERE (model_name IS NOT NULL and car_id IS NOT NULL)
                                          ORDER BY brand_name, model_name");

          foreach ($temp as $key => $item)
                $arr_bm[$item['brand_name']][$item['model_name']]=$item['model_id'];

          Core::PutData("arr_bm", $arr_bm);
        }

////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

    static function CarsAddEdit()
        {
        $msg="";
        $data=array();

        if(isset($_GET['id']))
            {
               $data['action']=$action=$data['header']="edit";
            $data['header']="Car edit";
            }
        else
            {
               $data['action']=$action=$data['header']="add";
            $data['header']="Car add";
            }

        Core::loadClass("error");
        Core::loadClass("form");
        $Error = new ClassError();
        $Form     = new ClassForm($Error,TABLE_PREFIX."car","car_id","id",$action);

        $arr_models=Core::$sql->selectCol("SELECT brand_name as ARRAY_KEY_1, model_id, model_name  as ARRAY_KEY_2 FROM ?_brand JOIN ?_model ON brand_id=model_brand_id ORDER BY brand_name, model_name");
        $data['arr_models']=$arr_models;

        $Form->AddField(new ClassList("car_model_id","",$param=array("discription"=>"Brand/model name","fill"=>1,"template"=>"","class"=>"base_i w300"), array()));

        $Form->AddField(new ClassField("car_name","",$param=array("discription"=>"Car name","fill"=>1,"template"=>"text","class"=>"base_i w300")));

        $Form->AddField(new ClassField("car_description_short","",$param=array("discription"=>"Short descritption","fill"=>1,"template"=>"textarea","class"=>"base_i w800 h50")));
        $Form->AddField(new ClassField("car_description_full","",$param=array("discription"=>"Full descritption","fill"=>1,"template"=>"textarea","class"=>"base_i w800 h200")));
        $Form->AddField(new ClassField("car_tech","",$param=array("discription"=>"Technical characteristics","fill"=>0,"template"=>"textarea","class"=>"base_i w800 h200")));

        $Form->AddField(new ClassField("car_test","",$param=array("discription"=>"Technical","fill"=>0,"template"=>"CKEditor","class"=>"")));


        if(isset($_POST['go']))
            {
            $_POST['car_description_full']=str_replace("\r\n", "<br/>", $_POST['car_description_full']);
            $_POST['car_tech']= str_replace("\r\n","<br/>", $_POST['car_tech']);
            $Form->CheckFields();

            if($Error->no_error)
                 $Form->ExecuteQuery($Form->CreateQuery());

            if($Error->no_error)
            {
            Core::loadClass("upload");
            $Img   = new ClassUpload($Error,'image_car',"images/cars/", array("jpg"),$Form->id);

            $Img->RefactFiles();
            $Img->Check();
            if($Error->no_error)
                $Img->ExecuteResizeAndUpload(500,500,150,150);
            }


             $msg=$Error->ErrorReport();
            }



        if($action=="edit" && $Error->no_error)
            {
              $Form->DataFromBase();
            $Form->fields['car_description_full']->value=str_replace("<br/>", "\r\n", $Form->fields['car_description_full']->value);
            $Form->fields['car_tech']->value=str_replace("<br/>", "\r\n", $Form->fields['car_tech']->value);
            }

        if($action=="add" && $Error->no_error)
           $Form->DataInit();


        $Form->DrawTemplates();
        $data['form']=$Form->form;
        $data['msg']=$msg;

        Core::PutData("data", $data);

        }

}
