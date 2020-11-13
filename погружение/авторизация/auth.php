<?php
session_start();

require "functions.php";

$user_in_db = check_user($_POST['email'], $_POST['password']);

if ($user_in_db){
    redirect_to('users.php');
} else {
    redirect_to('page_login.php');
    set_flash_message('danger', 'Ошибка в логине или пароле');
}
?>