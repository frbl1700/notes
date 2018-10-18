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

//if ($request[0] != "cars") { 
//	http_response_code(404);
//	exit();
//}

$notes_manager = new NotesManager(DB_HOST, DB_NAME, DB_USER, DB_PASS);

$note1 = new Note();
$note1->text = "En liten anteckning så att säga";

$note2 = new Note();
$note2->text = "Jobba jobba jobba..";

$result = new ApiResult();
$result->success = true;
$result->list = array($note1, $note2);
$result->id = 0;

switch ($method) {
	case "GET":
		//$sql = "SELECT ID, Car, Model, Year FROM Cars";
        //if(isset($request[1])) $sql = $sql . " WHERE ID = " . $request[1] . ";";
        
		break;
    case "PUT":
    
        //$sql = "UPDATE Cars SET Car = '" . $input['car'] . "', Model = '" . $input['model'] . "', Year = '" . $input['year'] . "' WHERE ID = " . $request[1] . ";";
        
    	break;
	case "POST":
        //$sql = "INSERT INTO Cars (Car, Model, Year) VALUES ('" . $input['car'] . "', '" . $input['model'] . "', " . $input['year'] . ");";
        
		break;
	case "DELETE":
        //$sql = "DELETE FROM Cars WHERE ID = " . $request[1] . ";";
        
        break;
    default:
        break;
}

echo json_encode($result);
?>