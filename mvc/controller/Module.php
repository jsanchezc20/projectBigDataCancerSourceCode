<?php
	class Module{
		# MÈTODE PER DEFECTE
		public function index() {
			#LISTADO DE MÓDULOS
			$this->list();
		}

		#LISTADO DE MÓDULOS
		public function list() {
			#RECUPERA LA LISTA DE MÓDULOS
			$objModulos=Modulo::getAll();

			if (empty($objModulos))
				throw new Exception("No hi ha registres per mostrar.");

			FC::getUserView("modulo/list", $objModulos);
		}

		#MUESTRA UNA MÓDULO
		public function read(int $id = 0) {
			# COMPROBA QUE ARRIBA EL CODI
			if (!$id)
				throw new Exception("No s'ha indicat l'identificador del módulo.");
			
			#RECUPERA EL MÓDULO
			$objModulo=Modulo::getOne($id);

			#COMPRUEBA QUE EL MÓDULO EXISTE
			if (!$objModulo)
				throw new Exception("No existeix el módulo con ID '$id'.");

			FC::getUserView("modulo/details", $objModulo);
		}

		# OBTÉ LA DESCRIPCIÓ DE LA TAULA PER MUNTAR EL FORMULARI
		public function create() {

			$strSQL = "DESCRIBE modulos;";
			
			$objModulos=DB::selectAll($strSQL);

			FC::getUserView("modulo/frmNew", $objModulos);
		}

		#CREA Y GUARDA EL NUEVO MÓDULO CON DATOS POST
		public function store() {
			# CREA L'OBJECTE
			$objModulo=new Modulo();

			#GUARDA LOS DATOS EN EL OBJETO EN FUNCIÓN
			#DE LAS CLAVES DEL ARRAY ASOCIATIVO $_POST 
			foreach ($_POST as $key1 => $value1) {
				foreach ($objModulo as $key2 => $value2) {
					if ($key1==$key2) {
						$objModulo->$key2=$value1;
						break;
					}
				}
			}
			$intAccionId=(int)$_POST["selAccionId"];
			# GUARDA A LA BD
			$id=$objModulo->insert($intAccionId);

			if ($id)
				$arrMsg = ["Success","Módulo<br>'$objModulo->nombre' amb codi '$objModulo->codigo'<br>insertado correctamente.","module"];
			else
				$arrMsg = ["Error","No se pudo guardar el módulo<br>'$objModulo->nombre' amb codi '$objModulo->codigo'.<br>No pot haver-hi una clau duplicada.","module"];

			FC::getUserView("frontSuccessError", $arrMsg);
		}

		#ACTUALIZA Y GUARDA LOS CAMBIOS DE EL MÓDULO
		public function update(int $id = 0) {
			# COMPROBA QUE ARRIBA L'ID
			if (!$id)
				throw new Exception("No s'ha indicat l'identificador del módulo.");

			#RECUPERA EL MÓDULO
			$objModulo=Modulo::getOne($id);

			#COMPRUEBA QUE EL MÓDULO EXISTE
			if (!$objModulo)
				throw new Exception("No existeix el módulo con ID '$id'.");

			# RECUPERA L'USUARI PARA PASARLO A LA VISTA
			#$objUser = UserLoginModel::getUser();

			FC::getUserView("modulo/frmUpdate", $objModulo);
		}

		public function update() {
			if (empty($_POST["actualizar"]))
				throw new Exception("No s'han rebut dades.");

			# CREA L'OBJECTE
			$objModulo=new Modulo();

			#GUARDA LOS DATOS QUE LLEGAN VÍA POST
			#EN FUNCIÓN DEL OBJETO Modulo
			foreach ($objModulo as $key => $value)
				$objModulo->$key=is_numeric($value)?(int)$_POST[$key]:$_POST[$key];

			# GUARDA A LA BD
			$id=$objModulo->update();
			if ($id)
				$arrMsg = ["Success","Módulo<br>'$objModulo->nombre' amb codi '$objModulo->codigo' actualizado correctamente.","module"];
			elseif ($id=== 0)
				$arrMsg = ["Warning","No s'han realitzat canvis en el módulo<br>'$objModulo->nombre' amb codi '$objModulo->codigo'.","module"];
			else
				$arrMsg = ["Error","No s'ha pogut actualitzar el módulo<br>'$objModulo->nombre' amb codi '$objModulo->codigo'.<br>No pot haver-hi una clau duplicada.","module"];

			FC::getUserView("frontSuccessError", $arrMsg);
		}

		#MUESTRA EL FORMULARIO DE CONFIRMACIÓN
		public function delete($id) {
			#Comproba que arriba l'id
			if (!$id)
				throw new Exception("No s'ha indicat l'identificador del módulo.");

			#RECUPERAR EL MÓDULO
			$objModulo=Modulo::getOne($id);

			#COMPROBAR QUE EL MÓDULO EXISTE
			if (!$objModulo)
				throw new Exception("No existeix el módulo con ID '$id'.", 1);
			
			FC::getUserView("modulo/frmDelete", $objModulo);
		}

		#ELIMINA EL MÓDULO
		public function destroy() {
			# RECUPEAR L'ID VIA POST
			$id = (int)$_POST["id"];
			$strModulo=(string)$_POST["nombre"];
			$strCodigo=(string)$_POST["codigo"];
			
			if (!Modulo::delete($id))
				throw new Exception("No s'ha pogut esborrar el módulo<br>'$strModulo'.");
			
			# MOSTRA LA VISTA
			$arrMsg = ["Success","Módulo<br>'$strModulo' amb codi '$strCodigo'<br>borrado correcamente.","module"];
			
			FC::getUserView("frontSuccessError", $arrMsg);
		}
	}