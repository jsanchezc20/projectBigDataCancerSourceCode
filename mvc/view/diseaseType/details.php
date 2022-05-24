<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>
			Pregunta <?=$intPreguntaID?>
		</title>
		<link rel="stylesheet" type="text/css" href="<?=DEFAULT_PATH?>css/normalize.css">
		<link rel="stylesheet" type="text/css" href="<?=DEFAULT_PATH?>css/main.css">
		<script src="<?=DEFAULT_PATH?>js/main.js"></script>
	</head>
	<body>
		<?php
			# MOSTRA L'ENCAPÇALAMENT (HEADER)
			Template::header();
			# MOSTRA EL FORMULARI DE LOGIN I LOGOUT
			Template::LogIn($objUser);
			# MOSTRA EL MENÚ
			Template::menu();
			#MUESTRA LOS DETALLES DE LA ACCIÓN Y SUS PREGUNTAS
			Template::getDetails($arrObj,"action");
			#MUESTRA EL PIE DE PÁGINA
			Template::footer();
		?>
	</body>
</html>