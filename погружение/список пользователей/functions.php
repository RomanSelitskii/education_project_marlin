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

function redirect_to($path){
    header('Location: /auth/'. $path);
}

/*
    Parametrs:
        string — $email
        string — $password

    Description: функция проверки введеной связки пользователя и пароля

    Return value: boolean
*/

function check_user($email, $password){
    //обратиться к базе
    $pdo = new PDO('mysql:host=127.0.0.1; dbname=marlin_users', 'root', 'root');

    //составить запрос
    $sql ='SELECT * FROM users_table WHERE email =:email;';
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    
    //вытащить из базы имя пользователя
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    //если имя и пароль не совпадают - false, Ошибка в имени или пароле
    if ($email == $user['email'] && password_verify ($password, $user['password']) ){
        $user_in_base = TRUE;
    } else {
        $user_in_base = FALSE;
    }

return $user_in_base;
    
}

/*
    Parametrs:

    Description: выводит список пользователей из базы данных

    Return value: array
*/

function users_list(){
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin_users', 'root', 'root');
    $sql = 'SELECT * FROM workers';
    $statement = $pdo->prepare($sql);
    $statement->execute();

    $users = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $users;
}

/*
    Parametrs:
        boolean — $status

    Description: присваивает статус авторизации для пользователя

    Return value: 
*/

function set_auth_status($status){
    $_SESSION['auth_status'] = $status;
}


/*
    Parametrs:
        string — $email
        string — 

    Description: установка роли set_user_role

    Return value: 
*/
function set_user_role($email){
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin_users', 'root', 'root');
    $sql = 'SELECT * FROM users_table WHERE email=:email';
    $statement = $pdo->prepare($sql);
    $statement->execute(['email' => $email]);

    $user_data = $statement->fetch(PDO::FETCH_ASSOC);
    $_SESSION['user_role'] = $user_data['role'];
}