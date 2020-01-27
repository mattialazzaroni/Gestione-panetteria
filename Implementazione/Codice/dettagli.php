<?php
//Metodo che non permette di stampare il contenuto visivo della pagina index.php
ob_start();
//Includo il file che contiene le informazioni dell'alloggio cliccato.
include('index.php');
//Includo il file che esegue il login.
include('login.php');
//Metodo che torna a permette di stampare tutto quello che segue.
ob_end_clean();

// mporto le classi PHPMailer nel namespace globale.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Includo il file autoload di composer per PHPMailer e mPDF.
require './phpmailer/vendor/autoload.php';
require './mpdf/vendor/autoload.php';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?php echo $_SESSION['nomeDettagli']; ?></title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="css/style.min.css" rel="stylesheet">
</head>

<body>


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
                            <a class="nav-link waves-effect" href="#">Dettagli
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                    </ul>

                </div>

            </div>
        </nav>
        <!-- Navbar -->

    </header>


    <?php

    //Dichiaro le variabili in modo da poterle stampare da subito nel form senza aspettare che venga premuto il bottone.
    $checkin = "";
    $checkout = "";
    $adulti = "";
    $bambini = "";
    $stampa_camera = "";
    $stampa_camere = "";


    //Se viene premuto il bottone per cercare la disponibilità.
    if (isset($_POST['cercaDisponibilita'])) {
        unset($_SESSION['message']);
        //Ricevo tutti gli input dal form.
        $today = date("d.m.Y");
        $adulti = $_POST['adulti'];
        $bambini = $_POST['bambini'];
        $checkin = $_POST['checkin'];
        $checkout = $_POST['checkout'];

        //Eseguo tutti i controlli sugli input.
        if (empty($checkin)) {
            array_push($errors, "La data del check-in &egrave; richiesta");
        } else {
            if ($today > $checkin) {
                array_push($errors, "Non puoi riservare nel passato");
            } else {
                $_SESSION['checkin'] = $checkin;
            }
        }

        if (empty($checkout)) {
            array_push($errors, "La data del check-out &egrave; richiesta");
        } else {
            if (!empty($checkin)) {
                if (strtotime($checkout) < strtotime($checkin)) {
                    array_push($errors, "La data del check-in deve essere prima del check-out");
                } else {
                    $_SESSION['checkout'] = $checkout;
                }
            }
        }

        if (empty($adulti) && strlen($adulti) == 0) {
            array_push($errors, 'Il numero di adulti &egrave; richiesto (in caso di assenza, inserire "0")');
        } else {
            $_SESSION['adulti'] = $_POST['adulti'];
        }

        if (empty($bambini) && strlen($bambini) == 0) {
            array_push($errors, 'Il numero di bambini &egrave; richiesto (in caso di assenza, inserire "0")');
        } else {
            $_SESSION['bambini'] = $_POST['bambini'];
        }
    }

    //Se la pagina tramite invio di un form o se viene inviato un form della seguente pagina.
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //Stampo l'alloggio cliccato prendendo tutte le informazioni della pagina "index.php".
        echo '<div class="row <!--wow fadeIn--> text-center" style="margin-top:12.5vh;">
        
        <div class="col-lg-12 col-xl-12 ml-xl-4 mb-4">
        <h3 class="mb-3 font-weight-bold dark-grey-text">
            <strong>' . $_SESSION['nomeDettagli'] . '</strong>
        </h3>
        <p>
            <strong>' . $_SESSION['indirizzoDettagli'] . '</strong>
        </p>
            <p class="grey-text">' . $_SESSION['cittaDettagli'] . ', ' . $_SESSION['regioneDettagli'] . '</p>
        </div>

        <div class="col-lg-6 col-xl-6 mb-4">
            <div class="view overlay rounded z-depth-1">
                <img src="' . $_SESSION['linkImmagineDettagli'] . '" class="img-fluid">
            </div>
        </div>
        <div class="col-lg-6 col-xl-6 mb-4">
            <iframe src="' . $_SESSION['linkMappaDettagli'] . '" width="100%" height="300" frameborder="0" style="border:0;" allowfullscreen=""></iframe>

            <!-- Contenuto visivo della pagina con form di riservazione -->
            <article class="card-body">
                <form method="post" action="dettagli.php" id="formRiservazione">
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-calendar-alt"></i> </span>
                        </div>
                        <input name="checkin" class="form-control" placeholder="Check-in" type="date" value="' . $checkin . '" min="' . date('Y-m-d') . '" max="9999-12-31" title="Data del Check-in">
                        <input name="checkout" class="form-control" placeholder="Check-out" type="date" value="' . $checkout . '" min="' . date('Y-m-d') . '" max="9999-12-31" title="Data del Check-out">
                    </div> <!-- form-group// -->
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-child"></i> </span>
                        </div>
                        <input name="adulti" class="form-control" placeholder="Inserisci il numero di adulti" type="number" min="0" value="' . $adulti . '" title="Numero di adulti">
                    </div> <!-- form-group// -->
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-baby"></i> </span>
                        </div>
                        <input name="bambini" class="form-control" placeholder="Inserisci il numero di bambini" type="number" min="0" value="' . $bambini . '" title="Numbero di bambini">
                    </div> <!-- form-group// -->
                    <button type="submit" name="cercaDisponibilita" class="btn btn-primary btn-block"> Cerca disponibilit&agrave; </button>';
    }

    //Se l'utente schiaccia il bottone per cercare la disponibilità di una camera.
    if (isset($_POST['cercaDisponibilita'])) {
        //Se non è presente nessun errore negli input dell'utente.
        if (count($errors) == 0) {
            ob_start();
            //Includo il file che esegue la connessione al database.
            include('server.php');
            ob_end_clean();

            //La query che prende l'id delle camere possibili senza però controllare ancora le date.
            $reservation_query = "SELECT riservazione.id_camera
            from riservazione
            join camera on camera.id = riservazione.id_camera
            join alloggio on alloggio.email_gerente = camera.email_gerente
            where camera.id_alloggio = " . $_SESSION['id'] . "
            and camera.numero_adulti = " . $_SESSION['adulti'] . "
            and camera.numero_bambini = " . $_SESSION['bambini'] . "
            group by (riservazione.id_camera)";

            //Preparo la query da eseguire.
            $stmt = $db->prepare($reservation_query);
            //Eseguo la query.
            $stmt->execute();
            //Il numero di radio button che dovrò stampare.
            $_SESSION['radioNumber'] = $stmt->rowCount();
            //Se la query ritorna almeno un risultato.
            if ($stmt->rowCount() > 0) {
            //Inizio a stampare un form.
            $stampa_camere = "<form action='dettagli.php' method='post'>";
            $stampa_camera = "";
            //Per ogni camera possibile.
            for ($i = 1; $i < $stmt->rowCount() + 1; $i++) {
                //Salvo delle variabili utili.
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $r_id = $_SESSION['camera_scelta'] = $row[0];
                ${"id" . $i}  = $row[0];
                //La query che controlla le date delle riservazioni delle camere.
                $date_query = "SELECT i.startDate, i.endDate, i.bambini, i.adulti
                    FROM (SELECT '" . $_SESSION['checkin'] . "' startDate, '" . $_SESSION['checkout'] . "' endDate, '" . $_SESSION['bambini'] . "' bambini, '" . $_SESSION['adulti'] . "' adulti) i
                    where not exists (
                select 1 
                from (select riservazione.id, riservazione.data_checkin, riservazione.data_checkout, riservazione.id_camera
                        from riservazione
                        join camera on camera.id = " . ${"id" . $i} . "
                        join alloggio on alloggio.email_gerente = camera.email_gerente
                        where camera.id_alloggio = " . $_SESSION['id'] . "
                        and camera.numero_adulti = " . $_SESSION['adulti'] . "
                        and camera.numero_bambini = " . $_SESSION['bambini'] . "
                        group by (riservazione.id_camera)) r 
                where r.data_checkin < i.endDate and r.data_checkout > i.startDate)";

                //Preparo la query da eseguire.
                $stmt1 = $db->prepare($date_query);
                //Eseguo la query.
                $stmt1->execute();
                $row1 = $stmt1->fetch(PDO::FETCH_NUM);
                //Preparo gli elementi della tabella se le camere trovate dovessero essere più di una.
                $stampa_camere .= "<tr>
                        <td>"
                    . ${"id" . $i} .
                    "</td>
                        <td>"
                    . $row1[3] .
                    "</td>
                        <td>"
                    . $row1[2] .
                    "</td>
                        <td>"
                    . date("d-m-Y", strtotime($row1[0])) .
                    "</td>
                        <td>"
                    . date("d-m-Y", strtotime($row1[1])) .
                    "</td>
                        <td>
                            <input type='radio' name='camera" . $i . "' value='camera" . $i . "'>
                        </td>
                    </tr>";

                    //Preparo del testo normale se la camera è solo una.
                    $stampa_camera .= "Per quella data &egrave; stata trovata la camera " . $r_id . ", per " . $row1[2] . " bambini/o, " . $row1[3] . " adulti/o, dal " . date("d-m-Y", strtotime($row1[0])) . " al " . date("d-m-Y", strtotime($row1[1])) . ".";
                    $stampa_camera .= "<form action='dettagli.php' method='post'><button style='margin-top:20px;' id='riserva' type='submit' name='riserva' class='btn btn-primary btn-block'>Riserva la camera</button></form><br>";
                }

                //Preparo un bottone solo se prima è stata cercata la disponibilità e se non ci sono errori.
                if (isset($_POST['cercaDisponibilita']) && count($errors) == 0) {
                    $button = "<button id='riserva' type='submit' name='riserva' class='btn btn-primary btn-block'>Riserva la camera</button><br>";
                }
                $stampa_camere .= "</form>";
                //Se il risultato è uno solo
                if ($stmt->rowCount() == 1) {
                    $row = $stmt->fetch(PDO::FETCH_NUM);
                    //Stampo il messaggio che indica che è stata trovata una sola camera.
                    echo "<br>" . $stampa_camera;
                    unset($_SESSION['camera_multipla']);
                    $_SESSION['camera_singola'] = true;
                } else if ($stmt->rowCount() > 1) {
                    //Preparo i vari <th> della tabella.
                    echo "<br>Per quella data sono disponibili le seguenti camere:<br><br>
                <table class='table text-center table-striped'>
                    <tr style='background-color:#D3D3D3;'>
                        <th>Numero</th> 
                        <th>Adulti</th>
                        <th>Bambini</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Riserva</th>
                    </tr>";

                    //Stampo  il messaggio che indica che sono state trovate più camere.
                    echo $stampa_camere;
                    echo "</table>";

                    //Se è stata settata la variabile contenente il bottone lo stampo.
                    if (isset($button)) {
                        echo $button;
                    }

                    unset($_SESSION['camera_singola']);
                    $_SESSION['camera_multipla'] = true;
                    //Stampo un messaggio se non viene trovata nessuna camera.
                } else if ($stmt->rowCount() == 0) {
                    array_push($errors, "Nessuna camera trovata!");
                }
            } else {
                //echo "Nessuna camera disponibile in questa data. Prova con un'altra data! <br><br>";
                array_push($errors, "Nessuna camera disponibile in questa data. Prova con un'altra data!");
            }
        }
    }

    //Se l'utente clicca per riservare.
    if (isset($_POST['riserva'])) {
        $isRadioClicked = false;
        //Controllo che sia un utente loggato.
        if (isset($_SESSION["utente"])) {

            //Prendo l'ultimo id disponibile e ci faccio +1 così da avere il nuovo id.
            $ultimo_id_query = "SELECT max(id) FROM riservazione";
            $stmt = $db->prepare($ultimo_id_query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_NUM);
            $nuovo_id = $row[0] + 1;


            //Istanzio un nuovo oggetto PHPMailer.
            $mail = new PHPMailer(true);

            //Server settings                    // Enable verbose debug output
            $mail->isSMTP();
            $mail->SMTPDebug = 2;                                          // Send using SMTP
            $mail->Host       = 'smtp.live.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'gestionealloggi@hotmail.com';                     // SMTP username
            $mail->Password   = 'Password&3';                               //  SMTP password
            $mail->SMTPSecure = 'tsl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('gestionealloggi@hotmail.com', 'Gestione alloggi');
            $mail->addAddress($_SESSION['email'], $_SESSION['name']);              // Name is optional
            $mail->addReplyTo('gestionealloggi@hotmail.com', 'Gestione alloggi');

            // Content
            $mail->CharSet = "UTF-8";
            $mail->Subject = 'Stampa riservazione';

            //Se sono state trovare più camere.
            if (isset($_SESSION['camera_multipla'])) {
                for ($i = 1; $i < $_SESSION['radioNumber'] + 1; $i++) {
                    if (isset($_POST['camera' . $i]) && !empty($_POST['camera' . $i])) {
                        //Salvo delle variabili utili a dipendenza della camera scelta.
                        $id_camera = $i;
                        $isRadioClicked = true;
                    }
                }

                //Se è stato cliccato almeno un radio button.
                if ($isRadioClicked) {

                    //Query per le informazioni della camera scelta.
                    $camera_scelta_query = "SELECT * FROM (SELECT row_number() OVER (ORDER BY riservazione.id ASC) AS rownumber, riservazione.id_camera
                    from riservazione
                    join camera on camera.id = riservazione.id_camera
                    join alloggio on alloggio.email_gerente = camera.email_gerente
                    where camera.id_alloggio = " . $_SESSION['id'] . "
                    and camera.numero_adulti = " . $_SESSION['adulti'] . "
                    and camera.numero_bambini = " . $_SESSION['bambini'] . "
                    group by (riservazione.id_camera)) as get_id where rownumber = " . $id_camera . "";

                    //Preparo ed eseguo la query.
                    $stmt = $db->prepare($camera_scelta_query);
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_NUM);
                    //L'id della camera scelta.
                    $camera_scelta = $row[1];

                    //La query che salva la riservazione nel database.
                    $salvataggio_query = "INSERT INTO riservazione values ('" . $nuovo_id . "', '" . $_SESSION['checkin'] . "', '" . $_SESSION['checkout'] . "', '" . $camera_scelta . "', '" . $_SESSION['email'] . "')";
                    //La preparo e la eseguo.
                    $stmt = $db->prepare($salvataggio_query);
                    $stmt->execute();

                    $_SESSION['message'] = "<br><div class='text-success text-center'>
                            <p style='color:green'>La riservazione della camera " . $camera_scelta . ", dal " . date("d-m-Y", strtotime($_SESSION['checkin'])) . " al " . date("d-m-Y", strtotime($_SESSION['checkout'])) . ", per " . $_SESSION['adulti'] . " adulti/o e per " . $_SESSION['bambini'] . " bambini/o &egrave; stata aggiunta con successo. Controlla la tua email se vuoi stampare la riservazione.</p>
                            </div>";

                    //Preparo il body dell'email.
                    unset($body);
                    $body = "Hai riservato la camera " . $camera_scelta . ", dal " . date("d-m-Y", strtotime($_SESSION['checkin'])) . " al " . date("d-m-Y", strtotime($_SESSION['checkout'])) . ", per " . $_SESSION['adulti'] . " adulti/o e per " . $_SESSION['bambini'] . " bambini/o.";
                    $allegato = "<br>In allegato è possibile trovare un pdf da stampare.";
                    $mail->Body = $body . $allegato;

                    //Creo un nuovo pdf che allegherò all'email.
                    $pdf = new \Mpdf\Mpdf();
                    $pdf->WriteHTML($body);
                    $pdf_riservazione = $pdf->Output('riservazione.pdf', 'S');
                } else {
                    array_push($errors, "Non hai selezionato una camera. Riprova");
                }
                //Se è stata trovata una sola camera. 
            } else if (isset($_SESSION['camera_singola'])) {
                $salvataggio_query = "INSERT INTO riservazione values ('" . $nuovo_id . "', '" . $_SESSION['checkin'] . "', '" . $_SESSION['checkout'] . "', '" . $_SESSION['camera_scelta'] . "', '" . $_SESSION['email'] . "')";
                $stmt = $db->prepare($salvataggio_query);
                $stmt->execute();

                $_SESSION['message'] = "<br><div class='text-success text-center'>
                            <p style='color:green'>La riservazione della camera " . date("d-m-Y", strtotime($_SESSION['camera_scelta'])) . ", dal " . date("d-m-Y", strtotime($_SESSION['checkin'])) . " al " . $_SESSION['checkout'] . ", per " . $_SESSION['adulti'] . " adulti/o e per " . $_SESSION['bambini'] . " bambini/o &egrave; stata aggiunta con successo. Controlla la tua email se vuoi stampare la riservazione.</p>
                            </div>";

                //Preparo il body dell'email.
                unset($body);
                $body = "Hai riservato la camera " . $_SESSION['camera_scelta'] . ", dal " . date("d-m-Y", strtotime($_SESSION['checkin'])) . " al " . date("d-m-Y", strtotime($_SESSION['checkout'])) . ", per " . $_SESSION['adulti'] . " adulti/o e per " . $_SESSION['bambini'] . " bambini/o.";
                $allegato = "<br>In allegato è possibile trovare un pdf da stampare.";
                $mail->Body = $body . $allegato;

                //Creo un nuovo pdf che allegherò all'email.
                $pdf = new \Mpdf\Mpdf();
                $pdf->WriteHTML($body);
                $pdf_riservazione = $pdf->Output('riservazione.pdf', 'S');
            }

            //Aggiungo l'allegato all'email.
            $mail->addStringAttachment($pdf_riservazione, 'riservazione.pdf');
            $mail->isHTML(true);

            //Mando l'email.
            if (!$mail->send()) {
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            }

            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
            }
        } else {
            //Stampo dei messaggi di errore se chi prova a riservare non è un utente.
            if (isset($_SESSION['amministratore']) || isset($_SESSION['amministratore_gerente'])) {
                array_push($errors, "Solo gli utenti possono effettuare le riservazioni.");
            } else {
                array_push($errors, "Per effettuare una riservazione devi aver effettuato l'accesso. <a href='login.php'>Vai al login</a>");
            }
        }
    }

    //In caso di errori, vado a stamparli quando il bottone viene premuto.
    if (count($errors) > 0) {
        unset($_SESSION['message']);
        echo '<br><div class="error">';
        foreach ($errors as $error) :
            echo '<p style="color:red">' . $error . '</p>';
        endforeach;
        echo '</div>';
    }
    echo '</form></article></div>';

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