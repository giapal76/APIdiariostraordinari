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
        $responseData['message'] = 'Accesso effettuato con successo';
        $responseData['tipoUtente'] = $db->getTypeByIdattore($idattore);

    } else {
        $responseData['error'] = true;
        $responseData['message'] = 'Credenziali errate';
    }
    return $response->withJson($responseData);
});


//inserimento attore

$app->post('/insert', function (Request $request, Response $response) {
    $db = new UtenteManager();

    $responseData = $request->getParsedBody();
    $idattore = $responseData['idattore'];
    $tipo = $responseData['tipo'];
    $nome = $responseData['nome'];
    $cognome = $responseData['cognome'];
    $password = $responseData['password'];

    $responseData = array();

    $result = $db->insert($idattore,$tipo,$nome,$cognome,$password);

    if ($result == 1) {
        $responseData['error'] = false;
        $responseData['message'] = 'Attore aggiunto con successo ';
    } elseif ($result == 0) {
        $responseData['error'] = true;
        $responseData['message'] = 'Inserimento non effettuato';
    }

    return $response->withJson($responseData);
});


//delete
$app->post('/delete', function (Request $request, Response $response){
    $db = new UtenteManager();

    $responseData = $request->getParsedBody();
    $idattore = $responseData['idattore'];

    $result = $db->delete($idattore);

    if ($result == 2) {
        $responseData['error'] = false;
        $responseData['message'] = 'Attore cancellato con successo ';
    } elseif ($result == 3) {
        $responseData['error'] = true;
        $responseData['message'] = 'Cancellazione non effettuata';
    }
});


$app->run();

