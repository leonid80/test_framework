if(ereg("([[:digit:]]{4})[\.\-]([[:digit:]]{2})[\.\-]([[:digit:]]{2})",$this->value,$arr_date))
   $this->value=$arr_date[3].".".$arr_date[2].".".$arr_date[1];

$t="";

if(!defined('DATE_PICKER'))
    {
    $t="<link rel='stylesheet' href='".BASE_HREF."../js/datepicker/datepicker.css' type='text/css' media='screen' title='Flora (Default)'>
        <script src='".BASE_HREF."../js/datepicker/datepicker.js'></script>";
    define('DATE_PICKER',TRUE);
    }

$t.="
<script>
 $(document).ready(function(){
    $('#$this->name').datepicker();
  });
</script>
  
<input type='text' id='$this->name' name='$this->name' value='$this->value' class='$this->class' />
";
return ($t);


