<?php
class CountryController {
	# MÈTODE PER DEFECTE
	public function index() {
		# LLISTAT DE COUNTRIES
		$this->list();
	}

	# LLISTAT DE COUNTRIES
	public function list() {
		# RECUPERA LA LLISTA DE COUNTRIES
		$objCountries = CountryModel::getAll();

		if (empty($objCountries))
			throw new Exception("No hi ha registres per mostrar.");

		FC::getUserView("country/list", $objCountries);
	}

	# MOSTRA UN COUNTRIES
	public function read(int $id = 0) {
		# COMPROBA QUE ARRIBA EL CODI
		if (!$id)
			throw new Exception("No s'ha indicat l'identificador de l'acció.");
		
		# RECUPERA COUNTRIES
		$objCountries = CountryModel::getOne($id);

		# COMPROBA QUE COUNTRIES EXISTEIX
		if (!$objCountries)
			throw new Exception("No existeixix l'acció amb identificador '$id'.");

		FC::getUserView("country/details", $objAccion);
	}

	# OBTÉ LA DESCRIPCIÓ DE LA TAULA PER MUNTAR EL FORMULARI
	public function create() {
		if (!UserLoginModel::isAdmin())
			throw new Exception('No tens permis');
		
		$strSQL = "DESCRIBE countries;";
		
		$objCountries=DB::selectAll($strSQL);

		FC::getUserView("country/frmNew", $objCountries);
	}

	# CREA I GUARDA EL NOU COUNTRIES AMB DADES POST
	public function store() {
		# CREA L'OBJECTE
		$objCountries=new Accion();

		# GUARDA LES DADES EN L'OBJECTE EN FUNCIÓ
		# DE LES CLAUS DEL VECTOR ASSOCIATIU $_POST 
		foreach ($_POST as $key1 => $value1) {
			foreach ($objCountries as $key2 => $value2) {
				if ($key1==$key2) {
					$objCountries->$key2=$value1;
					break;
				}
			}
		}

		# GUARDA A LA BD
		$id=$objAccion->insert();

		if ($id)
			$arrMsg = ["Success","Country<br>'$objCountries->nombre' amb codi '$objCountries->codigo'<br>insertat correctament.","action"];
		else
			$arrMsg = ["Error","No s'ha pogut guardar countries<br>'$objCountries->nombre' amb codi '$objCountries->codigo'.<br>
						No pot haver-hi una clau duplicada.","action"];

		FC::getUserView("frontSuccessError", $arrMsg);
	}

	# ACTUALITZA I GUARDA ELS CANVIS DE COUNTRIES
	public function update(int $id = 0) {
		# COMPROBA QUE ARRIBA L'ID
		if (!$id)
			throw new Exception("No s'ha indicat l'identificador de l'accés.");

		# RECUPERA COUNTRIES
		$objCountries=Countries::getOne($id);

		# COMPROBA QUE COUNTRIES EXISTEIX
		if (!$objCountries)
			throw new Exception("No existeixix l'accés amb identificador '$id'.");

		# RECUPERA L'USUARI PER PASSAR-HO A LA VISTA
		# $OBJUSER = USERLOGINMODEL::GETUSER();

		FC::getUserView("country/frmUpdate", $objCountries);
	}

	public function edit() {
		if (empty($_POST["actualizar"]))
			throw new Exception("No s'han rebut dades.");

		# CREA L'OBJECTE
		$objCountries=new Countries();

		# GUARDA LES DADES EN L'OBJECTE EN FUNCIÓ
		# DE LES CLAUS DEL VECTOR ASSOCIATIU $_POST
		//Utils::checkVariable($_POST);
		foreach ($_POST as $key1 => $value1) {
			foreach ($objCountries as $key2 => $value2) {
				if ($key1==$key2) {
					$objCountries->$key2=$value1;
					break;
				}
			}
		}
		/*# GUARDA LES DADES QUE ARRIBEN VIA POST
		# EN FUNCIÓ DE L'OBJECTE ¿PREGUNTA?
		foreach ($objCountries as $key => $value)
			$objCountries->$key=is_numeric($value)?(int)$_POST[$key]:$_POST[$key];
		*/

		# GUARDA A LA BD
		$id=$objCountries->update();
		if ($id)
			$arrMsg = ["Success","Countries<br>'$objCountries->nombre' amb codi '$objCountries->codigo'<br>actualitzat correctamente.","action"];
		elseif ($id=== 0)
			$arrMsg = ["Warning","No s'han realitzat canvis en countries<br>'$objCountries->nombre' amb codi '$objCountries->codigo'.","action"];
		else
			$arrMsg = ["Error","No s'ha pogut actualitzar countries<br>'$objCountries->nombre' amb codi '$objCountries->codigo'.<br>
						No pot haver-hi una clau duplicada.","action"];

		FC::getUserView("frontSuccessError", $arrMsg);
	}

	# MOSTRA EL FORMULARI DE CONFIRMACIÓ
	public function delete($id) {
		# COMPROBA QUE ARRIBA L'ID
		if (!$id)
			throw new Exception("No s'ha indicat l'identificador de countries.");

		# RECUPERA COUNTRIES
		$objCountries=Countries::getOne($id);

		# COMPROBA QUE COUNTRIES EXISTEIX
		if (!$objCountries)
			throw new Exception("No existeix countries amb identificador '$id'.", 1);
		
		FC::getUserView("country/frmDelete", $objCountries);
	}

	# ELIMINA COUNTRIES
	public function destroy() {
		# RECUPEAR L'ID VIA POST
		$id = (int)$_POST["id"];
		$strCountries=(string)$_POST["nombre"]; //
		$strCodigo=(string)$_POST["codigo"]; //

		if (!Accion::delete($id))
			throw new Exception("No s'ha pogut esborrar countries<br>'$strCountries' amb codi '$strCodigo'."); //
		
		# MOSTRA LA VISTA
		$arrMsg = ["Success","Countries<br>'$strCountries' amb codi '$strCodigo'<br>esborrat correcament.","action"]; //
		
		FC::getUserView("frontSuccessError", $arrMsg);
	}
}