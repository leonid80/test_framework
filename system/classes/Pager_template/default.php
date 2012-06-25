
isset($_GET['p']) ? $cur_page=(int)$_GET['p'] : $cur_page=1;

$t="";
foreach($this->pager_list as $key=>$item)
    if($key==$cur_page)
        $t.="<b>".$key."</b>&nbsp;&nbsp;";
    else
        $t.="<a href='".$item."'>".$key."</a>&nbsp;&nbsp;";
return $t;
