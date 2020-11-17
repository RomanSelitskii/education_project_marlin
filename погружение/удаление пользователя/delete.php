<?php
session_start();
require "functions.php";

if (!$_SESSION['auth_status']){
    set_flash_message('danger', 'Вы не авторизованы');
    redirect_to('page_login.php');
}

if($_SESSION['user_role'] != 'admin'):
    if($_SESSION['user_id'] != $_GET['id']):
        set_flash_message('danger', 'Можно удалить только свой профиль!');
        redirect_to('users.php');
    endif;
endif;

$user_id = $_GET['id'];

if ($_SESSION['user_id'] == $user_id){
    delete_user($user_id);
    set_auth_status(FALSE);
    redirect_to('page_login.php');
} else {
    delete_user($user_id);
    set_flash_message ('success', 'Пользователь удален!');
    redirect_to('users.php');
}






