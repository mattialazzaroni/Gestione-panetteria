<?php
header("Content-Type: text/html; charset=ISO-8859-1");
ob_start();
//Includo il file amministratore-gerente.php.
include('amministratore.php');
//Includo il file server.php.
include('server.php');
//Metodo che torna a permette di stampare tutto quello che segue.
ob_end_clean();

//Dichiaro le variabili in cui salverò gli input.
$email = "";
$nome = "";
$cognome =  "";
$password_1 = "";
$password_2 = "";
$telefono = "";

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
    //Soltanto se ho fatto il login com amministratore posso accedere a questa pagina.
    if (isset($_SESSION['amministratore'])) :

        //Se viene cliccato il bottone per aggiungere un gerente.
        if (isset($_POST['aggiungi'])) {

            //Prendo gli input dell'amministratore e li metto nelle variabili.
            $email = $_POST['email'];
            $nome = $_POST['nome'];
            $cognome = $_POST['cognome'];
            $password_1 = $_POST['password_1'];
            $password_2 = $_POST['password_2'];
            $telefono = $_POST['telefono'];

            //Controllo gli input.
            if (empty($email)) {
                array_push($errors, "L'email &egrave; richiesta");
            } else {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    array_push($errors, "Il formato dell'email non &egrave; valido");
                }
            }

            if (empty($nome)) {
                array_push($errors, "Il nome &egrave; richiesto");
            } else {
                if (strcspn($nome, '0123456789') != strlen($nome)) {
                    array_push($errors, "Il nome non pu&ograve; contenere valori numerici");
                }
            }

            if (empty($cognome)) {
                array_push($errors, "Il cognome &egrave; richiesto");
            } else {
                if (strcspn($cognome, '0123456789') != strlen($cognome)) {
                    array_push($errors, "Il cognome non pu&ograve; contenere valori numerici");
                }
            }

            if (empty($password_1)) {
                array_push($errors, "La password &egrave; richiesta");
            } else {
                if (strlen(trim($password_1)) < 6) {
                    array_push($errors, "La password &egrave; troppo corta");
                }
                $password_1 = trim($password_1);
            }
            if ($password_1 != $password_2) {
                array_push($errors, "Le due password non coincidono");
            }

            if (empty($telefono)) {
                array_push($errors, "Il numero di telefono &egrave; richiesto");
            } else {
                $telefono_no_spazi = str_replace(" ", "", $telefono);
                $telefono_no_piu = str_replace("+", "", $telefono_no_spazi);
                if (!is_numeric($telefono_no_piu)) {
                    array_push($errors, "Il formato per il numero di telefono non &egrave; valido");
                }
            }

            $email = trim($email);
            $nome = trim($nome);

            //Serie di query per controllare che le email non siano già in uso in nessun altro utente e che il nome non sia presente in quel gerente.
            $gerente_email_check = "SELECT * FROM amministratore_gerente WHERE email = '" . $email . "' LIMIT 1";
            $gerente_nome_check = "SELECT * FROM amministratore_gerente WHERE nome = '" . $nome . "' LIMIT 1";
            $amministratore_email_check = "SELECT * FROM amministratore WHERE email = '" . $email . "' LIMIT 1";
            $utente_email_check = "SELECT * FROM utente WHERE email = '" . $email . "' LIMIT 1";

            //Preparo le query.
            $stmt = $db->prepare($gerente_email_check);
            $stmt1 = $db->prepare($gerente_nome_check);
            $stmt2 = $db->prepare($amministratore_email_check);
            $stmt3 = $db->prepare($utente_email_check);

            //Eseguo la query del controllo degli amministratori gerenti.
            $stmt->execute();
            $stmt1->execute();
            $stmt2->execute();
            $stmt3->execute();
            //Se la query trova un utente, un amministratore o un amministratore gerente con quell'email stampo un errore.
            if ($stmt->rowCount() == 1) {
                array_push($errors, "La seguente email &egrave; gi&agrave; in uso (vedi sotto nella tabella)");
            }
            if ($stmt1->rowCount() == 1) {
                array_push($errors, "Il seguente nome &egrave; gi&agrave; in uso (vedi sotto nella tabella)");
            }
            if ($stmt2->rowCount() == 1) {
                array_push($errors, "La seguente email &egrave; gi&agrave; in uso per un amministratore");
            }
            if ($stmt3->rowCount() == 1) {
                array_push($errors, "La seguente email &egrave; gi&agrave; in uso per un utente");
            }
            if (count($errors) == 0) {
                $password_hashata = password_hash($password_1, PASSWORD_DEFAULT);
                //Query che inserisce l'amministratore gerente se non ci sono errori.
                $insert_query = "INSERT INTO amministratore_gerente VALUES
                ('" . $email . "', '" . $nome . "', '" . $cognome . "', '" . $password_hashata . "', '" . $telefono . "')";

                $stmt = $db->prepare($insert_query);

                //Eseguo le query.
                if ($stmt->execute()) {
                    $_SESSION['message'] = "<div class='text-success text-center'>
                    <p style='color:green'>L&apos;amministratore gerente con email '" . $email . "' &egrave; stato aggiunto con successo.</p>
                    </div>";

                    //Ricarico la pagina così da svuotare il form.
                    echo "<meta http-equiv='refresh' content='0'>";
                } else {
                    array_push($errors, "Ops! Qualcosa è andato storto. Riprova");
                }
            }
        }


        //Se viene cliccato il bottone per modificare.
        if (isset($_POST['modifica'])) {

            $id_da_controllare;
            //Prendo la posizione del gerente da modificare.
            $indice_da_modificare = $_POST['modifica'];
            //Query che prende tutti i gerenti.
            $gerenti_query = "SELECT * FROM amministratore_gerente";
            $stmt = $db->prepare($gerenti_query);
            $stmt->execute();

            //Se nel db è presente almeno un gerente.
            if ($stmt->rowCount() > 0) {

                for ($i = 1; $i < $stmt->rowCount() + 1; $i++) {
                    if ($i == $indice_da_modificare) {
                        //Imposto l'id da controllare.
                        $id_da_controllare = $i;
                    }
                }

                //Query che prende le info del gerente in base alla riga specificata da rownumber.
                $email_query = "SELECT * FROM
                (SELECT row_number() OVER (ORDER BY email ASC) AS rownumber,
                email,
                nome,
                cognome,
                n_telefono 
                FROM amministratore_gerente)
                as get_email
                where rownumber = " . $id_da_controllare . "";

                //Preparo la query
                $stmt = $db->prepare($email_query);

                //Eseguo la query.
                if ($stmt->execute()) {
                    $row = $stmt->fetch(PDO::FETCH_NUM);
                    //Salvo i valori restituite dalla query.
                    $email = $_SESSION['email_definitiva'] = $row[1];
                    $nome = $_SESSION['nome_definitivo'] = $row[2];
                    $cognome = $row[3];
                    $password_1 = "";
                    $password_2 = "";
                    $telefono = $row[4];
                    $_SESSION['controlli'] = true;
                } else {
                    array_push($errors, "Ops! Qualcosa è andato storto. Riprova");
                }
            } else {
                array_push($errors, "Ops! Qualcosa è andato storto. Riprova");
            }
        }

        //Se viene inviato il form modifica_controlli.
        if (isset($_POST['modifica_controlli'])) {

            //Salvo gli input nelle variabili.
            $email = $_POST['email'];
            $nome = $_POST['nome'];
            $cognome = $_POST['cognome'];
            $password_1 = $_POST['password_1'];
            $password_2 = $_POST['password_2'];
            $telefono = $_POST['telefono'];

            //Controllo gli input.
            if (!empty($email)) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    array_push($errors, "Il formato dell'email non &egrave; valido");
                }
            } else {
                $email = null;
            }

            if (!empty($nome)) {
                if (strcspn($nome, '0123456789') != strlen($nome)) {
                    array_push($errors, "Il nome non pu&ograve; contenere valori numerici");
                }
            } else {
                $nome = null;
            }

            if (!empty($cognome)) {
                if (strcspn($cognome, '0123456789') != strlen($cognome)) {
                    array_push($errors, "Il cognome non pu&ograve; contenere valori numerici");
                }
            } else {
                $cognome = null;
            }

            if (!empty($password_1)) {
                if (empty($password_2)) {
                    array_push($errors, "Se vuoi cambiare la password devi inserirla due volte");
                } else if (!empty($password_2) && strlen(trim($password_1)) < 6) {
                    array_push($errors, "La password &egrave; troppo corta");
                }
                $password_1 = trim($password_1);
                $password_2 = trim($password_2);
                if ($password_1 != $password_2) {
                    array_push($errors, "Le due password non coincidono");
                }
            }

            if (empty($password_1)) {
                $password_1 = null;
            }
            if (empty($password_2)) {
                $password_2 = null;
            }

            if (!empty($telefono)) {
                $telefono_no_spazi = str_replace(" ", "", $telefono);
                $telefono_no_piu = str_replace("+", "", $telefono_no_spazi);
                if (!is_numeric($telefono_no_piu)) {
                    array_push($errors, "Il formato per il numero di telefono non &egrave; valido");
                }
            } else {
                $telefono = null;
            }

            $email = trim($email);
            $nome = trim($nome);
            $cognome = trim($cognome);

            if ($email != $_SESSION['email_definitiva']) {
                $gerente_email_check = "SELECT * FROM amministratore_gerente WHERE email = '" . $email . "' LIMIT 1";
                $stmt = $db->prepare($gerente_email_check);
                $stmt->execute();
                if ($stmt->rowCount() == 1) {
                    array_push($errors, "La seguente email &egrave; gi&agrave; in uso (vedi sotto nella tabella)");
                }
            }
            if ($nome != $_SESSION['nome_definitivo']) {
                $gerente_nome_check = "SELECT * FROM amministratore_gerente WHERE nome = '" . $nome . "' LIMIT 1";
                $stmt = $db->prepare($gerente_nome_check);
                $stmt->execute();
                if ($stmt->rowCount() == 1) {
                    array_push($errors, "Il seguente nome &egrave; gi&agrave; in uso (vedi sotto nella tabella)");
                }
            }
            //Query che per controllare che nessun amminsitratore e nessun utente abbia quell'email.
            $amministratore_email_check = "SELECT * FROM amministratore WHERE email = '" . $email . "' LIMIT 1";
            $utente_email_check = "SELECT * FROM utente WHERE email = '" . $email . "' LIMIT 1";

            //Preparo le query.
            $stmt1 = $db->prepare($amministratore_email_check);
            $stmt2 = $db->prepare($utente_email_check);

            //Eseguo le query per il controllo.
            $stmt1->execute();
            $stmt2->execute();

            //Se la query trova un utente o un amministratore con quell'email stampo un errore.
            if ($stmt1->rowCount() == 1) {
                array_push($errors, "La seguente email &egrave; gi&agrave; in uso per un amministratore");
            }
            if ($stmt2->rowCount() == 1) {
                array_push($errors, "La seguente email &egrave; gi&agrave; in uso per un utente");
            }
            //Se non ci sono errori.
            if (count($errors) == 0) {

                //Seleziono tutte le informazioni del gerente da modificare.
                $gerente_da_modificare = "SELECT * FROM amministratore_gerente WHERE email = '" . $_SESSION['email_definitiva'] . "' LIMIT 1";

                //Prepato la query.
                $stmt = $db->prepare($gerente_da_modificare);

                //Eseguo la query.
                if ($stmt->execute()) {

                    $row = $stmt->fetch(PDO::FETCH_NUM);

                    //Mi salvo tutte le varie informazioni del gerente.
                    if (empty($email)) {
                        $email = $row[0];
                    }
                    if (empty($nome)) {
                        $nome = $row[1];
                    }
                    if (empty($email)) {
                        $cognome = $row[2];
                    }
                    if (empty($password_1)) {
                        $password_1 = $row[3];
                    } else if (!empty($password_1)) {
                        $password_1 = password_hash($password_1, PASSWORD_DEFAULT);
                    }
                    if (empty($password_2)) {
                        $password_2 = $row[3];
                    } else if (!empty($password_2)) {
                        $password_2 = password_hash($password_2, PASSWORD_DEFAULT);
                    }
                    if (empty($telefono)) {
                        $telefono = $row[4];
                    }

                    //Query che aggiorna il gerente.
                    $update_query = "UPDATE amministratore_gerente 
                    SET email = '" . $email . "', nome = '" . $nome . "', cognome = '" . $cognome . "', password_admin_gerente = '" . $password_1 . "', n_telefono = '" . $telefono . "'  
                    WHERE email = '" . $_SESSION['email_definitiva'] . "'";

                    //Preparo la query.
                    $stmt = $db->prepare($update_query);

                    //Eseguo le query.
                    if ($stmt->execute()) {

                        $_SESSION['message'] = "<br><div class='text-success text-center'>
                        <p style='color:green'>L&apos;amministratore gerente con email '" . $_SESSION['email_definitiva'] . "' &egrave; stato modificato con successo.</p>
                        </div>";

                        //Ricarico la pagina così da svuotare il form.
                        echo "<meta http-equiv='refresh' content='0'>";
                    } else {
                        array_push($errors, "Ops! Qualcosa è andato storto. Riprova");
                    }
                } else {
                    array_push($errors, "Ops! Qualcosa è andato storto. Riprova");
                }
                $_SESSION['controlli'] = null;
            }
        }


        //Se viene cliccato il bottone per eliminare.
        if (isset($_POST['elimina'])) {

            $id_da_controllare;
            //Prendo la posizione del gerente da eliminare.
            $indice_da_eliminare = $_POST['elimina'];
            //Query che prende tutti i gerenti.
            $gerenti_query = "SELECT * FROM amministratore_gerente";
            $stmt = $db->prepare($gerenti_query);
            $stmt->execute();

            //Se nel db è presente almeno un gerente.
            if ($stmt->rowCount() > 0) {

                for ($i = 1; $i < $stmt->rowCount() + 1; $i++) {
                    if ($i == $indice_da_eliminare) {
                        //Imposto l'id da controllare.
                        $id_da_controllare = $i;
                    }
                }

                //Query che prende le info del gerente in base alla riga specificata da rownumber.
                $email_query = "SELECT * FROM
                (SELECT row_number() OVER (ORDER BY email ASC) AS rownumber, email 
                FROM amministratore_gerente)
                as get_email
                where rownumber = " . $id_da_controllare . "";

                //Preparo la query.
                $stmt = $db->prepare($email_query);

                //Eseguo la query.
                if ($stmt->execute()) {

                    $row = $stmt->fetch(PDO::FETCH_NUM);
                    $email_da_eliminare = $row[1];

                    //Disabilito i controlli ed elimino il gerente.
                    $disable_checks = "SET FOREIGN_KEY_CHECKS=0";
                    $delete_query = "DELETE FROM amministratore_gerente
                    WHERE email = '" . $email_da_eliminare . "'";
                    $enable_checks = "SET FOREIGN_KEY_CHECKS=1";

                    //Preparo le query.
                    $stmt = $db->prepare($disable_checks);
                    $stmt1 = $db->prepare($delete_query);
                    $stmt2 = $db->prepare($enable_checks);
                    //Eseguo le query.
                    $stmt->execute();
                    $stmt1->execute();
                    $stmt2->execute();

                    $_SESSION['message'] = "<div class='text-success text-center'>
                <p style='color:green'>L&apos;amministratore gerente con email " . $email_da_eliminare . " &egrave; stato eliminato con successo.</p>
                </div>";
                    $_SESSION['controlli'] = null;

                    echo "<meta http-equiv='refresh' content='0'>";
                    //Notifico che l'amministratore gerente è stato eliminato.
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
            <form id="formAmministratori" method="post" action="gestisci-gerenti.php">
                <h4 class="card-title mt-3 text-center">Gestisci gli amministratori gerenti</h4><br>
                <?php if (isset($_SESSION['controlli'])) : ?><p class="grey-text text-center">Se non vuoi modificare una certa informazione non modificare il campo oppure lascialo vuoto </p><?php endif ?>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                    </div>
                    <input name="email" class="form-control" placeholder="Inserisci l'email del gerente" type="text" value="<?php echo $email; ?>" title="Email del gerente">
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                    </div>
                    <input name="nome" class="form-control" placeholder="Inserisci il nome del gerente" type="text" title="Nome del gerente" value="<?php echo $nome; ?>">
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                    </div>
                    <input name="cognome" class="form-control" placeholder="Inserisci il cognome del gerente" type="text" title="Cognome del gerente" value="<?php echo $cognome; ?>">
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                    </div>
                    <input name="password_1" class="form-control" placeholder="Crea la password del gerente" type="password" title="Password del gerente" value="<?php if (!isset($_POST['modifica'])) {
                                                                                                                                                                                                echo $password_1;
                                                                                                                                                                                            } ?>">
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                    </div>
                    <input name="password_2" class="form-control" placeholder="Ripeti la password del gerente" type="password" title="Password del gerente" value="<?php if (!isset($_POST['modifica'])) {
                                                                                                                                                                                                echo $password_2;
                                                                                                                                                                                            } ?>">
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-phone"></i> </span>
                    </div>
                    <input name="telefono" class="form-control" placeholder="Inserisci il numero del gerente" type="text" title="Numero di telefono del gerente" value="<?php echo $telefono; ?>">
                </div>
                <button id="aggiungi" type="submit" <?php if (!isset($_SESSION['controlli'])) {
                                                            echo 'name="aggiungi"';
                                                        } elseif (isset($_SESSION['controlli'])) {
                                                            echo 'name="modifica_controlli"';
                                                        } ?> class="btn btn-primary btn-block"> <?php if (!isset($_SESSION['controlli'])) {
                                                                                                    echo 'Aggiungi';
                                                                                                } elseif (isset($_SESSION['controlli'])) {
                                                                                                    echo 'Salva modifiche';
                                                                                                } ?></button>
            </form>
        </article>

        <?php if (count($errors) > 0) :
                //Se ci sono errori li stampo.
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
        <?php endif;


    if (isset($_SESSION['message'])) {
        echo "<br>";
        echo $_SESSION['message'];
    }
    //Query che prende tutti i gerenti.
    $gerenti_query = "SELECT * FROM amministratore_gerente";
    $stmt = $db->prepare($gerenti_query);
    $stmt->execute();
    //Se è presente almeno 1 gerente.
    if ($stmt->rowCount() > 0) {
        //Stampo una tabella con un gerente per riga.
        echo "<br><form id='formTabella' action='gestisci-gerenti.php' method='post'><div class='table-responsive'>
                <table class='text-center table table-striped'>
            <tr style='background-color:#D3D3D3;'>
                <th>Email</th> 
                <th>Nome</th>
                <th>Cognome</th>
                <th>Numero di telefono</th>
                <th>Modifica</th>
                <th>Elimina</th>
            </tr>
            <tr>";
                //For che cicla tutti i gerenti.
                for ($i = 1; $i < $stmt->rowCount() + 1; $i++) {
                    $row = $stmt->fetch(PDO::FETCH_NUM);
                    echo "<tr>
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
                <br><p style='color:red'>Nessun amministratore gerente presente nel database</p>
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