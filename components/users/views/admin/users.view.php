<h2>Users</h2>

<form action="" method="post" onSubmit="return ConfirmDell()">

<table class="table_sheet">
    <thead>
    <tr>
        <td>Дата</td>
        <td>Логин</td>
        <td class="dell"></td>
    </tr>
    </thead>
    <tbody>
    <?foreach($arr_users as $key=>$item):?>
    <tr>
        <td><?=$item['user_date_f']?></td>
        <td><a href="admin/users/add_edit?id=<?=$item['user_id']?>"><?=$item['user_login_email']?></a></td>
        <td><input type="checkbox" name="arr_dell[]" value="<?=$item['user_id']?>"></td>
    </tr>
    <?endforeach;?>
    <tr>
        <td class="footer" colspan="3"><input type="submit" value="Dell" name="dell" class="button"/></td>
    </tr>
    </tbody>
</table>

<div>
    <?if($pager['amount_page'] > 1):?>
        <b>Pages:</b> <?=$pager['list']?>
    <?endif;?>
</div>

</form>
