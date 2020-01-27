<?php
header("Content-Type: text/html; charset=ISO-8859-1");
ob_start();
//Includo il file amministratore-gerente.php.
include('amministratore-gerente.php');
//Includo il file server.php e login.php.
include('server.php');
include('login.php');

//Metodo che torna a permette di stampare tutto quello che segue.
ob_end_clean();

// Importo le classi PHPMailer nel namespace globale.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Includo il file autoload di composer per PHPMailer e mPDF.
require './phpmailer/vendor/autoload.php';
require './mpdf/vendor/autoload.php';

unset($_SESSION['message']);
//Dichiare le variabili in cui assegnerò gli input dell'utente.
$checkin = "";
$checkout = "";
$email = "";
?>

<!-- Pagina che viene mostrata quando l'utente si è registrato e deve verificare il suo account -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Gestisci gerenti</title>
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
    if (isset($_SESSION['amministratore_gerente'])) :

        //Quando l'utente preme il bottone salvo i valori che ha inserito nella variabili create precedentemente.
        if (isset($_POST['cerca'])) {
            $email = $_POST['email'];
            $checkin = $_POST['checkin'];
            $checkout = $_POST['checkout'];
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
                                <a class="nav-link waves-effect" href="amministratore-gerente.php">Amministratore
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
            <form id="formAmministratori" method="post" action="stampa.php">
                <h4 class="card-title mt-3 text-center">Cerca delle riservazioni da stampare</h4><br>
                <p class="grey-text text-center">Inserisci l'email dell'utente e/o la data di check-in e di check-out </p>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-calendar-alt"></i> </span>
                    </div>
                    <input name="checkin" class="form-control" type="date" placeholder="Data check-in riservazione" value="<?php echo $checkin; ?>" max="9999-12-31" title="Data del Check-in">
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-calendar-alt"></i> </span>
                    </div>
                    <input name="checkout" class="form-control" type="date" placeholder="Data check-out riservazione" value="<?php echo $checkout; ?>" max="9999-12-31" title="Data del Check-out">
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                    </div>
                    <input name="email" class="form-control" placeholder="Inserisci l'email dell'utente" type="text" title="Email dell'utente" value="<?php echo $email; ?>">
                </div>
                <button id="cerca" type="submit" name="cerca" class="btn btn-primary btn-block">Cerca</button><br>
            </form>
        </article>

        <?php

            //Se è presente un messaggio lo stampo.
            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
            }

            //Se l'utente preme il bottone.
            if (isset($_POST['cerca'])) {
                //Cancello gli eventuali messaggi.
                unset($_SESSION['message']);
                
                //Prendo la data di oggi.
                $today = date("Y-m-d");


                //Eseguo tutti i vari controlli sugli input.
                if (empty($email) && empty($checkin) && empty($checkout)) {
                    array_push($errors, "Inserire dei valori da cercare");
                }

                if (empty($checkin) && !empty($checkout)) {
                    array_push($errors, "La data del check-in &egrave; richiesta");
                } else if (!empty($checkin) && empty($checkout)) {
                    array_push($errors, "La data del check-out &egrave; richiesta");
                }

                if (!empty($email)) {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        array_push($errors, "Il formato dell'email non &egrave; valido");
                    }
                }

                if (!empty($checkin) && !empty($checkout)) {
                    if ($checkout < $checkin) {
                        array_push($errors, "La data del check-in deve essere prima del check-out");
                    }
                }

                //Se gli input dell'utente non hanno ritornato nessun errore.
                if (count($errors) == 0) {
                    //Preparo le query a dipendenza di quali campi sono stati riempiti.
                    if (empty($email) && !empty($checkin) && !empty($checkout)) {
                        $stampa_query = "SELECT riservazione.data_checkin, riservazione.data_checkout, riservazione.email_utente, camera.id, camera.numero_bambini, camera.numero_adulti
                        FROM riservazione 
                        JOIN camera
                        ON camera.id = riservazione.id_camera 
                        WHERE riservazione.data_checkin < '" . $checkout . "' AND riservazione.data_checkout > '" . $checkin . "'";
                    } else if (!empty($email) && empty($checkin) && empty($checkout)) {
                        $stampa_query = "SELECT riservazione.data_checkin, riservazione.data_checkout, riservazione.email_utente, camera.id, camera.numero_bambini, camera.numero_adulti
                        FROM riservazione 
                        JOIN camera
                        ON camera.id = riservazione.id_camera 
                        WHERE riservazione.email_utente = '" . $email . "'";
                    } else if (!empty($email) && !empty($checkin) && !empty($checkout)) {
                        $stampa_query = "SELECT riservazione.data_checkin, riservazione.data_checkout, riservazione.email_utente, camera.id, camera.numero_bambini, camera.numero_adulti
                        FROM riservazione 
                        JOIN camera
                        ON camera.id = riservazione.id_camera 
                        WHERE riservazione.email_utente = '" . $email . "' AND riservazione.data_checkin < '" . $checkout . "' AND riservazione.data_checkout > '" . $checkin . "'";
                    }

                    //Prepara la query per l'esecuzione.
                    $stmt = $db->prepare($stampa_query);

                    //Eseguo le query.
                    if ($stmt->execute()) {
                        //Se viene ritornato un risultato vado a stampare una tabella contenente solo quelle riservazione.
                        if ($stmt->rowCount() == 1) {
                            $row = $stmt->fetch(PDO::FETCH_NUM);
                            unset($_SESSION['stampa_multipla']);
                            //Sessione che più avanti mi servirà a capire che la query ha ritornato un solo risultato.
                            $_SESSION['stampa_singola'] = "La tua ricerca ha restituito la seguente riservazione:<br><br>
                            <table class='table table-striped'>
                                <tr>
                                    <th>Email utente</th> 
                                    <th>ID Camera</th> 
                                    <th>Adulti</th>
                                    <th>Bambini</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                </tr>
                                <tr>
                                    <td>" . $row[2] . "</td>
                                    <td>" . $row[3] . "</td>
                                    <td>" . $row[5] . "</td>
                                    <td>" . $row[4] . "</td>
                                    <td>" . date("d-m-Y" , strtotime($row[0])) . "</td>
                                    <td>" . date("d-m-Y" , strtotime($row[1])) . "</td>
                                </tr>
                            </table>";
                            //Stampo la tabella salvata nella sessione.
                            echo $_SESSION['stampa_singola'];
                            unset($_SESSION['messaggio_invio']);
                            $_SESSION['messaggio_invio'] = "L&apos;email con la riservazione &egrave; stata inviata a '" . $_SESSION['email'] . "' con successo.";
                        //Se vengono ritornati più risultati vado a stampare una tabella contenente tutte le riservazioni restituite.    
                        } else if ($stmt->rowCount() > 1) {
                            unset($_SESSION['stampa_singola']);
                            //Sessione che più avanti mi servirà a capire che la query ha ritornato più risultati.
                            $_SESSION['stampa_multipla'] = "La tua ricerca ha restituito le seguenti riservazioni:<br><br>
                            <table class='table table-<striped'>
                                <tr>
                                    <th>Email utente</th> 
                                    <th>ID Camera</th> 
                                    <th>Adulti</th>
                                    <th>Bambini</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                </tr>";
                            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                                $_SESSION['stampa_multipla'] .= "<tr>
                                        <td>"
                                    . $row[2] .
                                    "</td>
                                        <td>"
                                    . $row[3] .
                                    "</td>
                                        <td>"
                                    . $row[4] .
                                    "</td>
                                        <td>"
                                    . $row[5] .
                                    "</td>
                                        <td>"
                                    . date("d-m-Y" , strtotime($row[0])) .
                                    "</td>
                                    <td>"
                                    . date("d-m-Y" , strtotime($row[1])) .
                                    "</td>
                                    </tr>";
                            }
                            $_SESSION['stampa_multipla'] .= "</table>";
                            //Stampo la tabella salvata nella sessione.
                            echo $_SESSION['stampa_multipla'];
                            unset($_SESSION['messaggio_invio']);
                            $_SESSION['messaggio_invio'] = "L&apos;email con le riservazioni &egrave; stata inviata a '" . $_SESSION['email'] . "' con successo.";
                        } else {
                            array_push($errors, "Nessuna riservazione trovata. Controlla i campi!");
                        }

                        //Ricarico la pagina così da svuotare il form.
                        //echo "<meta http-equiv='refresh' content='0'>";
                    } else {
                        array_push($errors, "Ops! Qualcosa è andato storto. Riprova");
                    }
                }
            }

            //Se l'utente decide di inviare la riservazione.
            if (isset($_POST['invia'])) {
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
                $mail->addAddress($_SESSION['email'], $_SESSION['nome_gerente']);              // Name is optional
                $mail->addReplyTo('gestionealloggi@hotmail.com', 'Gestione alloggi');

                // Content
                $mail->CharSet = "UTF-8";
                $mail->Subject = 'Stampa riservazioni';

                //Se il risultato ritornato conteneva una sola riservazione.
                if (isset($_SESSION['stampa_singola'])) {
                    $body = $_SESSION['stampa_singola'];
                    $mail->Body = $body . "<br>In allegato è possibile trovare un pdf da stampare.";

                    $pdf = new \Mpdf\Mpdf();
                    $pdf->WriteHTML($_SESSION['stampa_singola']);
                    $pdf_riservazioni = $pdf->Output('riservazioni.pdf', 'S');
                //Se il risultato ritornato conteneva più riservazioni.
                } else if (isset($_SESSION['stampa_multipla'])) {
                    $body = $_SESSION['stampa_multipla'];
                    $mail->Body = $body . "<br>In allegato è possibile trovare un pdf da stampare.";

                    $pdf = new \Mpdf\Mpdf();
                    $pdf->WriteHTML($_SESSION['stampa_multipla']);
                    $pdf_riservazioni = $pdf->Output('riservazioni.pdf', 'S');
                }

                //Aggiungo un pdf in allegato all'email.
                $mail->addStringAttachment($pdf_riservazioni, 'riservazioni.pdf');
                $mail->isHTML(true);

                //Mando l'email.
                if (!$mail->send()) {
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                }

                //Stampo un messaggio se presente.
                if(isset($_SESSION['messaggio_invio'])){
                    echo "<p class='text-center' style='color:green'>" . $_SESSION['messaggio_invio'] . "</p>";
                }

                unset($mail);
                unset($pdf);
            }

            ?>

        <!-- Stampo il form con il bottone per inviare le riservazioni solo se l'utente ha prima cercato le riservazione e se non ci sono errori. -->
        <?php if (isset($_POST['cerca']) && count($errors) == 0) : ?>
            <article class='card-body mx-auto' style='max-width:450px;'>
                <form action='stampa.php' method='post'>
                    <button id='invia' type='submit' name='invia' class='btn btn-primary btn-block'><?php if (isset($_SESSION['stampa_singola'])) : echo "Invia la riservazione";
                                                                                                            elseif (isset($_SESSION['stampa_multipla'])) : echo "Invia le riservazioni";
                                                                                                            endif; ?></button><br>
                </form>
            </article>
        <?php endif;
            //Nel caso ci siano errori tolgo i messaggi vecchi e stampo gli errori.
            if (count($errors) > 0) :
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
        <?php endif; ?>


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