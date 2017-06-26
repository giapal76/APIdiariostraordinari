<?php
//File gestionale per utenti

class UtenteManager
{
    private $con;


    public function __construct()
    {
        $db = new ConnessioneDB();
        $this->con = $db->connection();
    }


    //Funzione lista utenti
    public function getUtenti()
    {
        $stmt = $this->con->prepare("SELECT idattore, tipo, nome, cognome FROM attori");
        //Esegue la query
        $stmt->execute();
        //Salvo il risultato della query nelle variabili
        $stmt->bind_result($idattore, $tipo, $nome, $cognome);

        $utenti = array();


        while ($stmt->fetch()) {
            $temp = array();
            $temp['idattore'] = $idattore;
            $temp['tipo'] = $tipo;
            $temp['nome'] = $nome;
            $temp['cognome'] = $cognome;
            array_push($utenti, $temp);
        }
        return $utenti;
    }


    //Funzione di accesso
    public function accesso($idattore, $password)
    {

        $stmt = $this->con->prepare("SELECT idattore, tipo, nome, cognome FROM attori WHERE idattore = ? AND password = ?");
        $stmt->bind_param("ss", $idattore, $password); //ss se sono 2 stringhe, ssi 2 string e un int
        $stmt->execute();
        $stmt->store_result();
        //Controllo se ha trovato matching tra dati inseriti e capi del db
        return $stmt->num_rows > 0;
    }

    //Funzione che mi prende il tipo attore in base al suo id (mi serve per lo switch degli attori)
    public function getTypeByIdattore($idattore)
    {
        $stmt = $this->con->prepare("SELECT idattore, tipo, nome, cognome FROM attori WHERE idattore=?");
        $stmt->bind_param("s", $idattore);
        $stmt->execute();
        $stmt->bind_result($idattore, $tipo, $nome, $cognome);
        $stmt->fetch();

        //PS posso passarmi anche tutti i restanti dati dell'attore utilizzando un array
        $utente = $tipo;
        //$utente['idattore'] = $idattore;
        $utente['tipo'] = $tipo;
        //$utente['nome'] = $nome;
        //$utente['cognome'] = $cognome;
        return $utente;
    }


    //Funzione inserimento attore
    public function insert($idattore, $tipo, $nome, $cognome, $password)
    {
        $stmt = $this->con->prepare("INSERT INTO attori (idattore, tipo, nome, cognome, password) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $idattore, $tipo, $nome, $cognome, $password);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }


    //Funzione delete (seleziona idattore da url)
    public function delete($idattore)
    {
        $stmt = $this->con->prepare("DELETE FROM attori WHERE idattore= ?");
        $stmt->bind_param("s", $idattore);
        $result = $stmt->execute();
        $stmt->store_result();

        return $result;
    }

    public function get_utente($id){

        $stmt = $this->con->prepare("SELECT idattore FROM attori WHERE idattore = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows() > 0 ){
            $stmt->bind_result($id);

            $attore = array();

            while($stmt->fetch()){
                $temp = array();
                $temp['id'] = $id;
                array_push($attore, $temp);
            }

            return $attore;
        }else{
            return null;
        }
    }
}