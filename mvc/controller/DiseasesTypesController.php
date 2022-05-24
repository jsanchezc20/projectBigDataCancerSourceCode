<?php
class DiseasesTypesController
{
	# MÈTODE PER DEFECTE
	public function index()
	{
		# LLISTAT D'ACCESSOS
		$this->list();
	}

	# LLISTAT D'ACCESSOS
	public function list()
	{
		# RECUPERA LA LLISTA D'ACCIONS
		$objDiseasesTypes = DiseaseTypeModel::getAll();

		if (empty($objDiseasesTypes))
			throw new Exception("No hi ha registres per mostrar.");

		FC::getUserView("diseasesTypes/list", $objDiseasesTypes);
	}

	# MOSTRA UN DISEASE TYPE
	public function read(int $id = 0)
	{
		# COMPROBA QUE ARRIBA EL CODI
		if (!$id)
			throw new Exception("No s'ha indicat l'identificador d'aquest tipus de càncer.");

		# RECUPERA EL DISEASE TYPE
		$objDiseasesTypes = DiseaseTypeModel::getOne($id);

		# COMPROBA QUE EL DISEASE TYPE EXISTEIX
		if (!$objDiseasesTypes)
			throw new Exception("No existeix el tipus de càncer amb identificador '$id'.");

		FC::getUserView("diseasesTypes/details", $objDiseasesTypes);
	}

	# OBTÉ LA DESCRIPCIÓ DE LA TAULA PER MUNTAR EL FORMULARI
	public function create()
	{
		if (!UserLoginModel::isAdmin())
			throw new Exception('No tens permis');

		$strSQL = "DESCRIBE diseases_types;";

		$objDiseasesTypes = DB::selectAll($strSQL);

		FC::getUserView("diseasesTypes/frmNew", $objDiseasesTypes);
	}

	# CREA I GUARDA EL NOU ACCÉS MAB DADES POST
	public function store()
	{
		# CREA L'OBJECTE
		$objDiseasesTypes = new DiseaseTypeModel();

		# GUARDA LES DADES EN L'OBJECTE EN FUNCIÓ
		# DE LES CLAUS DEL VECTOR ASSOCIATIU $_POST
		foreach ($_POST as $key1 => $value1) {
			foreach ($objDiseasesTypes as $key2 => $value2) {
				if ($key1 == $key2) {
					$objDiseasesTypes->$key2 = $value1;
					break;
				}
			}
		}

		# GUARDA A LA BD
		$id = $objDiseasesTypes->insert();

		if ($id)
			$arrMsg = ["Success", "Disease Type<br>'$objDiseasesTypes->nombre' amb codi '$objDiseasesTypes->disease_type_code'<br>insertat correctament.", "action"];
		else
			$arrMsg = ["Error", "No s'ha pogut guardar el tipus de càncer<br>'$objDiseasesTypes->nombre' amb codi '$objDiseasesTypes->disease_type_code'.<br>
						No pot haver-hi una clau duplicada.", "action"];

		FC::getUserView("frontSuccessError", $arrMsg);
	}

	# ACTUALITZA I GUARDA ELS CANVIS DE EL DISEASE TYPE
	public function update(int $id = 0)
	{
		# COMPROBA QUE ARRIBA L'ID
		if (!$id)
			throw new Exception("No s'ha indicat l'identificador del tipus de càncer.");

		# RECUPERA EL DISEASE TYPE
		$objDiseasesTypes = DiseaseTypeModel::getOne($id);

		# COMPROBA QUE EL DISEASE TYPE EXISTEIX
		if (!$objDiseasesTypes)
			throw new Exception("No existeix el tipus de càncer amb identificador '$id'.");

		# RECUPERA L'USUARI PER PASSAR-HO A LA VISTA
		# $OBJUSER = USERLOGINMODEL::GETUSER();

		FC::getUserView("diseasesTypes/frmUpdate", $objDiseasesTypes);
	}

	public function edit()
	{
		if (empty($_POST["actualizar"]))
			throw new Exception("No s'han rebut dades.");

		# CREA L'OBJECTE
		$objDiseasesTypes = new DiseaseTypeModel();

		# GUARDA LES DADES EN L'OBJECTE EN FUNCIÓ
		# DE LES CLAUS DEL VECTOR ASSOCIATIU $_POST
		//Utils::checkVariable($_POST);
		foreach ($_POST as $key1 => $value1) {
			foreach ($objDiseasesTypes as $key2 => $value2) {
				if ($key1 == $key2) {
					$objDiseasesTypes->$key2 = $value1;
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
		$id = $objDiseasesTypes->update();
		if ($id)
			$arrMsg = ["Success", "El tipus de càncer<br>'$objDiseasesTypes->name' amb codi '$objDiseasesTypes->disease_type_code'<br>actualitzat correctamente.", "action"];
		elseif ($id === 0)
			$arrMsg = ["Warning", "No s'han realitzat canvis en el tipus de càncer<br>'$objDiseasesTypes->name' amb codi '$objDiseasesTypes->disease_type_code'.", "action"];
		else
			$arrMsg = ["Error", "No s'ha pogut actualitzar el tipus de càncer<br>'$objDiseasesTypes->name' amb codi '$objDiseasesTypes->disease_type_code'.<br>
						No pot haver-hi una clau duplicada.", "action"];

		FC::getUserView("frontSuccessError", $arrMsg);
	}

	# MOSTRA EL FORMULARI DE CONFIRMACIÓ
	public function delete($id)
	{
		# COMPROBA QUE ARRIBA L'ID
		if (!$id)
			throw new Exception("No s'ha indicat l'identificador del tipus de càncer.");

		# RECUPERA L'ACCÉS
		$objDiseasesTypes = DiseaseTypeModel::getOne($id);

		# COMPROBA QUE L'ACCÉS EXISTEIX
		if (!$objDiseasesTypes)
			throw new Exception("No existeix el tipus de càncer amb identificador '$id'.", 1);

		FC::getUserView("diseasesTypes/frmDelete", $objDiseasesTypes);
	}

	# ELIMINA EL TIPUS DE CÀNCER
	public function destroy()
	{
		# RECUPEAR L'ID VIA POST
		$id = (int)$_POST["id"];
		$strName = (string)$_POST["name"];
		$strDiseasesTypeCode = (string)$_POST["disease_type_code"];

		if (!DiseaseTypeModel::delete($id))
			throw new Exception("No s'ha pogut esborrar el tipus de càncer<br>'$strName' amb codi '$strDiseasesTypeCode'.");

		# MOSTRA LA VISTA
		$arrMsg = ["Success", "El tipus de càncer<br>'$strName' amb codi '$strDiseasesTypeCode'<br>esborrat correctament.", "action"];

		FC::getUserView("frontSuccessError", $arrMsg);
	}
}
