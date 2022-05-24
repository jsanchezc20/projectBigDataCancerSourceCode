<?php
class DiseaseController {
	# MÈTODE PER DEFECTE
	public function index() {
		# LLISTAT DE DISEASES
		$this->list();
	}

	# LLISTAT DE DISEASES
	public function list() {
		# RECUPERA LA LLISTA DE DISEASES
		$objAccess = DiseaseModel::getAll();

		if (empty($objAccess))
			throw new Exception("No hi ha registres per mostrar.");

		FC::getUserView("diseases/list", $objAccess);
	}

	# MOSTRA UN DISEASES
	public function read(int $id = 0) {
		# COMPROBA QUE ARRIBA EL CODI
		if (!$id)
			throw new Exception("No s'ha indicat l'identificador de l'acció.");
		
		# RECUPERA DISEASES
		$objAccion = DiseaseModel::getOne($id);

		# COMPROBA QUE DISEASES EXISTEIX
		if (!$objAccion)
			throw new Exception("No existeixix l'acció amb identificador '$id'.");

		FC::getUserView("diseases/details", $objAccion);
	}

	# OBTÉ LA DESCRIPCIÓ DE LA TAULA PER MUNTAR EL FORMULARI
	public function create() {
		if (!UserLoginModel::isAdmin())
			throw new Exception('No tens permis');
		
		$strSQL = "DESCRIBE diseases;";
		
		$objDiseases=DB::selectAll($strSQL);

		FC::getUserView("diseases/frmNew", $objDiseases);
	}

	# CREA I GUARDA EL NOU DISEASES AMB DADES POST
	public function store() {
		# CREA L'OBJECTE
		$objDiseases=new Accion();

		# GUARDA LES DADES EN L'OBJECTE EN FUNCIÓ
		# DE LES CLAUS DEL VECTOR ASSOCIATIU $_POST 
		foreach ($_POST as $key1 => $value1) {
			foreach ($objDiseases as $key2 => $value2) {
				if ($key1==$key2) {
					$objDiseases->$key2=$value1;
					break;
				}
			}
		}

		# GUARDA A LA BD
		$id=$objAccion->insert();

		if ($id)
			$arrMsg = ["Success","Disease<br>'$objDiseases->nombre' amb codi '$objDiseases->codigo'<br>insertat correctament.","action"];
		else
			$arrMsg = ["Error","No s'ha pogut guardar diseases<br>'$objDiseases->nombre' amb codi '$objDiseases->codigo'.<br>
						No pot haver-hi una clau duplicada.","action"];

		FC::getUserView("frontSuccessError", $arrMsg);
	}

	# ACTUALITZA I GUARDA ELS CANVIS DE DISEASES
	public function update(int $id = 0) {
		# COMPROBA QUE ARRIBA L'ID
		if (!$id)
			throw new Exception("No s'ha indicat l'identificador de l'accés.");

		# RECUPERA DISEASES
		$objDiseases=Diseases::getOne($id);

		# COMPROBA QUE DISEASES EXISTEIX
		if (!$objDiseases)
			throw new Exception("No existeixix l'accés amb identificador '$id'.");

		# RECUPERA L'USUARI PER PASSAR-HO A LA VISTA
		# $OBJUSER = USERLOGINMODEL::GETUSER();

		FC::getUserView("diseases/frmUpdate", $objDiseases);
	}

	public function edit() {
		if (empty($_POST["actualizar"]))
			throw new Exception("No s'han rebut dades.");

		# CREA L'OBJECTE
		$objDiseases=new Diseases();

		# GUARDA LES DADES EN L'OBJECTE EN FUNCIÓ
		# DE LES CLAUS DEL VECTOR ASSOCIATIU $_POST
		//Utils::checkVariable($_POST);
		foreach ($_POST as $key1 => $value1) {
			foreach ($objDiseases as $key2 => $value2) {
				if ($key1==$key2) {
					$objDiseases->$key2=$value1;
					break;
				}
			}
		}
		/*# GUARDA LES DADES QUE ARRIBEN VIA POST
		# EN FUNCIÓ DE L'OBJECTE ¿PREGUNTA?
		foreach ($objDiseases as $key => $value)
			$objDiseases->$key=is_numeric($value)?(int)$_POST[$key]:$_POST[$key];
		*/

		# GUARDA A LA BD
		$id=$objDiseases->update();
		if ($id)
			$arrMsg = ["Success","Diseases<br>'$objDiseases->nombre' amb codi '$objDiseases->codigo'<br>actualitzat correctamente.","action"];
		elseif ($id=== 0)
			$arrMsg = ["Warning","No s'han realitzat canvis en diseases<br>'$objDiseases->nombre' amb codi '$objDiseases->codigo'.","action"];
		else
			$arrMsg = ["Error","No s'ha pogut actualitzar diseases<br>'$objDiseases->nombre' amb codi '$objDiseases->codigo'.<br>
						No pot haver-hi una clau duplicada.","action"];

		FC::getUserView("frontSuccessError", $arrMsg);
	}

	# MOSTRA EL FORMULARI DE CONFIRMACIÓ
	public function delete($id) {
		# COMPROBA QUE ARRIBA L'ID
		if (!$id)
			throw new Exception("No s'ha indicat l'identificador de diseases.");

		# RECUPERA COUNTRIES
		$objDiseases=Diseases::getOne($id);

		# COMPROBA QUE COUNTRIES EXISTEIX
		if (!$objDiseases)
			throw new Exception("No existeix diseases amb identificador '$id'.", 1);
		
		FC::getUserView("diseases/frmDelete", $objDiseases);
	}

	# ELIMINA COUNTRIES
	public function destroy() {
		# RECUPEAR L'ID VIA POST
		$id = (int)$_POST["id"];
		$strDiseases=(string)$_POST["nombre"]; //
		$strCodigo=(string)$_POST["codigo"]; //

		if (!Accion::delete($id))
			throw new Exception("No s'ha pogut esborrar diseases<br>'$strDiseases' amb codi '$strCodigo'."); //
		
		# MOSTRA LA VISTA
		$arrMsg = ["Success","Diseases<br>'$strDiseases' amb codi '$strCodigo'<br>esborrat correcament.","action"]; //
		
		FC::getUserView("frontSuccessError", $arrMsg);
	}
}