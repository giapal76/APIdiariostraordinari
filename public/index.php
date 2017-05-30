<?php


use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

require_once '../vendor/autoload.php';
require '../includes/UtenteManager.php';
require '../includes/ConnessioneDB.php';
$app = new App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

//lista utenti
$app->get('/listaUtenti', function (Request $request, Response $response) {
    $db = new UtenteManager();
    $responseData = $db->getUtenti();  //risposta
    $response->getBody()->write(json_encode(array("utenti" => $responseData))); //metto in un array json l'array
});


//accesso

$app->post('/accesso', function (Request $request, Response $response) {
    $db = new UtenteManager();

    $responseData = $request->getParsedBody();
    $idattore = $responseData['idattore'];
    $password = $responseData['password'];

    $responseData = array();

    if ($db->accesso($idattore, $password)) {
        $responseData['error'] = false;
        $responseData['message'] = 'Login ok';
    } else {
        $responseData['error'] = true;
        $responseData['message'] = 'Login non ok';
    }
    return $response->withJson($responseData);
  //  $response->getBody()->write(json_encode($responseData));
});

$app->run();
