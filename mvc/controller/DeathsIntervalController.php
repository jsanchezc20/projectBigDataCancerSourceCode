<?php
class DeathsIntervalController {
	# MÈTODE PER DEFECTE
	public function index() {
		# LLISTAT D'ACCESSOS
		$this->list();
	}

	# LLISTAT D'ACCESSOS
	public function list() {
		# RECUPERA LA LLISTA D'ACCIONS
		$objDeathsIntervals = DeathsIntervalsModel::getAll();

		if (empty($objDeathsIntervals))
			throw new Exception("No hi ha registres per mostrar.");

		FC::getUserView("deathsIntervals/list", $objDeathsIntervals);
	}

	# MOSTRA UN ACCÉS
	public function read(int $id = 0) {
		# COMPROBA QUE ARRIBA EL CODI
		if (!$id)
			throw new Exception("No s'ha indicat l'identificador de l'acció.");

		# RECUPERA L'ACCÉS
		$objDeathsIntervals = DeathsIntervalsModel::getOne($id);

		# COMPROBA QUE L'ACCÉS EXISTEIX
		if (!$objDeathsIntervals)
			throw new Exception("No existeixix l'interval de morts amb identificador '$id'.");

		FC::getUserView("deathsIntervals/details", $objDeathsIntervals);
	}

	# OBTÉ LA DESCRIPCIÓ DE LA TAULA PER MUNTAR EL FORMULARI
	public function create() {
		if (!UserLoginModel::isAdmin())
			throw new Exception('No tens permis');

		$strSQL = "DESCRIBE deaths_Intervals;";

		$objDeathsIntervals=DB::selectAll($strSQL);

		FC::getUserView("deathsIntervals/frmNew", $objDeathsIntervals);
	}

	# CREA I GUARDA EL NOU ACCÉS MAB DADES POST
	public function store() {
		# CREA L'OBJECTE
		$objDeathsIntervals=new DeathsIntervalsModel();

		# GUARDA LES DADES EN L'OBJECTE EN FUNCIÓ
		# DE LES CLAUS DEL VECTOR ASSOCIATIU $_POST
		foreach ($_POST as $key1 => $value1) {
			foreach ($objDeathsIntervals as $key2 => $value2) {
				if ($key1==$key2) {
					$objDeathsIntervals->$key2=$value1;
					break;
				}
			}
		}

		# GUARDA A LA BD
		$id=$objDeathsIntervals->insert();

		if ($id)
			$arrMsg = ["Success","L'interval de morts<br>'$objDeathsIntervals->intervals' amb codi '$objDeathsIntervals->death_interval_code'<br>insertat correctament.","action"];
		else
			$arrMsg = ["Error","No s'ha pogut guardar l'interval de morts<br>'$objDeathsIntervals->intervals' amb codi '$objDeathsIntervals->death_interval_code'.<br>
						No pot tenir una clau duplicada.","action"];

		FC::getUserView("frontSuccessError", $arrMsg);
	}

	# ACTUALITZA I GUARDA ELS CANVIS DE L'ACCÉS
	public function update(int $id = 0) {
		# COMPROBA QUE ARRIBA L'ID
		if (!$id)
			throw new Exception("No s'ha indicat l'identificador de l'interval de morts.");

		# RECUPERA L'ACCÉS
		$objDeathsIntervals = DeathsIntervalsModel::getOne($id);

		# COMPROBA QUE L'ACCÉS EXISTEIX
		if (!$objDeathsIntervals)
			throw new Exception("No existeix l'interval de morts amb identificador '$id'.");

		# RECUPERA L'USUARI PER PASSAR-HO A LA VISTA
		# $OBJUSER = USERLOGINMODEL::GETUSER();

		FC::getUserView("deathsIntervals/frmUpdate", $objDeathsIntervals);
	}

	public function edit() {
		if (empty($_POST["actualizar"]))
			throw new Exception("No s'han rebut dades.");

		# CREA L'OBJECTE
		$objDeathsIntervals = new DeathsIntervalsModel();

		# GUARDA LES DADES EN L'OBJECTE EN FUNCIÓ
		# DE LES CLAUS DEL VECTOR ASSOCIATIU $_POST
		//Utils::checkVariable($_POST);
		foreach ($_POST as $key1 => $value1) {
			foreach ($objDeathsIntervals as $key2 => $value2) {
				if ($key1==$key2) {
					$objDeathsIntervals->$key2=$value1;
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
		$id=$objDeathsIntervals->update();
		if ($id)
			$arrMsg = ["Success","L'interval de morts<br>'$objDeathsIntervals->intervals' amb codi '$objDeathsIntervals->death_interval_code'<br>actualitzat correctament.","action"];
		elseif ($id=== 0)
			$arrMsg = ["Warning","No s'han realitzat canvis en l'interval de morts<br>'$objDeathsIntervals->intervals' amb codi '$objDeathsIntervals->death_interval_code'.","action"];
		else
			$arrMsg = ["Error","No s'ha pogut actualitzar l'interval de morts<br>'$objDeathsIntervals->intervals' amb codi '$objDeathsIntervals->death_interval_code'.<br>
						No pot haver-hi una clau duplicada.","action"];

		FC::getUserView("frontSuccessError", $arrMsg);
	}

	# MOSTRA EL FORMULARI DE CONFIRMACIÓ
	public function delete($id) {
		# COMPROBA QUE ARRIBA L'ID
		if (!$id)
			throw new Exception("No s'ha indicat l'identificador de l'interval de morts'.");

		# RECUPERA L'ACCÉS
		$objDeathsIntervals = DeathsIntervalsModel::getOne($id);

		# COMPROBA QUE L'ACCÉS EXISTEIX
		if (!$objDeathsIntervals)
			throw new Exception("No existeix l'interval de morts amb identificador '$id'.", 1);

		FC::getUserView("deathsIntervals/frmDelete", $objDeathsIntervals);
	}

	# ELIMINA L'ACCÉS
	public function destroy() {
		# RECUPEAR L'ID VIA POST
		$id = (int)$_POST["id"];
		$strNom=(string)$_POST["intervals"];
		$strDeathsIntervalsCode=(string)$_POST["death_interval_code"];

		if (!DeathsIntervalsModel::delete($id))
			throw new Exception("No s'ha pogut esborrar l'interval de morts<br>'$strNom' amb codi '$strDeathsIntervalsCode'.");

		# MOSTRA LA VISTA
		$arrMsg = ["Success","L'interval de morts<br>'$strNom' amb codi '$strDeathsIntervalsCode'<br>esborrat correcament.","action"];

		FC::getUserView("frontSuccessError", $arrMsg);
	}
}