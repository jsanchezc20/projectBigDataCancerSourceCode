<?php
class AccessController {
	# MÈTODE PER DEFECTE
	public function index() {
		# LLISTAT D'ACCESSOS
		$this->list();
	}

	# LLISTAT D'ACCESSOS
	public function list() {
		# RECUPERA LA LLISTA D'ACCIONS
		$objAccess = AccessModel::getAll();

		if (empty($objAccess))
			throw new Exception("No hi ha registres per mostrar.");

		FC::getUserView("access/list", $objAccess);
	}

	# MOSTRA UN ACCÉS
	public function read(int $id = 0) {
		# COMPROBA QUE ARRIBA EL CODI
		if (!$id)
			throw new Exception("No s'ha indicat l'identificador de l'acció.");
		
		# RECUPERA L'ACCÉS
		$objAccess = AccessModel::getOne($id);

		# COMPROBA QUE L'ACCÉS EXISTEIX
		if (!$objAccess)
			throw new Exception("No existeixix l'acció amb identificador '$id'.");

		FC::getUserView("access/details", $objAccess);
	}

	# OBTÉ LA DESCRIPCIÓ DE LA TAULA PER MUNTAR EL FORMULARI
	public function create() {
		if (!UserLoginModel::isAdmin())
			throw new Exception('No tens permis');
		
		$strSQL = "DESCRIBE access;";
		
		$objAccess=DB::selectAll($strSQL);

		FC::getUserView("access/frmNew", $objAccess);
	}

	# CREA I GUARDA EL NOU ACCÉS MAB DADES POST
	public function store() {
		# CREA L'OBJECTE
		$objAccess=new AccessModel();

		# GUARDA LES DADES EN L'OBJECTE EN FUNCIÓ
		# DE LES CLAUS DEL VECTOR ASSOCIATIU $_POST 
		foreach ($_POST as $key1 => $value1) {
			foreach ($objAccess as $key2 => $value2) {
				if ($key1==$key2) {
					$objAccess->$key2=$value1;
					break;
				}
			}
		}

		# GUARDA A LA BD
		$id=$objAccess->insert();

		if ($id)
			$arrMsg = ["Success","Accés<br>'$objAccess->nombre' amb codi '$objAccess->codigo'<br>insertat correctament.","action"];
		else
			$arrMsg = ["Error","No s'ha pogut guardar l'acciés<br>'$objAccess->nombre' amb codi '$objAccess->codigo'.<br>
						No pot haver-hi una clau duplicada.","action"];

		FC::getUserView("frontSuccessError", $arrMsg);
	}

	# ACTUALITZA I GUARDA ELS CANVIS DE L'ACCÉS
	public function update(int $id = 0) {
		# COMPROBA QUE ARRIBA L'ID
		if (!$id)
			throw new Exception("No s'ha indicat l'identificador de l'accés.");

		# RECUPERA L'ACCÉS
		$objAccess = AccessModel::getOne($id);

		# COMPROBA QUE L'ACCÉS EXISTEIX
		if (!$objAccess)
			throw new Exception("No existeixix l'accés amb identificador '$id'.");

		# RECUPERA L'USUARI PER PASSAR-HO A LA VISTA
		# $OBJUSER = USERLOGINMODEL::GETUSER();

		FC::getUserView("access/frmUpdate", $objAccess);
	}

	public function edit() {
		if (empty($_POST["actualizar"]))
			throw new Exception("No s'han rebut dades.");

		# CREA L'OBJECTE
		$objAccess = new AccessModel();

		# GUARDA LES DADES EN L'OBJECTE EN FUNCIÓ
		# DE LES CLAUS DEL VECTOR ASSOCIATIU $_POST
		//Utils::checkVariable($_POST);
		foreach ($_POST as $key1 => $value1) {
			foreach ($objAccess as $key2 => $value2) {
				if ($key1==$key2) {
					$objAccess->$key2=$value1;
					break;
				}
			}
		}
		/*# GUARDA LES DADES QUE ARRIBEN VIA POST
		# EN FUNCIÓ DE L'OBJECTE ¿PREGUNTA?
		foreach ($objAccess as $key => $value)
			$objAccess->$key=is_numeric($value)?(int)$_POST[$key]:$_POST[$key];
		*/

		# GUARDA A LA BD
		$id=$objAccess->update();
		if ($id)
			$arrMsg = ["Success","Accés<br>'$objAccess->nombre' amb codi '$objAccess->codigo'<br>actualitzat correctamente.","action"];
		elseif ($id=== 0)
			$arrMsg = ["Warning","No s'han realitzat canvis en l'accés<br>'$objAccess->nombre' amb codi '$objAccess->codigo'.","action"];
		else
			$arrMsg = ["Error","No s'ha pogut actualitzar l'accés<br>'$objAccess->nombre' amb codi '$objAccess->codigo'.<br>
						No pot haver-hi una clau duplicada.","action"];

		FC::getUserView("frontSuccessError", $arrMsg);
	}

	# MOSTRA EL FORMULARI DE CONFIRMACIÓ
	public function delete($id) {
		# COMPROBA QUE ARRIBA L'ID
		if (!$id)
			throw new Exception("No s'ha indicat l'identificador de la acción.");

		# RECUPERA L'ACCÉS
		$objAccess = AccessModel::getOne($id);

		# COMPROBA QUE L'ACCÉS EXISTEIX
		if (!$objAccess)
			throw new Exception("No existeix l'accés amb identificador '$id'.", 1);
		
		FC::getUserView("access/frmDelete", $objAccess);
	}

	# ELIMINA L'ACCÉS
	public function destroy() {
		# RECUPEAR L'ID VIA POST
		$id = (int)$_POST["id"];
		$strAccion=(string)$_POST["nombre"];
		$strCodigo=(string)$_POST["codigo"];

		if (!AccessModel::delete($id))
			throw new Exception("No s'ha pogut esborrar l'accés<br>'$strAccion' amb codi '$strCodigo'.");
		
		# MOSTRA LA VISTA
		$arrMsg = ["Success","Accés<br>'$strAccion' amb codi '$strCodigo'<br>esborrat correcament.","action"];
		
		FC::getUserView("frontSuccessError", $arrMsg);
	}
}