<?php
class UserModel {
	### PROPIETATS ###
	public  $user_id	= 0,
			$name		= "",
			$password	= "",
			$access_id	= 0,
			$createdAt	= "",
			$updatedAt	= "";

	### MÈTODES ###
	# IDENTIFICACIÓ D'USUARI
	public static function identify(string $strUser='',
									string $strPassword='') : ? UserModel {
		$strSQL = " SELECT *
					FROM users
					WHERE name = ?;";

		$objRS = DB::selectOne($strSQL, self::class, [$strUser]);

		if ($objRS && password_verify($strPassword, $objRS->password)) {
			return $objRS;
		} else {
			return null;
		}
	}

	# SELECT » RETORNA TOTS ELS USUARIS
	public static function getAll() : array {

		$strSQL = "SELECT * FROM users;";

		return DB::selectAll($strSQL, self::class);
	}

	# SELECT » OBTENIR USUARI PER ID
	public static function getOne(int $intId) : ? UserModel {

		$strSQL = "SELECT * FROM users WHERE user_id = ?;";

		return DB::selectOne($strSQL, self::class, [$intId]);
	}

	# SELECT » OBTENIR USUARI PER NOM
	public static function getUserByName(string $strUser) : ? UserModel {

		$strSQL = "SELECT name FROM users WHERE name = ?;";

		return DB::selectOne($strSQL, self::class, [$strUser]);
	}

	# RETORNA USUARIS PER TIPUS D'ACCÉS (ID)
	public function getUsersByAccesId($intId) : array {

		$strSQL = " SELECT *
					FROM users u
					INNER JOIN access a ON u.access_id=a.access_id
					WHERE access_id = ?;";
					
		return DB::selectAll($strSQL, "stdClass", [$intId]);
	}

	# INSERT » CREA NOU USUARI
	public function insert() {

		$strSQL = " INSERT INTO users(name, password, access_id)
					VALUES	(?, ?, ?);";
					
		return DB::insert($strSQL, [$this->name, $this->password, $this->access_id]);
	}

	# UPDATE » ACTUALITZA USUARI EXISTENT PER ID
	public function update() {

		$strSQL = " UPDATE users 
					SET name = ?, password = ?, access_id = ?
					WHERE user_id = ?;";

		return DB::update($strSQL, [$this->name, $this->password, $this->access, $this->user_id]);
	}

	# DELETE » ESBORRA USUARI EXISTENT PER ID
	public static function delete(int $intId) {

		$strSQL = "DELETE FROM users WHERE user_id = ?;";

		return DB::delete($strSQL, [$intId]);
	}

	# RETORNA LA INFORMACIÓ DE L'OBJECTE
	public function __toString() : string {
		return	"user_id:	$this->user_id<br>
				 name:		$this->name<br>
				 access (description): " . DB::selectOne("SELECT description FROM access WHERE access_id=?;", self::class, [$this->access_id]);
	}
}