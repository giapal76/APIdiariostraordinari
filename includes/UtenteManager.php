<?php


class UtenteManager
{
    private $con;


    public function __construct()
    {
        $db = new ConnessioneDB();
        $this->con = $db->connection();
    }

    //lista utenti
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
        $stmt-> bind_param("ss",$idattore, $password); //ss se sono 2 stringhe, ssi 2 string e un int
        $stmt->execute();
        $stmt->store_result();

        return $stmt-> num_rows > 0;

    }

    //inserimento attore (non funziona, mi da sempre inserimento non riuscito)

    public function insert ($idattore, $tipo, $nome, $cognome, $password){
        $stmt = $this->con-> prepare("INSERT INTO attori (idattore, tipo, nome, cognome, password) VALUES (?,?,?,?,?)");
        $stmt-> bind_param("sssss",$idattore, $tipo, $nome, $cognome, $password);
        $stmt->execute();
        $stmt->store_result();

        if($stmt){
            return 1;
        } else{
            return 0;
        }
        }

}