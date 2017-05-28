<?php


class ConnessioneDB
{
    private $con;
    function connection(){
        $this->con = new mysqli("localhost", "root", "", "prova");
        return $this->con;
    }

}