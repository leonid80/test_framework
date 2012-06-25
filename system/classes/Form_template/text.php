$this->value = stripslashes($this->value);
return '<div><input type="text" name="'.$this->name.'" id="'.$this->name.'" class="'.$this->class.'" maxlength="'.$this->maxlength.'" value="'.$this->value.'"  title="'.$this->value.'"  '.$this->free.' /></div>';
