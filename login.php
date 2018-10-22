<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('include/config.php');

if (!empty($_POST['email'])) {
    $email = clean_input($_POST['email']);
    $user_manager = new UserManager(DB_HOST, DB_NAME, DB_USER, DB_PASS);
    $user_id = $user_manager->getUserId($email);

    if ($user_id == 0) {
        //Skapa ny user
        $user_id = $user_manager->createUser($email);

        if ($user_id > 0) {
            $note_manager = new NotesManager(DB_HOST, DB_NAME, DB_USER, DB_PASS);
            $note_manager->createNote($user_id, "Din första anteckning. Klicka här för att redigera. (Eller dra mig till papperskorgen för all del.. )");
        }
    }
    
    if ($user_id > 0) {
        //Logga in 
        $_SESSION['user'] = $user_id;
        header('location: notes.php');
        exit();
    }
}

//Fel. Tillbaka till startsidan.
header('location: index.php');

function clean_input($text) {
    $text = str_replace("'", "", $text);
    $text = str_replace("\"", "", $text);
    $text = trim($text);

    return $text;
}
?>