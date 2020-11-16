<?php
session_start();
require "functions.php";

$user_id = $_GET['id'];

delete_user($user_id);
set_flash_message ('success', 'Пользователь удален!');
redirect_to('users.php');