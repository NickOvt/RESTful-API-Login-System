<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/User.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate user object
    $user = new User($db);

    $data = json_decode(file_get_contents("php://input"));

    $user->uid = $data->uid;
    $user->email = $data->email;
    $user->pwd = $data->pwd;

    if($user->signUp()) {
        echo json_encode(
            array('message' => 'User Created')
        );
    } else {
        echo json_encode(
                array('message' => 'User Not Created')
            );
    }
 ?>
