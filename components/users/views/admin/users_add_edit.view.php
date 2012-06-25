<h2><?=$data['header']?></h2>

<?=$data['msg']?>

<form action="" method="post">
    
<h3><?=$data['form']['user_type']['discription_f']?></h3>
<?=$data['form']['user_type']['tpl']?>

<h3><?=$data['form']['user_login_email']['discription_f']?></h3>
<?=$data['form']['user_login_email']['tpl']?>

<h3><?=$data['form']['user_password']['discription_f']?></h3>
<?=$data['form']['user_password']['tpl']?>


<input type="submit"  value="<?=$data['action']?>" name="go" class="button"/>

</form>