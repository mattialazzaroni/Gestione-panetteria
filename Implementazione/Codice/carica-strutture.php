<?php
header("Content-Type: text/html; charset=ISO-8859-1");
ob_start();
//Includo il file che esegue amministratore.php.
include('amministratore.php');
//Metodo che torna a permette di stampare tutto quello che segue.
ob_end_clean();
?>

<!-- Pagina che viene mostrata quando l'utente si è registrato e deve verificare il suo account -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Carica struttura</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="css/style.min.css" rel="stylesheet">
    <script src="js/jquery-3.4.1.min.js"></script>
</head>

<body class="container-fluid">


    <?php
    //Soltanto se ho fatto il login com amministratore posso accedere a questa pagina.
    if (isset($_SESSION['amministratore'])) :

        //Dichiaro le variabili in modo da poterle stampare da subito nel form senza aspettare che venga premuto il bottone.
        $nome = "";
        $indirizzo = "";
        $link_immagine = "";
        $link_mappa = "";
        $regione = "";
        $citta = "";
        $email = "";
        $tipologia = "";


        //Se viene cliccato il bottone.
        if (isset($_POST['aggiungi'])) {

            //Salvo nelle variabili gli input dell'amministratore.
            $nome = $_POST['nome'];
            $indirizzo = $_POST['indirizzo'];
            $link_immagine = $_POST['link_immagine'];
            $link_mappa = $_POST['link_mappa'];
            $regione = $_POST['regione'];
            $citta = $_POST['citta'];
            $email = $_POST['email_gerente'];
            if (isset($_POST['tipologia'])) {
                $tipologia = $_POST['tipologia'];
            }

            //Controllo gli input.
            if (empty($nome)) {
                array_push($errors, "Il nome del tuo alloggio &egrave; richiesto.");
            }

            if (empty($indirizzo)) {
                array_push($errors, "L'indirizzo del tuo alloggio &egrave; richiesto.");
            }

            if (empty($link_immagine)) {
                array_push($errors, "Il link dell'immagine del tuo alloggio &egrave; richiesto.");
            }

            if (empty($link_mappa)) {
                array_push($errors, "Il link della mappa del tuo alloggio &egrave; richiesto.");
            }

            if (empty($regione)) {
                array_push($errors, "La regione del tuo alloggio &egrave; richiesta.");
            }

            if (empty($citta)) {
                array_push($errors, "La citt&agrave; del tuo alloggio &egrave; richiesta.");
            }

            if (empty($email)) {
                array_push($errors, "L'email dell'amministratore gerente del tuo alloggio &egrave; richiesta.");
            } else {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    array_push($errors, "Il formato dell'email non &egrave; valido");
                }
            }

            if (empty($tipologia)) {
                array_push($errors, "La tipologia del tuo alloggio &egrave; richiesta.");
            }
        }
        ?>

        <!--Main Navigation-->
        <header>

            <!-- Navbar -->
            <nav class="navbar fixed-top navbar-expand-lg navbar-light white scrolling-navbar">
                <div class="container">

                    <!-- Brand -->
                    <a class="navbar-brand waves-effect">
                        <strong class="blue-text">Gestione disponibilit&agrave; alloggi</strong>
                    </a>

                    <!-- Collapse -->
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- Links -->
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">

                        <!-- Left -->
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item">
                                <a class="nav-link waves-effect" href="index.php">Tutte le strutture
                                </a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link waves-effect" href="amministratore.php">Amministratore
                                    <span class="sr-only">(current)</span>
                                </a>
                            </li>
                        </ul>

                    </div>

                </div>
            </nav>
            <!-- Navbar -->

        </header>

        <!-- Contenuto visivo della pagina -->
        <article class="card-body mx-auto" style="max-width:450px; margin-top:75px;">
            <form id="formDisponibilita" method="post" action="carica-strutture.php">
                <h4 class="card-title mt-3 text-center">Aggiungi una struttura</h4>
                <div class="form-group input-group">
                    <input name="nome" class="form-control" placeholder="Inserisci il nome dell'alloggio" type="text" value="<?php echo $nome; ?>" title="Nome dell'alloggio">
                </div> <!-- form-group// -->
                <div class="form-group input-group">
                    <input name="indirizzo" class="form-control" placeholder="Inserisci l'indirizzo dell'alloggio" type="text" value="<?php echo $indirizzo; ?>" title="Indirizzo dell'alloggio">
                </div> <!-- form-group// -->
                <div class="form-group input-group">
                    <input name="link_immagine" class="form-control" placeholder="Inserisci il link dell'immagine del tuo alloggio" type="text" value="<?php echo $link_immagine; ?>" title="Link dell'immagine del tuo alloggio">
                </div> <!-- form-group// -->
                <div class="form-group input-group">
                    <input name="link_mappa" class="form-control" placeholder="Inserisci il link della mappa del tuo alloggio" type="text" value="<?php echo $link_mappa; ?>" title="Link della mappa del tuo alloggio">
                </div> <!-- form-group// -->
                <div class="form-group input-group">
                    <input name="regione" class="form-control" placeholder="Inserisci la regione del tuo alloggio" type="text" value="<?php echo $regione; ?>" title="Regione del tuo alloggio">
                </div> <!-- form-group// -->
                <div class="form-group input-group">
                    <input name="citta" class="form-control" placeholder="Inserisci la citt&agrave; del tuo alloggio" type="text" value="<?php echo $citta; ?>" title="Citt&agrave; del tuo alloggio">
                </div> <!-- form-group// -->
                <div class="form-group input-group">
                    <input name="email_gerente" class="form-control" placeholder="Inserisci l'email del gerente del tuo alloggio" type="text" value="<?php echo $email; ?>" title="Email del gerente del tuo alloggio">
                </div> <!-- form-group// -->
                <div class="form-group">
                    <select class="form-control" name="tipologia" id="selectTipologia">
                        <option value="" selected disabled>Tipologia</option>
                        <option value="Albergo" <?php if ((isset($_POST['tipologia']) && $_POST['tipologia'] == "Albergo") || (isset($_SESSION['filtro_tipologia']) && $_SESSION['filtro_tipologia'] == "Albergo")) {
                                                        echo "selected";
                                                    } ?>>Albergo</option>
                        <option value="Bed & Breakfast" <?php if ((isset($_POST['tipologia']) && $_POST['tipologia'] == "Bed & Breakfast") || (isset($_SESSION['filtro_tipologia'])  && $_SESSION['filtro_tipologia'] == "Bed & Breakfast")) {
                                                                echo "selected";
                                                            } ?>>Bed & Breakfast</option>
                        <option value="Camping" <?php if ((isset($_POST['tipologia']) && $_POST['tipologia'] == "Camping") || (isset($_SESSION['filtro_tipologia']) && $_SESSION['filtro_tipologia'] == "Camping")) {
                                                        echo "selected";
                                                    } ?>>Camping</option>
                    </select>
                </div>
                <button id="aggiungi" type="submit" name="aggiungi" class="btn btn-primary btn-block">Aggiungi</button>
                <!-- Stampa degli errori -->
                <?php if (count($errors) > 0) : ?>
                    <br>
                    <div class="error">
                        <?php foreach ($errors as $error) : ?>
                            <p style="color:red"><?php echo $error ?></p>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
            </form>
        </article>

    <?php else : ?>
        <!-- Contenuto visivo della pagina -->
        <article class="card-body mx-auto" style="max-width: 450px;  margin-top: 75px;">
            <h4 class="card-title mt-3 text-center">Errore!</h4>
            <p class="text-center">Non hai il permesso di accedere a questa pagina.</p>
            <form action="index.php">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block"><a href="index.php"></a> Torna alla home </button>
                </div> <!-- form-group// -->
            </form>
        </article>
    <?php endif; ?>


    <?php

    //Se viene cliccato il bottone.
    if (isset($_POST['aggiungi'])) {
        //Se non ci sono errori.
        if (count($errors) == 0) {
            ob_start();
            //Includo il file che esegue la connessione al database.
            include('server.php');
            ob_end_clean();

            //Query che seleziona l'id dell'ultimo alloggio.
            $alloggio_query = "SELECT alloggio.id
            FROM alloggio 
            JOIN amministratore_gerente
            ON amministratore_gerente.email = '" . $email . "'
            ORDER BY id DESC LIMIT 1";

            //Preparo la query da eseguire.
            $stmt = $db->prepare($alloggio_query);
            //Eseguo la query.
            $stmt->execute();
            //Stampo un errore nel caso che non venga trovato nessun amministratore gerente.
            if ($stmt->rowCount() == 0) {
                echo "<br><div class='error text-center'>
                        <p style='color:red'>Non &egrave; stato trovato nessun amministratore gerente con quest'email.</p>
                      </div>";
            } 
            //Se eseguendo la query viene trovata una riga, preparo una nuova query.
            else if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_NUM);
                //Aggiungo 1 all'ultimo id trovato, questo sarà l'id della struttura che l'amministratore aggiunge.
                $nextId = $row[0] + 1;
                $insert_alloggio_query = "INSERT INTO alloggio values (" . $nextId . ", '" . $nome . "', '" . $indirizzo . "', '" . $link_immagine . "', '" . $link_mappa . "', '" . $regione . "', '" . $citta . "', '" . $email . "', '" . $tipologia . "')";
                $stmt = $db->prepare($insert_alloggio_query);
                //Eseguo la query.
                $stmt->execute();
                echo "<br><div class='text-success text-center'>
                        <p style='color:green'>La struttura &egrave; stata aggiunta con successo.</p>
                      </div>";
            }
        }
    }
    ?>

    <!-- SCRIPTS -->
    <!-- JQuery -->
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="js/mdb.min.js"></script>
    <!-- Initializations -->
    <script type="text/javascript">
        // Animations initialization
        new WOW().init();
    </script>

</body>

</html>