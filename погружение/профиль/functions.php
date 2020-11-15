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
            string — $role (default value 'user')
        
        Description: добавить пользователя в базу, если пользователь уже есть в базе - то обновление информации о нем

        Return value: int (user_id)
*/
function insert_user_into_db($email, $password, $role='user'){
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin_users', 'root', 'root');
    $sql = 'SELECT * FROM users_table WHERE email = :email;';
    $statement= $pdo->prepare($sql);
    $statement->execute(['email' => $email]);
    $user_data = $statement->fetch(PDO::FETCH_ASSOC);
    
    
    //проверяем, существует ли id пользователя
    if(!$user_data['id']){
    
    //пользователь не существует - делаем вставку новой записи
    $sql = 'INSERT INTO users_table (email, password, role) VALUES (:email, :password, :role);';
    $statement = $pdo->prepare($sql);
    $statement->execute(
        [
        'email' => $email, 
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => $role
        ]
    );
    
    $user_id = $pdo->lastInsertId();

    } else {
    //пользователь существует - делаем обновление записи
    $user_id = (int) $user_data['id'];
    
    $sql = 'UPDATE users_table SET email = :email, password = :password, role = :role WHERE id = :user_id;';
    $statement = $pdo->prepare($sql);
    $statement->execute(
        [
        'email' => $email, 
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => $role,
        'user_id' => $user_id
        ]
    );
    
    }
    return (int) $user_id;
}

/*
        Parametrs:
            string — $status
            string — $message
            
        Description: подготовить сообщение

        Return value: NULL
*/
function set_flash_message ($status, $message){
    $_SESSION[$status] = $message; 
}


/*
        Parametrs:
            string — $status

        Description: показать сообщение на странице

        Return value: NULL
*/
function display_flash_message ($status){
    if(!empty($_SESSION[$status])): 
        echo '<div class="alert alert-'. $status . ' text-dark" role="alert"><strong>Уведомление!</strong> '. $_SESSION[$status] . '</div>';
    endif;
        unset($_SESSION[$status]); 
}


/* 
        Parametrs:
            string — $page

        Description: перенаправить пользователя на указанную страницу $page

        Return value: NULL
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
    /*
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin_users', 'root', 'root');
    $sql = 'SELECT * FROM workers';
    $statement = $pdo->prepare($sql);
    $statement->execute();

    $users = $statement->fetchAll(PDO::FETCH_ASSOC);
    */

    $pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin_users', 'root', 'root');
    $sql = 'SELECT * FROM users_table, general_information, social_links WHERE users_table.id = general_information.user_id AND users_table.id =  social_links.user_id';
    $statement = $pdo->prepare($sql);
    $statement->execute();

    $users = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    return $users;
}

/*
    Parametrs:
        boolean — $status

    Description: присваивает статус авторизации для пользователя

    Return value: NULL
*/

function set_auth_status($status){
    $_SESSION['auth_status'] = $status;
}


/*
    Parametrs:
        string — $email

    Description: установка роли set_user_role

    Return value: NULL
*/
function set_user_role($email){
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin_users', 'root', 'root');
    $sql = 'SELECT * FROM users_table WHERE email=:email';
    $statement = $pdo->prepare($sql);
    $statement->execute(['email' => $email]);

    $user_data = $statement->fetch(PDO::FETCH_ASSOC);
    $_SESSION['user_role'] = $user_data['role'];
}


/*
    Parametrs:
        int — $user_id
        string — $name
        string — $job_title
        string — $phone
        string — $address

    Description: добавление общей информации о пользователе

    Return value: NULL
*/ 
function edit_general_info ($user_id, $name, $job_title, $phone, $address){
    
    //делаем запрос к базе для проверки существования пользователя с введенным user_id
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin_users', 'root', 'root');
    $sql = 'SELECT * FROM general_information WHERE user_id = :user_id;';
    $statement= $pdo->prepare($sql);
    $statement->execute(['user_id' => $user_id]);

    $user_data = $statement->fetch(PDO::FETCH_ASSOC);

    //проверка: существует ли строка c user_id в таблице 
    if(!$user_data['user_id']){
    
        
        //строки нет в таблице - вставка
        
        $sql = "INSERT INTO `general_information` (`id`, `user_id`, `name`, `job_title`, `phone`, `address`, `status`, `avatar`) VALUES (NULL, :user_id, :name, :job_title, :phone, :address, '', '');";
        
        $statement= $pdo->prepare($sql);
        $statement->execute([
            'user_id' => $user_id,
            'name' => $name,
            'job_title' => $job_title,
            'phone' => $phone,
            'address' => $address
        ]);
        
        
    
    } else {
        //строка найдена в таблице - обновление
        $sql = 'UPDATE general_information SET name = :name, job_title = :job_title, phone = :phone, address = :address WHERE user_id = :user_id;';
        
        $statement= $pdo->prepare($sql);
        $statement->execute(
            [
                'user_id' => $user_id,
                'name' => $name,
                'job_title' => $job_title,
                'phone' => $phone,
                'address' => $address
            ]
        );
    }

}
/*
    Parametrs:
        int — $user_id
        string — $status

    Description: добавление статуса пользователя

    Return value: NULL
*/
function set_status($user_id, $status){
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin_users', 'root', 'root');
    $sql = 'SELECT * FROM general_information WHERE user_id = :user_id;';
    $statement= $pdo->prepare($sql);
    $statement->execute(['user_id' => $user_id]);

    $user_data = $statement->fetch(PDO::FETCH_ASSOC);
    
    //проверка: существует ли ячейка статус с user_id в таблице 
    if(!$user_data['user_id']){
        
        //ячейки нет в таблице - вставка
        $sql = 'INSERT INTO general_information (status) VALUES (:status) WHERE user_id = :user_id;';
        $statement= $pdo->prepare($sql);
        $statement->execute(
            [
                'user_id' => $user_id,
                'status' => $status
            ]
        );
    } else {
        //ячейка найдена в таблице - обновление
        $sql = 'UPDATE general_information SET status = :status WHERE user_id = :user_id;';
        $statement= $pdo->prepare($sql);
        $statement->execute(
            [
                'user_id' => $user_id,
                'status' => $status
            ]
        );
    }
}

