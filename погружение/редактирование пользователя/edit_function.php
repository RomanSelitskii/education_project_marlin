<?php
session_start();
require "functions.php";

$user_id = $_GET['id'];
$name = $_POST['name'];
$job_title =$_POST['job_title'];
$phone = $_POST['phone'];
$address = $_POST['address'];


edit_general_info ($user_id, $name, $job_title, $phone, $address);
set_flash_message('success', 'Профиль успешно обновлен!');
redirect_to('users.php');