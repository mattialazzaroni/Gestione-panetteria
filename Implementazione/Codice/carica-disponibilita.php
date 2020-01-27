<?php
header("Content-Type: text/html; charset=ISO-8859-1");
ob_start();
//Includo il file amministratore-gerente.php.
include('amministratore-gerente.php');
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
    <title>Carica disponibilit&agrave;</title>
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
    //Soltanto se ho fatto il login com amministratore gerente posso accedere a questa pagina.
    if (isset($_SESSION['amministratore_gerente'])) :

        //Dichiaro le variabili in modo da poterle stampare da subito nel form senza aspettare che venga premuto il bottone.
        $adulti = "";
        $bambini = "";
        $nome = "";
        $email = $_SESSION["email"];

        //Se viene cliccato il bottone per aggiungere una camera.
        if (isset($_POST['aggiungi'])) {

            //Salvo gli input nelle variabili.
            $adulti = $_POST['adulti'];
            $bambini = $_POST['bambini'];
            $nome = $_POST['nome'];

            //Eseguo i controlli sugli input.
            if (empty($adulti) && strlen($adulti) == 0) {
                array_push($errors, 'Il numero di adulti &egrave; richiesto (in caso di assenza, inserire "0")');
            } 

            if (empty($bambini) && strlen($bambini) == 0) {
                array_push($errors, 'Il numero di bambini &egrave; richiesto (in caso di assenza, inserire "0")');
            }

            if (empty($nome)) {
                array_push($errors, 'Il nome del tuo alloggio &egrave; richiesto.');
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
                                <a class="nav-link waves-effect" href="amministratore-gerente.php">Amministratore gerente
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
            <form id="formDisponibilita" method="post" action="carica-disponibilita.php">
                <h4 class="card-title mt-3 text-center">Aggiungi una camera</h4>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fas fa-child"></i> </span>
                    </div>
                    <input name="adulti" class="form-control" placeholder="Inserisci il numero di adulti" type="number" min="0" value="<?php echo $adulti; ?>" title="Numero di adulti">
                </div> <!-- form-group// -->
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fas fa-baby"></i> </span>
                    </div>
                    <input name="bambini" class="form-control" placeholder="Inserisci il numero di bambini" type="number" min="0" value="<?php echo $bambini; ?>" title="Numbero di bambini">
                </div> <!-- form-group// -->
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fas fa-home"></i> </span>
                    </div>
                    <input name="nome" class="form-control" placeholder="Inserisci il nome del tuo alloggio" type="text" value="<?php echo $nome; ?>" title="Nome dell&apos;alloggio">
                </div> <!-- form-group// -->
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
        <article class="card-body mx-auto" style="max-width: 450px; margin-top: 75px;">
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
    //Se viene premuto aggiungi.
    if (isset($_POST['aggiungi'])) {
        //Se non ci sono errori.
        if (count($errors) == 0) {
            ob_start();
            //Includo il file che esegue la connessione al database.
            include('server.php');
            ob_end_clean();

            //Query che prende l'id più grande della tabella delle camere.
            $room_query = "SELECT camera.id
            FROM camera 
            JOIN alloggio
            ON alloggio.nome = '" . $nome . "'
            WHERE alloggio.email_gerente = '" . $email . "'
            ORDER BY id DESC LIMIT 1";

            //Query che prende l'id dell'alloggio che il nome uguale a quello inserito dal gerente.
            $id_query = "SELECT alloggio.id
            FROM alloggio
            WHERE alloggio.nome = '" . $nome . "'
            AND alloggio.email_gerente = '" . $email . "'
            LIMIT 1";

            $stmt = $db->prepare($room_query);
            $stmt1 = $db->prepare($id_query);
            //Eseguo la query.
            $stmt->execute();
            $stmt1->execute();
            //Se eseguendo la query viene trovata una riga, preparo una nuova query.
            if ($stmt->rowCount() == 0) {
                echo "<br><div class='error text-center'>
                        <p style='color:red'>Non possiedi nessun alloggio con quel nome. Controlla la sintassi.</p>
                      </div>";
            } 
            //Se la query ritorna almeno una riga, posso aggiungere la camera al database.
            else if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $row1 = $stmt1->fetch(PDO::FETCH_NUM);
                //Prendo un id disponibile.
                $nextId = $row[0] + 1;
                $idAlloggio = $row1[0];
                //La query che inserisce la camera nel database.
                $insert_room_query = "INSERT INTO camera values (" . $nextId . ", " . $bambini . ", " . $adulti . ", " . $idAlloggio . ", '" . $email . "')";
                $res_id = "SELECT max(id)+1 FROM riservazione";
                $stmt = $db->prepare($res_id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $insert_reservation_query = "INSERT INTO riservazione values (" . $row[0] . ", '2019-10-05', '2019-10-05', " . $nextId . ", 'lazza.yt@gmail.com')";
                $stmt = $db->prepare($insert_room_query);
                $stmt1 = $db->prepare($insert_reservation_query);
                //Eseguo le query.
                $stmt->execute();
                $stmt1->execute();
                echo "<br><div class='text-success text-center'>
                        <p style='color:green'>La camera &egrave; stata aggiunta con successo.</p>
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