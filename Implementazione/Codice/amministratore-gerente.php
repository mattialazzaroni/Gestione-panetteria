<?php
if (isset($_POST['disponibilita'])) {
    header('Location: carica-disponibilita.php');
} else if (isset($_POST['clienti'])) {
    header('Location: gestisci-clienti.php');
} else if (isset($_POST['stampa'])) {
    header('Location: stampa.php');
} 
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Amministratore gerente</title>
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

    <?php
    header("Content-Type: text/html; charset=ISO-8859-1");
    ob_start();
    //Includo il file che esegue il login.
    include('index.php');
    //Metodo che torna a permette di stampare tutto quello che segue.
    ob_end_clean();
    if (isset($_SESSION['amministratore_gerente'])) :
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
                                <a class="nav-link waves-effect" href="#">Amministratore gerente
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
        <article class="card-body mx-auto" style="max-width: 450px; margin-top: 75px;">
            <h4 class="card-title mt-3 text-center">Benvenuto!</h4>
            <p class="text-center">In questa pagina puoi caricare le disponibilit&agrave; delle strutture, gestire i clienti che hanno riservato gli alloggi e stampare le  riservazioni per un determinato periodo e/o persona.</p>
            <form method="post" action="amministratore-gerente.php">
                <div class="form-group">
                    <button id="disponibilita" name="disponibilita" type="submit" class="btn btn-primary btn-block"> Carica disponibilit&agrave; </button>
                </div> <!-- form-group// -->
                <div class="form-group">
                    <button id="clienti" name="clienti" type="submit" class="btn btn-primary btn-block"> Gestisci clienti </button>
                </div> <!-- form-group// -->
                <div class="form-group">
                    <button id="stampa" name="stampa" type="submit" class="btn btn-primary btn-block"> Stampa riservazioni </button>
                </div> <!-- form-group// -->
            </form>
        </article>

    <?php else : ?>
        <!-- Contenuto visivo della pagina -->
        <article class="card-body mx-auto" style="max-width: 450px;">
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