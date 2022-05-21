<?php
	class Query {
		# MÈTODE PER DEFECTE
		public function index() {
			#LISTADO DE PREGUNTAS
			$this->list();
		}

		#LISTADO DE PREGUNTAS
		public function list() {
			#RECUPERA LA LISTA DE PREGUNTAS
			$objPreguntas=Pregunta::getAll();

			if (empty($objPreguntas))
				throw new Exception("No hi ha registres per mostrar.");

			FC::getUserView("pregunta/list", $objPreguntas);
		}

		#MUESTRA UNA PREGUNTA
		public function read(int $id = 0) {
			# COMPROBA QUE ARRIBA EL CODI
			if (!$id)
				throw new Exception("No s'ha indicat l'identificador de la pregunta.");
			
			#RECUPERA LA PREGUNTA
			$objPregunta=Pregunta::getOne($id);

			#COMPRUEBA QUE LA PREGUNTA EXISTE
			if (!$objPregunta)
				throw new Exception("No existeix la pregunta con ID '$id'.");

			FC::getUserView("pregunta/details", $objPregunta);
		}

		# OBTÉ LA DESCRIPCIÓ DE LA TAULA PER MUNTAR EL FORMULARI
		public function create() {

			$strSQL = "DESCRIBE preguntas;";
			
			$objPreguntas=DB::selectAll($strSQL);

			FC::getUserView("pregunta/frmNew", $objPreguntas);
		}

		#CREA Y GUARDA LA NUEVA PREGUNTA CON DATOS POST
		public function store() {
			# CREA L'OBJECTE
			$objPregunta=new Pregunta();

			#GUARDA LOS DATOS EN EL OBJETO EN FUNCIÓN
			#DE LAS CLAVES DEL ARRAY ASOCIATIVO $_POST 
			foreach ($_POST as $key1 => $value1) {
				foreach ($objPregunta as $key2 => $value2) {
					if ($key1==$key2) {
						$objPregunta->$key2=$value1;
						break;
					}
				}
			}
			$intModuloId=(int)$_POST["selModuloId"];
			# GUARDA A LA BD
			$id=$objPregunta->insert($intModuloId);

			if ($id)
				$arrMsg = ["Success","Pregunta<br>'$objPregunta->enunciado' insertada correctamente.","query"];
			else
				$arrMsg = ["Error","No se pudo guardar la pregunta<br>'$objPregunta->enunciado'.<br>No pot haver-hi una clau duplicada.","query"];

			FC::getUserView("frontSuccessError", $arrMsg);
		}

		#ACTUALIZA Y GUARDA LOS CAMBIOS DE LA PREGUNTA
		public function update(int $id = 0) {
			# COMPROBA QUE ARRIBA L'ID
			if (!$id)
				throw new Exception("No s'ha indicat l'identificador de la pregunta.");

			#RECUPERA LA PREGUNTA
			$objPregunta=Pregunta::getOne($id);

			#COMPRUEBA QUE LA PREGUNTA EXISTE
			if (!$objPregunta)
				throw new Exception("No existeix la pregunta con ID '$id'.");

			# RECUPERA L'USUARI PARA PASARLO A LA VISTA
			#$objUser = UserLoginModel::getUser();

			FC::getUserView("pregunta/frmUpdate", $objPregunta);
		}

		public function update() {
			if (empty($_POST["actualizar"]))
				throw new Exception("No s'han rebut dades.");

			# CREA L'OBJECTE
			$objPregunta=new Pregunta();

			#GUARDA LOS DATOS QUE LLEGAN VÍA POST
			#EN FUNCIÓN DEL OBJETO Pregunta
			foreach ($objPregunta as $key => $value)
				$objPregunta->$key=is_numeric($value)?(int)$_POST[$key]:$_POST[$key];

			# GUARDA A LA BD
			$id=$objPregunta->update();
			if ($id)
				$arrMsg = ["Success","Pregunta<br>'$objPregunta->enunciado'<br>actualizada correctamente.","query"];
			elseif ($id=== 0)
				$arrMsg = ["Warning","No s'han realitzat canvis en la pregunta<br>'$objPregunta->enunciado'.","query"];
			else
				$arrMsg = ["Error","No s'ha pogut actualitzar la pregunta<br>'$objPregunta->enunciado'.<br>No pot haver-hi una clau duplicada.","query"];

			FC::getUserView("frontSuccessError", $arrMsg);
		}

		#MUESTRA EL FORMULARIO DE CONFIRMACIÓN
		public function delete($id) {
			#Comproba que arriba l'id
			if (!$id)
				throw new Exception("No s'ha indicat l'identificador de la pregunta.");

			#RECUPERAR LA PREGUNTA
			$objPregunta=Pregunta::getOne($id);

			#COMPROBAR QUE LA PREGUNTA EXISTE
			if (!$objPregunta)
				throw new Exception("No existeix la pregunta con ID '$id'.", 1);
			
			FC::getUserView("pregunta/frmDelete", $objPregunta);
		}

		#ELIMINA LA PREGUNTA
		public function destroy() {
			# RECUPEAR L'ID VIA POST
			$id = (int)$_POST["id"];
			$strPregunta=(string)$_POST["enunciado"];

			if (!Pregunta::delete($id))
				throw new Exception("No s'ha pogut esborrar la pregunta<br>'$strPregunta'.");
			
			# MOSTRA LA VISTA
			$arrMsg = ["Success","Pregunta<br>'$strPregunta'<br>borrada correcamente.","query"];
			
			FC::getUserView("frontSuccessError", $arrMsg);
		}
	}