$t='<div><input type="checkbox" name="'.$this->name.'" value="1" class="'.$this->class.'"';

if($this->value==1)
    $t.=' checked="checked"';

$t.='/></div>';

return $t;

 

