<?php
session_start();

if (!empty($_POST['text'])) {
    $text = $_POST['text'];
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin_users', 'root', 'root');

    //запрос
    $sql = "SELECT id FROM form WHERE (data= :text)";
    $search = $pdo->prepare($sql);
    $search->execute(['text' => $text]);
    
    $is_duplicate = $search->fetch(PDO::FETCH_ASSOC);
        
        if ($is_duplicate == false) {
            $sql_insert = "INSERT INTO form (data) VALUES (:text);";
            $statement = $pdo->prepare($sql_insert);
            $statement->execute([':text' => $text]);
            //$status_text ='Строка добавлена.';
            $status = 'success';
            
        } else {
           // $status_text ='Строка уже есть в таблице.';
            $status = 'danger';
        }

        $_SESSION['status'] = $status;
        //$_SESSION['status_text'] = $status_text;

        header("Location: /tasks/task_10_2.php");
} else {
    header("Location: /tasks/task_10_2.php");
}
