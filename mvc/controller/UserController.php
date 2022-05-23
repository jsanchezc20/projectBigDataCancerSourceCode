<?php
class UserController {
	# MÈTODE PER DEFECTE
	public function index() {
		# LLISTAT D'USUARIS
		$this->list();
	}

	# LLISTAT D'USUARIS
	public function list() {
		# RECUPERA LA LLISTA D'USUARIS
		$objUsers = UserModel::getAll();

		if (empty($objUsers))
			throw new Exception("No hi ha registres per a mostrar.");

		FC::getUserView("user/list", $objUsers);
	}

	# MOSTRA UN USUARI
	public function read(int $id = 0) {
		# COMPROBA QUE ARRIBA EL CODI
		if (!$id)
			throw new Exception("No s'ha indicat l'identificador de l'usuari.");
		
		# RECUPERA L'USUARI
		$objUser = UserModel::getOne($id);

		# COMPROBA QUE L'USUARI EXISTEIX
		if (!$objUser)
			throw new Exception("No existeixix l'usuari amb identificador '$id'.");

		FC::getUserView("user/details", $objUser);
	}

	# OBTÉ LA DESCRIPCIÓ DE LA TAULA PER MUNTAR EL FORMULARI
	public function create() {

		$strSQL = "DESCRIBE users;";
		
		$objUsers = DB::selectAll($strSQL);

		FC::getUserView("user/frmNew", $objUsers);
	}

	# CREA I GUARDA EL NOU USUARI AMB DADES POST
	public function store() {
		# CREA L'OBJECTE
		$objUser = new UserModel();

		# GUARDA LES DADES EN L'OBJECTE EN FUNCIÓ
		# DE LES CLAUS DEL VECTOR ASSOCIATIU $_POST 
		foreach ($_POST as $key1 => $value1) {
			foreach ($objUser as $key2 => $value2) {
				if ($key1 == $key2) {
					$objUser->$key2=$value1;
					break;
				}
			}
		}

		# GUARDA A LA BD
		$id = $objUser->insert();

		if ($id)
			$arrMsg = ["Success","Usuari '$objUser->user' insertat correctament.","user"];
		else
			$arrMsg = ["Error","No s'ha pogut guardar l'usuari'$objUser->user'.<br>
						No pot haver-hi una clau duplicada.","user"];

		FC::getUserView("frontSuccessError", $arrMsg);
	}

	# ACTUALITZA I GUARDA ELS CANVIS DE L'USUARI
	public function update(int $id = 0) {
		# COMPROBA QUE ARRIBA L'ID
		if (!$id)
			throw new Exception("No s'ha indicat l'identificador de l'usuari.");

		# RECUPERA L'USUARI
		$objUser = UserModel::getOne($id);

		# COMPROBA QUE L'USUARI EXISTEIX
		if (!$objUser)
			throw new Exception("No existeixix l'usuari amb identificador '$id'.");

		# RECUPERA L'USUARI PARA PASARLO A LA VISTA
		# $OBJUSER = USERLOGINMODEL::GETUSER();

		FC::getUserView("user/frmUpdate", $objUser);
	}

	public function edit() {
		if (empty($_POST["actualizar"]))
			throw new Exception("No s'han rebut dades.");

		# CREA L'OBJECTE
		$objUser = new UserModel();

		# GUARDA LES DADES QUE ARRIBEN VIA POST
		# EN FUNCIÓ DE L'OBJECTE USUARI
		foreach ($objUser as $key => $value)
			$objUser->$key = is_numeric($value) ? (int)$_POST[$key] : $_POST[$key];

		# GUARDA A LA BD
		$id = $objUser->update();
		if ($id)
			$arrMsg = ["Success","Usuari '$objUser->user' actualitzat correctament.","user"];
		elseif ($id === 0)
			$arrMsg = ["Warning","No s'han fet canvis en l'usuari '$objUser->user'.","user"];
		else
			$arrMsg = ["Error","No s'ha pogut actualitzar l'usuari '$objUser->user'.<br>
								No pot haver-hi una clau duplicada.","user"];

		FC::getUserView("frontSuccessError", $arrMsg);
	}

	# MOSTRA EL FORMULARI DE CONFIRMACIÓ
	public function delete($id) {
		# COMPROBA QUE ARRIBA L'ID
		if (!$id)
			throw new Exception("No s'ha indicat l'identificador de l'usuari.");

		# RECUPERA L'USUARI
		$objUser = UserModel::getOne($id);

		# COMPROBA QUE L'USUARI EXISTEIX
		if (!$objUser)
			throw new Exception("No existeixix l'usuari amb identificador '$id'.", 1);
		
		FC::getUserView("user/frmDelete", $objUser);
	}

	# ELIMINA L'USUARI
	public function destroy() {
		# RECUPEAR L'ID VIA POST
		$id = (int) $_POST["id"];
		$strUsuario = (string) $_POST["user"];

		if (!UserModel::delete($id))
			throw new Exception("No s'ha pogut esborrar l'usuario '$strUsuario'.");
		
		# MOSTRA LA VISTA
		$arrMsg = ["Success","Usuari '$strUsuario' esborrat correcament.","user"];
		
		FC::getUserView("frontSuccessError", $arrMsg);
	}

	# COMPROVA NOM D'USUARI EXISTENT
	public function check() {
		$strUser = (string) $_POST["user"];

		$objUser = UserModel::getUserByName($strUser);

		if ($objUser) {
			echo $objUser->name;
		}
	}
}