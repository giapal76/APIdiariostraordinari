<?php


class UtenteManager
{
    private $con;


    public function __construct($con)
    {
        require_once dirname(__FILE__).'ConnessioneDB.php';
        $db = new ConnessioneDB();
        $this->con = $db->connection();
    }

    //restituisce lista utenti
    public function getUtenti(){
        $stmt = $this->con->prepare("SELECT id, nome, cognome, email FROM utente");
        $stmt->execute();//esegue query appena scritta
        $stmt->bind_result($id, $nome, $cognome, $email); //salvo il risultato della query nelle var

        $utenti = array();

        while($stmt->fetch()){
            $temp = array();
            $temp['id']= $id;
            $temp['nome']= $nome;
            $temp['cognome']= $cognome;
            $temp['email']= $email;
            array_push($utenti, $temp);
        }
        return $utenti;  // creato il metodo che mi restituisce la lista utenti
    }




    //accesso

    public  function accesso($email, $password){

        $stmt = $this->con-> prepare("SELECT * FROM utente WHERE email = ? AND password = ?");
        $stmt-> bind_param("ss",$email,$password); //ss ovvero 2 stringhe ssi 2 string e un int
        $stmt->execute();
        $stmt->store_result();

        return $stmt-> num_rows > 0;

    }
}