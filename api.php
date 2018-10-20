<?php 
require_once('include/config.php');

//JSON
header("Content-Type: application/json; charset=UTF-8");
//Ta reda på verb (GET, POST, PUT, DELETE)
$method = $_SERVER['REQUEST_METHOD'];
//Bryt upp URL:en vid varje "/"
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
//Input från request-bodyn
$input = json_decode(file_get_contents('php://input'), true);

//Det måste vara minst 2 parametrar
if (count($request) >= 2) {
    //User
    $user_id = intval($request[1]);
    $note_id = -1;

    //Specifik anteckning
    if (count($request) >= 3) {
        $note_id = intval($request[2]);
    }

    //Hanterar anteckningar
    $notes_manager = new NotesManager(DB_HOST, DB_NAME, DB_USER, DB_PASS);

    if ($method == "GET") {
        //Hämta anteckningar
        $notes = $notes_manager->getNotes($user_id);

        echo json_encode($notes);
        exit();
    }
    else if ($method == "PUT") { 
        //Uppdatera anteckning
        $text = $input['text']; 
        $success = $notes_manager->updateNote($user_id, $note_id, $text);

        echo json_encode($success);
        exit();
    }
    else if ($method == "POST") {
        //Lägg till anteckning
        $new_note_id = $notes_manager->createNote($user_id, "Text text text ..");

        echo json_encode($new_note_id);
        exit();
    }
    else if ($method == "DELETE") {
        //Ta bort anteckning
        $success = $notes_manager->deleteNote($user_id, $note_id);

        echo json_encode($success);
        exit();
    }    
}

//Något gick fel
echo json_encode(false);
?>