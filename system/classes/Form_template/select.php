$t='<div><select name="'.$this->name.'" id="'.$this->name.'" class="'.$this->class.'">';
    foreach ($this->arr_list as $key=>$val)
        {
        if($this->value==$key)
             $t.='<option selected="selected"  value="'.$key.'">'.$val.'</option>';
        else
             $t.='<option  value="'.$key.'">'.$val.'</option>';
        }
$t.='</select></div>';

return $t;


