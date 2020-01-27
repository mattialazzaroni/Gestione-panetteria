<?php

//Includo il file che esegue la connessione al database.
include('server.php');

//Creo le variabili per l'email e la password.
$email = '';
$password = '';

//Se il form di login viene confermato dall'utente.
if (isset($_POST['login_user'])) {
	//Imposto le variabili con i dati dell'utente.
	$email = $_POST['email'];
	$password = $_POST['password'];

	//Preparo dei messaggi di errori se l'utente lascia dei campi vuoti.
	if (empty($email)) {
		array_push($errors, "L'email è richiesta");
	}
	if (empty($password)) {
		array_push($errors, "La password è richiesta");
	}

	//Se non ci sono errori.
	if (count($errors) == 0) {
		//Query per controllare che un utente esista con quella email.
		$query_utente = "SELECT * FROM utente WHERE email = :email";
		//Query per controllare che un amministratore esista con quella email.
		$query_amministratore = "SELECT * FROM amministratore WHERE email = :email";
		//Query per controllare che un amministratore gerente esista con quella email.
		$query_amministratore_gerente = "SELECT * FROM amministratore_gerente WHERE email = :email";

		//Preparo le query.
		$stmt1 = $db->prepare($query_utente);
		$stmt2 = $db->prepare($query_amministratore);
		$stmt3 = $db->prepare($query_amministratore_gerente);

		//Associo i parametri alla variabile contenente l'email.
		$stmt1->bindParam(":email", $param_email, PDO::PARAM_STR);
		$stmt2->bindParam(":email", $param_email, PDO::PARAM_STR);
		$stmt3->bindParam(":email", $param_email, PDO::PARAM_STR);
		$param_email = trim($email);

		//Eseguo le query.
		$stmt1->execute();
		$stmt2->execute();
		$stmt3->execute();

		//Se la query ritorna un risultato.
			if ($stmt1->rowCount() == 1) {
				$row = $stmt1->fetch(PDO::FETCH_ASSOC);
				$email = $row["email"];
				$hashed_password = $row["password_utente"];
				$active = $row["is_active"];
				if ($active == 1) {
					//Se la password inserita dall'utente coincide con la password hashata presente nel db è legata a quell'email.
					if (password_verify($password, $hashed_password)) {
						//Imposto delle variabili session e sposto l'utente alla homepage.
						$_SESSION["name"] = $_SESSION["nome_utente"] = $row["nome"];
						$_SESSION["email"] = $row["email"];
						$_SESSION["utente"] = true;
						$_SESSION["loggedin"] = true;
						$_SESSION["type"] = "utente";
						header('location: index.php');
					}
					//Altrimenti indico all'utente che la password inserita è errata.
					else {
						array_push($errors, "La password che hai inserito non è corretta");
					}
				} else {
					array_push($errors, "Non hai verificato l'account, controlla la tua email!");
				}
			} else if ($stmt2->rowCount() == 1) {
			$row = $stmt2->fetch(PDO::FETCH_ASSOC);
			$hashed_password = $row["password_admin"];
			//Se la password inserita dall'utente coincide con la password hashata presente nel db e legata a quell'email.
			if (password_verify($password, $hashed_password)) {
				//Imposto delle variabili session e sposto l'utente alla homepage.
				$_SESSION["name"] = $_SESSION["nome_amministratore"] = $row["nome"];
				$_SESSION["email"] = $row["email"];
				$_SESSION["amministratore"] = true;
				$_SESSION["loggedin"] = true;
				$_SESSION["type"] = "amministratore";
				header('location: index.php');
			}
			//Altrimenti indico all'utente che la password inserita è errata.
			else {
				array_push($errors, "La password che hai inserito non è corretta");
			}
		} else if ($stmt3->rowCount() == 1) { 
			$row = $stmt3->fetch(PDO::FETCH_ASSOC);
			$hashed_password = $row["password_admin_gerente"];
			$email = $row["email"];
			//Se la password inserita dall'utente coincide con la password hashata presente nel db e legata a quell'email.
			if (password_verify($password, $hashed_password)) {
				//Imposto delle variabili session e sposto l'utente alla homepage.
				$_SESSION["name"] = $_SESSION["nome_gerente"] = $row["nome"];
				$_SESSION["email"] = $row["email"];
				$_SESSION["amministratore_gerente"] = true;
				$_SESSION["loggedin"] = true;
				$_SESSION["type"] = "amministratore gerente";
				header('location: index.php');
			}
			//Altrimenti indico all'utente che la password inserita è errata.
			else {
				array_push($errors, "La password che hai inserito non è corretta");
			}
		} 
		else {
			array_push($errors, "Nessun utente trovato con questa email");
		}
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Login</title>
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

	<!-- Contenuto visivo della pagina con form di login -->
	<article class="card-body mx-auto" style="max-width: 450px;">
		<h4 class="card-title mt-3 text-center">Accedi al tuo account</h4>
		<form method="post" action="login.php">
			<div class="form-group input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
				</div>
				<input name="email" class="form-control" placeholder="Inserisci la tua email" type="email" value="<?php echo $email; ?>">
			</div> <!-- form-group// -->
			<div class="form-group input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"> <i class="fa fa-lock"></i> </span>
				</div>
				<input name="password" class="form-control" placeholder="Inserisci la tua password" type="password" value="<?php echo $password; ?>">
			</div> <!-- form-group// -->

			<!-- Stampa degli errori -->
			<?php if (count($errors) > 0) : ?>
				<div class="error">
					<?php foreach ($errors as $error) : ?>
						<p style="color:red"><?php echo $error ?></p>
					<?php endforeach ?>
				</div>
			<?php endif ?>

			<div class="form-group">
				<button type="submit" name="login_user" class="btn btn-primary btn-block"> Accedi </button>
			</div> <!-- form-group// -->
			<p class="text-center">Non hai ancora un account? <a href="signup.php">Registrati</a> </p>
			<p class="text-center">Oppure <a href="index.php">torna alla home </a> </p>
		</form>
	</article>
</body>

</html>