<?php
session_start();
require "functions.php";

$status = $_POST['status'];
$user_id = $_GET['id'];

set_status($user_id, $status);

set_flash_message ('success', 'Статус обновлен!');

redirect_to('users.php');