/*
    Parametrs:
        int — $user_id    
        array — $avatar

    Description: установка аватара пользователя

    Return value: NULL
*/
function set_avatar($user_id, $avatar) {
    //блок загрузки изображения

    //директория загрузки
    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/auth/avatars/';

    //создаем уникальное имя файла взяв хэш всего файла
    $avatar_name = 'avatar_' . $user_id;

    //берем расширение файла
    $avatar_file = new SplFileInfo(basename($avatar['name']));
    $avatar_extention = $avatar_file->getExtension();

    //создаем название файла с уникальным именем и прежним расширением, которое привязано к id пользователя
    $avatar_full_name = $avatar_name . "." . $avatar_extention;

    //формируем конечный путь загрузки файла
    $upload_file = $upload_dir . $avatar_full_name;

    if(file_exists($upload_file)){
        //удаляем прежний файл
        unlink($upload_file);

        //загружаем файл
        move_uploaded_file($avatar['tmp_name'], $upload_file);
    } else {
        //загружаем файл
        move_uploaded_file($avatar['tmp_name'], $upload_file);
    }
   
    //блок записи в базу
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin_users', 'root', 'root');
    
    //$sql = 'INSERT INTO general_information (avatar) VALUES (:avatar) WHERE user_id = :user_id;';
    $sql = 'UPDATE general_information SET avatar = :avatar WHERE user_id = :user_id;';
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'avatar' => $avatar_full_name,
        'user_id' => $user_id
    ]);

}





/*
    Parametrs:
        int — $user_id
        string — $vk
        string — $telegram
        string — $instagram

    Description: добавление ссылок на социальные сети

    Return value: NULL
*/
function add_social_links($user_id, $vk, $telegram, $instagram){
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin_users', 'root', 'root');
    
     //проверка
     $sql = 'SELECT * FROM social_links WHERE user_id = :user_id;';
     $statement= $pdo->prepare($sql);
     $statement->execute(['user_id' => $user_id]);
 
     $user_data = $statement->fetch(PDO::FETCH_ASSOC);
 
     //проверка: существует ли строка c user_id в таблице 
     if(!$user_data['user_id']){
    
    //строки нет в таблице, то вставка
    $sql = 'INSERT INTO social_links (user_id, vk, telegram, instagram) VALUES (:user_id, :vk, :telegram, :instagram);';
    $statement= $pdo->prepare($sql);
    $statement->execute(
        [
            'vk' => $vk,
            'telegram' => $telegram,
            'instagram' => $instagram,
            'user_id' => $user_id
        ]
    );
} else {

    $sql = 'UPDATE social_links SET vk = :vk, telegram = :telegram, instagram = :instagram WHERE user_id = :user_id;';
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'vk' => $vk,
        'telegram' => $telegram,
        'instagram' => $instagram,
        'user_id' => $user_id
    ]);

}


}

/*
    Parametrs:
        string — $email

    Description: определение id пользователя и прикрепление через сессию

    Return value: integer
*/

function set_user_id($email){
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin_users', 'root', 'root');
    $sql = 'SELECT * FROM users_table WHERE email=:email';
    $statement = $pdo->prepare($sql);
    $statement->execute(['email' => $email]);

    $user_data = $statement->fetch(PDO::FETCH_ASSOC);
    $_SESSION['user_id'] = $user_data['id'];    
}


/*
    Parametrs:
        int — $user_id

    Description: берет все данные пользователя по id

    Return value: array
*/
function get_user_by_id ($user_id){
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin_users', 'root', 'root');
    $sql = 'SELECT * FROM users_table, general_information, social_links WHERE users_table.id = :user_id AND general_information.user_id = :user_id AND social_links.user_id = :user_id';
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'user_id' => $user_id
    ]);

    $user_data = $statement->fetch(PDO::FETCH_ASSOC);
    
    return $user_data;
}