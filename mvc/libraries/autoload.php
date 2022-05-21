<?php
	#Función que usaremos para buscar las clases
	function load($clase) {
		#Variable global
		global $arrAutoloadDir;

		#Para cada directorio de la lista
		foreach($arrAutoloadDir as $autoloadDir) {
			#Calcula la ruta
			$strPath="mvc/$autoloadDir/$clase.php";

			if (is_readable($strPath)) {#Si es legible
				require_once $strPath;	#Carga la clase
				break;					#Ahorra iteraciones
			}
		}
	}
	spl_autoload_register("load");