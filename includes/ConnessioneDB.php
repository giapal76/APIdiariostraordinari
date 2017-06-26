<?php

class ConnessioneDB
{
    private $con;
    //funzione per la connessione a MySql
    function connection(){
        $this->con = new mysqli("localhost", "root", "", "diariostraordinari");
        return $this->con;
    }

}