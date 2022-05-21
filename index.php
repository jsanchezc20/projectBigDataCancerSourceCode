<?php
	session_start();

	#CARGAR RECURSOS
	include 'mvc/config/config.php';
	include 'mvc/libraries/autoload.php';

	# VECTOR D"IMATGES DE LES OPERACIONS "CRUD"
	$arrColors = [
		"GN" => "green",
		"WT" => "white"
	];

	$arrCRUD = [
		"C"	 =>  "create",	# CREAR
		"R"	 =>  "read",	# DETALL
		"U"	 =>  "update",	# ACTUALITZAR
		"D"	 =>  "delete"	# ESBORRAR
	];

	# CREA CONSTANTS DE LES IMATGES DEL VECTOR "CRUD"
	foreach	($arrColors as $code => $color)
		foreach ($arrCRUD as $key => $value) {
			ob_start();
			Utils::loadFile("svg", "crud/$color/$value");
			$strAux = ob_get_clean();
			define("CRUD_" . $code . "_" . strtoupper($value), $strAux);
		}

	#CARGA EL CONTROLADOR FRONTAL
	FC::main();