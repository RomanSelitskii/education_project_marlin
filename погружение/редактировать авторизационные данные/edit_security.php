<?php
session_start();
require "functions.php";

$email = $_POST['email'];
$password = $_POST['password'];
$user_id = $_GET['id'];

$edit_result = edit_credentials ($user_id, $email, $password);

if ($edit_result == TRUE){
    set_flash_message ('success', 'Почта и пароль обновлены!');
} else {
    set_flash_message ('danger', 'Адрес почты занят!');
}
redirect_to('security.php?id='.$user_id);