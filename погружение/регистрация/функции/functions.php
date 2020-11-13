<?php

/*
        Parameters: 
                string - $email
        
        Description: взять пользователя из базы по критерию почта

        Return value: boolean
*/

function get_user_from_db($email){
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin_users', 'root', 'root');
    $sql = 'SELECT * FROM users_table WHERE email = :email;';
    $statement = $pdo->prepare($sql);
    $statement->execute(['email' => $email]);
    $is_double = $statement->fetch(PDO::FETCH_ASSOC);
    return $is_double;
}




/*

        Parametrs:
            string — $email
            string — $password
        
        Description: сохранить пользователя в базу

        Return value:
*/

function insert_user_into_db($email, $password){
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin_users', 'root', 'root');
    $sql = 'INSERT INTO users_table (email, password) VALUES (:email, :password);';
    $statement = $pdo->prepare($sql);
    $statement->execute(
        [
        'email' => $email, 
        'password' => password_hash($password, PASSWORD_DEFAULT)
        ]
    );
}



/*
        Parametrs:
            string — $status
            string — $message
            
        Description: подготовить сообщение

        Return value:
*/

function set_flash_message ($status, $message){
    $_SESSION[$status] = $message; 
}



/*
        Parametrs:
            string — $status

        Description: показать сообщение на странице

        Return value:
*/

//
function display_flash_message ($status){
    if(!empty($_SESSION[$status])): 
        echo '<div class="alert alert-'. $status . ' text-dark" role="alert"><strong>Уведомление!</strong>'. $_SESSION[$status] . '</div>';
    endif;
        unset($_SESSION[$status]); 
}



/* 
        Parametrs:
            string — $page

        Description: перенаправить пользователя на указанную страницу $page
*/

function redirect_to($page){
    $path = 'Location: /auth/'. $page; 
    header($path);
}