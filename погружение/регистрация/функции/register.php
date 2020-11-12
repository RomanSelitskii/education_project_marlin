<?php
session_start();

require "functions.php";

$email = $_POST['email'];
$password = $_POST['password'];

$user = get_user_from_db($email);


if($user == false) {
    insert_user_into_db($email, $password);
   
    set_flash_message ('success', 'Эл. адрес будет вашим логином при авторизации');

    redirect_to('page_login.php');
    exit;
} else {
    
    set_flash_message('danger', 'Такой эл. адрес уже есть в базе!');

    redirect_to('page_register.php');
    exit;
}
?>