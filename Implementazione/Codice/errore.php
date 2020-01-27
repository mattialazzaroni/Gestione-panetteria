<?php

?>

<!-- Pagina che viene mostrata quando l'utente inserisce un url non esistente -->
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Errore!</title>
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

	<!-- Contenuto visivo della pagina -->
	<article class="card-body mx-auto" style="max-width: 450px;">
		<h4 class="card-title mt-3 text-center">Errore!</h4>
		<p class="text-center">Questa pagina non esite oppure non hai il permesso per accederci.</p>
		<form action="index.php">
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-block"><a href="index.php"></a> Torna alla home </button>
			</div> <!-- form-group// -->
		</form>
	</article>
</body>
</html>