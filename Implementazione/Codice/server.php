<?php

session_start();

//Dichiaro le variabili per potermi connettere al database.
$errors = array();

//Controllo se sto lavorando in locale.
if ($_SERVER["SERVER_ADDR"] == '127.0.0.1' || $_SERVER["SERVER_NAME"] == 'localhost'){
    //Credenziali per lavorare in locale.
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "efof_i16lazmat";
}
//Altrimenti sono online.
else{
    //Credenziali per lavorare online.
    $servername = "efof.myd.infomaniak.com";
    $username = "efof_i16lazmat";
    $password = "Alloggi_Admin2019";
    $database = "efof_i16lazmat";
}

//Provo a connettermi al database.
try {
    $db = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
} catch (PDOException $e) {
    //Stampo un messaggio di errore se non riesco a connettermi al database.
    echo "Connessione fallita: " . $e->getMessage();
}

