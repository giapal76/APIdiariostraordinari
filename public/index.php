<?php

//Slim Framework e un framework PHP
//L installazione di slim framework e fortemente consigliata con  Composer.
//Composer e un tool per gestire le dipendenze nei progetti php

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

//Possiamo aggiungere route che gestiscono richieste di tipo Get il tutto avviente con il metodo get che slim ci mette a disposizione.

//Esso accetta due argomenti
//Il pattern della route
//Una funzione di callback, che a sua volta prende 2 argomenti (richiesta, risposta)

// lista utenti
$app->get('/listaUtenti', function (Request $request, Response $response) {
    $db = new UtenteManager();
    //risposta
    $responseData = $db->getUtenti();
    //metto in un array json
    $response->getBody()->write(json_encode(array("utenti" => $responseData)));
    //Piccola modifica per definire il Content-type come json, i dati sono strutturati precisamente adesso (cambia poco all'atto pratico)
    $newResponse = $response->withHeader('Content-type', 'application/json');
    return $newResponse;
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

    $result = $db->insert($idattore, $tipo, $nome, $cognome, $password);

    if ($result == 1) {
        $responseData['error'] = false;
        $responseData['message'] = 'Utente aggiunto con successo ';
    } elseif ($result == 0) {
        $responseData['error'] = true;
        $responseData['message'] = "Utente gia' esistente";
    }

    return $response->withJson($responseData);
});


//delete da controllare sta messo post.. metti delete salva
$app->delete('/delete/{id}', function (Request $request, Response $response) {
    $db = new UtenteManager();

    $idattore = $request->getAttribute('id');

    if ($idattore) {
        $attore = $db->get_utente($idattore);

        if ($attore) {
            $result = $db->delete($idattore);

            if ($result) {
                $responseData['error'] = false;
                $responseData['message'] = 'Utente cancellato con successo ';
            } else {
                $responseData['error'] = true;
                $responseData['message'] = 'Cancellazione non effettuata';
            }
        } else {
            $responseData['error'] = false;
            $responseData['message'] = 'Utente non presente';
        }

    } else {
        $responseData['error'] = false;
        $responseData['message'] = 'Parametri mancanti';
    }

    return $response->withJson($responseData);
});


$app->run();

