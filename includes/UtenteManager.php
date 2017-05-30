<?php


class UtenteManager
{
    private $con;


    public function __construct()
    {
        $db = new ConnessioneDB();
        $this->con = $db->connection();
    }

    //restituisce lista utenti
    public function getUtenti(){
        $stmt = $this->con->prepare("SELECT idattore, tipo, nome, cognome FROM attori");
        $stmt->execute();//esegue query appena scritta
        $stmt->bind_result($idattore,$tipo, $nome, $cognome); //salvo il risultato della query nelle var

        $utenti = array();

        while($stmt->fetch()){
            $temp = array();
            $temp['idattore']= $idattore;
            $temp['tipo']= $tipo;
            $temp['nome']= $nome;
            $temp['cognome']= $cognome;
            array_push($utenti, $temp);
        }
        return $utenti;  // creato il metodo che mi restituisce la lista utenti
    }




    //accesso

    public  function accesso($idattore, $password){

        $stmt = $this->con-> prepare("SELECT idattore, tipo, nome, cognome FROM attori WHERE idattore = ? AND password = ?");
        $stmt-> bind_param("ss",$idattore,$password); //ss se sono 2 stringhe, ssi 2 string e un int
        $stmt->execute();
        $stmt->store_result();

        return $stmt-> num_rows > 0;

    }
}