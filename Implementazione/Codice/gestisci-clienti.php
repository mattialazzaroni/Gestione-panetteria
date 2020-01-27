<?php
header("Content-Type: text/html; charset=ISO-8859-1");
ob_start();
//Includo il file amministratore-gerente.php.
include('amministratore-gerente.php');
//Metodo che torna a permette di stampare tutto quello che segue.
ob_end_clean();

//Dichiaro le variabili che conterrano i dati che inserisce il gerente.
$checkin = "";
$checkout = "";
?>

<!-- Pagina che viene mostrata quando l'utente si è registrato e deve verificare il suo account -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Gestisci clienti</title>
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
    //Soltanto se ho fatto il login com amministratore-gerente posso accedere a questa pagina.
    if (isset($_SESSION['amministratore_gerente'])) :
        unset($_SESSION['message']);

        //Se viene cliccato il bottone per modificare una riservazione.
        if (isset($_POST['modifica'])) {
            unset($_SESSION['message']);

            $id_da_controllare;
            //Prendo la posizione della riservazione da modificare.
            $indice_da_modificare = $_POST['modifica'];
            //Query che prende tutte le riservazioni disponibili.
            $riservazione_query = "SELECT * FROM riservazione";
            //Preparo ed eseguo la query.
            $stmt = $db->prepare($riservazione_query);
            $stmt->execute();

            //Se nel db è presente almeno un gerente.
            if ($stmt->rowCount() > 0) {

                for ($i = 1; $i < $stmt->rowCount() + 1; $i++) {
                    if ($i == $indice_da_modificare) {
                        //Imposto l'id da controllare.
                        $id_da_controllare = $i;
                    }
                }

                //Query che prende le info della riservazione in base alla riga specificata da rownumber.
                $email_query = "SELECT * FROM
                (SELECT row_number() OVER (ORDER BY id ASC) AS rownumber,
                data_checkin,
                data_checkout,
                id FROM riservazione)
                as get_id
                where rownumber = " . $id_da_controllare . "";

                //Preparo la query.
                $stmt = $db->prepare($email_query);

                //Eseguo la query.
                if ($stmt->execute()) {
                    $row = $stmt->fetch(PDO::FETCH_NUM);
                    //Salvo i valori restituiti dalla query.
                    $checkin = $_SESSION['checkin_iniziale'] = $row[1];
                    $checkout = $_SESSION['checkout_iniziale'] = $row[2];
                    $_SESSION['id_da_modificare'] = $row[3];
                }
            }
        }

        //Se viene cliccato il bottone per salvare le modifiche di una riservazione.
        if (isset($_POST['salva_modifiche'])) {
            //Ricevo tutti gli input dal form.
            $checkin = $_POST['checkin'];
            $checkout = $_POST['checkout'];

            //La data di oggi.
            $today = date("Y-m-d");

            //Eseguo tutti i controlli sugli input.
            if (empty($checkin)) {
                array_push($errors, "La data del check-in &egrave; richiesta");
            } else {
                if ($today > $checkin) {
                    array_push($errors, "Non puoi riservare nel passato");
                }
            }

            if (empty($checkout)) {
                array_push($errors, "La data del check-out &egrave; richiesta");
            } else {
                if (!empty($checkin)) {
                    if ($checkout < $checkin) {
                        array_push($errors, "La data del check-in deve essere prima del check-out");
                    }
                }
            }

            //Se non ci sono errori.
            if (count($errors) == 0) {

                //Query per controllare la disponibilità della riservazione da modificare,
                $disponibilita_query = "SELECT i.startDate, i.endDate
                from (select '" . $checkin . "' startDate, '" . $checkout . "' endDate) i
                where not exists (
                    select 1 
                    from riservazione r 
                    where r.data_checkin < i.endDate and r.data_checkout > i.startDate)";

                //Preparo ed eseguo la query.
                $stmt = $db->prepare($disponibilita_query);
                $stmt->execute();

                //Se la query ritorna almeno 1 risultato.
                if ($stmt->rowCount() > 0) {
                    if($_SESSION['checkin_iniziale'] == $checkin && $_SESSION['checkout_iniziale'] == $checkout){
                        unset($_SESSION['message']);
                        array_push($errors, "Non puoi rimettere le stesse date.");
                    }
                    else{
                        //Query per modificare la riservazione.
                        $modifica_query = "UPDATE riservazione 
                        SET data_checkin = '" . $checkin . "',
                        data_checkout = '" . $checkout . "'
                        WHERE id = ".$_SESSION['id_da_modificare']."";
                        //Preparo ed eseguo la query.
                        $stmt = $db->prepare($modifica_query);
                        $stmt->execute();
                    }
                    
                    //Se non ci sono errori.
                    if (count($errors) == 0) {
                        $_SESSION['message'] = "<div class='text-success text-center'>
                        <p style='color:green'>La riservazione con ID '" . $_SESSION['id_da_modificare'] . "' &egrave; stata modificata con successo.</p>
                        </div>";
                    }
                //Se non viene ritornata nessuna riga.
                } else if ($stmt->rowCount() == 0) {
                    unset($_SESSION['message']);
                    array_push($errors, "Le date inserite non sono disponibili. Riprova.");
                }
            }
        }

        //Se viene cliccato il bottone per eliminare le modifiche di un utente.
        if (isset($_POST['elimina'])) {
            unset($_SESSION['message']);

            $id_da_controllare;
            //Prendo la posizione della riservazione da eliminare.
            $indice_da_eliminare = $_POST['elimina'];
            //Query che prende tutte le riservazioni.
            $riservazione_query = "SELECT * FROM riservazione";
            //Preparo ed eseguo la query.
            $stmt = $db->prepare($riservazione_query);
            $stmt->execute();

            //Se nel db è presente almeno una riservazione.
            if ($stmt->rowCount() > 0) {

                for ($i = 1; $i < $stmt->rowCount() + 1; $i++) {
                    if ($i == $indice_da_eliminare) {
                        //Imposto l'id da controllare.
                        $id_da_controllare = $i;
                    }
                }

                //Query che prende le info della riservazione in base alla riga specificata da rownumber.
                $email_query = "SELECT * FROM
                (SELECT row_number() OVER (ORDER BY id ASC) AS rownumber, id
                FROM riservazione)
                as get_id 
                where rownumber = " . $id_da_controllare . "";

                //Preparo la query.
                $stmt = $db->prepare($email_query);

                //Eseguo la query.
                if ($stmt->execute()) {

                    $row = $stmt->fetch(PDO::FETCH_NUM);
                    $riservazione_da_eliminare = $row[1];

                    //Query che disattiva i controlli.
                    $disable_checks = "SET FOREIGN_KEY_CHECKS=0";
                    //Query che elimina la riservazione.
                    $delete_query = "DELETE FROM riservazione
                    WHERE id = '" . $riservazione_da_eliminare . "'";
                    //Query che riattiva i controlli.
                    $enable_checks = "SET FOREIGN_KEY_CHECKS=1";

                    //Preparo le query.
                    $stmt = $db->prepare($disable_checks);
                    $stmt1 = $db->prepare($delete_query);
                    $stmt2 = $db->prepare($enable_checks);
                    //Eseguo le query.
                    $stmt->execute();
                    $stmt1->execute();
                    $stmt2->execute();

                    //Notifico che la riservazione è stata eliminata.
                    $_SESSION['message'] = "<div class='text-success text-center'>
                <p style='color:green'>La riservazione con ID '" . $riservazione_da_eliminare . "' &egrave; stata eliminata con successo.</p>
                </div>";

                    echo "<meta http-equiv='refresh' content='0'>";
                } else {
                    array_push($errors, "Ops! Qualcosa è andato storto. Riprova.");
                }
            } else {
                array_push($errors, "No data.");
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
        <form id="formClienti" method="post" action="gestisci-clienti.php">
            <h4 class="card-title mt-3 text-center">Gestisci i clienti</h4><br>
            <?php if (isset($_POST['modifica']) || isset($_POST['salva_modifiche'])) : ?>
                <p class="grey-text text-center">Modifica la data di check-in e/o la data di check-out</p>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-calendar-alt"></i> </span>
                    </div>
                    <input name="checkin" class="form-control" type="date" max="9999-12-31" min="<?php echo date('Y-m-d'); ?>" title="Data di check-in" value="<?php echo $checkin; ?>">
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-calendar-alt"></i> </span>
                    </div>
                    <input name="checkout" class="form-control" type="date" max="9999-12-31" min="<?php echo date('Y-m-d'); ?>" title="Data di check-out" value="<?php echo $checkout; ?>">
                </div>
                <button id="modifica" type="submit" name="salva_modifiche" class="btn btn-primary btn-block">Salva modifiche</button>
            <?php endif ?>
        </form>
        </article>

        <?php
            if (count($errors) > 0) :
                //Se ci sono degli errori li stampo.
                unset($_SESSION['message']);
                ?>
            <br>
            <div class="error text-center">
                <?php foreach ($errors as $error) :
                            ?>
                    <p style="color:red"><?php echo $error ?></p>
                <?php endforeach
                        ?>
            </div>
        <?php endif ?>

        <?php
            //Se c'è un messaggio lo stampo.
            if (isset($_SESSION['message'])) {
                echo "<br>";
                echo $_SESSION['message'];
            }

            //Query che prende una riservazione più molte altre informazioni legate a questa riservazione.
            $clienti_query = "SELECT utente.email,
            utente.nome,
            utente.cognome,
            utente.password_utente,
            utente.n_telefono,
            riservazione.id,
            riservazione.data_checkin,
            riservazione.data_checkout,
            camera.numero_bambini,
            camera.numero_adulti
            FROM amministratore_gerente
            JOIN alloggio ON amministratore_gerente.email = alloggio.email_gerente
            JOIN camera ON alloggio.id = camera.id_alloggio
            JOIN riservazione ON camera.id = riservazione.id_camera
            JOIN utente ON riservazione.email_utente = utente.email
            WHERE amministratore_gerente.nome = '" . $_SESSION["nome_gerente"] . "'";

            //Preparo la query.
            $stmt = $db->prepare($clienti_query);

            //Eseguo la query.
            $stmt->execute();

            //Se la query fornisce almeno un risultato.
            if ($stmt->rowCount() > 0) {
                //Stampo una tabella.
                echo "<br><form id='formTabella' action='gestisci-clienti.php' method='post'><div class='table-responsive'>
                <table class='text-center table table-striped'>
            <tr style='background-color:#D3D3D3;'>
                <th>ID Riservazione</th>
                <th>Data check-in</th>
                <th>Data check-out</th>
                <th>Numero bambini</th>
                <th>Numero adulti</th>
                <th>Email</th> 
                <th>Nome</th>
                <th>Cognome</th>
                <th>Numero di telefono</th>
                <th>Modifica</th>
                <th>Elimina</th>
            </tr>
            <tr>";
                //Per ogni riga ritornata dalla query, aggiungo una riga alla tabella. 
                for ($i = 1; $i < $stmt->rowCount() + 1; $i++) {
                    $row = $stmt->fetch(PDO::FETCH_NUM);
                    echo "<tr>
                    <td><input type='hidden' name='id' value='" . $i . "'>"
                        . $row[5] .
                        "</td>
                    <td><input type='hidden' name='checkin' value='" . $i . "'>"
                        . date("d-m-Y", strtotime($row[6])) .
                        "</td>
                    <td><input type='hidden' name='checkout' value='" . $i . "'>"
                        . date("d-m-Y", strtotime($row[7])) .
                        "</td>
                    <td><input type='hidden' name='bambini' value='" . $i . "'>"
                        . $row[8] .
                        "</td>
                    <td><input type='hidden' name='adulti' value='" . $i . "'>"
                        . $row[9] .
                        "</td>
                    <td><input type='hidden' name='email' value='" . $i . "'>"
                        . $row[0] .
                        "</td>
                    <td><input type='hidden' name='nome' value='" . $i . "'>"
                        . $row[1] .
                        "</td>
                    <td><input type='hidden' name='cognome' value='" . $i . "'>"
                        . $row[2] .
                        "</td><input type='hidden' name='password' value='" . $row[3] . "'>
                    <td><input type='hidden' name='telefono' value='" . $i . "'>"
                        . $row[4] .
                        "</td>
                <td>
                    <button id='modifica' value='" . $i . "' type='submit' name='modifica' class='btn btn-info btn-block'>Modifica</button>
                </td>
                <td>
                    <button id='elimina' value='" . $i . "' type='submit' name='elimina' class='btn btn-danger btn-block'>Elimina</button>
                </td>
                </tr>";
                }
                echo "</table></div></form>";
            } else {
                echo "<div class='text-error text-center'>
                <br><p style='color:red'>Nessun utente ha riservato un tuo alloggio</p>
                </div>";
            }

            ?>


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