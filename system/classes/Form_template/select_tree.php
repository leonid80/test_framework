$t='<div><select name="'.$this->name.'" id="'.$this->name.'" class="'.$this->class.'">';
    foreach ($this->arr_list as $key=>$val)
        {
        if($this->value==$val["id"])
             $t.='<option selected="selected"  value="'.$val[id].'">'.str_repeat("&nbsp;",$val["level"]-1).$val[name].'</option>';
        else
             $t.='<option  value="'.$val[id].'">'.str_repeat("&nbsp;&nbsp;",$val["level"]-1).$val[name].'</option>';
        }
$t.='</select></div>';

return $t;


