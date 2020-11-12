<?php
session_start();

$email = $_POST['email'];
$password = $_POST['password'];

$pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin_users', 'root', 'root');
$sql = 'SELECT * FROM users_table WHERE email = :email;';
$statement = $pdo->prepare($sql);
$statement->execute(['email' => $email]);

$is_double = $statement->fetch(PDO::FETCH_ASSOC);

if($is_double == false) {
    $sql = 'INSERT INTO users_table (email, password) VALUES (:email, :password);';
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'email' => $email, 
        'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

    $_SESSION['status'] = 'success';
    $_SESSION['message'] = 'Эл. адрес будет вашим логином при авторизации';
    header('Location: /auth/page_login.php');
    exit;
} else {
    $_SESSION['status'] = 'danger';
    $_SESSION['message'] = 'Такой эл. адрес уже есть в базе!';
    header('Location: /auth/page_register.php');
    exit;
}
?>
