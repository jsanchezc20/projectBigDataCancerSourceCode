<?php
	# DIRECTORIS PER AUTOLOAD
	$arrAutoloadDir = ["controller","libraries","model","templates"];

	# PARÀMETRES DE CONFIGURACIÓ DE LA BDD
	define("SGDB","mysql");					# CONNECTOR
	define("DB_HOST","localhost");			# HOST
	define("DB_USER","admin");				# USUARI
	define("DB_PASS","JsZT*-Hf!t_DWwq)");	# CONTRASENYA
	define("DB_NAME","big_data_cancer");	# BASE DE DADES
	define("DB_CHARSET","utf8");			# CODIFICACIÓ
	
	# CONTROLADOR I MÈTODE PER DEFECTE
	define("DEFAULT_CONTROLLER","Welcome");
	define("DEFAULT_METHOD","index");
	
	# DEFINE("DEFAULT_PARAMETER",1);

	define("DEBUG",0);

	# RUTA ARREL DEL PROJECTE
	define("DEFAULT_PATH","http://bigdatacancer.com/");