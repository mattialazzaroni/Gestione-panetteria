<?php
//Metodo che impedisce a tutto quello che segue di essere stampato a schermo.
ob_start();
//Includo il file che esegue il login.
include('login.php');
//Metodo che torna a permette di stampare tutto quello che segue.
ob_end_clean();
?>

<!-- Homepage del progetto -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <meta name="00.14.00 20.12.2019" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Riserva subito un alloggio!</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- Material Design Bootstrap -->
  <link href="css/mdb.min.css" rel="stylesheet">
  <!-- Your custom styles (optional) -->
  <link href="css/style.min.css" rel="stylesheet">
</head>

<!-- Contenuto visivo della pagina -->

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
          <ul class="navbar-nav mr-auto" style="margin-bottom:5px;">
            <li class="nav-item active">
              <a class="nav-link waves-effect" href="#">Tutte le strutture
                <span class="sr-only">(current)</span>
              </a>
            </li>
            <?php
            //Se si è loggati come amministratore gerente o amministratore mostro il rispettivo bottone per il menu dedicato.
            if (isset($_SESSION['amministratore_gerente'])) :
            ?>
              <li class="nav-item">
                <a class="nav-link waves-effect" href="amministratore-gerente.php">Amministratore gerente</a>
              </li>
            <?php endif; ?>
            <?php
            if (isset($_SESSION['amministratore'])) :
            ?>
              <li class="nav-item">
                <a class="nav-link waves-effect" href="amministratore.php">Amministratore</a>
              </li>
            <?php endif; ?>
          </ul>
          <!-- Right -->
          <ul class="navbar-nav nav-flex-icons">
          <?php
          //Se non è stato effettuato il login, dò all'utente la possibilità di farlo o di creare un account.
          if (!isset($_SESSION['loggedin'])) :
          ?>
            <li class="nav-item">
              <a href="signup.php" class="nav-link border border-light rounded waves-effect">
                <i class="fas fa-user-plus"></i>Registrati
              </a>
            </li>&nbsp;
            <li class="nav-item">
              <a href="login.php" class="nav-link border border-light rounded waves-effect">
                <i class="fas fa-sign-in-alt"></i>Login
              </a>
            </li>&nbsp;
          <?php endif;
          //Stampo il bottone per il logout se l'utente ha effettuato il login.
          if (isset($_SESSION['loggedin'])) :
          ?>
            <li class="nav-item">
              <a href="logout.php" class="nav-link border border-light rounded waves-effect">
                <i class="fas fa-sign-out-alt"></i>Logout
              </a>
            </li>
          <?php endif; ?>
          </ul>

        </div>

      </div>
    </nav>
    <!-- Navbar -->

  </header>
  <!--Main Navigation-->

  <!--Main layout-->
  <main class="mt-5 pt-5">
    <div class="container">

      <!--Section: Cards-->
      <section class="pt-5">

        <!-- Heading & Description -->
        <div class="wow fadeIn">
          <!--Section heading-->
          <?php
          //Se l'utente esegue il login cambio il messaggio di benvenuto.
          if (isset($_SESSION['loggedin'])) :
          ?>
            <h2 class="h1 text-center mb-5">Ciao <?php echo $_SESSION['name']; ?>, hai effettuato l'accesso come <b><?php echo $_SESSION['type']; ?>.</b></h2>
          <?php else : ?>
            <h2 class="h1 text-center mb-5">Cerca un alloggio </h2>
          <?php endif; ?>
          <!--Section description-->
        </div>
        <!-- Heading & Description -->

        <!-- Stampo la barra di ricerca dell'alloggio con i filtri. -->
        <div class="container" id="applyCSS">
          <div class="row">
            <div class="col-md-12">
              <div class="input-group" id="adv-search">
                <div class="input-group-btn">
                  <div class="btn-group" role="group">
                    <div class="dropdown dropdown-lg">
                      <form action="" role="form" method="post" id="formCerca" onsubmit="">
                        <input type="text" id="nome" name="nome" class="form-control" size="4.5em" placeholder="Cerca un alloggio" />
                        <button name="cerca" type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                      </form>
                      <button type="button" class="btn btn-default dropdown-toggle filtro" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>
                      <div class="dropdown-menu dropdown-menu-right" role="menu">
                        <form id="filtro" class="form-horizontal" action="index.php" role="form" method="post">
                          <h3>Filtri</h3>
                          <div class="form-group">
                            <label for="tipologia">Tipologia</label>
                            <select class="form-control" name="tipologia" id="selectTipologia">
                              <!-- faccio si che la tipologia selezionata rimanga anche dopo aver salvato il filtro. -->
                              <option value="Qualsiasi" <?php if ((isset($_POST['tipologia']) && $_POST['tipologia'] == "Qualsiasi") || (isset($_SESSION['filtro_tipologia']) && $_SESSION['filtro_tipologia'] == "Qualsiasi")) {
                                                          echo "selected";
                                                        } ?>>Qualsiasi</option>
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
                          <!-- faccio si che tutti i campi rimangano anche dopo aver salvato il filtro. -->
                          <div class="form-group">
                            <label for="contain">Regione</label>
                            <input class="form-control" name="regione" type="text" <?php if (isset($_POST['regione'])) {
                                                                                      echo 'value="' . $_POST['regione'] . '"';
                                                                                    } else if (isset($_SESSION['filtro_regione'])) {
                                                                                      echo 'value="' . $_SESSION['filtro_regione'] . '"';
                                                                                    } ?> />
                          </div>
                          <div class="form-group">
                            <label for="contain">Citt&#224;</label>
                            <input class="form-control" name="citta" <?php if (isset($_POST['citta'])) {
                                                                        echo 'value="' . $_POST['citta'] . '"';
                                                                      } else if (isset($_SESSION['filtro_citta'])) {
                                                                        echo 'value="' . $_SESSION['filtro_citta'] . '"';
                                                                      } ?> type="text" />
                          </div>
                          <div class="form-group">
                            <label for="contain">Nome gerente della struttura</label>
                            <input class="form-control" name="nome_gerente" <?php if (isset($_POST['nome_gerente'])) {
                                                                              echo 'value="' . $_POST['nome_gerente'] . '"';
                                                                            } else if (isset($_SESSION['filtro_nome_gerente'])) {
                                                                              echo 'value="' . $_SESSION['filtro_nome_gerente'] . '"';
                                                                            } ?> type="text" />
                          </div>
                          <button name="applica" type="submit" class="btn btn-primary applica-filtro float-right" onclick="this.form.submit()">Applica</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div><br><br>

    <?php

    //Se viene premuto il bottone applica.
    if (isset($_POST['applica'])) {
      //Imposto la tipologia selezionata in una variabile se la tipologia non è "Qualsiasi".
      if ($_POST["tipologia"] != "Qualsiasi") {
        $_SESSION['filtro_tipologia'] = $_POST["tipologia"];
      }
      //Imposto la sessione a null se la tipologia selezionata dall'utente è "Qualsiasi".
      else if ($_POST["tipologia"] == "Qualsiasi") {
        $_SESSION['filtro_tipologia'] = null;
      }
      //Imposto la regione in una variabile se viene inserita dall'utente.
      if (!empty($_POST["regione"])) {
        $_SESSION['filtro_regione'] = $_POST["regione"];
      }
      //Imposto la sessione a null se l'utente non inserisce nessuna regione.
      else if (empty($_POST["regione"])) {
        $_SESSION['filtro_regione'] = null;
      }
      //Imposto la città in una variabile se viene inserita dall'utente.
      if (!empty($_POST["citta"])) {
        $_SESSION['filtro_citta'] = $_POST["citta"];
      }
      //Imposto la sessione a null se l'utente non inserisce nessuna città.
      else if (empty($_POST["citta"])) {
        $_SESSION['filtro_citta'] = null;
      }
      //Imposto il nome del gerente in una variabile se viene inserita dall'utente.
      if (!empty($_POST["nome_gerente"])) {
        $_SESSION['filtro_nome_gerente'] = $_POST["nome_gerente"];
      }
      //Imposto la sessione a null se l'utente non inserisce nessun nome.
      else if (empty($_POST["nome_gerente"])) {
        $_SESSION['filtro_nome_gerente'] = null;
      }
    }
    if (isset($_POST['cerca'])) {
      //Imposto la città in una variabile se viene inserita dall'utente.
      if (!empty($_POST["nome"])) {
        $_SESSION['nome'] = $_POST["nome"];
      }
      //Imposto la sessione a null se l'utente non inserisce nessuna città.
      else if (empty($_POST["nome"])) {
        $_SESSION['nome'] = null;
      }
    }

    ob_start();
    //Includo il file che esegue la connessione al database.
    include('server.php');
    ob_end_clean();
    //Preparo la query per prendere l'id più grande così da sapere il numero di alloggi da stampare.
    $get_max_id = "SELECT id FROM alloggio ORDER BY id DESC LIMIT 1";
    $stmt = $db->prepare($get_max_id);
    $stmt->execute();
    $max_id = implode($stmt->fetch(PDO::FETCH_ASSOC));

    //Sessione per sapere se viene trovato almeno un alloggio.
    $_SESSION['no_data'] = true;

    //Ciclo che viene eseguito in base al numero di alloggi presenti e che stampa gli alloggi. 
    for ($i = 1; $i < $max_id + 1; $i++) {
      //Se viene cliccato il tasto di ricerca.
      if (isset($_POST['cerca'])) {
        //Variabile per la query filtrata.
        $whereNome = "";
        if (isset($_POST['nome']) && !empty($_POST['nome'])) {
          $whereNome = "AND nome like '%" . $_POST['nome'] . "%' ";
        }
        //Se viene utiilizzato un filtro.
        if (isset($_SESSION['filtro_tipologia']) || isset($_SESSION['filtro_regione']) || isset($_SESSION['filtro_citta']) || isset($_SESSION['filtro_nome_gerente']) || isset($whereNome)) {
          $where = "";
          //Se viene usato un filtro per la tipologia utilizzo una certa query.
          if (isset($_SESSION['filtro_tipologia'])) {
            $where = "AND nome_tipologia = '" . $_SESSION['filtro_tipologia'] . "' ";
          }
          //Se viene usato un filtro per la regione utilizzo una certa query.
          if (isset($_SESSION['filtro_regione'])) {
            $where .= "AND regione LIKE '%" . $_SESSION['filtro_regione'] . "%' ";
          }
          //Se viene usato un filtro per la città utilizzo una certa query.
          if (isset($_SESSION['filtro_citta'])) {
            $where .= "AND citta LIKE '%" . $_SESSION['filtro_citta'] . "%' ";
          }
          //Se viene usato un filtro per il nome del gerente utilizzo una certa query.
          if (isset($_SESSION['filtro_nome_gerente'])) {
            //Query che prende l'email del gerente in base al nome inserito facendo una join
            $get_nome_gerente = "SELECT alloggio.email_gerente FROM alloggio JOIN amministratore_gerente
            ON alloggio.email_gerente = amministratore_gerente.email WHERE amministratore_gerente.nome LIKE '%" . $_SESSION['filtro_nome_gerente'] . "%' ";
            $stmt = $db->prepare($get_nome_gerente);
            //Eseguo la query.
            $stmt->execute();
            $row = $stmt->fetch();
            $email_gerente = $row["email_gerente"];
            $where .= "AND email_gerente = '" . $email_gerente . "' ";
          }
          //Controllo se è stato inserito anche il nome di un alloggio.
          if (isset($whereNome)) {
            $accommodation_query = "SELECT id FROM alloggio WHERE id = $i $where $whereNome LIMIT 1";
          }
          //Altrimenti eseguo la query solo coi filtri senza nome.
          else {
            $accommodation_query = "SELECT id FROM alloggio WHERE id = $i $where LIMIT 1";
          }

          $stmt = $db->prepare($accommodation_query);
          //Eseguo la query.
          $stmt->execute();
          //Se eseguendo la query viene trovata una riga, preparo una nuova query.
          if ($stmt->rowCount() > 0) {
            $id = implode($stmt->fetch(PDO::FETCH_ASSOC));
            $accommodation_query = "SELECT * FROM alloggio WHERE id = $id LIMIT 1";
          }
          //Altrimenti interrompo la corrente iterazione e passo alla prossima.
          else {
            continue;
          }
        }
        //Se non viene inserito nessun filtro, stampo tutti gli alloggi.
        else {
          //Se viene inserito un nome di un alloggio.
          if (isset($whereNome)) {
            $accommodation_query = "SELECT * FROM alloggio WHERE id = $i $whereNome LIMIT 1";
            echo $accommodation_query;
          }
          //Se non viene inserito un nome.
          else {
            $accommodation_query = "SELECT * FROM alloggio WHERE id = $i LIMIT 1";
          }
        }
      }
      //Se non viene cliccato il bottone per cercare, stampo tutti gli alloggi.
      else {
        $accommodation_query = "SELECT * FROM alloggio WHERE id = $i LIMIT 1";
      }
      $stmt = $db->prepare($accommodation_query);
      //Eseguo la query.
      $stmt->execute();
      //Se viene trovato anche solo un alloggio, imposto a false la sessione che indica l'assenza di alloggi.
      if ($stmt->rowCount() > 0) {
        $_SESSION['no_data'] = false;
      }
      $row = $stmt->fetch();
      //Salvo alcune variabili utili.
      ${"nome" . $i}  = $row["nome"];
      ${"indirizzo" . $i} = $row["indirizzo"];
      ${"link_immagine" . $i} = $row["link_immagine"];
      ${"regione" . $i} = $row["regione"];
      ${"citta" . $i} = $row["citta"];
      ${"nome_tipologia" . $i} = $row["nome_tipologia"];
      ${"link_mappa" . $i} = $row["link_mappa"];
      ${"email_gerente" . $i} = $row["email_gerente"];
      //Stampo un alloggio.
      echo '<form id="dettagli' . $i . '" action="dettagli.php" method="post">
              <div class="row wow fadeIn">
                <div class="col-lg-5 col-xl-4 mb-4">
                  <div class="view overlay rounded z-depth-1">
                    <img src="' . ${"link_immagine" . $i} . '" class="img-fluid" alt="">
                  </div>
                </div>
                
                <div class="col-lg-7 col-xl-7 ml-xl-4 mb-4">
                  <h3 class="mb-3 font-weight-bold dark-grey-text">
                    <strong>' . ${"nome" . $i} . '</strong>
                  </h3>
                  <p>
                    <strong>' . ${"indirizzo" . $i} . '</strong>
                  </p>
                    <p class="grey-text">' . ${"citta" . $i} . ', ' . ${"regione" . $i} . '</p>
                  <!--<a href="#" target="_blank" class="btn btn-primary btn-md">Mostra dettagli
                    <i class="fas fa-play ml-2"></i>
                  </a>-->
                  <button name="mostraDettagli' . $i . '" type="submit" class="btn btn-primary" onclick="this.form.submit()">Mostra dettagli</button>
                </div>
              </div>
              <hr class="mb-5">
            </form>';

      //Salvo delle sessioni utili che indicano l'alloggio cliccato.
      //Queste variabili mi serviranno in "dettagli.php" per stampare le info dell'alloggio cliccato.
      if (isset($_POST['mostraDettagli' . $i])) {
        $_SESSION["nomeDettagli"] = ${"nome" . $i};
        $_SESSION["linkImmagineDettagli"] = ${"link_immagine" . $i};
        $_SESSION["regioneDettagli"] = ${"regione" . $i};
        $_SESSION["cittaDettagli"] = ${"citta" . $i};
        $_SESSION["linkMappaDettagli"] = ${"link_mappa" . $i};
        $_SESSION["indirizzoDettagli"] = ${"indirizzo" . $i};
        $_SESSION["emailGerente"] = ${"email" . $i};
        $_SESSION["id"] = $i;
      }
    }

    //Se non viene trovato nessun alloggio, stampo un messaggio d'errore.
    if ($_SESSION['no_data'] == true) {
      echo "<p class='text-center' style='color:red;'>Non &egrave stato trovato nessun alloggio. Controlla i filtri.</p>";
    }

    ?>

    </section>
    <!--Section: Cards-->

    </div>
  </main>
  <!--Main layout-->

  <!--Footer-->
  <footer class="page-footer text-center font-small mdb-color darken-2 mt-4 wow fadeIn">

    <hr class="my-4">

    <!-- Social icons -->
    <div class="pb-4">
      <a href="https://www.facebook.com/mattia.lazzaroni.92" target="_blank">
        <i class="fab fa-facebook-f mr-3"></i>
      </a>

      <a href="https://github.com/mattialazzaroni" target="_blank">
        <i class="fab fa-github mr-3"></i>
      </a>

      <a href="http://instagram.com/mattia.lazza" target="_blank">
        <i class="fab fa-instagram mr-3"></i>
      </a>
    </div>
    <!-- Social icons -->

    <!--Copyright-->
    <div class="footer-copyright py-3">
      &copy; <script>
        document.write(new Date().getFullYear())
      </script> Copyright:
      <a class="copyright" href="https://samtinfo.ch/i16lazmat/web" target="_blank"> Mattia Lazzaroni </a>
    </div>
    <!--/.Copyright-->

  </footer>
  <!--/.Footer-->

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