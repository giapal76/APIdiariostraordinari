<?php


require_once '../vendor/autoload.php';

$app = new \Slim\App{[
        'settings' => [
            'displayErrorDetails' => true
        ]
]};


//lista utenti
$app->get('/listaUtenti', function (Request $request, Response $response){
    $db = new UtenteManager();
    $responseData = $db-> getUtenti();  //risposta
    $response-> getBody()-> write(json_encode(array("utenti" => $responseData))); //metto in un array json l'array
});



//accesso

$app->post('/accesso', function (Request $request, Response $response){
    $db = new UtenteManager();

    $responseData = $request->getParsedBody();
    $email = $responseData['email'];
    $password = $responseData['passsword'];

    $responseData = array();

    if($db->accesso($email, $password)){
        $responseData['error'] = false;
        $responseData['message'] = 'Login ok';
    }else{
        $responseData['error']= true;
        $responseData['message'] = 'Login non ok';
    }

    $response->getBody()-> write(json_encode($responseData));
});

$app->run();
