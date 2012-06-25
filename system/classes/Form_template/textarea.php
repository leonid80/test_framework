$this->value = stripslashes($this->value);
return '<div><textarea cols="" rows="" name="'.$this->name.'" id="'.$this->name.'" class="'.$this->class.'">'.$this->value.'</textarea></div>';
