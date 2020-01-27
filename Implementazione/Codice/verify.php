<?php
//Includo il file che esegue la connessione al database.
include('server.php');
?>

<!-- Pagina che viene mostrata all'utente quando ha cliccato il link ricevuto nelle email -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Verifica</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="css/style.min.css" rel="stylesheet">
</head>

<body class="container-fluid">

    <?php

    //Controllo di ricevere un email e una hash tramite url.
    if (isset($_GET['email']) && !empty($_GET['email']) and isset($_GET['hash']) && !empty($_GET['hash'])) {
        $email = $_GET['email']; // Imposto l'email
        $hash = $_GET['hash']; // Imposto l'hash
        //Preparo la query
        $search = $db->prepare("SELECT email, hash, is_active FROM utente WHERE email=:email AND hash=:hash AND is_active='0'");
        $search->bindParam(":email", $email, PDO::PARAM_STR);
        $search->bindParam(":hash", $hash, PDO::PARAM_STR);
        $search->execute();
        $match = $search->rowCount();

        //Nel caso che la query produce un risultato, modifico l'active dell'utente nel db e stampo un messaggio di riuscita all'utente.
        if ($match > 0) {
            $update = $db->prepare("UPDATE utente SET is_active='1' WHERE email='" . $email . "' AND hash='" . $hash . "' AND is_active='0'");
            $update->execute();
            echo '<div class="form-group text-center card-body mx-auto" style="max-width:450px;">
            <br><div style="font-size:20px;"><b>Congratulazioni!</b><br> Il tuo account è stato attivato, ora puoi fare il login.</div><br>
            <a href="login.php" class="btn btn-primary btn-block"> Vai al login </a>
            </div>';
            //Nel caso che la query non produca nessun risultato, stampo un messaggio di errore all'utente.
        } else {
            echo '<div class="form-group text-center card-body mx-auto" style="max-width:450px;">
            <br><div>L&apos;URL non è valido o hai già attivato il tuo account.</div><br>
            <a href="index.php" class="btn btn-primary btn-block"> Torna alla home </a>
            </div>';
        }
        //Se non ricevo email o hash, stampo un messaggio di errore.
    } else {
        // Invalid approach
        echo '<br><div class="text-center">Approccio non valido, si prega di utilizzare il link che è stato inviato alla tua email.</div>';
    }

    ?>

</body>

</html>