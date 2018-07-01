<?php
    require_once 'function.php';
    if(!empty($_POST['sign_in']){
        if (!empty($_POST['login']&&!empty($_POST['password'])) {
            $login = (string)$_POST['login'];
            $password = md5($_POST['password']);
            $validate = getValidate($login, $password);
            if ($validate) {
                $_SESSION['login'] = $login;
                redirect('index');
            } else {
                redirect('register');
            }
        }
    }
    if(!empty($_POST['register']){
        if (!empty($_POST['login']&&!empty($_POST['password'])) {
            $newlogin =  (string)$_POST['login'];
            $newpassword =  md5($_POST['password']);
            registration($newlogin, $newpassword);
            $_SESSION['login'] = $newlogin;
            redirect('index.php');
         } else {
            echo 'Для регистрации введите логин и пароль';
         }
    }
?>

<p>Введите данные для регистрации или войдите, если уже регистрировались:</p>

<form method="POST">
    <input type="text" name="login" placeholder="Логин" />
    <input type="password" name="password" placeholder="Пароль" />
    <input type="submit" name="sign_in" value="Вход" />
    <input type="submit" name="register" value="Регистрация" />
</form>