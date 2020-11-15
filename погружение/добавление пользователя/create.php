<?php
session_start();
require "functions.php";

$email = $_POST['email'];
$password = $_POST['password'];

$name = $_POST['name'];
$job_place = $_POST['job_place'];
$phone = $_POST['phone'];
$address = $_POST['address'];

$status = $_POST['status'];
$avatar = $_FILES['avatar'];

$vk = $_POST['vk'];
$telegram = $_POST['telegram'];
$instagram =$_POST['instagram'];


$user_id = insert_user_into_db($email, $password);

edit_general_info ($user_id, $name, $job_place, $phone, $address);

set_status($user_id, $status);

set_avatar($user_id, $avatar);

add_social_links($user_id, $vk, $telegram, $instagram);

set_flash_message('success', 'Данные пользователя добавлены!');

redirect_to('users.php');