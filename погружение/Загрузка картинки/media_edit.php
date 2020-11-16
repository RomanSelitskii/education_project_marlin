<?php
session_start();
require "functions.php";

$avatar = $_FILES['avatar'];
$user_id = $_GET['id'];

$user = get_user_by_id($user_id);


set_avatar($user_id, $avatar);
set_flash_message ('success', 'Картинка обновлена!');
redirect_to('page_profile.php?id='.$user_id);
