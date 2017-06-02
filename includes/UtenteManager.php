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

    // prendo il tipo attore in base al suo id (mi serve per lo switch degli attori)
    // ps posso passarmi anche tutti i restanti dati dell'attore
    public function getTypeByIdattore($idattore)
    {
        $stmt = $this->con->prepare("SELECT idattore, tipo, nome, cognome FROM attori WHERE idattore=?");
        $stmt->bind_param("s", $idattore);
        $stmt->execute();
        $stmt->bind_result($idattore, $tipo, $nome, $cognome);
        $stmt->fetch();
        $utente = $tipo;
        //$utente['idattore'] = $idattore;
        $utente['tipo'] = $tipo;
        //$utente['nome'] = $nome;
        //$utente['cognome'] = $cognome;
        return $utente;
    }



    //inserimento attore
    public function insert ($idattore, $tipo, $nome, $cognome, $password){
        $stmt = $this->con-> prepare("INSERT INTO attori (idattore, tipo, nome, cognome, password) VALUES (?,?,?,?,?)");
        $stmt-> bind_param("sssss",$idattore, $tipo, $nome, $cognome, $password);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            return 0;
        } else {
            return 1;
        }
        }

    //controllo se idattore è gia inserito nel db
    private function idattoreEsistente($idattore)
    {
        $stmt = $this->con->prepare("SELECT idattore FROM attori WHERE idattore = ?");
        $stmt->bind_param("s", $idattore);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows();
        $stmt->close();
        return $num_rows > 0;
    }


        //delete attore
    public  function  delete ($idattore){
        $stmt = $this->con-> prepare("DELETE FROM attori WHERE idattore=?");
        $stmt->bind_param("s", $idattore);
        $stmt->execute();

    }
}