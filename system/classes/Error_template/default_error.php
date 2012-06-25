$t='<div class="error">При заполнении формы были допущены следующие ошибки: <br>';
foreach($this->arr_error as $error)
    $t.='&nbsp;&nbsp;&nbsp;- '.$error.'<br/>';
$t.='</div>';

return $t;
