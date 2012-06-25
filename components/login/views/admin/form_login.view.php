<h2>Авторизация</h2>
    <p style="color:green; font-size:14px"><?=$msg?></p>
    <form action="" method=post>


        <h3><?=$form['user_login_email']['discription_f']?></h3>
        <?=$form['user_login_email']['tpl']?>

        <h3><?=$form['user_password']['discription_f']?></h3>
        <?=$form['user_password']['tpl']?>

        <br/>

        <input type="submit" class="button" value='Отправить' name='go'>

    </form>
<br>
