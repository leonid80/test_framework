$t='';
  foreach ($this->arr_list as $key=>$val)
    {
    if($this->value==$key)
      $t.='<div><input name="'.$this->name.'" checked="checked" id="'.$this->name.'" class="'.$this->class.'" type="radio" value="'.$key.'"/>'.$val.'</div>';
    else
      $t.='<div><input name="'.$this->name.'" id="'.$this->name.'" class="'.$this->class.'" type="radio" value="'.$key.'"/>'.$val.'</div>';
    }
    

return $t;
