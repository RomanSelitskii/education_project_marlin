<?php
session_start();
require "functions.php";

$avatar = $_FILES['avatar'];
$user_id = $_GET['id'];

$user = get_user_by_id($user_id);


if ($avatar['name']==''){
    set_flash_message ('danger', 'Не задана картинка!');
    redirect_to('media.php?id=' . $user_id);
} else {
    set_avatar($user_id, $avatar);
    set_flash_message ('success', 'Аватар обновлен!');
    redirect_to('page_profile.php?id='.$user_id);
}