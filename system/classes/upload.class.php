<?php 


class ClassUpload
{
    var $error;
    var $files_name;
    var $files_arr_refact=array();
    var $files_total;
    var $files_in;
    var $valid_ext;
    var $pref_new_name;
    var $path_upload;
    
    function Upload(&$error,$files_name,$path_upload,$valid_ext=array(),$pref_new_name="")
        {
        $this->error=&$error;        
        $this->files_name=$files_name;    
        $this->path_upload=$path_upload;    
        $this->files_in=false;    
        $this->valid_ext=$valid_ext;
        $this->pref_new_name=$pref_new_name;
        }
        
    function RefactFiles()
        {
        if(isset($_FILES) && count($_FILES)!=0)
            {
//            $this->pref_new_name=="" ? $new_name=time() : $new_name=$this->pref_new_name."_".time();

            foreach($_FILES[$this->files_name]['name'] as $key=>$val)
                {
                if($_FILES[$this->files_name]['size'][$key]!=0)
                    {
                    $path_parts = pathinfo($_FILES[$this->files_name]['name'][$key]);
                    $file_ext=strtolower($path_parts['extension']);
                    $this->files_arr_refact[$key]=array(
                                    'name'=>$_FILES[$this->files_name]['name'][$key],
                                    'type'=>$_FILES[$this->files_name]['type'][$key],
                                    'error'=>$_FILES[$this->files_name]['error'][$key],
                                    'size'=>$_FILES[$this->files_name]['size'][$key],
                                    'tmp_name'=>$_FILES[$this->files_name]['tmp_name'][$key],
                                    'new_name'=>$this->pref_new_name."_".$key,
                                    'ext'=>$file_ext
                                    );
                    
                    }
    
                }
            $this->files_total=count($this->files_arr_refact);
            if($this->files_total > 0)                
                $this->files_in=true;    
            }
        }
    
    function Check()
        {
        foreach ($this->files_arr_refact as $key=>$file) 
            {
            // P?Q P>P2P5Q QP5P< Q P0P7P<P5Q  QP0P9P;P0
            //if($file['size'] > 2097152 )// 2Mb
            if($file['size'] > 52428800 )// 50Mb
                 $this->error->AddError("P=P5P4P>P?QQQP8P<QP9 Q P0P7P<P5Q  QP0P9P;P0(".$file['size'].") - ".$file['name']);
    
            // P?Q P>P2P5Q QP5P< Q P0QQP8Q P5P=P8P5 QP0P9P;P0
            if(!in_array($file['ext'],$this->valid_ext))
                 $this->error->AddError("P=P5P4P>P?QQQP8P<P>P5 Q P0QQP8Q P5P=P8P5 QP0P9P;P0 - ".$file['name']);
            }
        }
        
        
    function ExecuteUpload()
        {
        foreach ($this->files_arr_refact as $key=>$file) 
            {
            if(!move_uploaded_file($file['tmp_name'],$this->path_upload.$file['new_name'].".".$file['ext']))
                 $this->error->AddError("PQP8P1P:P0 P7P0P3Q QP7P:P8 QP0P9P;P0".$file['name']);
            }
        }
        
    function ExecuteResizeAndUpload($maxi_max_width,$maxi_max_height,$mini_max_width,$mini_max_height)
        {
        foreach ($this->files_arr_refact as $key=>$file) 
            {
        
            $in=imagecreatefromjpeg($file['tmp_name']);
    
            $arr_size=getimagesize($file['tmp_name']);
            if($arr_size[0] > $arr_size[1])
                {
                if($maxi_max_width > $arr_size[0])
                   $out_maxi=imagecreatetruecolor($arr_size[0],$arr_size[1]);
                else
                   $out_maxi=imagecreatetruecolor($maxi_max_width,$maxi_max_width*($arr_size[1]/$arr_size[0]));
                
                if($mini_max_width > $arr_size[0])
                    $out_mini=imagecreatetruecolor($arr_size[0],$arr_size[1]);
                else
                    $out_mini=imagecreatetruecolor($mini_max_width,$mini_max_width*($arr_size[1]/$arr_size[0]));
                }
            elseif($arr_size[0] < $arr_size[1])
                {
                if($maxi_max_height > $arr_size[1])
                    $out_maxi=imagecreatetruecolor($arr_size[0],$arr_size[1]);
                else
                    $out_maxi=imagecreatetruecolor($maxi_max_height*($arr_size[0]/$arr_size[1]),$maxi_max_height);
                
                if($mini_max_height > $arr_size[1])
                    $out_mini=imagecreatetruecolor($arr_size[0],$arr_size[1]);
                else
                    $out_mini=imagecreatetruecolor($mini_max_height*($arr_size[0]/$arr_size[1]),$mini_max_height);

                }
            else
                {
                if($maxi_max_height > $arr_size[1])
                    $out_maxi=imagecreatetruecolor($arr_size[0],$arr_size[1]);
                else
                    $out_maxi=imagecreatetruecolor($maxi_max_height,$maxi_max_height);
                
                if($mini_max_height > $arr_size[1])
                    $out_mini=imagecreatetruecolor($arr_size[0],$arr_size[1]);
                else
                    $out_mini=imagecreatetruecolor($mini_max_height,$mini_max_height);
                }
            imagecopyresampled($out_maxi, $in, 0, 0, 0, 0, ImageSX($out_maxi), ImageSY($out_maxi), ImageSX($in), ImageSY($in));
            imagecopyresampled($out_mini, $out_maxi, 0, 0, 0, 0, ImageSX($out_mini), ImageSY($out_mini), ImageSX($out_maxi), ImageSY($out_maxi));
            
                
                
            # PP0P?P8QQ QP<P5P=QQP5P=P=P>P9 P:P0Q QP8P=P:P8 P2 QP0P9P; (q - P:P0QP5QQP2P>, P>Q QQP4QP5P3P> 0 P4P> P;QQQP5P3P> 100)
            imagejpeg($out_maxi,$this->path_upload.$file['new_name']."_b.".$file['ext'],90);
            imagejpeg($out_mini,$this->path_upload.$file['new_name']."_s.".$file['ext'],90);
    
            }
        }

        
        
}

